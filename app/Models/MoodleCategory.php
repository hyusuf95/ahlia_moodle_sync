<?php

namespace App\Models;

use App\Traits\HasIdNumber;
use App\Traits\MoodleConnection;
use Illuminate\Database\Eloquent\Model;


class MoodleCategory extends Model
{

        use  HasIdNumber;

        protected $connection = 'moodle';
        protected $primaryKey = 'id';
        protected $table = 'mdl_course_categories';






}
