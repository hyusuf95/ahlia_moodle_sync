<?php

namespace App\Models;

use App\Traits\HasIdNumber;
use App\Traits\MoodleConnection;
use Illuminate\Database\Eloquent\Model;


class MoodleCourse extends Model
{

    use MoodleConnection, HasIdNumber;
    protected $table = 'mdl_course';


}
