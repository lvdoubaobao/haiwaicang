<?php
/**
 * 数据库
 * Created by wzb.
 * User: wzb  cn1501573277
 * time:2017/11/08
 */
function cront(){
      $storeidarr = array('cn1001222963','cn1510392753','cn1500243523','cn1001768592','cn1501614413','cn1500246221','cn1500243488','cn1501573277');
       foreach ($storeidarr as $key => $value) {
         $storeid = $value;
          $url="https://tracknumber.cn/apiorder/Posttoerp/PostDataToErp?storeid=$storeid";
          //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_TIMEOUT,360);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($ch);
          $data = json_encode($response);
          write_log('syncorder'.$storeid,$data);
          curl_close($ch);
      }
        exit;
}

function write_log($filename,$data){
        $time = date('YmdHis');
        //设置路径目录信息
        $url = '/home/rylog/api/'.$time.$filename.'.log';
        $dir_name=dirname($url);
          //目录不存在就创建
          if(!file_exists($dir_name))
          {
            //iconv防止中文名乱码
           $res = mkdir(iconv("UTF-8", "GBK", $dir_name),0777,true);
          }
          $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建       
        fwrite($fp,var_export($data,true)."\r\n");//写入文件
        fclose($fp);//关闭资源通道
  }
cront();
