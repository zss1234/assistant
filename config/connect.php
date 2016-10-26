<?php
  header('Content-Type: text/html; charset=utf-8');
 
  $server='sqlserver'; //odbc系统连接池的名称    
  $username='sa';      //数据库的连接名     
  $password='';        //数据库连接密码
  $database='use SRQuery2016';     //选择操作的数据库
    
  $conn=odbc_connect($server,$username,$password);//odbc连接数据库
  $exec_datebase = odbc_exec($conn, $database);//odbc执行sql语句的方法

// 连接到sqlserver数据库
  if (!$conn) 
    die("Couldn't connect to SQL Server on $server");
//连接到当前使用的SRQuery2016数据库
  if (!$exec_datebase) 
    die("Couldn't connect to datebase");
  odbc_close($conn);
?>