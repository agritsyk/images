<?php
/* @var $this \yii\web\View */

/* @var $post \frontend\models\Post */

/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $currentUser \common\models\User */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;

?>

<div class="container full">

    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">


                <div class="blog-posts blog-posts-large">

                    <div class="row">

                        <!-- feed item -->
                        <article class="post col-sm-12 col-xs-12">
                            <div class="post-meta">
                                <div class="post-title">
                                    <img src="<?php echo $post->user->getPicture(); ?>" class="author-image" />
                                    <div class="author-name">
                                        <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($post->user->nickname) ? $post->user->nickname : $post->user->id]); ?>">
                                            <?php echo $post->user->username; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="post-type-image">
                                <a href="#">
                                    <img src="<?php echo $post->getImage(); ?>" alt="">
                                </a>
                            </div>
                            <div class="post-description">
                                <p><?php echo Html::encode($post->description); ?></p>
                            </div>
                            <div class="post-bottom">
                                <div class="post-likes">
                                    <i class="fa fa-lg fa-heart-o"></i>
                                    <span class="likes-count"><?php echo $post->countLikes(); ?></span>&nbsp;
                                    <?php if (isset($currentUser)): ?>
                                        <a href="#"
                                           class="btn btn-default button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : "" ?>"
                                           data-id="<?php echo $post->id; ?>">
                                            Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                        </a>
                                        <a href="#"
                                           class="btn btn-default button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none" ?>"
                                           data-id="<?php echo $post->id; ?>">
                                            Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="post-comments">
                                    <a href="#"><?php echo (($commentsCount = $post->countComments()) > 0) ? $commentsCount : 0;?> comment(s)</a>

                                </div>
                                <div class="post-date">
                                    <span><?php echo Yii::$app->formatter->asDatetime($post->created_at); ?></span>
                                </div>
                                <div class="post-report">
                                    <a href="#">Report post</a>
                                </div>
                            </div>
                        </article>
                        <!-- feed item -->
                        <div class="col-sm-12 col-xs-12">
                            <?php if ($commentsCount):?>
                            <h4><?php echo $commentsCount; ?> comment(s)</h4>
                            <?php endif; ?>
                            <div class="comments-post">
                                <div class="single-item-title"></div>
                                <div class="row">
                                    <ul class="comment-list">
                                        <!-- comment item -->
                                        <?php if (isset($currentUser)): ?>
                                            <?= $this->render('/comment/comments', [
                                                'comments' => $post->getComments(),
                                                'commentForm' => $commentForm,
                                                'currentUser' => $currentUser,
                                                'post' => $post,
                                            ]); ?>
                                        <?php else: ?>
                                            <?= $this->render('/comment/comments', [
                                                'comments' => $post->getComments(),
                                                'post' => $post,
                                            ]); ?>
                                        <?php endif; ?>
                                        <!-- comment item -->
                                    </ul>
                                </div>

                            </div>
                            <!-- comments-post -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::class,
]) ?>
