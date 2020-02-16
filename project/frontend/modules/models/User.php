<?php


namespace frontend\modules\models;

use common\models\Elect;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class User extends \common\models\User
{

    const STATUS_ACTIVE_NAME = 'Подтвержден';
    const STATUS_INACTIVE_NAME = 'Не подтвержден';

    /**
     * @param $id
     * @return User|null
     */
    public static function findUserById($id)
    {
        return User::findOne(['id' => $id]);
    }

    /**
     * Gets query for [[Elects]].
     *
     * @return ActiveQuery
     */
    public function getElects()
    {
        return $this->hasMany(Elect::className(), ['id_user' => 'id']);
    }

    /**
     * Gets query for [[Dishes]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getDishes()
    {
        return $this->hasMany(Dish::className(), ['id' => 'id_dish'])->viaTable('{{%elect}}', ['id_user' => 'id']);
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'email' => 'email',
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'role' => function(){
                $authManager = Yii::$app->getAuthManager();
                return key($authManager->getRolesByUser($this->id));
            },
            'status' => 'status',
//                function(){
//                return $this->status ? 'Подтвержден' : 'Не подтвержден';
//            },
            'limit' => 'limit',
            'start' => 'start_order_cancel',
            'end' => 'end_order_cancel',

        ];
    }


}