<?php
/**
*
* Discourse Categories
*
* @link https://docs.discourse.org/#tag/Categories
*
**/

namespace MatthewJensen\LaravelDiscourse\Traits;

trait Categories {

    /** @noinspection MoreThanThreeArgumentsInspection * */
    /**
    * createCategory
    *
    * @param string $categoryName name of new category
    * @param string $color        color code of new category (six hex chars, no #)
    * @param string $textColor    optional color code of text for new category
    * @param string $userName     optional user to create category as
    *
    * @return mixed HTTP return code and API return object
    **/
    public function createCategory(string $categoryName, string $color = '003399', string $textColor = '636b6f', $parent_category_id = 5, string $userName = 'system')
    {
        $params = [
            'name'       => $categoryName,
            'color'      => $color,
            'text_color' => $textColor,
            'parent_category_id' => $parent_category_id
        ];

        return $this->_postRequest('/categories', [$params], $userName);
    }
    /**
        * @param $categoryName
        * @return \stdClass
        */
    public function getSubCategories($parentSlug)
    {
        $response = $this->_getRequest("/categories.json", ['parent_category_id' => 5]);
        return  $response->apiresult->category_list->categories ?? [];
    }

    /**
        * @param $categoryName
        * @return \stdClass
        */
    public function getCategory($categoryName): \stdClass
    {
        return $this->_getRequest("/c/{$categoryName}.json");
    }
    public function getCategoryById($id): \stdClass
    {
        return $this->_getRequest("/c/{$id}.json");
    }

    /**
        * Edit Category
        *
        * @param integer    $catid
        * @param string     $name
        * @param int|string $parent_category_id
        * @param            $groupname
        * @param int|string $position
        * @param string     $slug
        * @param array      $permissions
        * @return mixed HTTP return code and API return object
        */
    public function updateCat(
        $catid,
        $allow_badges = 'true',
        $auto_close_based_on_last_post = 'false',
        $auto_close_hours = '',
        $background_url,
        $color = '0E76BD',
        $contains_messages = 'false',
        $email_in = '',
        $email_in_allow_strangers = 'false',
        $logo_url = '',
        $name = '',
        $parent_category_id = '',
        $groupname,
        $position = '',
        $slug = '',
        $suppress_from_homepage = 'false',
        $text_color = 'FFFFFF',
        $topic_template = '',
        $permissions
    ) {
        $params = [
            'allow_badges'                  => $allow_badges,
            'auto_close_based_on_last_post' => $auto_close_based_on_last_post,
            'auto_close_hours'              => $auto_close_hours,
            'background_url'                => $background_url,
            'color'                         => $color,
            'contains_messages'             => $contains_messages,
            'email_in'                      => $email_in,
            'email_in_allow_strangers'      => $email_in_allow_strangers,
            'logo_url'                      => $logo_url,
            'name'                          => $name,
            'parent_category_id'            => $parent_category_id,
            'position'                      => $position,
            'slug'                          => $slug,
            'suppress_from_homepage'        => $suppress_from_homepage,
            'text_color'                    => $text_color,
            'topic_template'                => $topic_template
        ];

        # Add the permissions - this is an array of group names and integer permission values.
        if (count($permissions) > 0) {
            foreach ($permissions as $key => $value) {
                $params['permissions[' . $key . ']'] = $permissions[$key];
            }
        }

        # This must PUT
        return $this->_putRequest('/categories/' . $catid, [$params]);
    }
    /** @noinspection MoreThanThreeArgumentsInspection * */
    /**
        * Edit Category
        *
        * @param integer    $catid
        * @param string     $allow_badges
        * @param string     $auto_close_based_on_last_post
        * @param string     $auto_close_hours
        * @param string     $background_url
        * @param string     $color
        * @param string     $contains_messages
        * @param string     $email_in
        * @param string     $email_in_allow_strangers
        * @param string     $logo_url
        * @param string     $name
        * @param int|string $parent_category_id
        * @param            $groupname
        * @param int|string $position
        * @param string     $slug
        * @param string     $suppress_from_homepage
        * @param string     $text_color
        * @param string     $topic_template
        * @param array      $permissions
        * @return mixed HTTP return code and API return object
        */
    public function updateCategory(
        $catid,
        $allow_badges = 'true',
        $auto_close_based_on_last_post = 'false',
        $auto_close_hours = '',
        $background_url,
        $color = '0E76BD',
        $contains_messages = 'false',
        $email_in = '',
        $email_in_allow_strangers = 'false',
        $logo_url = '',
        $name = '',
        $parent_category_id = '',
        $groupname,
        $position = '',
        $slug = '',
        $suppress_from_homepage = 'false',
        $text_color = 'FFFFFF',
        $topic_template = '',
        $permissions
    ) {
        $params = [
            'allow_badges'                  => $allow_badges,
            'auto_close_based_on_last_post' => $auto_close_based_on_last_post,
            'auto_close_hours'              => $auto_close_hours,
            'background_url'                => $background_url,
            'color'                         => $color,
            'contains_messages'             => $contains_messages,
            'email_in'                      => $email_in,
            'email_in_allow_strangers'      => $email_in_allow_strangers,
            'logo_url'                      => $logo_url,
            'name'                          => $name,
            'parent_category_id'            => $parent_category_id,
            'position'                      => $position,
            'slug'                          => $slug,
            'suppress_from_homepage'        => $suppress_from_homepage,
            'text_color'                    => $text_color,
            'topic_template'                => $topic_template
        ];

        # Add the permissions - this is an array of group names and integer permission values.
        if (count($permissions) > 0) {
            foreach ($permissions as $key => $value) {
                $params['permissions[' . $key . ']'] = $permissions[$key];
            }
        }

        # This must PUT
        return $this->_putRequest('/categories/' . $catid, [$params]);
    }

    /**
        * getCategories
        *
        * @return mixed HTTP return code and API return object
        */
    public function getCategories()
    {
        $response = $this->_getRequest('/site.json');
        return $response->apiresult->categories;
    }
    public function deleteCategory($id)
    {
        $response = $this->_deleteRequest("/categories/{$id}", []);
        return $response->apiresult;
    }
}
