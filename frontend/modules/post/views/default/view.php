<?php
/* @var $this \yii\web\View */

/* @var $post \frontend\models\Post */

/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $currentUser \common\models\User */

use yii\helpers\Html;
use yii\web\JqueryAsset;


?>

<div class="post-default-index">
    <div class="row">
        <div class="col-md-12">
            <?php if ($post->user): ?>
                <?php echo $post->user->username; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <img src="<?php echo $post->getImage(); ?>" alt="">
        </div>
        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

        <hr>

        <div class="col-md-12">
            Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>
            <?php if (isset($currentUser)): ?>
                <a href="#"
                   class="btn btn-primary button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : "" ?>"
                   data-id="<?php echo $post->id; ?>">
                    Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                </a>
                <a href="#"
                   class="btn btn-primary button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none" ?>"
                   data-id="<?php echo $post->id; ?>">
                    Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                </a>
            <?php endif; ?>

        </div>
        <div class="col-md-12">

            <?php if ($count = $post->getCommentsCount()): ?>
                <h1><?php echo 'There is ' . $count . ' comment(s)'; ?></h1>
            <?php endif; ?>
            <?php if (isset($currentUser)): ?>
                <?= $this->render('comments', [
                    'comments' => $post->getComments(),
                    'commentForm' => $commentForm,
                    'currentUser' => $currentUser,
                    'post' => $post,
                ]); ?>
            <?php else: ?>
                <?= $this->render('comments', [
                    'comments' => $post->getComments(),
                    'post' => $post,
                ]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::class,
]) ?>
