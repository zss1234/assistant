<?php
    //报错级别
    error_reporting(0);
    // 设置字符集
    header('Content-Type: text/html; charset=utf-8');
    // 引入数据库连接文件文件
    include_once ("conn.php");
    // 设置时区为北京时间
    date_default_timezone_set("Asia/Shanghai");
    //获取当前时间   例如20161011
    $time = time();
    $date =  date("Ymd",$time);

?>

<?php 
    // 查询当天的营业额总数
    $sql="SELECT income FROM `orders` WHERE DATE(`date`) =DATE(NOW())";
    $query = $mysqli->query($sql);
    $sum = 0;
    while (@$row = $query->fetch_array()) {
        $sum+=$row[income];
    }
    // echo $sum;

    // 查询当天订单数量
    $orders="SELECT count(`order_id`) FROM `orders` WHERE DATE(`date`) =DATE(NOW()) GROUP BY order_id";
    $orders_query = $mysqli->query($orders);
    $orders_sum = 0;
    while (@$orders_row = $orders_query->fetch_array()) {
        $orders_sum++;
    }
    // 查询当天营业额支付方式汇总
    /**(订单是否存在)
    *1.首先查询总共有哪几类支付方式根据当前所关注的店铺名称查询
    *然后根据支付方式进行汇总分析查询
    */
    //获取当前店铺名称
    // $dpname = $_GET[dpname];
    $dpname = "金字招牌大酒楼";
    $pay_sql= "SELECT * FROM payment WHERE dpname = '".$dpname."' GROUP BY payid";
    $pay_query = $mysqli->query($pay_sql);
    while (@$pay_row = $pay_query->fetch_array()) {
        // echo $pay_row[payid]."&nbsp;&nbsp;".$pay_row[payname];
        $orders_pay_sql = "SELECT * FROM orders WHERE DATE(`date`) =DATE(NOW()) AND name='".$dpname."' AND payment=".$pay_row[payid];
        $orders_pay_query = $mysqli->query($orders_pay_sql);
        $arr = array();
        $j=0;
        while (@$orders_pay_row = $orders_pay_query->fetch_array()) {
            // echo "&nbsp;&nbsp;".$orders_pay_row[income]."&nbsp;&nbsp;&nbsp;";
            $arr[$j] = $orders_pay_row[income];
            $j++;
        }
        // echo count($arr);
        // echo array_sum($arr);
        // echo "<br>";
    }
    // 查询当天营业额支付方式汇总笔数
    /*(订单是否存在)
    *1.时间是当天
    *2.店铺名称
    *3.订单相同的
    *4.支付方式是相同的
    */
    // 就是上面的数组长度啊！！！


    //菜类汇总
    /**
    *1.当前时间
    *2.店铺名称
    *3.菜类
    ***/
    $dpname = "金字招牌大酒楼";
    $category_sql= "SELECT * FROM vegs_category WHERE boss_name = '".$dpname."' GROUP BY category_id";
    $category_query = $mysqli->query($category_sql);
    while (@$category_row = $category_query->fetch_array()) {
        // echo $category_row[category_id]."&nbsp;&nbsp;".$category_row[category];
        $category_orders_sql = "SELECT * FROM orders WHERE DATE(`date`) =DATE(NOW()) AND name='".$dpname."' AND category_id=".$category_row[category_id];
        // echo $category_orders_sql;
        $category_orders_query = $mysqli->query($category_orders_sql);
        $arr = array();
        $j=0;
        while (@$category_orders_row = $category_orders_query->fetch_array()) {
            // echo "&nbsp;&nbsp;".$category_orders_row[income]."&nbsp;&nbsp;&nbsp;";
            $arr[$j] = $category_orders_row[income];
            $j++;
        }
        // echo count($arr);
        // echo array_sum($arr);
        // echo "<br>";
    }
    // 菜品汇总
    /**
    *1.订单是否存在
    *2.菜品是否存在《好像又不需要跟菜品没关系 菜品随时可以更换的 》
    *此时的菜品就需要从订单中分类了
    *3.根据店铺名称查询
    *4.根据时间查询
    *5.根据订单中的菜品进行分类
    *
    */ 
    $cp_dpname = "金字招牌大酒楼";
    $cp_category_sql = "SELECT * FROM orders WHERE name = '".$cp_dpname."' AND DATE( `date` ) = DATE( NOW( ) ) AND exits =0 GROUP BY vegs_name";
    $cp_category_query = $mysqli->query($cp_category_sql);
    while (@$cp_category_row = $cp_category_query->fetch_array()) {
        // echo $cp_category_row[vegs_name]."&nbsp;";
        $cp_sql = "SELECT * FROM orders WHERE name = '".$cp_dpname."' AND DATE( `date` ) = DATE( NOW( ) ) AND exits =0 AND vegs_name = '".$cp_category_row[vegs_name]."'";
        $cp_query = $mysqli->query($cp_sql);
        $cp_arr = array();
        $cp = 0;
        while (@$cp_row = $cp_query->fetch_array()) {
            // echo $cp_row[income]."&nbsp;&nbsp;";
            $cp_arr[$cp]= $cp_row[income];
            $cp++;
        }
        // echo array_sum($cp_arr)."&nbsp;&nbsp;";
        // echo count($cp_arr);
        // echo "<br>";
    }


    //  当天时间段的营业额汇总
    // SELECT * FROM `orders` WHERE DATE_FORMAT(`date`,'%H') = DATE_FORMAT(NOW(),'%H')
    // SELECT * FROM `orders` WHERE DATE_FORMAT(`date`,'%H') = 数字
    /*
    *1.循环写出24小时的数字
    *2.根据上面的24小时数字开始查询
    *3.确保当前店铺
    *4.确保当前页面选择日期
    */ 

    for ($i=9; $i < 22; $i++) { 
        // echo $i."&nbsp;&nbsp;";
        // $time_sql = "SELECT * FROM `orders` WHERE name ='金子招牌大酒楼' AND DATE(`date`) = DATE(NOW()) AND DATE_FORMAT(`date`,'%H') = 7 ";
        $time_sql = "SELECT * FROM `orders` WHERE DATE_FORMAT(`date`,'%H') = ".$i." AND name = '金字招牌大酒楼' AND DATE(`date`) = DATE(NOW())";
        // echo $time_sql;
        $time_query = $mysqli->query($time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = $time_query->fetch_array()) {
            // echo $time_row[income];
            $time_arr[$time] = $time_row[income];
            $time++;
        }
        // echo array_sum($time_arr)."&nbsp;&nbsp;";
        // echo count($time_arr);
        // echo "<br>";
    }
    // exit;
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
                <img src="img/headerimg.png">
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
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo $sum?></b></p>
			  </li>
			  <li style="border-right:1px solid #2E8906">
				<p style="text-align: center;">客单数</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo $orders_sum?></b></p>
			  </li>
			  <li>
				<p style="text-align: center;">单均</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo round($sum/$orders_sum)?></b></p>
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
                            $dpname = "金字招牌大酒楼";
                            $pay_sql= "SELECT * FROM payment WHERE dpname = '".$dpname."' GROUP BY payid";
                            $pay_query = $mysqli->query($pay_sql);
                            while (@$pay_row = $pay_query->fetch_array()) {
                                
                            ?>    
                            <tr>
                                <td><?php echo $pay_row[payname]?></td>
                            <?php
                                $orders_pay_sql = "SELECT * FROM orders WHERE DATE(`date`) =DATE(NOW()) AND name='".$dpname."' AND payment=".$pay_row[payid];
                                $orders_pay_query = $mysqli->query($orders_pay_sql);
                                $arr = array();
                                $j=0;
                                while (@$orders_pay_row = $orders_pay_query->fetch_array()) {
                                    $arr[$j] = $orders_pay_row[income];
                                    $j++;
                                }
                                ?>
                                <td><?php echo count($arr)?></td>
                                <td><?php echo array_sum($arr)?></td>
                            </tr>
                                <?php
                            }
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
                            for ($i=9; $i < 22; $i++) { 
                                // echo $i."&nbsp;&nbsp;";
                                 ?>
                        <tr>
                            <td><?php echo $i?></td>
                                 <?php
                                // $time_sql = "SELECT * FROM `orders` WHERE name ='金子招牌大酒楼' AND DATE(`date`) = DATE(NOW()) AND DATE_FORMAT(`date`,'%H') = 7 ";
                                $time_sql = "SELECT * FROM `orders` WHERE DATE_FORMAT(`date`,'%H') = ".$i." AND name = '金字招牌大酒楼' AND DATE(`date`) = DATE(NOW())";
                                // echo $time_sql;
                                $time_query = $mysqli->query($time_sql);
                                $time_arr = array();
                                $time = 0; 
                                while ($time_row = $time_query->fetch_array()) {
                                    // echo $time_row[income];
                                    $time_arr[$time] = $time_row[income];
                                    $time++;
                                }

                                 ?>
                            <td><?php echo count($time_arr)?></td>
                            <td><?php echo array_sum($time_arr)?></td>
                        </tr>
                                 <?php
                                // echo array_sum($time_arr)."&nbsp;&nbsp;";
                                // echo count($time_arr);
                                // echo "<br>";
                            }
                        ?>
                        
                            
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

                        $dpname = "金字招牌大酒楼";
                        $category_sql= "SELECT * FROM vegs_category WHERE boss_name = '".$dpname."' GROUP BY category_id";
                        $category_query = $mysqli->query($category_sql);
                        while (@$category_row = $category_query->fetch_array()) {
                            ?>
                            <tr>
                            <td><?php echo $category_row[category];?></td>
                            <?php
                            // echo $category_row[category_id]."&nbsp;&nbsp;".$category_row[category];
                            $category_orders_sql = "SELECT * FROM orders WHERE DATE(`date`) =DATE(NOW()) AND name='".$dpname."' AND category_id=".$category_row[category_id];
                            $category_orders_query = $mysqli->query($category_orders_sql);
                            $arr = array();
                            $j=0;
                            while (@$category_orders_row = $category_orders_query->fetch_array()) {
                                // echo "&nbsp;&nbsp;".$category_orders_row[income]."&nbsp;&nbsp;&nbsp;";
                                $arr[$j] = $category_orders_row[income];
                                $j++;
                            }
                            ?>
                                <td><?php echo count($arr);?></td>
                                <td><?php echo array_sum($arr);?></td>
                            </tr>
                               <?php
                            // echo count($arr);
                            // echo array_sum($arr);
                            // echo "<br>";
                        }


                        ?>
                        
                            
                        
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
                            $cp_dpname = "金字招牌大酒楼";
                            $cp_category_sql = "SELECT * FROM orders WHERE name = '".$cp_dpname."' AND DATE( `date` ) = DATE( NOW( ) ) AND exits =0 GROUP BY vegs_name";
                            $cp_category_query = $mysqli->query($cp_category_sql);
                            while (@$cp_category_row = $cp_category_query->fetch_array()) {
                                
                                ?>
                        <tr>
                            <td><?php echo $cp_category_row[vegs_name]; ?></td>
                                <?php
                                // echo $cp_category_row[vegs_name]."&nbsp;";
                                $cp_sql = "SELECT * FROM orders WHERE name = '".$cp_dpname."' AND DATE( `date` ) = DATE( NOW( ) ) AND exits =0 AND vegs_name = '".$cp_category_row[vegs_name]."'";
                                $cp_query = $mysqli->query($cp_sql);
                                $cp_arr = array();
                                $cp = 0;
                                while (@$cp_row = $cp_query->fetch_array()) {
                                    $cp_arr[$cp]= $cp_row[income];
                                    $cp++;
                                }

                                ?>
                            <td><?php echo count($cp_arr); ?></td>
                            <td><?php echo array_sum($cp_arr);?></td>
                        </tr>    
                                <?php
                                // echo array_sum($cp_arr)."&nbsp;&nbsp;";
                                // echo count($cp_arr);
                                // echo "<br>";
                            }


                        ?>
                        
                            
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
            maxYear: '2020'
        });
        </script>
    </body>
</html>