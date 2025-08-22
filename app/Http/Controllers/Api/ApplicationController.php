<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function checkExisting(Request $request, $divisionId)
    {
        $user = $request->user();
        
        $hasApplication = Application::where('email', $user->email)
            ->where('division_id', $divisionId)
            ->whereIn('status', ['menunggu', 'dalam_review', 'wawancara'])
            ->exists();
            
        return response()->json([
            'hasApplication' => $hasApplication
        ]);
    }
}
