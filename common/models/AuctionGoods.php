<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "auction_goods".
 *
 * @property integer $id
 * @property string $goods_guid
 * @property integer $cateid
 * @property string $name
 * @property string $desc
 * @property double $start_price
 * @property double $delta_price
 * @property double $lowest_deal_price
 * @property double $current_price
 * @property integer $count_auction
 * @property integer $count_view
 * @property integer $count_collection
 * @property double $deal_price
 * @property string $deal_user
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 */
class AuctionGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_price', 'delta_price','lowest_deal_price','name','cateid','start_time','end_time'], 'required'],
            [['cateid','roundid'], 'integer'],
            [['desc'], 'string'],
            [['start_price', 'delta_price', 'lowest_deal_price', 'current_price', 'deal_price','fixed_price','sort'], 'number'],
            [['goods_guid', 'deal_user'], 'string', 'max' => 48],
            [['name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_guid' => '商品唯一ID',
            'cateid' => '分类',
            'roundid'=>'专场',
            'name' => '商品名称',
            'desc' => '商品描述',
            'start_price' => '起拍价格',
            'delta_price' => '加价幅度',
            'lowest_deal_price' => '最低成交价格',
            'current_price' => '当前价格',
            'count_auction' => '出价人数',
            'count_view' => '浏览次数',
            'count_collection' => '收藏次数',
            'deal_price' => '最终成交价格',
            'deal_user' => '成交用户',
            'fixed_price'=>'一口价',
            'start_time' => '起拍时间',
            'end_time' => '结束时间',
            'created_at' => 'Created At',
            'updated_at' => '更新时间',
            'sort'=>'排序(数字越大越靠前)'
        ];
    }
    
    public function getRound(){
        return $this->hasOne(AuctionRound::className(), ['id'=>'roundid']);
    }
    
    public function getUser(){
        return $this->hasOne(AdminUser::className(), ['user_guid'=>'user_guid']);
    }
    
    
    
    public function getMerchant(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
