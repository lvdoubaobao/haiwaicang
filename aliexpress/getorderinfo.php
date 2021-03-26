<?php
header("Content-Type:text/html;charset=utf-8");
require("/home/formcmsgettracknumber/aliexpress/DB.php");
function getorderinfo($result,$rydb){
	$num=0;
	while ($values = $result->fetch(PDO::FETCH_ASSOC)) {
			$num++;
			$orderdata = array();
			$trackinfo = array();
			//只捡取单号信息
			$trackinfo['orderid'] = addslashes($values['orderid']);
			$trackinfo['shopid'] = addslashes($values['shopid']);
			if(strpos(trim(strtolower($values['expresscompany'])),'dhl') !== false){
				$tracknumber = addslashes($values['newtrackingNumber']);
			}else{
				$tracknumber = addslashes($values['trackingNumber']);
			}
			$trackinfo['tracknumber'] = $tracknumber;
			$trackinfo['expresscompany'] = addslashes($values['expresscompany']);
			//捡取全部信息
			foreach ($values as $key => $value) {
				$orderdata[$key] = addslashes($value);
			}
			//插入运单信息
			Insert($arrData=$trackinfo,$table='dlz_tracking_numberinfo',$rydb);
			//插入订单信息
			Insert($arrData=$orderdata,$table='dlz_aliexpress_orderinfo',$rydb);		
		}

}

//获取订单信息
$result = getinfo($db,$table='orderinfo');
getorderinfo($result,$rydb)

//获取店铺信息
//$result = queryinfo($db,$table='appinfo');
//Getappinfo($result,$rydb)
?>