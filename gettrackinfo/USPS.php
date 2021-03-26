<?php
/**
 * *USPS
 * 2018  03  06
 */
require("/home/formcmsgettracknumber/commonfunc.php");
function cronsups(){
	$url="https://tracknumber.cn/gettrackinginfo/getusps";
	        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

$rms = cronsups();
write_log($filename='getusps',$data=$rms);
