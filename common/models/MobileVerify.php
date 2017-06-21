<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mobile_verify".
 *
 * @property integer $id
 * @property string $verify_code
 * @property integer $is_valid
 * @property integer $created_at
 * @property string $mobile
 */
class MobileVerify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mobile_verify';
    }

    /**
     * @inheritdoc
     */
//     public function rules()
//     {
//         return [
//             [['is_valid', 'created_at'], 'integer'],
//             [['verify_code', 'mobile'], 'string', 'max' => 255]
//         ];
//     }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'verify_code' => 'Verify Code',
            'is_valid' => 'Is Valid',
            'created_at' => 'Created At',
            'mobile' => 'Mobile',
        ];
    }
}
