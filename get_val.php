<?php
//header("Content-Type:text/html;charset=utf-8");

//获取毫秒级时间戳
function getMillisecond() {
list($t1, $t2) = explode(' ', microtime());
return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}
$d = getMillisecond();

/*
 * 提交请求
*/


function log_in($cookie_file){
	$login_url = "http://rxyhair.com:8081/ryterp/user/login?loginName=mslynnmall&password=mslynn123!&vercode=";	
	//$cookie_file = dirname(__FILE__).'/cookie.txt';
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_HEADER,0); 
	curl_setopt($curl,CURLOPT_URL,$login_url);
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
	curl_exec($curl);
	curl_close($curl);
	
}
/**
 * [get_content 获取订单列表]
 * @param  [type] $data        [post数据]
 * @param  [type] $cookie_file [cookie]
 * @return [type]       return       [数组]
 */
function get_content($data,$cookie_file){
	$url = "http://rxyhair.com:8081/ryterp/orderinfo/list";
	$res = Postmethon($url,$data,$cookie_file);
	@unlink($cookie_file);
	return $res;
}

/**
 * [get_content 获取单个订单信息]
 * @param  [type] $data        [post数据]
 * @param  [type] $cookie_file [cookie]
 * @return [type]       return       [数组]
 */
function getSingerOrderId($data,$cookie_file){
	$url = "http://rxyhair.com:8081/ryterp/orderinfo/upd";
	$res = Postmethon($url,$data,$cookie_file);
	return $res;
}

function Updateorder($data,$cookie_file){
	$url = "http://rxyhair.com:8081/ryterp/orderinfo/update";
	$res = Postmethon($url,$data,$cookie_file);
	@unlink($cookie_file);
	return $res;
}


/**
 * Postmethon POst 方法 封装
 * @param string $url  地址
 * @param Arrary $data  Post 数据
 * @return Arrary  返回数据
 */

function Postmethon($url,$data,$cookie_file){
	$curl2 = curl_init();
	curl_setopt($curl2, CURLOPT_URL, $url);
	curl_setopt($curl2, CURLOPT_HEADER, 0);//设置header
	curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl2, CURLOPT_POST, 1); 
	curl_setopt($curl2, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl2, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl2, CURLOPT_FOLLOWLOCATION,1);
	$content = curl_exec($curl2);
	curl_close($curl2);
	$arr = json_decode($content,true);
	
	return $arr;
}




/**
     * 组装post url 数据格式
     *
     * @param array $post_data
     * @return String  $curlPost
     */
    function request_data($post_data) { 
        if (empty($post_data)) {
            return false;
        }
        
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);
        $curlPost = $post_data;
		return $curlPost;
    } 
$cookie_file = tempnam('./tmp','cookie');

