<?php
/**
 * 数据库
 * Created by wzb.
 * User: wzb
 * time:2017/11/08
 */
function cront(){
        $url="https://tracknumber.cn/apiorder/Postorderstatustoerp/PostDataToErp";
        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        $response = curl_exec($ch);
        $data = json_encode($response);
        //write_log('syncorder',$data);
        curl_close($ch);
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
