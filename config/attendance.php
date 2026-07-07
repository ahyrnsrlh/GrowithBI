<?php

return [
    'office' => [
        'latitude'  => env('OFFICE_LATITUDE',  -5.4194538),
        'longitude' => env('OFFICE_LONGITUDE', 105.2604370),
    ],
    'radius'           => env('ATTENDANCE_RADIUS_METERS', 500),    // meters
    'gps_accuracy_max' => env('GPS_ACCURACY_MAX_METERS',  50),     // meters
    'sample_count'     => env('GPS_SAMPLE_COUNT',         3),      // readings
    'stability_max'    => env('GPS_STABILITY_MAX',        0.0003), // degrees (~33m)
    'max_speed_kmh'    => env('GPS_MAX_SPEED_KMH',        150),    // km/h impossible speed threshold
];
