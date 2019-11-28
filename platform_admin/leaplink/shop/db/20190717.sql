
-- ----------------------------
-- 商户表(shop)
-- ----------------------------
CREATE TABLE IF NOT EXISTS `shop` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增 id',
  `phone` varchar(128) NOT NULL DEFAULT '' COMMENT '商户手机号',
  `username` varchar(128) NOT NULL DEFAULT '' COMMENT '商户用户名',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT '商户邮箱',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '商户名称',
  `password` varchar(128) NOT NULL DEFAULT '' COMMENT '登录密码(加盐密码)',
  `salt` varchar(128) NOT NULL DEFAULT '' COMMENT '盐',
  `shop_category_id` int(11) NOT NULL DEFAULT 0 COMMENT '商户类别 id',
  `code_p` varchar(15) NOT NULL DEFAULT '' COMMENT '省 code',
  `code_c` varchar(15) NOT NULL DEFAULT '' COMMENT '市 code',
  `code_a` varchar(15) NOT NULL DEFAULT '' COMMENT '区县 code',
  `address` varchar(512) NOT NULL DEFAULT '' COMMENT '详细地址',
  `open_time` varchar(128) NOT NULL DEFAULT '' COMMENT '营业开始时间',
  `close_time` varchar(128) NOT NULL DEFAULT '' COMMENT '打烊的时间',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `ip` BIGINT(11) NOT NULL DEFAULT 0 COMMENT 'ip_地址',
  `latitude` DECIMAL(3,2) NOT NULL DEFAULT 0 COMMENT '经度',
  `longitude` DECIMAL(3,2) NOT NULL DEFAULT 0 COMMENT '纬度',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '商品状态 0:未审核 1:审核通过',
  PRIMARY KEY (`shop_id`),
  UNIQUE KEY `uni_phone` (`phone`),
  UNIQUE KEY `uni_username` (`username`),
  UNIQUE KEY `uni_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户基本信息表';

-- ----------------------------
-- 商户资源关联表(shop_resource_related)
-- ----------------------------
CREATE TABLE IF NOT EXISTS `shop_resource_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增 id',
  `shop_id` int(11) NOT NULL DEFAULT 0 COMMENT '商户 id',
  `resource_id` int(128) NOT NULL DEFAULT 0 COMMENT '资源 id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_shop_resource` (`shop_id`, `resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户资源关联表';

-- ----------------------------
-- 商户类别表(category)
-- ----------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增 id',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '商户类型名称',
  `view_sort` int(11) NOT NULL DEFAULT 0 COMMENT '展示顺序',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户类型表';

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (1, '本帮菜', 1);
INSERT INTO `category` VALUES (2, '火锅', 2);
INSERT INTO `category` VALUES (3, '日料', 3);
INSERT INTO `category` VALUES (4, '奶茶', 4);


-- ----------------------------
-- 资源表(resource)
-- ----------------------------
CREATE TABLE IF NOT EXISTS `resource` (
  `resource_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '自增 id',
  `remote_uri` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '资源远程路径',
  `ip` bigint(11) NOT NULL DEFAULT 0 COMMENT '上传的 ip',
  `type` TINYINT NOT NULL DEFAULT 0 COMMENT '资源类型 0:图片',
  `position_type` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '该资源在商户中的位置类型 1:LOGO位置,2:插图位置 3:营业执照',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '资源添加时间',
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片资源表';


-- ----------------------------
-- 商户登录日志表(loginlog)
-- ----------------------------
CREATE TABLE IF NOT EXISTS `loginlog` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT COMMENT '自增 id',
  `shop_id` int(11) NOT NULL DEFAULT 0 COMMENT '商户 id',
  `ip` bigint(11) NOT NULL DEFAULT 0 COMMENT '登录时的 ip',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间(商户登录时间或刷新时间)',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '日志类型 1:登录 2:刷新token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户登入日志表';