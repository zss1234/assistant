<?php 
//scope=snsapi_userinfo实例
// $appid='wxdf937732890770c1';
// $redirect_uri = urlencode ( 'http://zss27149.applinzi.com/getUserInfo.php' );
// $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
// header("Location:".$url);

?>
<?php  
      
    if(isset($_SESSION['user'])){  
        print_r($_SESSION['user']);
    exit;
    }
    $APPID='wxdf937732890770c1';
    $REDIRECT_URI='http://zss27149.applinzi.com/getUserInfo.php';
    $scope='snsapi_base';
    //$scope='snsapi_userinfo';//需要授权
    $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
    
    header("Location:".$url);
    ?>