<?php

use yii\db\Migration;

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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230310_182139_createDb cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230310_182139_createDb cannot be reverted.\n";

        return false;
    }
    */
}
