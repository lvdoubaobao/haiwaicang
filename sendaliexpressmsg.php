<?php
/**
 * *DHL
 * 2018  03  06
 */
require("/home/formcmsgettracknumber/commonfunc.php");
function sendmsg(){
	$url="https://tracknumber.cn/aliexpress/sendmsg";
	        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}
$rms = sendmsg();
