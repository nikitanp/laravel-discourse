<?php

namespace NikitaMikhno\LaravelDiscourse\Facades;

use Illuminate\Support\Facades\Facade;

class DiscourseAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \NikitaMikhno\LaravelDiscourse\Contracts\SingleSignOn::class;
    }
}
