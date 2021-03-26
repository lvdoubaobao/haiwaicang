<?php
/**
* 201711.11
*/
class OMS{


function Piliangchaxuntracknumber($Data){
            $Url = "http://openapi.4px.com/api/service/woms/order/getDeliveryOrderList?customerId=548771&token=9605303a949df4d1b7e1be77d63f56d5&language=en_US";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$Url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
               // 'Accept:application/json',
            ));
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result, TRUE);
            // print_r($result);
            return $result;
    }

function chaxunexpresscompany($Data){
            $Url = "http://openapi.4px.com/api/service/woms/order/getOrderCarrier?customerId=548771&token=9605303a949df4d1b7e1be77d63f56d5&language=en_US";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$Url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
               // 'Accept:application/json',
            ));
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result, TRUE);
            // print_r($result);
            return $result;
    }

}