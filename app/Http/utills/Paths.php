<?php

namespace App\Http\utills;


class Paths
{
    public static function getPublicStoragePath()
    {
        return Config('app.url').'/storage';
    }

    public static function publicStoragePath()
    {
        return '';
    }
}
