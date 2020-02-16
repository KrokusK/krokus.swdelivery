<?php

namespace frontend\modules\models\basket;


use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\ServerErrorHttpException;

class BasketAmount extends BasketAdd
{
    /**
     * @param ActiveRecord $basket_dish
     * @return bool|mixed
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    protected function increaseAmount($basket_dish)
    {
        if($this->amount == 0)
            return $this->deleteBasketDish($basket_dish);

        $amount = $this->amount;

        $this->isAmountValid($amount);

        $basket_dish->amount = $amount;

        return $basket_dish->save();
    }
}