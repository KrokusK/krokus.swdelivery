<?php


namespace frontend\modules\models\request;


class MenuRequest extends AbstractRequestForm
{
    const FUNCTION_NAME = 'getMenuByDate';

    /**
     * @return string
     */
    protected function getContent()
    {
        $content = '<?xml version="' . self::VERSION . '" encoding="' . self::ENCODING. '"?>
                        <app>
                            <function>' . self::FUNCTION_NAME . '</function>
                            <date>'. $this->date . '</date>
                        </app>';

        return $content;
    }

}