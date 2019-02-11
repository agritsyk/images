<?php
/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $comments array of \frontend\models\Comment */

/* @var $currentUser \frontend\models\User */

/* @var $post \frontend\models\Post */

use yii\helpers\Html;

?>

<?php foreach ($comments as $comment): ?>
    <p><b><?php echo $comment->user->username; ?>: </b><?php echo $comment->text; ?>
        <?php if (isset($currentUser)): ?>
            <?php if ($currentUser->id == $comment->user_id): ?>
                <?= Html::a('Update', ['/post/default/update-comment', 'postId' => $comment->post->id, 'commentId' => $comment->id],
                    [
                        'class' => 'btn btn-success',
                    ]); ?>
            <?php endif; ?>
            <?php if ($currentUser->id == $post->user_id): ?>
                <?= Html::a('Delete', ['/post/default/delete-comment', 'postId' => $comment->post->id, 'commentId' => $comment->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]); ?>
            <?php endif; ?>
        <?php endif; ?>
    </p>
    <hr>
<?php endforeach; ?>
<?php if (isset($currentUser)): ?>
    <?= $this->render('_form', [
        'commentForm' => $commentForm,
    ]); ?>
<?php endif; ?>


