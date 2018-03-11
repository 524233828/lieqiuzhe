CREATE TABLE `team` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '球队ID',
  `league_id` int(11) UNSIGNED DEFAULT 0 COMMENT '联赛ID',
  `gb` varchar(255) DEFAULT NULL COMMENT '中文简体全称',
  `big` varchar(255) DEFAULT NULL COMMENT '中文繁体全称',
  `en` varchar(255) DEFAULT NULL COMMENT '英文全称',
  `found` varchar(20) DEFAULT NULL COMMENT '球队成立日期',
  `area` varchar(255) DEFAULT NULL COMMENT '所在地',
  `gym` varchar(64) DEFAULT NULL COMMENT '球场',
  `capacity` int(6) UNSIGNED DEFAULT 0 COMMENT '球场容量',
  `flag` varchar(255) DEFAULT NULL COMMENT '队标，相对路径，域名为http://zq.win007.com/Image/team/',
  `addr` varchar(255) DEFAULT NULL COMMENT '地址',
  `url` varchar (255) DEFAULT NULL COMMENT '球队网址',
  `master` varchar(64) DEFAULT NULL COMMENT '主教练',
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`) USING BTREE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

