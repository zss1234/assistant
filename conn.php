<?php
//使用mysqli方式连接数据库
$mysqli = new mysqli('localhost', 'root', 'root', 'assistant');
mysqli_query($mysqli, "set names 'UTF8'");
if (mysqli_connect_errno()) {
    die('数据库连接失败');
}
?>