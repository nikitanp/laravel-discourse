<?php

/**
 * Discourse API Client
 *
 * Expanded on original by DiscourseHosting
 *
 * @author       Matthew Jensen, Nikita Mikhno
 * @license      http://www.gnu.org/licenses/gpl-3.0.html GPL-3.0
 * @link         https://github.com/nikitanp/laravel-discourse-client
 **/

namespace NikitaMikhno\LaravelDiscourse;

use NikitaMikhno\LaravelDiscourse\Contracts\ApiClient;
use NikitaMikhno\LaravelDiscourse\Traits\Categories;
use NikitaMikhno\LaravelDiscourse\Traits\Groups;
use NikitaMikhno\LaravelDiscourse\Traits\Posts;
use NikitaMikhno\LaravelDiscourse\Traits\Requests;
use NikitaMikhno\LaravelDiscourse\Traits\Tags;
use NikitaMikhno\LaravelDiscourse\Traits\Topics;
use NikitaMikhno\LaravelDiscourse\Traits\Upload;
use NikitaMikhno\LaravelDiscourse\Traits\Users;
use NikitaMikhno\LaravelDiscourse\SingleSignOn;

class Discourse implements ApiClient
{
    // Most of the heavy lifting api requests are done in traits:
    use Requests, Users, Groups, Posts, Topics, Categories, Tags, Upload;

    private $_protocol;
    private $_apiKey;
    private $_dcHostname;

    /**
     *
     * @param        $host   host name of the forum.
     * @param null $apiKey
     * @param string $protocol
     */
    public function __construct($host, $apiKey = null, $protocol = 'https')
    {
        $this->_dcHostname = $host;
        $this->_apiKey = $apiKey;
        $this->_protocol = $protocol;
    }

    /**
     *
     * @param $siteSetting
     * @param $value
     * @return \stdClass
     *
     */
    public function changeSiteSetting($siteSetting, $value): \stdClass
    {
        $params = [
            $siteSetting => $value
        ];

        return $this->_putRequest('/admin/site_settings/' . $siteSetting, [$params]);
    }


}
