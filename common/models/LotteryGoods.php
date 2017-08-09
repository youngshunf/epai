<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lottery_goods".
 *
 * @property integer $id
 * @property string $goods_guid
 * @property string $name
 * @property string $desc
 * @property string $path
 * @property string $photo
 * @property double $price
 * @property integer $count_lottery
 * @property integer $count_view
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 */
class LotteryGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lottery_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','price','end_time'], 'required'],      
            [['desc'], 'string'],
            [['price'], 'integer'],
            [['goods_guid'], 'string', 'max' => 48],
            [['name'], 'string', 'max' => 255],
            [['path', 'photo'], 'string', 'max' => 128]
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
            'name' => '商品名称',
            'desc' => '商品描述',
            'path' => 'Path',
            'photo' => 'Photo',
            'price' => '商品价格(最大参与人数)',
            'count_lottery' => '参与人数',
            'count_view' => '浏览人数',
            'end_time' => '结束时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
