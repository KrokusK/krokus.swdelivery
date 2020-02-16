<?php


namespace frontend\modules\models;


use yii\helpers\Url;
use yii\web\Linkable;

class Dish extends \common\models\Dish implements Linkable
{
    /**
     * @return array|false
     */
    public function fields()
    {
        $fields =  parent::fields();
        $fields['price'] = function (){
            return $this->price;
        };
        $fields['active'] = function (){
            return $this->active;
        };
        $fields['amount'] = function (){
            return $this->amount;
        };
        return $fields;
    }

    public function getLinks()
    {
        $links = [];
        $links['edit'] = Url::to(['/modules/account/edit'], true);
        $links['add'] = Url::to(['/modules/basket/add'], true);
        $links['reduce'] = Url::to(['/modules/basket/reduce'], true);
        $links['delete'] = Url::to(['/modules/basket/delete'], true);
        return $links;
    }
}