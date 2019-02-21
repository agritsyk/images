<?php

namespace frontend\modules\post\controllers;

use frontend\models\User;
use frontend\modules\post\models\forms\CommentForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\modules\post\models\forms\PostForm;
use frontend\models\Post;
use yii\web\Response;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post())) {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Post created');
                return $this->goBack();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        /* @var $post Post */

        $post = Post::getPostById($id);

        /* @var $currentUser User */
        if (!$currentUser = Yii::$app->user->identity) {
            return $this->render('view', [
                'post' => $post,
            ]);
        }

        /* @var $commentForm CommentForm */
        $commentForm = new CommentForm($currentUser, $post);

        if ($commentForm->load(Yii::$app->request->post()) && $commentForm->save()) {
            Yii::$app->session->setFlash('success', 'Comment added');
            return $this->refresh();
        }

        return $this->render('view', [
            'post' => $post,
            'currentUser' => $currentUser,
            'commentForm' => $commentForm,
        ]);
    }

    /**
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $postId = Yii::$app->request->post('id');
        $post = Post::getPostById($postId);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->unLike($currentUser);


        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionLike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $postId = Yii::$app->request->post('id');
        $post = Post::getPostById($postId);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);


        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }



}
