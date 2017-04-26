<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "home_photo".
 *
 * @property integer $id
 * @property string $path
 * @property string $photo
 * @property string $url
 * @property string $title
 * @property string $desc
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 */
class HomePhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'home_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'created_at', 'updated_at','type'], 'integer'],
            [['path', 'photo'], 'string', 'max' => 128],
            [['url', 'title', 'desc'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'photo' => 'Photo',
            'url' => '跳转链接',
            'title' => '标题',
            'type'=>'类型',
            'desc' => 'Desc',
            'is_active' => '是否显示',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
