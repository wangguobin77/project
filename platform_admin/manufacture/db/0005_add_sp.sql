-- ------------------
-- 添加批次
-- add sp_add_sn_batch_info by qijun.jiang at 20190320
-- ------------------
DROP PROCEDURE IF EXISTS `sp_add_sn_batch_info`;

DELIMITER $$
CREATE PROCEDURE `sp_add_sn_batch_info`(`m_id` VARCHAR(32), `h_id` VARCHAR(32), `h_type` TINYINT(1), `batch_year` CHAR(3), `batch_no` SMALLINT(3), `batch_count` INT(11), `upc_code` VARCHAR(20), `comment` VARCHAR(100), OUT `ret` TINYINT(1))
  BEGIN
    declare created_ts int(11);
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;

    set created_ts = unix_timestamp();

    insert into sn_batch_info (
      `m_id`,
      `h_id`,
      `h_type`,
      `batch_year`,
      `batch_no`,
      `batch_count`,
      `upc_code`,
      `created_ts`,
      `comment`
    )
    VALUES(
      m_id,
      h_id,
      h_type,
      batch_year,
      batch_no,
      batch_count,
      upc_code,
      created_ts,
      comment
    );
  if _err=1 then
      set ret = 0;
      else
      set ret = 1;
      end if;
END $$

DELIMITER ;

-- ------------------
-- 修改批次列表分页查询存储过程
-- modify sp_sn_manufacture_batch_info_select_all by qijun.jiang at 20190320
DROP PROCEDURE IF EXISTS `sp_sn_manufacture_batch_info_select_all`;
DELIMITER ;;
CREATE PROCEDURE `sp_sn_manufacture_batch_info_select_all`(
   `start_number` SMALLINT(2), `page_size` SMALLINT(2), `m_id` VARCHAR(32), `is_delete` SMALLINT(1)
)
BEGIN
set @tempsql = "select b.id,b.m_id,b.h_id,b.h_type,b.is_delete,b.batch_year,b.batch_no,b.batch_count,b.check_status,b.check_ts,b.created_ts,b.comment,m.name_en as manufacture_name from
  sn_batch_info as b left join manufacture as m on b.m_id=m.id";
   if(m_id<>'')
          THEN
            SET @tempsql=CONCAT(@tempsql,' where b.m_id="',m_id,'" and b.is_delete=0 order by b.created_ts DESC ');
     ELSE
             SET @tempsql=CONCAT(@tempsql,' where b.is_delete=0 order by b.created_ts DESC ');
   END IF;

   if(page_size <> 0 )
            THEN
                SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
    END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END ;;
DELIMITER ;

-- ------------------
-- 按条件获取sn_info列表（增加绑定用户uuid）
-- modify sp_sn_info_from_params_condition_select by qijun.jiang at 20190321
-- ------------------
DROP PROCEDURE IF EXISTS `sp_sn_info_from_params_condition_select`;
DELIMITER ;;
CREATE PROCEDURE `sp_sn_info_from_params_condition_select`(
    start_number SMALLINT(2)
    , page_size SMALLINT(2)
    , bid VARCHAR(32)
    , check_status tinyint(1)
    , sn varchar(20)
)
BEGIN
	SET @exeSql = "select sn_info.sn_id,sn_info.bid,sn_info.sn,sn_info.check_status,sn_info.bind_time,sn_info.rand_str,sn_info.created_ts,b.uuid
	  from sn_info
	  left join sn_device_info d on d.sn_id=sn_info.sn_id
	  left join sn_bind_user b on b.did=d.did
	  where sn_info.is_delete=0 ";
	IF(bid<>'')
		THEN
			SET @exeSql=CONCAT(@exeSql," and sn_info.bid='",bid,"' ");
	END IF;
	IF(check_status<>'')
		THEN
			SET @exeSql=CONCAT(@exeSql," and sn_info.check_status='",check_status,"' ");
	END IF;
	IF(sn<>'')
		THEN
			SET @exeSql=CONCAT(@exeSql," and sn_info.sn like '%",sn,"%' ");
	END IF;

	SET @exeSql=CONCAT(@exeSql," order by sn_info.sn ASC ");

	if(page_size <> 0 )
			THEN
				SET @exeSql=CONCAT(@exeSql,' limit ',start_number,',',page_size);
	END IF;
	prepare stmt from @exeSql;
    EXECUTE stmt;
END ;;
DELIMITER ;


-- ------------------
-- 审批
-- add sp_batch_check_status by qijun.jiang at 20190329
-- ------------------
DROP PROCEDURE IF EXISTS `sp_batch_check_status`;
DELIMITER ;;
CREATE PROCEDURE `sp_batch_check_status`(
  `batch_id` INT(11),
  `new_status` TINYINT(1)
)
sp:BEGIN
  -- 批次信息存储
  declare _m_id varchar(32); -- 厂商id
  declare _h_id varchar(32); -- 设备id
  declare _h_type tinyint(1); -- 设备类型 1：device 2：rc
  declare _batch_year_month char(3); -- 批次年月
  declare _batch_no tinyint(1); -- 批次号
  declare _batch_count int(11); -- 生产数量
  declare _check_status tinyint(1); -- 审批状态

  -- sn相关信息
  declare _m_short varchar(10); -- 厂商缩写
  declare _c_short varchar(10); -- 大类缩写
  declare _t_short varchar(10); -- 类型缩写
  declare _pre_sn char(13); -- sn前13位

  declare _v int default 0;  -- sn流水 1-99999
  declare _sn char(18);  -- sn18位序列号
  declare _access_code char(8); -- 授权码
  declare _very_code char(6);  -- 激活码
  declare _new_id bigint(18); -- sn_info自增id
  declare _did varchar(32);  -- 设备id
  declare _ts int DEFAULT 0; -- 时间戳

  declare _return_code int(6) default 0; -- 返回码
  declare _return_msg varchar(128) default ''; -- 返回信息
  declare continue handler for sqlexception, sqlwarning, not found set _return_code=1;

  -- 获取批次信息
  select
    m_id,h_id,h_type,batch_year,batch_no,batch_count,check_status
  into
    _m_id,_h_id,_h_type,_batch_year_month,_batch_no,_batch_count,_check_status
  from sn_batch_info
  where id=batch_id;

  -- 非待审批状态
  if _check_status <> 1 then
    set _return_code = 90050;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 获取厂商缩写
  select short into _m_short from sn_manufacture_short_info where mid=_m_id;

  -- 厂商缩写未设置
  if _m_short = '' or _m_short is null then
    set _return_code = 90051;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 获取大类缩写和设备类型缩写
  if _h_type = 1 then
    select
      sn_category_short_info.short,sn_device_type_short_info.short into _c_short,_t_short
    from device_type
    left join sn_category_short_info on sn_category_short_info.category_id=device_type.category_id
    left join sn_device_type_short_info on sn_device_type_short_info.device_type_id=device_type.id
    where device_type.id=_h_id;
  elseif _h_type = 2 then
    select
      rc_category_short,short into _c_short,_t_short
    from sn_remote_short_info
    where remote_type_id=_h_id;
  end if;

  -- 大类缩写未设置
  if _c_short = '' or _c_short is null then
    set _return_code = 90052;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 类型缩写未设置
  if _t_short = '' or _t_short is null then
    set _return_code = 90053;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 拼接sn前13位
  set _pre_sn = CONCAT(_m_short, _c_short, _t_short, _batch_year_month, LPAD(_batch_no, 2, 0));
  set _ts = unix_timestamp(now())+8*3600; -- 经验证现在数据库时间与本地时间差8小时，加上
  -- 开启事务
  START TRANSACTION;

  -- 审批通过
  if new_status = 3 then
    -- 循环插入sn和设备信息表
    while _v < _batch_count do
      set _sn = CONCAT(_pre_sn,LPAD(_v+1,5,0));
      set _access_code=FLOOR(RAND()*50000000 + 50000000);
      set _very_code=FLOOR(RAND()*500000 + 500000);
      insert into sn_info(bid,type_category,sn,check_status,uuid,bind_time,is_delete,rand_str,created_ts)VALUE(batch_id,_h_type,_sn,1,'',0,0,_very_code,_ts);
      set _new_id=LAST_INSERT_ID(); -- 插入的sn_id

      BEGIN -- todo代码块 did重复插入失败重试
        set _did=md5(concat(_ts,_v));
        insert into sn_device_info(did,sn_id,type_id,access_code,active_ts,created_ts,tag,alias)
                VALUES(_did,_new_id,_h_type,_access_code,0,_ts,'en','');
      end;
      set _v=_v+1;
    end while;
  end if;

  -- 更新批次状态和时间
  update sn_batch_info set check_status = new_status,check_ts=_ts where id=batch_id;

  if _return_code=1 then
      ROLLBACK;
    else
      COMMIT;
    end if;
  -- 返回
  select _return_code,_return_msg;
END ;;
DELIMITER ;


-- ------------------
-- 在指定批次下再次添加sn
-- add sp_add_batch_sn_two by qijun.jiang at 20190417
-- ------------------
DROP PROCEDURE IF EXISTS `sp_add_batch_sn_two`;
DELIMITER ;;
CREATE PROCEDURE `sp_add_batch_sn_two`(
  `_batch_id` INT(11),
  `_add_count` INT(11)
)
sp:BEGIN
  -- 批次信息存储
  declare _m_id varchar(32); -- 厂商id
  declare _h_id varchar(32); -- 设备id
  declare _h_type tinyint(1); -- 设备类型 1：device 2：rc
  declare _batch_year_month char(3); -- 批次年月
  declare _batch_no tinyint(1); -- 批次号
  declare _batch_count int(11); -- 生产数量
  declare _all_batch_count int(11); -- 总生产数量
  declare _check_status tinyint(1); -- 审批状态

  -- sn相关信息
  declare _m_short varchar(10); -- 厂商缩写
  declare _c_short varchar(10); -- 大类缩写
  declare _t_short varchar(10); -- 类型缩写
  declare _pre_sn char(13); -- sn前13位

  declare _v int default 0;  -- sn流水 1-99999
  declare _sn char(18);  -- sn18位序列号
  declare _access_code char(8); -- 授权码
  declare _very_code char(6);  -- 激活码
  declare _new_id bigint(18); -- sn_info自增id
  declare _did varchar(32);  -- 设备id
  declare _ts int DEFAULT 0; -- 时间戳

  declare _return_code int(6) default 0; -- 返回码
  declare _return_msg varchar(128) default ''; -- 返回信息
  declare continue handler for sqlexception, sqlwarning, not found set _return_code=1;

  -- 获取批次信息
  select
    `m_id`,`h_id`,`h_type`,`batch_year`,`batch_no`,`batch_count`,`check_status`
  into
    _m_id,_h_id,_h_type,_batch_year_month,_batch_no,_batch_count,_check_status
  from `sn_batch_info`
  where `id`=_batch_id;

  -- 非待审批状态
  if _check_status <> 3 then
    set _return_code = 90050;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 获取厂商缩写
  select `short` into _m_short from `sn_manufacture_short_info` where `mid`=_m_id;

  -- 厂商缩写未设置
  if _m_short = '' or _m_short is null then
    set _return_code = 90051;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 获取大类缩写和设备类型缩写
  if _h_type = 1 then
    select
      `sn_category_short_info`.`short`,`sn_device_type_short_info`.`short` into _c_short,_t_short
    from `device_type`
    left join `sn_category_short_info` on `sn_category_short_info`.`category_id`=`device_type`.`category_id`
    left join `sn_device_type_short_info` on `sn_device_type_short_info`.`device_type_id`=`device_type`.`id`
    where `device_type`.`id`=_h_id;
  elseif _h_type = 2 then
    select
      `rc_category_short`,`short` into _c_short,_t_short
    from `sn_remote_short_info`
    where `remote_type_id`=_h_id;
  end if;

  -- 大类缩写未设置
  if _c_short = '' or _c_short is null then
    set _return_code = 90052;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 类型缩写未设置
  if _t_short = '' or _t_short is null then
    set _return_code = 90053;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 拼接sn前13位
  set _pre_sn = CONCAT(_m_short, _c_short, _t_short, _batch_year_month, LPAD(_batch_no, 2, 0));
  set _ts = unix_timestamp(now())+8*3600; -- 经验证现在数据库时间与本地时间差8小时，加上

  -- 计算总数量和当前数量
  set _all_batch_count = (_batch_count+_add_count);
  set _v = _batch_count;

  -- 总数量超限
  if _all_batch_count > 99999 then
    set _return_code = 90057;
    select _return_code,_return_msg;
    leave sp;
  end if;

  -- 开启事务
  START TRANSACTION;

  -- 循环插入sn和设备信息表
  while _v < _all_batch_count do
    set _sn = CONCAT(_pre_sn,LPAD(_v+1,5,0));
    set _access_code=FLOOR(RAND()*50000000 + 50000000);
    set _very_code=FLOOR(RAND()*500000 + 500000);
    insert into `sn_info`(`bid`,`type_category`,`sn`,`check_status`,`uuid`,`bind_time`,`is_delete`,`rand_str`,`created_ts`)VALUE(_batch_id,_h_type,_sn,1,'',0,0,_very_code,_ts);
    set _new_id=LAST_INSERT_ID(); -- 插入的sn_id

    BEGIN -- todo代码块 did重复插入失败重试
      set _did=md5(concat(_ts,_v));
      insert into `sn_device_info`(`did`,`sn_id`,`type_id`,`access_code`,`active_ts`,`created_ts`,`tag`,`alias`)
        VALUES(_did,_new_id,_h_type,_access_code,0,_ts,'en','');
    end;
    set _v=_v+1;
  end while;

  -- 更新数量
  update `sn_batch_info` set `sn_batch_info`.`batch_count` = _all_batch_count where `id`=_batch_id;

  if _return_code=1 then
      ROLLBACK;
    else
      COMMIT;
    end if;
  -- 返回
  select _return_code,_return_msg;
END ;;
DELIMITER ;

-- ------------------
-- 根据厂商id获取批次列表
-- add sp_get_batch_info_list by qijun.jiang at 20190415
-- ------------------
DROP PROCEDURE IF EXISTS `sp_get_batch_info_list`;
DELIMITER ;;
CREATE PROCEDURE `sp_get_batch_info_list`(
    start_number int,
    page_size int,
    mid CHAR(32),
    OUT totalCount int
)
BEGIN
  -- 获取总条数
  select count(*) into totalCount from sn_batch_info where m_id=mid;

	SET @tempsql = "select sn_batch_info.*,manufacture.name as manufacture_name,if(sn_batch_info.h_type=1,sn_device_type_short_info.short,sn_remote_short_info.short) as facility_name
	  from sn_batch_info
	  left join manufacture on manufacture.id=sn_batch_info.m_id
	  left join sn_remote_short_info on sn_remote_short_info.remote_type_id=sn_batch_info.h_id
	  left join sn_device_type_short_info on sn_device_type_short_info.device_type_id=sn_batch_info.h_id";
	SET @tempsql=CONCAT(@tempsql,' where sn_batch_info.m_id="',mid,'" and sn_batch_info.is_delete=0 order by sn_batch_info.created_ts DESC ');
  IF(page_size <> 0)
  THEN
    SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
  END IF;
  prepare stmt from @tempsql;
  EXECUTE stmt;
END ;;
DELIMITER ;


-- ------------------
-- 获取厂商列表
-- add sp_get_manufacture_list by qijun.jiang at 20190417
-- ------------------
DROP PROCEDURE IF EXISTS `sp_get_manufacture_list`;
DELIMITER ;;
CREATE PROCEDURE `sp_get_manufacture_list`(
    _offset int,
    _limit int
    , OUT totalCount int
)
BEGIN
  -- 获取总条数
  select count(*) into totalCount from manufacture;

	SET @tempsql = "select id,name,linkman,mobile,status,is_deleted from manufacture ";
	SET @tempsql=CONCAT(@tempsql,' where 1=1 order by add_time DESC ');
  IF(_limit <> 0)
  THEN
    SET @tempsql=CONCAT(@tempsql,' limit ',_offset,',',_limit);
  END IF;
  prepare stmt from @tempsql;
  EXECUTE stmt;
END ;;
DELIMITER ;


-- ------------------
-- 添加厂商缩写
-- add sp_add_manufacture_short by qijun.jiang at 20190416
-- ------------------
DROP PROCEDURE IF EXISTS `sp_add_manufacture_short`;
DELIMITER ;;
CREATE PROCEDURE `sp_add_manufacture_short`(
    _mid CHAR(32),
    _short CHAR(2)
)
sp:BEGIN
  declare _ts int DEFAULT 0; -- 时间戳

  declare _return_code int(6) default 0; -- 返回码
  declare _return_msg varchar(128) default ''; -- 返回信息

  declare continue handler for sqlexception, sqlwarning, not found set _return_code=1;

  -- 查看缩写是否被使用
  if( exists(select id from sn_manufacture_short_info where short=_short))
  then
    set _return_code = 90054;
    select _return_code,_return_msg;
    leave sp;
  end if;

  set _ts = unix_timestamp(now())+8*3600; -- 经验证现在数据库时间与本地时间差8小时，加上

  INSERT INTO sn_manufacture_short_info
      (
        mid,
        short,
        created_ts
      )
      VALUES
      (
        _mid,
        _short,
        _ts
      );
  -- 返回
  select _return_code,_return_msg;
END ;;
DELIMITER ;

-- ------------------
-- 获取sn列表
-- add sp_get_sn_list by qijun.jiang at 20190422
-- ------------------
DROP PROCEDURE IF EXISTS `sp_get_sn_list`;
DELIMITER ;;
CREATE PROCEDURE `sp_get_sn_list`(
   _start_number SMALLINT(2)
    , _page_size SMALLINT(2)
    , _bid int
    , _check_status tinyint(1)
    , _sn CHAR(18)
    , OUT totalCount int
)
BEGIN

  SET @countSql = "select count(*) into totalCount from sn_info where sn_info.is_delete=0 ";

  SET @exeSql = "select sn_info.sn_id,sn_info.bid,sn_info.sn,sn_info.check_status,sn_info.bind_time,sn_info.rand_str,sn_info.created_ts,b.uuid
	  from sn_info
	  left join sn_device_info d on d.sn_id=sn_info.sn_id
	  left join sn_bind_user b on b.did=d.did
	  where sn_info.is_delete=0 ";
	IF(_bid<>'')
		THEN
			SET @countSql=CONCAT(@exeSql," and sn_info.bid='",_bid,"' ");
			SET @exeSql=CONCAT(@exeSql," and sn_info.bid='",_bid,"' ");
	END IF;
	IF(_check_status<>0)
		THEN
			SET @countSql=CONCAT(@exeSql," and sn_info.check_status='",_check_status,"' ");
			SET @exeSql=CONCAT(@exeSql," and sn_info.check_status='",_check_status,"' ");
	END IF;
	IF(_sn<>'')
		THEN
			SET @countSql=CONCAT(@exeSql," and sn_info.sn like '%",_sn,"%' ");
			SET @exeSql=CONCAT(@exeSql," and sn_info.sn like '%",_sn,"%' ");
	END IF;

	SET @exeSql=CONCAT(@exeSql," order by sn_info.sn_id ASC ");

	if(_page_size <> 0 )
			THEN
				SET @exeSql=CONCAT(@exeSql,' limit ',_start_number,',',_page_size);
	END IF;

	prepare stmt from @countSql;
    EXECUTE stmt;

	prepare stmt from @exeSql;
    EXECUTE stmt;
END ;;
DELIMITER ;


