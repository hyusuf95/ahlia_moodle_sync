<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodleCourse extends Model
{
        protected $connection = 'moodle';

        // Choose the table to reference
        protected $table = 'mdl_course_categories';
        // Set the primary id key reference
        protected $primaryKey = 'id';




        public static function find2(int $idnumber)
        {
            return self::where('idnumber', $idnumber)->first();
        }
}
