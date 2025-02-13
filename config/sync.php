<?php

return [

    'adreg' => [
        'url' => env('ADREG_API_URL', 'https://localhost'),
    ],

    'moodle' => [
        'root' => env('MOODLE_ROOT', '/var/www/html/'),
        'url' => env('MOODLE_API_URL', 'https://localhost'),
        'token' => env('MOODLE_TOKEN', 'token'),
    ],


];
