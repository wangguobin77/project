
# OTA

**项目名称：SensePlay OTA**<br>
**创建日期：2018-09-03**

撰写人 | 版本号    | 变更内容 | 变更日期       | 审核人
--- | ------ | ---- | ---------- | ---
 张路| DRAFT | 新建   | 2018-09-07 | 胡洪滨


###   FTP 准备目录
1. 请FTP管理者在206 上创建FTP的根目录为： /data/ota 并在此目录下依次创建需要上传的差分包。例如：
   /data/ota/surf/v2.0.0/v1.1.1_v2.0.0 ，并在v1.1.1_v2.0.0 这个目录下上传差分包。请严格按照这个规则创建目录，否则后台创建添加v1.1.1_v2.0.0差分包时，系统通过不了。在此我解释上述的目录意思：  
   
    a. surf ,需要更新的产品名称。  
    b. v2.0.0 ,surf这个产品更新的最高版本。  
    c. v1.1.1_v2.0.0 为从哪个版本更新到当前最高版本。   
      




###  OTA API接口

###### 接口功能
> 1.明确接口调用需要使用的一些参数。
  2.调用过程中对一些错误解释说明。

###### URL
> [http://test.ota.senseplay.cn/ota-package/check_update_version](http://test.ota.senseplay.cn/ota-package/check_update_version/)

###### 支持格式
> JSON

###### HTTP请求方式
> GET OR POST

###### 请求参数
> |参数             |必选    |类型  |说明|
|:-----             |:-------|:----- |-----                               |
|product_code       |ture    |string |产品CODE                          |
|version            |true    |string   |版本名称|
|sn                 |true    |string   |SN码|
|language           |true    |string   |版本的语言 目前支持的有“zh”,"en"|
|country            |false    |string   |国家 |

###### 返回字段

> |返回字段     |字段类型      |说明                              |
|:-----         |:------        |:-----------------------------   |
|startVer         |string    |起始版本   |
|endVer         |string    |更新至版本   |                    |
|auto_download       |int | 自动下载选择0：否，1：仅wifi,2:任意网络                        |
|necessary        |int |是否强制更新0：否，1：是                         |
|alt_style           |int |消息提示类型1：通知栏提示，2：弹窗提示，3：全选         |
|entirety          |int |是否允许整包升级：0：不允许，1:允许          |
|size               |string |文件大小     |
|filePath          |string |文件下载地址 |
|md5sum             |string    |MD5加密文件          |


###### 返回JSON接口示例

{    
    "auto_download":"0",  
    "necessary":"0",  
    "alt_style":"2",  
    "entirety":"1",  
        "startVer":"v1.0",  
    "endVer":"v2.0",    
    "files":[  
        {  
            "size":"858.78kb",    
            "filePath":"http://test.ota.senseplay.cn/download/5000/v3/v1_v3/Lighthouse.jpg",

            "md5sum":"35628d2e7fe0572ac12e20c53e203b3d"  

        },  

        {  

            "size":"826.11kb",  
             "filePath":"http://test.ota.senseplay.cn/download/5000/v3/v1_v3/FlashFXP54_3970_Setup@131_61.exe",  

            "md5sum":"ebf2321527d6722dfdff6182fcaa0ad6"    

        },  

        {  

            "size":"581.33kb",  

            "filePath":"http://test.ota.senseplay.cn/download/5000/v3/v1_v3/Penguins.jpg",  

            "md5sum":"0c271af14a751a3e2fd8543788e6652f"  

        }  

    ]  

}


###### 返回结果代码

说明返回示例中存在编码形式的结果进行说明，例如：'0'，成功，'100000'，失败。

######错误提示有：

错误代码   | 返回msg            										| 详细描述
------ | ----------------    										| -----
0      | success         											|  正确返回统一
100000 | 产品CODE有问题        										|  请检查产品管理里对应的产品的code 是否设置。 
100000 | 版本名称不存在    										| 请检查版本管理下传递的版本是否设置，没有请添加版本。  
100000 | 灰度SN码不存在！											| 请检查灰度组是否有添加SN，没有请添加SN。 
100000 | 请检查传递的参数是否有误！									| 请检查请求传递的参数有否有误。 

######备注

ATTENTION:

调试时，请不用忘记配置HOST 如：106.75.122.206  test.ota.senseplay.cn

PS：ANY QUESTION PLS CONTACT ME, THANKS A LOT.


