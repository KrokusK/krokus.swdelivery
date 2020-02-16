<?php

namespace frontend\modules\models\basket;

use common\models\BasketDish;
use common\models\Elect;
use frontend\modules\models\AbstractMenuModel;

use frontend\modules\models\Basket;
use frontend\modules\models\Provider;
use frontend\modules\models\request\DishRequest;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\httpclient\Exception;
use yii\web\ServerErrorHttpException;

class BasketForm extends AbstractMenuModel
{
    public $date;
    public $refuse;
    public $accept;
    public $total = 0;
    public $balance;
    public $dishes;
    public $_meta;

    /**
     * @return $this
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function basket()
    {
        $data = $this->getBasket();

        $this->_provider = $this->prepareDataProvider($data);

        $this->balance = $this->countBalance();
        $this->accept = $this->isAccepted();
        $this->refuse = $this->isRefused();
        $this->dishes = $this->getData();
        $this->_meta = $this->getMeta();

        return $this;
    }

    /**
     * @param $data
     * @return Provider
     * @throws Exception
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    function prepareDataProvider($data)
    {
        if(is_null($data)) {
            $this->_basket = $this->createNewBasket();
            return new Provider($this->_basket->dishes);
        }

        $id_basket = $data->id;

        $dishes = $data->dishes;
        foreach ($dishes as $dish) {

            $_dish = $this->dishRequest($dish->id);

            if(is_null($_dish))
                continue;

            $dish->price = $_dish[0]['price'];
            $dish->active = true;
            $dish->amount = $this->getAmount($id_basket, $dish->id);
            $dish->elect = $this->isElect($dish->id);

            $dish->total = $dish->price * $dish->amount;

            $this->countTotal($dish->total);
        }

        return new Provider($dishes);

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

    /**
     * @param $id_basket
     * @param $id_dish
     * @return int|mixed
     */
    private function getAmount($id_basket, $id_dish)
    {
        $basket_dish = BasketDish::find()
            ->where(['id_basket' => $id_basket, 'id_dish' => $id_dish])
            ->one();

        return !is_null($basket_dish) ? $basket_dish->amount : 0;
    }

    /**
     * @param $id_dish
     * @return bool
     */
    private function isElect($id_dish)
    {
        return Elect::find()->where(['id_dish' => $id_dish])->exists();
    }

    /**
     * @return Basket
     * @throws ServerErrorHttpException
     */
    private function createNewBasket()
    {
        $basket = new Basket();

        $basket->id_user = $this->getUser()->id;
        $basket->date = $this->date;
        $basket->status = Basket::STATUS_INACTIVE;

        if(!$basket->save())
            throw new ServerErrorHttpException("Не удалось создать корзину");

        return $basket;
    }

    /**
     * @return mixed
     */
    private function countBalance()
    {
        return $this->getUser()->limit - $this->total;
    }

    /**
     * @param $price
     */
    private function countTotal($price)
    {
        $this->total += $price;
    }

    public function getLinks()
    {
        $links = parent::getLinks();
        $links['menu'] = Url::to(['/modules/menu', 'date' => $this->date], true);
        $links['elect'] = Url::to(['/modules/elect', 'date' => $this->date], true);
        $links['accept'] = Url::to(['/modules/basket/accept', 'date' => $this->date], true);
        return $links;
    }
}