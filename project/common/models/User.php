<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property string $email
 * @property int $status
 * @property int $status_order
 * @property string $firstname
 * @property string $lastname
 * @property string|null $midname
 * @property int $limit
 * @property string|null $start_order_cancel
 * @property string|null $end_order_cancel
 * @property int|null $password_reset_token_created_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Basket[] $baskets
 * @property Elect[] $elects
 * @property Dish[] $dishes
 */

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return User::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByVerificationToken($token)
    {
        if(!User::isVerificationEmailTokenValid($token)){
            return null;
        }

        return User::findOne(['verification_token' => $token, 'status' => self::STATUS_INACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!User::isPasswordResetTokenValid($token)) {
            return null;
        }

        return User::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function isVerificationEmailTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.verificationEmailTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    /**
     * Gets query for [[Baskets]].
     *
     * @return ActiveQuery
     */
    public function getBaskets()
    {
        return $this->hasMany(Basket::className(), ['id_user' => 'id']);
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
        return $this->hasMany(Dish::className(), ['id' => 'id_dish'])->viaTable('elect', ['id_user' => 'id']);
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'email' => 'email',
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'midname' => 'midname',
            'order' => function(){
                return $this->status_order == 1 ? true : false;
            },
            'start' => 'start_order_cancel',
            'end' => 'end_order_cancel',
        ];
    }

}
