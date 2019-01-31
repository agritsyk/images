<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 31.01.2019
 * Time: 20:17
 */

namespace frontend\components;

use yii\web\UploadedFile;

interface StorageInterface
{
    public function saveUploadedFile(UploadedFile $file);

    public function getFile(string $file);
}