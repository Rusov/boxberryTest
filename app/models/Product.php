<?php

declare(strict_types=1);

namespace app\models;

/**
 * This is the model class for table "params_relations".
 *
 * @property int    $id
 * @property string $name
 * @property string $color
 * @property float  $price
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }
}
