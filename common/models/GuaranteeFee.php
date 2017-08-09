<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "guarantee_fee".
 *
 * @property integer $id
 * @property string $fee_guid
 * @property string $order_guid
 * @property string $user_guid
 * @property string $goods_guid
 * @property double $guarantee_fee
 * @property integer $is_pay
 * @property string $created_at
 * @property string $updated_at
 */
class GuaranteeFee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guarantee_fee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guarantee_fee'], 'number'],
            [['is_pay', 'created_at', 'updated_at'], 'integer'],
            [['fee_guid', 'user_guid', 'goods_guid'], 'string', 'max' => 48]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fee_guid' => 'Fee Guid',
            'user_guid' => 'User Guid',
            'goods_guid' => 'Goods Guid',
            'guarantee_fee' => '保证金',
            'is_pay' => '是否支付',
            'created_at' => '时间',
            'updated_at' => '更新时间',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
    
}
