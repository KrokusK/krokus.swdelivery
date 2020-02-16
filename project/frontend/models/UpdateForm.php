<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

class UpdateForm extends Model
{
    public $firstname;
    public $lastname;
    public $midname;
    public $current_password;
    public $password;
    public $password_2;
    public $order;
    public $start;
    public $end;

    /**
     * @var User
     */
    private $_user;

    public function rules()
    {
        return [
            [['firstname', 'lastname', 'midname', 'start', 'end'], 'trim'],

            ['firstname', 'required', 'message' => 'Поле Имя не может быть пустым'],
            ['lastname', 'required', 'message' => 'Поле Фамилия не может быть пустым'],

            ['order', 'required'],
            ['order', 'boolean'],

            ['midname', 'default', 'value' => null],
            ['password', 'default', 'value' => 0],
            ['password_2', 'default', 'value' => 0],

            ['current_password', 'required', 'when' => function(){
                return !($this->password === 0 && $this->password_2 === 0);
            }, 'message' => 'Поле Пароль не может быть пустым'],

            ['password', 'comparisonPassword', 'when' => function(){
                return !($this->password === 0 && $this->password_2 === 0);
            }],

            ['current_password', 'validatePassword', 'when' => function(){
                return !($this->password === null);
            }],

            [['start', 'end'], 'required', 'message' => 'Поля Дата не могут быть пустыми' ,'when' => function(){
                return !$this->order;
            }],
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = $this->getUser();

        if($this->password !== 0)
            $user->setPassword($this->password);

        $user->status_order = $this->order;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->midname = $this->midname;

        if(!$this->order){
            $user->end_order_cancel = $this->end;
            $user->start_order_cancel = $this->start;
        }else {
            $user->end_order_cancel = null;
            $user->start_order_cancel = null;
        }

        return $user->save();

    }

    public function comparisonPassword($attribute, $params)
    {

        if($this->password === 0 || $this->password_2 === 0){
            $this->addError($attribute, "Пароли не совпадают");
        }

        if(strcmp($this->password, $this->password_2) !== 0){
            $this->addError($attribute, "Пароли не совпадают");
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->current_password)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }

    /**
     * @return User|IdentityInterface|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }
}