<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auction_bid_rec".
 *
 * @property integer $id
 * @property string $goods_guid
 * @property string $user_guid
 * @property double $amount
 * @property string $created_at
 */
class AuctionBidRec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_bid_rec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['created_at'], 'integer'],
            [['goods_guid', 'user_guid'], 'string', 'max' => 48]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_guid' => '商品guid',
            'user_guid' => 'User Guid',
            'amount' => '出价',
            'created_at' => '出价时间',
        ];
    }
    
    public function getAuctionGoods(){
        return $this->hasOne(AuctionGoods::className(), ['goods_guid'=>'goods_guid']);
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
