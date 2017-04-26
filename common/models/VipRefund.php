<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_refund".
 *
 * @property integer $id
 * @property string $user_guid
 * @property integer $feeid
 * @property string $fee_guid
 * @property string $created_at
 */
class VipRefund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_refund';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feeid', 'created_at'], 'integer'],
            [['user_guid', 'fee_guid'], 'string', 'max' => 48]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_guid' => '用户GUID',
            'feeid' => 'Feeid',
            'fee_guid' => 'Fee Guid',
            'created_at' => 'Created At',
        ];
    }
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
    
    public function getFee(){
        return $this->hasOne(GuaranteeFee::className(), ['fee_guid'=>'fee_guid']);
    }
}
