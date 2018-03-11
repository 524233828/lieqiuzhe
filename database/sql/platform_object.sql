CREATE TABLE `platform_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform_id` int(10) unsigned NOT NULL COMMENT '平台ID',
  `obj_id` int(10) unsigned NOT NULL COMMENT '对象ID',
  `obj_type` int(11) NOT NULL COMMENT '对象类型ID，具体查看object_type表',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态 0-禁用 1-启用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台对象关联表';

