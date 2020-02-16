<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "elect".
 *
 * @property int $id_user
 * @property int $id_dish
 *
 * @property Dish $dish
 * @property User $user
 */
class Elect extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'elect';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_dish'], 'required'],
            [['id_user', 'id_dish'], 'default', 'value' => null],
            [['id_user', 'id_dish'], 'integer'],
            [['id_user', 'id_dish'], 'unique', 'targetAttribute' => ['id_user', 'id_dish']],
            [['id_dish'], 'exist', 'skipOnError' => true, 'targetClass' => Dish::className(), 'targetAttribute' => ['id_dish' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'id_dish' => 'Id Dish',
        ];
    }

    /**
     * Gets query for [[Dish]].
     *
     * @return ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(Dish::className(), ['id' => 'id_dish']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
