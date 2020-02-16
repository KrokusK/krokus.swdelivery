<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $password string */


use yii\helpers\Html; ?>

Здравствуйте,  <?= Html::encode($user->firstname) ?>

Ваша учетная запись в сети SmartFood успешно была создана.

Пароль вашей учетной записи <?= $password ?>

