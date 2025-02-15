<?php


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
    require_once $root . '/config.php';
}

function get_category_id_by_idnumber(?int $idnumber = null)
{
    load_moodle();
    global $DB;

    $idnumber = $idnumber ?? 'ahlia';

    $category = $DB->get_record('course_categories', ['idnumber' => $idnumber]);

    return $category ? (int) ($category->id) : -1;


}
