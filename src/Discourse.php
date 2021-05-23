<?php

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

class Discourse implements ApiClient
{
    // Most of the heavy lifting api requests are done in traits:
    use Requests;
    use Users;
    use Groups;
    use Posts;
    use Topics;
    use Categories;
    use Tags;
    use Upload;

    /**
     * @var string
     */
    private $protocol;
    /**
     * @var string|null
     */
    private $apiKey;
    /**
     * @var string
     */
    private $hostname;

    /**
     *
     * @param string $host host name of the forum.
     * @param string|null $apiKey
     * @param string $protocol
     */
    public function __construct(string $host, ?string $apiKey = null, string $protocol = 'https')
    {
        $this->hostname = $host;
        $this->apiKey = $apiKey;
        $this->protocol = $protocol;
    }

    /**
     * @param $siteSetting
     * @param $value
     * @return \stdClass
     */
    public function changeSiteSetting($siteSetting, $value): \stdClass
    {
        $params = [
            $siteSetting => $value,
        ];

        return $this->_putRequest('/admin/site_settings/' . $siteSetting, [$params]);
    }
}
