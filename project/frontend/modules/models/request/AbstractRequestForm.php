<?php


namespace frontend\modules\models\request;


use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Client;
use yii\httpclient\Exception;

abstract class AbstractRequestForm extends Model
{
    const BASE_URL = 'https://edatomsk.ru';

    const URL = '/api/application.php';

    const CONTENT_TYPE = 'text/html; charset=utf-8';

    const ACCEPT_ENCODING = 'gzip, deflate';

    const VERSION = '1.0';

    const ENCODING = 'UTF-8';

    public $date;

    /**
     * AbstractRequestForm constructor.
     * @param $date
     * @param array $config
     */
    public function __construct($date, $config = [])
    {
        $this->date = $date;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['date', 'match', 'pattern' => '/^\d{4}.\d{2}.\d{2}$/', 'message' => 'Неверный формат даты'],
        ];
    }

    /**
     * @return array|mixed
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function response()
    {
        if(!$this->validate())
            return $this->getErrors();

        $client = new Client(['baseUrl' => self::BASE_URL]);

        $response = $client->createRequest()
            ->setUrl(self::URL)
            ->addHeaders([
                'content-type' => self::CONTENT_TYPE,
                'Accept-Encoding' => self::ACCEPT_ENCODING
            ])
            ->setContent($this->getContent())
            ->send();

        return json_decode($response->content, true);
    }

    /**
     * @return mixed
     */
    abstract protected function getContent();

}