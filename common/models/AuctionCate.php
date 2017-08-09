<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "auction_cate".
 *
 * @property integer $cateid
 * @property integer $referid
 * @property string $name
 * @property string $desc
 * @property string $path
 * @property string $photo
 * @property string $created_at
 * @property string $updated_at
 */
class AuctionCate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_cate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referid', 'created_at', 'updated_at'], 'integer'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 256],
            [['path', 'photo'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cateid' => 'Cateid',
            'referid' => 'Referid',
            'name' => '分类名称',
            'desc' => '分类描述',
            'path' => 'Path',
            'photo' => 'Photo',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(AdminUser::className(), ['user_guid'=>'user_guid']);
    }
}
