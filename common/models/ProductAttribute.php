<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_attribute".
 *
 * @property string $sku
 * @property string $color
 * @property string $size
 * @property integer $stock
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku', 'color', 'size', 'stock'], 'required'],
            [['stock'], 'integer'],
            [['sku', 'color'], 'string', 'max' => 50],
            [['size'], 'string', 'max' => 3],
        ];
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['sku' => 'sku']);
    }
    public function getTblSize()
    {
        return $this->hasOne(TblSize::className(), ['idsize' => 'size']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sku' => 'Sku',
            'color' => 'Color',
            'size' => 'Size',
            'stock' => 'Stock',
        ];
    }
}
