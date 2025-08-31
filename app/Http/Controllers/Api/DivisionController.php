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
                           ->select('id', 'name', 'description', 'icon', 'max_interns', 'is_active')
                           ->get()
                           ->map(function ($division) {
                               return [
                                   'id' => $division->id,
                                   'name' => $division->name,
                                   'description' => $division->description,
                                   'icon' => $division->icon,
                                   'quota' => $division->max_interns,
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
