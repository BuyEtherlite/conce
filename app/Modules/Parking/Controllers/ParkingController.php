<?php

namespace App\Modules\Parking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parking\Models\ParkingZone;
use App\Modules\Parking\Models\ParkingViolation;
use App\Modules\Parking\Models\ViolationPayment;
use App\Modules\Parking\Models\ParkingSpace;
use App\Modules\Parking\Models\ParkingPermit; // Import ParkingPermit model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingController extends Controller
{
    /**
     * Display a dashboard overview of the parking system.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'total_zones' => ParkingZone::active()->count(),
            'total_spaces' => ParkingSpace::active()->count(),
            'available_spaces' => ParkingSpace::available()->count(),
            'active_violations' => ParkingViolation::unpaid()->count(),
            'overdue_violations' => ParkingViolation::overdue()->count(),
            'today_revenue' => ViolationPayment::whereDate('payment_date', today())
                ->where('status', 'completed')
                ->sum('amount')
        ];

        $recentViolations = ParkingViolation::with(['zone', 'space', 'issuedBy'])
            ->latest()
            ->take(10)
            ->get();

        $zoneStats = ParkingZone::withCount(['violations' => function($query) {
            $query->unpaid();
        }])->get();

        return view('parking.index', compact('stats', 'recentViolations', 'zoneStats'));
    }

    /**
     * Display a list of all parking zones.
     *
     * @return \Illuminate\View\View
     */
    public function zones()
    {
        $zones = ParkingZone::withCount(['spaces', 'violations', 'permits'])
            ->paginate(20);

        return view('parking.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new parking zone.
     *
     * @return \Illuminate\View\View
     */
    public function createZone()
    {
        return view('parking.zones.create');
    }

    /**
     * Store a newly created parking zone in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeZone(Request $request)
    {
        $validated = $request->validate([
            'zone_code' => 'required|string|max:20|unique:parking_zones',
            'zone_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'zone_type' => 'required|in:residential,commercial,mixed,loading,disabled,visitor',
            'hourly_rate' => 'required|numeric|min:0',
            'max_duration_minutes' => 'required|integer|min:30',
            'operating_hours' => 'required|array',
            'boundaries' => 'required|array'
        ]);

        ParkingZone::create($validated);

        return redirect()->route('parking.zones.index')
            ->with('success', 'Parking zone created successfully.');
    }

    /**
     * Display a list of parking violations.
     *
     * @return \Illuminate\View\View
     */
    public function violations()
    {
        $violations = ParkingViolation::with(['zone', 'space', 'issuedBy'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('zone_id'), function($query, $zoneId) {
                return $query->where('zone_id', $zoneId);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('violation_number', 'like', "%{$search}%")
                      ->orWhere('vehicle_registration', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);

        $zones = ParkingZone::active()->get();

        return view('parking.violations.index', compact('violations', 'zones'));
    }

    /**
     * Show the form for creating a new parking violation.
     *
     * @return \Illuminate\View\View
     */
    public function createViolation()
    {
        $zones = ParkingZone::active()->with('spaces')->get();
        $violationTypes = [
            'overtime_parking' => 'Overtime Parking',
            'no_permit' => 'No Permit',
            'invalid_permit' => 'Invalid Permit',
            'disabled_space' => 'Disabled Space Violation',
            'loading_zone' => 'Loading Zone Violation',
            'fire_hydrant' => 'Fire Hydrant Violation',
            'no_parking_zone' => 'No Parking Zone',
            'expired_meter' => 'Expired Meter',
            'blocking_driveway' => 'Blocking Driveway',
            'double_parking' => 'Double Parking',
            'wrong_direction' => 'Wrong Direction Parking'
        ];

        return view('parking.violations.create', compact('zones', 'violationTypes'));
    }

    /**
     * Store a newly created parking violation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeViolation(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'space_id' => 'nullable|exists:parking_spaces,id',
            'vehicle_registration' => 'required|string|max:20',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_color' => 'nullable|string|max:50',
            'violation_type' => 'required|string',
            'violation_description' => 'required|string',
            'fine_amount' => 'required|numeric|min:0',
            'violation_datetime' => 'required|datetime',
            'location' => 'required|array',
            'evidence' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['status'] = 'issued';
        $validated['due_date'] = now()->addDays(30); // 30 days to pay

        ParkingViolation::create($validated);

        return redirect()->route('parking.violations.index')
            ->with('success', 'Parking violation issued successfully.');
    }

    /**
     * Display the details of a specific parking violation.
     *
     * @param  \App\Modules\Parking\Models\ParkingViolation  $violation
     * @return \Illuminate\View\View
     */
    public function showViolation(ParkingViolation $violation)
    {
        $violation->load(['zone', 'space', 'issuedBy', 'payments', 'dispute']);

        return view('parking.violations.show', compact('violation'));
    }

    /**
     * Process a payment for a parking violation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Parking\Models\ParkingViolation  $violation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, ParkingViolation $violation)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $violation->getRemainingBalance(),
            'payment_method' => 'required|in:cash,card,bank_transfer,online,mobile',
            'payment_details' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $payment = ViolationPayment::create([
                'violation_id' => $violation->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => now(),
                'processed_by' => auth()->id(),
                'status' => 'completed',
                'payment_details' => $validated['payment_details'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            // Update violation
            $newAmountPaid = $violation->amount_paid + $validated['amount'];
            $violation->update([
                'amount_paid' => $newAmountPaid,
                'status' => $newAmountPaid >= $violation->fine_amount ? 'paid' : $violation->status,
                'payment_date' => $newAmountPaid >= $violation->fine_amount ? now() : $violation->payment_date,
                'payment_reference' => $payment->payment_reference
            ]);

            DB::commit();

            return redirect()->route('parking.violations.show', $violation)
                ->with('success', 'Payment processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to process payment. Please try again.']);
        }
    }

    /**
     * Display parking violation reports.
     *
     * @return \Illuminate\View\View
     */
    public function reports()
    {
        $dateFrom = request('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        $violationStats = [
            'total_violations' => ParkingViolation::whereBetween('violation_datetime', [$dateFrom, $dateTo])->count(),
            'paid_violations' => ParkingViolation::whereBetween('violation_datetime', [$dateFrom, $dateTo])
                ->where('status', 'paid')->count(),
            'overdue_violations' => ParkingViolation::whereBetween('violation_datetime', [$dateFrom, $dateTo])
                ->overdue()->count(),
            'total_fines' => ParkingViolation::whereBetween('violation_datetime', [$dateFrom, $dateTo])
                ->sum('fine_amount'),
            'collected_fines' => ParkingViolation::whereBetween('violation_datetime', [$dateFrom, $dateTo])
                ->sum('amount_paid')
        ];

        $violationsByType = ParkingViolation::select('violation_type', DB::raw('COUNT(*) as count'))
            ->whereBetween('violation_datetime', [$dateFrom, $dateTo])
            ->groupBy('violation_type')
            ->get();

        $violationsByZone = ParkingZone::withCount(['violations' => function($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('violation_datetime', [$dateFrom, $dateTo]);
        }])->get();

        return view('parking.reports', compact(
            'violationStats',
            'violationsByType',
            'violationsByZone',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Display a list of all parking permits.
     *
     * @return \Illuminate\View\View
     */
    public function permits()
    {
        $permits = \App\Modules\Parking\Models\ParkingPermit::with(['zone'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('zone_id'), function($query, $zoneId) {
                return $query->where('zone_id', $zoneId);
            })
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('permit_number', 'like', "%{$search}%")
                      ->orWhere('vehicle_registration', 'like', "%{$search}%")
                      ->orWhere('holder_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);

        $zones = ParkingZone::active()->get();

        return view('parking.permits.index', compact('permits', 'zones'));
    }

    /**
     * Show the form for creating a new parking permit.
     *
     * @return \Illuminate\View\View
     */
    public function createPermit()
    {
        $zones = ParkingZone::active()->get();
        $permitTypes = [
            'residential' => 'Residential',
            'business' => 'Business',
            'visitor' => 'Visitor',
            'disabled' => 'Disabled',
            'temporary' => 'Temporary'
        ];

        return view('parking.permits.create', compact('zones', 'permitTypes'));
    }

    /**
     * Store a newly created parking permit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePermit(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'permit_type' => 'required|string',
            'vehicle_registration' => 'required|string|max:20',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_color' => 'nullable|string|max:50',
            'holder_name' => 'required|string|max:255',
            'holder_address' => 'required|string',
            'holder_phone' => 'required|string|max:20',
            'holder_email' => 'nullable|email|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'fee_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['status'] = 'active';

        \App\Modules\Parking\Models\ParkingPermit::create($validated);

        return redirect()->route('parking.permits.index')
            ->with('success', 'Parking permit issued successfully.');
    }

    /**
     * Display the details of a specific parking permit.
     *
     * @param  \App\Modules\Parking\Models\ParkingPermit  $permit
     * @return \Illuminate\View\View
     */
    public function showPermit(\App\Modules\Parking\Models\ParkingPermit $permit)
    {
        $permit->load(['zone', 'issuedBy']);

        return view('parking.permits.show', compact('permit'));
    }

    /**
     * Display a list of all parking spaces.
     *
     * @return \Illuminate\View\View
     */
    public function spaces()
    {
        $spaces = ParkingSpace::with('zone')
            ->when(request('zone_id'), function($query, $zoneId) {
                return $query->where('zone_id', $zoneId);
            })
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('type'), function($query, $type) {
                return $query->where('space_type', $type);
            })
            ->paginate(20);

        $zones = ParkingZone::active()->get();

        return view('parking.spaces.index', compact('spaces', 'zones'));
    }

    /**
     * Show the form for creating a new parking space.
     *
     * @return \Illuminate\View\View
     */
    public function createSpace()
    {
        $zones = ParkingZone::active()->get();
        $spaceTypes = [
            'standard' => 'Standard',
            'disabled' => 'Disabled',
            'loading' => 'Loading',
            'visitor' => 'Visitor',
            'reserved' => 'Reserved'
        ];

        return view('parking.spaces.create', compact('zones', 'spaceTypes'));
    }

    /**
     * Store a newly created parking space in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSpace(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'space_number' => 'required|string|max:20',
            'space_type' => 'required|in:standard,disabled,loading,visitor,reserved',
            'location' => 'required|array',
            'notes' => 'nullable|string'
        ]);

        $validated['status'] = 'available';
        $validated['is_active'] = true;

        ParkingSpace::create($validated);

        return redirect()->route('parking.spaces.index')
            ->with('success', 'Parking space created successfully.');
    }
}