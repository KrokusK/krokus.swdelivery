<?php


namespace frontend\modules\models\request;


class DishRequest extends AbstractRequestForm
{

    const FUNCTION_NAME = 'getItemByIdAndDate';

    public $id;

    /**
     * DishRequest constructor.
     * @param $date
     * @param $id
     * @param array $config
     */
    public function __construct($date, $id, $config = [])
    {
        $this->id = $id;
        parent::__construct($date, $config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        array_push($rules, ['id', 'required', 'message' =>'Поле {attribute} не может быть пустым']);
        array_push($rules, ['id', 'integer', 'message' => 'Поле {attribute} целое число']);
        return $rules;
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $content = '<?xml version="' . self::VERSION . '" encoding="' . self::ENCODING. '"?>
                        <app>
                            <function>' . self::FUNCTION_NAME . '</function>
                            <item_id>' .$this->id . '</item_id>
                            <date>'. $this->date . '</date>
                        </app>';

        return $content;
    }
}