<?php

namespace App\Modules\Housing\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Housing\Models\StandAllocation;
use App\Modules\Housing\Models\HousingStand;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index()
    {
        $allocations = StandAllocation::with(['stand.standArea'])
                                     ->latest()
                                     ->paginate(20);

        $summary = [
            'total_allocations' => StandAllocation::count(),
            'pending_allocations' => StandAllocation::where('status', 'pending')->count(),
            'approved_allocations' => StandAllocation::where('status', 'approved')->count(),
            'completed_allocations' => StandAllocation::where('status', 'completed')->count()
        ];

        return view('housing/allocation.index', compact('allocations', 'summary'));
    }

    public function create()
    {
        $availableStands = HousingStand::with('standArea')
                                      ->where('status', 'available')
                                      ->get();

        $intendedUses = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'mixed_use' => 'Mixed Use'
        ];

        $businessTypes = [
            'retail' => 'Retail',
            'office' => 'Office',
            'warehouse' => 'Warehouse',
            'manufacturing' => 'Manufacturing',
            'service' => 'Service',
            'other' => 'Other'
        ];

        $paymentPlans = [
            'full_payment' => 'Full Payment',
            'installments' => 'Installments',
            'lease' => 'Lease'
        ];

        return view('housing/allocation.create', compact('availableStands', 'intendedUses', 'businessTypes', 'paymentPlans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stand_id' => 'required|exists:housing_stands,id',
            'applicant_name' => 'required|string|max:255',
            'applicant_id_number' => 'required|string|max:50',
            'applicant_contact' => 'required|string|max:20',
            'applicant_email' => 'nullable|email|max:255',
            'applicant_address' => 'required|string',
            'intended_use' => 'required|string|in:residential,commercial,industrial,mixed_use',
            'business_type' => 'nullable|string|in:retail,office,warehouse,manufacturing,service,other',
            'allocation_amount' => 'required|numeric|min:0',
            'deposit_paid' => 'required|numeric|min:0',
            'payment_plan' => 'required|string|in:full_payment,installments,lease',
            'installment_months' => 'nullable|integer|min:1|max:120',
            'conditions' => 'nullable|array',
            'required_documents' => 'nullable|array',
            'submitted_documents' => 'nullable|array'
        ]);

        try {
            // Check if stand is still available
            $stand = HousingStand::findOrFail($validated['stand_id']);
            if ($stand->status !== 'available') {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Selected stand is no longer available');
            }

            // Generate allocation number
            $validated['allocation_number'] = $this->generateAllocationNumber();
            
            // Calculate balance and installments
            $validated['balance_due'] = $validated['allocation_amount'] - $validated['deposit_paid'];
            
            if ($validated['payment_plan'] === 'installments' && $validated['installment_months']) {
                $validated['monthly_installment'] = $validated['balance_due'] / $validated['installment_months'];
            }

            $validated['allocation_date'] = now();
            $validated['due_date'] = now()->addMonths($validated['installment_months'] ?? 12);
            $validated['status'] = 'pending';

            $allocation = StandAllocation::create($validated);

            // Update stand status to reserved
            $stand->update(['status' => 'reserved']);

            return redirect()->route('housing.allocation.show', $allocation)
                           ->with('success', 'Stand allocation created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create allocation: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $allocation = StandAllocation::with(['stand.standArea'])
                                    ->findOrFail($id);

        return view('housing/allocation.show', compact('allocation'));
    }

    public function edit($id)
    {
        $allocation = StandAllocation::findOrFail($id);
        
        $availableStands = HousingStand::with('standArea')
                                      ->where(function($q) use ($allocation) {
                                          $q->where('status', 'available')
                                            ->orWhere('id', $allocation->stand_id);
                                      })
                                      ->get();

        $intendedUses = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'mixed_use' => 'Mixed Use'
        ];

        $businessTypes = [
            'retail' => 'Retail',
            'office' => 'Office',
            'warehouse' => 'Warehouse',
            'manufacturing' => 'Manufacturing',
            'service' => 'Service',
            'other' => 'Other'
        ];

        $paymentPlans = [
            'full_payment' => 'Full Payment',
            'installments' => 'Installments',
            'lease' => 'Lease'
        ];

        return view('housing/allocation.edit', compact('allocation', 'availableStands', 'intendedUses', 'businessTypes', 'paymentPlans'));
    }

    public function update(Request $request, $id)
    {
        $allocation = StandAllocation::findOrFail($id);

        $validated = $request->validate([
            'stand_id' => 'required|exists:housing_stands,id',
            'applicant_name' => 'required|string|max:255',
            'applicant_id_number' => 'required|string|max:50',
            'applicant_contact' => 'required|string|max:20',
            'applicant_email' => 'nullable|email|max:255',
            'applicant_address' => 'required|string',
            'intended_use' => 'required|string|in:residential,commercial,industrial,mixed_use',
            'business_type' => 'nullable|string|in:retail,office,warehouse,manufacturing,service,other',
            'allocation_amount' => 'required|numeric|min:0',
            'deposit_paid' => 'required|numeric|min:0',
            'payment_plan' => 'required|string|in:full_payment,installments,lease',
            'installment_months' => 'nullable|integer|min:1|max:120',
            'conditions' => 'nullable|array',
            'required_documents' => 'nullable|array',
            'submitted_documents' => 'nullable|array'
        ]);

        try {
            // Calculate balance and installments
            $validated['balance_due'] = $validated['allocation_amount'] - $validated['deposit_paid'];
            
            if ($validated['payment_plan'] === 'installments' && $validated['installment_months']) {
                $validated['monthly_installment'] = $validated['balance_due'] / $validated['installment_months'];
            }

            $allocation->update($validated);

            return redirect()->route('housing.allocation.show', $allocation)
                           ->with('success', 'Stand allocation updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update allocation: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $allocation = StandAllocation::findOrFail($id);
            
            // Only allow deletion of pending allocations
            if (!in_array($allocation->status, ['pending', 'cancelled'])) {
                return redirect()->back()
                               ->with('error', 'Only pending or cancelled allocations can be deleted');
            }

            // Free up the stand
            if ($allocation->stand) {
                $allocation->stand->update(['status' => 'available']);
            }

            $allocation->delete();

            return redirect()->route('housing.allocation.index')
                           ->with('success', 'Stand allocation deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete allocation: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $allocation = StandAllocation::findOrFail($id);
            
            if ($allocation->status !== 'pending') {
                return redirect()->back()
                               ->with('error', 'Only pending allocations can be approved');
            }

            $allocation->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approval_date' => now()
            ]);

            // Update stand status to allocated
            if ($allocation->stand) {
                $allocation->stand->update(['status' => 'allocated']);
            }

            return redirect()->route('housing.allocation.show', $allocation)
                           ->with('success', 'Stand allocation approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to approve allocation: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $allocation = StandAllocation::findOrFail($id);
            
            if ($allocation->status !== 'pending') {
                return redirect()->back()
                               ->with('error', 'Only pending allocations can be rejected');
            }

            $allocation->update([
                'status' => 'cancelled',
                'rejection_reason' => $validated['rejection_reason']
            ]);

            // Free up the stand
            if ($allocation->stand) {
                $allocation->stand->update(['status' => 'available']);
            }

            return redirect()->route('housing.allocation.show', $allocation)
                           ->with('success', 'Stand allocation rejected successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to reject allocation: ' . $e->getMessage());
        }
    }

    public function complete($id)
    {
        try {
            $allocation = StandAllocation::findOrFail($id);
            
            if ($allocation->status !== 'approved') {
                return redirect()->back()
                               ->with('error', 'Only approved allocations can be completed');
            }

            // Check if full payment has been made
            if ($allocation->balance_due > 0) {
                return redirect()->back()
                               ->with('error', 'Allocation cannot be completed until full payment is made');
            }

            $allocation->update([
                'status' => 'completed',
                'completion_date' => now()
            ]);

            return redirect()->route('housing.allocation.show', $allocation)
                           ->with('success', 'Stand allocation completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to complete allocation: ' . $e->getMessage());
        }
    }

    private function generateAllocationNumber()
    {
        $year = date('Y');
        $prefix = 'SA';
        
        $lastAllocation = StandAllocation::whereRaw('allocation_number LIKE ?', ["{$prefix}{$year}%"])
                                        ->orderBy('allocation_number', 'desc')
                                        ->first();

        if ($lastAllocation) {
            $lastNumber = intval(substr($lastAllocation->allocation_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
