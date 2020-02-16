<?php
namespace frontend\models;

use Yii;
use common\models\User;

class SignupForm extends AbstractSignUpUserForm
{

    public $password;
    public $password_2;

    public function rules()
    {
        $rules =  parent::rules();
        array_push($rules, [['password', 'password_2'], 'required', 'message' => 'Поле {attribute} обязательно для заполнения']);
        array_push($rules, ['password_2', 'comparisonPassword']);
        return $rules;
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

    /**
     * @return bool|User
     * @throws \Exception
     */
    public function signup()
    {
        $user =  parent::signup();

        $user->limit = intval(getenv('LIMIT'));

        if($user->save() && $this->sendEmail($user)){
            $this->assignRole($user);
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @return mixed|void
     */
    function setPassword($user)
    {
       $user->setPassword($this->password);
    }

    /**
     * @param $user
     * @return bool
     */
    function sendEmail($user)
    {
        return Yii::$app->mailer->compose(['text' => 'emailVerify-text'], ['user' => $user])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($user->email)
            ->setSubject('Подтверждение email')
            ->send();
    }



}