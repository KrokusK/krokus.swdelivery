<?php


namespace frontend\modules\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\Linkable;

abstract class AbstractMenuModel extends Model implements Linkable
{
    public $date;
    public $refuse;
    public $accept;

    /**
     * @var Provider
     */
    protected $_provider;
    protected $_user;
    protected $_basket;

    public function rules()
    {
        return [
            ['date', 'default', 'value' => function(){
                return date(getenv('DATE_FORMAT_REQUEST'));
            }],
            ['date', 'match', 'pattern' => '/^\d{4}.\d{2}.\d{2}$/', 'message' => 'Неверный формат даты'],
        ];
    }

    /**
     * @return IdentityInterface|null
     */
    protected function getUser()
    {
        if($this->_user === null){
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }

    /**
     * @return array|ActiveRecord|null
     */
    protected function getBasket()
    {
        if($this->_basket === null) {
            $user = $this->getUser();
            $this->_basket = Basket::find()
                    ->where(['id_user' => $user->id, 'date' => $this->date])
                    ->one();
        }

        return $this->_basket;
    }

    /**
     * @return mixed
     */
    protected function isAccepted()
    {
        $basket = $this->getBasket();

        return is_null($basket) ? false : boolval($basket->status);
    }

    /**
     * @return bool
     */
    protected function isRefused()
    {
        $user = $this->getUser();

        $date_end = $user->end_order_cancel;
        $date_start = $user->start_order_cancel;

        if(is_null($date_end) || is_null($date_start))
            return false;

        return $this->date <= $date_end && $this->date >= $date_start;
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $data = $this->_provider->getModelsValue();
        return $data;
    }

    /**
     * @return array
     */
    protected function getMeta()
    {
        $_meta = $this->_provider->getMeta();
        return $_meta;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        $links = $this->_provider->getLinks();
        return $links;
    }

    abstract function prepareDataProvider($data);

}