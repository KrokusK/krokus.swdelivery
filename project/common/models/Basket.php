<?php

namespace common\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "basket".
 *
 * @property int $id
 * @property int $id_user
 * @property string $date
 * @property int $status
 *
 * @property User $user
 * @property BasketDish[] $basketDishes
 * @property Dish[] $dishes
 */
class Basket extends ActiveRecord
{
    const STATUS_ACCEPT = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'basket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'date'], 'required'],
            [['id_user', 'status'], 'default', 'value' => null],
            [['id_user', 'status'], 'integer'],
            [['date'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'date' => 'Date',
            'status' => 'Status',
        ];
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

    /**
     * Gets query for [[BasketDishes]].
     *
     * @return ActiveQuery
     */
    public function getBasketDishes()
    {
        return $this->hasMany(BasketDish::className(), ['id_basket' => 'id']);
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getDishes()
    {
        return $this->hasMany(Dish::className(), ['id' => 'id_dish'])->viaTable('basket_dish', ['id_basket' => 'id']);
    }
}
