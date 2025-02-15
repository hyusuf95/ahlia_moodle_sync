<?php

use App\Moodle\MoodleConfigLoader;

function sayHello()
{
    return 'Hello';
}



function load_moodle()
{

    $root = config('sync.moodle.root');

    if (php_sapi_name() === 'cli') {
        define('CLI_SCRIPT', true);
    }
    include_once "$root/config.php";
}

function get_category_id_by_idnumber(?int $idnumber = null)
{

    $moodle = new MoodleConfigLoader();

    $moodle->get_category_by_idnumber('college_10');

}
