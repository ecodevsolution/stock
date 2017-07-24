<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $idbrand
 * @property string $brand
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idbrand' => 'Idbrand',
            'brand' => 'Brand',
        ];
    }
}
