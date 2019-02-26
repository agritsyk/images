<?php
/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $comments array of \frontend\models\Comment */

/* @var $currentUser \frontend\models\User */

/* @var $post \frontend\models\Post */

use yii\helpers\Html;

?>

<?php foreach ($comments as $comment): ?>
    <li class="comment">
        <div class="comment-user-image">
            <img src="#">
        </div>
        <div class="comment-info">
            <h4 class="author"><a href="#"><?php echo $comment->user->username; ?></a> <span><?php echo Yii::$app->formatter->asDatetime($comment->created_at); ?></span></h4>
            <p><?php echo $comment->text ?></p>
            <?php if (isset($currentUser)): ?>
                <?php if ($currentUser->id == $comment->user_id): ?>
                    <?= Html::a('Update', ['/post/comment/update-comment', 'postId' => $comment->post->id, 'commentId' => $comment->id],
                        [
                            'class' => 'btn btn-default',
                        ]); ?>
                <?php endif; ?>
                <?php if ($currentUser->id == $post->user_id): ?>
                    <?= Html::a('Delete', ['/post/comment/delete-comment', 'postId' => $comment->post->id, 'commentId' => $comment->id], [
                        'class' => 'btn btn-default',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </li>
<?php endforeach; ?>

<?php if (isset($currentUser)): ?>
    <div class="col-sm-12 col-xs-12">
        <div class="comment-respond">
            <h4>Leave a Reply</h4>
            <?= $this->render('_form', [
                'commentForm' => $commentForm,
            ]); ?>
        </div>
    </div>
<?php endif; ?>


