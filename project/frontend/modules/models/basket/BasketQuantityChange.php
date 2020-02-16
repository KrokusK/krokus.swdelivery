<?php


namespace frontend\modules\models\basket;

use common\models\Basket;
use common\models\BasketDish;
use common\models\Dish;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;
use yii\web\ServerErrorHttpException;

class BasketQuantityChange extends Model
{
    public $id;
    public $date;
    public $amount = 1;

    private $_user;
    private $_basket;

    public function rules()
    {
        return [
            ['id', 'required', 'message' =>'Поле {attribute} не может быть пустым'],
            ['id', 'integer', 'message' => 'Поле {attribute} целое число'],
            ['id', 'isId'],

            ['date', 'default', 'value' => function(){
                return date(getenv('DATE_FORMAT_REQUEST'));
            }],
            ['date', 'match', 'pattern' => '/^\d{4}.\d{2}.\d{2}$/', 'message' => 'Неверный формат даты'],
            ['date', 'isCorrect'],

            ['amount', 'integer', 'min' => 0, 'tooSmall' => '{attribute} неотрицательное число']
        ];
    }

    /**
     * @return array|ActiveRecord|null
     */
    protected function getBasket()
    {
        if($this->_basket === null) {
            $this->_basket = Basket::find()
                ->where(['id_user' => $this->getUser()->id, 'date' => $this->date])
                ->one();
        }

        return $this->_basket;
    }

    /**
     * @param $id_basket
     * @param $id_dish
     * @return array|ActiveRecord|null
     */
    protected function getBasketDish($id_basket, $id_dish)
    {
        return BasketDish::find()
                ->where(['id_basket' => $id_basket, 'id_dish' => $id_dish])
                ->one();
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function isId($attribute, $params)
    {
        if(!Dish::find()->where(['id' => $this->id])->exists()){
            $this->addError($attribute, 'id не существует');
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function isCorrect($attribute, $params)
    {
        if($this->date < date(getenv('DATE_FORMAT_REQUEST'))){
            $this->addError($attribute, 'Некорректная дата');
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    protected function isAccept()
    {
        $basket = $this->getBasket();

        if(!is_null($basket) && $basket->status == Basket::STATUS_ACCEPT)
            throw new BadRequestHttpException('Заказ недоступен для редактирования');
    }

    /**
     * @param $basket_dish ActiveRecord
     * @return bool
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    protected function deleteBasketDish($basket_dish)
    {
        if(!$basket_dish->delete())
            throw new ServerErrorHttpException('Не удалось удалить блюдо');

        return true;
    }

    /**
     * @return IdentityInterface|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }
}