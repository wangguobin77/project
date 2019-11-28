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

            <div class='row'>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>广告标题:</label>
                        <input  type="text" class="form-control name" value="<?=$data['title']?>" autocomplete="off"  placeholder="请输入描述广告标题" name='name'>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>状态:</label>
                        <input  type="text" class="form-control" disabled autocomplete="off"  placeholder="<?=Yii::$app->params['broadcast_status'][$data['status']]?>" >
                    </div>
                </div>
            </div>

            <!-- 富文本编辑器 -->

            <div style="display:none">
            <textarea id="mk"><?=$tt?></textarea>
                </div>

            <div id="div1" class="toolbar">
            </div>
            <div id="div2" class="text">

            </div>
            <div class="row operate-box">
                <div class="col-md-3 col-xs-3  col-sm-12">
                    <div class="form-group col-md-12 input-xx" style="display:flex;justify-content: flex-start;">
                        <div class="col-md-3 col-xs-12">
                            <button class="btn btn-primary save_adver">
                                保存草稿
                            </button>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <button class="btn btn-primary submin" onclick="put()">
                                提交
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php include_once(NAV_DIR."/footer.php");?>
</div>
<!-- wrapper -->
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

<!-- 删除提示框 -->
<div class="del-box delete">
    <div class="dialog">
        <span class="font_family icon-close cursor"></span>
        <img src="images/warning-large.png" alt="">
        <h6>是否确认删除?</h6>
        <div class="operate-del">
            <div class="cursor cancel btn btn-default"> 取消</div>
            <div class="cursor btn btn-primary confirm">确认</div>

        </div>

    </div>
</div>
<!--tishi-->

<!-- jQuery 3 -->
<script type="text/javascript" src="js/public/wangEditor.min.js"></script>
<script>
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
    editor.create();
    editor.txt.html($('#mk').val());




</script>



