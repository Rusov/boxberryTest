<?php

declare(strict_types=1);

namespace app\components\product;

use app\models\Product;
use RuntimeException;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\mutex\FileMutex;

class PriceBulkUpdate
{
    /**
     * @var int Пересоздать временную таблицу
     */
    private int $recreateTable;

    public function __construct($recreateTable = 0)
    {
        $this->recreateTable = $recreateTable;
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function run()
    {
        $mutex = new FileMutex();
        $mutexName = 'price-bulk-update-mutex';
        $unlockSeconds = 5;

        if ($mutex->acquire($mutexName, $unlockSeconds)) {
            //обновляем
            $this->checkTempTable();

            $this->updatePriceBulk();

            $this->dropTempTableIfEmpty();

            $mutex->release($mutexName);
        } else {
            throw new RuntimeException('Уже запущено');
        }
    }

    /**
     */
    private function checkTempTable(): void
    {
        if ($this->recreateTable) {
            $this->dropTempTable();
            $this->createTempTable();
            $this->encrichTempTable();
        }

        if (
            !$this->recreateTable
            && Yii::$app->db->getTableSchema(ProductUpdateTemp::tableName(), true) === null
        ) {
            $this->createTempTable();
            $this->encrichTempTable();
        }

    }

    private function createTempTable(): void
    {
        Yii::$app->getDb()->createCommand()->createTable(
            ProductUpdateTemp::tableName(),
            [
                'id' => 'pk',
                'product_id' => 'int',
            ]
        );

        Yii::$app->getDb()->createCommand()->addForeignKey(
            ProductUpdateTemp::tableName() . '_product_id_fk_' . Product::tableName() . '_id',
            ProductUpdateTemp::tableName(),
            'product_id',
            Product::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * Заполнение временной таблицы всеми продуктами
     *
     * @return void
     */
    private function encrichTempTable(): void
    {
        try {
            Yii::$app->getDb()
                ->createCommand(
                    'INSERT INTO 
                        ' . ProductUpdateTemp::tableName() . ' (product_id, color) 
                        SELECT id, color 
                        FROM ' . Product::tableName()
                )
                ->execute();
        } catch (Exception $e) {
            /// + запись в логи
            throw new RuntimeException('Ошибка при создании ' . ProductUpdateTemp::tableName());
        }
    }

    private function dropTempTable(): void
    {
        Yii::$app->getDb()->createCommand()->dropTable(ProductUpdateTemp::tableName());
    }

    private function dropTempTableIfEmpty(): void
    {
        $cnt = ProductUpdateTemp::find()
            ->limit(1)
            ->count();

        if ($cnt !== 1) {
            $this->dropTempTable();
        }
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    private function updatePriceBulk(): void
    {
        try {
            $productUpdateTemp = ProductUpdateTemp::find()
                ->with('product')
                ->limit(10)
                ->all();

            /** @var ProductUpdateTemp $productTemp */
            foreach ($productUpdateTemp as $productTemp) {
                $newPrice = $this->getNewPrice($productTemp->product);
                $productTemp->product->price = $newPrice;
                $productTemp->save();
            }

            $productTempIds = ArrayHelper::getColumn($productUpdateTemp, 'id');
            ProductUpdateTemp::deleteAll(['id' => $productTempIds]);

        } catch (\Exception $e) {
            /// + запись в логи
            throw new RuntimeException('Ошибка при обновлении цены');
        }
    }

    /**
     * @param Product $product
     *
     * @return mixed
     */
    private function getNewPrice(Product $product)
    {
        $color = $product->color;
        $price = $product->price;

        if ($color === 'red') {
            $newPrice = $price * 0.95;
        } elseif ($color === 'green') {
            $newPrice = $price * 1.1;
        } else {
            throw new RuntimeException('Нет условия для цвета ' . $color);
        }

        return $newPrice;
    }
}
