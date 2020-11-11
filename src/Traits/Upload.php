<?php
/**
 *
 * Discourse Upload
 *
 * @link https://docs.discourse.org/#tag/Upload
 *
 **/

namespace MatthewJensen\LaravelDiscourse\Traits;

trait Upload
{

    public array $uploadTypes = [
        'avatar',
        'profile_background',
        'card_background',
        'custom_emoji',
        'composer'
    ];

    /**
     *
     * Upload a file like an image or an avatar.
     *
     * @param string $type
     * @param string $file
     * @param int|null $userId
     * @param bool $synchronous
     * @return mixed HTTP return code and API return object
     * @throws \Exception
     */
    public function uploadFile(string $type, string $file, ?int $userId = null, bool $synchronous = false)
    {
        if (!in_array($type, $this->uploadTypes, true)) {
            throw new \Exception('Unsupported upload type!');
        }

        $requestData = [
            'type' => $type,
            'file' => $file,
            'user_id' => $userId,
            'synchronous' => $synchronous,
        ];

        return $this->_postRequest('/uploads.json', $requestData);
    }

}
