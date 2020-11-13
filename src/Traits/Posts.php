<?php
/**
 *
 * Discourse Posts
 *
 * @link https://docs.discourse.org/#tag/Users
 *
 **/

namespace NikitaMikhno\LaravelDiscourse\Traits;

trait Posts
{

    /**
     * createPost
     *
     * NOT WORKING YET
     *
     * @param $bodyText
     * @param $topicId
     * @param $userName
     * @return \stdClass
     */
    public function createPost(string $bodyText, $topicId, string $userName): \stdClass
    {
        $params = [
            'raw' => $bodyText,
            'archetype' => 'regular',
            'topic_id' => $topicId
        ];

        return $this->_postRequest('/posts', [$params], $userName);
    }

    /**
     * getPostsByNumber
     *
     * @param $topic_id
     * @param $post_number
     * @return mixed HTTP return code and API return object
     */
    public function getPostsByNumber($topic_id, $post_number)
    {
        return $this->_getRequest('/posts/by_number/' . $topic_id . '/' . $post_number . '.json');
    }

    /**
     * UpdatePost
     *
     * @param        $bodyhtml
     * @param        $post_id
     * @param string $userName
     * @return \stdClass
     */
    public function updatePost($bodyhtml, $post_id, $userName = 'system'): \stdClass
    {
        $bodyraw = htmlspecialchars_decode($bodyhtml);
        $params = [
            'post[cooked]' => $bodyhtml,
            'post[edit_reason]' => '',
            'post[raw]' => $bodyraw
        ];

        return $this->_putRequest('/posts/' . $post_id, [$params], $userName);
    }

    /**
     * get count posts from topic
     * @param int $topicId
     * @param int $limit
     * @return object|null
     */
    public function getSpecificPostsInTopic(int $topicId, int $limit = 10)
    {
        $discourseTopic = $this->getTopic($topicId);
        if (!isset($discourseTopic->apiresult->post_stream->posts) || empty($discourseTopic->apiresult->post_stream->posts)) {
            return null;
        }

        $postIds = [];
        $count = 0;
        foreach ($discourseTopic->apiresult->post_stream->posts as $streamPostItem) {
            $postIds[] = $streamPostItem->id;
            $count++;

            if ($limit <= $count) {
                break;
            }
        }

        if (!empty($postIds)) {
            return $this->_getRequest("/t/{$topicId}/posts.json", ['post_ids' => $postIds])->apiresult->post_stream->posts ?? null;
        }

        return null;
    }
}
