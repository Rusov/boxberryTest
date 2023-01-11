<?php

use app\components\Sensor;
use yii\db\Migration;

/**
 * Class m230109_141135_sensorsTable
 */
class m230109_141135_sensorsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        ini_set('memory_limit', '-1');

        $this->createTable('firsttask.sensors', [
            'id' => $this->primaryKey()->notNull(),
            'patientId' => $this->integer()->notNull(),
            'date' => $this->date()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'time' => $this->time()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'pressure' => $this->float()->notNull(),
            //Вроде давление может быть с дробью. Думаю не критично в рамках задачи
            'pulse' => $this->integer()->notNull(),
            'notNormally' => $this->boolean()->notNull()->defaultValue(false)->comment(
                'Превышена ли норма по пульсу, давлению'
            ),
        ]);

        $this->addForeignKey(
            'sensors_patientId_fk_patients_id',
            'firsttask.sensors',
            'patientId',
            'firsttask.Patients',
            'id',
            'CASCADE'
        );

        /*
            date первее, тк его можно впихнуть в in(), а не в диапазон (between) и составной индекс будет работать эффективнее.
            К тому же, предполагаю, в запросе часто будет использоваться фильтрация по дате
        */
        $this->createIndex(
            'm_index_notNormally_date_time',
            'firsttask.sensors',
            'notNormally, date, time'
        );

        //остальные индексы нет смысла делать сейчас, тк в условии задачи не прописано какие запросы могут быть еще


        //фейк данные
        $sensors = [];
        $timeIMax = 24 * 60 / 10; //количество итераций для времени

        for ($patient = 1; $patient < 500; $patient++) {
            for ($dateI = 7; $dateI >= 0; $dateI--) { // 7 дней
                for ($timeI = $timeIMax; $timeI > 0; $timeI--) {
                    $date = (new DateTime())->modify('-' . $dateI . ' day')->format('Y-m-d');
                    $timeMinus = $timeI * 10;
                    $time = (new DateTime('2000-01-01 00:00:00'))->modify(
                        '-' . $timeMinus . ' minutes'
                    )->format('H:i');
                    $pressure = mt_rand(95, 135);
                    $pulse = mt_rand(60, 80);
                    $notNormally = !Sensor::isNormal($pulse, $pressure);
                    $sensors[] = [
                        $patient,
                        $date,
                        $time,
                        $pressure,
                        $pulse,
                        $notNormally,
                    ];
                }
            }
        }
        $this->batchInsert(
            'firsttask.sensors',
            ['patientId', 'date', 'time', 'pressure', 'pulse', 'notNormally'],
            $sensors
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'sensors_patientId_fk_patients_id',
            'firsttask.sensors'
        );

        $this->dropIndex('m_index_notNormally_date_time', 'firsttask.sensors');

        $this->dropTable('firsttask.sensors');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230109_141135_sensorsTable cannot be reverted.\n";

        return false;
    }
    */
}
