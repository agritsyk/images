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
use Intervention\Image\ImageManager;
use frontend\models\events\PostCreatedEvent;

class PostForm extends Model
{
    const MAX_DESCRIPTION_LENGHT = 1000;
    const EVENT_POST_CREATED = 'post_created';

    public $picture;
    public $description;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->on(self::EVENT_BEFORE_VALIDATE, [$this, 'resizePicture']);
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);
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

    public function resizePicture()
    {
        if ($this->picture->error) {
            /* В объекте UploadedFile есть свойство error. Если в нем "1", значит
            * произошла ошибка и работать с изображением не нужно, прерываем
            * выполнение метода */
            return;
        }

        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);

        // 3-й аргумент - органичения - специальные настройки при изменении размера
        $image->resize($width, $height, function ($constraint) {

            // Пропорции изображений оставлять такими же (например, для избежания широких или вытянутых лиц)
            $constraint->aspectRatio();

            // Изображения, размером меньше заданных $width, $height не будут изменены:
            $constraint->upsize();

        })->save();
    }

    public function save()
    {

        if ($this->validate()) {
            $post = new Post();
            $post->description = $this->description;
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            if ($post->save(false)) {
                $event = new PostCreatedEvent();
                $event->user = $this->user;
                $event->post = $post;
                $this->trigger(self::EVENT_POST_CREATED, $event);
                return true;
            }
        }
        return false;
    }

    private function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}