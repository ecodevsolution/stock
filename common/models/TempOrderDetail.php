<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "temp_order_detail".
 *
 * @property integer $tmp_id
 * @property string $tmp_sku
 * @property integer $tmp_qty
 * @property double $tmp_price
 * @property string $tmp_size
 * @property string $tmp_color
 * @property double $tmp_discount
 * @property double $tmp_discount_amount
 * @property double $tmp_total_price
 */
class TempOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp_order_detail';
    }

    /**
     * @inheritdoc
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['sku' => 'tmp_sku']);
    }
    public function rules()
    {
        return [
            [['tmp_qty', 'tmp_price', 'tmp_discount', 'tmp_discount_amount', 'tmp_total_price'], 'required'],
            [['tmp_qty'], 'integer'],
            [['tmp_price', 'tmp_discount', 'tmp_discount_amount', 'tmp_total_price'], 'number'],
            [['tmp_sku', 'tmp_size', 'tmp_color'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tmp_id' => 'Tmp ID',
            'tmp_sku' => 'Tmp Sku',
            'tmp_qty' => 'Tmp Qty',
            'tmp_price' => 'Tmp Price',
            'tmp_size' => 'Tmp Size',
            'tmp_color' => 'Tmp Color',
            'tmp_discount' => 'Tmp Discount',
            'tmp_discount_amount' => 'Tmp Discount Amount',
            'tmp_total_price' => 'Tmp Total Price',
        ];
    }
}
