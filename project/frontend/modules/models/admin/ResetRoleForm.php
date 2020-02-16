<?php


namespace frontend\modules\models\admin;

use Yii;
use yii\rbac\ManagerInterface;

class ResetRoleForm extends AbstractUserModel
{
    public $role;

    private $_authManager;

    public function rules()
    {
        $rules = parent::rules();
        array_push($rules, ['role', 'required', 'message' => 'Поле {attribute} не может быть пустым']);
        array_push($rules, ['role', 'isExist']);
        array_push($rules, ['id', 'isCurrent']);
        return $rules;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    function update()
    {
        $user = $this->getUser();

        $authManager = $this->getAuthManager();

        $authManager->revokeAll($user->id);

        $authManager->assign($authManager->getRole($this->role), $user->id);

        return true;
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function isExist($attribute, $params)
    {
        $role = $this->getAuthManager()->getRole($this->role);

        if(is_null($role)){
            $this->addError($attribute, 'Указанной роли не существует');
        }
    }

    public function isCurrent($attribute, $params)
    {
        $id = Yii::$app->user->identity->id;

        if($this->id == $id){
            $this->addError($attribute, 'Вы не можете изменить роль этому пользователю');
        }
    }

    /**
     * @return ManagerInterface
     */
    private function getAuthManager()
    {
        if($this->_authManager === null){
            $this->_authManager = Yii::$app->getAuthManager();
        }

        return $this->_authManager;
    }
}