<?php
require_once('/home/formcmsgettracknumber/oms/omsinc.php');
require("/home/formcmsgettracknumber/DB.php");

/**
* 从oms 系统中获取单号
* by：wzb
* time：2017-11-11
*/
//此处有个问题：如果海外仓出现异常，那么同步日期需要修改，否则同步不到信息
$endtime = date('Y-m-dH:i:s');
$begintime = date('Y-m-d',strtotime('-7 day',strtotime("$endtime")));
$oms = new OMS();
$Data = array(
    'orderCode' => '',
    'carrierCode' => '',
    'warehouseCode' => '',
    'referenceCode' => '',
    'createDateBegin' => $begintime,
    'createDateEnd' => $endtime,
    'shipmentDateBegin' => '',
    'shipmentDateEnd' => '',
    'orderStatusCode' => 'S'
    );
$trackmunber = $oms->Piliangchaxuntracknumber($Data);
if (!$trackmunber['data']) {
    exit;
}
     print_r($trackmunber);
foreach ($trackmunber['data'] as $key => $value) {

    $yundan = array();
    $yundan['orderCode'] = $value['orderCode'];//OMS单号
    $yundan['orderid'] = substr($value['referenceCode'],0,10);//CMS单号
    $yundan['house'] = $value['warehouseCode'];//仓库
/**********/

    $yundan['express_code'] = $value['carrierCode'];//运送的渠道代号
    $yundan['createTime'] = $value['createTime'];//创建时间
    //$company = "select * from dlz_haiwaicang_company where productCode='$yundan[express_code]'";
    $code_house = $value['carrierCode'].'_'.$value['warehouseCode'];
    $company = "select * from dlz_haiwaicang_company where code_house='$code_house'";
    $company_q = $db->query($company);
    /**********************处理快递公司**************************/
    $number = $company_q->rowCount();
    sleep(3);
    echo "++++++++++++++++++++++++++++++++++\n";
    if($number > 0){
        //如果存在则
        echo "仓库$yundan[house]已存在";
    }else{
        $expresscompanyarr = array(
                'warehouseCode' => $yundan['house']
                );
    $kuaidigongsi = $oms->chaxunexpresscompany($expresscompanyarr);
    //print_r($kuaidigongsi);
    if($kuaidigongsi){
        foreach ($kuaidigongsi['data'] as $keye => $valuee) {
            $ex_arr = array();

            $ex_arr['house'] = $valuee['warehouseCode'];//快递shibie代码
            $ex_arr['carrierCode'] = $valuee['carrierCode'];//快递公司代码
            $ex_arr['carrierName'] = $valuee['carrierName'];//快递公司mingzi
            $ex_arr['productCode'] = $valuee['productCode'];//快递shibie代码
            $ex_arr['carrierEName'] = $valuee['carrierEName'];// 
            $ex_arr['code_house'] = $valuee['productCode'].'_'.$valuee['warehouseCode'];//索引
            Insert($arrData=$ex_arr,$table='dlz_haiwaicang_company',$db);
        }
    }
    }// 处理快递公司结束
/***********************处理运单号********************************/

    $row = $company_q->fetch();
    $yundan['expresscompany'] = $row['carrierCode'];

    if(strpos("$yundan[expresscompany]",'UPS') !== false){
                $ShipperCode = 'UPS';
    }elseif(strpos("$yundan[expresscompany]",'USPS') !== false){
                $ShipperCode = 'USPS';
    }else{
        $ShipperCode = $row['carrierEName'];
    }
    $yundan['shippingNumber'] = $value['shippingNumber'];//原单号
    $yundan['shippingTime'] = $value['shippingTime'];//原单号 创建时间
    $yundan['status'] = $value['status'];//状态S(已发货--仓库已经发货)
    //记录sku
    echo "$yundan[orderid]---$yundan[shippingNumber]---\n";
    // 此处做为判断单号已经存在，不要重复推送
    $trcData = array(
                    'ShipperCode' => $ShipperCode,
                    'trackingnumber' => $yundan['shippingNumber'],
                    'posttracknumber_status' => 0 //默认为0
    );
    $checktracknumber = checktracknumber($trackingnumber=$yundan['shippingNumber'],$db);
        //print_r($checktracknumber);
     if($checktracknumber == 0){
        $rest = update($table='dlz_attribute_all_id',$arrData=$trcData,$filed='increment_id',$condition=$yundan['orderid'],$db);

    }



    Insert($arrData=$yundan,$table='dlz_haiwaicang_yundan',$db);
    $lsOrderDetails = $value['lsOrderDetails'];//订单产品；列表
    foreach ($lsOrderDetails as $keys => $values) {

        $arrData = array();
        $arrData['sku'] = $values['sku'];
        $arrData['skuId'] = $values['skuId'];
        echo "$arrData[sku]---$arrData[skuId]---\n";
        Insert($arrData,$table='dlz_haiwaicang_sku',$db);
    }
}
 echo "//////////////////////////End///////////////////////////////\n";
 exit;
