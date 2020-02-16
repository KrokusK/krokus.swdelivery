<?php

namespace frontend\modules\models\auth;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class ResendVerificationEmailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['email', 'email', 'message' => 'Невалидный {attribute}'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => '{attribute} не существует'
            ],
        ];
    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     */
    public function send()
    {
        $user = User::findOne(['email' => $this->email, 'status' => User::STATUS_INACTIVE]);

        if(is_null($user))
            throw new BadRequestHttpException();

        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * @param $user
     * @return bool
     */
    private function sendEmail($user)
    {
        return Yii::$app->mailer->compose(['text' => 'emailVerify-text'], ['user' => $user])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($user->email)
            ->setSubject('Подтверждение email')
            ->send();
    }

}
