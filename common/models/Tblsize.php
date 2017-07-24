<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tblsize".
 *
 * @property integer $idsize
 * @property string $size
 */
class Tblsize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblsize';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idsize' => 'Idsize',
            'size' => 'Size',
        ];
    }
}
