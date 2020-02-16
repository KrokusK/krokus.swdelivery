<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dish".
 *
 * @property int $id
 * @property string $name
 * @property string $weight
 * @property int $weighty
 * @property int $single
 * @property string|null $description
 * @property string|null $image
 * @property int $isGarnish
 * @property int $maybeGarnish
 *
 * @property BasketDish[] $basketDishes
 * @property Basket[] $baskets
 * @property Elect[] $elects
 * @property User[] $users
 */
class Dish extends ActiveRecord
{

    public $active = false;
    public $amount = 0;
    public $price = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dish';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'weight', 'weighty', 'single', 'isGarnish', 'maybeGarnish'], 'required'],
            [['id', 'weight', 'weighty', 'single', 'isGarnish', 'maybeGarnish'], 'default', 'value' => null],
            [['id', 'weighty', 'single', 'isGarnish', 'maybeGarnish'], 'integer'],
            [['name', 'description', 'image', 'weight'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'weight' => 'Weight',
            'weighty' => 'Weighty',
            'single' => 'Single',
            'description' => 'Description',
            'image' => 'Image',
            'isGarnish' => 'Is Garnish',
            'maybeGarnish' => 'Maybe Garnish',
        ];
    }

    /**
     * Gets query for [[BasketDishes]].
     *
     * @return ActiveQuery
     */
    public function getBasketDishes()
    {
        return $this->hasMany(BasketDish::className(), ['id_dish' => 'id']);
    }

    /**
     * Gets query for [[Baskets]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getBaskets()
    {
        return $this->hasMany(Basket::className(), ['id' => 'id_basket'])->viaTable('basket_dish', ['id_dish' => 'id']);
    }

    /**
     * Gets query for [[Elects]].
     *
     * @return ActiveQuery
     */
    public function getElects()
    {
        return $this->hasMany(Elect::className(), ['id_dish' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])->viaTable('elect', ['id_dish' => 'id']);
    }

}
