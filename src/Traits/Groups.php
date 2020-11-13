<?php
/**
 *
 * Discourse Groups
 *
 * @link https://docs.discourse.org/#tag/Groups
 *
 **/

namespace NikitaMikhno\LaravelDiscourse\Traits;

trait Groups
{

    /**
     *
     * Get all groups
     *
     * @return mixed HTTP return code and API return object
     *
     **/
    public function getGroups()
    {
        return $this->_getRequest('/groups.json');
    }

    /**
     *
     * Group by group name
     *
     * @param string $group name of group
     * @return mixed HTTP return code and API return object
     *
     **/
    public function getGroup($groupname)
    {
        return $this->_getRequest('/groups/' . $groupname . '.json');
    }

    /**
     *
     * joinGroup
     *
     * @param string $groupname name of group
     * @param string $username user to add to the group
     *
     * @return mixed HTTP return code and API return object
     *
     **/
    public function joinGroup($groupname, $username)
    {
        $groupId = $this->getGroupIdByGroupName($groupname);
        if (!$groupId) {
            return false;
        }

        $params = [
            'usernames' => $username
        ];

        return $this->_putRequest('/groups/' . $groupId . '/members.json', [$params]);
    }

    /**
     * getGroupIdByGroupName
     *
     * @param string $groupname name of group
     *
     * @return mixed id of the group, or false if nonexistent
     *
     **/
    public function getGroupIdByGroupName($groupname)
    {
        $obj = $this->getGroup($groupname);
        if ($obj->http_code !== 200) {
            return false;
        }

        return $obj->apiresult->group->id;
    }

    /**
     *
     * @param $groupname
     * @param $username
     * @return bool|\stdClass
     *
     **/
    public function leaveGroup($groupname, $username)
    {
        $userid = $this->getUserByUsername($username)->apiresult->user->id;
        $groupId = $this->getGroupIdByGroupName($groupname);
        if (!$groupId) {
            return false;
        }
        $params = [
            'user_id' => $userid
        ];

        return $this->_deleteRequest('/groups/' . $groupId . '/members.json', [$params]);
    }

    /**
     * getGroupMembers
     *
     * @param string $group name of group
     * @return mixed HTTP return code and API return object
     *
     */
    public function getGroupMembers($group)
    {
        return $this->_getRequest("/groups/{$group}/members.json");
    }

    /**
     *
     * @param string $groupname name of group to be created
     * @param array $usernames users in the group
     *
     * @param int $aliaslevel
     * @param string $visible
     * @param string $automemdomain
     * @param string $automemretro
     * @param string $title
     * @param string $primegroup
     * @param string $trustlevel
     * @return mixed HTTP return code and API return object
     *
     **/
    public function addGroup(
        $groupname,
        array $usernames = [],
        $aliaslevel = 3,
        $visible = 'true',
        $automemdomain = '',
        $automemretro = 'false',
        $title = '',
        $primegroup = 'false',
        $trustlevel = '0'
    )
    {
        $groupId = $this->getGroupIdByGroupName($groupname);
        if ($groupId) {
            return false;
        }

        $params = [
            'group' => [
                'name' => $groupname,
                'usernames' => implode(',', $usernames),
                'alias_level' => $aliaslevel,
                'visible' => $visible,
                'automatic_membership_email_domains' => $automemdomain,
                'automatic_membership_retroactive' => $automemretro,
                'title' => $title,
                'primary_group' => $primegroup,
                'grant_trust_level' => $trustlevel
            ]
        ];

        return $this->_postRequest('/admin/groups', $params);
    }

    /**
     *
     * @param string $groupname
     * @return bool|\stdClass
     *
     */
    public function removeGroup(string $groupname)
    {
        $groupId = $this->getGroupIdByGroupName($groupname);
        if (!$groupId) {
            return false;
        }

        return $this->_deleteRequest('/admin/groups/' . (string)$groupId, []);
    }
}
