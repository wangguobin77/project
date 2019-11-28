<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/24
 * Time: 下午3:51
 */
use yii\helpers\Url;

?>
<?php include_once(NAV_DIR."/header.php");?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 搜索 -->
        <form style="display:flex;justify-content:flex-end">
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'>厂商:</label>
                    <select name="m_type_id"  id="m_type_id" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                         <?php if($m_r_data):?>
                             <option value="" >请选择</option>
                         <?php foreach (json_decode($m_r_data,true) as $k=>$v):?>
                             <option <?=$m_id == $v[0]['manufacture_id']?'selected':''?> value="<?=$v[0]['manufacture_id']?>"><?=$v[0]['manufacture_name']?></option>
                         <?php endforeach;else:?>
                             <option value="" >请选择</option>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'>遥控器:</label>
                    <select name="r_type_id"  id="r_type_id" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">

                        <?php if($m_r_data):?>
                            <?php if($r_id != 0):?>

                            <?php foreach (json_decode($m_r_data,true) as $k=>$v):?>
                            <!--只遍历当前厂商下的-->
                            <?php if($k == $m_id):?>
                                <?php foreach ($v as $key=>$val):?>
                                        <option <?=$r_id == $val['r_id']?'selected':''?>  value="<?=$val['r_id']?>"><?=$val['r_name']?></option>
                                <?php endforeach;?>
                            <?php endif;?>

                        <?php endforeach;else:?>
                                <option value="" >请选择</option>
                        <?php endif;endif;?>
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <input name='name_en' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入中文名称" >
                </div>
            </div> -->
            <div class="col-md-1" style='float: right;text-align:right;'>
                <button type="button" class="btn btn-success btn-ss" style="min-width: 96px;">搜索</button>

            </div>
        </form>
        <!-- 搜索end -->
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">厂商</th>
                    <th width="160px"  class="sl">遥控器名称	</th>
                    <th width="160px"  class="sl">遥控器型号	</th>
                    <th width="200px"  class="sl">大类</th>
                    <th width="200px"  class="sl">正式版本 </th>
                    <th width="200px"  class="sl">测试版本</th>
                    <th width="300px"  class="sl">操作 </th>
                </tr>
                </thead>

                <tbody>


                <?php if(count($keymap_list) > 0):?>
                <?php foreach ($keymap_list as $key=>$val){
                    echo  '<tr>';
                    echo '<td class="sl">'.$val['manufacture_name'].'</td>';
                    echo  '<td class="sl">'.$val['r_name'].'</td>';
                        echo  '<td class="sl">'.$val['r_type'].'</td>';

                    echo  '<td class="sl">'.$val['key'].'</td>';

                    if($val['R'] && $val['R'] != '--'){
                        /*存在正式版本可以查看详情 title="<?=Yii::t("app","create_keymap")?>"*/
                        echo '<td class="sl opr-box">';
                        echo '<div class="czuo-box" style="width:fit-content">';

                        echo ' <a href="./index.php?r=keymap/check_r_keymap_show&keymap_id='.$val['keymap_id_R'].'" class="set-btn">'.$val['R'].'</a>';

                        echo '<span class="xian"></span>';

                        echo '<a  onclick="write_r_keymap_json(\''.$val['remote_type_id'].'\',\''.$val['id'].'\')" class="make"   class="set-btn make">生成</a>';

                        echo '</div>';
                        echo '</td>';

                    }else{
                        echo  '<td>'.$val['R'].'</td>';
                    }

                    //echo  '<td>'.$val['B'].'</td>';

                    if(!$val['B'] || $val['B'] == '--'){

                        echo '<td>';
                        echo '<a class="set-btn" href="./index.php?r=keymap/create_keymap&remote_type_id='.$val['remote_type_id'].'&category_id='.$val['id'].'">添加</a>';
                        echo '</td>';
                        echo '<td></td>';
                    }else{

                        echo '<td class="sl opr-box">';
                        echo '<div class="czuo-box" style="width:fit-content">';

                        echo ' <a href="'.Url::toRoute(['keymap/detail_view','keymap_id'=>$val['keymap_id_B'],'request_type'=>1]).'" class="set-btn">'.$val['B'].'</a>';

                        echo '<span class="xian"></span>';

                        echo '<a  onclick="write_r_keymap_json(\''.$val['remote_type_id'].'\',\''.$val['id'].'\')" class="make"   class="set-btn make">生成</a>';

                        echo '<span class="xian"></span>';

                        echo '<a  onclick="prod_send(this)" class="set-btn" data-value="'.$val['keymap_id_B'].'">发布</a>';

                        echo '</div>';
                        echo '</td>';


                        echo '<td class="sl opr-box">';
                        echo '<div class="czuo-box" style="width:fit-content">';
                        echo '<a href="staff-tree.html" class="set-btn">模拟</a>';
                        echo '<span class="xian"></span>';
                        echo '<a href="staff-tree.html" class="set-btn">校验</a>';
                        echo '</div>';
                        echo '</td>';

                    }
                }?>
                <?php endif;?>
                </tbody>
            </table>
        </div>

    </section>
</div>
<!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script type="text/javascript">

    var m_r_data = JSON.parse('<?=$m_r_data?>');

    $('.select2').select2()

    $('.bread-menu').click(function(){
        event.stopPropagation();
        $('.res-992-m-menubox').toggle();

    });

    $('#m_type_id').change(function(){
        var v = $(this).val();

        var r = m_r_data[v];

        var str = '';
        if(!r){
            str = '<option>请选择</option>';
        }else{
            for(var i=0;i<r.length;i++){
                str += '<option value="'+r[i].r_id+'">'+r[i].r_name+'</option>';
            }
        }

        $('#r_type_id').html(str);
    });

    $('.btn-ss').click(function(){
            var v = $('#r_type_id').val();

           window.location.href = '<?=Url::toRoute('keymap/keymap_list')?>&rc='+v;
    });

    //生成keymap json文件
    function write_r_keymap_json(a,b)
    {
        $.ajax({
            url:'<?=Url::toRoute('keymap/write_r_keymap_json')?>',
            type:'get',
            dataType:'json',
            data:{'rc_type':a,'c_type':b},
            success:function (data) {
                if(data.code==0){
                    // 成功
                    succ(data.message);

                }else{

                    fail(data.message);
                }
            }
        })
    }

    function write_beta_keymap_json(a,b)
    {
        $.ajax({
            url:'<?=Url::toRoute('keymap/write_beta_keymap_json')?>',
            type:'get',
            dataType:'json',
            data:{'rc_type':a,'c_type':b},
            success:function (data) {
                if(data.code==0){
                    succ(data.message);
                }else{

                    fail(data.message);
                }
            }
        })
    }

    /**
     * 切换遥控器
     */
    $('.qh-box').find('.rc-wrap').click(function(){
        $('.rc-wrap-list').toggle();
    })
    $(document).click(function(e){
        var con = $('.qh-box');   // 设置目标区域
        if(!con.is(e.target) && con.has(e.target).length === 0){ // Mark
            $('.rc-wrap-list').css('display','none');
        }
    })


    // 发布  相应的td添加一个prod类
    function prod_send(obj)
    {
        var keymap_id = $(obj).attr('data-value');
        if(!keymap_id){
            fail('please issue version');
            return false;
        }
        var _csrf = '<?= Yii::$app->request->csrfToken ?>';
        $.ajax({
            url: '<?=Url::toRoute('keymap/issue')?>',
            type: 'post',
            dataType: 'json',
            data: {'keymap_id':keymap_id,'_csrf':_csrf},
            success:function (data) {
                if(data.code==0){

                    succ(data.message,'<?=Url::toRoute('keymap/keymap_list')?>');

                }else{

                    fail(data.message);
                }
            }
        });
    }




</script>
