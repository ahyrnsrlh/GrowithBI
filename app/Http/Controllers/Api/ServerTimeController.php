<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ServerTimeController extends Controller
{
    /**
     * Get current server time
     * 
     * @return JsonResponse
     */
    public function getServerTime(): JsonResponse
    {
        $now = Carbon::now('Asia/Jakarta');
        
        return response()->json([
            'time' => $now->toIso8601String(), // ISO 8601 dengan timezone offset
            'timestamp' => $now->timestamp,
            'utc_time' => $now->copy()->utc()->toIso8601String(), // UTC untuk reference
            'timezone_offset' => '+07:00', // WIB offset dari UTC
            'formatted' => [
                'date' => $now->format('Y-m-d'),
                'time' => $now->format('H:i:s'),
                'datetime' => $now->format('Y-m-d H:i:s'),
                'timezone' => 'WIB',
                'full' => $now->format('l, d F Y H:i:s'),
            ]
        ]);
    }
    
    /**
     * Get allowed check-in time range
     * 
     * @return JsonResponse
     */
    public function getCheckInTimeRange(): JsonResponse
    {
        return response()->json([
            'check_in' => [
                'start' => '07:30:00',
                'end' => '08:00:00',
            ],
            'check_out' => [
                'start' => '16:00:00',
                'end' => '18:00:00',
            ],
            'timezone' => 'Asia/Jakarta',
        ]);
    }
}
