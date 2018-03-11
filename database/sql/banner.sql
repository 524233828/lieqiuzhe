CREATE TABLE `banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'banner的ID',
  `img_url` varchar(255) NOT NULL COMMENT '图片地址',
  `url` varchar(255) NOT NULL COMMENT '跳转地址',
  `type` varchar(255) NOT NULL COMMENT 'banner类型',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0-冻结 1-可用',
  `sort` int(10) unsigned zerofill DEFAULT NULL COMMENT '排序，小的在前',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='banner表';