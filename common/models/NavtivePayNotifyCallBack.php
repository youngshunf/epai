<?php
namespace common\models;

use yii;
use yii\db\Exception;

require_once dirname(__DIR__)."/WxpayAPI/lib/WxPay.Api.php";
require_once dirname(__DIR__).'/WxpayAPI/lib/WxPay.Notify.php';


class NavtivePayNotifyCallBack extends \WxPayNotify
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
                if($order->type==Order::TYPE_GUARANTEE ||$order->type==Order::TYPE_MERCHANT_GUARANTEE){
                    $order->status=3;
                }else{
                   $order->status=1;
                }
        $order->pay_time=time();
        $order->updated_at=time();
        if(!$order->save()) throw new Exception("订单更新失败!");
        
        if($order->type==Order::TYPE_LOTTERY){
            $lotteryGoods=LotteryGoods::findOne(['goods_guid'=>$order->biz_guid]);
            
            for( $i=0;$i<$order->number;$i++){
            $lotteryRec=new LotteryRec();
            $lotteryRec->goods_guid=$order->biz_guid;
            $lotteryRec->order_guid=$order->order_guid;
            $lotteryRec->user_guid=$order->user_guid;
            $lotteryRec->lottery_code=LotteryRec::getLotteryCode();
            $lotteryRec->ip=CommonUtil::getClientIp();
            $lotteryRec->created_at=time();
            if(!$lotteryRec->save() ) throw new Exception();
            }
            
            $lotteryGoods->count_lottery+=$order->number;
            if($lotteryGoods->count_lottery>=$lotteryGoods->price){
              
               $lotteryGoods->status=2;
              $lotteryAward=LotteryRec::findOne(['goods_guid'=>$order->biz_guid,'is_award'=>1]);
                if(empty($lotteryAward)){//开始抽奖
                $lotteryLib=LotteryRec::findAll(['goods_guid'=>$order->biz_guid]);
                $lottery_id=$lotteryLib[rand(0, intval(count($lotteryLib)-1))]['id'];
                $lottery=LotteryRec::findOne($lottery_id);
                $lottery->is_award=1;
                $lottery->award_time=time();
                if(!$lottery->save()) throw new Exception();
                }
            }
            
            $lotteryGoods->updated_at=time();
            if(!$lotteryGoods->save()) throw new Exception();
            
          
            
        }elseif ($order->type==Order::TYPE_MALL){
            $goods=MallGoods::findOne(['goods_guid'=>$order->biz_guid]);
            $goods->count_sales +=$order->number;
            $goods->updated_at=time();
            if(!$goods->save()) throw new Exception();
        }elseif ($order->type==Order::TYPE_AUCTION){
               $goods=AuctionGoods::findOne(['goods_guid'=>$order->biz_guid]);
               $goods->deal_user=$order->user_guid;
               $goods->status=3;
               $goods->deal_price=$order->amount;
               $goods->updated_at=time();
               if(!$goods->save()) throw new Exception();
        }elseif ($order->type==Order::TYPE_GUARANTEE){
                $guaranteeFee=GuaranteeFee::findOne(['fee_guid'=>$order->biz_guid]);
                $guaranteeFee->is_pay=1;
                $guaranteeFee->status=1;
                $guaranteeFee->updated_at=time();
                if(!$guaranteeFee->save()) throw new Exception();
            
                $user=User::findOne(['user_guid'=>$order->user_guid]);
            
                $user->role_id=$guaranteeFee->user_role;
                if($user->role_id==3){
                    $user->guarantee=1;
                }
                $user->updated_at=time();
                if(!$user->save()) throw new Exception();
           
        } elseif ($order->type==Order::TYPE_MERCHANT_GUARANTEE){
                $merchantGuarantee=MerchantGuarantee::findOne(['fee_guid'=>$order->biz_guid]);
                $merchantGuarantee->status=1;
                $merchantGuarantee->order_guid=$order->order_guid;
                $merchantGuarantee->updated_at=time();
                if(!$merchantGuarantee->save()) throw new Exception();
            
                $user=User::findOne(['user_guid'=>$order->user_guid]);
                $user->merchant_role=2;
                $user->updated_at=time();
                if(!$user->save()) throw new Exception();
           
        }          
                
                    $trans->commit();
                    \Log::DEBUG("success updated order");
                }catch(Exception $e){
                    $trans->rollBack();
                    \Log::DEBUG("fail updated order");
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

