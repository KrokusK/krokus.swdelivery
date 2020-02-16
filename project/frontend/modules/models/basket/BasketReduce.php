<?php

namespace frontend\modules\models\basket;

use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class BasketReduce extends BasketQuantityChange
{
    /**
     * @return mixed
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function reduce()
    {
        $id_dish = $this->id;
        $this->isAccept();

        $basket = $this->getBasket();

        if(is_null($basket))
            throw new BadRequestHttpException('Корзины не существует');

        $basket_dish = $this->getBasketDish($basket->id, $id_dish);

        if(is_null($basket_dish)){
            throw new BadRequestHttpException('Блюдо не находится в корзине');
        }

        return $this->decreaseAmount($basket_dish);

    }

    /**
     * @param $basket_dish ActiveRecord
     * @return mixed
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    private function decreaseAmount($basket_dish)
    {
        $amount = $basket_dish->amount - $this->amount;

        if($amount <= 0)
            return $this->deleteBasketDish($basket_dish);

        $basket_dish->amount = $amount;

        return $basket_dish->save();
    }



}
