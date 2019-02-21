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

class UploadProfilePicture extends Event
{
    /**
     * @var User
     */
    public $user;


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

}