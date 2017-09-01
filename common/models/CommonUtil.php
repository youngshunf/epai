<?php
	namespace common\models;
	use Yii;
use yii\helpers\Json;
use yii\db\Exception;
  
 
				
	class CommonUtil{

	    const GUARANTEE_FEE	=200;	//拍卖保证金
	    const MERCHANT_FEE=200;	//高级卖家保证金

		//生成uuid
		public static function createUuid(){			
			$connection = Yii::$app->db;
			$sql = "select uuid()";
			$command=$connection->createCommand($sql);
			$uuid=$command->queryAll();
		
			foreach ($uuid as $v){
				foreach ($v as $vv){
					return $vv;
				}
			}
		}
		
		//获取当前4S信息
		public static function getRoleId($val){
			$arr=[
			    'MVP'=>1,
			    '商圈经理'=>2,
			    '总监'=>3,
			    '其他'=>9
			];
			return $arr[$val];
		}
		
		public static function getLoginUser(){
			
			return !empty(yii::$app->user->identity->nick)?yii::$app->user->identity->nick:yii::$app->user->identity->mobile;
		}
		
		public static function cutHtml($str,$len=100){
		    return mb_substr(strip_tags($str), 0,$len,'utf-8').'...';
		}
			
		public static function getLastWeek(){
	       $current_date=date('w',time());
	       $last_week_end=date('Y-m-d ',time()-$current_date*24*3600);
	       $last_week_start=date('Y-m-d ',time()-(6+$current_date)*24*3600);
	       $last_week_start=date('Y-m-d H:i:s',strtotime($last_week_start.'00:00:00'));
	       $last_week_end=date('Y-m-d H:i:s',strtotime($last_week_end.'23:59:59'));	      
	       return array($last_week_start,$last_week_end);
           
		}
		
		public static function getWeekTime($first=1){
		    $date=date('Y-m-d');  //当前日期
		  //  $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期

		    $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6

		    $now_start=date('Y-m-d ',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
	
		    $now_end=date('Y-m-d H:i:s',strtotime("$now_start +6 days".'23:59:59'));  //本周结束日期
	
		    $last_start=date('Y-m-d H:i:s',strtotime("$now_start - 7 days".'00:00:00'));  //上周开始日期
		
		    $last_end=date('Y-m-d H:i:s',strtotime("$now_start - 1 days".'23:59:59'));  //上周结束日期
		    $now_start=date("Y-m-d H:i:s",strtotime($now_start.'00:00:00'));
		    return['now_week'=>[
		        'now_start'=>$now_start,
		        'now_end'=>$now_end
		    ],
		        'last_week'=>[
		            'last_start'=>$last_start,
		            'last_end'=>$last_end
		        ]
		    ];
		    
		}
		public static  function LogMsg($msg){
		    return date('Y-m-d H:i:s').':'.$msg.'\n';
		}
		
		public static function getMonthDay($month)
		{
		    $current_month=date('Y-m-01', time());
			$firstday = date('Y-m-01', strtotime("$current_month $month month"));
			$lastday = date('Y-m-d', strtotime("$firstday +1 month"));
			return array($firstday, $lastday);
		}
		
		public static function getUserMonth(){
			$firstday=time();
			$lastday=strtotime("+1months",$firstday);
			return array($firstday, $lastday);
		}
		
		public static function getOrderNum($type,$userid){
			return $type.date("YmdHis",time()).'-'.$userid;
		}
		
	
		
		public static function getthemonth()
		{
			$firstday = date('Y-m-01', time());
			$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
			return array($firstday, $lastday);
		}
	
        public static  function  getCirclePermissionValue($permissonString){
            $arr=[
                'public'=>0,
                'friends'=>1,
                'private'=>2
            ];
            return $arr[$permissonString];
        }

		
		public static function getDescByValue($tableName,$fieldName,$fieldValue){
			return Yii::$app->params[$tableName.'.'.$fieldName.'.'.$fieldValue];
		}
		
		
	   //通过IP获取地理位置
		public static function GetIpLookup($ip = ''){
		    if(empty($ip)){
		        $ip = $this::getClientIp();
		    }
		    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
		    if(empty($res)){ return false; }
		    $jsonMatches = array();
		    preg_match('#\{.+?\}#', $res, $jsonMatches);
		    if(!isset($jsonMatches[0])){ return false; }
		    $json = json_decode($jsonMatches[0], true);
		    if(isset($json['ret']) && $json['ret'] == 1){
		        $json['ip'] = $ip;
		        unset($json['ret']);
		    }else{
		        return false;
		    }
		    return $json;
		}
		
		
		//格式化时间
		public static function fomatTime($insert_time){
		    return empty($insert_time)?"":date("Y-m-d H:i:s ",$insert_time);
		}
				
		public static function fomatDate($insert_time){
		    return empty($insert_time)?"":date("Y-m-d ",$insert_time);
		}
		
		public static function fomatHours($insert_time){
			return empty($insert_time)?"":date("Y-m-d H:i",$insert_time);
		}
		
		public static function getClientIp(){
			$IPaddress='';
			
			if (isset($_SERVER)){
			
				if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
					$IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];			
				} else if (isset($_SERVER["HTTP_CLIENT_IP"])){
					$IPaddress = $_SERVER["HTTP_CLIENT_IP"];			
				} else {			
					$IPaddress = $_SERVER["REMOTE_ADDR"];		
				}
			}else{			
				if (getenv("HTTP_X_FORWARDED_FOR")){			
					$IPaddress = getenv("HTTP_X_FORWARDED_FOR");
				} else if (getenv("HTTP_CLIENT_IP")) {			
					$IPaddress = getenv("HTTP_CLIENT_IP");			
				} else {			
					$IPaddress = getenv("REMOTE_ADDR");			
				}			
			}
			
			return $IPaddress;
		}
        public  static  function createMaintenanceNum($id){
            return "M".date("YmdHis")."-".$id;
        }
        public  static  function createRoadsideNum($id){
            return "R".date("YmdHis")."-".$id;
        }
			
		
		//循环删除文件夹及里面的内容
		public static function deldir($dir) {
			$handle=opendir($dir);
			while(false !== ($item=readdir($handle))){
				$arr[]=$item;
				if($item != "." && $item != ".."){
					if(is_dir("$dir/$item")){
						$this::deldir("$dir/$item");
					}else{
						unlink("$dir/$item");
					}
				}
			}
			closedir($handle);
			if(rmdir($dir)){
				return true;
			}else{
				return false;
			}
		}
		
		//获取自然周的开始时间和结束时间
		public static function getWeekDate($year,$weeknum){
			$firstdayofyear=mktime(0,0,0,1,1,$year);
			$firstweekday=date('N',$firstdayofyear);
			$firstweenum=date('W',$firstdayofyear);
			if($firstweenum==1){
				$day=(1-($firstweekday-1))+7*($weeknum-1);
				$startdate=mktime(0,0,0,1,$day,$year);
				$enddate=mktime(0,0,0,1,$day+7,$year);
			}else{
				$day=(9-$firstweekday)+7*($weeknum-1);
				$startdate=mktime(0,0,0,1,$day,$year);
				$enddate=mktime(0,0,0,1,$day+7,$year);
			}
		
			return array($startdate,$enddate);
		}
	
		
		/*
		 * 获取离用户最近的4s店
		 */
		public static function getNearShop($loc){
			
			$lat = $loc['lat']*3600*256;
			$lng = $loc['lng']*3600*256;			
		
			$lnglat = $lng." ".$lat;
		
			$cmdStr = "
			SELECT *,ST_Distance(lnglat, ST_GeomFromText('POINT($lnglat)', 26910))/256 dist
			FROM fours_info
			WHERE ST_DWithin(
			lnglat,
			ST_GeomFromText('POINT($lnglat)', 26910),
			1000000000
			)  
			ORDER BY dist ASC
			LIMIT 10
			;
			";			
			$geoDbConnection = \Yii::$app->db;
			$command = $geoDbConnection->createCommand($cmdStr);
			$nearShopList = $command->queryAll();
		
            $nearShop=array();
			foreach ($nearShopList as $k=>$v){		
				$nearShop[$k+1]['fours_guid']=$v["fours_guid"];
				$nearShop[$k+1]['address']=$v["address"];
				$nearShop[$k+1]['mobile']=$v["mobile"];
				$distance=sprintf("%.2f",($v['dist']*30)/1000);
				$nearShop[$k+1]['distance']=$distance."km";
				$nearShop[$k+1]['text']=$v['company'].'(距离'.$distance.'公里)';
			}
		    return  $nearShop;
		}
		

		
		
		
		public static function  Log($message){
		    $filePath=yii::$app->basePath."/runtime/log.txt";
		    $fp=fopen($filePath, "a");		    		    
		    $msg=date("Y-m-d H:i:s")."|".$message;
		    fwrite($fp, $msg);
		    fclose($fp);		    		    
		}
		public static function getGeomLnglat($lng,$lat){
		    $connect=yii::$app->db;
             $lnglat = ($lng*3600*256)." ".($lat*3600*256);
		        $cmdStr="select ST_GeomFromText('POINT($lnglat)', 26910)";
		        $command=$connect->createCommand($cmdStr);
		        $lnglat= $command->queryAll();
		        
	         return $lnglat[0]['st_geomfromtext'];
		}
		

		
		/**
		 * 调用接口失败返回数据
		 * @param string $data
		 * @return string
		 * @author youngshunf
		 */
		public static  function error($errorCode){
		    header("Content-Type: application/json");
		    $error=[
		        'resultCode'=>0,
		        'result'=>'error',
	           'errcode'=>$errorCode,
                'errmsg'=>yii::$app->params[$errorCode]		        
		    ];
		    
		    return Json::encode($error);
		}
		
		/**
		 * 调用接口成功返回数据
		 * @param json $data
		 * @return string
		 * @author youngshunf
		 */
		public static function success($data){
		    header("Content-Type: application/json");
		    $result=[
		        'resultCode'=>1,
		        'result'=>'success',
		        'data'=>$data
		    ];
		    return Json::encode($result);
		}
		
		/**
		 * 获取距离当前时间的时分秒
		 * @param string $btime
		 * @param string $etime
		 * @return string
		 * @author youngshunf
		 */
		public static function getTime($btime, $etime = null) {
		    if ($etime == null)
		        $etime = time();
		    if ($btime < $etime) {
		        $stime = $btime;
		        $endtime = $etime;
		    } else {
		        $stime = $etime;
		        $endtime = $btime;
		    }
		    $timec = $endtime - $stime;
		    $days = intval($timec / 86400);
		    $rtime = $timec % 86400;
		    $hours = intval($rtime / 3600);
		    $rtime = $rtime % 3600;
		    $mins = intval($rtime / 60);
		    $secs = $rtime % 60;
		      if($days>=5){
		       return date('Y-m-d H:i:s',$btime);
		    }  
		    if ($days >= 1) {
		        return $days . ' 天前';
		    }
		    if ($hours >= 1) {
		        return $hours . ' 小时前';
		    }
		
		    if ($mins >= 1) {
		        return $mins . ' 分钟前';
		    }
		    if ($secs >= 1) {
		        return $secs . ' 秒前';
		    }
		}
		
		/**
		 * 多维数组排序
		 * @param array $multi_array
		 * @param array $sort_key
		 * @param string $sort
		 * @return boolean|array
		 * @author youngshunf
		 */
		public static function  multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){ 
                if(!is_array($multi_array)){ 
                    return false;
                }
                foreach ($multi_array as $row_array){ 
                    if(!is_array($row_array)){ 
                        return false;
                    }
                    $key_array[] = $row_array[$sort_key];                 
                } 
             
                array_multisort($key_array,$sort,$multi_array); 
                return $multi_array; 
     } 
		
     /**
      * 将base64编码转为图片格式存储
      * @param string $imgData
      * @param string $filePath
      * @param string $fileName
      * @return string|boolean
      */
    public static   function base64ToImg($imgData,$filePath,$fileName){
         if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $result)){
             $type = $result[2];
             $new_file =$filePath.$fileName.'.'.$type;
             if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imgData)))){
                 return $fileName.'.'.$type;
             }
         }     
         return false;
     }
     
     public static function truncateMobile($mobile){
         return $mobile[0].$mobile[1].$mobile[2]."****".$mobile[7].$mobile[8].$mobile[9].$mobile[10];
     }
     
     public static function getRandomMobile(){
         $d=['3','5','7','8'];
         $mobile='1'.$d[rand(0,3)].rand(100000000, 999999999);
         return $mobile;
     }
     
     public static function checkAllAuction(){
         $now=time();
         AuctionGoods::updateAll(['status'=>1],"$now>start_time and $now <end_time and status=0" );
//          AuctionRound::updateAll(['status'=>1],"$now>start_time and $now <end_time" );
         $auctionGoods=AuctionGoods::find()->andWhere(" $now >end_time and status = 1 ")->all();
         if(empty($auctionGoods)){
             return true;
         }
         $trans=yii::$app->db->beginTransaction();
         try{
         foreach ($auctionGoods as $k=>$v){
             $auctionRec=AuctionBidRec::find()->andWhere(['goods_guid'=>$v['goods_guid'],'is_leading'=>1])->one();
             if(empty($auctionRec)){
                 AuctionGoods::updateAll(['status'=>99],['goods_guid'=>$v['goods_guid']]);
             }else{
                 AuctionGoods::updateAll(['status'=>2,'deal_user'=>$auctionRec->user_guid,'deal_price'=>$v['current_price']],['goods_guid'=>$v['goods_guid']]);
                 $auctionRec->is_deal=1;
                 if(!$auctionRec->save()) throw new Exception('更新拍卖纪录失败!');
                 $order=Order::find()->andWhere(['user_guid'=>$auctionRec->user_guid,'biz_guid'=>$v->goods_guid])->one();
                 if(empty($order)){
                 $order=new Order();
                 $order->user_guid=$auctionRec->user_guid;
                 $order->order_guid=CommonUtil::createUuid();
                 $order->orderno=Order::getOrderNO(Order::TYPE_AUCTION);
                 $order->type=Order::TYPE_AUCTION;
                 $order->goods_name=$v->name;
                 $order->total_amount=$v['current_price'];
                 $order->amount=$v['current_price'];
                 $address=Address::findOne(['user_guid'=>$auctionRec->user_guid,'is_default'=>1]);
                 if(!empty($address)){
                     $order->address_id=$address->id;
                     $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
                 }
                 $order->number=1;
                 $order->biz_guid=$v->goods_guid;
                 $order->created_at=time();
                 if(!$order->save()){
                     throw new Exception('自动生成订单失败!');
                 }
                 self::SendTemplateMessage($order->user_guid, $v->id);
                 }
                 $message=new Message();
                 $url=yii::$app->urlManager->createAbsoluteUrl(['auction/view','id'=>$v['id']]);
                 $content="您好!您参与的拍卖-<span class='red'>".$v['name']."</span>-已结束,恭喜您已成交。请点击下面的按钮进行购买
                     <a class='btn btn-success' href='$url' >立即购买</a>";
                 $message->send(null, $auctionRec->user_guid, $content, Message::SYS);
             }
         }
         $trans->commit();
         }catch (Exception $e){
             $trans->rollBack();
             return false;
         }
     }
     
     public static  function actionAutoConfirm() {
     
         $now=time();
         $i=0;
         foreach (Order::find()->andWhere(['status'=>2])->each(10) as $order){
             $sevenDay=3600*24*7;//3天
             if(time()-$order->sent_time>=$sevenDay){
                 $order->status=3;
                 $order->save();
                 $i++;
             }
         }
        
     }
     
     public static  function SendTemplateMessage($user_guid,$goodsid){
          
         if(empty($user_guid)){
             return false;
         }
         $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
         $user=User::findOne(['user_guid'=>$user_guid]);
         $goods=AuctionGoods::findOne($goodsid);
         if($user->role_id==0){
             return false;
         }
         $data=[];
         $data['first']=[
             "value"=>'您参与的拍卖已结束,您已中拍，请尽快购买!',
             "color"=>"#173177"
         ];
         $data['keyword1']=[
             "value"=>$goods->name,
             "color"=>"#173177"
         ];
         $data['keyword2']=[
             "value"=>$goods->current_price,
             "color"=>"#173177"
         ];
         $data['keyword3']=[
             "value"=>$goods->count_auction,
             "color"=>"#173177"
         ];
         $data['keyword4']=[
             "value"=>'拍卖结束',
             "color"=>"#173177"
         ];
         $data['keyword5']=[
             "value"=>CommonUtil::fomatTime($goods->end_time),
             "color"=>"#173177"
         ];
         $result=false;
         $finalData=[
             "touser"=>$user->openid,
             "template_id"=>'RJL21kj3WHFNaj4bWaPjNupB3m0wAEdhcQITKiz9A2Y',
             "url"=>'http://wechat.1paibao.net/auction/view?id='.$goodsid,
             "topcolor"=>"#FF0000",
             "data"=>$data
         ];
         $res=$sendModel->send_template_message($finalData);
          
         if($res['errmsg']=='ok'){
             $result=true;
         }
         return $result;
     }
     
     public static  function SendOnlineMessage($user_guid,$roundid,$type){
         
         if(empty($user_guid)){
             return false;
         }
         $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
         $user=User::findOne(['user_guid'=>$user_guid]);
         $round=AuctionRound::findOne($roundid);
         $count=AuctionGoods::find()->andWhere(['roundid'=>$roundid])->count();
         $name=$round->name;
         if($user->role_id==0){
             return false;
         }
         $data=[];
         $title='';
         if($type==1){
             $title='亲，小火文玩最新专场拍卖全新上线!';
         }elseif ($type==2){
             $title='亲，专场拍卖正在进行中，我们约吗？';
         }elseif ($type==3){
             $title='亲，拍卖马上开始结束，藏品在呼唤您哦！';
         }
         $data['first']=[
             "value"=>$title,
             "color"=>"#173177"
         ];
         $data['keyword1']=[
             "value"=>$count . '件',
             "color"=>"#173177"
         ];
         $data['keyword2']=[
             "value"=>0 ,
             "color"=>"#173177"
         ];
         $data['keyword3']=[
             "value"=>$round->name,
             "color"=>"#173177"
         ];
         $data['keyword4']=[
             "value"=>CommonUtil::fomatTime($round->end_time),
             "color"=>"#173177"
         ];
         $data['remark']=[
             "value"=>'点击进入拍卖专场 >>',
             "color"=>"#173177"
         ];
         $result=false;
         $finalData=[
             "touser"=>$user->openid,
             "template_id"=>'8qeY18Z3OgxOpzhFiqa2duV4R91IvCl4JtUjfIsuil4',
             "url"=>'http://wechat.1paibao.net/auction/round-view?id='.$roundid,
             "topcolor"=>"#FF0000",
             "data"=>$data
         ];
         $res=$sendModel->send_template_message($finalData);
         
         if($res['errmsg']=='ok'){
             $result=true;
         }
         return $result;
     }
     
		
	}
	
	
?>