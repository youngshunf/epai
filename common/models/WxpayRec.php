<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wxpay_rec".
 *
 * @property integer $id
 * @property string $appid
 * @property string $attach
 * @property string $bank_type
 * @property double $cash_fee
 * @property string $fee_type
 * @property string $is_subscribe
 * @property string $mch_id
 * @property string $nonce_str
 * @property string $openid
 * @property string $out_trade_no
 * @property string $result_code
 * @property string $return_code
 * @property string $sign
 * @property string $time_end
 * @property double $total_fee
 * @property string $trade_type
 * @property string $transaction_id
 * @property string $created_at
 */
class WxpayRec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wxpay_rec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cash_fee', 'total_fee'], 'number'],
            [['created_at'], 'integer'],
            [['appid', 'transaction_id'], 'string', 'max' => 64],
            [['attach', 'nonce_str', 'out_trade_no', 'sign'], 'string', 'max' => 255],
            [['bank_type', 'fee_type', 'result_code', 'return_code'], 'string', 'max' => 20],
            [['is_subscribe', 'trade_type'], 'string', 'max' => 10],
            [['mch_id'], 'string', 'max' => 32],
            [['openid', 'time_end'], 'string', 'max' => 128]
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
            'attach' => 'Attach',
            'bank_type' => 'Bank Type',
            'cash_fee' => 'Cash Fee',
            'fee_type' => 'Fee Type',
            'is_subscribe' => 'Is Subscribe',
            'mch_id' => 'Mch ID',
            'nonce_str' => 'Nonce Str',
            'openid' => 'Openid',
            'out_trade_no' => 'Out Trade No',
            'result_code' => 'Result Code',
            'return_code' => 'Return Code',
            'sign' => 'Sign',
            'time_end' => 'Time End',
            'total_fee' => 'Total Fee',
            'trade_type' => 'Trade Type',
            'transaction_id' => 'Transaction ID',
            'created_at' => 'Created At',
        ];
    }
}
