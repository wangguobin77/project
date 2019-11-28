<?php
namespace common\library\resource;

/**
 * 附件素材类
 */
class Generic extends BaseResource
{
	public $file_type = parent::TYPE_GENERIC;
	
	/**
	 * 删除素材
	 */
	public static function delete($res_id)
	{
		parent::delete($res_id);
	}
	
	/**
	 * 上传附件
	 */
	public function save($fileData, $path="")
	{
		//检查文件合法性
		$ret = $this->validateFile($fileData['tmp_name'], $fileData['name']);
		if ($ret->code != 0)
			return $ret;
		
		return parent::save($fileData, $path);
	}
	
}
