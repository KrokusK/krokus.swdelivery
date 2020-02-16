<?php

namespace frontend\modules\models\menu;

use common\models\BasketDish;
use common\models\Dish;
use common\models\Elect;
use frontend\modules\models\AbstractMenuModel;
use frontend\modules\models\Provider;
use frontend\modules\models\request\MenuRequest;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\httpclient\Exception;
use yii\web\ServerErrorHttpException;

class MenuForm extends AbstractMenuModel
{

    public $date;
    public $refuse;
    public $accept;
    public $categories;
    public $dishes;
    public $_meta;

    /**
     * @var CategoryForm;
     */
    private $_category;

    /**
     * MenuForm constructor.
     * @param array $config
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function __construct($config = [])
    {
        $this->categories = $this->getCategoryForm()->getCategories();
        parent::__construct($config);
    }

    /**
     * @return MenuForm
     * @throws ServerErrorHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function menu()
    {
        $data = $this->menuRequest($this->date);

        $this->_provider = $this->prepareDataProvider($data);

        $this->dishes = $this->getData();
        $this->refuse = $this->isRefused();
        $this->accept = $this->isAccepted();
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
        $id_basket = $this->getBasketId();

        $menu = [];
        foreach ($data as $dish) {

            $this->createNewDish($dish);

            $dish['category'] = $this->getCategoryForm()->getCategory($dish['group_id']);
            $dish['elect'] = $this->isElect($dish['id']);
            $dish['amount'] = !is_null($id_basket) ? $this->getAmount($id_basket, $dish['id']) : 0;
            $dish['_links'] = $this->getDishLinks();

            unset($dish['group_id']);

            array_push($menu, $dish);
        }

        return new Provider($menu);
    }

    /**
     * @param $dish
     * @throws ServerErrorHttpException
     */
    private function createNewDish($dish)
    {
        if(!Dish::find()->where(['id' => $dish['id']])->exists()){

            $_dish = new Dish();
            $_dish->id = $dish['id'];
            $_dish->name = $dish['name'];
            $_dish->weight = $dish['weight'];
            $_dish->weighty = $dish['weighty'];
            $_dish->single = $dish['single'];
            $_dish->description = $dish['description'];
            $_dish->image = $dish['image'];
            $_dish->isGarnish = $dish['isGarnish'];
            $_dish->maybeGarnish = $dish['maybeGarnish'];

            if(!$_dish->save())
                throw new ServerErrorHttpException("Блюдо не удалось добавить");
        }

    }

    /**
     * @param $date
     * @return array|mixed
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function menuRequest($date)
    {
        $menu_request = new MenuRequest($date);
        $data = $menu_request->response();

        return $data;
    }

    /**
     * @param $id_dish
     * @return bool
     */
    private function isElect($id_dish)
    {
        return Elect::find()->where(['id_user' => $this->getUser()->id,'id_dish' => $id_dish])->exists();
    }

    /**
     * @return mixed|null
     */
    private function getBasketId()
    {
        $basket = $this->getBasket();

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

    /**
     * @return array|CategoryForm
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function getCategoryForm()
    {
        if($this->_category === null){
            $category_form = new CategoryForm();
            $this->_category= $category_form;
        }

        return $this->_category;
    }

    public function getLinks()
    {
        $links = parent::getLinks();
        $links['elect'] = Url::to(['/modules/elect', 'date' => $this->date], true);
        $links['basket'] = Url::to(['/modules/basket', 'date' => $this->date], true);
        return $links;
    }

    public function getDishLinks()
    {
        $links = [];
        $links['edit'] = ['href' => Url::to(['/modules/account/edit'], true)];
        $links['add'] = ['href' => Url::to(['/modules/basket/add'], true)];
        $links['reduce'] = ['href' =>  Url::to(['/modules/basket/reduce'], true)];
        $links['delete'] = ['href' => Url::to(['/modules/basket/delete'], true)];
        return $links;
    }

}