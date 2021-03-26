<?php
/*
*@param 20170510
*@param 查询卖家每日服务分信息
*/
require 'public_get_access_token.php';
function gettoken($access_token){
		$url = 'http://gw.api.alibaba.com:80/openapi';//1688开放平台使用gw.open.1688.com域名
		$appKey = '7152106';
		$appSecret = 'tLHw5WWZ5Gpt'; //签名串
		$apiInfo = 'param2/1/aliexpress.open/api.findOrderListQuery/' . $appKey;//此处请用具体api进行替换	
		//配置参数，请用apiInfo对应的api参数进行替换
		$code_arr = array(
		'page' => '1',
		'pageSize' => '50',
		'access_token' => $access_token
		);
		$aliParams = array();
		foreach ($code_arr as $key => $val) {
		$aliParams[] = $key . $val;
		}
		// print_r($aliParams);die;
		sort($aliParams);
		$sign_str = join('', $aliParams);
		$sign_str = $apiInfo . $sign_str; //拼接参数
		$code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $appSecret, true))); //签名算法
		$file_get_url = "{$url}/{$apiInfo}?access_token={$access_token}&_aop_signature={$code_sign}";
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_HEADER,0); 
		curl_setopt($curl,CURLOPT_URL,$file_get_url);
		$result = curl_exec($curl);
		$token = json_decode($result,true);
		$result = $token['result'];
		$success = $token['success'];
		curl_close($curl);
		if($success == 'true'){
			return $result;
		}else{
			$faildresult = array();
			$faildresult['faild'] = $token;
			return $faildresult;
		}
}

$shop_name_id_arr = array();
$shop_name_id_arr['beyo_shop'] = 'cn1001768592';
$shop_name_id_arr['ry_shop'] = 'cn1001222963';
$shop_name_id_arr['gl_shop'] = 'cn1501614413';
$shop_name_id_arr['Gossip_shop'] = 'cn1501573277';
$shop_name_id_arr['pop_shop'] = 'cn1500243488';
$shop_name_id_arr['KissQ_shop'] = 'cn1500246221';
$shop_name_id_arr['ACE_shop'] = 'cn1510392753';
$shop_name_id_arr['RXY_shop'] = 'cn1500243523';
foreach($shop_name_id_arr as $k=>$v){
		$shopid ="$v";

$access_token =  check_access_token($shopid,$cookie_file,$shop_name_id_arr);
$getServiceScoreInfo = gettoken($access_token['access_token']);
//卖家店铺每日服务分相关信息
print_r($getServiceScoreInfo);
}
?>