<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 07.02.2019
 * Time: 20:13
 */

namespace frontend\modules\post\models\forms;

use frontend\models\Comment;
use frontend\models\Post;
use yii\base\Model;
use frontend\models\User;


class CommentForm extends Model
{
    const MAX_COMMENT_LENGHT = 20;
    public $text;

    private $user;
    private $post;

    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'max' => self::MAX_COMMENT_LENGHT],
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => 'Comment',
        ];
    }

    /**
     * CommentForm constructor.
     * @param User $user
     * @param Post $post
     */
    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $comment = new Comment();
            $comment->text = $this->text;
            $comment->user_id = $this->user->getId();
            $comment->post_id = $this->post->getId();
            $this->post->addNewComment();
            return $comment->save(false);
        }
    }

    /**
     * @param $postId
     * @param $id
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function update($postId, $id)
    {
        if ($this->validate()) {
            $comment = Comment::findOne($id);
            $comment->id = $id;
            $comment->text = $this->text;
            $comment->user_id = $this->user->getId();
            $comment->post_id = $this->post->getId();
            return $comment->update(false);
        }
    }
}