<?php

namespace frontend\modules\controllers;

use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\modules\models\auth\PasswordResetForm;
use frontend\modules\models\auth\PasswordResetRequestForm;
use frontend\modules\models\auth\ResendVerificationEmailForm;
use frontend\modules\models\auth\VerifyEmailForm;
use yii\filters\AccessControl;
use \yii\rest\Controller;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['login', 'signup', 'verify', 'resend', 'reset-request', 'reset-password'],
            'rules' => [
                [
                    'actions' => ['login', 'signup', 'verify', 'resend', 'reset-request', 'reset-password'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
            ]
        ];
        return $behaviors;
    }

    /**
     * @return array|string
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->login()){
            $user = Yii::$app->user->identity;
            return [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'role' => key(Yii::$app->getAuthManager()->getRolesByUser($user->id)),
            ];
        }

        return 'fail';
    }

    /**
     * @return string
     * @throws UnauthorizedHttpException
     */
    public function actionLogout()
    {
        if(Yii::$app->user->isGuest)
            throw new UnauthorizedHttpException();

        $model = Yii::$app->user->logout();

        if($model){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';

    }

    /**
     * @return array|string
     * @throws \Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->signup()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     */
    public function actionVerify()
    {
        $model = new VerifyEmailForm();

        $model->load(Yii::$app->request->queryParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->verify()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     */
    public function actionResend()
    {
        $model = new ResendVerificationEmailForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->send()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     */
    public function actionResetRequest()
    {
        $model = new PasswordResetRequestForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->send()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     */
    public function actionResetPassword()
    {
        $model = new PasswordResetForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->reset()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
            'logout' => ['post'],
            'signup' => ['post'],
            'verify' => ['get'],
            'resend' => ['post'],
            'reset-request' => ['post'],
            'reset-password' => ['post']
        ];
    }




}