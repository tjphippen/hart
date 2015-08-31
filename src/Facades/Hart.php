<?php namespace Tjphippen\Hart\Facades;

use Illuminate\Support\Facades\Facade;

class Hart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hart';
    }
}