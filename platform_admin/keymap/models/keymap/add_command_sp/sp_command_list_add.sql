/*
 * category add
 * @param category_id
 * @param name
 * @param name_en
 * @param description
 * @param add_time
 * @param is_deleted
 * @param ret
 * @return
 */
CREATE PROCEDURE `sp_km_keymap_datas_list_add`(
    command_list varchar(5000)
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;
  START TRANSACTION;
    INSERT INTO keymap_data values command_list

  if _err=1 then
    set ret = 0;
      ROLLBACK;
  else
    set ret = 1;
     COMMIT;
  end if;
END
/*
 * category delete
 * @param category_id
 * @param ret
 * @return
 */
CREATE PROCEDURE `sp_category_delete`(
    category_id varchar(32)
    ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;
  if(not exists( select id from category where category.id = category_id and category.is_deleted = 0 ))
  then
    set ret = 200102;
  else
    UPDATE category
    SET category.is_deleted = 1
    WHERE category.id = category_id;
  if _err=1 then
    set ret = 0;
  else
    set ret = 1;
  end if;
  end if;
END
/*
 * one category
 * @param category_id
 * @param ret
 * @return
 */
CREATE PROCEDURE `sp_category_select_one`(
    category_id varchar(32)
    ,OUT ret int
)
BEGIN
	    select category.id
            ,category.name
            ,category.name_en
            ,category.description
            ,category.add_time
            ,category.update_time
            ,category.is_deleted
            ,category.key
            ,category.code
      from category
	    where category.id = category_id;
END
/*
 * all category
 * @param start_number
 * @param page_size
 * @param name_en_val
 * @param is_deleted_val
 * @return
 */
CREATE PROCEDURE `sp_category_select_all`(
    start_number varchar(32)
    ,page_size varchar(32)
    ,name_en_val varchar(255)
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
    SET @tempsql=CONCAT(@tempsql,' order by is_deleted asc,add_time desc');
    if(start_number<>'' and page_size <>'')
      THEN
        SET @tempsql=CONCAT(@tempsql,' limit ',@start_number,',',@page_size);
  END IF;
    prepare stmt from @tempsql;
    EXECUTE stmt;
END

/*
 * category update
 * @param category_id
 * @param name
 * @param name_en
 * @param description
 * @param ret
 * @return
 */
CREATE PROCEDURE `sp_category_edit`(
    category_id varchar(32)
    ,name varchar(128)
    ,name_en varchar(128)
    ,description text
    ,key_ varchar(32)
    ,code_ varchar(4)
    ,OUT ret int
)
BEGIN
  declare _err int default 0;
  declare continue handler for sqlexception,sqlwarning,not found set _err=1;
  if( exists( select id from category where category.name_en = name_en and category.id != category_id) )
  then
    set ret = 200101;
  elseif(exists(select id from category where category.key = key_ and category.id != category_id))
    then
    set ret = 200103;
  elseif(exists(select id from category where category.code = code_ and category.id != category_id))
    then
    set ret = 200104;
  else
    UPDATE category
    SET
      category.name = name
      ,category.name_en = name_en
      ,category.description = description
      ,category.update_time = now()
      ,category.key = key_
      ,category.code = code_
    WHERE category.id = category_id;
  if _err=1 then
    set ret = 0;
  else
    set ret = 1;
  end if;
  end if;
END





-------------------------------ERROR CODE-----------------------------------------------------------
200101 name_en已存在
200102 category不存在
200103 key已存在
200104 code已存在




