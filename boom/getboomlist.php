<?php
/**
 * 获取公司赔率 列表
 */
function cront(){
        $url="http://tracknumber.cn/boom/index/getboomlist";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        $response = curl_exec($ch);
        curl_close($ch);
        exit;
}
cront();
?>
