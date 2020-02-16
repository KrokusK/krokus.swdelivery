<?php


namespace frontend\modules\models\admin;


use common\models\User;
use frontend\models\AbstractSignUpUserForm;
use Yii;

class CreateUserForm extends AbstractSignUpUserForm
{

    public $password;
    public $limit;

    public function rules()
    {
        $rules = parent::rules();
        array_push($rules, ['limit', 'required', 'message' => 'Поле {attribute} обязательно для заполнения']);
        array_push($rules, ['limit', 'integer', 'min' => 0, 'tooSmall' => '{attribute} положительное значение',
            'message' => 'Поле {attribute} целочисленное значнение']);
        return $rules;
    }

    /**
     * @return User|null
     * @throws \Exception
     */
    public function signup()
    {
        $user = parent::signup();

        $user->status = User::STATUS_ACTIVE;
        $user->limit = $this->limit;

        if($user->save() && $this->sendEmail($user)){
            $this->assignRole($user);
            return $user;
        }

        return null;
    }

    /**
     * @param $user User
     * @throws \Exception
     */
    function setPassword($user)
    {
        $bytes = random_bytes(4);
        $this->password = bin2hex($bytes);
        $user->setPassword($this->password);
    }

    /**
     * @param $user
     * @return bool
     */
    function sendEmail($user)
    {
        return Yii::$app->mailer->compose(['text' => 'emailVerifyCreatedUser-text'], ['user' => $user, 'password' => $this->password])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($user->email)
            ->setSubject('Пароль от аккаунта SmartWorld')
            ->send();
    }
}