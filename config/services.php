<?php

return [

    'face_verification' => [
        'url' => env('FACE_SERVICE_URL', 'http://127.0.0.1:8001/verify-face'),
        'timeout' => (int) env('FACE_SERVICE_TIMEOUT', 300),
        'connect_timeout' => (int) env('FACE_SERVICE_CONNECT_TIMEOUT', 5),
        'threshold' => 0.25,
    ],

    'attendance_geofence' => [
        'latitude' => env('ATTENDANCE_CAMPUS_LATITUDE'),
        'longitude' => env('ATTENDANCE_CAMPUS_LONGITUDE'),
        'radius_meters' => (float) env('ATTENDANCE_GEOFENCE_RADIUS_METERS', 300),
        'max_accuracy_meters' => (float) env('ATTENDANCE_LOCATION_MAX_ACCURACY_METERS', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
