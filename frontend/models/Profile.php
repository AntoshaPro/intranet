<?php

namespace frontend\models;

use Yii;
use common\models\User;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\Expression;

/**
 * Это модель объекта для таблицы "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $birthdate
 * @property integer $gender_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Gender $gender
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'gender_id'], 'required'],
            [['user_id', 'gender_id'], 'integer'],
            [['first_name', 'second_name', 'last_name'], 'string'],
            [['birthdate', 'created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Идентификатор пользователя',
            'first_name' => 'Имя',
            'second_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'birthdate' => 'Дата рождения',
            'gender_id' => 'Пол',
            'created_at' => 'Дата создание профиля',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(Gender::className(), ['id' => 'gender_id']);
    }

    /**
     * Реализация метода поведений для контроля меткой времени, не забывайте объявлять операторы
     */

    public function behaviors()
    {
        return[
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
     * Использование магии getGender
     * Вернуть наименование пола
     */

    public function getGenderName()
    {
        return $this->gender->gender_name;
    }

    /**
     * Реализация метода getGenderList() для выпадающего мендю
     */

    public function getGenderList()
    {
        $droptions = Gender::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id', 'gender_name');
    }

    /**
     * Возвращаем запрос из базы данных
     * @return \yii\db\ActiveQuery
     * Реализуем метод getUser()  по id
     *
     */

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }

    /**
     * Реализация метода getUsername для извлечения имени пользователя
     * @get Username
     */

    public function getUserName()
    {
        return $this->user->username;
    }

    /**
     * @getUserId
     * Реализация метода getUserId для извлечения айди пользователя
     */

    public function getUserId()
    {
        return $this->user ? $this->user->id : 'идентификатор отсутствует';
    }

    /**
     * Реализация метода getUserLink ссылка на профиль
     * @getUserLink
     */

    public function getUserLink()
    {
        $url = Url::to(['user/view', 'id'=>$this->UserId]);
        $options = [];
        return Html::a($this->getUserName(), $url, $options);
    }

    /**
     * @getProfileLink
     */

    public function getProfileLink()
    {
        $url = Url::to(['profile/update', 'id'=>$this->id]);
        $options = [];
        return Html::a($this->id, $url, $options);
    }
}
