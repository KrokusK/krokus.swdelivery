<?php

namespace frontend\modules\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class Provider extends Model
{
    private $_provider;
    public $data;

    /**
     * Provider constructor.
     * @param $data
     * @param array $config
     */
    public function __construct($data, $config = [])
    {
        if(is_null($data))
            throw new InvalidArgumentException();
        $this->data = $data;
        parent::__construct($config);
    }

    /**
     * @return ArrayDataProvider
     */
    public function getProvider()
    {
        if($this->_provider !== null)
            return $this->_provider;

        $provider = new ArrayDataProvider([
            'allModels' => $this->data,
            'pagination' => [
                'pageSize' => getenv('PAGE_SIZE'),
            ],
        ]);

        $this->_provider = $provider;

        return $provider;
    }

    /**
     * @return array
     */
    public function getModelsValue()
    {
        return array_values($this->getProvider()->getModels());
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        $provider = $this->getProvider();
        return [
            'totalCount' => $provider->getTotalCount(),
            'pageCount' => $provider->getPagination()->pageCount,
            'currentPage' => $provider->getPagination()->page + 1,
            'perPage' => $provider->getPagination()->pageSize,
        ];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->getProvider()->getPagination()->getLinks();
    }

}