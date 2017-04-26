<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $order_guid
 * @property string $orderno
 * @property integer $type
 * @property string $biz_guid
 * @property double $amount
 * @property integer $is_pay
 * @property string $pay_time
 * @property string $created_at
 * @property string $updated_at
 */
class Order extends \yii\db\ActiveRecord
{
    const TYPE_GUARANTEE=0;//拍卖保证金订单
    const TYPE_AUCTION=1;//拍卖订单
    const TYPE_MALL=2;//商城订单
    const TYPE_LOTTERY=3;//夺宝订单
    const TYPE_MERCHANT_GUARANTEE=4;//卖家保证金
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'is_pay', 'pay_time', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['user_guid', 'order_guid', 'orderno', 'biz_guid'], 'string', 'max' => 48]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_guid' => 'User Guid',
            'order_guid' => 'Order Guid',
            'orderno' => '订单编号',
            'type' => '订单类型',
            'biz_guid' => 'Biz Guid',
            'amount' => '金额',
            'goods_name'=>'商品名称',
            'status'=>'状态',
            'cancel_time'=>'取消时间',
            'express_number'=>'快递单号',
            'express_company'=>'快递公司',
            'number'=>'数量',
            'is_pay' => '是否支付',
            'pay_time' => '支付时间',
            'created_at' => '时间',
            'updated_at' => '更新时间',
            'address'=>'收货地址',
        ];
    }
    
    //获取订单编号
    public static function getOrderNO($type){
        $prefix="O";
        if($type==self::TYPE_AUCTION){
            $prefix="A";
        }
        if($type==self::TYPE_GUARANTEE){
            $prefix="G";
        }
        if($type==self::TYPE_MALL){
            $prefix="M";
        }
        if($type==self::TYPE_LOTTERY){
            $prefix="L";
        }    
        if($type==self::TYPE_MERCHANT_GUARANTEE){
            $prefix="MG";
        }    
        
        return $prefix.date("YmdHis").rand(100, 999);      
    }
    
    public  function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
