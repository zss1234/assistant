<?php 
//scope=snsapi_userinfo实例
$appid='wxdf937732890770c1';
$redirect_uri = urlencode ( 'http://1.zss27149.applinzi.com/getUserInfo.php' );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
header("Location:".$url);
?>