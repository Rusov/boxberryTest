<?php

use yii\db\Migration;

/**
 * Class m230109_134517_Schemas
 */
class m230109_134517_Schemas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            '
            CREATE SCHEMA IF NOT EXISTS firsttask;
        '
        );
        $this->execute(
            '
            CREATE SCHEMA IF NOT EXISTS secondtask;
        '
        );
        $this->execute(
            '
            CREATE SCHEMA IF NOT EXISTS fourthtask;
        '
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute(
            '
            DROP SCHEMA IF EXISTS firsttask;
        '
        );
        $this->execute(
            '
            DROP SCHEMA IF EXISTS secondtask;
        '
        );
        $this->execute(
            '
            DROP SCHEMA IF EXISTS fourthtask;
        '
        );

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230109_134517_Schemas cannot be reverted.\n";

        return false;
    }
    */
}
