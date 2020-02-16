<?php


namespace frontend\modules\models\basket;

use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class BasketDelete extends BasketQuantityChange
{
    /**
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function delete()
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

       return $this->deleteBasketDish($basket_dish);
    }

}