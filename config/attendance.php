<?php

return [
    'office' => [
        'latitude'  => env('OFFICE_LATITUDE',  -5.4194538),
        'longitude' => env('OFFICE_LONGITUDE', 105.2604370),
    ],
    'radius'           => env('ATTENDANCE_RADIUS_METERS', 500),    // meters
];
