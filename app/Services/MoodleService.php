<?php

namespace App\Services;

/**
 * Class MoodleService.
 */
class MoodleService
{


    private function requireMoodle()
    {
        // Define CLI_SCRIPT if running from the command line
        if (php_sapi_name() === 'cli') {
            define('CLI_SCRIPT', true);
        }

        $root = config('sync.moodle.root');

        // Check if redirect() already exists
        if (!function_exists('redirect')) {
            require_once $root . '/config.php';
        }
    }

    public function getDB()
    {
        $this->requireMoodle();
        global $DB;
        return $DB;
    }

    public function getDBTable($table)
    {
        $this->requireMoodle();
        global $DB;
        return $DB->get_records($table);
    }

    public function getDBTableWhere($table, $field, $value)
    {
        $this->requireMoodle();
        global $DB;
        return $DB->get_records($table, [$field => $value]);
    }

    public function getCourses()
    {
        return $this->getDBTable('course');
    }
}
