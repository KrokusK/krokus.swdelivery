<?php


namespace frontend\modules\models\elect;

use common\models\Basket;
use common\models\BasketDish;
use frontend\modules\models\AbstractMenuModel;
use frontend\modules\models\Provider;
use frontend\modules\models\request\DishRequest;
use frontend\modules\models\User;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\httpclient\Exception;


class ElectForm extends AbstractMenuModel
{
    public $date;
    public $refuse;
    public $accept;
    public $dishes;
    public $_meta;

    /**
     * @return ElectForm
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function elect()
    {
        $user = User::findUserById($this->getUser()->id);
        $data = $user->dishes;

        $this->_provider = $this->prepareDataProvider($data);

        $this->dishes = $this->getData();
        $this->accept = $this->isAccepted();
        $this->refuse = $this->isRefused();
        $this->_meta = $this->getMeta();

        return $this;
    }

    /**
     * @param $data
     * @return Provider
     * @throws InvalidConfigException
     * @throws Exception
     */
    function prepareDataProvider($data)
    {
        $id_basket = $this->getBasketId();

        $elect = [];
        foreach ($data as $dish){

            $_dish = $this->dishRequest($dish->id);

            array_push($elect, $dish);
            if(is_null($_dish))
                continue;

            $dish->price = $_dish[0]['price'];
            $dish->active = true;
            $dish->amount = !is_null($id_basket) ? $this->getAmount($id_basket, $dish['id']) : 0;
        }

        return new Provider($elect);
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
     * @return mixed|null
     */
    private function getBasketId()
    {
        $user = $this->getUser();

        $basket = Basket::find()
            ->where(['id_user' => $user->id, 'date' => $this->date])
            ->one();


        return !is_null($basket) ? $basket->id : null;
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

    public function getLinks()
    {
        $links = parent::getLinks();
        $links['menu'] = Url::to(['/modules/menu', 'date' => $this->date], true);
        $links['basket'] = Url::to(['/modules/basket', 'date' => $this->date], true);
        return $links;
    }
}