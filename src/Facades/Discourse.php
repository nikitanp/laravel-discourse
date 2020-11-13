<?php

namespace NikitaMikhno\LaravelDiscourse\Facades;

use Illuminate\Support\Facades\Facade;

class Discourse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \NikitaMikhno\LaravelDiscourse\Contracts\ApiClient::class;
    }
}
