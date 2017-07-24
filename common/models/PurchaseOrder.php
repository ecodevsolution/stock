<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchase_order".
 *
 * @property integer $idpo
 * @property string $sku
 * @property integer $id
 * @property integer $qty
 * @property double $price
 * @property string $date
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty'], 'integer'],
            [['price'], 'number'],
            [['date'], 'safe'],
            [['sku'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idpo' => 'Idpo',
            'sku' => 'Sku',
            'id' => 'ID',
            'qty' => 'Qty',
            'price' => 'Price',
            'date' => 'Date',
        ];
    }
}
