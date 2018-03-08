CREATE TABLE `league` (
  `id` int(11) NOT NULL COMMENT '联赛ID',
  `color` char(7) DEFAULT '#000000' COMMENT '颜色值',
  `gb_short` varchar(16) DEFAULT NULL COMMENT '中文简体简称',
  `big_short` varchar(16) DEFAULT NULL COMMENT '中文繁体简称',
  `en_short` varchar(16) DEFAULT NULL COMMENT '英文简称',
  `gb` varchar(255) DEFAULT NULL COMMENT '中文简体全称',
  `big` varchar(255) DEFAULT NULL COMMENT '中文繁体全称',
  `en` varchar(255) DEFAULT NULL COMMENT '英文全称',
  `type` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '比赛类型 1-联赛 2-杯赛',
  `sum_round` tinyint(3) UNSIGNED DEFAULT 38 COMMENT '总轮次',
  `curr_round` tinyint(3) UNSIGNED DEFAULT 1 COMMENT '当前轮次',
  `Curr_matchSeason` int(11) UNSIGNED DEFAULT NULL COMMENT '当前赛季',
  `countryID` int(11) UNSIGNED DEFAULT NULL COMMENT '国家ID',
  `country` varchar(255)  DEFAULT NULL COMMENT '国家名称',
  `areaID` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '地区ID',
  PRIMARY KEY (`id`),
  KEY `countryId` (`countryID`) USING BTREE,
  KEY `areaID` (`areaID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

