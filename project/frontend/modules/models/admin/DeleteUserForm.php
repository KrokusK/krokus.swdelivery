<?php

namespace frontend\modules\models\admin;

use yii\db\StaleObjectException;

class DeleteUserForm extends AbstractUserModel
{

    /**
     * @return false|int
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function update()
    {
        $user = $this->getUser();

        return $user->delete();
    }

}