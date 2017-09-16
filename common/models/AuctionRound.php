<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "auction_round".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $name
 * @property string $desc
 * @property string $start_time
 * @property string $end_time
 * @property string $path
 * @property string $photo
 * @property string $source
 * @property string $created_at
 * @property string $updated_at
 */
class AuctionRound extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_round';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'name', 'desc'], 'required'],
            [['desc'], 'string'],
            [['created_at', 'updated_at','sort','seller_fee'], 'integer'],
            [['user_guid', 'path', 'photo'], 'string', 'max' => 64],
            [['name', 'source'], 'string', 'max' => 255]
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
            'name' => '专场名称',
            'desc' => '专场描述',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'path' => 'Path',
            'photo' => 'Photo',
            'source' => '提供方',
            'sort'=>'排序',
            'seller_fee'=>'买家佣金(%)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function getUser(){
        return $this->hasOne(AdminUser::className(), ['user_guid'=>'user_guid']);
    }
    
    public function getMerchant(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
