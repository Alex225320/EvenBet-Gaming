<?php


namespace app\models;


use yii\db\ActiveRecord;

class Items extends ActiveRecord
{
    public static function tableName(){
        return 'tbl_items';
    }
}