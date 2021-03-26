<?php
/**
 * *从CMS 系统获取运单信息
 * 、2017-11-08
 */
//require("/home/formcmsgettracknumber/DB.php");
//require_once '/home/formcmsgettracknumber/get_val.php';

function crontpost(){

	$url="https://www.tracknumber.cn/apiorder/localpostnumber";
	        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	return true;	
}

crontpost();

?>