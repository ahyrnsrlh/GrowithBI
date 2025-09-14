<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix job_description and requirements data that might be stored as character arrays
        $divisions = \App\Models\Division::all();
        
        foreach ($divisions as $division) {
            $jobDescriptionFixed = false;
            $requirementsFixed = false;
            
            // Fix job_description if it's stored as character array
            if (!empty($division->getRawOriginal('job_description'))) {
                $jobDesc = $division->getRawOriginal('job_description');
                $decoded = json_decode($jobDesc, true);
                
                if (is_array($decoded) && count($decoded) > 0 && strlen($decoded[0]) === 1) {
                    // This looks like a character array, reconstruct as single string
                    $reconstructed = implode('', $decoded);
                    $division->job_description = json_encode([$reconstructed]);
                    $jobDescriptionFixed = true;
                }
            }
            
            // Fix requirements if it's stored as character array
            if (!empty($division->getRawOriginal('requirements'))) {
                $requirements = $division->getRawOriginal('requirements');
                $decoded = json_decode($requirements, true);
                
                if (is_array($decoded) && count($decoded) > 0 && strlen($decoded[0]) === 1) {
                    // This looks like a character array, reconstruct as single string
                    $reconstructed = implode('', $decoded);
                    $division->requirements = json_encode([$reconstructed]);
                    $requirementsFixed = true;
                }
            }
            
            if ($jobDescriptionFixed || $requirementsFixed) {
                $division->saveQuietly(); // Save without triggering events
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data corruption, rollback is not recommended
        // as it might break existing functionality
    }
};
