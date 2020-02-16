<?php

namespace frontend\modules\models\auth;

use common\models\User;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class VerifyEmailForm extends Model
{
    public $token;

    public function rules()
    {
        return [
            ['token', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
        ];
    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     */
    public function verify()
    {
        $user = User::findByVerificationToken($this->token);

        if(is_null($user))
            throw new BadRequestHttpException();

        $user->status = User::STATUS_ACTIVE;

        return $user->save();
    }



}