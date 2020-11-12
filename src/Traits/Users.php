<?php
/**
 *
 * Discourse Users
 *
 * @link https://docs.discourse.org/#tag/Users
 *
 **/

namespace MatthewJensen\LaravelDiscourse\Traits;

use stdClass;

trait Users
{

    /**
     *
     * Used for SSO logout action.
     *
     * @param string $userName username of user to be logged out.
     *
     * @return mixed HTTP return code and API return object
     */
    public function logoutUser(string $userName)
    {
        $userId = $this->getUserByUsername($userName)->apiresult->user->id;
        if (!is_int($userId)) {
            return false;
        }

        return $this->_postRequest('/admin/users/' . $userId . '/log_out', []);
    }


    /**
     *
     * createUser
     *
     * @param string $name name of new user
     * @param string $userName username of new user
     * @param string $emailAddress email address of new user
     * @param string $password password of new user
     *
     * @param bool $active
     * @param bool $approved
     * @return mixed HTTP return code and API return object
     */
    public function createUser(string $name, string $userName, string $emailAddress, string $password, bool $active = true, bool $approved = true)
    {
        $obj = $this->_getRequest('/users/hp.json');
        if ($obj->http_code !== 200) {
            return false;
        }

        $params = [
            'name' => $name,
            'username' => $userName,
            'email' => $emailAddress,
            'password' => $password,
            'challenge' => strrev($obj->apiresult->challenge),
            'password_confirmation' => $obj->apiresult->value,
            'active' => $active,
            'approved' => $approved
        ];

        return $this->_postRequest('/users', [$params]);
    }

    /**
     * activateUser
     *
     * @param integer $userId id of user to activate
     *
     * @return mixed HTTP return code
     */
    public function activateUser($userId)
    {
        return $this->_putRequest("/admin/users/{$userId}/activate", []);
    }

    /**
     * getUsernameByEmail
     *
     * @param string $email email of user
     * @param bool $useFilter use filter parameter in query
     * @return mixed HTTP return code and API return object
     */
    public function getUsernameByEmail(string $email, bool $useFilter = true)
    {
        if ($useFilter) {
            $result = $this->_getRequest('/admin/users/list/active.json',['filter' => $email]);
            return $this->searchUserInUsersByEmail($result->apiresult, $email);
        }

        $page = 1;

        do {
            $resultUsers = $this->_getRequest("/admin/users/list/active.json?show_emails=true&page={$page}");

            if ($userName = $this->searchUserInUsersByEmail($resultUsers->apiresult, $email)) {
                return $userName;
            }

            $page++;
        } while (!empty($resultUsers->apiresult));


        return false;
    }

    private function searchUserInUsersByEmail(array $users, string $email)
    {
        foreach ($users as $user) {
            if ($user->email === $email) {
                return $user->username;
            }
        }
        return false;
    }

    /**
     * getUserByUsername
     *
     * @param string $userName username of user
     *
     * @return mixed HTTP return code and API return object
     */
    public function getUserByUsername($userName)
    {
        return $this->_getRequest("/users/{$userName}.json");
    }

    /**
     * getUserByExternalID
     *
     * @param string $externalID external id of sso user
     *
     * @return mixed HTTP return code and API return object
     */
    function getUserByExternalID($externalID)
    {
        return $this->_getRequest("/users/by-external/{$externalID}.json");
    }

    /**
     * @param        $email
     * @param        $topicId
     * @param string $userName
     * @return stdClass
     */
    public function inviteUser($email, $topicId, $userName = 'system'): stdClass
    {
        $params = [
            'email' => $email,
            'topic_id' => $topicId
        ];

        return $this->_postRequest('/t/' . (int)$topicId . '/invite.json', [$params], $userName);
    }

    /**
     * getUserByEmail
     *
     * @param string $email email of user
     *
     * @return mixed user object
     */
    public function getUserByEmail($email)
    {
        $users = $this->_getRequest('/admin/users/list/active.json', [
            'filter' => $email
        ]);
        foreach ($users->apiresult as $user) {
            if (strtolower($user->email) === strtolower($email)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * getUserBadgesByUsername
     *
     * @param string $userName username of user
     *
     * @return mixed HTTP return code and list of badges for given user
     */
    public function getUserBadgesByUsername($userName)
    {
        return $this->_getRequest("/user-badges/{$userName}.json");
    }
}
