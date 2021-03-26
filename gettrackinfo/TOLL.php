<?php
/**
 * *从CMS 系统获取运单信息
 * 、2017-11-08
 */
require("/home/formcmsgettracknumber/DB.php");
require_once '/home/formcmsgettracknumber/get_val.php';
//日期
$endtime = date('Y-m-d');
$begintime=date('Y-m-d',strtotime('-7 day',strtotime("$endtime")));
//定义店铺信息
$shop_name_id_arr = array();
$shop_name_id_arr['MSMall'] = 'mall001';
$shop_name_id_arr['ry_shop'] = 'mall002';
$shop_name_id_arr['OXeye'] = 'mall003';
//$shop_name_id_arr[intou_shop] = 'mall003';


foreach($shop_name_id_arr as $k=>$v){
	$shop_name_id ="$v";
	$shop_name = "$k";
	get_info($shop_name_id,$shop_name,$begintime,$endtime,$cookie_file,$db);
}

// 获取并处理结果写入数据库
function get_info($shop_name_id,$shop_name,$begintime,$endtime,$cookie_file,$db){
		$post_data = array();
        $post_data['orderid'] = '';
        $post_data['shopid'] = "$shop_name_id";
        $post_data['selforderstatus'] = '';
        $post_data['trackingnumber'] = '';
		$post_data['ifprint'] = '';
		$post_data['ifoutstore'] = '';
		$post_data['expresscompany'] = '';
		$post_data['ifstatesuccess'] = '';
		$post_data['ordernumber'] = '';
		$post_data['ifrqueriedapply'] = '';
		$post_data['isPhone'] = '';
		$post_data['ordersource'] = '';
		$post_data['buyersignerfullname'] = '';
		$post_data['begintime'] = "$begintime";
		$post_data['endtime'] = "$endtime";
		$post_data['cursor'] = '0';
		$post_data['offset'] = '1500';
$data = request_data($post_data);//post数据
log_in($cookie_file);//登录
$get_result = get_content($data,$cookie_file);//获取信息
$total_num = $get_result['total'];//获取总数量
$code = $get_result['code'];//获取登录状态
$total_info = $get_result['orderList'];//获取到的所有信息

	foreach ($total_info as $k=>$singer_info){
		/******抓取信息*********/
		 $expresscompany = $singer_info['expresscompany'];//快递公司
		 $ordernumber = !empty($singer_info['ordernumber'])?$singer_info['ordernumber']:null;//编号
		 $ifprint = $singer_info['ifprint'];//打印状态 2 已打印 0 不可打印
		  $ifoutstore = $singer_info['ifoutstore'];//出库状态 1出 0未出库 ifsendout
		  $printresultnote = !empty($singer_info['printresultnote'])?$singer_info['printresultnote']:null;//打印结果
		  //$ordernumber = $singer_info['ordernumber'];//运单号   ifoutstore 是否出库
		  $orderid = $singer_info['orderid'];//订单号
		  /******处理异常*******/
		  if("$expresscompany" == "DHL"){
			  $trackingnumber_str = $singer_info['newtrackingnumber'];
			  $trackingnumber = str_replace(array("\r\n", "\r", "\n"," "),'',$trackingnumber_str);
		  }else{
			  $trackingnumber_str = $singer_info['trackingnumber'];
			  $trackingnumber = str_replace(array("\r\n", "\r", "\n"," "),'',$trackingnumber_str);
		  }
		  $updatetime = date('Y-m-d H:i:s');
		  /***记录信息****/
		if(!empty($trackingnumber)){
	
			$arrData = array(
					'ShipperCode' => $expresscompany,
					'trackingnumber' => $trackingnumber,
					'track_updatetime' => $updatetime,
					'isTrackinfofinshed' => 0,//一旦检测为新单号，则改变状态为0
					'posttracknumber_status' => 0 //默认为0
				);
			// 此处做为判断单号已经存在，不要重复推送
		$checktracknumber = checktracknumber($trackingnumber,$db);
		//print_r($checktracknumber);
		if($checktracknumber == 0){
			$rest = update($table='dlz_attribute_all_id',$arrData,$filed='increment_id',$condition=$orderid,$db);

		}
		  
		}
		/*******状态插入******/
		if(!empty($ordernumber)){
				$Datast = array(
					'ordernumber' => $ordernumber,
					'ifprint' => $ifprint,
					'ifoutstore' => $ifoutstore, //默认为0
					'printresultnote' => $printresultnote,
				);
				$checkordernumber = checkordernumber($ordernumber,$db);
			if($checkordernumber == 0){
				update($table='dlz_attribute_all_id',$arrData=$Datast,$filed='increment_id',$condition=$orderid,$db);

			}
			$checkstatus = checkstatus($ordernumber,$db);

			if($checkstatus == 1){
				$Datasts = array(
					'ifprint' => $ifprint,
					'ifoutstore' => $ifoutstore, //默认为0
					'printresultnote' => $printresultnote,
				);
				update($table='dlz_attribute_all_id',$arrData=$Datasts,$filed='increment_id',$condition=$orderid,$db);

			}

		}



	}
}

function crontpost(){

	$url="http://www.ruiyuhair.cn:8089/apiorder/localpostnumber";
	        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	return true;	
}

function crontgettrack(){

	$url="http://www.ruiyuhair.cn:8089/gettrackinginfo/gettracknumber";
	        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	return true;	
}




$msg = crontpost();
$trackmsg = crontgettrack();
//print_r($msg);

?>