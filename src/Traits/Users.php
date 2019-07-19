<?php
/**
*
* Discourse Users
*
* @link https://docs.discourse.org/#tag/Users
*
**/

namespace MatthewJensen\LaravelDiscourse\Traits;

trait Users {

    /**
    * 
    * Used for SSO logout action.
    *
    * @param string $userName     username of user to be logged out.
    *
    * @return mixed HTTP return code and API return object
    */
    public function logoutUser(string $userName)
    {
        $userid  = $this->getUserByUsername($userName)->apiresult->user->id;
        if (!\is_int($userid)) {
            return false;
        }

        return $this->_postRequest('/admin/users/'.$userid.'/log_out', []);
    }


    /**
    *
    * createUser
    *
    * @param string $name         name of new user
    * @param string $userName     username of new user
    * @param string $emailAddress email address of new user
    * @param string $password     password of new user
    *
    * @return mixed HTTP return code and API return object
    *
    * @noinspection MoreThanThreeArgumentsInspection
    *
    */
    public function createUser(string $name, string $userName, string $emailAddress, string $password)
    {
        $obj = $this->_getRequest('/users/hp.json');
        if ($obj->http_code !== 200) {
            return false;
        }

        $params = [
            'name'                  => $name,
            'username'              => $userName,
            'email'                 => $emailAddress,
            'password'              => $password,
            'challenge'             => strrev($obj->apiresult->challenge),
            'password_confirmation' => $obj->apiresult->value
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
        *
        * @return mixed HTTP return code and API return object
        */
    public function getUsernameByEmail($email)
    {
        $users = $this->_getRequest('/admin/users/list/active.json');
        //$users = $this->_getRequest('/admin/users/list/active.json?filter=' . urlencode($email));
        foreach ($users->apiresult as $user) {
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
        * @param string $externalID     external id of sso user
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
        * @return \stdClass
        */
    public function inviteUser($email, $topicId, $userName = 'system'): \stdClass
    {
        $params = [
            'email'    => $email,
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
