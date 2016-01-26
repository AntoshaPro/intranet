<?php
/**
 * Created by PhpStorm.
 * User: Проскурин Антон
 * Date: 25.01.16
 * Time: 16:36
 */

namespace common\models;

class ValueHelpers
{
    /**
     * возвращает значение имени роли в строку
     * к примеру: 'Admin'
     *
     * @param mixed $role_name
     */

    public static function getRoleValue($role_name)
    {
        $connection = \Yii::$app->db;
        $sql = 'SELECT role_value FROM role WHERE role_name=:role_name';
        $command = $connection->createCommand($sql);
        $command->bindValue(':role_name', $role_name);
        $result = $command->queryOne();

        return $result["'role_value'"];
    }

    /**
     * возвращает значение имени статуса в строку
     * например: 'Активный'
     * @param mixed $status_name
     */

    public static function getStatusValue($status_name)
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT status_value FROM status WHERE status_name= :status_name";
        $command = $connection->createCommand($sql);
        $command->bindValue(":status_name", $status_name);
        $result = $command->queryOne();

        return $result["'status_value'"];
    }

    /**
     * Возвращает значение user_type_name так что можно использовать в методах PermissionHelpers
     * в строку. Например 'Руководитель'
     *
     * @param mixed $user_type_name
     */

    public static function getUserTypeValue($user_type_name)
    {
        $connection = \Yii::$app->db;
        $sql = "SELECT user_type_value FROM user_type WHERE user_type_name=:user_type_name";
        $command = $connection->createCommand($sql);
        $command->bindValue(":user_type_name", $user_type_name);
        $resul = $command->queryOne();

        return $resul['user_type_value'];
    }
}