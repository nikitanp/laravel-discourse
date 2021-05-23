<?php

namespace NikitaMikhno\LaravelDiscourse\Contracts;

interface ApiClient
{
    // users
    public function logoutUser(string $userName);

    public function createUser(
        string $name,
        string $userName,
        string $emailAddress,
        string $password,
        bool $active,
        bool $approved
    );

    public function activateUser(int $userId);

    public function getUsernameByEmail(string $email, bool $useFilter = true);

    public function getUserByUsername(string $userName);

    public function inviteUser(
        string $email,
        int $topicId,
        string $userName = 'system'
    );

    public function getUserByEmail(string $email);

    public function getUserByExternalID(string $externalID);

    public function getUserBadgesByUsername(string $userName);

    public function getUserEmails(string $username);

    // groups
    public function getGroups();

    public function getGroup($groupname);

    public function joinGroup($groupname, $username);

    public function getGroupIdByGroupName($groupname);

    public function leaveGroup($groupname, $username);

    public function getGroupMembers($group);

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
    );

    public function removeGroup(string $groupname);


    // categories
    public function createCategory(
        string $categoryName,
        string $color = '003399',
        string $textColor = '636b6f',
        $parent_category_id = 5,
        string $userName = 'system'
    );

    public function getSubCategories($parentSlug);

    public function getCategory($categoryName);

    public function getCategoryById($id);

    public function updateCategory(
        $catid,
        $allow_badges,
        $auto_close_based_on_last_post,
        $auto_close_hours,
        $background_url,
        $color,
        $contains_messages,
        $email_in,
        $email_in_allow_strangers,
        $logo_url,
        $name,
        $parent_category_id,
        $groupname,
        $position,
        $slug,
        $suppress_from_homepage,
        $text_color,
        $topic_template,
        $permissions
    );

    public function getCategories();

    public function deleteCategory($id);

    // topics
    public function makeTopicUrl(string $slug, $id);

    public function createTopic(
        string $topicTitle,
        string $bodyText,
        string $categoryId,
        string $userName,
        int $replyToId = 0
    );

    public function getTopic($topicId);

    public function topTopics($category, $period = 'daily');

    public function latestTopics($category);

    public function deleteTopic(int $topicId);

    // posts
    public function createPost(
        string $bodyText,
        $topicId,
        string $userName
    );

    public function getPostsByNumber($topic_id, $post_number);

    public function updatePost(
        $bodyhtml,
        $post_id,
        $userName = 'system'
    );

    public function getSpecificPostsInTopic(int $topicId, int $limit = 10);

    // tags
    public function getTag($name);

    public function getLatestTopicsForTag($name);

    // upload
    public function uploadFile(
        string $type,
        string $file,
        ?int $userId,
        bool $synchronous
    );
}
