<?php
namespace common;

class ErrorCode {
	//外部error code
	const SUCCEED = 0;
	
	/** 异常 */
	const ERROR = -1;

	//移动文件失败
	const FAILED_TO_MOVE_FILE = 90001;

	//未知的mimetype
    const MIMETYPE_UNKNOWN = 90002;

    //mimetype与文件后缀不匹配
    const MIMETYPE_NOT_MATCHED = 90003;

    //数据库运行错误或者sql错误等
    const BAD_DB_EXEC = 90004;

    //参数错误
    const BAD_PARAMS = 90005;

    //数据不存在
    const NON_EXISTENT_INFORMATION = 90006;

    //mimetype与文件后缀不匹配
    const LACK_PARAMS = 90010;

    //只有待审核状态才能审批
    const BAD_STATUS = 90050;

    //厂商缩写未设置
    const INVALID_MANUFACTURE_SHORT = 90051;

    //大类缩写未设置
    const INVALID_CATEGORY_SHORT = 90052;

    //类型缩写未设置
    const INVALID_TYPE_SHORT = 90053;

    //缩写重复
    const REPEAT_SHORT = 90054;

    //长度不符合要求
    const INCONSISTENT_LENGTH = 90055;

    //两次输入密码不一致
    const TWO_INCONSISTENT_PASSWORDS = 90056;

    //超出数量限制
    const MORE_THAN_LIMIT = 90057;


}