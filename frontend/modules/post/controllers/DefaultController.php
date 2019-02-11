<?php

namespace frontend\modules\post\controllers;

use frontend\models\Comment;
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
                return $this->goHome();
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

        $post = $this->findPost($id);

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

    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $post_id = Yii::$app->request->post('id');
        $post = $this->findPost($post_id);

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

        $post_id = Yii::$app->request->post('id');
        $post = $this->findPost($post_id);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);


        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * @param $id
     * @return Post|null
     * @throws NotFoundHttpException
     */
    private function findPost($id)
    {
        if ($post = Post::findOne($id)) {
            return $post;
        }

        throw new NotFoundHttpException();
    }

    /**
     * @param $postId
     * @param $commentId
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdateComment($postId, $commentId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        /* @var $post Post */
        $post = $this->findPost($postId);

        /* @var $commentForm \frontend\modules\post\models\forms\CommentForm */
        $commentForm = new CommentForm($currentUser, $post);

        if ($commentForm->load(Yii::$app->request->post()) && $commentForm->update($postId, $commentId)) {
            Yii::$app->session->setFlash('success', 'Comment updated');
            return $this->goBack();
        }

        $commentText = Comment::getCommentText($commentId);

        return $this->render('update-comment', [
            'commentForm' => $commentForm,
            'commentText' => $commentText,
        ]);
    }

    /**
     * @param $commentId
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteComment($commentId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        if ($this->findComment($commentId)->delete()) {
            Yii::$app->session->setFlash('success', 'Comment successfully deleted!');
        } else {
            Yii::$app->session->setFlash('error', 'Comment is not deleted!');
        }

        return $this->goBack();
    }

    /**
     * @param $id
     * @return Comment|null
     * @throws NotFoundHttpException
     */
    private function findComment($id)
    {
        if (($comment = Comment::findOne(($id))) !== null) {
            return $comment;
        }

        throw new NotFoundHttpException('The requested comment does not exist!');
    }


}
