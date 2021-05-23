<?php

namespace NikitaMikhno\LaravelDiscourse\Facades;

use Illuminate\Support\Facades\Facade;
use NikitaMikhno\LaravelDiscourse\Contracts\SingleSignOn;

class DiscourseAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SingleSignOn::class;
    }
}
