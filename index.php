<?php 
//scope=snsapi_userinfo实例
$appid='wx66e0b4d53dccd1ac';
$redirect_uri = urlencode ( 'http://zss27149.applinzi.com/getUserInfo.php' );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
header("Location:".$url);

?>