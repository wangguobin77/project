DROP PROCEDURE IF EXISTS `sp_alter_tables`;
DELIMITER $$
CREATE PROCEDURE `sp_alter_tables`()
BEGIN
  DECLARE `col_cnt` INT DEFAULT 0; -- 字段存在否
  DECLARE `inx_cnt` INT DEFAULT 0; -- 索引存在否

  /* alter table sn_batch_info */
  SELECT COUNT(1) INTO col_cnt
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = 'sn_batch_info'
    AND column_name = 'comment'
    AND TABLE_SCHEMA = database();
  IF col_cnt <= 0 THEN
    ALTER TABLE `sn_batch_info` ADD `comment` VARCHAR(100) DEFAULT '' COMMENT '备注';
  END IF;


  /* alter table sn_device_info */
  SELECT COUNT(1) INTO inx_cnt
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = database()
    AND table_name = 'sn_device_info'
    AND index_name = 'uniq_sn_id';
  IF inx_cnt <=0 THEN
    ALTER TABLE `sn_device_info` ADD UNIQUE `uniq_sn_id` USING HASH (`sn_id`);
  END IF;

  /* alter table sn_remote_short_info */
  SELECT COUNT(1) INTO inx_cnt
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = database()
    AND table_name = 'sn_remote_short_info'
    AND index_name = 'short';
  IF inx_cnt <= 0 THEN
    ALTER TABLE `sn_remote_short_info` ADD UNIQUE `short` USING BTREE (`short`) comment '缩写唯一';
  END IF;

  /* alter table sn_device_type_short_info */
  SELECT COUNT(1) INTO inx_cnt
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = database()
    AND table_name = 'sn_device_type_short_info'
    AND index_name = 'short';
  IF inx_cnt <= 0 THEN
    ALTER TABLE `sn_device_type_short_info` ADD UNIQUE `short` USING BTREE (`short`) comment '缩写唯一';
  END IF;

  /* alter table sn_manufacture_short_info */
  SELECT COUNT(1) INTO inx_cnt
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = database()
    AND table_name = 'sn_manufacture_short_info'
    AND index_name = 'short';
  IF inx_cnt <= 0 THEN
    ALTER TABLE `sn_manufacture_short_info` ADD UNIQUE `short` USING BTREE (`short`) comment '缩写唯一';
  END IF;

  ALTER TABLE hardware.device_type
  CHANGE add_time add_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '添加时间';

END $$

DELIMITER ;

CALL sp_alter_tables();
DROP PROCEDURE IF EXISTS `sp_alter_tables`;