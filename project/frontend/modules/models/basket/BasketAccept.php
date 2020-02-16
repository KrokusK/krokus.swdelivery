<?php


namespace frontend\modules\models\basket;


use common\models\Dish;
use yii\base\Model;

class BasketAccept extends Model
{
    public $id;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['id', 'integer', 'message' => 'Поле {attribute} целое число'],
            ['id', 'isId'],

        ];
    }

    public function accept()
    {
        return true;
    }

    public function isId($attribute, $params)
    {
        if(!Dish::find()->where(['id' => $this->id])->exists()){
            $this->addError($attribute, 'Id не существует');
        }
    }



}