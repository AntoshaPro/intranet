<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\helpers\Security;

/**
 * Модель User
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role_id
 * @property integer $status_id
 * @property integer $user_type_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    public static function tableName()
    {
        return 'user';
    }
    /**
     *behaviors
     *Поведения
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['status_id', 'default', 'value' => self::STATUS_ACTIVE],
            ['role_id', 'default', 'value' => 1],
            ['user_type_id', 'default', 'value' => 1],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 2, 'max'=>255],

            ['email', 'filter', 'filter'=> 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email','unique'],
        ];
    }
    /*Атрибуты модели */
    public function attributeLabels()
    {
        return[
            /*Еще атрибуты модели*/
        ];
    }
    /**
     * Поиск идентификатора
     * @findIdentity
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status_id' => self::STATUS_ACTIVE]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" не реализован.');
    }
    /**
     * Поиск пользователя по имени пользователя
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status_id' => self::STATUS_ACTIVE]);
    }
    /**
     * Поиск пользователя по сбросу токена пароля
     * @param string $token токен сброса пароля
     * @return static|null
     */

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)){
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status_id' => self::STATUS_ACTIVE,
        ]);
    }
    /**
     * Вычисляем если токен сброса пароля проходит проверку
     * @param string $token токен сброса пароля
     * @return boolean
     */

    public static function isPasswordResetTokenValid($token)
    {
        if(empty($token)){
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    /**
     * @getId
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    /**
     * @getAuthKey
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    /**
     * @validateAuthKey
     */

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /**
     * Проверка пароля
     *
     * @param string $password пароль для валидации
     * @return boolean если предоставленный пароль подходит пользователю
     */

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Генерируем хэш пароля и устанавливем его в модель
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Генерируем ключи идентификации "запомнить меня"
     */

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Генерируем новый токен сброса пароля
     */

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Удаляем токен сброса пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}