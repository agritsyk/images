<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 18.02.2019
 * Time: 22:29
 */

namespace frontend\components;

use yii\base\Component;
use yii\base\Event;
use frontend\models\Feed;


class FeedService extends Component
{
    public function addToFeeds(Event $event)
    {
        $user = $event->getUser();
        $followers = $user->getFollowers();
        $post = $event->getPost();

//        echo '<pre>';
//        print_r($post);
//        echo '</pre>';
//        die('add post to feeds');

        foreach ($followers as $follower) {
            $feedItem = new Feed();
            $feedItem->user_id = $follower['id'];
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->nickname;
            $feedItem->author_picture = $user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();
        }
    }

    public function updateFeedProfilePicture(Event $event)
    {
        $user = $event->getUser();

        $feedList = Feed::getFeedListByAuthorId($user->getId());

        foreach ($feedList as $feedItem) {
            $feedItem->author_picture = $user->getPicture();
            $feedItem->save(false);
        }
    }
}