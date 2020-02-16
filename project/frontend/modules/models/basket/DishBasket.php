<?php


namespace frontend\modules\models\basket;


use frontend\modules\models\Dish;

class DishBasket extends Dish
{
    public $total = 0;
    public $elect = false;

    /**
     * @return array|false
     */
    public function fields()
    {
        $fields = parent::fields();

        unset($fields['description'], $fields['weighty'], $fields['weight']);
        unset($fields['single'], $fields['isGarnish'], $fields['maybeGarnish']);

        $fields['elect'] = function (){
            return $this->elect;
        };
        $fields['total'] = function (){
            return $this->total;
        };

        return $fields;
    }
}