/*
* @Author: Marte
* @Date:   2019-09-09 10:19:42
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-21 13:50:34
*/

'use strict';

    /**
     * 重置搜素条件
     */
    const reset_search=()=>{
        $('.verification_code').val('')
        $('.product-name').val('')
        $(".yyzz_selsct").find("option:nth-child(1)").prop("selected", true);

    }

    var $table =  $('#tb_table'),
        globaltitle = {},
        emptydata = {},
        configjson ,
        result=[],
        table=[],
        ori_length=''; //记录上传是数据未做处理时的数据长度

    function inittitle(gtitle) {
        var firstcolumns = [ ]
        for (var a in gtitle) {
            var obj = {
                editable: {
                    type: 'text',
                    mode: "popup",//popup
                    title: '',
                    disabled: true,
                    emptytext: '无',
                }
            }
            obj.field = gtitle[a];
            obj.title = a;
            obj.editable.title = a;

            firstcolumns.push(obj);
        }
        return firstcolumns;
    }
    var TableInit = function (data,columns) {
        var oTableInit = new Object();
        //初始化Table
        oTableInit.Init = function () {
            $table.bootstrapTable({
                url: '',         //请求后台的URL（*）
                data:data,
                method: 'get',
                toolbar: '',                //工具按钮用哪个容器
                striped: true,
                cache: false,
                pagination: false,
                sortable: false,
                // sortOrder: "asc",
                queryParams: '',//传递参数（*）
                sidePagination: "client",           //分页方式：client客户端分页，server服务端分页（*） 设置 ‘server’时，必须设置 服务器数据地址（url）或者重写ajax方法
                pageNumber:1,
                pageSize: 1,                       //每页的记录行数（*）
                pageList: [10,50, 100,300,500],        //可供选择的每页的行数（*）
//                search: true,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
                strictSearch: true,
                showColumns: false,                  //是否显示所有的列
                showRefresh: false,                  //是否显示刷新按钮
                minimumCountColumns: 2,             //最少允许的列数
                clickToSelect: true,                //是否启用点击选中行
//                height: 500,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
                uniqueId: "ID",                     //每一行的唯一标识，一般为主键列
//                showToggle:true,                    //是否显示详细视图和列表视图的切换按钮
                cardView: false,                    //是否显示详细视图
                detailView: false,                   //是否显示父子表
                columns:columns,
                onClickRow: function (row) {
                   console.log(row)
                },
            });
        };
        return oTableInit;
    };
    function importfile(file) {
        var f = file.files[0];
        var size = f.size / 1024;
        $("#excelfile").val(f.name);
        var type=f.type;
        var val=$('.myfile').val();
        var pos = val.lastIndexOf("\\");
        var filename = val.substring(pos+1);
        var fileextname = filename.substring(filename.lastIndexOf("."), filename.length);//截取的后缀名称 .xls
        var Maxsize=2000;
        var wb;//读取完成的数据
        var rABS = false; //是否将文件读取为二进制字符串
        var ie = IEVersion();
        if(size>Maxsize){
            fail('上传文件大小不得大于2M')
            return;
           }else{
                $('.upfile').hide();
                $('span.filename').html(filename);
                $('span.filesize').html(f.size+'kb');
                $('.fileinfo').show();
                var reader = new FileReader();
                reader.onload = function (e) {
                var data = e.target.result;
           }
        };
        if(fileextname!=='.xls' || fileextname!=='.xlsx'||fileextname!=='.XLSX' ||fileextname!=='.XLS'){
            fail('上传文件格式不正确！')
            return
        }
        if(ie != -1 && ie != 'edge'){
            if(ie<10){
                return;
            }else{
                rABS = true;
            }
        }
        if(checkfilename(file)){
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = e.target.result;
                if(rABS) {
                    wb = XLSX.read(btoa(fixdata(data)), {//手动转化
                        type: 'base64'
                    });
                } else {
                    wb = XLSX.read(data, {
                        type: 'binary'
                    });
                }
                var result = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
                console.log(result);
                resoveresult(globaltitle,result);
            };
            if(rABS) {
                reader.readAsArrayBuffer(f);
            } else {
                reader.readAsBinaryString(f);
            }
        }
    }

    function resoveresult(config,list) {
        // $table.bootstrapTable('showLoading');
        var rs= [];
        if(list.length>0){
            for(var one in list){
                var obj = {};
                for(var index in config){
                    var key = list[one][index];
                    if(!key){
                        obj[config[index]]="";
                    }else {
                        obj[config[index]] = key;
                    }
                }
                obj.id = Number(one);
                rs.push(obj);
            }
            $table.bootstrapTable('load',rs );
        }
        // $table.bootstrapTable('hideLoading');
    }

    function getjson(url) {
        $.ajaxSetup({async:false});
        var rs;
        $.getJSON(url, function(json){
            rs = json;
        });
        return rs;
    }
    function initTable() {
        var columns = inittitle(globaltitle);
        //1.初始化Table
        var oTable = new TableInit([],columns);
        oTable.Init();
    }
    /**
     * 渲染table中数据
     */
    $(function () {
        configjson = getjson('../../web/js/public/config/data.json');
        globaltitle = configjson[0].title;
        initTable();
    });

/**
 * 数据预览  判断数据中是否有为空的数据
 * 是否满足验证要求
 * 显示错误日志 code name ori_price price
  */
const see_data=()=>{
    if($('span.filename').html()==''){
        fail('请先上传文件')
        return
    }
    var total='',
        error_num=[],//错误的条数
        normal_num=[],//正确的条数
        code=[];//存放核销码的数组
        table = $table.bootstrapTable('getData')
    var data_len=$table.bootstrapTable('getData').length
        ori_length=table.length
    // 上传条数限制
    if(table.length>3000){fail('上传信息最多可支持3000条');return}
    $.each(table, function(index, val) {
        //不符合要求数据处理
        if(val.code==''){
            $('.log-box').append("<li class='child-li'>第"+(index+1)+"行：核销码字段不能为空</li>")
            error_num.push(index+1)//错误信息行push到数组中
        }
        if(val.name==''){
            $('.log-box').append("<li class='child-li'>第"+(index+1)+"行：品名字段不能为空</li>")
            error_num.push(index+1)//错误信息行push到数组中
        }
        if(val.ori_price==''){
            $('.log-box').append("<li class='child-li'>第"+(index+1)+"行：原价字段不能为空</li>")
            error_num.push(index+1)//错误信息行push到数组中
        }
        if(val.price==''){
            $('.log-box').append("<li class='child-li'>第"+(index+1)+"行：售价字段不能为空</li>")
            error_num.push(index+1)//错误信息行push到数组中
        }
        // 显示错误的log
        $('.lead-first').hide();
        $('.lead-format').show();
        //表格中的code核销码  存入数组
        code.push(val.code)
    });

    //数据循环结束

    /**
     *处理code码相同的行数
     */
    var rep = [];
    code.forEach((item,index)=>{
        if(code[index]==''){
            //code为空不做处理
        }else if(code.indexOf(item)!=index){ // 匹配数组元素第一个item位置和当前循环的index
            let obj = {};
            obj.key = (code.indexOf(item) + 1) + '和' + (index + 1); // 用'|'分隔两个重复项的下标
            error_num.push(code.indexOf(item) + 1)
            error_num.push(index+ 1)
            obj.value = item;
            rep.push(obj);
            table.splice(index, 1)
            table.splice(code.indexOf(item),1)
        }
    });
    console.log(rep)
    // 循环处理核销码相同的行数 并push到errorlog中显示
    $.each(rep, function(index, val) {
        $('.log-box').append("<li class='child-li'>第"+val.key+"行：核销码相同，均为"+val.value+"</li>")
    });

    // 属性值为空的数组对象集合 赋值result[]

    if(table.lenght!==0){
        for (var i = 0; i<table.length; i++) {
            var code = table[i].code,
                name = table[i].name,
                ori_price=table[i].ori_price,
                price=table[i].price;

            if (code!==''&&name!==''&&ori_price!==''&&price!=='') {
                result.push(table[i]);
            }else{

            }
        }
    }

    // 错误条数赋值显示
    var newArr = error_num.filter((x, index,self)=>self.indexOf(x)===index)
    var error_length=ori_length-result.length,
        normal_length=result.length;
    if(normal_length<0){
        normal_length=0
    }
    $('.error_num').html(error_length+'条')
    $('.normal_num').html(normal_length+'条')

    if(($('.log-box').find('li').length)==0&&table.length!==0){
        $('.two').addClass('num-active');
        $('.lead-format').hide()
        $('.lead-second').show()
    }
};

/**
 * 数据不合法中重新上传
 */
const upload_again=()=>{
    $('#FileInput').val('')
    $('.log-box').empty()//清空log-box下面的所有子元素
    $('.lead-format').hide()
    $('.lead-first').show()
    $('.upfile').show();
    $('.myfile').val('');
    $('span.filename').html('');
    $('span.filesize').html('');
    $('.fileinfo').hide();
    $('#FileInput').val('')
    $table.bootstrapTable('load',[])//重新加载渲染 table表格 data=[]
    result=[]
};
/**
 * 上传文件含有出现错误字段的数据  点击继续上传  显示预览正确的数据
 */
const goon_lead=()=>{
    // 渲染显示正确的列表信息
    if(table.length!==0){
        $table.bootstrapTable('load',result)
        $('.two').addClass('num-active');
        $('.lead-format').hide()
        $('.lead-second').show()
    }
};
/**
 * 数据预览上一步
 */
const see_data_back=()=>{
    console.log(ori_length)
    result=[]
    $('.lead-first').show()
    $('.lead-second').hide()
};
 /**
 * 导入  上传文件
 */
const lead=()=>{
    $('.lead-box').show();
}
const cancel_lead=()=>{
    clear_info()
    $('.lead-box').hide();
    $table.bootstrapTable('load',[])//重新加载渲染 table表格 data=[]
    $('.log-box').empty()//清空log-box下面的所有子元素
    result=[]
    $('.myfile').val('')
}
$('.lead-close').click(function(){
     $('.lead-box').hide();
     refresh()
})
/**
 *清除上传文件信息
 */
const clear_info=()=>{
    $('.upfile').show();
    $('.myfile').val('');
    $('span.filename').html('')
    $('span.filesize').html('')
    $('.fileinfo').hide()
    $('#FileInput').val('')
     $('.log-box').empty()//清空log-box下面的所有子元素
    $('.lead-format').hide()
    $table.bootstrapTable('load',[])
}
/** 确认提交 导入数据*/
$('#btn_get').click(function () {
    $('.lead-second').hide()
    var data = $table.bootstrapTable('getData');
    console.log(data)
    $.ajax({
        url:'',
        type:'post',
        data:data,
        beforeSend:function(){
            $('.loading').show()
        },
        success:function(result){
             $('.loading').hide()
            if(result.success){

            }else{
                 $('.lead-second').show()
                fail('error','')

            }
        }
    })

})


