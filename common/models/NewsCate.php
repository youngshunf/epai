<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "news_cate".
 *
 * @property integer $cateid
 * @property string $name
 * @property string $desc
 * @property string $path
 * @property string $photo
 * @property string $created_at
 * @property string $updated_at
 */
class NewsCate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_cate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
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
            'name' => '分类名称',
            'desc' => '分类描述',
            'path' => 'Path',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(AdminUser::className(), ['user_guid'=>'user_guid']);
    }
    
}
