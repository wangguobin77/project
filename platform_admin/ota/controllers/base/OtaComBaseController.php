<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/4/24
 * Time: 上午11:14
 */

namespace app\controllers\base;

use yii;
use app\models\db\DiffPackageGroup;
use app\models\db\Product;
use app\models\db\Version;
use app\models\db\DiffPackage;
use app\models\db\DiffPackageFile;
class OtaComBaseController extends BaseController
{

    protected $redis = null;
    public function init()
    {
        $this->redis = Yii::$app->redis_3;
        $this->layout = false;
        $this->enableCsrfValidation = false;
    }


    /**
     * 判断是否可添加包的条件
     * 差分包
     * 对比开始版本 与 结束版本的创建时间 开始不能大于结束版本创建时间
     * @param $from_ver_add_time
     * @param $to_ver_add_time
     * @return bool
     */
    protected function isCheckAddVer($from_ver_add_time,$to_ver_add_time)
    {
        if(intval($from_ver_add_time) < intval($to_ver_add_time)) return true;//开始包创建时间，不能比结束包创建时间大

        show_json(100000,'开始版本必须低于结束版本');
    }

    /**
     * 整包 添加条件
     * 整包下只能添加一次 不能重复添加
     * @param $pro_code
     * @param $ver_id
     */
    protected function isCheckFullAddVer($pro_code,$ver_id)
    {
        $key = md5($pro_code.$ver_id);

        $info = DiffPackage::find()->where(['b_ver_id'=>$key])->asArray()->one();

        if($info) show_json(100000,'整包下数据已经存在,不可重复添加');

        return true;
    }


    /**
     * 接口返回数据格式
     * @param $pack_info
     * @return array
     */
    protected function otaJsonData($pack_info)
    {
        $ver_list = $this->getDisVerNameList();

        $json = array(
            'auto_download' => $pack_info['auto_download'],
            'alt_style' => $pack_info['alt_style'],
            'startVer' => $ver_list[$pack_info['from_ver_id']],
            'endVer' => $ver_list[$pack_info['to_ver_id']],
            'entirety' => $pack_info['fullupdate'],
            'url' => '',
            'necessary' => $pack_info['force_update'],
            'release_note' => base64_encode($pack_info['description']),
        );

        return $json;
    }

    /**
     * redis
     * 处理同步数据信息
     * @param $pro_id
     * @param $pro_code
     * @param $packageInfo
     */
    protected function disSortingVerInfo($pro_id,$pro_code,$packageInfo)
    {
        //处理升级包 与 灰度组包缓存数据
        $ver_list = Version::find()->where(['pro_id'=>$pro_id])->orderBy("created_ts desc")->asArray()->all();//获取该本版下所有版本包，逆序

        if(!$ver_list) return;

        $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表

        $this->addVerNameToCache($ver_list,$pro_code);//只要满足一个条件 需要将版本压入版本列表
        foreach ($ver_list as $key=>$val){

            $version_package_type = $val['is_full'] == 1?1:2 ;//1整包 0差分包

            if($packageInfo['type'] == 1){//整包

                //注意差分包与整包处理机制不一样 整包需要处理更具时间逆序处理最新的一条
                $package_one = DiffPackage::find()->where(['b_ver_id'=>md5($pro_code.$val['ver_id'])])->asArray()->one();//整包 每个版本下只会有一个包

                if(!$package_one) continue;

                $info = $this->otaJsonData($package_one);

                if($package_one['status'] == 1 || $package_one['status'] == 2){//灰度测试使用更新信息

                    $info['is_group_gray'] = 1;//灰度版本
                    $info['files'] = $this->disFilesInfo($GLOBALS['filePath']);

                    $this->setGrayFullPackageFromRedis($pro_code,$info);//ota 接口信息存储

                    $this->setPromptApiInfoFromRedis($pro_code,1,$version_package_type);
                    break;//终止循环;
                }elseif ($package_one['status'] == 3){//正式版本

                    $info['is_group_gray'] = 0;//灰度版本
                    $info['files'] = $this->disFilesInfo($GLOBALS['filePath']);

                    $this->setPromptApiInfoFromRedis($pro_code,2,$version_package_type);

                    $this->setFormalFullPackageFromRedis($pro_code,$info);//ota接口信息存储


                    //todo 发布正式版本 需要清空灰度组信息
                    //$this->delAllFullPackageSnWriteFromRedis($pro_code,$ver_name_list[$package_one['to_ver_id']]);//当前的ver_name 对于整包而言是to_ver_id

                    break;//终止循环;
                }else{
                    //0处于未测试或者发布状态的包，无效  -1 代表禁用所以需要找到可用的包
                    continue;
                }

            }else if($packageInfo['type'] == 2){//差分包

                //注意差分包与整包处理机制不一样 差分包存在多条记录只能根据 sp_pack_id 处理包数据
                $package_one = DiffPackage::find()->where(['sp_pack_id'=>$packageInfo['sp_pack_id']])->asArray()->one();//差分包会存在多个包

                if(!$package_one) continue;

                $info = $this->otaJsonData($package_one);

                if($package_one['status'] == 1 || $package_one['status'] == 2){//灰度测试使用更新信息

                    $info['is_group_gray'] = 1;//灰度版本
                    $info['files'] = $this->disFilesInfo($GLOBALS['filePath']);

                    $this->setPromptApiInfoFromRedis($pro_code,1,$version_package_type);

                   /* $this->setGrayDiffPackageInfoFromRedis($pro_code,$val['ver_name'],$info);//ota接口信息存储*/
                    $this->setGrayDiffPackageInfoFromRedis($pro_code,$ver_name_list[$package_one['from_ver_id']],$info);//ota接口信息存储
                    break;//终止循环;
                }elseif ($package_one['status'] == 3){//正式版本

                    $info['is_group_gray'] = 0;//灰度版本
                    $info['files'] = $this->disFilesInfo($GLOBALS['filePath']);

                    $this->setPromptApiInfoFromRedis($pro_code,2,$version_package_type);

                   /* $this->setDiffPackageInfoFromRedis($pro_code,$val['ver_name'],$info);//ota接口信息存储*/
                    $this->setDiffPackageInfoFromRedis($pro_code,$ver_name_list[$package_one['from_ver_id']],$info);//ota接口信息存储

                    //todo 当前包发布上线的时候需要将灰度组信息清空
                   // $this->delAllXpackageSnWriteFromRedis($pro_code,$ver_name_list[$package_one['from_ver_id']],$ver_name_list[$package_one['to_ver_id']]);
                    break;//终止循环;
                }else{
                    //0处于未测试或者发布状态的包，无效  -1 代表禁用所以需要找到可用的包
                    //注意差分包只能检测一次 不能往下面寻找
                    break;
                }

            }else{
                return;
            }



        }


    }

    /**
     * 设置唯一的id 建立每个版本号与包的对应关系， 版本号下重复的名称不能添加包
     * @param $pro_code
     * @param $ver_id
     * @return string
     */
    protected function setonlyPackId($pro_code,$ver_id)
    {
        if(!$pro_code)show_json(100000,'添加包文件时产品名称不能为空');
        if(!$ver_id)show_json(100000,'缺少版本id');

        return md5($pro_code.$ver_id);
    }

    /**
     * 处理目录下所有文件的格式返回
     * @param $pathInfo
     * @return array
     */
    protected function disFilesInfo($pathInfo,$sub_path='')
    {
        $data = [];
        if(count($pathInfo) <= 0) return $data;

        foreach ($pathInfo as $key=>$val){

            $n = [];
            $n['filePath'] = str_replace('/data',Yii::$app->params['static_ota_json_url'],$val[0]);//文件地址
            if($sub_path){
                $n['filePath_a'] = substr($val[0],strlen($sub_path));
            }
            $n['file_size'] = $val[1];//文件大小
            $n['md5sum'] = md5(file_get_contents($val[0]));

            array_push($data,$n);//
        }

        return $data;
    }

    /**
     * 提前将版本压入缓存列表
     * @param $ver_list
     * @param $pro_code
     */
    protected function addVerNameToCache($ver_list,$pro_code)
    {
        if(!$ver_list) return;

        //先删除 在重新压入
        $this->delAllVerName($pro_code);
        foreach ($ver_list as $k=>$v){
            //2019-06-18
            if($v['status'] == -1) continue;//禁用版本跳过
            $this->isCheckVernameInList($pro_code,$v['ver_name']);//只要满足一个条件 需要将版本压入版本列表
        }

    }

    /**
     * 过滤新增版本下不存在包的
     * @param $ver_id
     * @return bool
     */
    protected function filterVersion($ver_id)
    {
        $data = DiffPackage::find()->where(['to_ver_id'=>$ver_id])->asArray()->all();

        if($data) return true;//

        return false;

    }

    /**
     * 有新的升级包 或者 灰度组包需要更新接口提示信息
     * @param $pro_code
     * @param $type 包的类型 1整包  2 差分包
     * @param $status 1=>灰度组  2=>正式
     */
    protected function setPromptApiInfoFromRedis($pro_code,$status,$type)
    {
        $key = 'ota:prompt:'.$pro_code;

        $data = $this->getPromptApiInfoFromRedis($key);

        /**
         * 注意：没次包的发布 都会先是线下测试 更新线下包类型，上线发布的时候需要清空线下的包类型，更新线上包类型
         */
        if($status == 2){//如果将灰度组包升级到最新版本  需要将接口提示灰度信息设置为空
            $data['is_check_new_gray_group_package'] = 0;// 1存在最新升级包 0不存在
            $data['offline_version_type'] = $type;//线下
            $data['online_version_type'] = $type;//线上
        }else{//
            $data['is_check_new_gray_group_package'] = 1;//1存在最新灰度组。0不存在
            $data['offline_version_type'] = $type;//线下
        }

        $this->redis->set($key,json_encode($data));
    }

    /**
     * 获取更新提示信息
     * @param $key
     * @return array|mixed
     */
    protected function getPromptApiInfoFromRedis($key)
    {

        $res = $this->redis->get($key);

        if($res) return json_decode($res,true);

        return [
            //'is_check_new_release_package' =>0, // 1存在最新升级包 0不存在
            'is_check_new_gray_group_package' =>0, //1存在最新灰度组。0不存在
            'online_version_type' =>0,//1整包 2差分包  初始0  线上 在线的包
            'offline_version_type' =>0,//1整包 2差分包  初始0  线下的包
        ];
    }

    /**
     * 检测当前版本是否在列表中 不存在需要压入  存在则跳过
     * @param $pro_code
     * @param $ver_name
     */
    protected function isCheckVernameInList($pro_code,$ver_name)
    {
        $key = 'ota:ver_name_list:'.$pro_code;

        $ver_list = $this->redis->lrange($key,0,-1);//获取版本列表

        if(!$ver_list || !is_array($ver_list)){
            $this->setVerNameFromRedis($pro_code,$ver_name);//只要满足一个条件 需要将版本压入版本列表
            return ;
        }

        if(!in_array($ver_name,$ver_list)){
            $this->setVerNameFromRedis($pro_code,$ver_name);//只要满足一个条件 需要将版本压入版本列表
            return ;
        }
    }

    /**
     * 整包
     * 灰度包 数据更新存储
     * @param $pro_code
     * @param $data
     */
    protected function setGrayFullPackageFromRedis($pro_code,$data)
    {
        $key = 'ota:gray_package_info:'.$pro_code;

        $this->redis->set($key,json_encode($data));
    }

    /**
     * 整包
     * 正式 对外发布 数据更新存储
     * @param $pro_code
     * @param $data
     */
    protected function setFormalFullPackageFromRedis($pro_code,$data)
    {
        $key = 'ota:formal_package_info:'.$pro_code;

        $this->redis->set($key,json_encode($data));
    }


    /**
     * 正式版
     * 差分包
     * 设置差分包存储
     */
    protected function setDiffPackageInfoFromRedis($pro_code,$ver_name,$packageInfo)
    {
        $key = 'ota:hash:'.$pro_code;

        $field = $ver_name;

        $this->redis->hset($key,$field,json_encode($packageInfo));//使用hash 格式存储差分包数据
    }

    /**
     * 灰度测试版
     * 差分包
     * 设置差分包存储
     */
    protected function setGrayDiffPackageInfoFromRedis($pro_code,$ver_name,$packageInfo)
    {
        $key = 'ota:gray_hash:'.$pro_code;

        $field = $ver_name;

        $this->redis->hset($key,$field,json_encode($packageInfo));//使用hash 格式存储差分包数据
    }

    /**
     * 整包
     * 添加某个版本下的包 成功后，需要将当前的版本压入队列
     * ota接口请求时只需 判断版本名称是否在当前列表中
     * @param $pro_code
     * @param $ver_name
     * @return bool
     */
    protected function setVerNameFromRedis($pro_code,$ver_name)
    {
        if(!$ver_name) return true;

        $key = 'ota:ver_name_list:'.$pro_code;

        //因为得到的数据列表 最新的版本排在第一个所以需要在左侧压入
        $this->redis->rpush($key,$ver_name);//如果版本下已经存在包，需要将版本名称压入队列 用作ota 整包检测升级接口使用
    }


    /**
     * 删除缓存的版本列表 重新压入
     * @param $pro_code
     */
    protected function delAllVerName($pro_code)
    {
        $key = 'ota:ver_name_list:'.$pro_code;

        $this->redis->del($key);//删除全部数据
    }


    /**
     * 差分包
     * 当禁用差分包某个版本时需要删除hash中值
     * @param $pro_code
     * @param $ver_name
     */
    protected function delDiffPackageInfoFromRedis($pro_code,$ver_name)
    {
        $this->redis->hdel($pro_code, $ver_name);
    }

    /**
     * 整包操作
     * 当包警用状态时候需要将当前的可使用的列表中清除掉该版本
     */
    protected function delVerNameFromRedis($pro_code,$ver_name)
    {
        $key = 'ota:ver_name_list:'.$pro_code;

        $data = $this->redis->lrange($key,0,-1);//获取全部数据

        if(!$data) return [];

        $new_ver_list = $this->delByValue($data,$ver_name);//过滤掉警用的状态版本

        //从新将剩余的值存入redis
        if(!$new_ver_list){//当新的列表数据为空时，需要删除掉这个健值对
            $this->redis->del($key);
            return ;
        }

        foreach ($new_ver_list as $v)
        {
            /**
             * 注意：这里需要特别说明一下
             * 因为开始设置列表值都是lpush操作，但是为了过滤禁用版本，需要获取全部值，最后压入的值就在数组第一位了，
             * 所以当操作for循环再次压入的话，需要反操作rpush，列表中头尾数据特表重要，所以要保持数据的顺序性
             */
            $this->redis->rpush($key,$v);
        }

        return ;

    }


    /**
     * 过滤数组中指定的值
     * @param $arr
     * @param $value
     * @return mixed
     */
    public function delByValue($arr, $value)
    {
        if (!is_array($arr)) {
            return $arr;
        }
        foreach ($arr as $k => $v) {
            if ($v == $value) {
                unset($arr[$k]);
            }
        }
        return $arr;

    }

    //未用
    protected function getBindGraySnList($bindGrayGroupList)
    {
        //绑定灰度组情况
        $group_id_list = '';//sql 条件拼接 灰度组id
        foreach ($bindGrayGroupList as $k=>$v)
        {
            $group_id_list .= $v['group_id'];

            if(count($bindGrayGroupList)-1 > $k){
                $group_id_list .= ',';
            }
        }


        $sql = "select * from group_sn where group_id in (".$group_id_list.")";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $bind_sn_list     = $command->queryAll();

        return $this->filterSnInfo($bind_sn_list);//将可测试的sn 信息同步到redis

    }


    /**
     * 过滤灰度组sn表信息 只返回sn列表 如 ['123','321']
     * @param $bind_sn_list
     * @return array
     */
    protected function filterSnInfo($bind_sn_list)
    {
        if(!is_array($bind_sn_list)) return [];

        $sn_list = [];
        foreach ($bind_sn_list as $key => $v)
        {
            array_push($sn_list,$v['sn']);
        }

        return $sn_list;
    }

    public function traverse($path = '.') {

        if (!file_exists($path)) {
            show_json(100000,'包目录不存在');
        }
        if(!is_dir($path)) {
            //mkdir($path, 0777, true);
            show_json(100000,'包目录有问题！');
        }


        $GLOBALS['filePath'] = [];
        $current_dir = opendir($path); //opendir()返回一个目录句柄,失败返回false
        while(($file = readdir($current_dir)) !== false) { //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file; //构建子目录路径
            if($file == '.' || $file == '..') {
                continue;
            }else if(is_dir($sub_dir)) { //如果是目录,进行递归

                $this->traverse($sub_dir);
            }else{ //如果是文件,直接输出路径和文件名

                $ori_size = filesize($path . DIRECTORY_SEPARATOR . $file);

                $GLOBALS['filePath'][$path . DIRECTORY_SEPARATOR . $file] = [$path . DIRECTORY_SEPARATOR . $file,$ori_size];//把文件路径赋值给数组
            }
        }

    }

    //2019-06-13 new create
    public function disVerAllPath($path = '.') {

        if (!file_exists($path)) {
            show_json(100000,'包目录不存在');
        }
        if(!is_dir($path)) {
            //mkdir($path, 0777, true);
            show_json(100000,'包目录有问题！');
        }


        $arr = [];
        $current_dir = opendir($path); //opendir()返回一个目录句柄,失败返回false
        while(($file = readdir($current_dir)) !== false) { //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file; //构建子目录路径
            if($file == '.' || $file == '..') {
                continue;
            }else if(is_dir($sub_dir)) { //如果是目录,进行递归

                $this->disVerAllPath($sub_dir);
            }else{ //如果是文件,直接输出路径和文件名

                $ori_size = filesize($path . DIRECTORY_SEPARATOR . $file);

                $arr[$path . DIRECTORY_SEPARATOR . $file] = [$path . DIRECTORY_SEPARATOR . $file,$ori_size];//把文件路径赋值给数组
            }
        }

        return $arr;

    }

    /*************************处理关联 或者 取消关联灰度组 是需要刷新redis sn 信息**********************************/
    //添加
    protected function disAddGrayPackageBindSnInfo($sp_pack_id,$group_id,$type)
    {
       //1.根据当前包id 获取产品code 版本名称等
        $info1 = $this->getVerNameAndProductInfo($sp_pack_id);

        if(!$info1) return false;

        //2.根据组id 获取该组下所有的sn
        $snList = $this->getSnListToGroupId($group_id);

        if(!$snList) return false;

        foreach ($snList as $k=>$v){
            if($type == 1){//整包
                $this->zsetFullPackageSnWriteFromRedis($info1['pro_code'],$info1['ver_name'],$v['sn']);//当前的ver_name 对于整包而言是to_ver_id
            }elseif ($type == 2){//差分包

                $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表

                if(!isset($ver_name_list[$info1['from_ver_id']])) continue;//查找不到版本名称就跳过

                $fromVer=$ver_name_list[$info1['from_ver_id']];

                $this->zsetXpackageSnWriteFromRedis($info1['pro_code'],$fromVer,$info1['ver_name'],$v['sn']);
            }
        }

        return true;
    }

    //删除
    protected function disDelGrayPackageBindSnInfo($sp_pack_id,$group_id,$type)
    {
        //1.根据当前包id 获取产品code 版本名称等
        $info1 = $this->getVerNameAndProductInfo($sp_pack_id);

        if(!$info1) return false;

        //2.根据组id 获取该组下所有的sn
        $snList = $this->getSnListToGroupId($group_id);

        if(!$snList) return false;

        foreach ($snList as $k=>$v){
            if($type == 1){//整包
                $this->delFullPackageSnWriteFromRedis($info1['pro_code'],$info1['ver_name'],$v['sn']);//当前的ver_name 对于整包而言是to_ver_id
            }elseif ($type == 2){//差分包

                $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表

                if(!isset($ver_name_list[$info1['from_ver_id']])) continue;//查找不到版本名称就跳过

                $fromVer=$ver_name_list[$info1['from_ver_id']];

                $this->delXpackageSnWriteFromRedis($info1['pro_code'],$fromVer,$info1['ver_name'],$v['sn']);
            }
        }

        return true;
    }

    /**
     * 根据包ID 获取当前的版本名称
     * @param $sp_pack_id
     */
    protected function getVerNameAndProductInfo($sp_pack_id)
    {
        $sql = "select p.pro_name,p.pro_code,b.to_ver_id,b.from_ver_id,b.ver_name,b.ver_id from 
             (select d.sp_pack_id,d.to_ver_id,d.from_ver_id,d.status,v.ver_id,v.ver_name,v.pro_id from  
             diff_package as d left join version as v on d.to_ver_id=v.ver_id where 
             d.sp_pack_id=$sp_pack_id) as b left join product as p on b.pro_id=p.pro_id;
            ";

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $info     = $command->queryOne();

        return $info;
    }

    /**
     * 根据当前组id 返回该组下所有的sn信息
     * @param $group_id
     * @return array|false
     */
    protected function getSnListToGroupId($group_id)
    {
        $sql = "select * from group_sn where group_id=$group_id and status=1";

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $snList     = $command->queryAll();

        return $snList;
    }

    /************************************处理sn 绑定 删除 同步信息处理************************************************/

    /**
     * 同步更新 该灰度组与不同包 sn的绑定关系
     * @param $group_id
     */
    protected function addSyncSnFromRedis($group_id,$sn)
    {

        try{

            $info  = $this->getGroupBindPackageInfo($group_id);

            if(!$info) return false;//这里如果查不到相关数据，说明当前灰度组没被任何关联过，所有不需要将sn写入redis

            $this->cycleAddDisSnInfoFromRedis($info,$sn);
            show_json(0,'添加sn成功且同步sn信息到redis成功');
        }catch (\Exception $e){
            show_json(100000,'同步sn信息到redis失败');
        }



    }

    /**
     * 删除redis 缓存的sn信息  注意这里会删除多个包与子关联的信息 都会删除  所有需要便利一下
     * @param $group_id
     * @param $sn
     * @return bool
     */
    protected function delSyncSnFromRedis($group_id,$sn)
    {
        try{

            $info  = $this->getGroupBindPackageInfo($group_id);

            if(!$info) return false;//这里如果查不到相关数据，说明当前灰度组没被任何关联过，所有不需要将sn写入redis

            /*if($this->cycleDelDisSnInfoFromRedis($info,$sn))   show_json(0,'删除缓存sn信息成功');*/
            $this->cycleDelDisSnInfoFromRedis($info,$sn);
        }catch (\Exception $e){
            show_json(100000,'删除缓存sn信息失败');
        }
    }

    /**
     * 根据灰度组id 获取所有包与子有关联的信息
     * @param $group_id
     * @return array
     */
    protected function getGroupBindPackageInfo($group_id)
    {
        $sql = "select d.*,v.* ,pro.pro_name,pro.pro_code from (select dg.group_id,dp.* from diff_package_group as dg left join diff_package as dp on dg.sp_pack_id=dp.sp_pack_id where
 dg.group_id=$group_id and dp.status not in(0,3)) as d left JOIN version as v ON d.to_ver_id=v.ver_id  left join product as pro on v.pro_id=pro.pro_id";

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $info     = $command->queryAll();

        return $info;
    }

    /**
     * @param $sp_pack_id
     */
    protected function getPackBindGroupInfoFromPackId($sp_pack_id)
    {
        $info = DiffPackageGroup::find()->where(['sp_pack_id'=>$sp_pack_id])->asArray()->all();

        $data = [];
        if(!$info) return $data;

        foreach ($info as $k=>$v){
            array_push($data,$v['group_id']);
        }

        return $data;
    }

    /**
     * 当禁用该灰度组 需要清楚缓存中所有包与之绑定关系  开启的时候又要将所有的绑定关系添加到缓存
     * @param $group_id
     * @param $status
     * @return bool
     */
    protected function disDisableGrayGroup($group_id,$status)
    {
        $sql = "select g.group_id,pack.* from diff_package_group as g left join diff_package as pack on g.sp_pack_id=pack.sp_pack_id where g.group_id=$group_id";

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $info     = $command->queryAll();

        if(!$info) return true;

        foreach ($info as $k=>$v){
            if($status == 0) $this->disDelGrayPackageBindSnInfo($v['sp_pack_id'],$group_id,$v['type']);//清楚缓存的sn信息;
            if($status == 1) $this->disAddGrayPackageBindSnInfo($v['sp_pack_id'],$group_id,$v['type']);//同步sn 到缓存
        }

        return true;
    }


    /**
     * 获取每个组下面的sn数量
     * @return array
     */
    protected function getGroupBindCountSn()
    {
        $sql = "select count(*) as sum_sn,group_id from group_sn GROUP BY group_id";

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $info     = $command->queryAll();

        $data = [];
        if(!$info) return $data;

        foreach ($info as $k=>$v){
           $data[$v['group_id']] = $v['sum_sn'];
        }

        return $data;
    }

    /**
     * 更新每个组下 sn的关系
     * @param $info
     * @param $sn
     */
    protected function cycleAddDisSnInfoFromRedis($info,$sn)
    {
        if(!$info) return;

        $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表
        foreach ($info as $k=>$v){
            if($v['type'] == 1){//整包处理
                $this->zsetFullPackageSnWriteFromRedis($v['pro_code'],$v['ver_name'],$sn);//当前的ver_name 对于整包而言是to_ver_id
            }elseif ($v['type'] == 2){//差分包处理
                //差分包有点特殊  redis的key值 需要用到 来自初始版本 与 最终版本
                if(!isset($ver_name_list[$v['from_ver_id']])) continue;//查找不到版本名称就跳过
                $fromVer=$ver_name_list[$v['from_ver_id']];
                $this->zsetXpackageSnWriteFromRedis($v['pro_code'],$fromVer,$v['ver_name'],$sn);
            }
        }

        return;
    }

    /**
     * 循环便利删除多个包与该组存在绑定关系的都要删除
     * @param $info
     * @param $sn
     */
    protected function cycleDelDisSnInfoFromRedis($info,$sn)
    {
        if(!$info) return true;

        $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表
        foreach ($info as $k=>$v){
            if($v['type'] == 1){//整包处理
                $this->delFullPackageSnWriteFromRedis($v['pro_code'],$v['ver_name'],$sn);//当前的ver_name 对于整包而言是to_ver_id
            }elseif ($v['type'] == 2){//差分包处理
                //差分包有点特殊  redis的key值 需要用到 来自初始版本 与 最终版本
                if(!isset($ver_name_list[$v['from_ver_id']])) continue;//查找不到版本名称就跳过
                $fromVer=$ver_name_list[$v['from_ver_id']];
                $this->delXpackageSnWriteFromRedis($v['pro_code'],$fromVer,$v['ver_name'],$sn);
            }
        }

        return true;
    }

    /**
     * 获取处理过的所有版本名称对应版本id 的列表
     * $data[1] => 'v1.0.0'
     * @return array
     */
    protected function getDisVerNameList()
    {
        $ver_list = Version::find()->asArray()->all();

        if(!$ver_list) return [];

        $data = [];
        foreach ($ver_list as $key=>$val){
            $data[$val['ver_id']] = $val['ver_name'];
        }

        return $data;
    }

    /**
     * 根据产品的code 存储 产品名称
     * @param $pro_code
     * @param $pro_name
     * @return mixed
     */
    protected function setPronameFromRedis($pro_code,$pro_name)
    {
        $key = 'ota:product_name:'.$pro_code;

        return $this->redis->set($key,$pro_name);
    }

    /**
     * 添加
     * 整包
     * 灰度产品包可测试的 sn列表
     * sn 写入 redis
     *
     */
    protected function zsetFullPackageSnWriteFromRedis($pro_code,$toVer,$sn)
    {
        $key = 'ota:sn_order_info:'.$pro_code.":".$toVer;

        return $this->redis->zadd($key,time(),$sn);
    }

    /**
     * 添加
     * 差分包
     * @param $pro_code
     * @param $fromVer
     * @param $toVer
     * @param $sn
     */
    protected function zsetXpackageSnWriteFromRedis($pro_code,$fromVer,$toVer,$sn)
    {
        $key = 'ota:sn_order_info:'.$pro_code.":".$fromVer.":".$toVer;

        return $this->redis->zadd($key,time(),$sn);
    }

    /**
     * 指定删除
     * del
     * 整包
     * 灰度产品包可测试的 sn列表
     * sn 写入 redis
     *
     */
    protected function delFullPackageSnWriteFromRedis($pro_code,$toVer,$sn)
    {
        $key = 'ota:sn_order_info:'.$pro_code.":".$toVer;

        return $this->redis->zrem($key, $sn);
    }

    /**
     * 指定删除
     * 删除
     * 差分包
     * @param $pro_code
     * @param $fromVer
     * @param $toVer
     * @param $sn
     */
    protected function delXpackageSnWriteFromRedis($pro_code,$fromVer,$toVer,$sn)
    {
        $key = 'ota:sn_order_info:'.$pro_code.":".$fromVer.":".$toVer;

        return $this->redis->zrem($key, $sn);
    }

    /**
     * 删除全部数据
     * 整包
     * @param $pro_code
     * @param $toVer
     */
    protected function delAllFullPackageSnWriteFromRedis($pro_code,$toVer)
    {
        $key = 'ota:sn_order_info:'.$pro_code.":".$toVer;

        return $this->redis->del($key);
    }

    /**
     * 删除全部数据
     * 差分包
     * @param $pro_code
     * @param $fromVer
     * @param $toVer
     */
    protected function delAllXpackageSnWriteFromRedis($pro_code,$fromVer,$toVer)
    {
        $key = 'ota:sn_order_info:'.$pro_code.":".$fromVer.":".$toVer;

        return $this->redis->del($key);
    }


    /**
     * 删除在线数据差分包
     * @param $key
     * @param $field
     * @return mixed
     */
    protected function delXOlineOtaData($key,$field)
    {
        return $this->redis->hdel($key,$field);
    }

    /**
     * 整包在线数据删除
     * @param $key
     * @return mixed
     */
    protected function delZOlineOtaData($key)
    {
        return $this->redis->del($key);
    }


    /**
     * 灰度
     * 删除在线数据差分包
     * @param $key
     * @param $field
     * @return mixed
     */
    protected function delGrayXOlineOtaData($key,$field)
    {
        return $this->redis->hdel($key,$field);
    }

    /**
     * 灰度
     * 整包在线数据删除
     * @param $key
     * @return mixed
     */
    protected function delGrayZOlineOtaData($key)
    {
        return $this->redis->del($key);
    }

}