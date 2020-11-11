<?php

/**
* Discourse API Client
*
* Expanded on original by DiscourseHosting
*
* @author       Matthew Jensen
* @license      http://www.gnu.org/licenses/gpl-3.0.html GPL-3.0
* @link         https://github.com/matthew-jensen/laravel-discourse-client
*
* @noinspection MoreThanThreeArgumentsInspection
**/

namespace MatthewJensen\LaravelDiscourse;

use MatthewJensen\LaravelDiscourse\Contracts\ApiClient;
use MatthewJensen\LaravelDiscourse\Traits\Requests;
use MatthewJensen\LaravelDiscourse\Traits\Upload;
use MatthewJensen\LaravelDiscourse\Traits\Users;
use MatthewJensen\LaravelDiscourse\Traits\Groups;
use MatthewJensen\LaravelDiscourse\Traits\Posts;
use MatthewJensen\LaravelDiscourse\Traits\Topics;
use MatthewJensen\LaravelDiscourse\Traits\Categories;
use MatthewJensen\LaravelDiscourse\Traits\Tags;
use SingleSignOn;

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
    * @param null   $apiKey
    * @param string $protocol
    */
    public function __construct($host, $apiKey = null, $protocol = 'https')
    {
        $this->_dcHostname = $host;
        $this->_apiKey     = $apiKey;
        $this->_protocol   = $protocol;
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
