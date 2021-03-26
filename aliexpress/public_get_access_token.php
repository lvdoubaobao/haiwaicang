<?php
/*
@param 20170210
@param 获取access_token,过期后重新获取

*/
//导入文件

require_once '/var/www/html/ry/db/databases.php';
include 'getallshoptoken.php';

//get param
//public $shopid = $_REQUEST['shop_order'];cn1001768592
//$shopid = "cn1001768592";
function check_access_token($shopid,$cookie_file,$shop_name_id_arr){
			//mysql 部分，先测试，之后封装到db api 文件
			$sql = "select * from ry_shop where shopid = '$shopid'";
			$result = mysqli_query($sql);
			$row = mysqli_fetch_array($result);
			//解析数据
			$access_token = $row['accesstoken'];
			$shortname = $row['shortname'];
			/*
			计算时间
			*/
			$updatetime_str = strtotime($row['updatetime']);
			$end_time=strtotime('+8 hour',"$updatetime_str");
			$now_time =strtotime(date('Y-m-d H:i:s'));
			if ("$now_time" < "$end_time"){
				$ret_conf = array();
				$ret_conf['shopid'] = $shopid;
				$ret_conf['access_token'] = $access_token;
				return $ret_conf;
			}else{
				$token = new Token();
				$token->Get_access_token($cookie_file,$shop_name_id_arr);
				return false;
			}
}


