<?php

namespace App\Modules\Housing\Services;

use App\Models\Housing\HousingArea;
use App\Models\Housing\HousingStand;
use App\Models\Housing\HousingStandAllocation;
use App\Models\Housing\HousingApplication;
use App\Models\Housing\HousingWaitingList;
use Illuminate\Support\Collection;

class StandAllocationService
{
    /**
     * Allocate a stand to an applicant
     */
    public function allocateStand(array $params): HousingStandAllocation
    {
        $applicant = $params['applicant'];
        $sectorPreference = $params['sector_preference'];
        $areaPreference = $params['area_preference'] ?? [];
        $priorityScore = $params['priority_score'];

        // Find available stand based on preferences
        $stand = $this->findAvailableStand($sectorPreference, $areaPreference);

        if (!$stand) {
            // Add to waiting list if no stand available
            return $this->addToWaitingList($applicant, $sectorPreference, $priorityScore);
        }

        // Create allocation
        $allocation = HousingStandAllocation::create([
            'stand_id' => $stand->id,
            'applicant_id' => $applicant->id,
            'allocation_date' => now(),
            'status' => 'allocated',
            'priority_score' => $priorityScore,
            'sector_type' => $sectorPreference,
            'allocation_type' => 'standard',
            'allocation_letter_number' => $this->generateAllocationLetterNumber(),
            'compliance_status' => 'pending'
        ]);

        // Update stand status
        $stand->update(['status' => 'allocated']);

        // Remove from waiting list if applicable
        HousingWaitingList::where('applicant_id', $applicant->id)
            ->where('sector_type', $sectorPreference)
            ->delete();

        // Generate allocation documents
        $this->generateAllocationDocuments($allocation);

        return $allocation;
    }

    /**
     * Find available stand based on preferences
     */
    protected function findAvailableStand(string $sectorType, array $areaPreferences = []): ?HousingStand
    {
        $query = HousingStand::where('sector_type', $sectorType)
            ->where('status', 'available');

        if (!empty($areaPreferences)) {
            $query->whereHas('area', function ($q) use ($areaPreferences) {
                $q->whereIn('name', $areaPreferences);
            });
        }

        return $query->orderBy('created_at', 'asc')->first();
    }

    /**
     * Add applicant to waiting list
     */
    protected function addToWaitingList($applicant, string $sectorType, float $priorityScore): HousingWaitingList
    {
        return HousingWaitingList::create([
            'applicant_id' => $applicant->id,
            'sector_type' => $sectorType,
            'priority_score' => $priorityScore,
            'date_added' => now(),
            'status' => 'waiting'
        ]);
    }

    /**
     * Generate allocation letter number
     */
    protected function generateAllocationLetterNumber(): string
    {
        $year = now()->year;
        $lastAllocation = HousingStandAllocation::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastAllocation ? (int) substr($lastAllocation->allocation_letter_number, -4) + 1 : 1;

        return "HSA/{$year}/" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate allocation documents
     */
    protected function generateAllocationDocuments(HousingStandAllocation $allocation): void
    {
        // Generate allocation letter
        // Generate certificate of allocation
        // Generate payment schedule
        // This would integrate with document generation service
    }

    /**
     * Get allocation statistics
     */
    public function getAllocationStatistics(): array
    {
        $totalStands = HousingStand::count();
        $allocatedStands = HousingStand::where('status', 'allocated')->count();
        $availableStands = HousingStand::where('status', 'available')->count();

        $sectorStats = HousingStand::selectRaw('sector_type, status, COUNT(*) as count')
            ->groupBy('sector_type', 'status')
            ->get()
            ->groupBy('sector_type');

        $waitingListStats = HousingWaitingList::selectRaw('sector_type, COUNT(*) as count')
            ->groupBy('sector_type')
            ->get()
            ->pluck('count', 'sector_type');

        return [
            'total_stands' => $totalStands,
            'allocated_stands' => $allocatedStands,
            'available_stands' => $availableStands,
            'occupancy_rate' => $totalStands > 0 ? ($allocatedStands / $totalStands) * 100 : 0,
            'sector_statistics' => $sectorStats,
            'waiting_list_statistics' => $waitingListStats
        ];
    }

    /**
     * Process waiting list for automatic allocation
     */
    public function processWaitingList(): Collection
    {
        $processed = collect();

        // Get all sector types with waiting applicants
        $sectors = HousingWaitingList::distinct('sector_type')->pluck('sector_type');

        foreach ($sectors as $sector) {
            // Get available stands for this sector
            $availableStands = HousingStand::where('sector_type', $sector)
                ->where('status', 'available')
                ->count();

            if ($availableStands > 0) {
                // Get top priority applicants
                $waitingApplicants = HousingWaitingList::where('sector_type', $sector)
                    ->orderBy('priority_score', 'desc')
                    ->orderBy('date_added', 'asc')
                    ->limit($availableStands)
                    ->get();

                foreach ($waitingApplicants as $waitingApplicant) {
                    $allocation = $this->allocateStand([
                        'applicant' => $waitingApplicant->applicant,
                        'sector_preference' => $sector,
                        'priority_score' => $waitingApplicant->priority_score
                    ]);

                    $processed->push($allocation);
                }
            }
        }

        return $processed;
    }
}
