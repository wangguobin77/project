


ALTER TABLE ota_product add COLUMN pro_code VARCHAR (128) not NULL after pro_name;
CREATE PROCEDURE `ota_product_add`(
    product_name VARCHAR (128)
    ,product_code VARCHAR (128)
    ,product_type VARCHAR (128)
    ,`desc` VARCHAR (128)
    ,user_id INT (11)
    ,user_name VARCHAR (128)
    ,OUT ret int
)
BEGIN
	  set ret = 0;
		insert into `ota_product`
		(
			`pro_name`
			,`pro_code`
			,`type`
			,`descriptions`
			,`staff_id`
			,`staff_name`
			,`created_ts`
			,`updated_ts`
		)
		values
		(
			product_name
			,product_code
			,product_type
			,descriptions
			,user_id
			,user_name
			,unix_timestamp()
			,unix_timestamp()
		);
		set ret = last_insert_id();
END



CREATE PROCEDURE `ota_product_list_select_all`(
  start_number varchar(32)
  ,page_size varchar(32)
  ,pro_type varchar(20)
  ,pro_name varchar(128)
)
BEGIN
  set @pro_type = pro_type;
  set @pro_name = pro_name;
  set @start_number = start_number;
  set @page_size = page_size;
  set @tempWhere = ' WHERE 1=1  ';

  set @tempsql = 'select a.* from ota_product a';

    if(pro_name <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.pro_name like \'%',@pro_name, '%\' ');
    end if;


    if(pro_type <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.type = ',@pro_type);
    end if;


  -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
                 FROM ota_product a ' ,@tempWhere);
    prepare stmt from @tmpcount;
    EXECUTE stmt;

    set @tempWhere=CONCAT(@tempWhere,' order by a.created_ts desc');
    if(start_number<>'' and page_size <>'')
    then
    set @tempsql=CONCAT(@tempsql,@tempWhere,' limit ',@start_number,',',@page_size);
    end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END

--  查找该产品下的具体信息



CREATE PROCEDURE `ota_product_select_one_by_pro_id`(
    pro_id int(11)
)
BEGIN
	  SELECT a.* FROM ota_product a where a.pro_id = pro_id;
END

CREATE PROCEDURE `ota_product_select_one_by_pro_name`(
    pro_name VARCHAR (128)
)
BEGIN
	  SELECT a.* FROM ota_product a where a.pro_name = pro_name;
END

 DELIMITER ;;
CREATE PROCEDURE `ota_product_update`(
    pro_id int (11),
    pro_name VARCHAR(128),
    pro_code VARCHAR(128),
    `type` int(1),
    `desc` VARCHAR (255),
    user_id int(11),
    user_name VARCHAR(128),
     ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;
  update ota_product a set
                a.pro_name = pro_name,
                a.pro_code = pro_code,
                a.type = `type`,
                a.staff_id = user_id,
                a.staff_name = user_name,
                a.descriptions = `desc`,
                a.updated_ts = unix_timestamp()
                where a.pro_id = pro_id;
    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END


 DELIMITER ;;
CREATE PROCEDURE `ota_product_delete_one`(
    pro_id int (11)
     ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;

    delete from ota_product where ota_product.pro_id = pro_id ;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END



CREATE PROCEDURE `ota_package_delete`(
    sp_pack_id int (11)
     ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;

    delete from  `diff_package` where diff_package.sp_pack_id = sp_pack_id ;

    delete from  `diff_package_file` where diff_package_file.sp_pack_id = sp_pack_id ;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END

-------------------------------------版本管理--------------------------------------------

CREATE PROCEDURE `ota_version_select_one_by_ver_id`(
    ver_id int(11)
)
BEGIN
	  SELECT a.*,b.pro_name FROM ota_version a LEFT JOIN ota_product b on a.pro_id = b.pro_id where a.ver_id = ver_id;
END

CREATE PROCEDURE `ota_version_list_select_all`(
  start_number varchar(32)
  ,page_size varchar(32)
  ,status varchar (2)
  ,is_init varchar (2)
  ,ver_pro_name varchar(128)
)
BEGIN
  set @status = status;
  set @is_init = is_init;
  set @ver_pro_name = ver_pro_name;
  set @start_number = start_number;
  set @page_size = page_size;
  set @tempWhere = ' WHERE 1=1 and a.status >=0 ';

  set @tempsql = 'select a.*,b.pro_name from version a LEFT JOIN product b on a.pro_id = b.pro_id ';

    if(ver_pro_name <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.ver_name like \'%',@ver_pro_name, '%\' ','',' or b.pro_name like \'%',@ver_pro_name, '%\' ');
    end if;


    if(status <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.status = ',@status);
    end if;
    if(is_init <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.is_init = ',@is_init);
    end if;

  -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
                 from version a LEFT JOIN product b on a.pro_id = b.pro_id ' ,@tempWhere);
    prepare stmt from @tmpcount;
    EXECUTE stmt;

    set @tempWhere=CONCAT(@tempWhere,' order by a.created_ts desc');
    if(start_number<>'' and page_size <>'')
    then
    set @tempsql=CONCAT(@tempsql,@tempWhere,' limit ',@start_number,',',@page_size);
    end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END

--------------------------------------灰度组管理---------------------------------------------------


CREATE PROCEDURE `ota_group_add`(
    group_name VARCHAR (128)
    ,sn VARCHAR (128)
    ,description VARCHAR (128)
    ,status TINYINT (1)
    ,user_id INT (11)
    ,OUT ret int
)
BEGIN
	  set ret = 0;
		insert into `ota_gray_group`
		(
			`group_name`
			,`description`
			,`status`
			,`staff_id`
			,`created_ts`
			,`updated_ts`
		)
		values
		(
			group_name
			,description
			,status
			,user_id
			,unix_timestamp()
			,unix_timestamp()
		);


    if(not exists( select * from ota_group_sn where ota_group_sn.group_id = last_insert_id() ))
    then
        insert into `ota_group_sn`
        (	`group_id`,`sn`,`status`,`staff_id`	,`created_ts`	,`updated_ts`)
        values
        (last_insert_id(),sn,status,user_id,unix_timestamp(),unix_timestamp()
        );
        set ret = last_insert_id();
    else
      set ret = 0;
    end if;
END


CREATE PROCEDURE `ota_group_select_one_by_group_id`(
    group_id int(11)
)
BEGIN
	  SELECT a.*,b.sn FROM ota_gray_group a LEFT JOIN ota_group_sn b on a.group_id = b.group_id where a.group_id = group_id;
END

CREATE PROCEDURE `ota_group_select_one_by_group_name`(
    group_name int(11)
)
BEGIN
	  SELECT a.* FROM ota_gray_group a  where a.group_name = group_name;
END

CREATE PROCEDURE `ota_group_list_select_all`(
  start_number varchar(32)
  ,page_size varchar(32)
  ,status tinyint(1)
  ,group_name varchar(128)
)
BEGIN
  set @status = status;
  set @group_name = group_name;
  set @start_number = start_number;
  set @page_size = page_size;
  set @tempWhere = ' WHERE 1=1  ';

  set @tempsql = 'select a.* from ota_gray_group a  ';

    if(group_name <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.group_name like \'%',@group_name, '%\' ');
    end if;


    if(status <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.status = ',@status);
    end if;

  -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
                 from ota_gray_group a ' ,@tempWhere);
    prepare stmt from @tmpcount;
    EXECUTE stmt;

    set @tempWhere=CONCAT(@tempWhere,' order by a.created_ts desc');
    if(start_number<>'' and page_size <>'')
    then
    set @tempsql=CONCAT(@tempsql,@tempWhere,' limit ',@start_number,',',@page_size);
    end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END

CREATE PROCEDURE `ota_group_update`(
    group_id int (11),
    group_name VARCHAR(128),
    sn VARCHAR(128),
    description VARCHAR (255),
    status VARCHAR (2),
    user_id int(11),
     ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

            update ota_gray_group a set
                a.group_name = group_name,
                a.staff_id = user_id,
                a.descriptions = description,
                a.updated_ts = unix_timestamp()
                where a.group_id = group_id;

            update ota_group_sn a set
                a.staff_id = user_id,
                a.sn = sn,
                a.updated_ts = unix_timestamp()
                where a.group_id = group_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;

END




CREATE PROCEDURE `ota_group_del`(
    group_id int (11),
    status VARCHAR (2),
     ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

            update ota_gray_group a set
                a.staff_id = user_id,
                a.status = status,
                a.updated_ts = unix_timestamp()
                where a.group_id = group_id;

            update ota_group_sn a set
                a.staff_id = user_id,
                a.status = status,
                a.updated_ts = unix_timestamp()
                where a.group_id = group_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END

-----------------------差分包管理--------------------------

CREATE PROCEDURE `ota_package_add`(
    sp_pack_name VARCHAR (128)
    ,from_ver_id INT (11)
    ,to_ver_id INT (11)
    ,alt_style TINYINT (1)
    ,fullupdate TINYINT (1)
    ,auto_download TINYINT (1)
    ,force_update TINYINT (1)
    ,description VARCHAR (128)
    ,status TINYINT (1)
    ,lang varchar (20)
    ,OUT ret int
)
BEGIN
	  set ret = 0;
		insert into `ota_split_package`
		(
			`sp_pack_name`
			,`description`
			,`status`
			,`type`
			,`from_ver_id`
			,`to_ver_id`
			,`lang`
			,`auto_download`
			,`force_update`
			,`alt_style`
			,`fullupdate`
			,`created_ts`
			,`updated_ts`
		)
		values
		(
			sp_pack_name
			,description
			,status
			,type
			,from_ver_id
			,to_ver_id
			,lang
			,auto_download
			,force_update
			,alt_style
			,fullupdate
			,unix_timestamp()
			,unix_timestamp()
		);
    set ret = last_insert_id();
END



CREATE PROCEDURE `ota_version_split_package_add`(
    sp_pack_id int (11)
    ,group_id INT (11)
    ,OUT ret int
)
BEGIN
	  set ret = 0;
		insert into `ota_version_spilt_package`
		(
			`sp_pack_id`
			,`group_id`
			,`created_ts`
			,`updated_ts`
		)
		values
		(
			sp_pack_id
			,group_id
			,unix_timestamp()
			,unix_timestamp()
		);
    set ret = last_insert_id();
END

CREATE PROCEDURE `ota_version_split_package_select_one`(
    sp_pack_id int (11)
)
BEGIN
    SELECT * FROM ota_split_package where ota_split_package.sp_pack_id = sp_pack_id;
END

CREATE PROCEDURE `ota_version_split_package_select_one_by_pack_group_id`(
    sp_pack_id int (11)
    ,group_id int (11)
)
BEGIN
    SELECT * FROM ota_version_split_package where ota_version_split_package.sp_pack_id = sp_pack_id and ota_version_split_package.group_id = group_id;
END
--
CREATE PROCEDURE `ota_package_select_one`(
    sp_pack_id int (11)
)
BEGIN
    SELECT * FROM ota_split_package where ota_split_package.sp_pack_id = sp_pack_id;
END

-- 更新

CREATE PROCEDURE `ota_package_update_by_pack_id`(
    sp_pack_id INT (11)
    ,sp_pack_name VARCHAR (128)
    ,from_ver_id INT (11)
    ,to_ver_id INT (11)
    ,alt_style TINYINT (1)
    ,fullupdate TINYINT (1)
    ,auto_download TINYINT (1)
    ,force_update TINYINT (1)
    ,description VARCHAR (128)
    ,status TINYINT (1)
    ,lang varchar (20)
    ,OUT ret int
)
begin
      declare _var,_err int default 0;
    	declare continue handler for sqlexception, sqlwarning, not found set _err=1;

      update
      ota_split_package as ota
      set ota.sp_pack_name = sp_pack_name ,
      ota.status = status,
      ota.from_ver_id = from_ver_id,
      ota.to_ver_id = to_ver_id,
      ota.lang = lang ,
      ota.description = description,
      ota.auto_download = auto_download,
      ota.force_update = force_update ,
      ota.alt_style = alt_style,
      ota.fullupdate = fullupdate,
      ota.updated_ts = unix_timestamp(now())
      where ota.sp_pack_id = sp_pack_id;

      if _err=1 then
          set ret = 0;
      ELSE
           set ret = 1;
      end if;
end


CREATE PROCEDURE `ota_package_list_select_all`(
  start_number varchar(32)
  ,page_size varchar(32)
  ,status varchar(4)
  ,ver_id int(11)
)
BEGIN
  set @status = status;
  set @ver_id = ver_id;
  set @start_number = start_number;
  set @page_size = page_size;
  set @tempWhere = ' WHERE 1=1  ';

  set @tempsql = 'select a.* from ota_split_package a  ';

    if(ver_id <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.to_ver_id = ',@ver_id);
    end if;


    if(status <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.status = ',@status);
    end if;

  -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
                 from ota_split_package a ' ,@tempWhere);
    prepare stmt from @tmpcount;
    EXECUTE stmt;

    set @tempWhere=CONCAT(@tempWhere,' order by a.created_ts desc');
    if(start_number<>'' and page_size <>'')
    then
    set @tempsql=CONCAT(@tempsql,@tempWhere,' limit ',@start_number,',',@page_size);
    end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END

CREATE PROCEDURE `ota_version_split_package_update_by_pack_id`(
    sp_pack_id int (11),
    group_id int(11),
     ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

          update ota_version_split_package a set
                a.group_id = group_id,
                a.updated_ts = unix_timestamp()
                where a.sp_pack_id = sp_pack_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END

CREATE PROCEDURE `ota_version_split_package_delete_by_pack_id`(
    sp_pack_id int (11),
     ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

          delete FROM ota_version_split_package a
                where a.sp_pack_id = sp_pack_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END



CREATE PROCEDURE `ota_diff_package_file_add`(
    sp_pack_id int (11)
    ,file_size VARCHAR (128)
    ,file_download_uri VARCHAR (128)
    ,md5file VARCHAR (128)
    ,OUT ret int
)
BEGIN
	  set ret = 0;
		insert into `ota_diff_package_file`
		(
			`sp_pack_id`
			,`file_size`
			,`file_download_uri`
			,`md5sum`
			,`created_ts`
			,`updated_ts`
		)
		values
		(
			sp_pack_id
			,file_size
			,file_download_uri
			,md5file
			,unix_timestamp()
			,unix_timestamp()
		);
    set ret = last_insert_id();
END


CREATE PROCEDURE `ota_diff_package_file_select_one_by_pack_md5_id`(
    sp_pack_id int (11)
    ,md5file VARCHAR (128)
)
BEGIN
    SELECT * FROM ota_diff_package_file a where a.sp_pack_id = sp_pack_id and a.md5sum = md5file;
END


CREATE PROCEDURE `ota_diff_package_file_update_by_pack_md5_id`(
    sp_pack_id int (11),
    ,md5file VARCHAR (128)
     ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

          update ota_diff_package_file a set
                a.sp_pack_id = sp_pack_id,
                a.sp_pack_id = sp_pack_id,
                a.updated_ts = unix_timestamp()
                where a.sp_pack_id = sp_pack_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END



CREATE PROCEDURE `ota_group_sn_list_select_all`(
  start_number varchar(32)
  ,page_size varchar(32)
  ,group_id int(11)
)
BEGIN
  set @group_id = group_id;
  set @start_number = start_number;
  set @page_size = page_size;
  set @tempWhere = ' WHERE 1=1  ';

  set @tempsql = 'select a.* from ota_group_sn a  ';

    if(group_id <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.group_id = ',@ver_id);
    end if;

  -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
                 from ota_group_sn a ' ,@tempWhere);
    prepare stmt from @tmpcount;
    EXECUTE stmt;

    set @tempWhere=CONCAT(@tempWhere,' order by a.created_ts desc');
    if(start_number<>'' and page_size <>'')
    then
    set @tempsql=CONCAT(@tempsql,@tempWhere,' limit ',@start_number,',',@page_size);
    end if;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END


CREATE PROCEDURE `ota_log_add`(
    sp_pack_id int (11)
    ,product_code VARCHAR (128)
    ,sn VARCHAR (128)
    ,OUT ret int
)
BEGIN
	  set ret = 0;

    insert into `log`
    (
      `sp_pack_id`
      ,`product_code`
      ,`sn`
      ,`created_ts`
      ,`updated_ts`
    )
    values
    (
      sp_pack_id
      ,product_code
      ,sn
      ,unix_timestamp()
      ,unix_timestamp()
    );
    set ret = last_insert_id();
END

CREATE PROCEDURE `ota_group_sn_add`(
    group_id int (11)
    ,sn VARCHAR (128)
    ,OUT ret int
)
BEGIN
	  set ret = 0;

	  if(EXISTS (SELECT * FROM ota_group_sn WHERE ota_group_sn.group_id and ota_group_sn.sn =sn )) THEN
	  -- 存在
	  SET ret = -1;

	  ELSE
        insert into `ota_group_sn`
        (
          `group_id`
          ,`sn`
          ,`created_ts`
          ,`updated_ts`
        )
        values
        (
          group_id
          ,sn
          ,unix_timestamp()
          ,unix_timestamp()
        );
        set ret = last_insert_id();
    END if;
END



CREATE PROCEDURE `ota_diff_package_set_status`(
     sp_pack_id int (11)
    ,status tinyint (1)
    ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

    update ota_diff_package a set
          a.status = status,
          a.updated_ts = unix_timestamp()
          where a.sp_pack_id = sp_pack_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END


CREATE PROCEDURE `ota_version_set_status`(
     ver_id int (11)
    ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;

    update ota_version a set
          a.status = 1,
          a.updated_ts = unix_timestamp()
          where a.ver_id = ver_id;

    if _err=1 then
      set ret = 0;
    else
      set ret = 1;
    end if;
END

ota_version_select_one_by_ver_name
CREATE PROCEDURE `ota_version_select_one_by_ver_name`(
    ver_name VARCHAR (128)
)
BEGIN
	  SELECT a.* FROM ota_version a where a.ver_name = ver_name;
END

CREATE PROCEDURE `ota_gray_group_select_one_by_sn`(
    sn VARCHAR (128)
)
BEGIN
	  SELECT a.*,b.sn FROM ota_gray_group a LEFT JOIN ota_group_sn b ON a.group_id=b.group_id  where b.sn = sn ;
END

CREATE PROCEDURE `ota_diff_package_by_from_ver_id`(
    from_ver_id INT (11)
    ,lang VARCHAR (50)
)
BEGIN
	  SELECT a.* FROM ota_diff_package a  where a.from_ver_id = from_ver_id and a.lang = lang ;
END

CREATE PROCEDURE `ota_diff_package_file_by_pack_id`(
    sp_pack_id INT (11)
)
BEGIN
	  SELECT b.file_size,b.file_download_uri,b.md5sum FROM ota_diff_package_file b  where b.sp_pack_id = sp_pack_id ;
END



CREATE PROCEDURE `ota_diff_package_info_by_sp_pack_id`(
    sp_pack_id INT (11)
)
BEGIN
	    SELECT a.auto_download,a.force_update as necessary,a.alt_style,a.fullupdate as entirety,b.startVer,c.endVer,d.pro_name from diff_package a
      LEFT JOIN (select ver_id,pro_id,ver_name as startVer FROM version ) as b on b.ver_id=a.from_ver_id
      LEFT JOIN (select ver_id,pro_id,ver_name as endVer FROM version ) as c on c.ver_id = a.to_ver_id
      LEFT JOIN (select pro_id,pro_name FROM product ) as d on c.pro_id = d.pro_id  and b.pro_id = d.pro_id
      where a.sp_pack_id =  sp_pack_id;
END



CREATE PROCEDURE `ota_package_version_select_one_by_ver_id`(
    to_ver_id INT (11)
)
BEGIN
	  SELECT a.* FROM diff_package_file a  where a.to_ver_id = to_ver_id ;
END



CREATE PROCEDURE `ota_exist_product_version_by_pro_id`(
    pro_id int (11)
)
BEGIN
	  SELECT a.* FROM version a  where a.pro_id = pro_id and a.status <> -1;
END

CREATE PROCEDURE `ota_version_select_all`(
)
BEGIN
	  SELECT a.* FROM version a  where  a.status <> -1;
END

CREATE PROCEDURE `ota_exist_version_by_pro_id`(
    pro_id int (11)
    ,ver_name VARCHAR (128)
)
BEGIN
	  SELECT a.* FROM version a  where a.ver_name = ver_name and a.pro_id = pro_id and a.status <> -1;
END


CREATE PROCEDURE `ota_exist_version_by_ver_id`(
    ver_id int (11)
    ,ver_name VARCHAR (128)
)
BEGIN
    if(ver_name <> '' && ver_id <> '' ) THEN
			SELECT a.* FROM version a  where a.ver_name = ver_name and a.ver_id = ver_id and a.status <> -1;
		END IF;
END

CREATE PROCEDURE `ota_check_version_update`(
    product_code VARCHAR (128)
    ,version VARCHAR (128)
    ,sn VARCHAR (128)
    ,lang VARCHAR (50)
    ,country VARCHAR (50)
    ,OUT ret int (1)
)
BEGIN

	  --  先检查产品code 存在否
	  -- 通过pro_id & version 查看对应版本是不是存在
	  -- 通过起始版本&语言 查看对应的差分包是否存在，只检查最新的已发布的差分包
	  -- 通过差分包状态检查传递的sn 状态，如果在灰度测试中，则检查SN是不是在对应的灰度组里，若已发布，则不用检查SN
    DECLARE pro_id INT(11);
    DECLARE ver_id INT(11);
    DECLARE sp_pack_id INT(11);
    DECLARE pack_status INT(1);
    DECLARE gn_id INT(11);
		SET @pro_id = 0;
		SET @ver_id = 0;
		SET @sp_pack_id = 0;
		SET @pack_status = 0;
		SET @gn_id = 0;
		SET @ret = 0;

    SELECT a.pro_id INTO @pro_id FROM ota_product a where a.pro_code = product_code;
    -- 100001 产品CODE有问题
		IF @pro_id = 0 THEN
			SET @ret ='100001';
		end IF;
		-- 100002 产品版本名称不存在
		if (@pro_id > 0) then
		  SELECT a.ver_id INTO @ver_id FROM ota_version a where a.pro_id = @pro_id and a.ver_name= version;
      IF @ver_id = 0 THEN
        SET @ret ='100002';
      end IF;
		end IF;

    -- 检查已发布的差分包
    if (@ver_id > 0) then
        SELECT a.sp_pack_id,a.status into @sp_pack_id,@pack_status FROM ota_diff_package a  where a.from_ver_id = @ver_id and a.lang = lang group by a.from_ver_id ORDER by a.created_ts DESC LIMIT 1;
        IF @sp_pack_id = 0 THEN
          SET @ret ='100003'; -- 该版本的语言包不存在。
        end IF;
    end IF;

    -- 检查差分包状态 来判断是不是要检查SN 如果有差分包且状态在测试中 则验证SN 否则不用验证SN
    if( @sp_pack_id > 0 && @pack_status = 1) THEN
        SELECT b.gn_id INTO @gn_id FROM ota_diff_package_group a left JOIN ota_group_sn b on a.group_id=b.group_id where a.sp_pack_id = @sp_pack_id and b.sn=sn;
        IF @gn_id = 0 THEN
          SET @ret ='100004'; -- 灰度测试SN不存在。
        end IF;

    END if;
		-- 已发布 版本文件
		if(@sp_pack_id > 0 && @pack_status = 3) THEN
        -- 返回对应sp_pack_id infos
        SELECT a.sp_pack_id,b.file_size,b.file_download_uri,b.md5sum FROM ota_diff_package_file a left JOIN ota_diff_package_file b on a.sp_pack_id =b.sp_pack_id where b.sp_pack_id = @sp_pack_id ;
		END if;


END






-- ota 操作记录

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sp_pack_id` int(11) DEFAULT 0 COMMENT '差分包ID',
  `product_code` VARCHAR (128)  DEFAULT '' COMMENT '产品CODE',
  `sn` VARCHAR (128)  DEFAULT ''  COMMENT 'SN码',
  `content` text (255)  COMMENT '内容',
  `created_ts` int(11) default 0 comment '数据创建时间',
  `updated_ts` int(11) default 0 comment '数据更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_sp_pack_id` (`sp_pack_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='OTA操作日志';


