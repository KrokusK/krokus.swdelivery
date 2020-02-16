<?php


namespace frontend\modules\models\menu;


use frontend\modules\models\request\CategoryRequest;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Exception;

class CategoryForm extends Model
{
    private $_category;
    private $_array_categories = [];

    /**
     * CategoryForm constructor.
     * @param array $config
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function __construct($config = [])
    {
        $data = $this->categoryRequest();
        $this->_category = $this->prepareData($data);

        parent::__construct($config);
    }

    /**
     * @return array|mixed
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function categoryRequest()
    {
        $category_request = new CategoryRequest();
        $data = $category_request->response();

        return $data;
    }
// БД
    /**
     * @param $data
     * @return array
     */
    private function prepareData($data)
    {
        $categories = [];
        foreach ($data as $category){

            unset($category['alias']);
            unset($category['sort']);

            $this->_array_categories += [$category['id'] => $category['name']];
            array_push($categories, $category);
        }

        return $categories;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->_category;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategory($id)
    {
        if(!key_exists($id, $this->_array_categories)){
            return null;
        }

        return $this->_array_categories[$id];
    }
}