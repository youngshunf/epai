<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "merchant_guarantee".
 *
 * @property integer $id
 * @property string $fee_guid
 * @property string $user_guid
 * @property string $amount
 * @property integer $merchant_role
 * @property string $order_guid
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class MerchantGuarantee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'merchant_guarantee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['merchant_role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['fee_guid', 'user_guid', 'order_guid'], 'string', 'max' => 48]
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
            'amount' => '金额',
            'merchant_role' => '卖家等级',
            'order_guid' => 'Order Guid',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
