<?php
/**
 * 数据库
 * Created by wzb.
 * User: wzb
 * time:2017/11/08
 */
function cront(){
       $storeidarr = array('cn1001222963','cn1510392753','cn1500243523');//cn1501614413 cn1500243488 'cn1500243523   ,cn1500243488'cn1501614413','cn1501573277',','cn1001768592''cn1500246221',
	   $nowdate = date('Y-m-d H:i:s');
   	 $tpyTime = date("Y-m-d H:i:s",strtotime("-1 day",strtotime($nowdate)));
 
        foreach ($storeidarr as $key => $valuekey) {
        $url="http://ruiyuhair.cn:8089/apiorder/Getaliexpressorder/orderlist?storeid=$valuekey&poststatus=2";
	     print_r($url);
        //$url="http://www.ruiyuhair.cn:8089/apiorder/options/updatatoken";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT,60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $data = json_encode($response);
        write_log('getaliexpressorder',$data);
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
