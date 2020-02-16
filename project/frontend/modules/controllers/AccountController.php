<?php

namespace frontend\modules\controllers;

use frontend\models\UpdateForm;
use frontend\modules\models\elect\ElectEdit;
use frontend\modules\models\elect\ElectForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\httpclient\Exception;
use yii\rest\Controller;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

class AccountController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'denyCallback' => function($rule, $action){
                throw new UnauthorizedHttpException();
            },
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ]
        ];
        return $behaviors;
    }

    /**
     * @return IdentityInterface|null
     */
    public function actionIndex()
    {
        return $this->findModel();
    }

    /**
     * @return array|string|IdentityInterface|null
     */
    public function actionUpdate()
    {
        $model = new UpdateForm();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->update()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return $this->findModel();
        }

        return 'fail';
    }

    /**
     * @return array|ElectForm
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionElect()
    {

        $model = new ElectForm();
        $model->load(Yii::$app->request->queryParams, '');

        if(!$model->validate())
            return $model->getErrors();

        return $model->elect();

    }

    /**
     * @return ElectEdit|string
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionEdit()
    {
        $model = new ElectEdit();
        $model->load(Yii::$app->request->bodyParams, '');

        if (!$model->validate())
            return $model;

        if($model->edit()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return $model;
    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'update' => ['post'],
            'elect' => ['get'],
            'edit' => ['post']
        ];
    }

    private function findModel()
    {
        return Yii::$app->user->identity;
    }


}