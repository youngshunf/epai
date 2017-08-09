<?php
namespace common\models;

use yii;
use yii\db\Exception;

require_once dirname(__DIR__)."/WxpayAPI/lib/WxPay.Api.php";
require_once dirname(__DIR__).'/WxpayAPI/lib/WxPay.Notify.php';


class PayNotifyCallBack extends \WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        \Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            $orderno=$result['out_trade_no'];
            \Log::DEBUG($orderno."begin updated order");
            $order=Order::findOne(['orderno'=>$orderno]);

            if(!empty($order)){
                $trans=yii::$app->db->beginTransaction();
                try{
                    $order->is_pay=1;
                    $order->status=1;
                    $order->pay_time=time();
                    if(!$order->save()) throw  new Exception("update order error");
                    $trans->commit();
                    \Log::DEBUG("success updated order");
                }catch (Exception $e){
                   \Log::DEBUG("fail updated order");
                    $trans->rollBack();
                }

            }

            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
         
        \Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }

        \Log::DEBUG("begin updated rec");
        //保存交易记录
        $wxpayRec=new WxpayRec();
        $wxpayRec->appid=$data['appid'];
        $wxpayRec->attach=$data['attach'];
        $wxpayRec->bank_type=$data['bank_type'];
        $wxpayRec->cash_fee=$data['cash_fee'];
        $wxpayRec->fee_type=$data['fee_type'];
        $wxpayRec->is_subscribe=$data['is_subscribe'];
        $wxpayRec->mch_id=$data['mch_id'];
        $wxpayRec->nonce_str=$data['nonce_str'];
        $wxpayRec->openid=$data['openid'];
        $wxpayRec->out_trade_no=$data['out_trade_no'];
        $wxpayRec->result_code=$data['result_code'];
        $wxpayRec->return_code=$data['return_code'];
        $wxpayRec->sign=$data['sign'];
        $wxpayRec->time_end=$data['time_end'];
        $wxpayRec->total_fee=$data['total_fee'];
        $wxpayRec->trade_type=$data['trade_type'];
        $wxpayRec->transaction_id=$data['transaction_id'];
        $wxpayRec->created_at=time();
        if($wxpayRec->save()){
            \Log::DEBUG("success updated rec");
        }
        return true;
    }
}

