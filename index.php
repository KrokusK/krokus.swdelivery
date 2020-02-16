<?php

require __DIR__ . '/project/vendor/autoload.php';
require __DIR__ . '/project/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/project/common/config/bootstrap.php';
require __DIR__ . '/project/frontend/config/bootstrap.php';
require __DIR__ . '/project/env.php';

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV'));

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/project/common/config/main.php',
    require __DIR__ . '/project/common/config/main-local.php',
    require __DIR__ . '/project/frontend/config/main.php',
    require __DIR__ . '/project/frontend/config/main-local.php'
);

(new yii\web\Application($config))->run();
