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

<?php
    // 2.根据房台分析分析表查询账单信息
    include_once("connect.php");
    // $sql = "select jzdt from ftfx where SUBSTRING(jzdt, 1, 11) = '2016-09-26' and SubStore = '测试'";
    $ftfx_sql = "select SubStore,ysje,krrs,jzdt from ftfx where SUBSTRING(jzdt, 1, 11) = '".$date."' and SubStore = '".$dp_name."'";
    $ftfx_exec = odbc_exec($conn, $ftfx_sql);
    $ftfx_sum = 0;
    $ftfx_num = odbc_num_rows($ftfx_exec);//根据记录数来查询账单笔数，一个账单对应一条信息
    while (@$ftfx_row = odbc_fetch_array($ftfx_exec)) {
        $ftfx_sum += $ftfx_row[ysje];
    }
    // echo $ftfx_sum."<br>";//总的销售额
    // echo $ftfx_num."<br>";//账单笔数
    // echo round($ftfx_sum/$ftfx_num);//单均
?>
<?php 
    // 3.收款方式
    $skfs_sql = "select skfs from skjl where jzrq = '".$date."' and SubStore = '".$dp_name."' group by skfs";
    $skfs_exec = odbc_exec($conn, $skfs_sql);
    $skfs_sum = array();
    $skfs_num = array();
    $num = 0;
    while (@$skfs_row = odbc_fetch_array($skfs_exec)) {
        $num++;
        // echo  conver2utf8($skfs_row[skfs])."&nbsp;";//输出收款方式
        $skjl_sql = "select je from skjl where jzrq = '".$date."' and skfs = '".$skfs_row[skfs]."'";
        $skjl_exec = odbc_exec($conn, $skjl_sql);
        $skjl_sum = 0;
        $skjl_num = 0;
        while (@$skjl_row = odbc_fetch_array($skjl_exec)) {
            $skjl_sum += $skjl_row[je];
            $skjl_num++;
        }
        // echo $skfs_sum[$num] = $skjl_sum."&nbsp;";//支付方式分类金额
        // echo $skfs_num[$num] = $skjl_num."<br>";//支付方式分类订单数量
    }
    // echo array_sum($skfs_sum)."&nbsp;&nbsp;&nbsp;";//支付方式总金额
    // echo array_sum($skfs_num);//支付方式分类总订单数量
?>
<?php 
    // 4.时段汇总
    $time_num = 0;
    $sum_time = 0;//其实最主要的是上面的笔数 因为总金额就是当日的营业总金额营业总额根据不同查询方式是不会变化的
    for ($i=9; $i < 22; $i++) { 
        // echo $i."&nbsp;&nbsp;";
        $time_sql = "select ysje from ftfx where SUBSTRING(jzdt, 1, 11) = '".$date."' and SUBSTRING(jzdt, 12, 2) = '".$i."' and SubStore = '".$dp_name."'";
        $time_exec = odbc_exec($conn, $time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = odbc_fetch_array($time_exec)) {
            $time_arr[$time] = $time_row[ysje];
            $time++;
        }
        // echo array_sum($time_arr)."&nbsp;&nbsp;";//该时间段的销售总金额
        // echo count($time_arr);//该时间段的销售总笔数
        // echo "<br>";
        $time_num+=count($time_arr);
        $sum_time+=array_sum($time_arr);
    }
    // echo $time_num;//总笔数
    // echo $sum_time;//总金额
?>
<?php
// 5.菜类汇总
// SELECT lbname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '2016-09-26' AND SubStore = '测试' GROUP BY lbname

    $category_sql = "SELECT lbname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' GROUP BY lbname";
    $category_exec = odbc_exec($conn, $category_sql);
    $category_arr = array();
    $category = 0;
    $category_num = 0;
    while ($category_row = odbc_fetch_array($category_exec)) {

        // echo conver2utf8($category_row[lbname])."<br>";//菜类名称

        $cate_sql = "SELECT * FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' AND lbname = '".$category_row[lbname]."'";
        $cate_exec = odbc_exec($conn, $cate_sql);
        $cate_arr = array();
        $cate = 0;
        while ($cate_row = odbc_fetch_array($cate_exec)) {
            $cate_arr[$cate] += $cate_row[xsje];
            $cate++;
        }
        // echo array_sum($cate_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cate_arr);//该菜类总销售数量
        // echo "<br>";
        $category_num += count($cate_arr);
    }
    // echo $category_num;//总共销售菜类数量
?>
<?php 
    // 6.菜品汇总
    $cpname_sql = "SELECT jcname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' GROUP BY jcname";
    $cpname_exec = odbc_exec($conn, $cpname_sql);
    $cpname_arr = array();
    $cpname = 0;
    $cpname_num = 0;
    while ($cpname_row = odbc_fetch_array($cpname_exec)) {

        // echo conver2utf8($cpname_row[jcname])."<br>";//菜品名称
        $cp_sql = "SELECT * FROM jcfx WHERE SUBSTRING(jzrq,1,11)= '".$date."' AND SubStore = '".$dp_name."' AND jcname = '".$cpname_row[jcname]."'";
        $cp_exec = odbc_exec($conn,$cp_sql);
        $cp_arr = array();
        $cp = 0;
        while ($cp_row = odbc_fetch_array($cp_exec)) {
            $cp_arr[$cp] += $cp_row[xsje];
            $cp++;
        }
        // echo array_sum($cp_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cp_arr);//该菜类总销售数量
        // echo "<br>";
        $cpname_num += count($cp_arr);
    }
    // echo $cpname_num;//总共销售菜类数量

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
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo $ftfx_sum?></b></p>
			  </li>
			  <li style="border-right:1px solid #2E8906">
				<p style="text-align: center;">客单数</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo $ftfx_num?></b></p>
			  </li>
			  <li>
				<p style="text-align: center;">单均</p>
				<p style="color:#000;font-size:22px;text-align: center;"><b><?php echo round($ftfx_sum/$ftfx_num)?></b></p>
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
    // 3.收款方式
    $skfs_sql = "select skfs from skjl where jzrq = '".$date."' and SubStore = '".$dp_name."' group by skfs";
    $skfs_exec = odbc_exec($conn, $skfs_sql);
    $skfs_sum = array();
    $skfs_num = array();
    $num = 0;
    while (@$skfs_row = odbc_fetch_array($skfs_exec)) {
        $num++;
?>        
                            <tr>
                                <td><?php echo conver2utf8($skfs_row[skfs])?></td>
<?php
        // echo  conver2utf8($skfs_row[skfs])."&nbsp;";//输出收款方式
        $skjl_sql = "select je from skjl where jzrq = '".$date."' and skfs = '".$skfs_row[skfs]."'";
        $skjl_exec = odbc_exec($conn, $skjl_sql);
        $skjl_sum = 0;
        $skjl_num = 0;
        while (@$skjl_row = odbc_fetch_array($skjl_exec)) {
            $skjl_sum += $skjl_row[je];
            $skjl_num++;
        }
        $skfs_sum[$num] = $skjl_sum;
        $skfs_num[$num] = $skjl_num;
?>   
                                <td><?php echo $skfs_num[$num]?></td>
                                <td><?php echo $skfs_sum[$num]?></td>
                            </tr>     
<?php
        // echo $skfs_sum[$num] = $skjl_sum."&nbsp;";//支付方式分类金额
        // echo $skfs_num[$num] = $skjl_num."<br>";//支付方式分类订单数量
    }
    // echo array_sum($skfs_sum)."&nbsp;&nbsp;&nbsp;";//支付方式总金额
    // echo array_sum($skfs_num);//支付方式分类总订单数量
?>                            


                            <tr>
                                <td>汇总</td>
                                <td><?php echo array_sum($skfs_num)?></td>
                                <td><?php echo array_sum($skfs_sum)?></td>
                            </tr>                            
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
    // 4.时段汇总
    $time_num = 0;
    $sum_time = 0;//其实最主要的是上面的笔数 因为总金额就是当日的营业总金额营业总额根据不同查询方式是不会变化的
    for ($i=9; $i < 22; $i++) { 
?>        
                            <tr>
                                <td><?php echo $i?></td>
<?php
        // echo $i."&nbsp;&nbsp;";
        $time_sql = "select ysje from ftfx where SUBSTRING(jzdt, 1, 11) = '".$date."' and SUBSTRING(jzdt, 12, 2) = '".$i."' and SubStore = '".$dp_name."'";
        $time_exec = odbc_exec($conn, $time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = odbc_fetch_array($time_exec)) {
            $time_arr[$time] = $time_row[ysje];
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
// 5.菜类汇总
// SELECT lbname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '2016-09-26' AND SubStore = '测试' GROUP BY lbname

    $category_sql = "SELECT lbname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' GROUP BY lbname";
    $category_exec = odbc_exec($conn, $category_sql);
    $category_arr = array();
    $category = 0;
    $category_num = 0;
    while ($category_row = odbc_fetch_array($category_exec)) {
?>
                            <tr>
                                <td><?php echo conver2utf8($category_row[lbname])?></td>
<?php
        // echo conver2utf8($category_row[lbname])."<br>";//菜类名称

        $cate_sql = "SELECT * FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' AND lbname = '".$category_row[lbname]."'";
        $cate_exec = odbc_exec($conn, $cate_sql);
        $cate_arr = array();
        $cate = 0;
        while ($cate_row = odbc_fetch_array($cate_exec)) {
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
    }
    // echo $category_num;//总共销售菜类数量
?>                            
                            <tr>
                                <td>汇总</td>
                                <td><?php echo $category_num?></td>
                                <td><?php echo $ftfx_sum?></td>
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
    // 6.菜品汇总
    $cpname_sql = "SELECT jcname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' GROUP BY jcname";
    $cpname_exec = odbc_exec($conn, $cpname_sql);
    $cpname_arr = array();
    $cpname = 0;
    $cpname_num = 0;
    while ($cpname_row = odbc_fetch_array($cpname_exec)) {
?>        
                        <tr>
                            <td><?php echo conver2utf8($cpname_row[jcname])?></td>
                            
<?php
        // echo conver2utf8($cpname_row[jcname])."<br>";//菜品名称
        $cp_sql = "SELECT * FROM jcfx WHERE SUBSTRING(jzrq,1,11)= '".$date."' AND SubStore = '".$dp_name."' AND jcname = '".$cpname_row[jcname]."'";
        $cp_exec = odbc_exec($conn,$cp_sql);
        $cp_arr = array();
        $cp = 0;
        while ($cp_row = odbc_fetch_array($cp_exec)) {
            $cp_arr[$cp] += $cp_row[xsje];
            $cp++;
        }
?>        
                            <td><?php echo count($cp_arr)?></td>
                            <td><?php echo array_sum($cp_arr) ?></td>
                        </tr>        
<?php                        
        // echo array_sum($cp_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cp_arr);//该菜类总销售数量
        // echo "<br>";
        $cpname_num += count($cp_arr);
    }
    // echo $cpname_num;//总共销售菜类数量

?>                        
                        <tr>
                            <td>汇总</td>
                            <td><?php echo $cpname_num?></td>
                            <td><?php echo $ftfx_sum?></td>
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