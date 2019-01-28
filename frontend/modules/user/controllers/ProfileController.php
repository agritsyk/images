<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 28.01.2019
 * Time: 20:09
 */

namespace frontend\modules\user\controllers;

use frontend\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'user' => $this->findUser($id),
        ]);
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findUser($id)
    {
        if ($user = User::find()->where(['id' => $id])->one()) {
            return $user;
        }

        throw new NotFoundHttpException('User not found!');
    }
}