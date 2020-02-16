<?php


namespace frontend\modules\models\admin;


use yii\web\ServerErrorHttpException;

class LimitUserForm extends AbstractUserModel
{
    public $limit;

    public function rules()
    {
        $rules = parent::rules();
        array_push($rules, ['limit', 'required', 'message' => 'Поле {attribute} не может быть пустым']);
        array_push($rules, ['limit', 'integer', 'message' => 'Требуется число']);
        return $rules;
    }

    /**
     * @return $this
     * @throws ServerErrorHttpException
     */
    public function update()
    {
        $user = $this->getUser();

        $user->limit = $this->limit;

        if($user->save())
            return $this;

        throw new ServerErrorHttpException();
    }
}