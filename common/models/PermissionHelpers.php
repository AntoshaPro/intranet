<?php
/**
 * Created by PhpStorm.
 * User: antoshapro
 * Date: 25.01.16
 * Time: 17:14
 */

namespace common\models;

use common\models\ValueHelpers;
use yii;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * Class PermissionHelpers
 * @package common\models
 */

class PermissionHelpers
{
/**
 * Проверяет если пользователь является владельцем записи используя Yii:$app->user->identiti->id для $userid, 'string'
 * для имени модели к примеру 'profile' проверяет модель профайл, что бы убедиться является ли пользователь владельцем
 * записи. Предоставляет экземпляр модели, обычно как $model->id как последний параметр. Возвращает true или false.
 * @param mixed $userid
 * @param mixed $model_name
 * @param mixed $model_id
 */
    public static function userMustBeOwner($model_name, $model_id)
    {
        $connection = \Yii::$app->db;
        $userid = Yii::$app->user->identity->id;
        $sql = "SELECT id FROM $model_name WHERE user_id=:userid AND id=:model_id";
        $command = $connection->createCommand($sql);
        $command->bindValue(":userid", $userid);
        $command->bindValue(":model_id", $model_id);
        if($result = $command->queryOne()) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param mixed $user_type_name
     * @return \yii\web\Response
     */

    public static function requireUpgradeTo($user_type_name)
    {
        if(Yii::$app->user->identity->user_type_id != ValueHelpers::getUserTypeValue($user_type_name)){
            return Yii::$app->getResponse()->redirect(Url::to(['upgrade/index']));
        }
    }

    /**
     * @requireStatus
     */

    public static function requireStatus($status_name)
    {
        if (Yii::$app->user->identity->status_id == ValueHelpers::getStatusValue($status_name)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * @requireMinimumStatus
     */

    public static function requireMinimumStatus($status_name)
    {
        if(Yii::$app->user->identity->status_id >= ValueHelpers::getStatusValue($status_name)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @requireRole
     * @param mixed $role_name
     */

    public static function requireRole($role_name)
    {
        if(Yii::$app->user->identity->role_id == ValueHelpers::getRoleValue($role_name)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @requireMinimumRole
     * @param mixed $role_name
     */

    public static function requireMinimumRole($role_name)
    {
        if(Yii::$app->user->identity->role_id >= ValueHelpers::getRoleValue($role_name)){
            return true;
        } else {
            return false;
        }
    }

}