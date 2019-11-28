<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/11
 * Time: 下午5:57
 */
?>

<!-- 成功提示 -->
<div class="succ">
    <div class="ts-box">
        <div class="ts-xx ">
            <span class="font_family icon-judge_success_small glyphicon glyphicon-ok-circle"></span>
            <p>保存成功</p>
        </div>
    </div>
</div>

<!-- 失败提示 -->
<div class="fail" >
    <div class="fail-ts">
        <div class="ts-xx">
            <span class='font_family icon-warning_large glyphicon glyphicon-remove-circle' ></span>
            <p>保存失败，请重新尝试！</p>
        </div>
    </div>
</div>
<!-- 删除提示框 -->
<div class="del-box delete">
    <div class="dialog">
        <span class="font_family icon-close fa fa-close"></span>
        <img src="/static/images/warning-large.png" alt="">
        <h6 class="del-title">是否确认删除?</h6>
        <div class="operate-del">
            <div class="cursor cancel btn btn-default"> 取消</div>
            <div class="cursor confirm  btn btn-primary">确认</div>
        </div>

    </div>
</div>
<footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
        </div>
        <!-- Default to the left -->
        <strong>Copyright ©  2017 上海感悟通信科技有限公司版权所有</strong> 沪ICP备17022468号

    </footer>

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 3 -->
<script src="/static/js/public/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="/static/js/public/bootstrap.min.js"></script>
<script   src="/static/js/public/common.js"></script>
<script   src="/static/js/public/ts.js"></script>
<script   src="/static/js/public/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/js/public/adminlte.min.js"></script>
<!--  -->
<script src="/static/js/public/bootstrap-table-expandable.js"></script>

<script type="text/javascript">
    // 弹框取消按键
    $('.del-box').find('.cancel').click(function(){
        $(this).parent().parent().parent().hide();

    })
    // 弹框关闭 按键
    $('.del-box').find('.icon-close').click(function(){
        $(this).parent().parent().hide();
    })

    $('.btn-back-call').click(function(){
        window.history.back(-1);
    });
</script>

</body>
