<?php

use yii\db\Migration;

/**
 * Class m230109_135622_patientsTable
 */
class m230109_135622_patientsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('firsttask.Patients', [
            'id' => $this->primaryKey()->notNull(),
        ]);

        //фейк данные
        $patients = [];
        for ($i = 1; $i < 1000; $i++) {
            $patients[] = [$i];
        }
        $this->batchInsert('firsttask.Patients', ['id'], $patients);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('firsttask.Patients');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230109_135622_patientsTable cannot be reverted.\n";

        return false;
    }
    */
}
