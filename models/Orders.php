<?php


namespace app\models;


use yii\db\ActiveRecord;

class Orders extends ActiveRecord
{
    public static function tableName(){
        return 'tbl_orders';
    }

    public static function create() {
        $model = new self();
        $model->createDate = time();
        if ($model->insert()) return $model->id;
        else return false;
    }
}