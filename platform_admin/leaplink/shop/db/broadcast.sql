DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
    `id`                 bigint(18) NOT NULL COMMENT 'id',
     `title`               VARCHAR (255)NOT NULL DEFAULT ""  COMMENT '广告标题',
     `bid`               bigint(18) NOT NULL         COMMENT '广告id',
    `description`        TEXT       COMMENT '当前活动规则描述',
    `week`               VARCHAR (255)NOT NULL DEFAULT ""  COMMENT '周期执行：1：礼拜一，2：礼拜2，3 礼拜3 .... 7：礼拜日',
    `type`               INT NOT NULL DEFAULT 0        COMMENT '任务状态 0 关闭 1 开启',
    `interval_type`      INT NOT NULL DEFAULT 0        COMMENT '是否存在间隔段时间推送，0代表没有间隔一次性，以秒为单位',
    `start_ts`           INT  NOT NULL DEFAULT 0                         COMMENT '有效期开始',
    `end_ts`             INT  NOT NULL DEFAULT 0                         COMMENT '有效期截止（实际上，也只是显示显示而已，也允许超期后继续购买。）',
    `send_start_ts`      VARCHAR(255) NOT NULL DEFAULT ''                   COMMENT '发送开始时间',
    `send_end_ts`        VARCHAR(255) NOT NULL DEFAULT ''                    COMMENT '发送结束时间',
    `interval_ts`             INT NOT NULL DEFAULT 0                   COMMENT '发送间隔时间 以秒为单位',
    `created_ts`         int(11)  NOT NULL DEFAULT 0 COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = '广告定时推送规则';