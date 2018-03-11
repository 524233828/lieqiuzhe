CREATE TABLE `object_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_table` varchar(32) DEFAULT NULL COMMENT '对象相关的表',
  `desc` varchar(32) DEFAULT NULL COMMENT '对象的描述',
  `primary_key` varchar(32) DEFAULT 'id' COMMENT '主键',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 0-弃用 1-有用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='数据对象描述表（用于记录，联表时查看）';
