<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "basket_dish".
 *
 * @property int $id_basket
 * @property int $id_dish
 * @property int $price
 * @property int $amount
 *
 * @property Basket $basket
 * @property Dish $dish
 */
class BasketDish extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'basket_dish';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_basket', 'id_dish', 'price'], 'required'],
            [['id_basket', 'id_dish', 'price', 'amount'], 'default', 'value' => null],
            [['id_basket', 'id_dish', 'price', 'amount'], 'integer'],
            [['id_basket', 'id_dish'], 'unique', 'targetAttribute' => ['id_basket', 'id_dish']],
            [['id_basket'], 'exist', 'skipOnError' => true, 'targetClass' => Basket::className(), 'targetAttribute' => ['id_basket' => 'id']],
            [['id_dish'], 'exist', 'skipOnError' => true, 'targetClass' => Dish::className(), 'targetAttribute' => ['id_dish' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_basket' => 'Id Basket',
            'id_dish' => 'Id Dish',
            'price' => 'Price',
            'amount' => 'Amount',
        ];
    }

    /**
     * Gets query for [[Basket]].
     *
     * @return ActiveQuery
     */
    public function getBasket()
    {
        return $this->hasOne(Basket::className(), ['id' => 'id_basket']);
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
}
