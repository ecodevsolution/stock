<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property string $sku
 * @property integer $idcategory
 * @property integer $idbrand
 * @property string $product_name
 * @property double $price
 * @property double $price_sell
 * @property string $image
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['idcategory' => 'idcategory']);
    }
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['sku' => 'sku']);
    }
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['idbrand' => 'idbrand']);
    }

    public function rules()
    {
        return [
            [['sku', 'id', 'date', 'idcategory', 'idbrand', 'price', 'price_sell'], 'required'],
            [['idcategory', 'idbrand'], 'integer'],
            [['price', 'price_sell'], 'number'],
            [['sku', 'product_name', 'image'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sku' => 'Sku',
            'id' => 'id',
            'idcategory' => 'Idcategory',
            'idbrand' => 'Idbrand',
            'product_name' => 'Product Name',
            'price' => 'Price',
            'price_sell' => 'Price Sell',
            'image' => 'Image',
            'date'=> 'date',
        ];
    }
}
