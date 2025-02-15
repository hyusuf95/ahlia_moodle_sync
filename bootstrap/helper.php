<?php

function sayHello()
{
    return 'Hello';
}


function parseidnumber(string $idnumber)
{
    $parts = explode('_', $idnumber);
    return (int) $parts[1];
}


function get_department_idnumber( $department_id)
{
    return "department_$department_id";
}

function get_college_idnumber( $college_id)
{
    return "college_$college_id";
}
