-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 10 月 18 日 16:10
-- 服务器版本: 5.5.40
-- PHP 版本: 5.4.33

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `boss`
--

-- --------------------------------------------------------

--
-- 表的结构 `dp`
--

CREATE TABLE IF NOT EXISTS `dp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zd_id` int(11) NOT NULL COMMENT '账单编号',
  `dp_id` int(11) NOT NULL COMMENT '店铺编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=gbk COMMENT='店铺表' AUTO_INCREMENT=31 ;

--
-- 转存表中的数据 `dp`
--

INSERT INTO `dp` (`id`, `zd_id`, `dp_id`) VALUES
(1, 10000, 1),
(2, 10000, 2),
(3, 10000, 3),
(4, 10000, 4),
(5, 10000, 5),
(6, 10000, 6),
(7, 10000, 7),
(8, 10000, 8),
(9, 10000, 1),
(12, 10000, 2),
(13, 10001, 3),
(14, 10001, 4),
(15, 10001, 5),
(16, 10001, 6),
(17, 10001, 7),
(18, 10001, 8),
(19, 10001, 9),
(20, 10001, 10),
(21, 10002, 1),
(22, 10002, 2),
(23, 10002, 3),
(24, 10002, 4),
(25, 10002, 5),
(26, 10002, 6),
(27, 10002, 7),
(28, 10002, 8),
(29, 10002, 9),
(30, 10002, 10);

-- --------------------------------------------------------

--
-- 表的结构 `skjl`
--

CREATE TABLE IF NOT EXISTS `skjl` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键(一个订单可能有多种支付方式)',
  `dp_id` int(11) NOT NULL COMMENT '店铺编号',
  `zd_id` int(11) NOT NULL COMMENT '账单编号',
  `price` double NOT NULL COMMENT '金额',
  `end_time` datetime NOT NULL COMMENT '结账时间',
  `payment` varchar(32) NOT NULL COMMENT '收款方式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=gbk COMMENT='收款记录表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `skjl`
--

INSERT INTO `skjl` (`id`, `dp_id`, `zd_id`, `price`, `end_time`, `payment`) VALUES
(1, 1, 10001, 50, '2016-10-14 14:31:21', '现金'),
(2, 1, 10000, 40, '2016-10-14 11:52:53', '支付宝'),
(3, 1, 10000, 60, '2016-10-14 11:52:53', '微信');

-- --------------------------------------------------------

--
-- 表的结构 `xsjl`
--

CREATE TABLE IF NOT EXISTS `xsjl` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cp_name` varchar(32) NOT NULL COMMENT '菜品名称',
  `num` int(11) NOT NULL COMMENT '数量',
  `price` double NOT NULL COMMENT '单价',
  `sum_price` double NOT NULL COMMENT '总金额',
  `xsje` double NOT NULL COMMENT '销售金额（实收金额）',
  `start_time` datetime NOT NULL COMMENT '下单时间',
  `end_time` datetime NOT NULL COMMENT '结账时间',
  `zd_id` int(11) NOT NULL COMMENT '账单编号',
  `dp_id` int(11) NOT NULL COMMENT '店铺编号',
  `category` varchar(32) NOT NULL COMMENT '类别名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=gbk COMMENT=' 销售记录表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `xsjl`
--

INSERT INTO `xsjl` (`id`, `cp_name`, `num`, `price`, `sum_price`, `xsje`, `start_time`, `end_time`, `zd_id`, `dp_id`, `category`) VALUES
(1, '干煸豆角', 2, 20, 40, 38, '2016-10-14 08:52:42', '2016-10-14 11:52:53', 10000, 1, '小炒'),
(2, '火爆虾尾', 1, 62, 62, 62, '2016-10-14 08:52:42', '2016-10-14 11:52:53', 10000, 1, '小炒');

-- --------------------------------------------------------

--
-- 表的结构 `zd`
--

CREATE TABLE IF NOT EXISTS `zd` (
  `zd_id` int(11) NOT NULL COMMENT '账单编号',
  `dp_id` int(11) NOT NULL COMMENT '店铺编号',
  `ycrs` int(11) NOT NULL COMMENT '用餐人数',
  `xsje` double NOT NULL COMMENT '销售金额',
  `jz_time` datetime NOT NULL COMMENT '结账时间',
  PRIMARY KEY (`zd_id`,`dp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk COMMENT='账单表';

--
-- 转存表中的数据 `zd`
--

INSERT INTO `zd` (`zd_id`, `dp_id`, `ycrs`, `xsje`, `jz_time`) VALUES
(10000, 1, 1, 100, '2016-10-14 11:52:53');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
