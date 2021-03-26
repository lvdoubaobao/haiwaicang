<?php
/**
 * 数据库
 * Created by wzb.
 * User: wzb
 * time:2017/08/15
 */

echo "setp: db \n";

$db = new PDO("mysql:host=localhost;dbname=dlz_order","root","rymht321123!@");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query("set character set 'UTF8'");

//插入
function Getinsert($arrData,$table,$db,$sync_data) {
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
    $result = $db->exec($strSql);
    if( $result > 0 && $sync_data !== "0") {
      sync_info($sync_data,$db);
    }
    if($result > 0){
            $return_code = '0';
           return $return_code;
    }
    
}

//更新
function update($table,$arrData,$filed,$condition,$db) {
    $flag = 1;
    foreach($arrData as $key => $value) {
        if($flag) {
            $data = "$key = '$value'";
            $flag = 0;
        } else {
          $data .= ",$key = '$value'";
        }
    }
    $strSql = "update $table set $data where $filed = '$condition'";
    echo "$strSql \n";
    $result = $db->exec($strSql);
    if($result) {
        return 1;
    }else{
      return 0;
    }
    
}


function checktracknumber($trackingnumber,$db){
       $xc_sql = "select id from dlz_attribute_all_id where trackingnumber='$trackingnumber'";
       echo "$xc_sql \n";
       $num = $db->query($xc_sql);
       $number = $num->rowCount();
       if($number > 0){
          return 1;
       }else{
          return 0; //echo "不存在";
       }



}


function checkordernumber($ordernumber,$db){
       $xc_sql = "select id from dlz_attribute_all_id where ordernumber='$ordernumber'";
       echo "$xc_sql \n";
       $num = $db->query($xc_sql);
       $number = $num->rowCount();
       if($number > 0){
          return 1;
       }else{
          return 0; //echo "不存在";
       }

}
//检查是否出库，未出库则 更新 部分状态
function checkstatus($ordernumber,$db){
       $xc_sql = "select id from dlz_attribute_all_id where ordernumber='$ordernumber' and ifoutstore='0'";
       echo "$xc_sql \n";
       $num = $db->query($xc_sql);
       $number = $num->rowCount();
       if($number > 0){
          return 1;
       }else{
          return 0; //echo "不存在";
       }

}




function sync_info($sync_data,$db){
  $updatetime = $sync_data['updatetime'];
  $new_add_num = $sync_data['new_add_num'];
  $pagenumber = $sync_data['pagenumber'];
  $store_name = $sync_data['store_name'];
  $totalnums = $sync_data['lasttotalnums'];
  $fornum_totnum = $sync_data['fornum_totnum'];
  $strSql = "insert into dlz_sync_ordernums (updatetime,new_add_num,pagenumber,store_name,lasttotalnums,fornum_totnum) values ('$updatetime','$new_add_num','$pagenumber','$store_name','$totalnums','$fornum_totnum') ON DUPLICATE KEY UPDATE updatetime='$updatetime',new_add_num='$new_add_num',pagenumber='$pagenumber',store_name='$store_name',lasttotalnums='$totalnums',fornum_totnum='$fornum_totnum'";
    echo "$strSql\n";
     $result = $db->exec($strSql);
    if($result > 0){
            $return_code = '1';
           return $return_code;
    }
}

function Insert($arrData,$table,$db) {
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
    $result = $db->exec($strSql);
    if($result > 0){
            $return_code = '0';
           return $return_code;
    }
    
}


