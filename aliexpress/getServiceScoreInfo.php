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
		$apiInfo = 'param2/1/aliexpress.open/alibaba.ae.seller.getServiceScoreInfo/' . $appKey;//此处请用具体api进行替换	
		//配置参数，请用apiInfo对应的api参数进行替换
		$code_arr = array(
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

/*
*@param : primOprPcateLv2Name primOprPcateLv2Id 类目id 名称
*@param : checkMordCnt 考核父订单数
*@param : statEndDate 服务分计算截止时间
*@param : statStartDate 服务分计算起始时间
*@param : indexDTO 服务指标信息
*@param : weightDTO 考核项权重信息
*@param : scoreDTO 服务得分信息
*@param : indexDTO 服务指标信息
*@param : industryAvgScoreDTO 行业平均得分
*@param : industryAvgIndexDTO 行业平均指标
*/
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
$primOprPcateLv2Name = $getServiceScoreInfo['primOprPcateLv2Name'];
$primOprPcateLv2Id = $getServiceScoreInfo['primOprPcateLv2Id'];
$serviceScoreInfoDTO = $getServiceScoreInfo['serviceScoreInfoDTO'];//arr
$statStartDate = $serviceScoreInfoDTO['statStartDate'];
$statEndDate = $serviceScoreInfoDTO['statEndDate'];
$checkMordCnt = $serviceScoreInfoDTO['checkMordCnt'];
$checkMordCnt1m = $serviceScoreInfoDTO['checkMordCnt1m'];
$checkMordCnt3m = $serviceScoreInfoDTO['checkMordCnt3m'];

//$indexDTO = $serviceScoreInfoDTO['indexDTO'];//arr
//$industryAvgIndexDTO = $serviceScoreInfoDTO['industryAvgIndexDTO'];//arr
//$scoreDTO = $serviceScoreInfoDTO['scoreDTO'];//arr
//$industryAvgScoreDTO = $serviceScoreInfoDTO['industryAvgScoreDTO'];//arr
//$weightDTO = $serviceScoreInfoDTO['weightDTO'];//arr
$updatetime = date('Y-m-d H:i:s');
$chectime = date('Ymd');
//print_r($getServiceScoreInfo['serviceScoreInfoDTO']['scoreDTO']);
$shopid01 = $access_token['shopid'];
//写入数据库部分 
$sql = "insert into getSS (primOprPcateLv2Name,primOprPcateLv2Id,updatetime,chectime,shopid) SELECT '$primOprPcateLv2Name','$primOprPcateLv2Id','$updatetime','$chectime','$shopid' FROM DUAL WHERE NOT EXISTS(SELECT chectime FROM getSS WHERE chectime='$chectime' and shopid='$shopid01')";
mysql_query($sql);
echo "$shopid ...";
$query_id = "select id from getSS where chectime='$chectime' and shopid='$shopid01'";
$query = mysql_query($query_id);
$row = mysql_fetch_array($query);
$masterid = $row['id'];
if(!empty($masterid)){

$sql_insert_getSS_serviceScoreInfoDTO = "insert into getSS_serviceScoreInfoDTO (id,statStartDate,statEndDate,checkMordCnt,checkMordCnt1m,checkMordCnt3m)
																		SELECT '$masterid','$statStartDate','$statEndDate','$checkMordCnt','$checkMordCnt1m','$checkMordCnt3m' FROM DUAL WHERE NOT EXISTS(SELECT id FROM getSS_serviceScoreInfoDTO WHERE id='$masterid')";
mysql_query($sql_insert_getSS_serviceScoreInfoDTO);

/*****************************************************************/
$indexDTO = $serviceScoreInfoDTO['indexDTO'];//arr
$snadIssueMordCnt = $indexDTO['snadIssueMordCnt'];
$logis48hSendGoodsRate = $indexDTO['logis48hSendGoodsRate'];
$buyNotSelRate = $indexDTO['buyNotSelRate'];
$nrIssueMordCnt = $indexDTO['nrIssueMordCnt'];
$nrDisclaimerIssueRate = $indexDTO['nrDisclaimerIssueRate'];
$dsrProdScore = $indexDTO['dsrProdScore'];
$nrDisclaimerIssueMordCnt = $indexDTO['nrDisclaimerIssueMordCnt'];
$dsrCommunicateScore = $indexDTO['dsrCommunicateScore'];
$dsrLogisScoreAftDisclaim = $indexDTO['dsrLogisScoreAftDisclaim'];
$snadDisclaimerIssueMordCnt = $indexDTO['snadDisclaimerIssueMordCnt'];
$snadDisclaimerIssueRate = $indexDTO['snadDisclaimerIssueRate'];

$sql_insert_getSS_indexDTO = "insert into getSS_indexDTO (id,snadIssueMordCnt,logis48hSendGoodsRate,buyNotSelRate,nrIssueMordCnt,nrDisclaimerIssueRate,dsrProdScore,nrDisclaimerIssueMordCnt,dsrCommunicateScore,dsrLogisScoreAftDisclaim,snadDisclaimerIssueMordCnt,snadDisclaimerIssueRate)
													SELECT '$masterid','$snadIssueMordCnt','$logis48hSendGoodsRate','$buyNotSelRate','$nrIssueMordCnt','$nrDisclaimerIssueRate','$dsrProdScore','$nrDisclaimerIssueMordCnt','$dsrCommunicateScore','$dsrLogisScoreAftDisclaim','$snadDisclaimerIssueMordCnt','$snadDisclaimerIssueRate' FROM DUAL WHERE NOT EXISTS(SELECT id FROM getSS_indexDTO WHERE id='$masterid')";
mysql_query($sql_insert_getSS_indexDTO);
/*****************************************************************/

$industryAvgIndexDTO = $serviceScoreInfoDTO['industryAvgIndexDTO'];//arr
$buyNotSelRate = $industryAvgIndexDTO['buyNotSelRate'];
$pcateFlag = $industryAvgIndexDTO['pcateFlag'];
$nrDisclaimerIssueRate = $industryAvgIndexDTO['nrDisclaimerIssueRate'];
$dsrProdScore = $industryAvgIndexDTO['dsrProdScore'];
$nrDisclaimerIssueRate = $industryAvgIndexDTO['nrDisclaimerIssueRate'];
$dsrProdScore = $industryAvgIndexDTO['dsrProdScore'];
$dsrCommunicateScore = $industryAvgIndexDTO['dsrCommunicateScore'];
$pcateId = $industryAvgIndexDTO['pcateId'];
$dsrLogisScoreAftDisclaim = $industryAvgIndexDTO['dsrLogisScoreAftDisclaim'];
$snadDisclaimerIssueRate = $industryAvgIndexDTO['snadDisclaimerIssueRate'];

$sql_insert_getSS_industryAvgIndexDTO = "insert into getSS_industryAvgIndexDTO (id,buyNotSelRate,pcateFlag,nrDisclaimerIssueRate,dsrProdScore,dsrCommunicateScore,pcateId,dsrLogisScoreAftDisclaim,snadDisclaimerIssueRate)
																		SELECT '$masterid','$buyNotSelRate','$pcateFlag','$nrDisclaimerIssueRate','$dsrProdScore','$dsrCommunicateScore','$pcateId','$dsrLogisScoreAftDisclaim','$snadDisclaimerIssueRate' FROM DUAL WHERE NOT EXISTS(SELECT id FROM getSS_industryAvgIndexDTO WHERE id='$masterid')";
//echo "$masterid','$buyNotSelRate','$pcateFlag','$nrDisclaimerIssueRate','$dsrProdScore','$nrDisclaimerIssueRate','$dsrProdScore','$dsrCommunicateScore','$pcateId','$dsrLogisScoreAftDisclaim','$snadDisclaimerIssueRate'";
mysql_query($sql_insert_getSS_industryAvgIndexDTO);
/*****************************************************************/
$industryAvgScoreDTO = $serviceScoreInfoDTO['industryAvgScoreDTO'];//arr
$pcateFlag = $industryAvgScoreDTO['pcateFlag'];
$totalScore = $industryAvgScoreDTO['totalScore'];
$dsrProdScore = $industryAvgScoreDTO['dsrProdScore'];
$buyNotSelScore = $industryAvgScoreDTO['buyNotSelScore'];
$dsrLogisScore = $industryAvgScoreDTO['dsrLogisScore'];
$nrIssueScore = $industryAvgScoreDTO['nrIssueScore'];
$dsrCommunicateScore = $industryAvgScoreDTO['dsrCommunicateScore'];
$pcateId = $industryAvgScoreDTO['pcateId'];
$snadIssueScore = $industryAvgScoreDTO['snadIssueScore'];
$sql_insert_getSS_industryAvgScoreDTO = "insert into getSS_industryAvgScoreDTO (id,pcateFlag,totalScore,dsrProdScore,buyNotSelScore,dsrLogisScore,nrIssueScore,dsrCommunicateScore,pcateId,snadIssueScore) 
																		SELECT '$masterid','$pcateFlag','$totalScore','$dsrProdScore','$buyNotSelScore','$dsrLogisScore','$nrIssueScore','$dsrCommunicateScore','$pcateId','$snadIssueScore' FROM DUAL WHERE NOT EXISTS(SELECT id FROM getSS_industryAvgScoreDTO WHERE id='$masterid')";
mysql_query($sql_insert_getSS_industryAvgScoreDTO);
/*****************************************************************/
$scoreDTO = $serviceScoreInfoDTO['scoreDTO'];//arr
$totalScore = $scoreDTO['totalScore'];
$dsrProdScore = $scoreDTO['dsrProdScore'];
$buyNotSelScore = $scoreDTO['buyNotSelScore'];
$dsrLogisScore = $scoreDTO['dsrLogisScore'];
$nrIssueScore = $scoreDTO['nrIssueScore'];
$dsrCommunicateScore = $scoreDTO['dsrCommunicateScore'];
$snadIssueScore = $scoreDTO['snadIssueScore'];

$sql_insert_getSS_scoreDTO = "insert into getSS_scoreDTO (id,totalScore,dsrProdScore,buyNotSelScore,dsrLogisScore,nrIssueScore,dsrCommunicateScore,snadIssueScore) 
												  SELECT '$masterid','$totalScore','$dsrProdScore','$buyNotSelScore','$dsrLogisScore','$nrIssueScore','$dsrCommunicateScore','$snadIssueScore' FROM DUAL WHERE NOT EXISTS(SELECT id FROM getSS_scoreDTO WHERE id='$masterid')";
mysql_query($sql_insert_getSS_scoreDTO);
/*****************************************************************/
$weightDTO = $serviceScoreInfoDTO['weightDTO'];//arr
$notSellWeight = $weightDTO['notSellWeight'];
$nrIssueWeight = $weightDTO['nrIssueWeight'];
$dsrCommunicatWeight = $weightDTO['dsrCommunicatWeight'];
$dsrGoodDescriptionWeight = $weightDTO['dsrGoodDescriptionWeight'];
$snadIssueWeight = $weightDTO['snadIssueWeight'];
$dsrLogisticsWeight = $weightDTO['dsrLogisticsWeight'];

$sql_insert_getSS_weightDTO = "insert into getSS_weightDTO (id,notSellWeight,nrIssueWeight,dsrCommunicatWeight,dsrGoodDescriptionWeight,snadIssueWeight,dsrLogisticsWeight) 
												  SELECT '$masterid','$notSellWeight','$nrIssueWeight','$dsrCommunicatWeight','$dsrGoodDescriptionWeight','$snadIssueWeight','$dsrLogisticsWeight' FROM DUAL WHERE NOT EXISTS(SELECT id FROM getSS_weightDTO WHERE id='$masterid')";
mysql_query($sql_insert_getSS_weightDTO);
	
}

}
?>