<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $text
 * @property int $created_at
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'text' => 'Text',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id'])->one();
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->one();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getCommentText($id)
    {
        $comment = Comment::find()->select('text')->where(['id' => $id])->one();
        return $comment->text;
    }


}
