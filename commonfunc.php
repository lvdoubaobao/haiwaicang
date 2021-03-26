<?php
/**
 * [write_log description] 写入日志
 * @param  [type] $filename [文件名]
 * @param  [type] $data     [内容]
 * @return [type]           [description]
 */
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