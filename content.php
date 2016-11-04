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
    // $date = "2016-09-26";
    // 获取页面上的用户应该是在session中保存的

    // 获取店铺名称，先判断该SRQuery数据库的SubStore表中name字段中是否存在该店铺名称
    $dp_name = $_GET['dp_name'];
    $dp_name = conver2gbk("测试");
    // 1.存在就直接查询2.不存在就要添加这个店铺名称
    // 问题分析：现在就是有两个数据库，并且需要对两个数据库进行操作
    // 引入基本数据库连接文件文件
    $date = $_GET['q'];
    // 转换数据库的编码格式
function conver2utf8($string){
    return iconv("gbk", "utf-8", $string);
}
function conver2gbk($string){
    return iconv("utf-8", "gbk", $string);
}
    include_once ("connect.php");
// 3.收款方式
    $skfs_sql = "select skfs from skjl where jzrq = '".$date."' and SubStore = '".$dp_name."' group by skfs";
    $skfs_exec = odbc_exec($conn, $skfs_sql);
    $skfs_sum = array();
    $skfs_num = array();
    $num = 0;
    $res1[] = array();
    while (@$skfs_row = odbc_fetch_array($skfs_exec)) {
        $num++;
        $res1[$num][skfs] = conver2utf8($skfs_row[skfs]);
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
        $res1[$num][flje] = $skfs_sum[$num];
        $res1[$num][ddsl] = $skfs_num[$num];
    }   
        $res1[$num+1][skfs] = "汇总";    
        $res1[$num+1][flje] = array_sum($skfs_sum);
        $res1[$num+1][ddsl] = array_sum($skfs_num);

// 时段汇总
    $res2[] = array();
    $num2 = 0;
    $time_num = 0;
    $sum_time = 0;
    for ($i=9; $i < 22; $i++) { 
        $num2++;
        $res2[$num2][sd] = $i;
        $time_sql = "select ysje from ftfx where SUBSTRING(jzdt, 1, 11) = '".$date."' and SUBSTRING(jzdt, 12, 2) = '".$i."' and SubStore = '".$dp_name."'";
        $time_exec = odbc_exec($conn, $time_sql);
        $time_arr = array();
        $time = 0; 
        while ($time_row = odbc_fetch_array($time_exec)) {
            $time_arr[$time] = $time_row[ysje];
            $time++;
        }
        $res2[$num2][sdsl] = count($time_arr);
        $res2[$num2][sdje] = array_sum($time_arr);
        $time_num+=count($time_arr);
        $sum_time+=array_sum($time_arr);
    }
    $res2[$num2+1][sd] = "汇总";
    $res2[$num2+1][sdsl] = $time_num;
    $res2[$num2+1][sdje] = $sum_time;
// 5.菜类汇总
// SELECT lbname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '2016-09-26' AND SubStore = '测试' GROUP BY lbname

    $category_sql = "SELECT lbname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' GROUP BY lbname";
    $category_exec = odbc_exec($conn, $category_sql);
    $category_arr = array();
    $category = 0;
    $category_num = 0;
    $num3 = 0;
    $res3[] = array();
    while ($category_row = odbc_fetch_array($category_exec)) {
        $num3++;
        $res3[$num3][clmc] = conver2utf8($category_row[lbname]);

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
        $res3[$num3][sl] = count($cate_arr);
        $res3[$num3][je] = array_sum($cate_arr);
        $category_num += count($cate_arr);
        $category_sum += array_sum($cate_arr);
    }
    $res3[$num3+1][clmc] = "汇总";
    $res3[$num3+1][sl] = $category_num;
    $res3[$num3+1][je] = $category_sum;

// 6.菜品汇总
    $cpname_sql = "SELECT jcname FROM jcfx WHERE SUBSTRING(jzrq, 1, 11) = '".$date."' AND SubStore = '".$dp_name."' GROUP BY jcname";
    $cpname_exec = odbc_exec($conn, $cpname_sql);
    $cpname_arr = array();
    $cpname = 0;
    $cpname_num = 0;
    $num4 = 0;
    $res4[] = array();
    while ($cpname_row = odbc_fetch_array($cpname_exec)) {
        $num4++;
        $res4[$num4][cpmc] = conver2utf8($cpname_row[jcname]);
        // echo conver2utf8($cpname_row[jcname])."<br>";//菜品名称
        $cp_sql = "SELECT * FROM jcfx WHERE SUBSTRING(jzrq,1,11)= '".$date."' AND SubStore = '".$dp_name."' AND jcname = '".$cpname_row[jcname]."'";
        $cp_exec = odbc_exec($conn,$cp_sql);
        $cp_arr = array();
        $cp = 0;
        while ($cp_row = odbc_fetch_array($cp_exec)) {
            $cp_arr[$cp] += $cp_row[xsje];
            $cp++;
        }
        $res4[$num4][cpsl] = count($cp_arr);
        $res4[$num4][cpje] = array_sum($cp_arr);
        // echo array_sum($cp_arr)."&nbsp;&nbsp;&nbsp;";//该菜类总销售金额
        // echo count($cp_arr);//该菜类总销售数量
        // echo "<br>";
        $cpname_num += count($cp_arr);
        $cpname_sum += array_sum($cp_arr);
    }
    // echo $cpname_num;//总共销售菜类数量
    $res4[$num4+1][cpmc] = "汇总";
    $res4[$num4+1][cpsl] = $cpname_num;
    $res4[$num4+1][cpje] = $cpname_sum;

// $sql = "select jzdt from ftfx where SUBSTRING(jzdt, 1, 11) = '2016-09-26' and SubStore = '测试'";
    $ftfx_sql = "select SubStore,ysje,krrs,jzdt from ftfx where SUBSTRING(jzdt, 1, 11) = '".$date."' and SubStore = '".$dp_name."'";
    $ftfx_exec = odbc_exec($conn, $ftfx_sql);
    $ftfx_sum = 0;
    $ftfx_num = odbc_num_rows($ftfx_exec);//根据记录数来查询账单笔数，一个账单对应一条信息
    $res5[] = array();
    $num5 = 0;
    while (@$ftfx_row = odbc_fetch_array($ftfx_exec)) {
        $ftfx_sum += $ftfx_row[ysje];
    }
        $res5[$num5][sum] = $ftfx_sum;
        $res5[$num5][num] = $ftfx_num;
        $res5[$num5][round] = round($ftfx_sum/$ftfx_num);
    // echo $ftfx_sum."<br>";//总的销售额
    // echo $ftfx_num."<br>";//账单笔数
    // echo round($ftfx_sum/$ftfx_num);//单均

$res[] = array();
$res[0] = $res1;
$res[1] = $res2;
$res[2] = $res3;
$res[3] = $res4;
$res[4] = $res5;
echo json_encode($res);
exit;
?>
