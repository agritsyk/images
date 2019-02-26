<?php
/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $commentText string (if isset) */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(); ?>

<?php if (isset($commentText)): ?>
    <p class="comment-form-comment">
        <?php echo $form->field($commentForm, 'text')
            ->textarea(array(
                'value' => $commentText,
                'class' => 'form-control',
                'rows' => 6
            ))
            ->label(false);
        ?>
    </p>
    <p class="form-submit">
        <?php echo Html::submitButton('Send', [
            'class' => 'btn btn-secondary',
        ]); ?>
    </p>
<?php else: ?>
    <p class="comment-form-comment">
        <?php echo $form->field($commentForm, 'text')
            ->textarea(array(
                'placeholder' => 'Enter your comment',
                'class' => 'form-control',
                'rows' => 6
            ))
            ->label(false);
        ?>
    </p>
    <p class="form-submit">
        <?php echo Html::submitButton('Send', [
            'class' => 'btn btn-secondary',
        ]); ?>
    </p>

<?php endif; ?>
<?php ActiveForm::end(); ?>