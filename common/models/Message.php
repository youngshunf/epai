<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $from_user
 * @property string $to_user
 * @property string $content
 * @property integer $type
 * @property integer $refer_id
 * @property integer $is_read
 * @property string $created_at
 */
class Message extends \yii\db\ActiveRecord
{
    const SYS=0;
    const USER=1;
    const MERCHANT=2;
    const ADMINUSER="eb3a4eba-8a87-11e4-8ef2-201a065c562d";
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         /*    [['content'], 'string'],
            [['type', 'refer_id', 'is_read', 'created_at'], 'integer'],
            [['from_user', 'to_user'], 'string', 'max' => 48] */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_user' => 'From User',
            'to_user' => 'To User',
            'content' => '消息内容',
            'type' => '消息内容',
            'refer_id' => '回复id',
            'is_read' => '是否已读',
            'created_at' => '发送时间',
        ];
    }
    
    public function send($fromUser=NULL,$toUser,$content,$type,$refer_id=0){
        $message=new Message();
        if($fromUser==null){
            $message->from_user=Message::ADMINUSER;
        }else{
            $message->from_user=$fromUser;
        }
       
        $message->to_user=$toUser;
        $message->type=$type;
        $message->refer_id=$refer_id;
        $message->content=$content;
        $message->created_at=time();
        if($message->save()){
            return true;
        }
        
        return false;
    }
}
