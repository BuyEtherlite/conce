<?php

namespace App\Modules\Licensing\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Licensing\Models\BusinessLicense;
use App\Modules\Licensing\Models\LicenseType;
use App\Modules\Licensing\Models\LicenseApplication;
use Illuminate\Http\Request;

class LicensingController extends Controller
{
    public function index()
    {
        $licenses = BusinessLicense::with(['licenseType', 'processedBy'])
                                  ->latest()
                                  ->paginate(20);

        $summary = [
            'total_licenses' => BusinessLicense::count(),
            'active_licenses' => BusinessLicense::active()->count(),
            'pending_applications' => BusinessLicense::pending()->count(),
            'expiring_soon' => BusinessLicense::expiringSoon()->count()
        ];

        return view('licensing.index', compact('licenses', 'summary'));
    }

    public function create()
    {
        $licenseTypes = LicenseType::where('is_active', true)->get();
        
        $businessTypes = [
            'retail' => 'Retail',
            'wholesale' => 'Wholesale',
            'manufacturing' => 'Manufacturing',
            'service' => 'Service',
            'restaurant' => 'Restaurant',
            'other' => 'Other'
        ];

        return view('licensing.create', compact('licenseTypes', 'businessTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'owner_name' => 'required|string|max:255',
            'owner_id_number' => 'required|string|max:50',
            'owner_contact' => 'required|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'business_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'ward_id' => 'nullable|integer',
            'registration_number' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:100',
            'license_type_id' => 'required|exists:license_types,id',
            'application_date' => 'required|date',
            'fee_amount' => 'required|numeric|min:0',
            'conditions' => 'nullable|array',
            'remarks' => 'nullable|string'
        ]);

        try {
            $license = new BusinessLicense($validated);
            $license->license_number = $license->generateLicenseNumber();
            $license->status = 'pending';
            $license->payment_status = 'pending';
            $license->fee_paid = 0;
            $license->is_renewable = true;
            $license->is_active = false;
            $license->processed_by = auth()->id();
            $license->save();

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License application created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create license application: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $license = BusinessLicense::with(['licenseType', 'processedBy', 'approvedBy', 'applications'])
                                 ->findOrFail($id);

        return view('licensing.show', compact('license'));
    }

    public function edit($id)
    {
        $license = BusinessLicense::findOrFail($id);
        $licenseTypes = LicenseType::where('is_active', true)->get();
        
        $businessTypes = [
            'retail' => 'Retail',
            'wholesale' => 'Wholesale',
            'manufacturing' => 'Manufacturing',
            'service' => 'Service',
            'restaurant' => 'Restaurant',
            'other' => 'Other'
        ];

        return view('licensing.edit', compact('license', 'licenseTypes', 'businessTypes'));
    }

    public function update(Request $request, $id)
    {
        $license = BusinessLicense::findOrFail($id);

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'owner_name' => 'required|string|max:255',
            'owner_id_number' => 'required|string|max:50',
            'owner_contact' => 'required|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'business_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'ward_id' => 'nullable|integer',
            'registration_number' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:100',
            'license_type_id' => 'required|exists:license_types,id',
            'fee_amount' => 'required|numeric|min:0',
            'conditions' => 'nullable|array',
            'remarks' => 'nullable|string'
        ]);

        try {
            $license->update($validated);

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update license: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $license = BusinessLicense::findOrFail($id);
            
            // Only allow deletion of pending licenses
            if ($license->status !== 'pending') {
                return redirect()->back()
                               ->with('error', 'Only pending licenses can be deleted');
            }

            $license->delete();

            return redirect()->route('licensing.licensing.index')
                           ->with('success', 'License deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete license: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $license = BusinessLicense::findOrFail($id);
            
            if ($license->status !== 'pending') {
                return redirect()->back()
                               ->with('error', 'Only pending licenses can be approved');
            }

            // Check if fees are paid
            if ($license->fee_paid < $license->fee_amount) {
                return redirect()->back()
                               ->with('error', 'License fees must be paid before approval');
            }

            $license->approve();
            $license->issue_date = now();
            $license->expiry_date = now()->addYear(); // 1 year validity
            $license->save();

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License approved and issued successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to approve license: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $license = BusinessLicense::findOrFail($id);
            
            if ($license->status !== 'pending') {
                return redirect()->back()
                               ->with('error', 'Only pending licenses can be rejected');
            }

            $license->reject($validated['rejection_reason']);

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License rejected successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to reject license: ' . $e->getMessage());
        }
    }

    public function renew($id)
    {
        try {
            $license = BusinessLicense::findOrFail($id);
            
            if (!$license->is_renewable) {
                return redirect()->back()
                               ->with('error', 'This license is not renewable');
            }

            $newExpiryDate = $license->expiry_date->addYear();
            $license->renew($newExpiryDate);

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License renewed successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to renew license: ' . $e->getMessage());
        }
    }

    public function suspend(Request $request, $id)
    {
        $validated = $request->validate([
            'suspension_reason' => 'required|string|max:500'
        ]);

        try {
            $license = BusinessLicense::findOrFail($id);
            $license->suspend($validated['suspension_reason']);

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License suspended successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to suspend license: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            $license = BusinessLicense::findOrFail($id);
            $license->activate();

            return redirect()->route('licensing.licensing.show', $license)
                           ->with('success', 'License activated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to activate license: ' . $e->getMessage());
        }
    }
}
