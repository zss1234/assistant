<?php
    //报错级别
    error_reporting(0);
    // 设置字符集
    header('Content-Type: text/html; charset=utf-8');
    // 引入数据库连接文件文件
    include_once ("connect.php");
    // 设置时区为北京时间
    date_default_timezone_set("Asia/Shanghai");
    //获取当前时间   例如20161011
    $time = time();
    $date =  date("Ymd",$time);

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
    // echo round($day_sum/$day_num);       //单均金额


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
        // echo $pay_row[payment];               //支付方式名称          
        $pay_sum_sql = "SELECT * FROM `skjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND payment = '".$pay_row[payment]."' AND dp_id = ".$dp_id ;
        $pay_sum_query = $mysqli->query($pay_sum_sql);
        $pay_sum_arr = array();
        $pay_sum=0;
        while (@$pay_sum_row = $pay_sum_query->fetch_array()) {
            $pay_sum_arr[$pay_sum] = $pay_sum_row[price];
            $pay_sum++;
        }
        // echo "&nbsp;&nbsp;".count($pay_sum_arr)."&nbsp;&nbsp;&nbsp;";//支付方式分类订单数量
        // echo array_sum($pay_sum_arr);                                //支付方式分类金额   
        // echo "<br>";
        $pay_num += count($pay_sum_arr);
        $sum_jine += array_sum($pay_sum_arr);
    }
    // echo $pay_num;
    // echo $sum_jine;


    // 时段汇总 时间段 笔数 金额
    // 获取的条件：时间（年月日时）店铺编号
    // 问题分析 ：首先根据年月日以及店铺编号查询然后根据时分类汇总
    $time_num = 0;
    $sum_time = 0;//其实最主要的是上面的笔数 因为总金额就是当日的营业总金额营业总额根据不同查询方式是不会变化的
    for ($i=9; $i < 22; $i++) { 
        // echo $i."&nbsp;&nbsp;";
        $time_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id ." AND DATE_FORMAT(`end_time`,'%H') = ".$i;
        $time_query = $mysqli->query($time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = $time_query->fetch_array()) {
            $time_arr[$time] = $time_row[xsje];
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

        //echo $category_row[category];//菜类名称
        $cate_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id." AND category = '".$category_row[category]."'";
        $cate_query = $mysqli->query($cate_sql);
        $cate_arr = array();
        $cate = 0;
        while ($cate_row = $cate_query->fetch_array()) {
            $cate_arr[$cate] += $cate_row[xsje];
            $cate++;
        }
        // echo array_sum($cate_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cate_arr);//该菜类总销售数量
        // echo "<br>";
        $category_num += count($cate_arr);
    }
    // echo $category_num;//总共销售菜类数量

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

        // echo $cpname_row[cp_name];//菜类名称
        $cp_sql = "SELECT * FROM `xsjl` WHERE date_format(`end_time`,'%Y%m%d') = ".$jz_time." AND dp_id = ".$dp_id." AND `cp_name` = '".$cpname_row[cp_name]."'";
        $cp_query = $mysqli->query($cp_sql);
        $cp_arr = array();
        $cp = 0;
        while ($cp_row = $cp_query->fetch_array()) {
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