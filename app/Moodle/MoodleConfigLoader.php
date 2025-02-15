<?php

namespace App\Moodle;

$moodle_path = config('sync.moodle.root');
if (php_sapi_name() === 'cli') {
    define('CLI_SCRIPT', true);
}
require_once $moodle_path . '/config.php';

class MoodleConfigLoader
{
    public function __construct()
    {
        global $CFG;
        global $DB;
    }

    public function get_category_by_idnumber($idnumber)
    {
        global $DB;
        return $DB->get_record('course_categories', ['idnumber' => $idnumber]);
    }
}
