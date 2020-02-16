<?php


namespace frontend\modules\models\admin;

use common\models\User;
use yii\base\Model;

abstract class AbstractUserModel extends Model
{
    public $id;

    private $_user;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['id', 'integer', 'message' => 'Невалидный {attribute}'],
            'isValidId' => ['id', 'exist',
                'targetClass' => '\common\models\User',
                'message' => 'Пользователь не существует'
            ],
        ];
    }

    protected function getUser()
    {
        if($this->_user === null){
            $this->_user = User::find()->where(['id' => $this->id])->one();
        }

        return $this->_user;
    }

    abstract function update();

}