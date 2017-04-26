<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "register_coupon".
 *
 * @property integer $id
 * @property integer $is_open
 * @property string $amount
 * @property string $min_amount
 * @property integer $expire_day
 * @property string $remark
 */
class RegisterCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'register_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_open', 'expire_day'], 'integer'],
            [['amount', 'min_amount'], 'number'],
            [['remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_open' => '是否开启注册优惠',
            'amount' => '优惠金额',
            'min_amount' => '最低使用门槛(元)',
            'expire_day' => '有效期(天）',
            'remark' => '备注',
        ];
    }
}
