<?php

namespace App\Services;

/**
 * Class MoodleService.
 */
class MoodleService
{


    private function requireMoodle()
    {
        if (php_sapi_name() === 'cli') {
            define('CLI_SCRIPT', true);
        }

        $root = config('sync.moodle.root');
        require_once $root . '/config.php';

        if (!function_exists('moodle_redirect')) {
            function moodle_redirect($url, $message = '', $delay = null, $messagetype = \core\output\notification::NOTIFY_INFO)
            {
                redirect($url, $message, $delay, $messagetype);
            }
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
