<?php

return [

    'adreg' => [
        'url' => env('ADREG_API_URL', 'https://localhost'),
    ],

    'moodle' => [
        'source_host' => env('MOODLE_SOURCE_HOST', 'localhost'),
        'source_root' => env('MOODLE_SOURCE_ROOT', '/var/www/html/'),
        'source_url' => env('MOODLE_SOURCE_API_URL', 'https://localhost'),
        'target_host' => env('MOODLE_HOST', 'localhost'),

        'root' => env('MOODLE_ROOT', '/var/www/html/'),
        'url' => env('MOODLE_API_URL', 'https://localhost'),
        'token' => env('MOODLE_TOKEN', 'token'),

        'categories' => [
            'active_parent' => env('MOODLE_CATEGORY_PARENT', 0),
            'archive_parent' => env('MOODLE_CATEGORY_ARCHIVE', -1),
        ],

        'courses' => [
            'format' => env('MOODLE_COURSE_FORMAT', 'topics'),
            'ends_after' => env('MOODLE_COURSE_ENDS_AFTER', 12),
            'backup_folder' => env('MOODLE_COURSE_BACKUP_FOLDER', 'backupdata'),
        ]


    ],


];
