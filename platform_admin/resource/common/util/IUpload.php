<?php
/**
 * @file upload_class.php
 * @brief 文件上传处理
 * @author localuser1
 * @version 5.1
 */
namespace app\common\util;
use Yii;
/**
 * @class IUpload
 * @brief 文件上传类
 */
class IUpload
{
	//允许上传附件类型
	private $allowType = array('jpg','gif','png','zip','rar','docx','doc','bmp','swf','flv','doc','docx','xls','xlsx');

	//需要检测木马的文件类型
	private $checkType = array('jpg','gif','png');

	//附件存放物理目录
	private $dir = 'upload';

	//最大允许文件大小，单位为B(字节)
    private $maxsize;

    //伪造upload提交
    public $isForge = false;

    /**
     * @brief 构造函数
     * @param Int   $size 允许最大上传KB数
     * @param Array $type 允许上传的类型
     */
    function __construct($size = 10000,$type = array())
    {
    	//设置附件上传类型
    	if($type)
    	{
    		$this->allowType = $type;
    	}

    	//设置附件上传最大值
    	$iniMaxSize    = $this->getIniPostMaxSize();
    	$uploadMaxSize = $size << 10;
    	$this->maxsize = ($uploadMaxSize <= $iniMaxSize) ? $uploadMaxSize : $iniMaxSize;
    }
    /**
     * @brief 设置上传文件存放目录
     * @param String $dir 文件存放目录
     * @return object $this	 返回当前对象，以支持连贯操作
     */
    public function setDir($dir)
    {
    	if($dir != '' && !is_dir($dir))
    	{
    		IFile::mkdir($dir);
    	}
    	$dir       = strtr($dir,'\\','/');
    	$this->dir = substr($dir,0,-1)=='/' ? $dir : $dir.'/';
    	return $this;
    }
    /**
     * @brief get php.ini minimum post_max_size and upload_max_filesize and website config uploadSize
     */
    public static function getMaxSize()
    {
    	$uploadSize   = ini_get('upload_max_filesize');
    	$postSize     = ini_get('post_max_size');
    	$memory_limit = ini_get('memory_limit');
    	$website      = 10;

		//读取配置文件中的附件容量限制，MB单位
    	if(isset(Yii::$app->params['uploadSize']) && Yii::$app->params)
    	{
    		$website = Yii::$app->params;
    	}
    	return min( floatval($uploadSize) , floatval($postSize),floatval($memory_limit),floatval($website) ).'M';
    }
    /**
     * @brief 获取环境POST数据的最大上传值
     * @return int 最大上传的字节数
     */
    private function getIniPostMaxSize()
    {
    	$maxSize = trim(self::getMaxSize());
	    $unit    = strtolower($maxSize{strlen($maxSize)-1});
	    $maxSize = intval($maxSize);
	    $step    = 0;
	    switch($unit)
	    {
	    	//GB单位
	        case 'g':
	        {
	        	$step = 9;
	        }
	        break;

			//MB单位
	        case 'm':
	        {
	        	$step = 6;
	        }
	        break;

			//KB单位
	        case 'k':
	        default:
	        {
	        	$step = 3;
	        }
	        break;
	    }
	    return str_pad($maxSize,strlen($maxSize)+$step,"0");
    }

    /**
     * @brier 设置需要做HEX检查的文件类型
     * @param string|array|boolean $type 需要做HEX检查的文件类型
     * @return object $this 返回当前对象，以支持连贯操作
     */
    public function setCheckFileType($type)
    {
    	if($type === false)
    	{
    		$this->checkType = array();
    	}
    	elseif(is_string($type))
    	{
    		$this->checkType = array($type);
    	}
    	elseif(is_array($type))
    	{
    		$this->checkType = $type;
    	}
    	return $this;
    }
    /**
     * @brief show code message
     * @param sring $code code
     * @return string
     */
    public static function errorMessage($code)
    {
    	$codeMessage = array(
			'-1'=>'上传的文件超出服务器限制',
			'-2'=>'上传的文件超出浏览器限制',
			'-3'=>'上传的文件被部分上传',
			'-4'=>'没有找到上传的文件',
			'-5'=>'上传的文件丢失',
			'-6'=>'上传的临时文件没有正确写入',
			'-7'=>'扩展名不允许上传',
			'-8'=>'上传的文件超出了网站限制',
			'-9'=>'上传的文件中有木马病毒',
    		'-10'=>'上传的文件无法移动',
    		'-11'=>'第三方上传操作失败',
    		'-12'=>'非法POST上传',
			'1' =>'上传成功'
		);
		return isset($codeMessage[$code]) ? $codeMessage[$code] : '未知错误';
    }
    /**
     * @brief  开始执行上传
     * @return array 包含上传成功信息的数组
     *		$file = array(
	 *			 name    如果上传成功，则返回上传后的文件名称，如果失败，则返回客户端名称
	 *			 size    上传附件大小
	 *           fileSrc 上传文件完整路径
	 *			 dir     上传目录
	 *			 ininame 上传图片名
	 *			 flag    -1:上传的文件超出服务器限制; -2:上传的文件超出浏览器限制; -3:上传的文件被部分上传; -4:没有找到上传的文件; -5:上传的文件丢失;
	 *                   -6:上传的临时文件没有正确写入; -7:扩展名不允许上传; -8:上传的文件超出了程序的限制; -9:上传的文件中有木马病毒 ;
	 *                   -10:上传的文件无法移动; -11:第三方上传操作失败; -12:非法POST上传; 1:上传成功;
	 *			 ext     上传附件扩展名
     *		);
     */
    public function execute()
    {
    	//总的文件上传信息
    	$info = array();
    	$is_forge = $this->getIsForge();

        foreach($_FILES as $field => $file)
        {
            $fileInfo = array();

			//不存在上传的文件名
            if(!isset($file['name']) || $file['name'] == '')
            {
            	continue;
            }

			//统一兼容性处理多个数组,file和file[]
            if(is_string($file['name']))
            {
            	$file = array_map(function($v){return array($v);},$file);
            }

            $keys = array_keys($file['name']);
            foreach($keys as $key)
            {
            	//获取临时文件是否POST上传
            	$isUpload = is_uploaded_file($file['tmp_name'][$key]);

                //获取扩展名
                $fileext = IFile::getFileType($file['tmp_name'][$key]);
                if($file['tmp_name'][$key] && (is_array($fileext) || $fileext == null))
                {
                    $fileext = IFile::getFileSuffix($file['name'][$key]);
                }

            	//1,上传出现错误
            	if(isset($file['error'][$key]) && $file['error'][$key] != 0)
            	{
            		$fileInfo[$key]['flag'] = 0 - $file['error'][$key];
            	}
            	//2,如果此文件非通过POST方式上传的则禁止
            	else if($is_forge == false && $isUpload == false)
            	{
            		$fileInfo[$key]['flag'] = -12;
            	}
            	//3,图片木马检测
            	else if(in_array($fileext,$this->checkType) && !IFilter::checkHex($file['tmp_name'][$key]))
            	{
            		$fileInfo[$key]['flag'] = -9;
            	}
            	//4,上传类型不符合
            	else if(!in_array($fileext,$this->allowType))
            	{
            		$fileInfo[$key]['flag'] = -7;
            	}
            	//5,上传大小不符合
            	else if($file['size'][$key] > $this->maxsize)
            	{
            		$fileInfo[$key]['flag'] = -8;
            	}
            	//成功情况
            	else
            	{
                    $directory = DOCROOT . DIRECTORY_SEPARATOR . $this->dir;
                    if (! is_dir($directory)) {
                        IFile::mkdir($directory);
                    }

	                //修改图片状态值
	                $fileInfo[$key]['name']    = ITime::getDateTime('Ymdhis').mt_rand(100,999).'.'.$fileext;

                    $filename = realpath($directory).DIRECTORY_SEPARATOR.$fileInfo[$key]['name'];

	                $fileInfo[$key]['dir']     = $this->dir;
	                $fileInfo[$key]['size']    = $file['size'][$key];
	                $fileInfo[$key]['ininame'] = $file['name'][$key];
	                $fileInfo[$key]['ext']     = $fileext;
	                $fileInfo[$key]['fileSrc'] = $filename;
//	                $fileInfo[$key]['fileSrc'] = $fileInfo[$key]['dir'].$fileInfo[$key]['name'];
	                $fileInfo[$key]['flag']    = 1;

	                //本地上传
	                if($isUpload == true)
	                {
	                	IFile::mkdir($this->dir);
	                	$upload_result = move_uploaded_file($file['tmp_name'][$key],$fileInfo[$key]['fileSrc']);
	                }
	                else
	                {
	                	$upload_result = IFile::xcopy($file['tmp_name'][$key],$fileInfo[$key]['fileSrc']);
	                }

	                if (true != $upload_result)
	                {
	                	$fileInfo[$key]['flag'] = -10;
	                }
            	}

            	//处理错误信息提示
            	$fileInfo[$key]['error'] = self::errorMessage($fileInfo[$key]['flag']);
            }

            $info[$field] = $fileInfo;
        }
        return $info;
    }

    /**
     * @brief 设置是否伪造upload提交
     * @param boolean $is_forge 是否伪造upload提交
     */
    public function setIsForge($is_forge)
    {
    	$this->isForge = $is_forge;
    }

    /**
     * @brief 获取是否伪造upload提交
     * @return boolean
     */
    public function getIsForge()
    {
    	return $this->isForge;
    }
}