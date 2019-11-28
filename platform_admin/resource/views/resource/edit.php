

<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>


<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 头部导航条 -->
        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li><?php include_once(NAV_DIR."/bottom-menu.php");?></li>
                <span>
                     <button type="button" style="margin-top: -7px;" class="btn  btn-default" onclick="goback()">
                        返回
                    </button>
                </span>
            </ol>
        </div>

        <!-- 内容区域-->
        <div class="row col-md-12">
            <div id="admin-user-form" class="general">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>设备SN (前8位) :</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" disabled value="<?=$data['sn']?>" autocomplete="off" name="client_name" placeholder="请输入" >
                    </div>
                </div>
                <!--<div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>厂商:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" disabled value="<?/*=$data['sn_info']*/?>" autocomplete="off" name="client_id" placeholder="请输入" >
                    </div>
                </div>-->

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>背景大图:</label>
                        <img  id="" border="0" class="pimg"  src="<?=$data['resource_back_img']?>" alt="picture" width="80" height="60">
                        <input type="file" name="picurl" accept="image/png,image/jpg" onchange="imgUpload_sm(this)"/>
                        <input type="hidden" name="resource_back_img">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>ICON 小图:</label>
                        <img  border="0" class="pimg"  src="<?=$data['resource_icon_img']?>" alt="picture" width="80" height="60">
                        <input type="file" name="picurl0" accept="image/png, image/jpg" onchange="imgUpload_sm(this)" data-width="800px" data-height="800px"/>
                        <input type="hidden" name="resource_icon_img">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>终端视频(限MP4):</label>
                        <video controls style="width:100px;height:60px;" src="<?=$data['resource_rotate_video']?>" >
                        </video>
                        <input type="file"  id="videoUpload" onchange="imgUpload_video(this)"  autocomplete="off"  placeholder="请上传" >
                        <input type="hidden" name="resource_rotate_video">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>描述:</label>
                        <textarea class="form-control my-colorpicker1 colorpicker-element bmmc" style="outline:none;" id="desc" ><?=$data['desc']?></textarea>
                    </div>
                </div>


<!--                <input type="hidden" name="buff" value="--><?//=$data['sn']?><!--">-->
<!--                <input type="hidden" name="type" value="--><?//=$data['type']?><!--">-->
                <div class="col-md-6"
                     style='height: 54px;'>
                </div>
              <!--   <div class="col-md-6 save-box">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title"></label>
                        <button class="btn btn-block btn-primary submit-btn">确定修改</button>
                    </div>
                </div> -->

                 <div class="row col-md-10 col-md-offset-1 save-box" style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle"></h4>
                            <div class="col-md-8 tpimg-b">
                                <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div id="admin-user-form" class="non_general">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>设备SN (前8位) :</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" disabled value="<?=$data['sn']?>" autocomplete="off" name="sn" placeholder="请输入" >
                    </div>
                </div>
                <!--<div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>厂商:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" disabled value="<?/*=$data['sn_info']*/?>" autocomplete="off" name="client_id" placeholder="请输入" >
                    </div>
                </div>-->

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>背景大图:</label>
                        <img  id="" border="0" class="pimg"  src="<?=$data['resource_back_img']?>" alt="picture" width="80" height="60">
                        <input type="file" name="picurl" accept="image/png,image/jpg" onchange="imgUpload_sm(this)"/>
                        <input type="hidden" name="resource_back_img">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>ICON 小图:</label>
                        <img  border="0" class="pimg"  src="<?=$data['resource_icon_img']?>" alt="picture" width="80" height="60">
                        <input type="file" name="picurl0" accept="image/png, image/jpg" onchange="imgUpload_sm(this)" data-width="800px" data-height="800px"/>
                        <input type="hidden" name="resource_icon_img">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>终端视频(限MP4):</label>
                        <video controls style="width:100px;height:60px;" src="<?=$data['resource_rotate_video']?>" >
                        </video>
                        <input type="file"  id="videoUpload" onchange="imgUpload_video(this)"  autocomplete="off"  placeholder="请上传" >
                        <input type="hidden" name="resource_rotate_video">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>描述:</label>
                        <textarea class="form-control my-colorpicker1 colorpicker-element bmmc" style="outline:none;" id="desc" ><?=$data['desc']?></textarea>
                    </div>
                </div>



                <div class="col-md-6"
                     style='height: 54px;'>
                </div>
           <!--      <div class="col-md-6 save-box">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title"></label>
                        <button class="btn btn-block btn-primary submit-btn">确定修改2</button>
                    </div>
                </div>

 -->
                 <div class="row col-md-10 col-md-offset-1 save-box" style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle"></h4>
                            <div class="col-md-8 tpimg-b">
                                <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="buff" value="<?=$data['sn']?>">
                <input type="hidden" name="type" value="<?=$data['type']?>">
            </div>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="../../club/web/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
    function goback(){
        history.go(-1);//返回或者history.back();
    }

    // 切换显示模式

    var type = $("input[name='type']").val();
    if(type == 1){
        $(".general").show();
        $(".non_general").hide();
    }else if(type ==2){
        $(".non_general").show();
        $(".general").hide();
    }

    //上传图片
    function imgUpload_sm(e) {
        var t = e.getAttribute("data-width"),
            a = e.getAttribute("data-height"),
            r = [],
            o = $(".imgs");
        r.push(o);
        var result = e.files[0];

        var s = result.size;
        if (s > 1024*1024*2){
            $('.img-des-zc').hide();
            $('.img-ts').show();
            return false;
        }else{
            $('.img-ts').hide();
        }

        var n = new FileReader;
        n.onload = function(o) {
            var l = o.target.result;

            var s = new Image;
            s.onload = function() {
                $.ajax({
                    url:'<?=Url::toRoute('resource/upload')?>',
                    type:'post',
                    dataType:'json',
                    data:{'file':l,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
                    success: function(data) {
                        console.log(data);
                        if( data.code == 0 ) {
                            $(e).next().val(data.data);
                            $(e).prev().attr('src', data.data);
                            // $(e).prev().show();
                        } else {
                            fail(data.message);
                        }
                    }
                });

            };
            s.src = l
        };


        n.readAsDataURL(result)
    }

    $('.select2').select2()
    // 点击确认提交时弹框

    $('.save-box').find('.submit-btn').click(function(){

        var sn = $("input[name='sn']").val();
        var type = $("input[name='type']").val();
        var resource_rotate_video = $("input[name='resource_rotate_video']").val();
        var buff = $("input[name='buff']").val();
        var resource_back_img = $("input[name='resource_back_img']").val();
        var resource_icon_img = $("input[name='resource_icon_img']").val();
        var desc = $("#desc").val();

        // var sn_name_length = getStrLeng(sn);
        // var sn_info_length = getStrLeng(sn_info);
        //
        // if( sn_name_length > 128 || sn_info_length > 128 ) {
        //     fail('字符过长，请重新输入！');return
        // }


        var msg ="确定要修改吗？";
        if(confirm(msg)){

            $.ajax({
                // 验证当前单号是否存在
                url:'<?=Url::toRoute('resource/submit')?>',
                type:'post',
                dataType:'json',
                data:{'sn':sn,
                    'buff':buff,
                    'type':type,
                    'resource_rotate_video':resource_rotate_video,
                    'desc':desc,
                    'resource_back_img':resource_back_img,
                    'resource_icon_img':resource_icon_img,
                    '_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code > 0){
                        fail(data.message);
                    }else {
                        // 保存成功
                        succ(data.message,function(){
                        refresh()
                       });
                    }
                }
            })
        }

    })
    $('.del-box').find('.cancel').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.icon-close').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.confirm').click(function(){
        window.location.href="auditDetail.html";
    })
    // 确认提交框操作end

    function getStrLeng(str){
        var realLength = 0;
        var len = str.length;
        var charCode = -1;
        for(var i = 0; i < len; i++){
            charCode = str.charCodeAt(i);
            if (charCode >= 0 && charCode <= 128) {
                realLength += 1;
            }else{
                // 如果是中文则长度加3
                realLength += 3;
            }
        }
        return realLength;
    }
    function imgUpload_video(e){
        // var fileObj = document.getElementById("videoUpload").files[0]; // js 获取文件对象
        var fileObj = e.files[0];
        if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
            alert("请选择视频");
            return;
        }

        var formFile = new FormData();
        formFile.append("file", fileObj); //加入文件对象
        var data = formFile;

        $.ajax({
            url: "<?=Url::toRoute('resource/video_upload')?>",
            data: data,
            type: "Post",
            dataType: "json",
            cache: false,//上传文件无需缓存
            processData: false,//用于对data参数进行序列化处理 这里必须false
            contentType: false, //必须
            success: function (result) {
                console.log(result);
                if( result.code == 0 ) {
                    $(e).next().val(result.data);
                    $(e).prev().attr('src', result.data);
                    // $(e).prev().show();
                } else {
                    fail(result.message);
                }

            }
        })
    }

</script>





