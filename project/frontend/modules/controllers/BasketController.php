<?php

namespace frontend\modules\controllers;

use frontend\modules\models\basket\BasketAccept;
use frontend\modules\models\basket\BasketAdd;
use frontend\modules\models\basket\BasketAmount;
use frontend\modules\models\basket\BasketDelete;
use frontend\modules\models\basket\BasketForm;
use frontend\modules\models\basket\BasketReduce;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\httpclient\Exception;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class BasketController extends Controller
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
     * @return array|BasketForm
     * @throws InvalidConfigException
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    public function actionIndex()
    {
        $model = new BasketForm();

        $model->load(Yii::$app->request->queryParams, '');

        if(!$model->validate())
            return $model->getErrors();

        return $model->basket();
    }

    /**
     * @return array|string
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
     */
    public function actionAdd()
    {
        $model = new BasketAdd();
        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->add()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionReduce()
    {
        $model = new BasketReduce();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->reduce()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function actionAmount()
    {
        $model = new BasketAmount();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->add()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    /**
     * @return array|string
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete()
    {
        $model = new BasketDelete();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model->getErrors();

        if($model->delete()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }
# ОТПРАВКА ЗАКАЗА
    public function actionAccept()
    {
        $model = new BasketAccept();

        $model->load(Yii::$app->request->bodyParams, '');

        if(!$model->validate())
            return $model;

        if($model->accept()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(200);
            return 'success';
        }

        return 'fail';
    }

    public function verbs()
    {
        return [
            'index' => ['get'],
            'add' => ['post'],
            'reduce' => ['post'],
            'amount' => ['post'],
            'delete' => ['post'],
            'accept' => ['post'],
        ];
    }

}