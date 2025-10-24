<?php

namespace ThankSong\Lecang\Facades;
use Illuminate\Support\Facades\Facade;

class Lecang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lecang';
    }
}