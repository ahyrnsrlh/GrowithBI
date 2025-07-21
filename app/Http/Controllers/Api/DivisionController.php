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
                           ->select('id', 'name', 'description', 'icon', 'quota', 'is_active')
                           ->get();

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
