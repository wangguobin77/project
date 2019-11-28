
DROP TABLE IF EXISTS `coupon_category`;
CREATE TABLE `coupon_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '类型id',
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级 id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券类型表';


-- ----------------------------
--  Table structure for `coupon_type`
-- ----------------------------
DROP TABLE IF EXISTS `coupon_type`;
CREATE TABLE `coupon_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '优惠券模型id(自增主键)',
  `coupon_category_id` tinyint(1) NOT NULL COMMENT '类型 id',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建人(商户) id',
  `title` varchar(512) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '兑换券标题',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '购买金额,单位分',
  `worth` int(11) NOT NULL DEFAULT '0' COMMENT '面值,单位分',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0:有效 1:下架',
  `start_at` int(11) NOT NULL COMMENT '有效期起',
  `end_at` int(11) NOT NULL COMMENT '有效期止',
  `available_date` tinyint(1) unsigned NOT NULL DEFAULT '255' COMMENT '可用日期 周 1-7, 使用 1 byte 存储,每位1/0代表是/否不可用',
  `scope` varchar(512) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '适用范围（一段描述）',
  `info` varchar(2048) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '描述(一段比较长的描述，使用规则)',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券模型表';

-- ----------------------------
--  Table structure for `coupon_code`
-- ----------------------------
DROP TABLE IF EXISTS `coupon_code`;
CREATE TABLE `coupon_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键id',
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '优惠券码',
  `coupon_type_id` int(11) NOT NULL COMMENT '优惠券模型 id(从哪个模型中生成出来的)',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0:待使用 1:已使用 2:已禁用 3:已退货',
  `expire_at` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间戳',
  `created_at` int(11) NULL COMMENT '创建时间',
  `updated_at` int(11) NULL COMMENT '修改时间',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券价格(分)',
  `worth` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券价值(分)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupon_code_index` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券实例表';


-- ----------------------------
--  Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单自增 id',
  `orders_no` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号，唯一，18位纯数字，13位到毫秒时间戳+5位随机数',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户 id',
  `total_price` int(11) NOT NULL DEFAULT '0' COMMENT '订单价格(订单包含 coupon 的总价格)单位分',
  `total_worth` int(11) NOT NULL DEFAULT '0' COMMENT '订单面额(包含优惠券的总价值)单位分',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态: 0.待支付 1.已支付 2.取消订单 3.用户已提交退货 4.已退回 5.退货未审核通过',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型: 0.购物订单 1.退货订单',
  `pay_method` varchar(128) COMMENT '支付/退货返回，方式：free NULL?,alipay,wechat,cash,etc...',
  `pay_at` int(11) COMMENT '支付/退货返回，成功时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_no_UNIQUE` (`orders_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户订单表';

-- ----------------------------
--  Table structure for `orders_item`
-- ----------------------------
DROP TABLE IF EXISTS `orders_item`;
CREATE TABLE `orders_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单详情 id',
  `orders_id` int(11) NOT NULL COMMENT '订单 id',
  `coupon_type_id` int(11) NOT NULL COMMENT '优惠券模型 id/产品ID',
  `quantity` int(11) NOT NULL COMMENT '产品数量',
  `unit_price` int(11) NOT NULL DEFAULT '0' COMMENT '生成订单时的单价，单位分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订单详情表';

-- ----------------------------
--  Table structure for `wallet`
-- ----------------------------
DROP TABLE IF EXISTS `wallet`;
CREATE TABLE `wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '卡包 id',
  `user_id` int(11) NOT NULL COMMENT '用户 id',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `coupon_code_id` int(11) NOT NULL COMMENT '优惠券code id',
  `coupon_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '优惠券码，此字段，冗余',
  `orders_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单 id，需要记录每一张兑换券的来源，每一张优惠券，都需要通过显性或隐性的订单系统来获取，此字段，冗余',
  `orders_item_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单详情 id，退回时，按照生成订单时的价格退还',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态: 0.待使用 1.已使用 2.已申请退货 3.已退货',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='卡包';


-- ----------------------------
--  Table structure for `orders_pay_log`
-- ----------------------------
DROP TABLE IF EXISTS `orders_pay_log`;
CREATE TABLE `orders_pay_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单自增 id',
  `orders_no` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `info_request` varchar(2048) COMMENT '支付的请求信息',
  `info_response` varchar(2048) COMMENT '支付的返回信息',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户订单支付信息表';

-- ----------------------------
--  Table structure for `coupon_code_used_log`
-- ----------------------------
DROP TABLE IF EXISTS `coupon_code_used_log`;
CREATE TABLE `coupon_code_used_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键id',
  `coupon_code_id` int(11) NOT NULL COMMENT '优惠券ID',
  `used_by` int(11) NOT NULL DEFAULT '0' COMMENT '使用者 id',
  `used_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '使用者 ip',
  `used_at` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券使用log表';
