<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 31.01.2019
 * Time: 19:56
 */

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;
use Intervention\Image\ImageManager;

class PictureForm extends Model
{
    const EVENT_AFTER_PICTURE_UPDATE = 'picture_updated';

    public $picture;


    public function rules()
    {
        return [
            [['picture'], 'file',
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }

    public function __construct()
    {
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
        $this->on(self::EVENT_AFTER_PICTURE_UPDATE, [Yii::$app->feedService, 'updateFeedProfilePicture']);
    }

    public function resizePicture()
    {
        if ($this->picture->error) {
            /* В объекте UploadedFile есть свойство error. Если в нем "1", значит
            * произошла ошибка и работать с изображением не нужно, прерываем
            * выполнение метода */
            return;
        }

        $width = Yii::$app->params['profilePicture']['maxWidth'];
        $height = Yii::$app->params['profilePicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);

        // 3-й аргумент (анонимная функция - органичения - специальные настройки при изменении размера
        $image->resize($width, $height, function ($constraint) {

            // Пропорции изображений оставлять такими же (например, для избежания широких или вытянутых лиц)
            $constraint->aspectRatio();

            // Изображения, размером меньше заданных $width, $height не будут изменены:
            $constraint->upsize();

        })->save();
    }

    public function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}