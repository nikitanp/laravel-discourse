<?php
/**
 *
 * Discourse Topics
 *
 * @link https://docs.discourse.org/#tag/Topics
 *
 **/

namespace MatthewJensen\LaravelDiscourse\Traits;

trait Topics
{
    public function getLatestTopicsForTag($name)
    {
        $url = "/tags/{$name}/l/latest.json?order=default&ascending=false&filter=tags/{$name}/l/latest";
        return $this->_getRequest($url)->apiresult->topic_list->topics ?? [];
    }



    //////////////  TOPICS

    /**
     * createTopic
     *
     * @param string $topicTitle title of topic
     * @param string $bodyText body text of topic post
     * @param string $categoryId
     * @param string $userName user to create topic as
     * @param int $replyToId post id to reply as
     * @return mixed HTTP return code and API return object
     * @internal param string $categoryName category to create topic in
     **/
    public function createTopic(string $topicTitle, string $bodyText, string $categoryId, string $userName, int $replyToId = 0)
    {
        $params = [
            'title' => $topicTitle,
            'raw' => $bodyText,
            'category' => $categoryId,
            'archetype' => 'regular',
            'reply_to_post_number' => $replyToId
        ];

        return $this->_postRequest('/posts', [$params], $userName);
    }

    /**
     * getTopic
     *
     * @param $topicId
     * @return \stdClass
     */
    public function getTopic($topicId): \stdClass
    {
        return $this->_getRequest("/t/{$topicId}.json");
    }

    /**
     * topTopics
     *
     * @param string $category slug of category
     * @param string $period daily, weekly, monthly, yearly
     *
     * @return mixed HTTP return code and API return object
     */
    public function topTopics($category, $period = 'daily')
    {
        return $this->_getRequest('/c/' . $category . '/l/top/' . $period . '.json');
    }

    /**
     * latestTopics
     *
     * @param string $category slug of category
     *
     * @return mixed HTTP return code and API return object
     */
    public function latestTopics($category)
    {
        return $this->_getRequest('/c/' . $category . '/l/latest.json');
    }
}
