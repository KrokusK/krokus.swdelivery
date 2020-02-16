<?php


namespace frontend\modules\controllers;

use frontend\modules\models\admin\CreateUserForm;
use frontend\modules\models\admin\DeleteUserForm;
use frontend\modules\models\admin\LimitUserForm;
use frontend\modules\models\admin\OrderCancelUserForm;
use frontend\modules\models\admin\ResetRoleForm;
use frontend\modules\models\admin\UsersForm;
use frontend\modules\models\admin\VerifyUserForm;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class AdminController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ]
        ];
        return $behaviors;
    }

    /**
     * @return UsersForm
     */
    public function actionIndex()
    {
        $model = new UsersForm();

        return $model->users();
    }

    /**
     * @return array|string
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete()
    {
        $model = new DeleteUserForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->update()){
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
    public function actionResetRole()
    {
        $model = new ResetRoleForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->update()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws ServerErrorHttpException
     */
    public function actionLimit()
    {
        $model = new LimitUserForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->update()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     */
    public function actionVerify()
    {
        $model = new VerifyUserForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->update()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     */
    public function actionOrderCancel()
    {
        $model = new OrderCancelUserForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->update()){
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
    public function actionCreate()
    {
        $model = new CreateUserForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if(($user = $model->signup())){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return ['id' => $user->id];
        }

        return 'fail';
    }


    public function actionReport()
    {

    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'delete' => ['post'],
            'reset-role' => ['post'],
            'limit' => ['post'],
            'verify' => ['post'],
            'order-cancel' => ['post'],
            'create' => ['post'],
            'report' => ['post'],
        ];
    }


}