<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use frontend\components\Storage;
use yii\redis\Connection;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 */
class Post extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
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
     * @return mixed
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->one();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id'])->all();
    }


    /**
     * @param User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * @param User $user
     */
    public function unLike(User $user)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:likes");
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }

    /**
     * @return int|string
     */
    public function getCommentsCount()
    {
        return Comment::find()->where(['post_id' => $this->getId()])->count();
    }
}
