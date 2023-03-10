<?php

use yii\db\Migration;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class m230310_182139_createDb
 */
class m230310_182139_createDb extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->db->getTableSchema('tbl_chef', true) === null) {
            $this->createTable('tbl_chef', [
                'id' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => $this->string()->notNull()
            ]);
        }
        $rows = [];
        $arrChef = ArrayHelper::map((new Query())->from('tbl_chef')->all(), 'name', 'id');
        if (empty($arrChef['Dave'])) $rows[] = ['Dave'];
        if (empty($arrChef['Bob'])) $rows[] = ['Bob'];
        if (empty($arrChef['David'])) $rows[] = ['David'];
        if (empty($arrChef['Katrin'])) $rows[] = ['Katrin'];
        if ($rows)
            $this->batchInsert('tbl_chef', ['name'], $rows);

        if (Yii::$app->db->getTableSchema('tbl_items', true) === null) {
            $this->createTable('tbl_items', [
                'id' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => $this->string()->notNull(),
                'price' => $this->integer(11)->notNull(),
                'chef' => $this->integer(11)->notNull()
            ]);
        }
        $rows = [];
        $arrChef = ArrayHelper::map((new Query())->from('tbl_chef')->all(), 'name', 'id');
        $arrItems = ArrayHelper::map((new Query())->from('tbl_items')->all(), 'name', 'id');

        if (empty($arrItems['Grilled cheese sandwich'])) $rows[] = ['Grilled cheese sandwich', 800, $arrChef['Bob']];
        if (empty($arrItems['House Made Slaw'])) $rows[] = ['House Made Slaw', 350, $arrChef['Bob']];
        if (empty($arrItems['Lobster Bisque'])) $rows[] = ['Lobster Bisque', 650, $arrChef['Dave']];
        if (empty($arrItems['Cheesecake'])) $rows[] = ['Cheesecake', 520, $arrChef['Dave']];
        if (empty($arrItems['Fried Shrimp Po Boy'])) $rows[] = ['Fried Shrimp Po Boy', 1050, $arrChef['Katrin']];
        if (empty($arrItems['Mac&Cheese'])) $rows[] = ['Mac&Cheese', 780, $arrChef['Katrin']];
        if (empty($arrItems['Classic Lobster Roll'])) $rows[] = ['Classic Lobster Roll', 1500, $arrChef['David']];
        if (empty($arrItems['Tater tots'])) $rows[] = ['Tater tots', 240, $arrChef['David']];

        $this->batchInsert('tbl_items', ['name', 'price', 'chef'], $rows);

        if (Yii::$app->db->getTableSchema('tbl_orders', true) === null) {
            $this->createTable('tbl_orders', [
                'id' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'createDate' => $this->integer(11)->notNull(),
            ]);
        }

        $arrOrders = ArrayHelper::map((new Query())->from('tbl_orders')->all(), 'name', 'id');
        if (!$arrOrders) {
            $rows = [
                [time()],
                [time() - 3600],
                [time() - 7200],
                [time() - 10800],
                [time() - 14400],
                [time() - 18000],
                [time() - 21600],
            ];
            $this->batchInsert('tbl_orders', ['createDate'], $rows);
        }

        if (Yii::$app->db->getTableSchema('tbl_order_items', true) === null) {
            $this->createTable('tbl_order_items', [
                'id' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'idOrder' => $this->integer(11)->notNull(),
                'idProduct' => $this->integer(11)->notNull(),
                'price' => $this->integer(11)->notNull(),
            ]);
        }

        $arrOrders = (new Query())->from('tbl_orders')->all();

        if ($arrOrders) {
            foreach ($arrOrders as $arrOrder) {
                $arrItems = (new Query())->from('tbl_order_items')->where(['idOrder' => $arrOrder['id']])->all();
                if (!$arrItems) {
                    $countItems = self::generator();
                    $arr = []; $itemNumber = false;
                    for($i = 0; $i < $countItems; $i++) {
                        while (!$itemNumber) {
                            $itemNumber = self::generator();
                            if (in_array($itemNumber, $arr)) $itemNumber = false;
                            else $arr[] = $itemNumber;
                        }
                        $item = (new Query())->from('tbl_items')->where(['id' => $itemNumber])->one();
                        $this->insert('tbl_order_items', ['idOrder'=>$arrOrder['id'], 'idProduct'=>$item['id'], 'price' => $item['price']]);
                        $itemNumber = false;
                    }
                }
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (Yii::$app->db->getTableSchema('tbl_chef', true) === null)
            $this->dropTable('tbl_chef');
        if (Yii::$app->db->getTableSchema('tbl_items', true) === null)
            $this->dropTable('tbl_items');
        if (Yii::$app->db->getTableSchema('tbl_orders', true) === null)
            $this->dropTable('tbl_orders');
        if (Yii::$app->db->getTableSchema('tbl_order_items', true) === null)
            $this->dropTable('tbl_order_items');
    }

    protected function generator()
    {
        $array = array(1, 2, 3, 4, 5, 6, 7, 8);
        $arr = array_rand($array, 1);

        return $array[$arr];
    }
}
