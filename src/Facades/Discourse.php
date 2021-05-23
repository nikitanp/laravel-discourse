<?php

namespace NikitaMikhno\LaravelDiscourse\Facades;

use Illuminate\Support\Facades\Facade;
use NikitaMikhno\LaravelDiscourse\Contracts\ApiClient;

class Discourse extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ApiClient::class;
    }
}
