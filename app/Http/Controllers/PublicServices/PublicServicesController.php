<?php

namespace App\Http\Controllers\PublicServices;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use App\Models\ServiceTeam;
use App\Models\ServiceRequestAttachment;
use App\Models\ServiceRequestUpdate;
use App\Models\ServiceRequestStatusHistory;
use App\Models\ServiceRequestEscalation;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PublicServicesController extends Controller
{
    public function index()
    {
        $stats = [
            'total_requests' => ServiceRequest::count(),
            'pending_requests' => ServiceRequest::where('status', 'submitted')->count(),
            'in_progress_requests' => ServiceRequest::where('status', 'in_progress')->count(),
            'completed_today' => ServiceRequest::where('status', 'completed')
                ->whereDate('actual_completion_date', today())->count(),
            'overdue_requests' => ServiceRequest::where('expected_completion_date', '<', now())
                ->whereNotIn('status', ['completed', 'closed', 'cancelled'])->count(),
            'emergency_requests' => ServiceRequest::where('is_emergency', true)
                ->whereNotIn('status', ['completed', 'closed', 'cancelled'])->count(),
            'avg_resolution_time' => ServiceRequest::where('status', 'completed')
                ->whereNotNull('actual_completion_date')
                ->selectRaw('AVG(DATEDIFF(actual_completion_date, requested_date)) as avg_days')
                ->value('avg_days') ?? 0,
            'satisfaction_rate' => ServiceRequest::whereNotNull('satisfaction_rating')
                ->avg('satisfaction_rating') ?? 0
        ];

        $recentRequests = ServiceRequest::with(['customer', 'serviceType', 'assignedUser'])
            ->latest()
            ->limit(10)
            ->get();

        $statusDistribution = ServiceRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $priorityDistribution = ServiceRequest::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        return view('public-services.index', compact(
            'stats',
            'recentRequests',
            'statusDistribution',
            'priorityDistribution'
        ));
    }

    public function requests(Request $request)
    {
        $query = ServiceRequest::with(['customer', 'serviceType', 'assignedUser', 'assignedTeam', 'department']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type_id', $request->service_type);
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('is_emergency')) {
            $query->where('is_emergency', $request->is_emergency);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('requested_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('requested_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('full_name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $serviceRequests = $query->paginate(20);

        // Filter options
        $serviceTypes = ServiceType::where('active', true)->get();
        $departments = Department::all();
        $users = User::all();

        return view('public-services.requests.index', compact(
            'serviceRequests',
            'serviceTypes',
            'departments',
            'users'
        ));
    }

    public function createRequest()
    {
        $serviceTypes = ServiceType::where('active', true)->with('department')->get();
        $customers = Customer::where('status', 'active')->get();
        $departments = Department::all();

        return view('public-services.requests.create', compact(
            'serviceTypes',
            'customers',
            'departments'
        ));
    }

    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'location_address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'ward_number' => 'nullable|string|max:20',
            'priority' => 'required|in:low,medium,high,urgent,emergency',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:100',
            'is_emergency' => 'boolean',
            'source' => 'required|in:website,phone,email,walk_in,mobile_app,social_media',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',

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
            $validated['expected_completion_date'] = now()->addHours($serviceType->sla_hours);
            $validated['estimated_cost'] = $serviceType->estimated_cost;

            // Generate request number
            $validated['request_number'] = 'SR-' . date('Y') . '-' . str_pad(ServiceRequest::count() + 1, 6, '0', STR_PAD_LEFT);

            // Set initial status
            $validated['status'] = 'submitted';
            $validated['requested_date'] = now();

            $serviceRequest = ServiceRequest::create($validated);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('service-attachments', 'public');

                    ServiceRequestAttachment::create([
                        'service_request_id' => $serviceRequest->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => auth()->id() ?? 1
                    ]);
                }
            }

            // Create initial status history
            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'from_status' => null,
                'to_status' => 'submitted',
                'changed_by' => auth()->id() ?? 1,
                'notes' => 'Service request submitted',
                'changed_at' => now()
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

            return redirect()->route('public-services.requests.show', $serviceRequest)
                ->with('success', 'Service request submitted successfully! Request Number: ' . $serviceRequest->request_number);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Failed to create service request: ' . $e->getMessage());
        }
    }

    public function showRequest(ServiceRequest $serviceRequest)
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

        return view('public-services.requests.show', compact('serviceRequest'));
    }

    public function updateRequestStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:submitted,acknowledged,assigned,in_progress,on_hold,completed,closed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_team_id' => 'nullable|exists:service_teams,id',
            'resolution_notes' => 'nullable|string',
            'actual_cost' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $serviceRequest->status;

            // Update service request
            $updateData = ['status' => $validated['status']];

            if (isset($validated['assigned_to'])) {
                $updateData['assigned_to'] = $validated['assigned_to'];
            }

            if (isset($validated['assigned_team_id'])) {
                $updateData['assigned_team_id'] = $validated['assigned_team_id'];
            }

            if (isset($validated['resolution_notes'])) {
                $updateData['resolution_notes'] = $validated['resolution_notes'];
            }

            if (isset($validated['actual_cost'])) {
                $updateData['actual_cost'] = $validated['actual_cost'];
            }

            if ($validated['status'] === 'completed') {
                $updateData['actual_completion_date'] = now();
            }

            $serviceRequest->update($updateData);

            // Create status history
            ServiceRequestStatusHistory::create([
                'service_request_id' => $serviceRequest->id,
                'from_status' => $oldStatus,
                'to_status' => $validated['status'],
                'changed_by' => auth()->id(),
                'notes' => $validated['notes'],
                'changed_at' => now()
            ]);

            // Create update
            $message = "Status changed from " . ucfirst(str_replace('_', ' ', $oldStatus)) .
                      " to " . ucfirst(str_replace('_', ' ', $validated['status']));

            if ($validated['notes']) {
                $message .= ". Notes: " . $validated['notes'];
            }

            ServiceRequestUpdate::create([
                'service_request_id' => $serviceRequest->id,
                'message' => $message,
                'is_public' => true,
                'created_by' => auth()->id(),
                'update_type' => 'info'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Service request status updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function services()
    {
        $serviceTypes = ServiceType::with('department')->get();
        return view('public-services.services.index', compact('serviceTypes'));
    }

    public function community()
    {
        return view('public-services.community.index');
    }

    public function citizen()
    {
        return view('public-services.citizen.index');
    }

    public function information()
    {
        return view('public-services.information.index');
    }

    public function analytics()
    {
        $analytics = [
            'monthly_requests' => ServiceRequest::selectRaw('MONTH(requested_date) as month, COUNT(*) as count')
                ->whereYear('requested_date', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month'),

            'resolution_times' => ServiceRequest::where('status', 'completed')
                ->selectRaw('AVG(DATEDIFF(actual_completion_date, requested_date)) as avg_days, service_type_id')
                ->groupBy('service_type_id')
                ->with('serviceType')
                ->get(),

            'department_performance' => ServiceRequest::selectRaw('department_id, COUNT(*) as total,
                AVG(CASE WHEN status = "completed" THEN DATEDIFF(actual_completion_date, requested_date) END) as avg_resolution')
                ->groupBy('department_id')
                ->with('department')
                ->get()
        ];

        return view('public-services.analytics.index', compact('analytics'));
    }
}