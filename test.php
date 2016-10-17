<?php
    $mysqli = new mysqli('localhost', 'root', 'root', 'assistant');
    mysqli_query($mysqli, "set names 'UTF8'");
    if (mysqli_connect_errno()) {
        die('数据库连接失败');
    }
    //报错级别
    error_reporting(0);
    // 设置字符集
    header('Content-Type: text/html; charset=utf-8');
    // 设置时区为北京时间
    date_default_timezone_set("Asia/Shanghai");
    //获取当前时间   例如20161011
    $time = time();
    $date =  date("Ymd",$time);

?>