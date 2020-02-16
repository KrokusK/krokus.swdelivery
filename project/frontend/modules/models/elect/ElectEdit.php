<?php

namespace frontend\modules\models\elect;

use common\models\Dish;
use common\models\Elect;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\IdentityInterface;

class ElectEdit extends Model
{
    public $id;

    private $_user;

    public function rules()
    {
        return [
            ['id', 'required', 'message' =>'Поле {attribute} не может быть пустым'],
            ['id', 'integer', 'message' => 'Поле {attribute} целое число'],
            ['id', 'isId'],
        ];
    }

    /**
     * @return bool|mixed
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function edit()
    {
        $id_user = $this->getUser()->id;
        $id_dish = $this->id;

        $model = Elect::find()
            ->where(['id_user' => $id_user, 'id_dish' => $id_dish])
            ->one();

        if(!is_null($model)){
           return $this->deleteElect($model);
        }

        return $this->createNewElect($id_user, $id_dish);

    }

    /**
     * @param $model ActiveRecord
     * @return mixed
     * @throws \Throwable
     * @throws StaleObjectException
     */
    private function deleteElect($model)
    {
        return $model->delete();
    }

    /**
     * @param $id_user
     * @param $id_dish
     * @return bool
     */
    private function createNewElect($id_user, $id_dish)
    {
        $model = new Elect();
        $model->id_user = $id_user;
        $model->id_dish = $id_dish;

        return $model->save();
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function isId($attribute, $params)
    {
        if(!Dish::find()->where(['id' => $this->id])->exists()){
            $this->addError($attribute, 'Id не существует');
        }
    }

    /**
     * @return IdentityInterface|null
     */
    private function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }

}