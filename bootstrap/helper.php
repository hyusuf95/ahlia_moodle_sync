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

    // Temporarily override Laravel's redirect function
    if (!function_exists('laravel_redirect')) {
        function laravel_redirect($to = null, $status = 302, $headers = [], $secure = null)
        {
            return \Illuminate\Support\Facades\Redirect::to($to, $status, $headers, $secure);
        }

        // Rename Moodle's redirect function to avoid conflict
        function moodle_redirect($url, $params = null, $msgtype = null, $delay = 0)
        {
            global $CFG;
            return \moodle_url::make_redirect_url($url, $params, $msgtype, $delay);
        }
    }

    require_once "$root/config.php";
}

function get_category_id_by_idnumber(?int $idnumber = null)
{
    $moodle = new MoodleConfigLoader();
    return $moodle->get_category_by_idnumber($idnumber);
}
