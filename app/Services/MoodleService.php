<?php

namespace App\Services;

/**
 * Class MoodleService.
 */
class MoodleService
{


    private function requireMoodle()
    {
        $root = config('sync.moodle.root');
        require_once $root . '/config.php';
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
