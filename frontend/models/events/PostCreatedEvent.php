<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 19.02.2019
 * Time: 20:01
 */

namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

class PostCreatedEvent extends Event
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var Post
     */
    public $post;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }
}