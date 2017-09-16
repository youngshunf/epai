<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property integer $id
 * @property string $coupon_code
 * @property string $amount
 * @property integer $status
 * @property integer $end_time
 * @property integer $type
 * @property string $min_amount
 * @property string $created_user
 * @property string $user_guid
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'min_amount','end_time'], 'required'],
            [['amount', 'min_amount'], 'number'],
            [['status', 'type', 'created_at', 'updated_at'], 'integer'],
            [['coupon_code','mobile'], 'string', 'max' => 32],
            [['created_user', 'user_guid', 'remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coupon_code' => '优惠券码',
            'amount' => '优惠券金额(元)',
            'status' => '状态',
            'end_time' => '过期时间',
            'type' => '优惠券类型',
            'min_amount' => '使用门槛(元)',
            'created_user' => '发放用户',
            'user_guid' => 'User Guid',
            'remark' => '备注',
            'mobile'=>'手机号',    
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
    
    public static  function generateCouponCode() {
        $code=yii::$app->security->generateRandomKey();
        $start=rand(0,25);
        return strtoupper(substr(MD5($code), $start,6));
    }
    
}
