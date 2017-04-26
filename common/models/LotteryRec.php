<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lottery_rec".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $goods_guid
 * @property string $lottery_code
 * @property integer $is_award
 * @property integer $is_end
 * @property string $created_at
 */
class LotteryRec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lottery_rec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           
            [[ 'is_award', 'is_end', 'created_at'], 'integer'],
            [['user_guid', 'goods_guid'], 'string', 'max' => 48],
            [['lottery_code'], 'string', 'max' => 255]
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
            'goods_guid' => 'Goods Guid',
            'lottery_code' => '抽奖码',
            'is_award' => '是否中奖',
            'is_end' => '是否结束',
            'created_at' => '创建时间',
        ];
    }
    
    public static function getLotteryCode(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $lotteryCode = $yCode[intval(date('Y')) - 2015] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $lotteryCode;
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
    
    public function getGoods(){
        return $this->hasOne(LotteryGoods::className(), ['goods_guid'=>'goods_guid']);
    }
}
