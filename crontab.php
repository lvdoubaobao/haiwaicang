<?php
/**
 * 数据库
 * Created by wzb.
 * User: wzb
 * time:2017/11/08
 */

        $url="https://tracknumber.cn/apiorder/localpostnumber";
        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
       // print_r($response);
        curl_close($ch);
        exit;

?>