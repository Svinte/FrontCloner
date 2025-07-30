<?php

namespace Svinte\FrontCloner\Facades;

use Illuminate\Support\Facades\Facade;

class FrontCloner extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Svinte\FrontCloner\Services\CloneService::class;
    }
}
