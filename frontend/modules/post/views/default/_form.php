<?php
/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $commentText string (if isset) */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(); ?>
<?php if (isset($commentText)): ?>
    <?php echo $form->field($commentForm, 'text')->textInput(array('placeholder' => $commentText)); ?>
    <?php echo Html::submitButton('Update comment', [
        'class' => 'btn btn-info',
    ]); ?>
<?php else: ?>
    <?php echo $form->field($commentForm, 'text')->textInput(array('placeholder' => 'Enter your comment')); ?>
    <?php echo Html::submitButton('Comment', [
        'class' => 'btn btn-info',
    ]); ?>
<?php endif; ?>
<?php ActiveForm::end(); ?>