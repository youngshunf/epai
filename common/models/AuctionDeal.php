<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auction_deal".
 *
 * @property integer $id
 * @property string $goods_guid
 * @property string $user_guid
 * @property double $deal_price
 * @property integer $is_buy
 * @property string $created_at
 * @property string $updated_at
 */
class AuctionDeal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_deal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deal_price'], 'number'],
            [['is_buy', 'created_at', 'updated_at'], 'integer'],
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
            'goods_guid' => 'Goods Guid',
            'user_guid' => 'User Guid',
            'deal_price' => 'Deal Price',
            'is_buy' => '是否购买',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
