<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "news".
 *
 * @property integer $newsid
 * @property integer $cateid
 * @property string $user_guid
 * @property string $title
 * @property string $content
 * @property string $path
 * @property string $photo
 *  @property string $is_web
 *  @property string $is_wechat
 * @property string $created_at
 * @property string $updated_at
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'cateid', 'is_web','is_wechat'], 'required'],
            [['content'], 'string'],
            [['user_guid'], 'string', 'max' => 48],
            [['title'], 'string', 'max' => 255],
            [['path'], 'string', 'max' => 32],
            [['photo'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => 'Newsid',
            'cateid' => '分类id',
            'user_guid' => '发布者',
            'title' => '标题',
            'content' => '内容',
            'path' => 'Path',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(AdminUser::className(), ['user_guid'=>'user_guid']);
    }
    
    public function getCate(){
        return $this->hasOne(NewsCate::className(), ['cateid'=>'cateid']);
    }
}
