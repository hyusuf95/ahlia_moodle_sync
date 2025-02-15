<?php

namespace App\Models;

use App\Traits\HasIdNumber;
use Illuminate\Database\Eloquent\Model;
use MoodleConnection;

class MoodleCategory extends Model
{

        use MoodleConnection, HasIdNumber;
        protected $table = 'mdl_course_categories';






}
