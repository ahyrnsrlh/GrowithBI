<?php

namespace App\Services;

use App\Models\Division;
use App\Models\Application;
use App\Enums\RegistrationStatus;
use Illuminate\Validation\ValidationException;

class DivisionQuotaService
{
    /**
     * Get the statuses that indicate an application has been accepted.
     * 
     * @return array<string>
     */
    public function getAcceptedStatuses(): array
    {
        return [
            RegistrationStatus::ACCEPTED->value,
            RegistrationStatus::LETTER_READY->value,
            'diterima', // Legacy support
        ];
    }

    /**
     * Count accepted participants for a division.
     */
    public function acceptedCount(Division $division): int
    {
        return Application::where('division_id', $division->id)
            ->whereIn('status', $this->getAcceptedStatuses())
            ->count();
    }

    /**
     * Calculate remaining quota for a division.
     */
    public function remainingQuota(Division $division): int
    {
        $quota = $division->max_interns;
        return max(0, $quota - $this->acceptedCount($division));
    }

    /**
     * Determine if a division has available quota.
     */
    public function hasAvailableQuota(Division $division): bool
    {
        return $this->remainingQuota($division) > 0;
    }

    /**
     * Validate quota during registration.
     */
    public function validateRegistrationQuota(Division $division): void
    {
        if (!$this->hasAvailableQuota($division)) {
            throw ValidationException::withMessages([
                'division_id' => 'Divisi yang dipilih telah mencapai batas kuota peserta. Silakan pilih divisi lain.'
            ]);
        }
    }

    /**
     * Validate quota during approval.
     */
    public function validateApprovalQuota(Division $division): void
    {
        if (!$this->hasAvailableQuota($division)) {
            throw ValidationException::withMessages([
                'status' => 'Peserta tidak dapat diterima karena kuota divisi telah terpenuhi. Silakan pilih divisi lain atau tambah kuota terlebih dahulu.'
            ]);
        }
    }
}
