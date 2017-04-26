<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_goods".
 *
 * @property integer $id
 * @property string $goods_guid
 * @property string $name
 * @property string $desc
 * @property string $path
 * @property string $photo
 * @property double $price
 * @property integer $number
 * @property integer $is_sale
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 */
class MallGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','is_sale','end_time','price','number'], 'required'],   
            [['desc'], 'string'],
            [['price'], 'number'],
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
            'price' => '商品价格',
            'number' => '商品库存',
            'count_view'=>'浏览次数',
            'count_sales'=>'销售数量',
            'is_sale' => '是否上架',
            'end_time' => '自动下架时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
