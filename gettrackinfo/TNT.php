<?php
/**
 * *TNT
 * 2018  03  06
 */
require("/home/formcmsgettracknumber/commonfunc.php");
function crontnt(){
	$url="https://tracknumber.cn/gettrackinginfo/gettnt";
	        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}


$rms = crontnt();
write_log($filename='gettnt',$data=$rms);
