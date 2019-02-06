<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 05.02.2019
 * Time: 20:00
 */

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;

class PostForm extends Model
{
    const MAX_DESCRIPTION_LENGHT = 1000;

    public $picture;
    public $description;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function rules()
    {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
                ],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
        ];
    }

    public function save()
    {

        if ($this->validate()) {
            $post = new Post();
            $post->description = $this->description;
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return $post->save(false);
        }
    }

    private function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}