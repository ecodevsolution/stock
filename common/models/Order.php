<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property string $order_no
 * @property double $total_qty
 * @property double $grandtotal
 * @property string $date
 * @property string $status
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no', 'customer','payment','total_qty', 'grandtotal', 'date'], 'required'],
            [['total_qty', 'grandtotal'], 'number'],
            [['date'], 'safe'],
            [['order_no', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_no' => 'Order No',
            'customer'=> 'customer',
            'payment'=> 'payment',
            'total_qty' => 'Total Qty',
            'grandtotal' => 'Grandtotal',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }
}
