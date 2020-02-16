<?php

namespace frontend\modules\models\auth;

use common\models\User;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class PasswordResetForm extends Model
{
    public $token;
    public $password;
    public $password_2;

    public function rules()
    {
        return [
            ['token', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['password', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['password_2', 'required', 'message' => 'Поле {attribute} не может быть пустым'],

            ['password_2', 'comparisonPassword']
        ];
    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     */
    public function reset()
    {
        $user = User::findByPasswordResetToken($this->token);

        if(is_null($user))
            throw new BadRequestHttpException();

        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save();
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function comparisonPassword($attribute, $params)
    {
        if(strcmp($this->password, $this->password_2) !== 0){
            $this->addError($attribute, "Пароли не совпадают");
        }
    }

}
