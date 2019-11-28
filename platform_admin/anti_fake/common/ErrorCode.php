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

    //参数错误
    const LACK_PARAMS = 90010;

    //没有检测到上传文件
    const NO_MATCHED_UPLOADER = 90011;
}