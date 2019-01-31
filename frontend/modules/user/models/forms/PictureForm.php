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

class PictureForm extends Model
{
    public $picture;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'extensions' => ['jpg'],
                'checkExtensionByMimeType' => true,
            ],
        ];
    }

    public function save()
    {
        return 1;
    }
}