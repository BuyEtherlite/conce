<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\ServiceType;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceTeam;
use App\Models\Department;
use App\Models\ServiceRequestAttachment;
use App\Models\ServiceRequestUpdate;
use App\Models\ServiceRequestStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['customer', 'serviceType', 'assignedUser', 'assignedTeam']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        $serviceRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total_requests' => ServiceRequest::count(),
            'pending_requests' => ServiceRequest::whereIn('status', ['submitted', 'acknowledged'])->count(),
            'in_progress_requests' => ServiceRequest::where('status', 'in_progress')->count(),
            'completed_requests' => ServiceRequest::where('status', 'completed')->count(),
        ];

        $serviceTypes = ServiceType::where('is_active', true)->get();

        return view('service-requests.index', compact('serviceRequests', 'stats', 'serviceTypes'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $departments = Department::all();
        $customers = Customer::where('status', 'active')->get();

        return view('service-requests.create', compact('serviceTypes', 'departments', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent,emergency',
            'location_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:100',
            'is_emergency' => 'boolean',
            'attachments.*' => 'file|max:10240', // 10MB max

            // Customer details if creating new customer
            'customer_name' => 'required_without:customer_id|string|max:100',
            'customer_email' => 'required_without:customer_id|email|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Create customer if not exists
            if (!$validated['customer_id'] && $request->filled('customer_name')) {
                $customer = Customer::create([
                    'full_name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'phone' => $validated['customer_phone'] ?? null,
                    'address' => $validated['customer_address'] ?? null,
                    'status' => 'active'
                ]);
                $validated['customer_id'] = $customer->id;
            }

            // Get service type for SLA calculation
            $serviceType = ServiceType::find($validated['service_type_id']);
            $validated['department_id'] = $serviceType->department_id;
            $validated['expected_completion_date'] = now()->addHours($serviceType->sla_hours ?? 24);
            $validated['estimated_cost'] = $serviceType->estimated_cost ?? 0;

            // Generate request number
            $validated['request_number'] = 'SR-' . date('Y') . '-' . str_pad(ServiceRequest::count() + 1, 6, '0', STR_PAD_LEFT);
            $validated['status'] = 'submitted';
            $validated['requested_date'] = now();

            $serviceRequest = ServiceRequest::create($validated);

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('service-requests/' . $serviceRequest->id, 'public');

                    ServiceRequestAttachment::create([
                        'service_request_id' => $serviceRequest->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => auth()->id() ?? 1,
                    ]);
                }
            }

            // Create status history
            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'previous_status' => null,
                'new_status' => 'submitted',
                'changed_by' => auth()->id() ?? 1,
                'notes' => 'Service request submitted'
            ]);

            // Create initial update
            ServiceRequestUpdate::create([
                'service_request_id' => $serviceRequest->id,
                'message' => 'Service request has been submitted and is awaiting processing.',
                'is_public' => true,
                'created_by' => auth()->id() ?? 1,
                'update_type' => 'info'
            ]);

            DB::commit();

            return redirect()->route('service-requests.show', $serviceRequest)
                ->with('success', 'Service request submitted successfully! Request Number: ' . $serviceRequest->request_number);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Failed to create service request: ' . $e->getMessage());
        }
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'customer',
            'serviceType',
            'assignedUser',
            'assignedTeam',
            'department',
            'attachments.uploadedBy',
            'statusHistory.changedBy',
            'updates.createdBy',
            'escalations.escalatedFrom',
            'escalations.escalatedTo',
            'escalations.escalatedBy'
        ]);

        return view('service-requests.show', compact('serviceRequest'));
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $departments = Department::all();
        $customers = Customer::where('status', 'active')->get();
        $teams = ServiceTeam::where('is_active', true)->get();

        return view('service-requests.edit', compact('serviceRequest', 'serviceTypes', 'departments', 'customers', 'teams'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent,emergency',
            'location_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:100',
            'status' => 'required|in:submitted,acknowledged,assigned,in_progress,on_hold,completed,closed,cancelled',
            'assigned_team_id' => 'nullable|exists:service_teams,id',
            'resolution_notes' => 'nullable|string',
        ]);

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update($validated);

        // Create status history if status changed
        if ($oldStatus !== $validated['status']) {
            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'previous_status' => $oldStatus,
                'new_status' => $validated['status'],
                'changed_by' => auth()->id() ?? 1,
                'notes' => 'Status updated via edit form'
            ]);

            // Set completion date if completed
            if ($validated['status'] === 'completed') {
                $serviceRequest->update(['actual_completion_date' => now()]);
            }
        }

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Service request updated successfully.');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();

        return redirect()->route('service-requests.index')
            ->with('success', 'Service request deleted successfully.');
    }

    public function assign(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'assigned_team_id' => 'required|exists:service_teams,id',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $serviceRequest->status;

        $serviceRequest->update([
            'assigned_team_id' => $validated['assigned_team_id'],
            'status' => 'assigned',
        ]);

        // Create status history
        ServiceRequestStatusHistory::create([
            'service_request_id' => $serviceRequest->id,
            'previous_status' => $oldStatus,
            'new_status' => 'assigned',
            'changed_by' => auth()->id() ?? 1,
            'notes' => $validated['notes'] ?? 'Service request assigned to team'
        ]);

        // Create update
        if ($validated['notes']) {
            ServiceRequestUpdate::create([
                'service_request_id' => $serviceRequest->id,
                'message' => $validated['notes'],
                'is_public' => true,
                'created_by' => auth()->id() ?? 1,
                'update_type' => 'assignment'
            ]);
        }

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Service request assigned successfully.');
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:submitted,acknowledged,assigned,in_progress,on_hold,completed,closed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $serviceRequest->status;

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'completed') {
            $updateData['actual_completion_date'] = now();
        }

        $serviceRequest->update($updateData);

        // Create status history
        ServiceRequestStatusHistory::create([
            'service_request_id' => $serviceRequest->id,
            'previous_status' => $oldStatus,
            'new_status' => $validated['status'],
            'changed_by' => auth()->id() ?? 1,
            'notes' => $validated['notes'] ?? 'Status updated'
        ]);

        // Create update if notes provided
        if ($validated['notes']) {
            ServiceRequestUpdate::create([
                'service_request_id' => $serviceRequest->id,
                'message' => $validated['notes'],
                'is_public' => true,
                'created_by' => auth()->id() ?? 1,
                'update_type' => 'status_change'
            ]);
        }

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Service request status updated successfully.');
    }
}