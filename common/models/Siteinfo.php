<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "siteinfo".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property string $created_at
 * @property string $updated_at
 */
class Siteinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'siteinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
     
         
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'type' => '0:联系我们;1-拍品征集',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
