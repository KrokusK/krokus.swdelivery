<?php


namespace frontend\modules\controllers;


use frontend\modules\models\menu\MenuForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\httpclient\Exception;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class MenuController extends Controller
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
     * @return array|MenuForm
     * @throws ServerErrorHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new MenuForm();
        $model->load(Yii::$app->request->queryParams, '');

        if(!$model->validate())
            return $model->getErrors();

        return $model->menu();
    }

    public function verbs()
    {
        return [
          'index' => ['get'],
        ];
    }
}