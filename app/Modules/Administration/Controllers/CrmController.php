<?php

namespace App\Modules\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceRequest;
use App\Models\Communication;
use App\Models\Council;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
    use \App\Http\Controllers\ModuleAccessHelper;

    public function index()
    {
        if (($response = $this->requireModuleAccess('administration')) !== null) {
            return $response;
        }
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('active', true)->count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'completed_requests' => ServiceRequest::where('status', 'completed')->count(),
            'communications_today' => Communication::whereDate('created_at', today())->count()
        ];

        $recent_customers = Customer::with(['council', 'department'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recent_requests = ServiceRequest::with(['customer', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('administration.crm.index', compact('stats', 'recent_customers', 'recent_requests'));
    }

    // Customer Management
    public function customers()
    {
        $customers = Customer::with(['council', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('administration.crm.customers.index', compact('customers'));
    }

    public function createCustomer()
    {
        $councils = Council::all();
        $departments = Department::all();

        return view('administration.crm.customers.create', compact('councils', 'departments'));
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:20',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'id_number' => 'required|string|max:20|unique:customers',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'physical_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'nationality' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'employer' => 'nullable|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            'council_id' => 'nullable|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id',
            'notes' => 'nullable|string'
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('administration.customers.index')
            ->with('success', 'Customer created successfully! Customer Number: ' . $customer->customer_number);
    }

    public function showCustomer(Customer $customer)
    {
        $customer->load(['council', 'department', 'serviceRequests.assignedUser', 'communications']);

        return view('administration.crm.customers.show', compact('customer'));
    }

    public function editCustomer(Customer $customer)
    {
        $councils = Council::all();
        $departments = Department::all();

        return view('administration.crm.customers.edit', compact('customer', 'councils', 'departments'));
    }

    public function updateCustomer(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:20',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'id_number' => 'required|string|max:20|unique:customers,id_number,' . $customer->id,
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'physical_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'nationality' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'employer' => 'nullable|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'council_id' => 'nullable|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('administration.customers.show', $customer)
            ->with('success', 'Customer updated successfully!');
    }

    // Service Requests
    public function serviceRequests()
    {
        $serviceRequests = ServiceRequest::with(['customer', 'assignedUser', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('administration.crm.service-requests.index', compact('serviceRequests'));
    }

    public function createServiceRequest()
    {
        $customers = Customer::where('active', true)->get();
        $users = User::all();
        $councils = Council::all();
        $departments = Department::all();

        return view('administration.crm.service-requests.create', compact('customers', 'users', 'councils', 'departments'));
    }

    public function storeServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'requested_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after:requested_date',
            'assigned_to' => 'nullable|exists:users,id',
            'council_id' => 'nullable|exists:councils,id',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $serviceRequest = ServiceRequest::create($validated);

        return redirect()->route('administration.service-requests.index')
            ->with('success', 'Service request created successfully! Request Number: ' . $serviceRequest->request_number);
    }

    public function showServiceRequest(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['customer', 'assignedUser', 'department', 'communications']);

        return view('administration.crm.service-requests.show', compact('serviceRequest'));
    }

    public function updateServiceRequestStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
            'resolution_notes' => 'nullable|string'
        ]);

        if ($validated['status'] === 'completed') {
            $validated['actual_completion_date'] = now()->toDateString();
        }

        $serviceRequest->update($validated);

        return redirect()->back()->with('success', 'Service request status updated successfully!');
    }

    // Communications
    public function communications()
    {
        $communications = Communication::with(['customer', 'serviceRequest', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('administration.crm.communications.index', compact('communications'));
    }

    public function createCommunication()
    {
        $customers = Customer::where('active', true)->get();
        $serviceRequests = ServiceRequest::whereIn('status', ['pending', 'in_progress'])->get();

        return view('administration.crm.communications.create', compact('customers', 'serviceRequests'));
    }

    public function storeCommunication(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'service_request_id' => 'nullable|exists:service_requests,id',
            'type' => 'required|in:email,sms,letter,phone_call,meeting',
            'subject' => 'required|string|max:200',
            'content' => 'required|string',
            'direction' => 'required|in:inbound,outbound',
            'recipient_name' => 'nullable|string|max:100',
            'recipient_email' => 'nullable|email',
            'recipient_phone' => 'nullable|string|max:20'
        ]);

        $validated['created_by'] = Auth::id();

        $communication = Communication::create($validated);

        return redirect()->route('administration.communications.index')
            ->with('success', 'Communication recorded successfully! Communication Number: ' . $communication->communication_number);
    }

    public function showCommunication(Communication $communication)
    {
        $communication->load(['customer', 'serviceRequest', 'createdBy']);

        return view('administration.crm.communications.show', compact('communication'));
    }
}