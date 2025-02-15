<?php

return [

    'adreg' => [
        'url' => env('ADREG_API_URL', 'https://localhost'),
    ],

    'moodle' => [
        'root' => env('MOODLE_ROOT', '/var/www/html/'),
        'url' => env('MOODLE_API_URL', 'https://localhost'),
        'token' => env('MOODLE_TOKEN', 'token'),

        'categories'=>[
            'active_parent' => env('MOODLE_CATEGORY_PARENT', 6),
            'archive_parent' => env('MOODLE_CATEGORY_ARCHIVE', -1),
        ],

        'courses'=>[
            'format' => env('MOODLE_COURSE_FORMAT', 'topics'),
            'ends_after' => env('MOODLE_COURSE_ENDS_AFTER', 12),
        ]


    ],


];
