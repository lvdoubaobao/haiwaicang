<?php
echo "test";
$token = "https://gw.api.alibaba.com/openapi/http/1/system.oauth2/getToken/9380888?";
    function request_data($shop_name_id,$begintime,$endtime) {
	    $post_data = array();
        $post_data['grant_type'] = 'authorization_code';
        $post_data['need_refresh_token'] = "true";
        $post_data['client_id'] = '9380888';
        $post_data['client_secret'] = 'vnktG8KgLI';
		$post_data['redirect_uri'] = '';
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
	
	
	
	$curl2 = curl_init();
	curl_setopt($curl2, CURLOPT_URL, $url);
	curl_setopt($curl2, CURLOPT_HEADER, 0);//设置header
	curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl2, CURLOPT_POST, 1); 
	curl_setopt($curl2, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl2,CURLOPT_CONNECTTIMEOUT, $timeout); 
	curl_setopt($curl2, CURLOPT_FOLLOWLOCATION,1);
	$rand_num = rand(1,5);
	sleep($rand_num);
	$content = curl_exec($curl2);
	curl_close($curl2);
	$arr = json_decode($content,true);
?>