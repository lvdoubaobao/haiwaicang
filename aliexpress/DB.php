<?php
/**
 * 数据库
 * Created by wzb.
 * User: wzb
 * time:2017/08/15
 */

echo "setp: db \n";

$db = new PDO("mysql:host=117.158.101.212;dbname=ryterp","root",'ruiYUOxeyeLynn9876!');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query("set character set 'UTF8'");



function queryinfo($db,$table){
       $xc_sql = "select * from $table";
       $result = $db->query($xc_sql);
       //$result = $num->fetch();
       $result->execute();
       if($result){
          return $result;
       }else{
          return 0; //echo "不存在";
       }
}


function getinfo($db,$table){
    //日期
    $endtime = date('Y-m-d');
    $begintime=date('Y-m-d',strtotime('-3 day',strtotime("$endtime")));
    $sql = "select * from $table where syntime>='$begintime' and expresscompany is NOT NULL";
    $result = $db->query($sql);
    if($result){
          return $result;
       }else{
          return 0; //echo "不存在";
       }
}


$rydb = new PDO("mysql:host=localhost;dbname=dlz_order","root","rymht321123!@");
$rydb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$rydb->query("set character set 'UTF8'");

//插入
function Insert($arrData,$table,$rydb) {
    $name = $values = '';
    $flag = $flagV = 1;
    $true = is_array( current($arrData) );//判断是否一次插入多条数据
    if($true) {
        //构建插入多条数据的sql语句
        foreach($arrData as $arr) {
            $values .= $flag ? '(' : ',(';
            foreach($arr as $key => $value) {
                if($flagV) {
                    if($flag) $name .= "$key";
                    $values .= "'$value'";
                    $flagV = 0;
                } else {
                    if($flag) $name .= ",$key";
                    $values .= ",'$value'";
                }
            }
            $values .= ') ';
            $flag = 0;
            $flagV = 1;
        }




    } else {
        //构建插入单条数据的sql语句
        foreach($arrData as $key => $value) {
            if($flagV) {
                $name = "$key";
                $values = "('$value'";
                $flagV = 0;
            } else {
                $name .= ",$key";
                $values .= ",'$value'";
            }
        }
        $values .= ") ";
       $flagV2 = 1;
         foreach($arrData as $key => $value) {
            if($flagV2) {
                $name = "$key";
                $values_du = "$key='$value'";
                $flagV2 = 0;
            } else {
                $name .= ",$key";
                $values_du .= ",$key='$value'";
            }
        }
    }
     
    $strSql = "insert into $table ($name) values $values ON DUPLICATE KEY UPDATE $values_du";
    echo "***$strSql***\n";
    $result = $rydb->exec($strSql);
    if($result > 0){
            $return_code = '0';
           return $return_code;
    }
    
}