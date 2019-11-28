<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/17
 * Time: 14:17
 */

namespace app\controllers;

use app\models\db\DiffPackageGroup;
use app\models\db\Product;
use Yii;
use app\models\input;
use yii\base\Exception;
use yii\data\Pagination;
use app\controllers\base\OtaComBaseController;
use app\models\db\Version;
use app\models\db\DiffPackage;
use app\models\db\DiffPackageFile;

use  common\util\IFilter;
use  common\util\IReq;
class OtaPackageController extends OtaComBaseController
{

    protected $p_title= '包管理';//注意更具自己项目做具体定义名称 一级
    protected $d_title= '';//二级
    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分


    /**
     * 某个版本下所有包列表
     * @return string
     */
    public function actionPackage_list(){


        $ver_id = IFilter::act(IReq::get('ver_id')); //版本名称 r如：v1.0.1

        if(!$ver_id) show_json(100000,'缺少请求参数');

        $verInfo = Version::findOne($ver_id);

        if(!$verInfo) show_json(1000000,'当前版本信息数据不存在');

        $proInfo = Product::findOne($verInfo['pro_id']);//查看所属产品信息


        //组装查看某个版本下所有相关包信息  组装条件 产品code+ver_id

        $packKey = md5($proInfo['pro_code'] . $ver_id);

        $packageInfoList = DiffPackage::find()->where(['b_ver_id'=>$packKey]);//获取该版本下所有的包

        $b_packageInfoList = clone $packageInfoList;
        $pages = new Pagination(['totalCount'=>$b_packageInfoList->count(),'defaultPageSize'=>10] );

        $models = $packageInfoList->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_ts DESC')->all();


        if(isset($verInfo['is_full']) && $verInfo['is_full'] > 0){
            $name = '整包列表';
        }else{
            $name = '差分包列表';
        }

        $this->d_title = ' / '.$verInfo['ver_name'].' / '.$name;
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('package_list', [
            'data'=>$models,
            'pages'=>$pages,
            'ver_id'=>$ver_id,
            'ver_data'=>$verInfo,
            'ver_list'=>$this->getDisVerNameList()
        ]);
    }


    /**
     * 添加版本
     * 差分包页面
     * @return array|false
     */
    public function actionPackage_add()
    {

        $ver_id = IFilter::act(IReq::get('ver_id')); //版本id

        $pro_id = IFilter::act(IReq::get('pro_id')); //产品id

        if(!$ver_id) show_json(100000,'缺少版本id');

        if(!$pro_id) show_json(100000,'缺少产品id');

        $ver_data_list = Version::find()->where(['pro_id'=>$pro_id])->orderBy('created_ts DESC')->all();//根据产品id 获取该产品的所有版本信息

        $ver_one_data_list = Version::find()->where(['ver_id'=>$ver_id])->one();//根据版本id 获取当前版本信息 单条记录


        $this->d_title = ' / '.$ver_one_data_list['ver_name'].' / 添加差分包';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('package_add',['version_list'=>$ver_data_list,
            'version_data'=>$ver_one_data_list,
            'ver_id'=>$ver_id, // 版本下的差分包
        ]);

    }

    /**
     * 添加正包版本
     * 整包页面
     * @return array|false
     */
    public function actionPackage_full_add()
    {
        $ver_id = IFilter::act(IReq::get('ver_id')); //版本id

        $pro_id = IFilter::act(IReq::get('pro_id')); //产品id

        if(!$ver_id) show_json(100000,'缺少版本id');

        if(!$pro_id) show_json(100000,'缺少产品id');

        $ver_data_list = Version::find()->where(['pro_id'=>$pro_id])->orderBy('created_ts DESC')->all();//根据产品id 获取该产品的所有版本信息

        $ver_one_data_list = Version::find()->where(['ver_id'=>$ver_id])->one();//根据版本id 获取当前版本信息 单条记录

        $this->d_title = ' / '.$ver_one_data_list['ver_name'].' / 添加整包';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('package_full_add',['version_list'=>$ver_data_list,
            'version_data'=>$ver_one_data_list,
            'ver_id'=>$ver_id,
        ]);

    }




    /**
    /**
     * 设置版本状态
     * @return array|false
     */
    public function actionSet_version_status(){

        $sp_pack_id = IFilter::act(IReq::get('pack_id')); //包 id

        $ver_id = IFilter::act(IReq::get('ver_id')); //包 id

        if(!$ver_id) show_json(100000,'缺少升级到指定的版本id');

        $status = intval(IFilter::act(IReq::get('status'))); //状态

        if(!$sp_pack_id) show_json(100000,'缺少升级包id');

        $DiffPackage = DiffPackage::findOne($sp_pack_id);//判断修改的数据是否存在

        if(!$DiffPackage) show_json(100000,'数据不存在');

        $verOneList = Version::findOne($ver_id);

        if(!$verOneList) show_json(100000,'版本信息数据不存在');

        $product_info  = Product::find()->where(['pro_id'=>$verOneList['pro_id']])->one();//根据产品id 只需要产品名称

        if(!$product_info) show_json(100000,'所属的产品信息数据不存在');

        //2019-06-18
        $verOneList->status = $status;
        $verOneList->updated_ts = time();

        $DiffPackage->status = $status;
        $DiffPackage->updated_ts = time();

        if($DiffPackage->save() && $verOneList->save()){
            //对外发布
            if($status == 3){
                $this->delGroupBindSnInfoFromCache($product_info,$DiffPackage);
            }
            //禁用的时候需要删除ota接口信息
            if($status == -1){
                $this->delGroupBindSnInfoFromCache($product_info,$DiffPackage);
                $this->delOtaPackageInfoFromRedis($product_info,$DiffPackage);
            }
            show_json(0,'状态切换成功');
        }
        show_json(100000,'状态切换失败');


    }

    /**
     * 禁用的时候删除 接口在线数据
     * @param $product_info
     * @param $packInfo
     */
    protected function delOtaPackageInfoFromRedis($product_info,$packInfo)
    {

        if($packInfo['type'] == 1){//整包key  key=>value 数据结构
            $key = 'ota:formal_package_info:'.$product_info['pro_code'];
            $this->delZOlineOtaData($key);//当前的ver_name 对于整包而言是to_ver_id
        }else if($packInfo['type'] == 2){//差分包  hash 结构

            $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表

            $key = 'ota:hash:'.$product_info['pro_code'];

            $field = $ver_name_list[$packInfo['from_ver_id']];
            $this->delXOlineOtaData($key,$field);
        }

        DiffPackageGroup::deleteAll(['sp_pack_id'=>$packInfo['sp_pack_id']]);
    }

    /**
     * 当包对外发布时候需要删除灰度组信息
     * @param $product_info
     * @param $packInfo
     */
    protected function delGroupBindSnInfoFromCache($product_info,$packInfo)
    {

        $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表

        if($packInfo['type'] == 1){

            $key = 'ota:gray_package_info:'.$product_info['pro_code'];
            $this->delGrayZOlineOtaData($key);//当前的ver_name 对于整包而言是to_ver_id

            //todo 发布正式版本 需要清空灰度组信息
            $this->delAllFullPackageSnWriteFromRedis($product_info['pro_code'],$ver_name_list[$packInfo['to_ver_id']]);//当前的ver_name 对于整包而言是to_ver_id
        }else if($packInfo['type'] == 2){

            $ver_name_list = $this->getDisVerNameList();//索引为版本id 所有版本的列表

            $key = 'ota:gray_hash:'.$product_info['pro_code'];

            $field = $ver_name_list[$packInfo['from_ver_id']];

            $this->delGrayXOlineOtaData($key,$field);

            //todo 当前包发布上线的时候需要将灰度组信息清空
            $this->delAllXpackageSnWriteFromRedis($product_info['pro_code'],$ver_name_list[$packInfo['from_ver_id']],$ver_name_list[$packInfo['to_ver_id']]);
        }

    }


    /**
     * 修改数据
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionPackage_edit()
    {

        $sp_pack_id = IFilter::act(IReq::get('pack_id')); //包 id

        $ver_id = IFilter::act(IReq::get('ver_id')); //版本 id

        if(!$sp_pack_id) show_json(100000,'缺少升级包id');

        if(!$ver_id) show_json(100000,'缺少版本id');

        $DiffPackage = DiffPackage::findOne($sp_pack_id);//判断修改的数据是否存在

        $verInfo = Version::findOne($ver_id);//查看当前版本数据是否存在

        if(!$DiffPackage) show_json(100000,'包数据不存在');

        if(!$verInfo) show_json(100000,'版本数据不存在');

        $ver_data_list = Version::find()->where(['pro_id'=>$verInfo['pro_id']])->orderBy('created_ts DESC')->all();//根据产品id 获取该产品的所有版本信息

        $ver_one_data_list = Version::find()->where(['ver_id'=>$ver_id])->one();//根据版本id 获取当前版本信息 单条记录


        $this->d_title = $verInfo['ver_name'] .' / 编辑';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('package_edit',['version_list'=>$ver_data_list,
            'pack_data'=>$DiffPackage,
            'to_ver_data'=>$ver_one_data_list,
            'ver_list'=>$this->getDisVerNameList()
        ]);

    }

    public function actionPackage_detail()
    {

        $sp_pack_id = IFilter::act(IReq::get('pack_id')); //包 id

        $ver_id = IFilter::act(IReq::get('ver_id')); //版本 id

        if(!$sp_pack_id) show_json(100000,'缺少升级包id');

        if(!$ver_id) show_json(100000,'缺少版本id');

        $DiffPackageInfo = DiffPackage::findOne($sp_pack_id);//查看当前包是否存在

        $VersionInfo = Version::findOne($ver_id);//查看当前版本数据信息是否存在

        if(!$DiffPackageInfo) show_json(100000,'该包数据不存在');

        if(!$VersionInfo) show_json(100000,'该版本数据信息不存在');

        if(isset($DiffPackageInfo['type']) && $DiffPackageInfo['type'] == 1){
            $name = '整包详情';
        }else{
            $name = '差分包详情';
        }


        $version_list = Version::find()->where(['pro_id'=>$VersionInfo['pro_id']])->asArray()->all();

        $this->d_title = ' / '.$name;
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('package_detail',['version_list'=>empty($version_list)?[]:$version_list,
            'pack_id'=>$sp_pack_id,
            'pack_data'=>$DiffPackageInfo,
            'ver_list'=>$this->getDisVerNameList()
          ]);

    }


    /**
     * 产品删
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionPackage_del()
    {

        $sp_pack_id = IFilter::act(IReq::get('pack_id')); //包 id

        if(!$sp_pack_id) show_json(100000,'选择删除项~');

        $Info = DiffPackage::findOne($sp_pack_id);

        if(!$Info) show_json(100000,'删除的数据信息不存在');

        try
        {
            if($Info->delete() && DiffPackageFile::deleteAll(['sp_pack_id'=>$sp_pack_id])){
                show_json(0,'删除成功！');
            }else{
                throw new Exception('删除失败');
            }

        }catch (\Exception $e){
            show_json(100000,'删除失败！~');
        }


    }

    /**
     * 产品提交
     * 差分包数据提交
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionPackage_submit()
    {

        ini_set('memory_limit','2024M');

        $lang = IFilter::act(IReq::get('lang')); //语言
        $from_ver_id = IFilter::act(IReq::get('from_ver_id')); //来自什么版本 开始版本
        $alt_style = IFilter::act(IReq::get('alt_style')); //消息提示类型
        $fullupdate = intval(IFilter::act(IReq::get('fullupdate'))); //是否允许整包升级 0 不允许 1 允许
        $auto_download = intval(IFilter::act(IReq::get('auto_download'))); //自动下载选择0：否，1：仅wifi,2:任意网络
        $force_update = intval(IFilter::act(IReq::get('force_update'))); //是否强制更新0：否，1：是
        $description = IFilter::act(IReq::get('description')); //包描述内容
        $to_ver_id = IFilter::act(IReq::get('to_ver_id')); //当前的版本id   从fromid 升级到 tofromid

        if(!$from_ver_id) show_json(100000,'缺少来版本的id');
        if(!$to_ver_id) show_json(100000,'缺少升级到指定的版本id');

        $from_ver_data = Version::find()->where(['ver_id'=>$from_ver_id])->asArray()->one();//开始版本信息
        $to_ver_data = Version::find()->where(['ver_id'=>$to_ver_id])->asArray()->one();//结束版本信息

        if(!$from_ver_data || !$to_ver_data) show_json(100000,'缺少已存在版本的信息,请核对数据信息');

        $this->isCheckAddVer($from_ver_data['created_ts'],$to_ver_data['created_ts']);//判断是否可创建该版本

        $product_info  = Product::find()->where(['pro_id'=>$to_ver_data['pro_id']])->one();//根据产品id 只需要产品名称

        $pro_name = $product_info['pro_name'];//产品名称

        $pro_code = $product_info['pro_code'];//产品code

        $from_ver_name = $from_ver_data['ver_name'];
        $to_ver_name = $to_ver_data['ver_name'];

        $base_path = Yii::$app->params['diff_base_path'];
        $param = $from_ver_name.'_'.$to_ver_name;

        $file_download_uris = '/'.$from_ver_name.'_'.$to_ver_name.'/'.$param;
        // 查看当前包是不是整包 若是整包 则进行另一个操作。
      /*  if(isset($to_ver_data['is_full']) && $to_ver_data['is_full'] > 0){
            $path =  $base_path . $pro_name.'/'.$to_ver_name ;
        }else{*/
            $path =  $base_path . $pro_name.'/'.$to_ver_name .'/'.$param;
       //}
        // 检查当前的路径下有木有对应的OTA包上传
        $this->traverse($path);
        // 从全局变量中获取文件大小
        $file_array = $GLOBALS['filePath'];


        if(!$file_array){
            show_json(100000,'该差分包没有文件，请通过FTP上传,注意目录名称规范！');
        }

        try
        {
            $DiffPackage = new DiffPackage;

            $onlyPackId = $this->setonlyPackId($pro_code,$to_ver_id);//产品+版本号 唯一的id

            //判断该产品下是否重复添加了相同的包
            /*$oneInfo = DiffPackage::find()->where(['b_ver_id'=>$onlyPackId])->one();*/
            $oneInfo = DiffPackage::find()->where(['from_ver_id'=>$from_ver_id,'to_ver_id'=>$to_ver_id])->one();

            if($oneInfo) show_json(100000,'该产品下的版本不能添加相同的包 或者 起始版本->结束版本重复');

            $DiffPackage->b_ver_id = $onlyPackId;//产品+版本号
            $DiffPackage->sp_pack_name = $pro_name;//直接对应的是产品的名称
            $DiffPackage->status = 0;//差分包状态-1:禁用;0：未发布，1：小范围测试 2. 已测试 3 已发布
            $DiffPackage->type = 2;//差分包类型 或者整包 1整包 2差分包
            $DiffPackage->from_ver_id = $from_ver_id;//开始版本
            $DiffPackage->to_ver_id = $to_ver_id;//结束版本
            $DiffPackage->lang = $lang;//语言
            $DiffPackage->description = $description;//当前包的描述内容
            $DiffPackage->auto_download = $auto_download;//自动下载选择0：否，1：仅wifi,2:任意网络
            $DiffPackage->force_update = $force_update;//是否强制更新0：否，1：是
            $DiffPackage->alt_style = $alt_style;//消息提示类型1：通知栏提示，2：弹窗提示，3：全选
            $DiffPackage->fullupdate = $fullupdate;//是否允许整包升级：0：不允许，1:允许
            $DiffPackage->created_ts = time();
            $DiffPackage->updated_ts = 0;

            if($DiffPackage->save()){
                $auto_id = $DiffPackage->attributes['sp_pack_id'];//添加成功后返回自增id

                $i = 0;
                // 把差分包的相应文件保存
                foreach ($file_array as $k=>$value){//同一个版本下可以出现多个包
                    $sqlstr = "(";//sql拼接
                    $s = str_replace('\\', '/', $k);
                    $file_download_uri = $file_download_uris;
                    $md5file = file_get_contents($k);

                    $sqlstr .= $auto_id.",";
                    $sqlstr .= $value[1].",'";
                    $sqlstr .= $file_download_uri."','";
                    $sqlstr .= md5($md5file)."',";
                    $sqlstr .= time().",";
                    $sqlstr .= 0;
                    if(count($file_array)-1 == $i){
                        $sqlstr .= ");";//sql结束
                    }else{
                        $sqlstr .= "),";//下一个sql拼接
                    }
                }

                $sql = "insert into diff_package_file (`sp_pack_id`,`file_size`,`file_download_uri`,`md5sum`,`created_ts`,`updated_ts`)values ".$sqlstr;


                $connection = Yii::$app->db;

                $command = $connection->createCommand($sql);

                $command->execute();

                show_json(0,'添加差分包信息数据成功');

            }

            throw new Exception("创建差分包文件数据信息失败");

        }catch (\Exception $e){

            show_json(100000,'添加差分包信息数据失败');
        }




    }


    /**
     * 产品提交
     * 整包数据提交
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionPackage_full_submit()
    {

        ini_set('memory_limit','2024M');

        $lang = IFilter::act(IReq::get('lang')); //语言
        $from_ver_id = IFilter::act(IReq::get('from_ver_id')); //来自什么版本 开始版本
        $alt_style = IFilter::act(IReq::get('alt_style')); //消息提示类型
        $fullupdate = intval(IFilter::act(IReq::get('fullupdate'))); //是否允许整包升级 0 不允许 1 允许
        $auto_download = intval(IFilter::act(IReq::get('auto_download'))); //自动下载选择0：否，1：仅wifi,2:任意网络
        $force_update = intval(IFilter::act(IReq::get('force_update'))); //是否强制更新0：否，1：是
        $description = IFilter::act(IReq::get('description')); //包描述内容
        $to_ver_id = IFilter::act(IReq::get('to_ver_id')); //当前的版本id   从fromid 升级到 tofromid

        // 编辑
        if(!$from_ver_id) show_json(100000,'缺少版本的id');
        if(!$to_ver_id) show_json(100000,'缺少升级到指定的版本id');

        $from_ver_data = Version::find()->where(['ver_id'=>$from_ver_id])->one();//开始版本信息
        $to_ver_data = Version::find()->where(['ver_id'=>$to_ver_id])->one();//结束版本信息

        if(!$from_ver_data || !$to_ver_data) show_json(100000,'缺少已存在版本的信息,请核对数据信息');

        $product_info  = Product::find()->where(['pro_id'=>$to_ver_data['pro_id']])->one();//根据产品id 只需要产品名称

        $pro_name = $product_info['pro_name'];//产品名称

        $pro_code = $product_info['pro_code'];//产品code

        $this->isCheckFullAddVer($pro_code,$to_ver_id);//检测是否可添加整包的条件

        $from_ver_name = $from_ver_data['ver_name'];
        $to_ver_name = $to_ver_data['ver_name'];

        $base_path = Yii::$app->params['diff_base_path'];

       // $param = $from_ver_name.'_'.$to_ver_name;
        $file_download_uris = '/'.$pro_name.'/'.$to_ver_name;//文件下载地址
        // 查看当前包是不是整包 若是整包 则进行另一个操作。
        if(isset($to_ver_data['is_full']) && $to_ver_data['is_full'] > 0){
            $path =  $base_path . $pro_name.'/'.$to_ver_name ;//整包
        }/*else{
            $path =  $base_path . $pro_name.'/'.$to_ver_name .'/'.$param;//差分包
        }*/

        // 检查当前的路径下有木有对应的OTA包上传
        $this->traverse($path);
        // 从全局变量中获取文件大小
        $file_array = $GLOBALS['filePath'];//[]

        if(!$file_array){
            show_json(100000,'该整包没有文件，请通过FTP上传,注意目录名称规范！');
        }

        try
        {
            $DiffPackage = new DiffPackage;

            $onlyPackId = $this->setonlyPackId($pro_code,$to_ver_id);//产品+版本号 唯一的id

            //判断该产品下是否重复添加了相同的包
            $oneInfo = DiffPackage::find()->where(['b_ver_id'=>$onlyPackId])->one();

            if($oneInfo) show_json(100000,'该产品下的版本不能添加相同的包 或者 起始版本->结束版本重复');

            $DiffPackage->b_ver_id = $onlyPackId;//产品+版本号
            $DiffPackage->sp_pack_name = $pro_name;//直接对应的是产品的名称
            $DiffPackage->status = 0;//差分包状态-1:禁用;0：未发布，1：小范围测试 2. 已测试 3 已发布
            $DiffPackage->type = 1;//差分包类型 或者整包 1整包 2差分包
            $DiffPackage->from_ver_id = $from_ver_id;//开始版本
            $DiffPackage->to_ver_id = $to_ver_id;//结束版本
            $DiffPackage->lang = $lang;//语言
            $DiffPackage->description = $description;//当前包的描述内容
            $DiffPackage->auto_download = $auto_download;//自动下载选择0：否，1：仅wifi,2:任意网络
            $DiffPackage->force_update = $force_update;//是否强制更新0：否，1：是
            $DiffPackage->alt_style = $alt_style;//消息提示类型1：通知栏提示，2：弹窗提示，3：全选
            $DiffPackage->fullupdate = $fullupdate;//是否允许整包升级：0：不允许，1:允许
            $DiffPackage->created_ts = time();
            $DiffPackage->updated_ts = 0;

            if($DiffPackage->save()){
                $auto_id = $DiffPackage->attributes['sp_pack_id'];//添加成功后返回自增id

                // 把差分包的相应文件保存
                $i = 0;
                foreach ($file_array as $k=>$value){//同一个版本下可以出现多个包
                    $sqlstr = "(";//sql拼接
                    $s = str_replace('\\', '/', $k);
                   /* $file_download_uri = strstr(strstr($s,"$param"),"/");*/
                    $file_download_uri = $file_download_uris;
                    $md5file = file_get_contents($k);

                    $sqlstr .= $auto_id.",";
                    $sqlstr .= $value[1].",'";
                    $sqlstr .= $file_download_uri."','";
                    $sqlstr .= md5($md5file)."',";
                    $sqlstr .= time().",";
                    $sqlstr .= 0;
                    if(count($file_array)-1 == $i){
                        $sqlstr .= ");";//sql结束
                    }else{
                        $sqlstr .= "),";//下一个sql拼接
                    }

                    $i++;
                }

                $sql = "insert into diff_package_file (`sp_pack_id`,`file_size`,`file_download_uri`,`md5sum`,`created_ts`,`updated_ts`)values ".$sqlstr;


                $connection = Yii::$app->db;

                $command = $connection->createCommand($sql);

                $bool = $command->execute();

                //每次新增包数据时 需要将当前的版本号压入redis ，ota接口需要验证是否可升级
               //$this->setVerNameFromRedis($pro_code,$to_ver_name['ver_name']);//在整包中只有最后一次添加的数据有效

                show_json(0,'添加整包包信息数据成功');
            }

            throw new Exception("创建整包文件数据信息失败");

        }catch (\Exception $e){

            show_json(100000,'添加整包包信息数据失败');
        }



    }

    /**
     * 同步数据到redis
     */
    public function actionSyncData()
    {
        $ver_id = IFilter::act(IReq::get('ver_id')); //当前的版本id   从fromid 升级到 tofromid

        $sp_pack_id = IFilter::act(IReq::get('sp_pack_id')); //当前包的id

        if(!$sp_pack_id) show_json(100000,'缺少当前包id');

        if(!$ver_id) show_json(100000,'缺少升级到指定的版本id');

        $verOneList = Version::findOne($ver_id);

        if(!$verOneList) show_json(100000,'版本信息数据不存在');

        $packageInfo = DiffPackage::findOne($sp_pack_id);

        if(!$packageInfo) show_json(100000,'包数据不存在');


        $from_ver_data = Version::find()->where(['ver_id'=>$packageInfo['from_ver_id']])->one();//开始版本信息
        $to_ver_data = Version::find()->where(['ver_id'=>$packageInfo['to_ver_id']])->one();//结束版本信息

        if(!$from_ver_data || !$to_ver_data) show_json(100000,'缺少已存在版本的信息,请核对数据信息');

        $product_info  = Product::find()->where(['pro_id'=>$to_ver_data['pro_id']])->one();//根据产品id 只需要产品名称

        $pro_name = $product_info['pro_name'];//产品名称

        $from_ver_name = $from_ver_data['ver_name'];
        $to_ver_name = $to_ver_data['ver_name'];

        $base_path = Yii::$app->params['diff_base_path'];//根目录地址

        $param = $from_ver_name.'_'.$to_ver_name;
        // 查看当前包是不是整包 若是整包 则进行另一个操作。
        if(intval($packageInfo['type']) === 1){
            $path =  $base_path . $pro_name.'/'.$to_ver_name ;//整包

            $t_id = 1;
        }else{
            $path =  $base_path . $pro_name.'/'.$to_ver_name .'/'.$param;//差分包

            $t_id = 2;
        }

        try{

            $this->disVerJsonInfo($product_info['pro_code'],$pro_name,$to_ver_data['pro_id'],$t_id);//2019-06-13

            // 检查当前的路径下有木有对应的OTA包上传
            $this->traverse($path);

            $this->disSortingVerInfo($product_info['pro_id'],$product_info['pro_code'],$packageInfo);

            show_json(0,'同步成功');
        }catch (\Exception $e){
            show_json(100000,'同步失败');
        }


    }


    //2019-06-13
    /**
     * @param $pro_code
     * @param $pro_name
     * @param $pro_id
     * @param $to_ver_name 差分包的当前版本
     * @param $from_ver_name  来自什么版本
     * @param $t_id
     */
    /*public function disVerJsonInfo($pro_code,$pro_name,$pro_id,$to_ver_name,$from_ver_name,$t_id)
    {

        if($t_id == 1) return ;

        $this->setPronameFromRedis($pro_code,$pro_name);//设置存储 产品名称

        //处理升级包 与 灰度组包缓存数据  //注意 顺序是因为根据当前版本号 生成不同的json文件内容
        $ver_list = Version::find()->where(['pro_id'=>$pro_id])->orderBy("created_ts asc")->asArray()->all();//获取该本版下所有版本包，顺序排，

        if(!$ver_list) return;

        $totalnum = count($ver_list);

        $base_path = Yii::$app->params['diff_base_path'];//根目录地址
        $path =  $base_path . $pro_name;

        foreach ($ver_list as $k=>$v){
            if($from_ver_name == $v['ver_name']){
                $file_name = $v['ver_name'];//文件名称
                $arr = [];//需要更新的版本信息

                for ($i=$k;$i<$totalnum-1;$i++){
                    $ver_path = $path.'/'.$to_ver_name.'/'.$ver_list[$i]['ver_name'].'_'.$to_ver_name;//下一个版本的名称

                    if(is_dir($ver_path)) {//没有目录酒跳过
                        array_push($arr,$this->disFilesInfo($this->disVerAllPath($ver_path),$ver_path));//压入数组
                    }


                }

                $this->write_ver_json($pro_name,$file_name,$to_ver_name,json_encode(['files'=>$arr]));
                break;
            }

        }


    }*/

    public function disVerJsonInfo($pro_code,$pro_name,$pro_id,$t_id)
    {

        if($t_id == 2) return ;

        $this->setPronameFromRedis($pro_code,$pro_name);//设置存储 产品名称

        //处理升级包 与 灰度组包缓存数据  //注意 顺序是因为根据当前版本号 生成不同的json文件内容
        $ver_list = Version::find()->where(['pro_id'=>$pro_id])->orderBy("created_ts desc")->asArray()->all();//获取该本版下所有版本包，逆序排，

        if(!$ver_list) return;


        $base_path = Yii::$app->params['static_base_path'];//根目录地址
        $path =  $base_path . $pro_name;

        $ver_key_arr = [];//合法的版本 最新的从左往右押入

        foreach ($ver_list as $k=>$v){//逆序数组 最新的应该是第一个，注意：为了过滤老的版本的整包资源与新的整包资源更新，必须安最新的往前获取

            //先判断当前包是否呗禁用
            if($v['status'] == -1) continue;//如果不是发布版本就跳过

            //判断是否以当前版本做整包资源更新，还是迭代更新  0 迭代更新  1 整包更新
            array_push($ver_key_arr,$v['ver_name']);
            if($v['is_up_holt'] == 1){//整包更新
                break;
            }
        }

        $this->disSourceInfo($pro_name,$path,$ver_key_arr);

    }


    public function disSourceInfo($pro_name,$path ,$data)
    {
        //处理版本的更新资源，如果某些版本下资源名相同，用新的覆盖老得
        if(!$data) return;
        $arr_data = [];//所有资源地址 过滤重名的文件名称
        $ver_name = '';
        $ver_data_list = $data;
        $total_count = count($data);
        for($i=0;$i<$total_count;$i++){
           $ver_name = array_pop($data);

           $B_path = $path.'/'.Yii::$app->params['source_path'].'/'.$ver_name;

            if(is_dir($path)) {
                if($file_data = $this->return_source_path($B_path)){
                    $arr_data = array_merge($arr_data,$file_data);//合并所有资源信息 重名的文件名，新的覆盖老得
                }

            }


        }

        //处理全部资源信息
        $arr_s_info = [];
        foreach ($arr_data as $k=>$v){
            $ori_size = filesize($v[0]);

            $arr_s_info[$v[0]] = [$v[0],$ori_size,$v[1]];//把文件路径赋值给数组
        }


        //ver_name 循环最后一个版本名称应该是最新的一个版本名称
        $this->write_ver_json($pro_name,$ver_name,json_encode(['files'=>$this->returnFileInfoApi($arr_s_info)]));


    }

    /**
     * @param $path 资源地址
     * @param $ver_data_list 版本列表
     * @param $pathInfo
     * @return array
     */
    protected function returnFileInfoApi($pathInfo)
    {
        $data = [];
        if(count($pathInfo) <= 0) return $data;

        foreach ($pathInfo as $key=>$val){

            $n = [];
            $n['filePath'] = str_replace(Yii::$app->params['static_base_path'],Yii::$app->params['static_ota_json_url'].'/ota/',$val[0]);//文件地址


            $n['filePath_a'] = substr($val[0],strlen($val[2]));

            $n['file_size'] = $val[1];//文件大小
            $n['md5sum'] = md5(file_get_contents($val[0]));

            array_push($data,$n);//
        }

        return $data;
    }



    //返回当前路径下所有的资源信息
    public function return_source_path($path)
    {

        $arr = [];
        $current_dir = opendir($path); //opendir()返回一个目录句柄,失败返回false
        while(($file = readdir($current_dir)) !== false) { //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file; //构建子目录路径
            if($file == '.' || $file == '..') {
                continue;
            }else if(is_dir($sub_dir)) { //如果是目录,进行递归

                $this->return_source_path($sub_dir);
            }else{ //如果是文件,直接输出路径和文件名

                $arr[$file] = [$path . DIRECTORY_SEPARATOR . $file,$path];//文件地址

            }
        }

        return $arr;
    }


    public function write_ver_json($pro_name,$ver_name,$data)
    {
        $base_path = Yii::$app->params['ver_json_path'].$pro_name;
        if (!is_dir($base_path)) {
            mkdir($base_path, 0755, true);
        }

        if (!is_dir($base_path)) {
            mkdir($base_path, 0755, true);
        }

        if (!is_dir($base_path)) {
            mkdir($base_path, 0755, true);
        }

        $path = $base_path.'/'.$ver_name.'.json';//当前地址

        $handel = fopen($path, 'w');
        fwrite($handel, $data);
        fclose($handel);
    }


}



