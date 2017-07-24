<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history_order".
 *
 * @property string $order_id
 * @property integer $id
 * @property integer $status
 * @property string $date
 */
class HistoryOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date'], 'safe'],
            [['order_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'id' => 'ID',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }
}
