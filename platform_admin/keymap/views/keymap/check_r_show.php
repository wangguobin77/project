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
<link rel="stylesheet" href="/static/css/public/editVer.css">
<!-- 20190506 -->
<style type="text/css">
    .select-mlxx-box .select-box{
        flex-wrap:wrap;
    }
    .youce-box select{
        margin-bottom:5px;
    }
</style>
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

        <!-- 内容区域-->

        <!-- 命令泪飙区域  -->
        <div class=" neirong-wrap" style="min-width:1200px;min-height:1000px">
        <div class="order-box col-md-12">
            <div class="zuoce-box col-md-2">
                <div class="ml-list">
                    <h4><?=Yii::t('app','An_ADDED_COMMAND')?></h4>
                    <span>（<?=count($keymap_data_list)?>）</span>
                </div>
                <ul class='zuoce-box-list col-md-3'>
                    <?php foreach($keymap_data_list as $key=>$val):?>
                        <?php if($val['id'] == $id):?>
                            <li class='sl ml-item li-active'><a href="<?=Url::toRoute(['keymap/check_r_keymap_show','keymap_id'=>$keymap_id,'id'=>$val['id']])?>" ><?=Yii::t('db',$val['command'])?></a></li>
                        <?php else:?>
                            <li class='sl ml-item'><a href="<?=Url::toRoute(['keymap/check_r_keymap_show','keymap_id'=>$keymap_id,'id'=>$val['id']])?>" ><?=Yii::t('db',$val['command'])?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>

            </div>

            <!--  命令详情 右侧  -->
            <div class="youce-box youce-detail-box col-md-9" >
                <h4>命令编辑</h4>
                <?php if(count($keymap_data_list) > 0):?>
                    <div class='ter-box'>
                        <div class="mlbj-xx">
                            <h5><?=Yii::t('app','COMMAND_NAME')?></h5>
                            <select disabled class="sl">
                                <?php echo '<option>'.Yii::t('db',$jsonData['COMMAND']).'</option>';?>
                            </select>
                        </div>

                        <div class="mlbj-xx">
                            <h5><?=Yii::t('app','TRIGGER_TYPE')?></h5>
                            <select disabled class="sl">
                                <?php echo '<option>'.Yii::t('db',$jsonData['KEYMAP_TYPE']).'</option>';?>
                            </select>

                        </div>

                        <?php if(isset($jsonData['EVENT']) && $jsonData['EVENT']):?>
                            <div class="mlbj-xx select-mlxx-box">
                                <h5><?=Yii::t('app','KEY_CONFIG')?></h5>
                                <div class="select-box">
                                    <select disabled class="sl">
                                        <option><?=Yii::t('db',$jsonData['event_1'])?></option>
                                    </select>
                                    <select disabled class="sl">
                                        <option><?=Yii::t('db',$jsonData['event_2'])?></option>
                                    </select>
                                    <select disabled class="sl">
                                        <option><?=Yii::t('db',$jsonData['EVENT'])?></option>
                                    </select>

                                </div>
                            </div>
                        <?php endif;?>

                        <!--start-->
                        <?php if(isset($jsonData['CONDITIONS']) && !empty($jsonData['CONDITIONS'])):?>
                            <!-- 触发条件 盒子 -->
                            <?php  foreach ($jsonData['CONDITIONS'] as $k=>$v):?>
                                <div class="mlbj-xx select-mlxx-box trigger-box">
                                    <h5><?=Yii::t('app','CONDITION_SETTING')?></h5>
                                    <div class="select-box">
                                        <span><?=$k?></span>
                                        <select disabled class="sl">
                                            <option value="<?=$v['CONDITION_JUDGE_TYPE']?>"><?=Yii::t('db',$v['CONDITION_JUDGE_TYPE'])?></option>
                                        </select>
                                        <select disabled class="sl">
                                            <option value="<?=$v['CONDITION_TYPE']?>"><?=Yii::t('db',$v['CONDITION_TYPE'])?></option>
                                        </select>
                                        <select disabled class="sl">
                                            <option value="<?=$v['CONDITION_VALUE']?>"><?=Yii::t('db',$v['CONDITION_VALUE'])?></option>
                                        </select>
                                        <span class='font_family icon-operation_delate'></span>
                                    </div>
                                </div>
                            <?php endforeach;?>

                        <?php endif;?>

                        <!--模拟量参数-->
                        <?php if(isset($jsonData['PARAMS'])&& count($jsonData['PARAMS']) > 0){
                            $str_a = '';//不存在操作手
                            $str_u ='';//美国手
                            $str_j = '';//日本手
                            $str_c = '';//中国手
                            $str_m = '';//正常参数
                            foreach ($jsonData['PARAMS'] as $key=>$val){
                                if($jsonData['PARAMS'][$key]['TYPE'] == 'ANALOG'){
                                    if(isset($jsonData['PARAMS'][$key]['OP_STYLE']) && $jsonData['PARAMS'][$key]['OP_STYLE']){
                                        /*美国手*/
                                        $str_u .= '<select disabled class="sl itemcs">';
                                        /* $str_u .= '<option value="'.$val['VALUE'].'">'.$val['VALUE'].'</option>';*/
                                        $str_u .= '<option value="'.$val['VALUE'].'">'.Yii::t('db',$val['VALUE']).'</option>';
                                        $str_u .= '</select>';
                                        /*美国手*/


                                        /*日本手*/
                                        $str_j .= '<select disabled class="sl itemcs">';
                                        /* $str_j .= '<option value="'.$val['OP_STYLE']['JAPAN'].'">'.$val['OP_STYLE']['JAPAN'].'</option>';*/
                                        $str_j .= '<option value="'.$val['OP_STYLE']['JAPAN'].'">'.Yii::t('db',$val['OP_STYLE']['JAPAN']).'</option>';
                                        $str_j .= '</select>';

                                        /*日本手*/

                                        /*中国手*/
                                        $str_c .= '<select disabled class="sl itemcs">';
                                        /* $str_c .= '<option value="'.$val['OP_STYLE']['CHINA'].'">'.$val['OP_STYLE']['CHINA'].'</option>';*/
                                        $str_c .= '<option value="'.$val['OP_STYLE']['CHINA'].'">'.Yii::t('db',$val['OP_STYLE']['CHINA']).'</option>';
                                        $str_c .= '</select>';


                                        /*中国手*/
                                    }else{

                                        $str_a .= '<select disabled class="sl itemcs">';
                                        /* $str_a .= '<option value="'.$val['VALUE'].'">'.$val['VALUE'].'</option>';*/
                                        $str_a .= '<option value="'.$val['VALUE'].'">'.Yii::t('db',$val['VALUE']).'</option>';
                                        $str_a .= '</select>';

                                    }
                                }else{
                                    /* $str_m .= '<input type="text" class="tc" value="'.$val['VALUE'].'"/>';*/
                                    $str_m .= '<input type="text" disabled class="sl itemcs" value="'.Yii::t('db',$val['VALUE']).'"/>';

                                }

                            }
                            $str_us = '';
                            if($str_u){

                                $str_us .= '<div class="mlbj-xx select-mlxx-box trigger-box">';
                                $str_us .= '<h5 >'.Yii::t('app','SIMULATION_PARAMETERS').' USA </h5>';
                                $str_us .= '<div class="select-box zccs" style="flex-wrap:wrap;">';

                                $str_us .= $str_u;

                                $str_us .= '</div>';
                                $str_us .= '</div>';
                            }
                            $str_js = '';
                            if($str_j){

                                $str_js .= '<div class="mlbj-xx select-mlxx-box trigger-box">';
                                $str_js .= '<h5>'.Yii::t('app','SIMULATION_PARAMETERS').' JAPEN </h5>';
                                $str_js .= '<div class="select-box zccs" style="flex-wrap:wrap;">';

                                $str_js .= $str_j;

                                $str_js .= '</div>';
                                $str_js .= '</div>';
                            }
                            $str_cs = '';
                            if($str_c){
                                $str_cs .= '<div class="mlbj-xx select-mlxx-box trigger-box">';
                                $str_cs .= '<h5>'.Yii::t('app','SIMULATION_PARAMETERS').' CHINA </h5>';
                                $str_cs .= '<div class="select-box zccs" style="flex-wrap:wrap;">';

                                $str_cs .= $str_c;

                                $str_cs .= '</div>';
                                $str_cs .= '</div>';
                            }

                            $str_as = '';
                            if($str_a){
                                $str_as .= '<div class="mlbj-xx select-mlxx-box trigger-box">';
//
                                $str_as .= '<h5>'.Yii::t('app','SIMULATION_PARAMETERS').'</h5>';
                                $str_as .= '<div class="select-box zccs" style="flex-wrap:wrap;">';

                                $str_as .= $str_a;
                                $str_as .= '</div>';
                                $str_as .= '</div>';
                            }

                            $str_ms = '';
                            if($str_m){
                                $str_ms .= '<div class="mlbj-xx select-mlxx-box">';
//
                                $str_ms .= '<h5>'.Yii::t('app','OSPF_PARAMETERS').' </h5>';

                                $str_ms .= '<div class="select-box zccs" style="justify-content: flex-start;
                                padding-right: 77px;
                                ">';
                                $str_ms .= $str_m;
                                $str_ms .= '</div>';

                                $str_ms .= '</div>';
                            }

                            $ret_str = '';
                            if($str_a){
                                $ret_str = $str_as.$str_ms;
                            }else if($str_u){
                                $ret_str = $str_us.$str_js.$str_cs.$str_ms;
                            }else{
                                $ret_str = ''.$str_ms;
                            }
                            echo $ret_str;
                        }?>
                        <!--end-->
                    </div>
                <?php else:?>
                    <p style="line-height: 30px;padding-left: 10px;color:#999"><?=Yii::t('app','CONTENT_NULL')?> </p>
                <?php endif;?>
            </div>

        </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>
<script>
    $('.back').click(function(){
        window.location.href = '<?=Url::toRoute(['keymap/keymap_list','rc'=>$remote_type_id])?>';
    });
</script>

