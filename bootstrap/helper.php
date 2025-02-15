<?php


function sayHello()
{
    return 'Hello';
}



function load_moodle()
{

    $root = config('sync.moodle.root');
    require_once $root . '/config.php';
}

function get_category_id_by_idnumber()
{
    load_moodle();
    global $DB;

    $idnumber = $idnumber ?? 'ahlia';

    $category = $DB->get_record('course_categories', ['idnumber' => $idnumber]);

    return $category ? (int) ($category->id) : -1;


}
