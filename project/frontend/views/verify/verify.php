<?php

use yii\helpers\Html;
?>

<div class="signup_container container_center">
    <div class="logo">
        <?= Html::img('@web/img/logo.png', ['alt' => 'Logo']) ?>
    </div>
    <div class="signup container">
      <div class="title signup-title">Регистрация</div>
      <div class="signup-info">
        <div class="signup-info__title">Регистрация успешно завершена</div>
        <a href="https://pylearn.info/signin" class="signup-info__repeat">Войти</a>
      </div>
    </div>
</div>
