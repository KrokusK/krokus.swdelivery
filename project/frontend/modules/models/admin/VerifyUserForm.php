<?php


namespace frontend\modules\models\admin;


use common\models\User;

class VerifyUserForm extends AbstractUserModel
{
    public function rules()
    {
        $rules = parent::rules();
        $rules['isValidId'] =  ['id', 'exist',
            'targetClass' => '\common\models\User',
            'filter' => ['status' => User::STATUS_INACTIVE],
            'message' => 'Пользователь не существует'
        ];
        return $rules;
    }

    /**
     * @return bool
     */
    function update()
    {
       $user = $this->getUser();

       $user->status = User::STATUS_ACTIVE;

       return $user->save();
    }
}