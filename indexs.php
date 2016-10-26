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
    // 获取页面上的用户应该是在session中保存的

    // 获取店铺名称，先判断该SRQuery数据库的SubStore表中name字段中是否存在该店铺名称
    $dp_name = $_GET['dp_name'];
    $dp_name = "测试";
    // 1.存在就直接查询2.不存在就要添加这个店铺名称
    // 问题分析：现在就是有两个数据库，并且需要对两个数据库进行操作
    // 引入基本数据库连接文件文件
function conver2utf8($string){
    return iconv("gbk", "utf-8", $string);
}
function conver2gbk($string){
    return iconv("utf-8", "gbk", $string);
}

    include_once ("connects.php");
    $name_sql = "select * from SubStore where Name = '".$dp_name."'";
    $name_exec = odbc_exec($conn, $name_sql);
    echo odbc_fetch_row($name_exec);
    if (odbc_fetch_row($name_exec)) {
        echo "向数据库中添加这个店铺名称";
    }else{
        echo "向数据库中添加这个店铺名称";
    }

?>

<?php 
    // 引入数据库连接文件文件
    include_once ("connect.php");
    $zd_sql = "select * from ftfx";
    $zd_exec = odbc_exec($conn, $zd_sql);
    echo odbc_fetch_row($zd_exec);
    while (odbc_fetch_row($zd_exec)) {
        $substore = odbc_result($zd_exec, "SubStore");
        echo conver2utf8($substore)."<br>";
      }
?>

<?php 
    // 问题 ：查询所选日期的总销售金额，账单笔数，均单金额
    // 获取的条件：店铺编号，所选的日期
    // 问题分析：首先根据条件查询账单表
    // $dp_id = $_GET['dp_id']  $jz_time = $_GET['jz_time']
    // $dp_id = $_GET['dp_id'];  
    // $jz_time = $_GET['jz_time'];
    $dp_id = 1;
    $jz_time = 20161014;
    $day_sql = "select * from zd where date_format(`jz_time`,'%Y%m%d') = ".$jz_time." and dp_id = ".$dp_id;
    $day_query = $mysqli->query($day_sql);
    $day_sum = 0;
    $day_num = 0;
    while (@$day_row = $day_query->fetch_array()) {
        $day_sum+=$day_row[xsje];
        $day_num++;
    }
    // echo $day_sum;                       //总的销售额
    // echo $day_num;                       //账单笔数
    // echo round($day_sum/$day_num);
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
        <script src="js/rili.js"></script>
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
    $unionid = "oZcVLv2a_6Y7TkDS-wNpmANjAySs";
    $user_sql = "SELECT * FROM user WHERE unionid = '".$unionid."'";  
    $user_query = $mysqli->query($user_sql);
    $user_row = $user_query->fetch_array();
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
                <li class="rili" id="main"><input type="text" class="input" id="pickdate" onMouseOut=reloads()/></li>		
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
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo $day_sum?></b></p>
			  </li>
			  <li style="border-right:1px solid #2E8906">
				<p style="text-align: center;">客单数</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo $day_num?></b></p>
			  </li>
			  <li>
				<p style="text-align: center;">单均</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo round($day_sum/$day_num)?></b></p>
			  </li>
			</ul>
		  </div>
		</div>
        <div id="tab">
            <div class="content" id="content">
                <div class="tab-content" style="display:block">
                    <h3>支付方式汇总</h3>
                        <table class="payment">
                            <tr>
                                <td>支付方式</td>
                                <td>笔数</td>
                                <td>金额</td>
                            </tr>
                            
                            
<?php
    // 问题：收款方式汇总 支付方式 笔数 金额 总金额 总笔数
    // 获取的条件：店铺编号 所选日期   同上
    // 问题分析:首先根据条件查询收款记录的表然后根据支付方式进行分组查询获取笔数以及相应的金额
    $pay_sql = "SELECT * FROM `skjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id ." GROUP BY payment";
    $pay_query = $mysqli->query($pay_sql);
    $pay_num = 0;
    $sum_jine = 0;
    while (@$pay_row = $pay_query->fetch_array()) {
        $pay_arr[$pay] = $pay_row[price];
        $pay++;
        ?>
                            <tr>
                                <td><?php echo $pay_row[payment]?></td>
        <?php
        // echo $pay_row[payment];               //支付方式名称          
        $pay_sum_sql = "SELECT * FROM `skjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND payment = '".$pay_row[payment]."' AND dp_id = ".$dp_id ;
        $pay_sum_query = $mysqli->query($pay_sum_sql);
        $pay_sum_arr = array();
        $pay_sum=0;
        while (@$pay_sum_row = $pay_sum_query->fetch_array()) {
            $pay_sum_arr[$pay_sum] = $pay_sum_row[price];
            $pay_sum++;
        }
        ?>
                            
                                <td><?php echo count($pay_sum_arr)?></td>
                                <td><?php echo array_sum($pay_sum_arr)?></td>
                            </tr>
        <?php
        // echo "&nbsp;&nbsp;".count($pay_sum_arr)."&nbsp;&nbsp;&nbsp;";//支付方式分类订单数量
        // echo array_sum($pay_sum_arr);                                //支付方式分类金额   
        // echo "<br>";
        $pay_num += count($pay_sum_arr);
        $sum_jine += array_sum($pay_sum_arr);
    }
    ?>

                            <tr>
                                <td>汇总</td>
                                <td><?php echo $pay_num?></td>
                                <td><?php echo $sum_jine?></td>
                            </tr>
<?php                            
    // echo $pay_num;
    // echo $sum_jine;ｅ
?>
                        </table>
                </div>
                <div class="tab-content" style="display:none">
                    <h3>时段汇总</h3>
                    

                    <table class="payment">
                        <tr>
                            <td>时段汇总</td>
                            <td>笔数</td>
                            <td>金额</td>
                        </tr>
<?php

    // 时段汇总 时间段 笔数 金额
    // 获取的条件：时间（年月日时）店铺编号
    // 问题分析 ：首先根据年月日以及店铺编号查询然后根据时分类汇总
    $time_num = 0;
    $sum_time = 0;//其实最主要的是上面的笔数 因为总金额就是当日的营业总金额营业总额根据不同查询方式是不会变化的
    for ($i=9; $i < 18; $i++) { 
        ?>
                        <tr>
                            <td><?php echo $i?></td>        
        <?php
        // echo $i."&nbsp;&nbsp;";
        $time_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id ." AND DATE_FORMAT(`end_time`,'%H') = ".$i;
        $time_query = $mysqli->query($time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = $time_query->fetch_array()) {
            $time_arr[$time] = $time_row[xsje];
            $time++;
        }
?>        
                            <td><?php echo count($time_arr)?></td>
                            <td><?php echo array_sum($time_arr)?></td>
                        </tr>
<?php                        
        // echo array_sum($time_arr)."&nbsp;&nbsp;";//该时间段的销售总金额
        // echo count($time_arr);//该时间段的销售总笔数
        // echo "<br>";
        $time_num+=count($time_arr);
        $sum_time+=array_sum($time_arr);
    }
    // echo $time_num;//总笔数
    // echo $sum_time;//总金额

?>                        

                        <tr>
                            <td>汇总</td>
                            <td><?php echo $time_num?></td>
                            <td><?php echo $sum_time?></td>
                        </tr>                            
                    </table>
                </div>
                <div class="tab-content" style="display:none">
                    <h3>菜类汇总</h3>

                    <table class="payment">
                        <tr>
                            <td>菜品类别</td>
                            <td>数量</td>
                            <td>金额</td>
                        </tr>
<?php
// 菜类汇总     菜品类别    数量      金额（xsje）
    // 分析：菜品类别 是根据菜品进行分类（group by）
    //       数量     是销售该菜类的数量
    //       金额     是该菜类的销售金额

    $category_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id." GROUP BY `category`";
    $category_query = $mysqli->query($category_sql);
    $category_arr = array();
    $category = 0;
    $category_num = 0;
    while ($category_row = $category_query->fetch_array()) {
?>   
                        <tr>
                            <td><?php echo $category_row[category]?></td>
                                 

<?php

        //echo $category_row[category];//菜类名称
        $cate_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id." AND category = '".$category_row[category]."'";
        $cate_query = $mysqli->query($cate_sql);
        $cate_arr = array();
        $cate = 0;
        while ($cate_row = $cate_query->fetch_array()) {
            $cate_arr[$cate] += $cate_row[xsje];
            $cate++;
        }
?>
                            <td><?php echo count($cate_arr)?></td>
                            <td><?php echo array_sum($cate_arr)?></td>
                        </tr>        
<?php                        
        // echo array_sum($cate_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cate_arr);//该菜类总销售数量
        // echo "<br>";
        $category_num += count($cate_arr);
        $cateprice_num += array_sum($cate_arr);
    }
    // echo $category_num;//总共销售菜类数量
    // echo $cateprice_num;//总销售金额
?>
                        <tr>
                            <td>汇总</td>
                            <td><?php echo $category_num?></td>
                            <td><?php echo $cateprice_num?></td>
                        </tr>
                    </table>
                    
                </div>
                <div class="tab-content" style="display:none">
                    <h3>菜品汇总</h3>
                    <table class="payment">
                        <tr>
                            <td>菜品名称</td>
                            <td>数量</td>
                            <td>金额</td>
                        </tr>
<?php
// 菜名汇总     菜品名称    数量      金额（xsje）
    // 分析：菜品类别 是根据名称进行分类（group by）
    //       数量     是销售该菜品的数量
    //       金额     是该菜品的销售金额

    $cpname_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id." GROUP BY `cp_name`";
    $cpname_query = $mysqli->query($cpname_sql);
    $cpname_arr = array();
    $cpname = 0;
    $cpname_num = 0;
    while ($cpname_row = $cpname_query->fetch_array()) {
?>      
                        <tr>
                            <td><?php echo $cpname_row[cp_name]?></td>
     

<?php
        // echo $cpname_row[cp_name];//菜类名称
        $cp_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id." AND `cp_name` = '".$cpname_row[cp_name]."'";
        $cp_query = $mysqli->query($cp_sql);
        $cp_arr = array();
        $cp = 0;
        while ($cp_row = $cp_query->fetch_array()) {
            $cp_arr[$cp] += $cp_row[xsje];
            $cp++;
        }
?>
                            <td><?php echo count($cp_arr)?></td>
                            <td><?php echo array_sum($cp_arr)?></td>
                        </tr>        
<?php                        
        // echo array_sum($cp_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cp_arr);//该菜类总销售数量
        // echo "<br>";
        $cpname_num += count($cp_arr);
        $cpprice_num += array_sum($cp_arr);
    }
    // echo $cpname_num;//总共销售菜类数量
    // echo $cpprice_num;//总销售金额
?>  
                        <tr>
                            <td>汇总</td>
                            <td><?php echo $cpname_num?></td>
                            <td><?php echo $cpprice_num?></td>
                        </tr>                                                       
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
        $("#pickdate").dateDropper({
            animate: false,
            format: 'Y-m-d',
            maxYear: '2050'
        });
        </script>
    </body>
</html>