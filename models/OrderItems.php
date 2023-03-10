<?php


namespace app\models;


use yii\db\ActiveRecord;

class OrderItems extends ActiveRecord
{
    public static function tableName(){
        return 'tbl_order_items';
    }

    public static function create($arr) {
        $model = new self();
        $model->idOrder = $arr['orderID'];
        $model->idProduct = $arr['productID'];
        $model->price = $arr['price'];
        if ($model->insert()) return true;
        else return false;
    }

    public static function ItemsWithChef(int $start, int $end, int $chefID): int {
        $query = self::find()
            ->select('tbl_order_items.*')
            ->leftJoin('tbl_orders','tbl_order_items.idOrder = tbl_orders.id')
            ->leftJoin('tbl_items','tbl_order_items.idProduct = tbl_items.id')
            ->where('tbl_orders.createDate BETWEEN ' . $start . ' AND ' . $end)
            ->andWhere('tbl_items.chef =' . $chefID)
            ->asArray()
            ->count();

        return $query;
    }
}