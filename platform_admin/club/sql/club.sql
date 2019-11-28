



DELIMITER ;;
CREATE PROCEDURE `club_client_by_client_id`(
    client_id VARCHAR (32)
)
BEGIN
    select * from `club_client` a where a.client_id = client_id ;
END



DELIMITER ;;
CREATE PROCEDURE `club_client_by_client_name`(
client_name VARCHAR (32)
)
BEGIN
select * from `club_client` a where a.client_name = client_name;
END

DELIMITER ;;
CREATE  PROCEDURE `club_client_add`(
    client_name VARCHAR (128)
    ,client_id VARCHAR (32)
    ,status int(1)
    ,out ret int
)
BEGIN
declare exit handler for sqlexception rollback;

    start transaction;

        INSERT INTO `club_client`
        (`client_name`,`client_id`,`status`
        ,`created_ts`,`updated_ts`
        )
        VALUES
        (client_name,client_id,status
        ,unix_timestamp(),unix_timestamp()
        );
        set ret = last_insert_id();

    COMMIT ;
END




DELIMITER ;;
CREATE PROCEDURE `club_client_list_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,client_name varchar(128)
)
BEGIN
    set @client_name = client_name;
    set @start_number = start_number;
    set @page_size = page_size;
    set @tempWhere = ' WHERE 1=1 and a.status =1 ';

    set @tempsql = 'select a.* from club_client a  ';

    if(client_name <> '')
    then
    set @tempWhere=CONCAT(@tempWhere,' and a.client_name = ',@client_name);
    end if;



    -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
    from club_client a ' ,@tempWhere);
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

club_video_list_select_all


DELIMITER ;;
CREATE PROCEDURE `club_client_select_one_by_id`(
    id int (11)
)
BEGIN
    select * from `club_client` a where a.id = id ;
END

DELIMITER ;;
CREATE PROCEDURE `club_client_update`(
    id int (11),
    client_id VARCHAR (128),
    client_name VARCHAR(128),
    status int(1),
    ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;
    update club_client a set
        a.client_id = client_id,
        a.client_name = client_name,
        a.status = status,
        a.updated_ts = unix_timestamp()
    where a.id = id;
    if _err=1 then
        set ret = 0;
    else
        set ret = 1;
    end if;
END



DELIMITER ;;
CREATE PROCEDURE `club_client_delete_one`(
    client_id varchar(128)
)
BEGIN
    delete from `club_client` a where a.client_id = client_id ;
END


DELIMITER ;;
CREATE PROCEDURE `club_config_del`(
    id varchar(11)
)
BEGIN
    delete from `club_config` a where a.id = id ;
END


DELIMITER ;;
CREATE PROCEDURE `club_type_select_all`(
)
BEGIN
    select * from `club_type` a where a.type <> '' ;
END


DELIMITER ;;
CREATE PROCEDURE `club_client_select_all`(
)
BEGIN
    select * from `club_client` a where a.client_id <> '' and a.status = 1 ;
END



DELIMITER ;;
CREATE PROCEDURE `club_config_list_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,client_id varchar(128)
)
BEGIN
    set @client_id = client_id;
    set @start_number = start_number;
    set @page_size = page_size;
    set @tempWhere = ' WHERE 1=1 ';

    set @tempsql = 'select a.* from club_config a  ';

    if(client_id <> '')
    then
      set @tempWhere=CONCAT(@tempWhere,' and a.client_id = ',@client_id);
    end if;



    -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
    from club_config a ' ,@tempWhere);
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



DELIMITER ;;
CREATE PROCEDURE `club_config_type_add`(
    client_id varchar(128)
    ,type varchar(128)
    ,out ret int
)
BEGIN
	  set ret = 0;

	  if(EXISTS (SELECT * FROM club_config WHERE club_config.`client_id` = client_id and club_config.`type` =type   )) THEN
	  -- 存在
	  SET ret = -1;

	  ELSE
        insert into `club_config`
        (
          `client_id`
          ,`type`
					,`config_status`
          ,`created_ts`
          ,`updated_ts`
        )
        values
        (
          client_id
          ,type
					,1
          ,unix_timestamp()
          ,unix_timestamp()
        );
        set ret = last_insert_id();
    END if;
END



DELIMITER ;;
CREATE PROCEDURE `club_config_by_client_id`(
    client_id varchar(128)
)
BEGIN
    select * from `club_config` a where a.client_id = client_id and a.type <> '' ;
END


ALTER TABLE `like` add COLUMN `openid` VARCHAR (128) NOT NULL AFTER `user_id`, ADD COLUMN `nickname` VARCHAR (128) not NULL AFTER `openid`;
ALTER TABLE `favorite` add COLUMN `openid` VARCHAR (128) NOT NULL AFTER `user_id`, ADD COLUMN `nickname` VARCHAR (128) not NULL AFTER `openid`;



/*视频*/

CREATE PROCEDURE `club_video_list_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,status varchar (1)
)
BEGIN
    set @is_recommended = is_recommended;
    set @start_number = start_number;
    set @page_size = page_size;
    set @tempWhere = ' WHERE 1=1 ';

    set @tempsql = 'select a.* from video a  ';

    if(status <> '')
    then
    set @tempWhere=CONCAT(@tempWhere,' and a.status = ',@status);
    end if;



    -- 得到总记录数
    set @tmpcount = CONCAT('select count(1) into @rowCount
    from video a ' ,@tempWhere);
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


CREATE PROCEDURE `club_video_by_video_id`(
    video_id int(11)
)
BEGIN
    select * from `video` a where a.id = video_id;
END





CREATE PROCEDURE `club_video_set_recommend`(
    video_id int (11)
    ,is_recommended int (1)
    ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;

    update video a set
        a.is_recommended = is_recommended,
        a.updated_ts = unix_timestamp()
    where a.id = video_id;

    if _err=1 then
        set ret = 0;
    else
        set ret = 1;
    end if;
END



CREATE PROCEDURE `club_video_del`(
    video_id int (11),
    ,OUT ret int
)
BEGIN
    declare _err int default 0;
    declare continue handler for sqlexception,sqlwarning,not found set _err=1;
    update video a set
        a.status = -1,
        a.updated_ts = unix_timestamp()
    where a.id = video_id;
    if _err=1 then
        set ret = 0;
    else
        set ret = 1;
    end if;
END