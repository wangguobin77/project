<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/11
 * Time: 下午5:57
 */
?>

<!--tishi-->
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
<div class="fail-ts" style='z-index:10000'>
    <div class="ts-xx">
        <span class='font_family icon-warning_large'></span>
        <p>保存失败，请重新尝试！</p>
    </div>
</div>

<!-- 删除提示框 -->
<div class="del-box delete">
    <div class="dialog">
        <span class="font_family icon-close cursor"></span>
        <img src="/static/images/warning-large.png" alt="">
        <h6>是否确认删除?</h6>
        <div class="operate-del">
            <div class="cursor cancel btn btn-default"> 取消</div>
            <div class="cursor btn btn-primary confirm">确认</div>

        </div>

    </div>
</div>
<!--tishi-->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
        </div>
        <!-- Default to the left -->
        <strong>Copyright ©  2017 上海感悟通信科技有限公司版权所有</strong> 沪ICP备17022468号

    </footer>

    <!-- Control Sidebar 右侧滑出菜单处理 -->
    <aside class="control-sidebar control-sidebar-dark" style="display:none;">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="../../keymap/web/#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="../../keymap/web/#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="../../keymap/web/javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="../../keymap/web/javascript:;">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="pull-right-container">
                                <span class="label label-danger pull-right">70%</span>
                              </span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="/static/js/public/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/static/js/public/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/js/public/adminlte.min.js"></script>
<!-- 信息提示  -->
<script src='/static/js/public/ts.js'></script>
<script src='/static/js/public/common.js'></script>
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
</html>
