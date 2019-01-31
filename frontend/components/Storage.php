<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 31.01.2019
 * Time: 20:24
 */

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use frontend\components\StorageInterface;

class Storage extends Component implements StorageInterface
{
    private $fileName;

    /**
     * @param UploadedFile $file
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->preparePath($file);

        if ($path && $file->saveAs($path)) {
            return $this->fileName;
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws \yii\base\Exception
     */
    protected function preparePath(UploadedFile $file)
    {
        $this->fileName = $this->getFileName($file);

        $path = $this->getStoragePath() . $this->fileName;

        $path = FileHelper::normalizePath($path);
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFileName(UploadedFile $file)
    {
        $hash = sha1_file($file->tempName);

        $name = substr_replace($hash, '/', 2, 0);
        $name = substr_replace($name, '/', 5, 0);

        return $name . $file->extension;
    }

    /**
     * @return bool|string
     */
    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    public function getFile(string $filename)
    {
        return Yii::$app->params['storageUri'] . $filename;
    }
}