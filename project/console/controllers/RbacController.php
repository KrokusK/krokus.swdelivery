<?php

namespace console\controllers;

use Yii;
use yii\base\Exception;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit()
    {
        $authManager =  Yii::$app->getAuthManager();
        $authManager->removeAll();

        $admin = $authManager->createRole('admin');
        $admin->description = 'Администратор системы управления персоналом';
        $authManager->add($admin);

        $user = $authManager->createRole('user');
        $user->description = 'Сотрудник компании, активный пользователь системы';
        $authManager->add($user);

        $banned = $authManager->createRole('banned');
        $banned->description = 'Удаленный из активных пользователей сотрудник компании';
        $authManager->add($banned);

        $authManager->addChild($admin, $user);

        $this->stdout('success' . PHP_EOL);
    }

}