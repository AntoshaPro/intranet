<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // имя пользователя и пароль оба необходимы
            [['username', 'password'], 'required'],
            // rememberMe должна быть boolean
            ['rememberMe', 'boolean'],
            // проверка пароля осуществляется при помощи validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Сверка пароля.
     * Этот метод служит встроенной проверки допустимости пароля.
     *
     * @param string $attribute проверяемый атрибут
     * @param array $params дополнительные параметры
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Имя пользователя или пароль не правильные.');
            }
        }
    }

    /**
     * Пользователь входящий в систему в данный момент.
     *
     * @return boolean возвращается при успехе
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Найти пользователя по [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
