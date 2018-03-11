CREATE TABLE `platform` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '平台ID',
  `name` int(11) UNSIGNED DEFAULT 0 COMMENT '联赛ID',
  `status` varchar(255) DEFAULT NULL COMMENT '中文简体全称',
  `create_time` varchar(255) DEFAULT NULL COMMENT '中文繁体全称',
  `update_time` varchar(255) DEFAULT NULL COMMENT '英文全称',
  `app_id` varchar(20) DEFAULT NULL COMMENT '球队成立日期',
  `app_secret` varchar(255) DEFAULT NULL COMMENT '所在地',
  `salt` varchar(64) DEFAULT NULL COMMENT '球场',
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`) USING BTREE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

