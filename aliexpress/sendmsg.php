<?php
require '/var/www/html/db/databases.php';
$sql = "select * from token where resource_owner = 'cn1510285035'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$access_token = $row['access_token'];

function gettoken($access_token){
        #根据code获取refresh_token、access_token 
        $url = 'http://gw.api.alibaba.com/openapi';
        $appKey = '9380888';
        $appSecret ='vnktG8KgLI';
		#生成签名
        $code_arr = array(
			'currentPage' => '1',
			'pageSize' => '20',
			'msgSources' => 'msgSources',
			'access_token' => '$access_token',
        );
        ksort($code_arr);
        $sign_str = '';
        foreach ($code_arr as $key=>$val){
            $sign_str .= $key . $val;
        }
        $code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $appSecret, true)));
        #拼接获取token的Url
        $getTokenUrl='http://gw.api.alibaba.com:80/openapi/param2/1/aliexpress.open/api.queryMsgRelationList/'.$appKey;
        $data="currentPage=1&pageSize=20&msgSources=order_msg&access_token=$access_token&_aop_signature=$code_sign";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getTokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
       #打印出返回结果
        $token = json_decode($result,true);
		print_r($token);
		$UpdateTime = date('Ymd H:i:s');
		/*$aliId = $token['aliId'];// aliId
		$sql = "update token set aliId='$aliId',resource_owner='$resource_owner',updatatime='$UpdateTime',expires_in='$expires_in',access_token='$access_token' where resource_owner='$resource_owner'";
		if(!empty($resource_owner)){
				$result=mysql_query($sql);
		if(! $result ){
				die('Could not delete data: ' . mysql_error());
			}else{
				echo "1";
			}
		}*/
        exit;
    }
	
	
	
gettoken($access_token);
$db->sql_close();
?>