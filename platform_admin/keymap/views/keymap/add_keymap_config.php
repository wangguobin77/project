<?php
use yii\helpers\Url;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="/static/css/public/editVer.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">

        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <?php include_once(NAV_DIR."/bottom-menu.php");?>
                <span>
                    <button type="button" class="btn  btn-default back">
                        返回
                    </button>
                </span>
            </ol>


        </div>

        <!-- 命令泪飙区域  -->
        <div class=" neirong-wrap" style="min-width:1200px;min-height:1000px">
        <div class="order-box col-md-12">
            <div class="zuoce-box col-md-2">
                <div class="ml-list">
                    <h4><?=Yii::t('app','An_ADDED_COMMAND')?></h4>
                    <span>（<?=count($keymap_data_list)?>）</span>
                </div>
                <ul class='zuoce-box-list col-md-3'>
                    <!-- <li class='sl add-commond cursor'></li>-->
                    <?php  foreach ($keymap_data_list as $key=>$val):?>
                        <li class='sl ml-item'><a class="" href="<?=Url::toRoute(['keymap/detail_view','keymap_id'=>$keymap_id,'id'=>$val['id'],'request_type'=>2])?>" ><?=Yii::t('db',$val['command'])?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>

            <form action="<?=Url::toRoute('keymap/dis_jsondata')?>" id="form_keymap" method="post" onsubmit="return toVaild(this);">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input type="hidden" id="keymap_id" name="keymap_id" value="<?=$keymap_id?>">
                <input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>">
                <input type="hidden" id="remote_type_id" name="remote_type_id" value="<?=$remote_type_id?>">
                <!--  命令详情 右侧  -->
                <div class="youce-box youce-detail-box col-md-9" >
                    <h4>命令编辑</h4>
                    <div class='ter-box'>
                        <div class="mlbj-xx">
                            <h5><?=Yii::t('app','COMMAND_NAME')?></h5>
                            <select  name="COMMAND" class="sl" id="command" data-v="<?=$keymap_id?>" style="margin-left: 15px">
                                <option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>
                                <?php
                                foreach ($command_list as $key=>$val){
                                     echo  '<option value="'.$val.'">'.$val.'</option>';
                                   /*  echo  '<option value="'.$val.'">'.Yii::t('db',$val).'</option>'; */
                                }
                                ?>

                            </select>
                        </div>

                        <div class="mlbj-xx">
                            <h5><?=Yii::t('app','TRIGGER_TYPE')?></h5>
                            <select name="KEYMAP_TYPE" class="sl" id="keymap_type" style="margin-left: 15px">
                                <option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>
                                <?php
                                foreach ($drive_way as $key=>$val){
                                     echo  '<option value="'.$val.'">'.$val.'</option>';
                                    /* echo  '<option value="'.$val.'">'.Yii::t('db',$val).'</option>'; */
                                }
                                ?>
                            </select>

                        </div>

                        <!--按键设置 start-->
                        <div class="mlbj-xx select-mlxx-box" id="an_key" style="display:none">
                            <div class="mlbj-xx select-mlxx-box">
                                <h5><?=Yii::t('app','KEY_CONFIG')?></h5>
                                <div class="select-box">
                                    <select id="event_1" class="sl" name="event_1">

                                        <option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>

                                    </select>
                                    <select id="event_2" class="sl" name="event_2">

                                        <option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>

                                    </select>
                                    <select name="EVENT" class="sl" id="event_3">

                                        <option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--按键设置end-->
                        <!-- 触发条件 盒子 -->
                        <div class="tjbox condition_block" style="overflow: auto;display:none">
                            <div id="tj_list">

                            </div>
                        </div>

                        <!-- 添加条件 -->
                        <div class="mlbj-xx select-mlxx-box add-box">
                            <h5></h5>
                            <div class="select-box addtj-box cursor add-tj">
                                <span class='font_family icon-add_list'></span>
                                <span style="margin-left:8px;"><?=Yii::t('app','ADD_CONDITION_SETTING')?></span>
                            </div>
                            <span class='font_family fa fa-trash del' data-toggle="tooltip" data-placement="top" title="默认删除最后一条" style="font-size:24px;cursor:pointer;"></span>
                        </div>
                        <!-- 正常参数 -->
                        <!-- <div class="mlbj-xx select-mlxx-box">
                             <h5>正常参数:</h5>
                             <div class="select-box">
                                 <input type="text" class="sl" />
                                 <input type="text" class="sl" />
                                 <input type="text" class="sl" />

                             </div>
                         </div>-->
                        <!--参数-->
                        <div id="pars" style="display:none">


                        </div>
                        <!--参数 end-->

                        <!-- 保存按键 -->
                        <div class="mlbj-xx select-mlxx-box">
                            <h5></h5>
                            <div class="select-box">
                                <input type="submit" class="bc-btn btn btn-primary" value="<?=Yii::t('app','SAVE_SETTING')?>">
                                <!-- <button type="button" class='bc-btn'>保存</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>



        </div>
        </div>
    </section>
</div>

<!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>


<script type="text/javascript">

    $('.back').click(function(){
        window.location.href = '<?=Url::toRoute(['keymap/keymap_list','rc'=>$remote_type_id])?>';
    });

    // 导入命令 框关闭
    $('.close').click(function(){
        $('.call-box').hide();
    });
    // 点击导入命令
    $('.daoru-btn').click(function(){
        $('.commond-have').show();
    });



    //命令导入确定时
    $('button.have-button').click(function(e) {
        e.preventDefault();
        var names = document.getElementsByName("bjjb");
        var flag = false;
        for (var i = 0; i < names.length; i++) {
            if (names[i].checked) {
                flag = true;
                // alert('成功');
                succ('成功');
            }
        }


    })
</script>

<script>
    var jsonData = {
        'eventData':{},//按键相关
        'judgeData':{},//条件类型
        'conditionData':{},//条件值相关
        'paramsData':{},//参数相关
    }

    //判断对象json是否唯恐
    function isEmptyObject(e) {
        var t;
        for(t in e){
            return !1;
        }
        return !0;
    }

    $(document).ready(function(){
        getAllkeyEvent();
    });
    $('#command').change(function(){
        $('#pars').css('display','none');
    });
    //联动 按键 start
    $('#keymap_type').change(function(){
        var event_drive = $(this).val();
        //判断 是否是事件驱动 是：需要展示按键设置 不是：需要隐藏按键设置
        if(event_drive == 'EVENT_DRIVE'){
            if(!isEmptyObject(jsonData.eventData)){

                var str_e = '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
                str_e = '';
                for(var v in jsonData.eventData){
                    str_e += '<option>'+v+'</option>';

                }
                $('#event_1').html(str_e); //2019-03-19


            }else{
                $('#event_1').html('<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>');
                /* $('#an_key').css('display','none');*/
            }
            //$('#an_key').css('display','block');//2019-03-28
            event_3option();
        }else{
            //$('#event_1').html('<option value="0">--请选择--</option>');
            $('#an_key').css('display','none');
        }

       /* $('#event_2').html('<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>');
        $('#event_3').html('<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>');*/ //2019-03-28


        //根据命令 异步获取
        paramsJoinStr();//条件相关显示
    })

    //2019-03-28    新增出发功能 start
    function event_3option()
    {
        var v1 = $('#event_1').val();
        if(v1 == 0){
            return false;
        }

        if(!isEmptyObject(jsonData.eventData)){
            var str_j = '';
            for(var v in jsonData.eventData[v1]){
                str_j += '<option>'+v+'</option>';

            }

            $('#event_2').html(str_j);
        }

        //new
        if($('#event_2').val() && $('#event_2').val() != 0){
            var v1 = $('#event_1').val();
            var v2 = $('#event_2').val();
            if(v1 == 0 || v2 == 0){
                return false;
            }

            var str_jj = '';
            if(!isEmptyObject(jsonData.eventData[v1][v2]['keycode'])){

                for(var i=0;i<jsonData.eventData[v1][v2]['keycode'].length;i++){
                    str_jj += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['language_name']+'</option>';

                }
            }else{
                str_jj = '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>'
            }

            $('#event_3').html(str_jj);

        }
        $('#an_key').css('display','block');
    }
    //end
    $('#event_1').change(function(){
        $('#event_3').html('<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>');
        var v1 = $(this).val();
        if(v1 == 0){
            return false;
        }
        if(!isEmptyObject(jsonData.eventData)){
            var str_j = '';
            for(var v in jsonData.eventData[v1]){
                str_j += '<option>'+v+'</option>';

            }

            $('#event_2').html(str_j);
        }

        //new
        if($('#event_2').val() && $('#event_2').val() != 0){
            var v1 = $('#event_1').val();
            var v2 = $('#event_2').val();
            if(v1 == 0 || v2 == 0){
                return false;
            }

            var str_jj = '';
            if(!isEmptyObject(jsonData.eventData[v1][v2]['keycode'])){

                for(var i=0;i<jsonData.eventData[v1][v2]['keycode'].length;i++){
                    /* str_j += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'</option>';*/
                    str_jj += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['language_name']+'</option>';

                }
            }else{
                str_jj = '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>'
            }

            $('#event_3').html(str_jj);
        }
        //new end
    });

    $('#event_2').change(function(){
        var v1 = $('#event_1').val();
        var v2 = $(this).val();
        if(v1 == 0 || v2 == 0){
            return false;
        }


        var str_j = '';
        if(!isEmptyObject(jsonData.eventData[v1][v2]['keycode'])){

            for(var i=0;i<jsonData.eventData[v1][v2]['keycode'].length;i++){
                /* str_j += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'</option>';*/
                str_j += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['language_name']+'</option>';

            }
        }else{
            str_j = '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>'
        }

        $('#event_3').html(str_j);


    });
    //按键 end

    //删除条件 每次只能删除最后一个
    $('.del').click(function(){
        var children_num = $('#tj_list').children().length;
        if(children_num <= 0){
            fail('没有可删除的条件');
            return false;
        }
        var index = parseInt(children_num-1);
        $('#tj_list').children().eq(index).remove();
        succ('删除成功！');

        // bottom-right高度处理
        var box=$('.tjbox')
        var height=$('.tjbox').height();


        $('.bottom').css('height',$('.tjbox').height()+300);
        $('.banner').css('height',$('.tjbox').height()+913);
        $('.bottom-right').css('height',$('.tjbox').height()+800);
    });

    /*当select 值只有一条时无法触发onchange 只能click*/
    $('#event_2').click(function(){
        var one = $(this).find('option').length;
        if(one == 1){
            var v1 = $('#event_1').val();
            var v2 = $(this).val();
            if(v1 == 0 || v2 == 0){
                return false;
            }
            var str_j = '';
            if(!isEmptyObject(jsonData.eventData[v1][v2]['keycode'])){

                for(var i=0;i<jsonData.eventData[v1][v2]['keycode'].length;i++){
                    /*str_j += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'</option>';*/
                    str_j += '<option value="'+jsonData.eventData[v1][v2]['keycode'][i]['key']+'">'+jsonData.eventData[v1][v2]['keycode'][i]['language_name']+'</option>';

                }
            }else{
                str_j = '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>'
            }

            $('#event_3').html(str_j);

        }

    });
    /*end*/
    /**************************函数************************/
    //条件相关处理 联动 start
    function judge_fun(obj){
        //$('.condition_value').html('<option>--请选择--</option>');
        $(obj).parent().find('select').eq(2).html('<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>');
        var num = $(obj).get(0).selectedIndex;

        var k_type = '';
        if(num <= 3){
            //condition
            if(!isEmptyObject(jsonData.conditionData.condition)){
                for(var k in jsonData.conditionData.condition){
                    /* k_type += '<option value="'+k+'">'+k+'</option>';*/
                    k_type += '<option value="'+k+'">'+jsonData.conditionData.condition[k][1]+'</option>';
                }
            }else{
                k_type += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
            }
            $(obj).parent().find('select').eq(1).html(k_type);
        }else{
            //展示keycode
            if(!isEmptyObject(jsonData.conditionData.keycode)){
                for(var k in jsonData.conditionData.keycode){
                    /* k_type += '<option value="'+k+'">'+k+'</option>';*/
                    k_type += '<option value="'+k+'">'+jsonData.conditionData.keycode[k][1]+'</option>';
                }
            }else{
                k_type += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
            }

            $(obj).parent().find('select').eq(1).html(k_type);
        }

        //new
        if($(obj).parent().find('select').eq(1).val() && $(obj).parent().find('select').eq(1).val() != 0){
            condition_type_fun($(obj).parent().find('select').eq(1));
        }
        //end
    }

    function condition_type_fun(obj)
    {
        var o = $(obj).parent().find('select').eq(0).get(0).selectedIndex;
        var v = $(obj).val();

        var condition_value_str = '';
        if(o <= 3){
            if(!isEmptyObject(jsonData.conditionData.condition)){
                /* for(var i=0;i<jsonData.conditionData.condition[v].length;i++){
                 condition_value_str += '<option value="'+jsonData.conditionData.condition[v][i]+'">'+jsonData.conditionData.condition[v][i]+'</option>';
                 }*/

                for(var i=0;i<jsonData.conditionData.condition[v][0].length;i++){
                    condition_value_str += '<option value="'+jsonData.conditionData.condition[v][0][i][0]+'">'+jsonData.conditionData.condition[v][0][i][1]+'</option>';
                }
            }else{
                condition_value_str += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
            }

        }else{
            if(!isEmptyObject(jsonData.conditionData.condition)){
                /* for(var i=0;i<jsonData.conditionData.keycode[v].length;i++){
                 condition_value_str += '<option value="'+jsonData.conditionData.keycode[v][i]+'">'+jsonData.conditionData.keycode[v][i]+'</option>';
                 }*/

                for(var i=0;i<jsonData.conditionData.keycode[v][0].length;i++){
                    condition_value_str += '<option value="'+jsonData.conditionData.keycode[v][0][i][0]+'">'+jsonData.conditionData.keycode[v][0][i][1]+'</option>';
                }
            }else{
                condition_value_str += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
            }

        }

        $(obj).parent().find('select').eq(2).html(condition_value_str);

    }
    //条件相关 end

    /*添加条件处理end*/
    /*当select 值只有一条时无法触发onchange 只能click*/
    $('.condition_type').click(function(){
        var one = $(this).find('option').length;

        if(one == 1 && $(this).val() != 0){

            condition_type_fun($(this));
        }

    });
    /*end*/
    /*添加条件处理 start*/
    $('.add-tj').click(function(){
        var children_num = $('#tj_list').children().length;

        addConditionList(children_num);


        $('.condition_block').css('display','block');
    });
    /*添加条件*/
    function addConditionList(index)
    {

        //展示条件type
        var judge_type_str = '';
        for(var i=0;i<jsonData.judgeData.length;i++){
            /* judge_type_str += '<option value="'+jsonData.judgeData[i]['key']+'" data-value="'+i+'">'+jsonData.judgeData[i]['key']+'</option>';*/
            judge_type_str += '<option value="'+jsonData.judgeData[i]['key']+'" data-value="'+i+'">'+jsonData.judgeData[i]['language_name']+'</option>';
        }

        var str = '';
        str += '<div class="mlbj-xx select-mlxx-box trigger-box">';
        str += '<h5><?=Yii::t("app","CONDITION_SETTING")?> </h5>';
        str += '<div class="select-box">';
        str += '<span>'+index+'</span>';
        str += '<select name="CONDITIONS['+index+'][CONDITION_JUDGE_TYPE]" onchange="judge_fun(this)" class="sl condition_judge_type">';
        /*  str += '<option>--请选择--</option>';*/
        str += judge_type_str;
        str += '</select>';
        str += '<select name="CONDITIONS['+index+'][CONDITION_TYPE]" onchange="condition_type_fun(this)" class="sl condition_type">';
        str += '<option>--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
        str += '</select>';
        str += '<select name="CONDITIONS['+index+'][CONDITION_VALUE]" class="sl condition_value">';
        str += '<option>--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';
        str += '</select>';
        str += '<span></span>';
        str += '</div>';
        str += '</div>';

        $('#tj_list').append(str);
    }

    //参数字符串拼接
    function paramsJoinStr()
    {
        var com_v = $('#command').val();//前面选的命令值v
        if(!com_v || com_v == 0){
            return false;
        }

        if(isEmptyObject(jsonData.paramsData.analog_value)){
            if(jsonData.paramsData.params_num[com_v]['normal_num'] > 0){
                var n_n = jsonData.paramsData.params_num[com_v]['normal_num'];//偏移量参数个数
                noAnalog_str(0,n_n,jsonData.paramsData.analog_value);
                $('#pars').css('display','block');
            }
            return false;
        }

        /* if(jsonData.paramsData.analog_value.length > 0){*/
        if(!isEmptyObject(jsonData.paramsData.analog_value)){
            /* var s = jsonData.paramsData.analog_value.length;*/
            var s = 0;
            for(var item in jsonData.paramsData.analog_value){
                s++;
            }
        }else{
            return false;
        }

        //大于0 说明有操作手只说
        var category_id = "<?=$category_id?>";

        if(s > 0 && category_id ==5 ||  category_id ==6){
            var a_n = jsonData.paramsData.params_num[com_v]['analog_num'];//偏移量参数个数
            var n_n = jsonData.paramsData.params_num[com_v]['normal_num'];//偏移量参数个数
            analog_str(a_n,n_n,jsonData.paramsData.analog_value);
        }else{
            var a_n = jsonData.paramsData.params_num[com_v]['analog_num'];//偏移量参数个数
            var n_n = jsonData.paramsData.params_num[com_v]['normal_num'];//偏移量参数个数
            noAnalog_str(a_n,n_n,jsonData.paramsData.analog_value);
        }

        $('#pars').css('display','block');
    }


    //偏移量参数
    function very_tc(obj)
    {
        var v = $(obj).val();
        if(v.length > 2){
            fail('长度超出限制！');
            $(obj).val('00');
            return false;
        }
        if(!v){
            fail('格式不正确！');
            $(obj).val('00');
            return false;
        }
        var hex_v = '0X'+v;
        if(!isNaN(v)){
            return true;
        }else if($.isNumeric(hex_v)){
            return true;
        }else{
            fail('格式不正确');
            $(obj).val('00');
            return false;

        }
    }
    /**
     * @param a_v 偏移量值
     *@param a_n 偏移量所带参数个数
     *@param n_n 正常参数所带参数个数
     *
     */
    function analog_str(a_n,n_n,a_v)
    {
        var par_str = '';
        if(a_n > 0){
            par_str += '<div class="mlbj-xx select-mlxx-box trigger-box">';
//            par_str +=  '<h4 style="position:relative"><?//=Yii::t("app","SIMULATION_PARAMETERS")?>//<span class="cdcs simulation"></span></h4><?//=Yii::t('app','USA')?>//';
            par_str +=  '<h5 style="position:relative"><?=Yii::t("app","SIMULATION_PARAMETERS")?> <?=Yii::t('app','USA')?></h5>';
            par_str +=  '<div class="select-box zccs" style="flex-wrap:wrap;padding: 0 48px;">';
            for(var i=0;i<a_n;i++){
                par_str += '<select name="PARAMS['+i+'][TYPE][ANALOG][USA]" class="U_list sl itemcs">';
                par_str += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';

                for(var k in a_v){
                    par_str += '<option value="'+k+'">'+a_v[k]+'</option>';
                }

                par_str +=   '</select>';
            }


            par_str +=   '</div>';
            par_str +=   '</div>';
        }

        if(a_n > 0){
            par_str += '<div class="mlbj-xx select-mlxx-box trigger-box">';
            par_str +=  '<h5><?=Yii::t('app','JAPAN')?></h5>';
            par_str +=  '<div class="select-box zccs" style="flex-wrap:wrap;padding: 0 48px;">';
            for(var i=0;i<a_n;i++){
                par_str += '<select name="PARAMS['+i+'][TYPE][ANALOG][JAPAN]" class="J_list sl itemcs">';
                par_str += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';

                for(var k in a_v){
                    par_str += '<option value="'+k+'">'+a_v[k]+'</option>';
                }

                par_str +=   '</select>';
            }


            par_str +=   '</div>';
            par_str +=   '</div>';
        }

        if(a_n > 0){
            par_str += '<div class="mlbj-xx select-mlxx-box trigger-box">';
            par_str +=  '<h4><?=Yii::t('app','CHINA')?></h4>';
            par_str +=  '<div class="select-box zccs" style="flex-wrap:wrap;padding: 0 48px;">';
            for(var i=0;i<a_n;i++){
                par_str += '<select name="PARAMS['+i+'][TYPE][ANALOG][CHINA]" class="C_list sl itemcs">';
                par_str += '<option value="0">--<?=Yii::t('app','PLEASE_CHOOSE')?>--</option>';

                for(var k in a_v){
                    par_str += '<option value="'+k+'">'+a_v[k]+'</option>';
                }

                par_str +=   '</select>';
            }


            par_str +=   '</div>';
            par_str +=   '</div>';
        }

        //正常参数
        if(n_n > 0){
            par_str += '<div class="mlbj-xx select-mlxx-box">';
            par_str += '<h5><?=Yii::t('app','OSPF_PARAMETERS')?></h5>';

            if(typeof(i) == 'undefined'){
                var i = 0;
            }
            par_str+='<div class="select-box zccs" style="flex-wrap:wrap;padding: 0 48px;">';

            for(var j=0;j<n_n;j++){

                // par_str += '<input type="text" name="PARAMS['+parseInt(i+j)+'][TYPE][TRANSPARENT]" class="tc" />';
                par_str += '<input type="text" onblur="very_tc(this)" name="PARAMS['+parseInt(i+j)+'][TYPE][TRANSPARENT]" class="tc sl itemcs form-control   " autocomplete="off"/>';
            }
            par_str += '</div>';

            par_str += '</div>';
        }

        $('#pars').html(par_str);

    }

    //没有偏移量字符串拼接
    /**
     * @param a_v 偏移量值
     *@param a_n 偏移量所带参数个数
     *@param n_n 正常参数所带参数个数
     *
     */

    function noAnalog_str(a_n,n_n,a_v)
    {

        var par_str = '';
        if(a_n > 0){
            par_str += '<div class="mlbj-xx select-mlxx-box trigger-box">';
//            par_str +=  '<h4 style="position:relative"><?//=Yii::t('app','SIMULATION_PARAMETERS')?>//<span class="cdcs simulation"></span></h4>';
            par_str +=  '<h5><?=Yii::t("app","SIMULATION_PARAMETERS")?></h5>';
            par_str +=  '<div class="select-box zccs" style="flex-wrap:wrap;padding: 0 48px;">';
            for(var i=0;i<a_n;i++){
                par_str += '<select name="PARAMS['+i+'][TYPE][ANALOG]" class="A_list sl itemcs">';
                /*for(var k=0;k<a_v.length;k++){
                 par_str += '<option value="'+a_v[k]+'">'+a_v[k]+'</option>';
                 }*/
                for(var k in a_v){
                    par_str += '<option value="'+k+'">'+a_v[k]+'</option>';
                }

                par_str +=   '</select>';
            }


            par_str +=   '</div>';
            par_str +=   '</div>';
        }

        if(n_n > 0){
            par_str += '<div class="mlbj-xx select-mlxx-box">';
            par_str += '<h5><?=Yii::t("app","OSPF_PARAMETERS")?> </h5>';

            if(typeof(i) == 'undefined'){
                var i = 0;
            }
            par_str += '<div class="select-box zccs" style="flex-wrap:wrap;padding: 0 48px;">';

            for(var j=0;j<n_n;j++){
                par_str += '<input type="text" onblur="very_tc(this)" name="PARAMS['+parseInt(i+j)+'][TYPE][TRANSPARENT]" class="tc sl itemcs" />';
            }

            par_str += '</div>';

            par_str += '</div>';
        }
        $('#pars').html(par_str);

    }
    /**
     * 获取按键的相关信息json数组
     * 1 键盘操作事件类型 以及 展示所有的按键
     * 2 条件类型
     * 3 条件值相关
     * 4 参数设置
     */
    function getAllkeyEvent()
    {
        var keymap_id = $('#keymap_id').val();
        var category_id = $('#category_id').val();
        var remote_type_id = $('#remote_type_id').val();

        if(!keymap_id){
            fail('请重新创建keymap！');
        }
        if(!category_id){
            fail('请重新创建keymap！');
        }
        if(!remote_type_id){
            fail('请重新创建keymap！');
        }
        var _csrf = '<?= Yii::$app->request->csrfToken ?>';
        $.ajax({
            url:'<?=Url::toRoute('keymap/get_all_jsondata')?>',
            type:'post',
            dataType:'json',
            data:{'keymap_id':keymap_id,'category_id':category_id,'remote_type_id':remote_type_id,'_csrf':_csrf},
            success:function (data) {
                if(data.code == 0){

                    jsonData.eventData = data.data.rc_all_key_sort_list;
                    jsonData.judgeData = data.data.judge_type_list;
                    jsonData.conditionData = data.data.Condition_list;
                    jsonData.paramsData = data.data.params_list;
                }
            }
        })

    }

    /*验证表单字段值的合法性*/
    function toVaild()
    {
        var command = $('#command').val();

        var keymap_type = $('#keymap_type').val();

        if(!command || command ==0 ){
            fail('请选择命令！');
            return false;
        }

        if(!keymap_type || keymap_type == 0){
            fail('请选择keymap类型！');
            return false;
        }

        if(keymap_type == 'EVENT_DRIVE'){
            var event = $('#event_3').val();
            if(!event || event == 0){
                fail('事件驱动模式，值不能为空！');
                return false;
            }
        }else{

        }

        if(!returnBool()){
            return false;
        }
        $('.save-set').attr('disabled','disabled');
        return true;
    }

    function returnBool()
    {
        var v_bool = true;
        //不存在操作手时验证
        if($('.A_list').length > 0){
            $('.A_list').each(function(i){

                var s = $(this).val();
                if(!s || s == 0){

                    v_bool = false;
                }
            });
            if(!v_bool){
                fail('请填写参数！');
                return false;
            }
        }

        //存在操作手验证
        if($('.U_list').length > 0){
            $('.U_list').each(function(i){

                var s = $(this).val();
                if(!s || s == 0){
                    v_bool = false;
                }
            });
            if(!v_bool){
                fail('请填写参数！');
                return false;
            }
        }

        if($('.J_list').length > 0) {
            $('.J_list').each(function(i){

                var s = $(this).val();
                if(!s || s == 0){

                    v_bool = false;
                }
            });
            if(!v_bool){
                fail('请填写参数！');
                return false;
            }
        }

        if($('.C_list').length > 0) {
            $('.C_list').each(function(i){

                var s = $(this).val();
                if(!s || s == 0){

                    v_bool = false;
                }
            });
            if(!v_bool){
                fail('请填写参数！');
                return false;
            }
        }

        //正常参数验证
        if($('.tc').length > 0) {
            $('.tc').each(function(i){

                var s = $(this).val();
                //if(!s || s == 0){
                if(!s){

                    v_bool = false;
                }

            });
            if(!v_bool){
                fail('请填写正常参数！');
                return false;
            }
        }

        return true;
    }


</script>