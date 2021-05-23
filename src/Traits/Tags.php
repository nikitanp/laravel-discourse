<?php
/**
 *
 * Discourse Tags
 *
 * @link https://docs.discourse.org/#tag/Tags
 *
 **/

namespace NikitaMikhno\LaravelDiscourse\Traits;

trait Tags
{
    public function getTag($name)
    {
        $url = "/tags/{$name}";
        return $this->_getRequest($url)->apiresult ?? [];
    }
}
