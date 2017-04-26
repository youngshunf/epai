<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wxpay_refund_rec".
 *
 * @property integer $id
 * @property string $appid
 * @property string $mch_id
 * @property string $device_info
 * @property string $nonce_str
 * @property string $transaction_id
 * @property string $out_trade_no
 * @property string $out_refund_no
 * @property string $refund_id
 * @property string $refund_channel
 * @property integer $refund_fee
 * @property integer $total_fee
 * @property integer $cash_fee
 * @property integer $cash_refund_fee
 * @property integer $coupon_refund_fee
 * @property integer $coupon_refund_count
 * @property string $coupon_refund_id
 * @property string $created_at
 */
class WxpayRefundRec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wxpay_refund_rec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['refund_fee', 'total_fee', 'cash_fee', 'cash_refund_fee', 'coupon_refund_fee', 'coupon_refund_count', 'created_at'], 'integer'],
            [['appid', 'device_info', 'transaction_id', 'out_trade_no', 'out_refund_no', 'refund_id'], 'string', 'max' => 64],
            [['mch_id', 'refund_channel'], 'string', 'max' => 20],
            [['nonce_str'], 'string', 'max' => 255],
            [['coupon_refund_id'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appid' => 'Appid',
            'mch_id' => 'Mch ID',
            'device_info' => 'Device Info',
            'nonce_str' => 'Nonce Str',
            'transaction_id' => 'Transaction ID',
            'out_trade_no' => 'Out Trade No',
            'out_refund_no' => 'Out Refund No',
            'refund_id' => 'Refund ID',
            'refund_channel' => 'Refund Channel',
            'refund_fee' => 'Refund Fee',
            'total_fee' => 'Total Fee',
            'cash_fee' => 'Cash Fee',
            'cash_refund_fee' => 'Cash Refund Fee',
            'coupon_refund_fee' => 'Coupon Refund Fee',
            'coupon_refund_count' => 'Coupon Refund Count',
            'coupon_refund_id' => 'Coupon Refund ID',
            'created_at' => 'Created At',
        ];
    }
}
