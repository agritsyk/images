<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 12.02.2019
 * Time: 18:53
 */

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Post;
use frontend\models\User;
use frontend\models\Comment;
use frontend\modules\post\models\forms\CommentForm;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class CommentController extends Controller
{
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
        $post = Post::getPostById($postId);

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
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteComment($postId, $commentId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        /* @var $post Post */
        $post = Post::getPostById($postId);

        if (Comment::getCommentById($commentId)->delete() && $post->deleteComment()) {
            Yii::$app->session->setFlash('success', 'Comment successfully deleted!');
        } else {
            Yii::$app->session->setFlash('error', 'Comment is not deleted!');
        }

        return $this->goBack();
    }
}