<?php

namespace App\Traits;

trait HasIdNumber
{
    public static function find2(int|string $idnumber)
    {
        return self::where('idnumber', $idnumber)->first();
    }
}
