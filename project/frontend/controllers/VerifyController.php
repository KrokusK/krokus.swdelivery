<?php
namespace frontend\controllers;


use frontend\modules\models\auth\PasswordResetForm;
use frontend\modules\models\auth\VerifyEmailForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ErrorHandler;


/**
 * Site controller
 */
class VerifyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionError()
    {
        return $this->redirect(['verify/verify']);
    }

    /**
     * @return string
     */
    public function actionVerify()
    {
        $model = new VerifyEmailForm();

        $model->load(Yii::$app->request->queryParams, '');

        if(!$model->validate()){
            return $this->render('verify_error');
        }

        try {
            if($model->verify()) {
                return $this->render('verify');
            }
        } catch (BadRequestHttpException $e) {
            return $this->render('verify_error');
        }

        return $this->render('verify_error');

    }

}
