<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="css/advertising/setadvertising.css">



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content container-fluid">
            <div class="right-box p-b-20 row">
                <button type="button" class="btn  btn-default btn-back-call" onclick='javascript:history.back();'>
                    返回
                </button>
            </div>
            <form id="sub-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class='row'>
                    <div class="col-md-6 col-xs-12  col-sm-12">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-3 title'>广告标题:</label>
                            <input  type="text" class="form-control name" autocomplete="off"  placeholder="请输入描述广告标题" name='name'>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12  col-sm-12">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-3 title'>简介:</label>
                            <input  type="text" class="form-control desc_short" autocomplete="off"  placeholder="广告简介" name='desc_short'>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12  col-sm-12">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-3 title'>状态:</label>
                            <input  type="text" class="form-control status" disabled autocomplete="off"  placeholder="草稿" name='status'>
                        </div>
                    </div>
                </div>
                <!--富文本编辑器的内容-->
                <input class="input-xlarge focused" style="float:left;" name="content" type="hidden" id="content1">
            </form>
            <!-- 富文本编辑器 -->
            <div id="div1" class="toolbar">
            </div>
            <div id="div2" class="text">

            </div>

            <!--封面图片 start-->
            <div class="file-box">
                <img id="preview" />
                <input type="hidden" id="cover" name="cover" class="txt" placeholder="预览">
                <input type="file"  id = "input_file" accept="image/gif,image/jpeg,image/jpg,image/png,image/svg" onchange="imgPreview(this)" >
            </div>
            <!--封面图片 end-->

            <div class="row operate-box">
                <div class="col-md-1 col-xs-12" style="padding-left:0 !important;">
                    <button class="btn btn-primary submin" onclick="put()">
                        提交
                    </button>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php include_once(NAV_DIR."/footer.php");?>
    <!-- ./wrapper -->
</div>
    <!-- 删除提示框 -->
    <div class="del-box delete" >
        <div class="dialog">
            <span class="font_family icon-close fa fa-close"></span>
            <img src="images/warning-large.png" alt="">
            <h6>是否确认删除?</h6>
            <div class="operate-del">
                <div class="cursor cancel btn btn-default"> 取消</div>
                <div class="cursor confirm  btn btn-primary">确认</div>
            </div>

        </div>
    </div>

    <!-- 成功提示 -->
    <div class="succ" style="position:fixed;top:0;bottom:0;left:0;right:0;display: none;z-index:1000;">
        <div class="ts-box" style="z-index:10000;display:block;">
            <div class="ts-xx ">
                <span class="font_family icon-judge_success_small"></span>
                <p>保存成功</p>

            </div>
        </div>
    </div>

    <!-- 失败提示 -->
    <div class="fail" style="position:fixed;top:0;bottom:0;left:0;right:0;display: none;z-index:1000;">
        <div class="fail-ts">
            <div class="ts-xx">
                <span class='font_family icon-warning_large'></span>
                <p>保存失败，请重新尝试！</p>
            </div>
        </div>
    </div>

<!-- jQuery 3 -->
<script type="text/javascript" src="js/public/wangEditor.min.js"></script>
<script type="text/javascript">
    var httpUrl = "http://test.cloud.leaplink.cn";//临时
    var E = window.wangEditor
    var editor = new E('#div1', '#div2')  // 两个参数也可以传入 elem 对象，class 选择器
    editor.customConfig = {
        uploadImgShowBase64:true,
        uploadImgMaxLength:1,
        showLinkImg:false
    }
    editor.customConfig.menus = [
        'head',  // 标题
        'bold',  // 粗体
        'fontSize',  // 字号
        'fontName',  // 字体
        'italic',  // 斜体
        'underline',  // 下划线
        'strikeThrough',  // 删除线
        'foreColor',  // 文字颜色
        'backColor',  // 背景颜色
        'list',  // 列表
        'justify',  // 对齐方式
        'quote',  // 引用
        'image',  // 插入图片
        'code',  // 插入代码
        'undo',  // 撤销
        'redo',  // 重复

    ]

    // 插入网络图片的地址
    editor.customConfig.linkImgCallback = function (url) {
        console.log(url) // url 即插入图片的地址
    }
    //获取富文本中的html
    // editor.customConfig.onchange = function (html) {
    //     console.log(html)
    // }

    //自定义提手方法
    editor.customConfig.customAlert = function (info) {
        fail(info)
    }

    // 配置服务器端地址
    editor.customConfig.uploadImgServer = 'http://test.cloud.leaplink.cn/api/fs/upload/upload?dir=brocasts';

    editor.customConfig.uploadFileName = 'file';
    editor.customConfig.pasteFilterStyle = false;
    editor.customConfig.uploadImgMaxLength = 5;
    editor.customConfig.uploadImgHooks = {
        before: function (xhr, editor, files) {
            // 图片上传之前触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件

            // 如果返回的结果是 {prevent: true, msg: 'xxxx'} 则表示用户放弃上传
            // return {
            //     prevent: true,
            //     msg: '放弃上传'
            // }
        },
        // 上传超时
        timeout: function (xhr, editor) {
            layer.msg('上传超时！')
        },
        success: function (xhr, editor, result) {
            // 图片上传并返回结果，图片插入成功之后触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
            console.log(1111);
            console.log(result);
        },
        fail: function (xhr, editor, result) {
            // 图片上传并返回结果，但图片插入错误时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        error: function (xhr, editor) {
            // 图片上传出错时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },
        timeout: function (xhr, editor) {
            // 图片上传超时时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },

        // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
        // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
        customInsert: function (insertImg, result, editor) {
            // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
            // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果

            // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
             var url = result.retData.url;
              insertImg(url);

            // result 必须是一个 JSON 格式字符串！！！否则报错
           // console.log(result);
           // if (result.code == 1) {
            //    var url = result.data.url;
            //    url.forEach(function (e) {
                    //insertImg(e);
           //     })
           // } else {
           //     console.log(result.msg);
           // }
        }
    }

    // 自定义图片上传事件
   // editor.customConfig.customUploadImg = function (files, insert) {
  //      console.log(files);
        // files 是 input 中选中的文件列表
        // insert 是获取图片 url 后，插入到编辑器的方法

        // 上传代码返回结果之后，将图片插入到编辑器中
       //  insert("test.cloud.leaplink.cn/upload/upload_pic");
 //   }
    editor.customConfig.customAlert = function (info) {
       console.log(info)
    };
    editor.create()



    const put = () =>{
        var name=$('.name').val();
        var cover = $('#cover').val();
        var desc_short = $('.desc_short').val();
        if(name==''){
            fail('名称不能为空')
            return false;
        }
        if(!fun.isMc(name)){
            fail('名称长度不符合规范')
            return false;
        }


        if(editor.txt.html()==''){ //获取富文本中的内容
            fail('请输入广告内容')
            return false;
        }

       // var data =$('#sub-form').serializeObject();
        $.ajax({
            url:'<?=Url::toRoute("broadcasts/add")?>',
            type:'post',
            dataType:'json',
            data:{'name':name,'desc_short':desc_short,'cover':cover,'content':editor.txt.html(),'_csrf':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                console.log(data.code);
                if(data.code==0){
                    succ('提交成功')
                    location.href = "<?=Url::toRoute(['broadcasts/list'])?>"
                }else{
                    fail('提交失败')
                }
            }
        });
    };


    function imgPreview(fileDom) {
        //判断是否支持FileReader
        if(window.FileReader) {
            var reader = new FileReader();
        } else {
            alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
        }
        //获取文件
        var file = fileDom.files[0];
        var imageType = /^image\//;
        //是否是图片
        if(!imageType.test(file.type)) {
            alert("请选择图片！");
            return;
        }
        //读取完成
        reader.onload = function(e) {
            //获取图片dom
            var img = document.getElementById("preview");
            //图片路径设置为读取的图片
            img.src = e.target.result;

            on_upload();//上传
        };
        reader.readAsDataURL(file);
    }

    function on_upload(){
        var formData = new FormData();
        formData.append('file', $('#input_file')[0].files[0]);  //添加图片信息的参数
        //  formData.append('sizeid',123);  //添加其他参数
        $.ajax({
            url: 'http://test.cloud.leaplink.cn/api/fs/upload/upload?dir=brocasts',
            type: 'POST',
            cache: false, //上传文件不需要缓存
            data: formData,
            processData: false, // 告诉jQuery不要去处理发送的数据
            contentType: false, // 告诉jQuery不要去设置Content-Type请求头
            success: function (data) {
                var url = data.retData.url;
                if(url){
                    $('#cover').val(url);
                    return;
                }
                fail("上传失败");
            },
            error: function (data) {
                fail("上传失败");
            }
        })
    }


</script>
