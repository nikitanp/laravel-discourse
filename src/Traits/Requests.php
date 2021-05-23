<?php

namespace NikitaMikhno\LaravelDiscourse\Traits;

trait Requests
{
    /**
     * @param string $reqString
     * @param array $paramArray
     * @param string $apiUser
     * @param bool $useArrayNumIndexes
     * @return \stdClass
     */
    private function getRequest(
        string $reqString,
        array $paramArray = [],
        string $apiUser = 'system',
        bool $useArrayNumIndexes = true
    ): \stdClass {
        $queryParams = $useArrayNumIndexes
            ? http_build_query($paramArray)
            : preg_replace(
                '/%5B\d+%5D/',
                '%5B%5D',
                http_build_query($paramArray)
            );

        $url = sprintf('%s://%s%s?%s', $this->protocol, $this->hostname, ltrim($reqString, '/'), $queryParams);

        $ch = $this->initCurl($url, $apiUser);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $body = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = new \stdClass();
        $result->http_code = $responseCode;

        $json = json_decode($body);
        $result->apiresult = $body;
        if (json_last_error() === JSON_ERROR_NONE) {
            $result->apiresult = $json;
        }

        return $result;
    }

    /**
     * @param string $reqString
     * @param array $paramArray
     * @param string $apiUser
     * @param string $method
     * @return \stdClass
     **/
    private function putPost(
        string $reqString,
        array $paramArray,
        string $apiUser = 'system',
        string $method = 'POST'
    ): \stdClass {
        $url = sprintf(
            '%s://%s%s',
            $this->protocol,
            $this->hostname,
            ltrim($reqString, '/')
        );

        $query = '';

        if (isset($paramArray['group']) && is_array($paramArray['group'])) {
            $query = http_build_query($paramArray);
        } elseif (isset($paramArray[0]) && is_array($paramArray[0])) {
            foreach ($paramArray[0] as $param => $value) {
                $query .= $param . '=' . urlencode($value) . '&';
            }
        }

        $query = trim($query, '&');

        $ch = $this->initCurl($url, $apiUser);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $body = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = new \stdClass();
        $json = json_decode($body);
        $result->apiresult = $body;
        if (json_last_error() === JSON_ERROR_NONE) {
            $result->apiresult = $json;
        }

        $result->http_code = $responseCode;

        return $result;
    }

    /**
     * @param string $reqString
     * @param array $paramArray
     * @param string $apiUser
     * @return \stdClass
     */
    private function deleteRequest(
        string $reqString,
        array $paramArray,
        string $apiUser = 'system'
    ): \stdClass {
        return $this->putPost(
            $reqString,
            $paramArray,
            $apiUser,
            'DELETE'
        );
    }

    /**
     * @param string $reqString
     * @param array $paramArray
     * @param string $apiUser
     * @return \stdClass
     */
    private function putRequest(
        string $reqString,
        array $paramArray,
        string $apiUser = 'system'
    ): \stdClass {
        return $this->putPost(
            $reqString,
            $paramArray,
            $apiUser,
            'PUT'
        );
    }

    /**
     * @param string $reqString
     * @param array $paramArray
     * @param string $apiUser
     * @return \stdClass
     */
    private function postRequest(
        string $reqString,
        array $paramArray,
        string $apiUser = 'system'
    ): \stdClass {
        return $this->putPost(
            $reqString,
            $paramArray,
            $apiUser
        );
    }

    private function initCurl(string $url, string $apiUser = 'system')
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new \RuntimeException('Error in curl_init!');
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Api-Key: " . $this->apiKey,
            "Api-Username: $apiUser"
        ]);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return $ch;
    }
}
