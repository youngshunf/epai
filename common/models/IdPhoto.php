<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "id_photo".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $path
 * @property string $photo
 * @property integer $type
 * @property integer $auth_status
 * @property string $auth_remark
 * @property string $auth_user
 * @property string $created_at
 * @property string $updated_at
 */
class IdPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'id_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'type', 'auth_status', 'created_at', 'updated_at'], 'integer'],
            [['auth_remark'], 'string'],
            [['user_guid', 'auth_user'], 'string', 'max' => 48],
            [['path', 'photo'], 'string', 'max' => 64]
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
            'path' => 'Path',
            'photo' => 'Photo',
            'type' => '证件类型',
            'auth_status' => '审核状态',
            'auth_remark' => '审核意见',
            'auth_user' => '审核用户',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
