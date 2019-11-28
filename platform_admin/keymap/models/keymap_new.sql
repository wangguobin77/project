DROP PROCEDURE IF EXISTS `sp_category_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_category_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,name_en_val varchar(128)
    ,is_deleted_val varchar(32)
)
BEGIN
	set @name_en_val = name_en_val;
	set @start_number = start_number;
	set @page_size = page_size;
	set @is_deleted_val = is_deleted_val;
	set @tempsql = 'select * from category where 1=1';
    if(is_deleted_val <> '')
			THEN
				SET @tempsql=CONCAT(@tempsql,' and is_deleted = ',@is_deleted_val);
	END IF;
    if(name_en_val <>'')
			THEN
				SET @tempsql=CONCAT(@tempsql,' and name_en like ''',@name_en_val,'''');
	END IF;
		SET @tempsql=CONCAT(@tempsql,' order by add_time desc');
    if(start_number<>'' and page_size <>'')
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',@start_number,',',@page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_command_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_command_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,category_id varchar(32)
)
BEGIN
	SET @tempsql = "select * from km_command ";
    IF(category_id<>'')
		THEN
			SET @tempsql=CONCAT(@tempsql,' where category_id = "0" or find_in_set(\'',category_id,'\',category_id)');
	ELSEIF(start_number <> '' and page_size <> '' )
		THEN
			SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
			SET @tempsql=CONCAT(@tempsql,' ORDER BY km_command.code asc');

    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_remote_keyset_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_remote_keyset_select_all`(IN `remote_type_id` VARCHAR(32), IN `type_group` VARCHAR(32))
BEGIN
	if(remote_type_id<>'')
		then
        	if(type_group='1')
            then
				select type from remote_keyset as a  left join km_keycode b on a.key = b.parent where a.remote_type_id = remote_type_id group by type;
            else
				select k.*,p.* from remote_keyset k
					left join (
						SELECT parent,type FROM km_keycode group by parent,type
					) p on k.key = p.parent
				where k.remote_type_id = remote_type_id;
			end if;
	else
		if(type_group='1')
        then
			select type from km_keycode group by type;
		else
			select k.*,p.* from remote_keyset k
				left join (
					SELECT parent,type FROM km_keycode group by parent,type
				) p on k.key = p.parent;

        end if;
	end if;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_remote_keyset_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_remote_keyset_select_all`(IN `remote_type_id` VARCHAR(32), IN `type_group` VARCHAR(32))
BEGIN
	if(remote_type_id<>'')
		then
        	if(type_group='1')
            then
				select type from remote_keyset as a  left join km_keycode b on a.key = b.parent where a.remote_type_id = remote_type_id group by type;
            else
				select k.*,p.* from remote_keyset k
					left join (
						SELECT parent,type FROM km_keycode group by parent,type
					) p on k.key = p.parent
				where k.remote_type_id = remote_type_id;
			end if;
	else
		if(type_group='1')
        then
			select type from km_keycode group by type;
		else
			select k.*,p.* from remote_keyset k
				left join (
					SELECT parent,type FROM km_keycode group by parent,type
				) p on k.key = p.parent;

        end if;
	end if;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_keycode_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_keycode_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
)
BEGIN
		set @tempsql = "SELECT * from km_keycode ORDER BY `key` asc";
	if(start_number <> '' and page_size <> '' )
	then
		set @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_condition_judge_type_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_condition_judge_type_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
)
BEGIN
	set @tempsql = "select * from km_condition_judge_type ";
  if(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_condition_type_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_condition_type_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,category_id varchar(32)
)
BEGIN
	set @tempsql = "select * from km_condition_type ";
	IF(category_id<>'')
		THEN
			SET @tempsql=CONCAT(@tempsql,' where km_condition_type.category_id = "0xFF" or km_condition_type.category_id = ''',category_id,'''');
	ELSEIF(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_condition_value_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_condition_value_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
)
BEGIN
	set @tempsql = "select a.id
									,a.key
                                    ,a.code
                                    ,a.tag
                                    ,a.type
                                    ,c.key as condition_type_key
                                    ,c.code as condition_type_code
                                    ,c.tag as condition_type_tag
								from km_condition_value a
                                left join km_condition_type_condition_value  b on a.id = condition_value
                                left join km_condition_type c on b.condition_type = c.id ";
  if(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_remote_analog_select_all`;
delimiter ;;
CREATE  PROCEDURE `sp_remote_analog_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,remote_type_id varchar(32)
)
BEGIN
	SET @tempsql = "select * from remote_analog ";
	IF(remote_type_id<>'')
		THEN
			SET @tempsql = CONCAT(@tempsql,' where remote_analog.remote_type_id = ''',remote_type_id,''' order by analog asc ');
	END IF;
	IF(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_add_new`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_add_new`(
	keymap_id varchar(32)
	,remote_type_id varchar(32)
    ,category_id varchar(32)
    ,device_type_id varchar(32)
    ,is_official smallint(2)
    ,version_ varchar(2)
    ,keymap_name varchar(255)
    ,manufacture_id varchar(32)
    ,user_id varchar(32)
)
BEGIN
    declare b varchar(32);
    declare m varchar(32);
    declare s varchar(32);
    declare id varchar(32);
    declare ver_status varchar(32);
	declare exit handler for sqlexception rollback;
	call sp_km_keymap_get_ver(remote_type_id,category_id,device_type_id,is_official,manufacture_id,user_id);
    select @id, @b, @m, @s, @ver_status into id,b,m,s,ver_status;
	start transaction;
    if ver_status = 'R' then
		if( !isnull(b) && !isnull(m) && !isnull(s) ) then
			if version_ = 'b' then
				set b = b+1;
				set m = 0;
				set s = 0;
			end if;
			if version_ = 'm' then
				set m = m+1;
				set s = 0;
			end if;
			if version_ = 's' then
				set s = s+1;
			end if;
		else
			set b= 0;
			set m=0;
			set s=1;
		end if;

    INSERT INTO km_keymap
		(
			id
			,remote_type_id
			,category_id
			,device_type_id
			,status
			,is_official
			,ver_big
			,ver_mid
			,ver_sml
			,add_time
			,keymap_name
		)
		VALUES
		(	keymap_id
			,	remote_type_id
            ,	category_id
            ,	device_type_id
            , 	'B'
            ,	is_official
            ,	b
            ,	m
            ,	s
            ,	now()
            ,	keymap_name
		);


        if is_official = 1 then
            INSERT INTO km_keymap_manufacture (`keymap_id`,`manufacture_id`) values(keymap_id,manufacture_id);
		end if;






	end if;
    if ver_status = 'B' then
		set keymap_id= id;
    end if;

	SELECT *,CONCAT(ver_big,'.',ver_mid,'.',ver_sml) as ver
	FROM km_keymap km
	WHERE km.id = keymap_id;

	commit;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_data_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_data_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,km_id varchar(32)
)
BEGIN
	set @tempsql = "select * from km_keymap_data ";
    if(km_id<>'')
    then
		set @tempsql = CONCAT(@tempsql,' where km_keymap_data.km_id =''',km_id,'''');
	end if;
	if(start_number <> '' and page_size <> '' )
	then
		set @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	end if;
		set @tempsql=CONCAT(@tempsql,' ORDER BY command asc,add_time desc');
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_data_add`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_data_add`(
	id varchar(32)
	,remote_type_id varchar(32)
    ,category_id varchar(32)
    ,command varchar(32)
    ,km_data text
    ,km_id varchar(32)
    ,km_name varchar(32)
    ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;
	IF( not exists(select id from category where category.id = category_id) )
		THEN
        SET ret = 200001;
	ELSEIF( not exists(select id from remote_type where remote_type.id = remote_type_id) )
		THEN
        SET ret = 200002;
	ELSEIF( not exists(select id from km_command where (km_command.key = command and find_in_set(category_id,km_command.category_id) ) or (km_command.key = command and km_command.category_id = '0') ) )
		THEN
        SET ret = 200003;
	ELSE
			insert into km_keymap_data
					(
                    km_keymap_data.id
                    ,km_keymap_data.km_data
                    ,km_keymap_data.command
                    ,km_keymap_data.km_id
                    ,km_keymap_data.km_name
                    ,km_keymap_data.add_time
					)
				values
					(
					id
                    ,km_data
                    ,command
                    ,km_id
                    ,km_name
                    ,now()
					);
    IF _err=1 then
      SET ret = 0;
    ELSE
      SET ret = 1;
    END IF;
    END IF;
END
 ;;
delimiter ;


/*
DROP PROCEDURE IF EXISTS `sp_remote_type_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_remote_type_select_all`(
    TheOffset int
    ,TheLimit int
    ,State int
    ,Deleted int
    ,ManufactureId varchar(32)
    ,Keywords varchar(128)
)
BEGIN
	declare tmpsql  varchar(4000);
	declare tmpwhere  varchar(4000);
	declare rowCount  varchar(4000);

    set tmpsql = 'select
			r.*
			,m.name m_name
			,m.name_en m_name_en
			,m.logo
			,m.home_page
			,m.is_deleted manufacture_deleted
		from remote_type r
        left join manufacture m on r.manufacture_id = m.id';
	set tmpwhere = '  where m.is_deleted=0';
	if (ManufactureId <> '') then
		set tmpwhere = CONCAT(tmpwhere, ' and r.manufacture_id = \'', ManufactureId, '\'');
    end if;
	if (State >= 0) then
		set tmpwhere = CONCAT(tmpwhere, ' and r.status = ', State);
    end if;
	if (Deleted >= 0) then
		set tmpwhere = CONCAT(tmpwhere, ' and r.is_deleted = ', Deleted);
    end if;
	if (Keywords <> '') then
		set tmpwhere = CONCAT(tmpwhere, ' and r.name like \'%', Keywords, '%\' or r.name_en like \'%',Keywords,'%\'');
	end if;
    -- 得到总记录数
    set @tmpcount = CONCAT(' select count(*) into @rowCount from remote_type r left join manufacture m on r.manufacture_id = m.id' ,tmpwhere);
	prepare stmt from @tmpcount;
	EXECUTE stmt;

	set tmpsql = CONCAT(tmpsql, tmpwhere, ' ORDER BY add_time desc');

    if (TheLimit > 0) then
		set tmpsql = CONCAT(tmpsql, ' limit ', TheOffset, '  , ', TheLimit);
    end if;

    set @tmpsql = tmpsql;
    prepare stmt from @tmpsql;
    EXECUTE stmt;
END
 ;;
delimiter ;
*/

DROP PROCEDURE IF EXISTS `sp_remote_type_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_remote_type_select_all`(
    TheOffset int
    ,TheLimit int
    ,State int
    ,Deleted int
    ,ManufactureId varchar(32)
    ,Keywords varchar(128)
)
BEGIN
	declare tmpsql  varchar(4000);
	declare tmpwhere  varchar(4000);
	declare rowCount  varchar(4000);

    set tmpsql = 'SELECT r.*,r_short.short as short_name
                                    ,m.name as m_name
                                    ,m.name_en m_name_en
                                    ,m.logo,m.home_page
                                    ,m.is_deleted as manufacture_deleted
                                FROM remote_type r LEFT JOIN sn_remote_short_info as r_short on r.id=r_short.remote_type_id LEFT JOIN manufacture m on r.manufacture_id = m.id';
	set tmpwhere = '  where m.is_deleted=0';
	if (ManufactureId <> '') then
		set tmpwhere = CONCAT(tmpwhere, ' and r.manufacture_id = \'', ManufactureId, '\'');
    end if;
	if (State >= 0) then
		set tmpwhere = CONCAT(tmpwhere, ' and r.status = ', State);
    end if;
	if (Deleted >= 0) then
		set tmpwhere = CONCAT(tmpwhere, ' and r.is_deleted = ', Deleted);
    end if;
	if (Keywords <> '') then
		set tmpwhere = CONCAT(tmpwhere, ' and r.name like \'%', Keywords, '%\' or r.name_en like \'%',Keywords,'%\'');
	end if;
    -- 得到总记录数
    set @tmpcount = CONCAT(' select count(*) into @rowCount from remote_type r left join manufacture m on r.manufacture_id = m.id' ,tmpwhere);
	prepare stmt from @tmpcount;
	EXECUTE stmt;

	set tmpsql = CONCAT(tmpsql, tmpwhere, ' ORDER BY add_time desc');

    if (TheLimit > 0) then
		set tmpsql = CONCAT(tmpsql, ' limit ', TheOffset, '  , ', TheLimit);
    end if;

    set @tmpsql = tmpsql;
    prepare stmt from @tmpsql;
    EXECUTE stmt;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_remote_type_select_all_new`;
delimiter ;;
CREATE PROCEDURE `sp_remote_type_select_all_new`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,manufacture_id_val varchar(32)
    ,name_en_val varchar(255)
    ,is_deleted_val varchar(32)
)
BEGIN
    set @manufacture_id_val = manufacture_id_val;
    set @name_en_val = name_en_val;
    set @is_deleted_val = is_deleted_val;
    set @start_number = start_number;
    set @page_size = page_size;
    set @tempsql = 'SELECT r.*,r_short.short as short_name
                                    ,m.name as m_name
                                    ,m.name_en m_name_en
                                    ,m.logo,m.home_page
                                    ,m.is_deleted as manufacture_deleted
                                FROM remote_type r LEFT JOIN sn_remote_short_info as r_short LEFT JOIN manufacture m on r.manufacture_id = m.id WHERE m.is_deleted=0';
    if(is_deleted_val <>'')
    then
        set @tempsql=CONCAT(@tempsql,' and r.is_deleted = ',@is_deleted_val);
    end if;
    if(manufacture_id_val <>'')
    then
        set @tempsql=CONCAT(@tempsql,' and manufacture_id = ''',@manufacture_id_val,'''');
    end if;
    if(name_en_val <>'')
    then
        set @tempsql=CONCAT(@tempsql,' and name_en like ''',@name_en_val,'''');
    end if;
        set @tempsql=CONCAT(@tempsql,' ORDER BY add_time desc');
    if(start_number<>'' and page_size <>'')
    then
        set @tempsql=CONCAT(@tempsql,' limit ',@start_number,',',@page_size);
    end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_keymap_data_select_one`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_data_select_one`(
   km_data_id varchar(32)
)
BEGIN
 select c.key as c_key,c.id,r.key as r_key,r.id,a.* from category c,remote_type r,(select  kd.id
            ,kd.km_data
            ,command
            ,kd.km_id
			,CONCAT(km.ver_big,'.',km.ver_mid,'.',km.ver_sml) as ver
			,km.keymap_name
			,km.remote_type_id
			,km.category_id
			,km.device_type_id
			,km.`status`
			from km_keymap_data as kd left join km_keymap as km on kd.km_id = km.id where kd.id = km_data_id) as a where c.id=a.category_id and r.id=a.remote_type_id;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_data_delete`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_data_delete`(
    km_data_id varchar(32)
    ,OUT ret int
)
BEGIN
	declare _err int default 0;
	declare continue handler for sqlexception,sqlwarning,not found set _err=1;
		set @keymap_id = (select km_id from km_keymap_data where id = km_data_id);

	if(@keymap_id is null)
	then
		set ret = 200101;

	elseif(not exists( select id from km_keymap where km_keymap.id = @keymap_id and km_keymap.status = 'B'))
	then
		set ret = 200102;
    else

		DELETE FROM km_keymap_data where km_keymap_data.id = km_data_id;
    if _err=1 then
		set ret = 0;
    else
		set ret = 1;
    end if;
    end if;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_data_edit`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_data_edit`(
	id varchar(32)
	,remote_type_id varchar(32)
    ,category_id varchar(32)
    ,command varchar(32)
    ,km_data text
    ,km_id varchar(32)
    ,km_name varchar(32)
    ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;
	IF( not exists(select id from category where category.id = category_id) )
		THEN
        SET ret = 200001;
	ELSEIF( not exists(select id from remote_type where remote_type.id = remote_type_id) )
		THEN
        SET ret = 200002;
	ELSEIF( not exists(select id from km_command where (km_command.key = command and find_in_set(category_id,km_command.category_id) ) or (km_command.key = command and km_command.category_id = '0') ) )
		THEN
        SET ret = 200003;
	ELSE
			update km_keymap_data set
                    km_keymap_data.km_data = km_data
                    ,km_keymap_data.command = command
                    ,km_keymap_data.km_id = km_id
                    ,km_keymap_data.km_name = km_name
					where  km_keymap_data.id = id;
    IF _err=1 then
      SET ret = 0;
    ELSE
      SET ret = 1;
    END IF;
    END IF;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_keymap_selects_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_selects_all`()
BEGIN
select id,remote_type_id,category_id,device_type_id,`status`,tag,is_official,B_status,CONCAT(ver_big,'.',ver_mid,'.',ver_sml) as ver,add_time,release_time from km_keymap A
	left join (
		select max(release_time) aa , category_id bb,remote_type_id cc  from km_keymap group by bb,cc
        ) B
        on A.category_id = B.bb and
        A.remote_type_id = B.cc
        and A.release_time = B.aa
where (A.category_id = B.bb and A.remote_type_id = B.cc and A.release_time = B.aa) or A.status = 'B';
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keycode_select_by_key`;
delimiter ;;
CREATE PROCEDURE `sp_km_keycode_select_by_key`(
    key_ varchar(255)
)
BEGIN
	    select * from km_keycode
	    where km_keycode.key = key_;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_keymap_release`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_release`(
	keymap_id varchar(32)
    ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;
    if( not exists(select id from km_keymap where id = keymap_id and status = 'B') )
    then
		set ret = 200101;
	else
	UPDATE km_keymap
    SET
		km_keymap.status = 'R'
        ,km_keymap.release_time = now()
	WHERE km_keymap.id = keymap_id;
    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
    end if;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_keymap_select_one`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_select_one`(
    keymap_id varchar(32)
)
BEGIN
	     select c.key as c_key,c.id,r.key as r_key,r.id,a.*
         from category c,remote_type r,(select *,CONCAT(ver_big,'.',ver_mid,'.',ver_sml) as ver
         from km_keymap where id = keymap_id) as a
         where c.id=a.category_id and r.id=a.remote_type_id;
END
 ;;
delimiter ;



-- ----------------------------
--  Procedure structure for `sp_km_keymap_r_info_select_one`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_km_keymap_r_info_select_one`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_r_info_select_one`(
   remote_type_id varchar(32),
   category_id varchar(32)
)
BEGIN
	select id,remote_type_id,category_id,release_time,`status`,CONCAT(ver_big,'.',ver_mid,'.',ver_sml) as ver
    from km_keymap
    WHERE is_official=0 and `status`='R' and km_keymap.remote_type_id=remote_type_id and km_keymap.category_id=category_id
    ORDER BY release_time DESC limit 1;
END
 ;;
delimiter ;

-- ----------------------------
--  Procedure structure for `sp_km_keymap_r_info_select_one_new`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_km_keymap_r_info_select_one_new`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_r_info_select_one_new`(
   remote_type_id varchar(32),
   category_id varchar(32),
   offcial smallint(2)
)
BEGIN
	select id,remote_type_id,category_id,release_time,`status`,CONCAT(ver_big,'.',ver_mid,'.',ver_sml) as ver
    from km_keymap
    WHERE is_official=offcial and `status`='R' and km_keymap.remote_type_id=remote_type_id and km_keymap.category_id=category_id
    ORDER BY release_time DESC limit 1;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_data_and_km_command_select_count_one`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_data_and_km_command_select_count_one`(
	keymap_id varchar(32)
    ,command varchar(32)
)
BEGIN
	select c.canmap_once,d.num
    from km_command as c
    left join (select km_id,count(command) as num,command
    from km_keymap_data
    where km_keymap_data.km_id=keymap_id and km_keymap_data.command=command) as d on c.key=d.command where c.key=command;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_keymap_select_category_and_rc`;
delimiter ;;
CREATE PROCEDURE `sp_keymap_select_category_and_rc`(
	rc varchar(32)
	,category varchar(32)
)
BEGIN
	select r.id as rc_id
			,r.name_en as rc_name_en
            ,r.`key` as rc_key
            ,r.tag as rc_tag
            ,c.id as c_id
            ,c.name_en as c_name_en
            ,c.key as c_key
            ,c.tag as c_tag
	from remote_type r,category c
    where r.id = rc and c.id = category;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_remote_category_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_remote_category_select_all`()
BEGIN
   select *
			from remote_category;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_type_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_type_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
)
BEGIN
	set @tempsql = "select * from km_keymap_type ";
  if(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_km_command_type_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_command_type_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
)
BEGIN
	set @tempsql = "select * from km_command_type ";
  if(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_op_style_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_km_op_style_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
)
BEGIN
	set @tempsql = "select * from km_op_style ";
  if(start_number <> '' and page_size <> '' )
			THEN
				SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
	END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_manufacture_select_all_new`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_manufacture_select_all_new`(
   start_number SMALLINT(2),
    page_size SMALLINT(2),
    B_status tinyint(2),
   is_official smallint(2)
)
BEGIN
 set @tempsql = "
select k.*,ifnull(m.tag,m.name_en) as manufacture,ifnull(c.tag,c.name_en) as category,ifnull(r.tag,r.name_en) as remote_type,ifnull(d.tag,d.name_en) as device_type
                        from km_keymap k
left join km_keymap_manufacture a on k.id = a.keymap_id
left join manufacture m on a.manufacture_id = m.id
left join remote_type r on k.remote_type_id = r.id
left join device_type d on k.device_type_id = d.id
left join category c on k.category_id = c.id
where !isnull(r.name_en) and !isnull(m.name_en) and !isnull(c.name_en)
 ";

  if(!is_official)
     THEN
        SET @tempsql=CONCAT(@tempsql,' and is_official=0');
  ELSE
      set @tempsql=CONCAT(@tempsql,' and is_official=',is_official,' and B_status !=2');
  END If;

  if(B_status <> 0)
     THEN
        SET @tempsql=CONCAT(@tempsql,' and B_status=',B_status);
  END If;

  if(page_size <> 0 )
THEN
SET @tempsql=CONCAT(@tempsql,' limit ',start_number,',',page_size);
END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_device_type_select_by_device_id`;
delimiter ;;
CREATE PROCEDURE `sp_device_type_select_by_device_id`(
    device_id varchar(32)
)
BEGIN
	SELECT d.*,m.name_en as manufacture_name,c.name_en as category_name
	FROM device_type d
    LEFT JOIN manufacture m
    ON d.manufacture_id = m.id
    LEFT JOIN category c
    ON d.category_id =c.id
    WHERE d.id = device_id;
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_change_B_status`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_change_B_status`(
	km_id varchar(32)
    ,state smallint(2)
    ,out ret int
)
BEGIN
	UPDATE km_keymap
    SET
		B_status = state
    WHERE id = km_id;

    set ret = ReturnState_Succeed();
END
 ;;
delimiter ;


DROP PROCEDURE IF EXISTS `sp_km_keymap_describe_check_log_add`;
delimiter ;;
CREATE PROCEDURE `sp_km_keymap_describe_check_log_add`(
  keymap_id varchar(32),
  msg varchar(500),
  B_status tinyint(2),
  ip varchar(255),
  created_ts INT(11),
	OUT ret smallint
)
BEGIN
    	declare _err int default 0;
    	declare continue handler for sqlexception, sqlwarning, not found set _err=1;

     INSERT INTO km_keymap_describe_check_log
           (
        km_keymap_describe_check_log.keymap_id,
        km_keymap_describe_check_log.msg,
        km_keymap_describe_check_log.B_status,
        km_keymap_describe_check_log.ip,
        km_keymap_describe_check_log.created_ts
			)
     VALUES
           (
			 keymap_id
			,msg
			,B_status
			,ip
			,created_ts
			);

    if _err=1 then
        set ret = 0;
    ELSE
         set ret = 1;
    end if;

END
 ;;
delimiter ;

DROP PROCEDURE IF EXISTS `sp_manufacture_get_remote_select_all`;
delimiter ;;
CREATE PROCEDURE `sp_manufacture_get_remote_select_all`()
BEGIN

select m.*,r.id as r_id,r.name as r_name,r.name_en as r_name_en,r.type as r_type,r.key as r_key from remote_type as r
left join manufacture as m on m.id = r.manufacture_id where m.is_deleted != 1 and r.is_deleted != 1;

END
 ;;
delimiter ;

