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
        
        if (!$user) {
            return response()->json(['hasApplication' => false]);
        }
        
        $hasApplication = Application::where('user_id', $user->id)
            ->where('division_id', $divisionId)
            ->whereIn('status', ['menunggu', 'dalam_review', 'wawancara'])
            ->exists();
            
        return response()->json([
            'hasApplication' => $hasApplication
        ]);
    }

    public function getStatus(Request $request, $applicationId)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $application = Application::where('id', $applicationId)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$application) {
            return response()->json([
                'error' => 'Application not found'
            ], 404);
        }
        
        return response()->json([
            'id' => $application->id,
            'status' => $application->status,
            'updated_at' => $application->updated_at,
            'created_at' => $application->created_at
        ]);
    }
}
