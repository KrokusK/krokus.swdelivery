<?php


namespace frontend\models;


use common\models\User;
use Yii;
use yii\base\Model;

abstract class AbstractSignUpUserForm extends Model
{
    public $firstname;
    public $lastname;
    public $midname;
    public $email;

    public function rules()
    {
        return [
            [['email','firstname', 'lastname'], 'required', 'message' => 'Поле {attribute} обязательно для заполнения'],
            [['email', 'lastname', 'firstname', 'midname' ] , 'trim'],
            ['email', 'email', 'message' => 'Неверный формат {attribute}'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '{attribute} уже занят'],
        ];
    }

    /**
     * @return User
     * @throws \Exception
     */
    protected function signup()
    {
        $user = new User();

        $user->email = $this->email;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->midname = $this->midname;
        $user->status_order = 1;

        $this->setPassword($user);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user;
    }

    /**
     * @param $user
     * @throws \Exception
     */
    protected function assignRole($user)
    {
        $authManager = Yii::$app->getAuthManager();
        $authManager->assign($authManager->getRole('user'), $user->id);
    }

     abstract function setPassword($user);

     abstract function sendEmail($user);

}