<?php
/* @var $this yii\web\View */

/* @var $user , $currentUser frontend\models\User */

/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

?>

<h3><?php echo Html::encode($user->username); ?></h3>
<p><?php echo HtmlPurifier::process($user->about); ?></p>
<hr>


<img src="<?php echo $user->getPicture(); ?>" id="profile-picture" alt="">
<?php if ($currentUser): ?>
    <?php if ($currentUser->equals($user)): ?>
        <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
        <div class="alert alert-danger display-none" id="profile-image-fail"></div>

        <?= FileUpload::widget([
            'model' => $modelPicture,
            'attribute' => 'picture',
            'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
            'options' => ['accept' => 'image/*'],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
            if (data.result.success) {
                $("#profile-image-success").show();
                $("#profile-image-fail").hide();
                $("#profile-picture").attr("src", data.result.pictureUri);
            } else {
                $("#profile-image-fail").html(data.result.errors.picture).show();
                $("#profile-image-success").hide();
            }
        }',
            ],
        ]); ?>

    <?php endif; ?>


    <?php if (!$user->equals($currentUser)): ?>
        <?php if (!$currentUser->isSubscribed($user)): ?>
            <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">
                Subscribe
            </a>
        <?php else: ?>
            <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>"
               class="btn btn-info">
                Unsubscribe
            </a>
        <?php endif; ?>
        <hr>
    <?php endif; ?>

    <?php if ($mutualSubscriptions = $currentUser->getMutualSubscriptionsTo($user)): ?>
        <h5>Friends, who are also following <?php echo Html::encode($user->username); ?>:</h5>
        <div class="row">
            <?php foreach ($mutualSubscriptions as $item): ?>
                <div class="col-md-12">
                    <a href="<?php Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                        <?php echo Html::encode($item['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
    <?php endif; ?>
<?php endif; ?>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal1">
    Subscriptions: <?php echo Html::encode($user->countSubscriptions()); ?>
</button>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal2">
    Followers: <?php echo Html::encode($user->countFollowers()); ?>
</button>

<!-- Modal1 -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getSubscriptions() as $subscription): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname'] ? $subscription['nickname'] : $subscription['id'])]); ?>">
                                <?php echo Html::encode($subscription['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal2 -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $follower): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($follower['nickname'] ? $follower['nickname'] : $follower['id'])]); ?>">
                                <?php echo Html::encode($follower['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>