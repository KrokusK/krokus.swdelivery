<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['verify/verify', 'token' => $user->verification_token]);

use yii\helpers\Html; ?>

Здравствуйте,  <?= Html::encode($user->firstname) ?>

Вы успешно создали учетную запись в сети SmartFood.

Чтобы закончить регистрацию, вы должны подтвердить свою почту.

Перейдите по ссылке указанной ниже, для подтверждения почты
<?= $verifyLink ?>