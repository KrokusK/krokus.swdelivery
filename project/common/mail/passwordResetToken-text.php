<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = 'https://pylearn.info/password-confirmation?token=' . $user->password_reset_token;
?>
Здравствуйте, <?= $user->firstname ?>,

<p>Вы запросили восстановление пароля от учетной записи на сайте SmartFood.</p>

<p>Для смены пароля, пожалуйста, перейдите по ссылке ниже:</p>
<?= $resetLink ?>
