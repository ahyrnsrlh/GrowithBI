<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the divisions.
     */
    public function index()
    {
        $divisions = Division::where('is_active', true)
                           ->select('id', 'name', 'description', 'icon', 'max_interns', 'is_active', 'start_date', 'end_date')
                           ->get()
                           ->map(function ($division) {
                               // Calculate duration in months based on actual start_date and end_date from admin
                               $duration = 6; // Default to 6 months if no dates set
                               
                               if ($division->start_date && $division->end_date) {
                                   $startDate = \Carbon\Carbon::parse($division->start_date);
                                   $endDate = \Carbon\Carbon::parse($division->end_date);
                                   
                                   // Calculate difference in days first for more accurate assessment
                                   $durationInDays = $startDate->diffInDays($endDate);
                                   
                                   if ($durationInDays === 0) {
                                       // Same day = 1 month minimum
                                       $duration = 1;
                                   } elseif ($durationInDays <= 7) {
                                       // 1 week or less = 1 month
                                       $duration = 1;
                                   } elseif ($durationInDays <= 45) {
                                       // Up to 45 days = 1-2 months (round by 30-day chunks)
                                       $duration = max(1, round($durationInDays / 30));
                                   } else {
                                       // More than 45 days = calculate months and round
                                       $durationCalc = $startDate->diffInMonths($endDate, false);
                                       $duration = max(1, round($durationCalc));
                                       
                                       // Cap at reasonable internship duration (12 months max)
                                       if ($duration > 12) {
                                           $duration = 6; // Default to 6 months for unrealistic long periods
                                       }
                                   }
                               }

                               return [
                                   'id' => $division->id,
                                   'name' => $division->name,
                                   'description' => $division->description,
                                   'icon' => $division->icon,
                                   'quota' => $division->max_interns,
                                   'duration' => $duration,
                                   'is_active' => $division->is_active
                               ];
                           });

        return response()->json($divisions);
    }

    /**
     * Display the specified division.
     */
    public function show(Division $division)
    {
        return response()->json($division);
    }
}
