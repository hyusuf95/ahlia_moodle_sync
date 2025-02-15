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


