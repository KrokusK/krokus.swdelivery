<?php

namespace frontend\modules\models\basket;

use common\models\Basket;
use common\models\BasketDish;
use frontend\modules\models\request\DishRequest;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\httpclient\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class BasketAdd extends BasketQuantityChange
{
    /**
     * @return bool|BasketDish
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function add()
    {
        $id_user = $this->getUser()->id;
        $id_dish = $this->id;

        $this->isAccept();

        $basket = $this->getBasket();

        if(is_null($basket)) {
            $basket = $this->createNewBasket($id_user);
        }

        $basket_dish = $this->getBasketDish($basket->id, $id_dish);

        if(is_null($basket_dish)){
            return $this->createBasketDish($basket->id, $id_dish);
        }

        return $this->increaseAmount($basket_dish);
    }

    /**
     * @param $basket_dish ActiveRecord
     * @return mixed
     * @throws BadRequestHttpException
     */
    protected function increaseAmount($basket_dish)
    {
        $amount = $this->amount + $basket_dish->amount;

        $this->isAmountValid($amount);

        $basket_dish->amount = $amount;

        return $basket_dish->save();
    }

    /**
     * @param $amount
     * @throws BadRequestHttpException
     */
    protected function isAmountValid($amount)
    {
        if($amount > intval(getenv('MAX_DISHES_NUMBER'))) {
            throw new BadRequestHttpException('Количество блюд не может быть больше ' . getenv('MAX_DISHES_NUMBER'));
        }
    }

    /**
     * @param $id_user
     * @return Basket
     * @throws ServerErrorHttpException
     */
    private function createNewBasket($id_user)
    {
        $basket = new Basket();
        $basket->id_user = $id_user;
        $basket->date = $this->date;
        $basket->status = Basket::STATUS_INACTIVE;

        if(!$basket->save()) {
            throw new ServerErrorHttpException('Не удалось создать корзину');
        }

        return $basket;
    }

    /**
     * @param $id_basket
     * @param $id_dish
     * @return BasketDish
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    private function createBasketDish($id_basket, $id_dish)
    {
        $dish = $this->dishRequest($id_dish);

        if(is_null($dish))
            throw new BadRequestHttpException("Блюдо с таким id недоступно");

        $basket_dish = new BasketDish();

        $basket_dish->id_basket = $id_basket;
        $basket_dish->id_dish = $id_dish;
        $basket_dish->price = $dish[0]['price'];
        $basket_dish->amount = $this->amount;

        if(!$basket_dish->save()) {
            throw new ServerErrorHttpException('Не удалось создать корзину');
        }

        return $basket_dish;
    }

    /**
     * @param $id_dish
     * @return array|mixed
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function dishRequest($id_dish)
    {
        $request = new DishRequest($this->date, $id_dish);
        $_dish = $request->response();
        return $_dish;
    }

}