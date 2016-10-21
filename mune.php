<?php 
	header("index.php");
    $appid = "wxdf937732890770c1";  
    $secret = "d4624c36b6795d1d99dcf0547af5443d";  
    $menu_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
    header("Location:".$menu_url);
	$access_token = $_GET['access_token'];
	echo $access_token;
?>