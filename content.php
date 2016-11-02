<?php
    //报错级别
    error_reporting(0);
    // 设置字符集
    header('Content-Type: text/html; charset=utf-8');
    
    // 设置时区为北京时间
    date_default_timezone_set("Asia/Shanghai");
    //获取当前时间   例如2016-10-11  获取页面所选时间
    $time = time();
    $date =  date("Y-m-d",$time);
    // $date = "2016-09-26";
    // 获取页面上的用户应该是在session中保存的

    // 获取店铺名称，先判断该SRQuery数据库的SubStore表中name字段中是否存在该店铺名称
    $dp_name = $_GET['dp_name'];
    $dp_name = conver2gbk("测试");
    // 1.存在就直接查询2.不存在就要添加这个店铺名称
    // 问题分析：现在就是有两个数据库，并且需要对两个数据库进行操作
    // 引入基本数据库连接文件文件
    $date = $_GET['q'];
    // 转换数据库的编码格式
function conver2utf8($string){
    return iconv("gbk", "utf-8", $string);
}
function conver2gbk($string){
    return iconv("utf-8", "gbk", $string);
}
    include_once ("connect.php");
// 3.收款方式
    $skfs_sql = "select skfs from skjl where jzrq = '".$date."' and SubStore = '".$dp_name."' group by skfs";
    $skfs_exec = odbc_exec($conn, $skfs_sql);
    $skfs_sum = array();
    $skfs_num = array();
    $num = 0;
    $res1[] = array();
    while (@$skfs_row = odbc_fetch_array($skfs_exec)) {
        $num++;
        $res1[$num][skfs] = conver2utf8($skfs_row[skfs]);
        $skjl_sql = "select je from skjl where jzrq = '".$date."' and skfs = '".$skfs_row[skfs]."'";
        $skjl_exec = odbc_exec($conn, $skjl_sql);
        $skjl_sum = 0;
        $skjl_num = 0;
        while (@$skjl_row = odbc_fetch_array($skjl_exec)) {
            $skjl_sum += $skjl_row[je];
            $skjl_num++;
        }
        $skfs_sum[$num] = $skjl_sum;
        $skfs_num[$num] = $skjl_num;
        $res1[$num][flje] = $skfs_sum[$num];
        $res1[$num][ddsl] = $skfs_num[$num];
    }   
        $res1[$num+1][skfs] = "汇总";    
        $res1[$num+1][flje] = array_sum($skfs_sum);
        $res1[$num+1][ddsl] = array_sum($skfs_num);

// 时段汇总
    $time_num = 0;
    $sum_time = 0;//其实最主要的是上面的笔数 因为总金额就是当日的营业总金额营业总额根据不同查询方式是不会变化的
    for ($i=9; $i < 22; $i++) { 
        // echo $i."&nbsp;&nbsp;";
        $time_sql = "select ysje from ftfx where SUBSTRING(jzdt, 1, 11) = '".$date."' and SUBSTRING(jzdt, 12, 2) = '".$i."' and SubStore = '".$dp_name."'";
        $time_exec = odbc_exec($conn, $time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = odbc_fetch_array($time_exec)) {
            $time_arr[$time] = $time_row[ysje];
            $time++;
        }
        // echo array_sum($time_arr)."&nbsp;&nbsp;";//该时间段的销售总金额
        // echo count($time_arr);//该时间段的销售总笔数
        // echo "<br>";
        $time_num+=count($time_arr);
        $sum_time+=array_sum($time_arr);
    }
    // echo $time_num;//总笔数
    // echo $sum_time;//总金额
        $res[] = array();
        $res[0] = $res1;
        echo json_encode($res);

    exit;
?>
