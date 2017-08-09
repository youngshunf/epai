<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auction_agent_bid".
 *
 * @property integer $id
 * @property string $goods_guid
 * @property string $user_guid
 * @property double $top_price
 * @property string $created_at
 * @property string $updated_at
 */
class AuctionAgentBid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_agent_bid';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['top_price'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
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
            'top_price' => '最高出价',
            'created_at' => '代理出价时间',
            'updated_at' => '修改时间',
        ];
    }
}
