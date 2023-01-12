<?php

namespace app\components\product;

use app\models\Product;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "params_relations".
 *
 * @property int     $id
 * @property int     $product_id
 * @property Product $product
 */
class ProductUpdateTemp extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_update_temp';
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
