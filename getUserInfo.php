<?php 
$appid = "wxdf937732890770c1";  
$secret = "d4624c36b6795d1d99dcf0547af5443d";  
$code = $_GET["code"];
//第一步:取得openid
$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
echo $oauth2Url;
$oauth2 = getJson($oauth2Url);
//第二步:根据全局access_token和openid查询用户信息  
$access_token = $oauth2["access_token"];
$openid = $oauth2['openid'];  
// $access_token = "9F_K9CcZU35ICCJT0NsnaP3t9g03tjOIRNGqeJjWw-6Ikr_rsDGZ8mxWxRDGSFh6rVDXFq1jXnfRDqV3PqtB8oM1XN7vY1EBuIDvbS0isr7sgU2K7sreM_N8BnO3IbE6EGGaABAKMM";
// $openid = "";
// $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
// $userinfo = getJson($get_user_info_url);
 
//打印用户信息
  // print_r($userinfo);
 
function getJson($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}
?>