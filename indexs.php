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
    $date = "2016-09-26";

    // 获取页面上的用户应该是在session中保存的

    // 获取店铺名称，先判断该SRQuery数据库的SubStore表中name字段中是否存在该店铺名称
    $dp_name = $_GET['dp_name'];
    $dp_name = conver2gbk("测试");
    // 1.存在就直接查询2.不存在就要添加这个店铺名称
    // 问题分析：现在就是有两个数据库，并且需要对两个数据库进行操作
    // 引入基本数据库连接文件文件

    // 转换数据库的编码格式
function conver2utf8($string){
    return iconv("gbk", "utf-8", $string);
}
function conver2gbk($string){
    return iconv("utf-8", "gbk", $string);
}
// 1.
    // 连接到店铺表，查询店铺名称是否存在
    include_once ("connects.php");
    $name_sql = "select * from SubStore where Name = '".$dp_name."'";
    $name_exec = odbc_exec($conn, $name_sql);
    // 如果没有查询到该记录，就像数据库中添加该店铺名称
    if(!odbc_num_rows($name_exec)){
        $insert_name = "insert into SubStore (Name,ConStr,IsCY,IsKF,IsXY) values ('".$dp_name."','null',0,0,0)";
        $insert_name_exec = odbc_exec($conn, $insert_name);
        odbc_close($conn);
    }
    // echo odbc_num_rows($name_exec);//输出记录数
    // $row = odbc_fetch_array($name_exec);
    // echo conver2utf8($row['Name']);//输出店铺的名称
?>
<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <title>老板小助手</title>
    	<link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/datedropper.css">
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/nav.js"></script>
        <script src="js/tab.js"></script>
        <script src="selectuser.js"></script>
        <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
        <script src="js/datedropper.min.js"></script>
    </head>
    <body>
        <!--《开始》页面整体背景，为了释放抽屉时点击空白处可以调用-->
        <div class="bgDiv"></div>
        <!--《结束》页面整体背景，为了释放抽屉时点击空白处可以调用-->
        <!--《开始》抽屉里面的内容，就是个人资料的内容-->
        <div class="leftNav">
            <div class="vip">
<?php 
    // $unionid = "oZcVLv2a_6Y7TkDS-wNpmANjAySs";
    // $user_sql = "SELECT * FROM user WHERE unionid = '".$unionid."'";  
    // $user_query = $mysqli->query($user_sql);
    // $user_row = $user_query->fetch_array();
    // echo $user_row[headimgurl];输出头像连接地址
?>
                <img src="<?php echo $user_row[headimgurl]?>">
                <p>免费版用户<p>
                <button>开通/续费VIP</button>
            </div>
            <div class="mendian">
                <ul>
                    <li><img src="img/mendian.png">已关注门店</li>
                    <li><img src="img/guanzhu.png">关注新门店</li>
                </ul>
            </div>
        </div>
        <!--《结束》抽屉里面的内容，就是个人资料的内容-->
        <!--主页面内容-->
        <div class="head">
            <ul>
                <li class="left" id="left"><img src="img/headimg.png"></li>
        		<li class="before"><img src="img/left.png"></li>
                <li class="titles"><span>金字招牌大酒楼</span></li>
                <li class="last"><img src="img/right.png"></li>
                <li class="rili" id="main"><form><input type="text" class="input" id="pickdate" onchange="showUser(this.value)"/></form></li>		
            </ul>
        </div>
        <script>
            function reloads(){
                location.reload();
            }
        </script>
        
        <div class="mian">
		  <div class="mian_biaodan">
			<ul class="biaodan">
			  <li style="border-right:1px solid #2E8906">
				<p style="text-align: center;">营业额</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b id="sum">0</b></p>
			  </li>
			  <li style="border-right:1px solid #2E8906">
				<p style="text-align: center;">客单数</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b id="num">0</b></p>
			  </li>
			  <li>
				<p style="text-align: center;">单均</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b id="round">0</b></p>
			  </li>
			</ul>
		  </div>
		</div>
        
        <div id="tab">
            <div class="content" id="content">
                <div id="txtHint" class="tab-content" style="display:block">
                    <h3>支付方式汇总</h3>
                    <table class="payment">
                        <thead>
                            <tr>
                                <td>支付方式</td>
                                <td>笔数</td>
                                <td>金额</td>
                            </tr>
                        </thead>
                        <tbody id="tabs1">

                        </tbody>
                    </table>                            
                </div>
                <div class="tab-content" style="display:none">
                    <h3>时段汇总</h3>
                    <table class="payment">
                        <thead>
                            <tr>
                                <td>时段汇总</td>
                                <td>笔数</td>
                                <td>金额</td>
                            </tr>
                        </thead>
                        <tbody id="tabs2">

                        </tbody>


                    </table>                    
                </div>
                <div class="tab-content" style="display:none">
                    <h3>菜类汇总</h3>
                    <table class="payment">
                        <thead>
                            <tr>
                                <td>菜品类别</td>
                                <td>数量</td>
                                <td>金额</td>
                            </tr>
                        </thead>
                        <tbody id="tabs3"></tbody>
                    </table>                    
                </div>
                <div class="tab-content" style="display:none">
                    <h3>菜品汇总</h3>
                    <table class="payment">
                        <thead>
                            <tr>
                                <td>菜品名称</td>
                                <td>数量</td>
                                <td>金额</td>
                            </tr>
                        </thead>
                        <tbody id="tabs4"></tbody>
                    </table>                    
                </div>
            </div>
            <div id="menus">
                <ul>
                    <li class="select"><img src="img/shouru.png"></li>
                    <li><img src="img/shiduan.png"></li>
                    <li><img src="img/cailei.png"></li>
                    <li><img src="img/caipin.png"></li>
                </ul>
            </div>
        </div>
        <!--《结束》主页面内容-->

<script>
    $("#pickdate").change(function(){
        var date=$("#pickdate").val();
         var str1="";
         var str2="";
         var str3="";
         var str4="";
         var st_sum = "";
         var st_num = "";
         var st_round = "";

        $.ajax({
                type: "get",
                dataType: "json",
                url: "content.php",//地址
                data: "q=" + date,
                success: function(data){
                    var t1 = data[0].length;
                    var t2 = data[1].length;
                    var t3 = data[2].length;
                    var t4 = data[3].length;
                    
                    for(var x=1;x<t1;x++){
                        str1 += "<tr><td>" + data[0][x].skfs +"</td><td>" + data[0][x].ddsl +"</td><td>" + data[0][x].flje +"</td></tr>";
                    }
                    for(var x=1;x<t2;x++){
                        str2 += "<tr><td>" + data[1][x].sd +"</td><td>" + data[1][x].sdsl +"</td><td>" + data[1][x].sdje +"</td></tr>";
                    }
                    for(var x=1;x<t3;x++){
                        str3 += "<tr><td>" + data[2][x].clmc +"</td><td>" + data[2][x].sl +"</td><td>" + data[2][x].je +"</td></tr>";
                    }
                    for(var x=1;x<t4;x++){
                        str4 += "<tr><td>"+data[3][x].cpmc+"</td><td>"+data[3][x].cpsl+"</td><td>"+data[3][x].cpje+"</td></tr>";
                    }
                    $("#tabs1").html(str1);
                    $("#tabs2").html(str2);
                    $("#tabs3").html(str3);
                    $("#tabs4").html(str4);
                    st_sum = data[4][0].sum;
                    $("#sum").html(st_sum);
                    st_num = data[4][0].num;
                    $("#num").html(st_num);
                    st_round = data[4][0].round;
                    $("#round").html(st_round);



                }
    });
    });
</script>
        <script>
        $("#pickdate").dateDropper({
            animate: false,
            format: 'Y-m-d',
            maxYear: '2050'
        });
        </script>
    </body>
</html>