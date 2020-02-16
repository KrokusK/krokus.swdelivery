<?php


namespace frontend\modules\models\request;


class CategoryRequest extends AbstractRequestForm
{

    const FUNCTION_NAME = 'getGroupList';

    /**
     * CategoryRequest constructor.
     * @param null $date
     * @param array $config
     */
    public function __construct($date = null, $config = [])
    {
        parent::__construct($date, $config);
    }

    /**
     * @return mixed
     */
    protected function getContent()
    {
        $content = '<?xml version="' . self::VERSION . '" encoding="' . self::ENCODING. '"?>
                        <app>
                            <function>' . self::FUNCTION_NAME . '</function>
                        </app>';

        return $content;
    }
}