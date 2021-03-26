<?php
header("Content-Type:text/html;charset=utf-8");
require("/home/formcmsgettracknumber/aliexpress/DB.php");


function Getappinfo($result,$rydb){
$num=0;
while ($values = $result->fetch(PDO::FETCH_ASSOC)) {
			$num++;
			$storeInfo = array();
			$storeInfo['province'] = $values['province'];
			$storeInfo['country'] = $values['country'];
			$storeInfo['city'] = $values['city'];
			$storeInfo['companyadressext'] = $values['companyadressext'];
			$storeInfo['companyName'] = $values['companyName'];
			$storeInfo['Id'] = $values['Id'];
			$storeInfo['appName'] = $values['appName'];
			$storeInfo['appKey'] = $values['appKey'];
			$storeInfo['appSecret'] = $values['appSecret'];
			$storeInfo['callCount'] = $values['callCount'];
			$storeInfo['redirectUrl'] = $values['redirectUrl'];
			$storeInfo['createTime'] = $values['createTime'];
			$storeInfo['refreshToken'] = $values['refreshToken'];
			$storeInfo['refreshTokenTimeout'] = $values['refreshTokenTimeout'];
			$storeInfo['accessToken'] = $values['accessToken'];
			$storeInfo['getrequest'] = $values['getrequest'];
			$storeInfo['postrequest'] = $values['postrequest'];
			$storeInfo['companyAdress'] = $values['companyAdress'];
			$storeInfo['zip'] = $values['zip'];
			$storeInfo['phoneNumber'] = $values['phoneNumber'];
			$storeInfo['minimum'] = $values['minimum'];
			$storeInfo['updcycle'] = $values['updcycle'];
			$storeInfo['shopName'] = $values['shopName'];
			$storeInfo['managerName'] = $values['managerName'];
			$storeInfo['apppassword'] = $values['apppassword'];
			$storeInfo['shopId'] = $values['shopId'];
			$storeInfo['identificode'] = $values['identificode'];
			$storeInfo['updatetime'] = $values['updatetime'];
			$storeInfo['shortName'] = $values['shortName'];
			$storeInfo['calcurequiredtime'] = $values['calcurequiredtime'];
			$storeInfo['isSyn'] = $values['isSyn'];
			$storeInfo['remindetime'] = $values['remindetime'];
			$storeInfo['sendmsgorderno'] = $values['sendmsgorderno'];
			$storeInfo['orderbegintime'] = $values['orderbegintime'];
			$storeInfo['msgearlydays'] = $values['msgearlydays'];
			$storeInfo['orderNo'] = $values['orderNo'];
			$storeInfo['brandId'] = $values['brandId'];
			$storeInfo['brandName'] = $values['brandName'];
			$storeInfo['trackingAPIID'] = $values['trackingAPIID'];
			$storeInfo['trackingAPIKey'] = $values['trackingAPIKey'];
			Insert($arrData=$storeInfo,$table='dlz_appinfo',$rydb);

}
}



function Getstoreinfo($result,$rydb){
$num=0;
while ($values = $result->fetch(PDO::FETCH_ASSOC)) {
			$num++;
			$storeInfo = array();
			$storeInfo['name'] = $values['name'];
			$storeInfo['simpleName'] = $values['simpleName'];
			$storeInfo['type'] = $values['type'];
			$storeInfo['sequnceNo'] = $values['sequnceNo'];
			$storeInfo['shopID'] = $values['shopID'];
			$storeInfo['appKey'] = $values['appKey'];
			$storeInfo['appSecret'] = $values['appSecret'];
			$storeInfo['refreshToken'] = $values['refreshToken'];
			$storeInfo['accessToken'] = $values['accessToken'];
			$storeInfo['timeoutDate'] = $values['timeoutDate'];
			$storeInfo['managerName'] = $values['managerName'];
			$storeInfo['phoneNumber'] = $values['phoneNumber'];
			Insert($arrData=$storeInfo,$table='dlz_storeinformation',$rydb);
		}
}		


//获取授权信息
$result = queryinfo($db,$table='storeinformation');
Getstoreinfo($result,$rydb)

//获取店铺信息
//$result = queryinfo($db,$table='appinfo');
//Getappinfo($result,$rydb)
?>