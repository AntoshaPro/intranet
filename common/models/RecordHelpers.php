<?php
/**
 * Created by PhpStorm.
 * User: antoshapro
 * Date: 25.01.16
 * Time: 17:54
 */

namespace common\models;

use yii;


class RecordHelpers
{
    /**
     * @param $model_name
     * @return bool
     */
    public static function userHas($model_name)
    {
        $connection = \Yii::$app->db;
        $userid = Yii::$app->user->identity->id;
        $sql = "SELECT id FROM $model_name WHERE user_id=:userid";
        $command = $connection->createCommand($sql);
        $command->bindValue(":userid", $userid);
        $result = $command->queryOne();

        if($result == null){
            return false;
        } else {
            return $result['id'];
        }
    }
}