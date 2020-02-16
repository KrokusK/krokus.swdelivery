<?php

namespace frontend\modules\models\admin;


use frontend\modules\models\Provider;
use frontend\modules\models\User;
use Yii;
use yii\base\Model;
use yii\web\Linkable;

class UsersForm extends Model implements Linkable
{
    public $statuses;
    public $roles;
    public $users;
    public $_meta;

    /**
     * @var Provider
     */
    private $_provider;

    public function users()
    {
        $data = User::find()->all();

        $this->_provider = $this->prepareData($data);

        $this->statuses = $this->getStatuses();
        $this->roles = $this->getRoles();
        $this->users = $this->getData();
        $this->_meta = $this->getMeta();

        return $this;
    }

    /**
     * @param $data
     * @return Provider
     */
    private function prepareData($data)
    {
        return new Provider($data);
    }

    /**
     * @return array
     */
    private function getData()
    {
        $data = $this->_provider->getModelsValue();
        return $data;
    }

    /**
     * @return array
     */
    private function getMeta()
    {
        $_meta = $this->_provider->getMeta();
        return $_meta;
    }

    /**
     * @return array
     */
    private function getRoles()
    {
        $data = array_keys(Yii::$app->getAuthManager()->getRoles());
        $roles = [];
        foreach ($data as $role){
            array_push($roles, ['name' => $role]);
        }
        return $roles;
    }

    /**
     * @return array
     */
    private function getStatuses()
    {
        $statuses = [];
        array_push($statuses, ['id' => User::STATUS_INACTIVE,'name' => User::STATUS_INACTIVE_NAME]);
        array_push($statuses, ['id' => User::STATUS_ACTIVE,'name' => User::STATUS_ACTIVE_NAME]);
        return $statuses;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        $links = $this->_provider->getLinks();
        return $links;
    }

}