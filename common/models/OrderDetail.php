<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_detail".
 *
 * @property string $order_no
 * @property string $sku
 * @property integer $qty
 * @property double $price
 * @property string $size
 * @property string $color
 * @property double $discount
 * @property double $total_price
 *
 * @property Order $orderNo
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qty', 'price', 'discount', 'total_price'], 'required'],
            [['qty'], 'integer'],
            [['price', 'discount', 'total_price'], 'number'],
            [['order_no', 'sku', 'size', 'color'], 'string', 'max' => 50],
            [['order_no'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_no' => 'order_no']],
        ];
    }

    /**
     * @inheritdoc
     */
     public function getProduct()
    {
        return $this->hasOne(Product::className(), ['sku' => 'sku']);
    }
    public function attributeLabels()
    {
        return [
            'order_no' => 'Order No',
            'sku' => 'Sku',
            'qty' => 'Qty',
            'price' => 'Price',
            'size' => 'Size',
            'color' => 'Color',
            'discount' => 'Discount',
            'total_price' => 'Total Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderNo()
    {
        return $this->hasOne(Order::className(), ['order_no' => 'order_no']);
    }
}
