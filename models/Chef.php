<?php


namespace app\models;


use yii\db\ActiveRecord;

class Chef extends ActiveRecord
{
    public static function tableName(){
        return 'tbl_chef';
    }

    public static function getData($where = ''){
        return self::find()->where($where)->asArray()->all();
    }
}