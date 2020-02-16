<?php

namespace frontend\modules\models;

use common\models\BasketDish;
use frontend\modules\models\basket\DishBasket;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class Basket extends \common\models\Basket
{

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
        return $this->hasMany(DishBasket::className(), ['id' => 'id_dish'])->viaTable('{{%basket_dish}}', ['id_basket' => 'id']);
    }
}