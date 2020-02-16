<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['modules/auth/verify', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Здравствуйте,  <?= Html::encode($user->firstname) ?>,</p>

    <p>Вы успешно создали учетную запись в сети SmartFood.
    Чтобы закончить регистрацию, вы должны подтвердить свою почту. </p>

    <br>

    <p>Перейдите по ссылке указанной ниже, для подтверждения почты</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
