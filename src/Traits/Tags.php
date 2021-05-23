<?php

namespace NikitaMikhno\LaravelDiscourse\Traits;

trait Tags
{
    /**
     * @param $name
     * @return array
     */
    public function getTag($name)
    {
        return $this->getRequest("/tags/$name")->apiresult ?? [];
    }
}
