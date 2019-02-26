<?php
/* @var $commentForm \frontend\modules\post\models\forms\CommentForm */

/* @var $commentText string */

use yii\helpers\Html;

?>
<h4><?php echo Html::encode('Update your comment'); ?></h4>
<?= $this->render('_form', [
    'commentForm' => $commentForm,
    'commentText' => $commentText,
]); ?>
