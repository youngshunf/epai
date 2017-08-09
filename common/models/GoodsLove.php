<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goods_love".
 *
 * @property integer $id
 * @property integer $goodsid
 * @property string $user_guid
 * @property integer $created_at
 */
class GoodsLove extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_love';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goodsid', 'created_at'], 'integer'],
            [['user_guid'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goodsid' => 'Goodsid',
            'user_guid' => 'User Guid',
            'created_at' => 'Created At',
        ];
    }
    
    public function getAuctionGoods() {
        return $this->hasOne(AuctionGoods::className(), ['id'=>'goodsid']);
    }
}
