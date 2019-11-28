<?php
header("Content-Type: text/html;charset=utf-8");
header("Cache-Control:no-cache");
include 'main.load.inc';
$token = getToken();
$_SESSION['token'] = $token;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Snseplay</title>
    <script src="./js/template.js"></script>
    <link rel="stylesheet"
          href="./css/main.css">
</head>

<body>

<style type="text/css"
       media="screen">
    body {
        width: 100%;
        height: 100%;
        position: fixed;

    }

    .tree {
        width: 100%;
        overflow: scroll;
        height: 284px;
        /* height: 100%; */

    }

    * {
        margin: 0;
        padding: 0;
    }

    .tree .tree1 {
        margin-bottom: 30px;
        float: left;
    }

    .tree ul {

        padding-top: 20px;
        position: relative;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        transition: all 0.5s;
    }

    .tree li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0 5px;

        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        transition: all 0.5s;
    }


    .tree li::before,
    .tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 1px solid #ccc;
        width: 50%;
        height: 45px;
        z-index: -1;
    }

    .tree li::after {
        right: auto;
        left: 50%;
        border-left: 1px solid #ccc;
    }

    .tree li:only-child::after,
    .tree li:only-child::before {
        display: none;
    }

    .tree li:only-child {
        padding-top: 0;
    }

    .tree li:first-child::before,
    .tree li:last-child::after {
        border: 0 none;
    }

    .tree li:last-child::before {
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;

        -webkit-transform: translateX(1px);
        -moz-transform: translateX(1px);
        transform: translateX(1px);

        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
        border-radius: 0 5px 0 0;
    }

    .tree li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    .tree ul ul::before {
        content: '';
        position: absolute;
        top: -12px;
        left: 50%;
        border-left: 1px solid #ccc;
        width: 0;
        height: 32px;
        z-index: -1;
    }

    .tree li a {
        border: 1px solid #ccc;
        padding: 5px 10px;
        text-decoration: none;
        color: #666;

        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;
        background: #cccccc;

        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;

        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        transition: all 0.5s;
        border-radius: 50%
    }

    .tree li a+a {
        margin-left: 20px;
        position: relative;
    }

    .tree .parent {
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        color: #fff !important;
        background: #787878

    }

    .color {
        background:green !important;
        color: #fff !important;
    }
    </style>

<input type="hidden"
       value="<?php echo $token; ?>"
       id="token">
<div id='tip'></div>
<div class="fail-ts"
     style="z-index:10000">
    <div class="ts-xx">
        <span class="font_family icon-warning_large"></span>
        <p>保存失败，请重新尝试！</p>
    </div>
</div>

<div class="tree">


</div>
<div class='all_dismiss'>
    <div class="unread_message_box">未读 <span class="unread_message"></span>条</div>
    <button style='margin-right: 20px'
            class='mint-button mint-button--default mint-button--small all-message-cancel' ><label for="">全部忽略</label></button>
</div>
<div class='message_tabel'>

</div>

<script src="js/jq.min.js"></script>
<script src="./js/ts.js"></script>
<script>
    var BASE_URL = 'http://192.168.90.217:8091/';
    // var BASE_URL = '/';
    var nodeArr = [];
    var nodeOperator = {};
    var list = [];
    var unreadMessag = 0;
    var token = ''
    var treeNodeList = []
    $(document).ready(function () {
        var tokenEle = document.querySelector('#token')
        token = tokenEle.value
        $('#tip').delegate('.x-toast-box-close','click',function(){
            $(this).parent().hide()
        })
        $('.tree').delegate('.tree-child-box', 'click', treeNodeClick)
        getNode()
        getList()
        $('.message_tabel').delegate('.check','click',checkIp)
        $('#tip').delegate('.cancel_all','click',cancel_all)
        $('.all-message-cancel').click(function(){
            all_message_cancel()
        })
        $('.message_tabel').delegate('.cancel_message', 'click',cancel_message_gingal)

        setInterval(function(){
            messageLoop()
        },20000)



    })

    function messageLoop(){
        $.ajax({
            url: BASE_URL + 'msg.php',
            type: 'get',
            dataType: 'json',
            data: {},
            headers:{
                'token':token
            },
            success: function (res) {
                if (res.code == 0) {
                    var msg = res.data.filter(function(item){
                        return item.read == '0'
                    }).map(function(item){
                        item.title = '消息提醒'
                        item.content = item.tip
                        item.class = 'warning'
                        item.display = true
                        return item
                    })
                    $.get('./tpl/tip.tpl.html',function(htmls){
                        var  render = template.compile(htmls);
                        var data = {
                            title:'消息提醒' ,
                            content:msg[0].tip,
                            class: 'warning',
                            display:true,
                            message: res.message,
                            ip:msg[0].ip
                        }
                        htmls = render({ data: [data]});
                        $("#tip").html(htmls);
                    })
                    getList()

                }
            }
        })
    }
    function cancel_message_gingal(){
        $.ajax({
            url: BASE_URL + 'msg_i.php',
            type: 'post',
            data: {
                gid:$(this).attr('data-id')
            },
            headers:{
                'Content-Type':'application/x-www-form-urlencoded',
                'token':token
            },
            success: function (res) {

                var data = JSON.parse(res)
                if (data.code == 0) {
                    toast('忽略成功');
                    getList()
                } else {
                    toast(data.message);
                }

            }
        })
    }

    function cancel_all(){
        var that = $(this)
        $.ajax({
            url: BASE_URL + 'msg_i.php',
            type: 'post',
            data: {
                gid:'all'
            },
            headers:{
                'Content-Type':'application/x-www-form-urlencoded',
                'token':token
            },
            success: function (res) {
                var data = JSON.parse(res)
                if (data.code == 0) {
                    that.parent().parent().hide()
                    toast('忽略成功');
                    getList()
                } else {
                    toast(data.message);
                }

            }
        })
    }
    function all_message_cancel(){
        $.ajax({
            url: BASE_URL + 'msg_i.php',
            type: 'post',
            data: {
                gid:'all'
            },
            headers:{
                'Content-Type':'application/x-www-form-urlencoded',
                'token':token
            },
            success: function (res) {

                var data = JSON.parse(res)
                if (data.code == 0) {
                    toast('忽略成功');
                    getList()
                } else {
                    toast(data.message);
                }

            }
        })
    }

    function checkIp() {
        var parentkey = ''
        var key = $(this).attr('data-id')
        for (var i = 0; i<list.length; i++){
            for(var j = 0; j<list[i].length;j++){
                if(list[i][j].key == key){
                    parentkey = list[i][j].parent

                }
            }
        }
        var opretor = nodeOperator[parentkey]
        var opneItem = opretor.filter(function (item){
            return item.key == key
        })

        if (opneItem.length) {
            var old = opneItem[0];
            if (old.select) {
                return toast(old.geo+'已开启')
            }

            if (!old.select) {
                var sublings = opretor.filter(function(item){
                    return item.parent == old.parent
                }).filter(function(item){
                    return  item.select
                })
                if (sublings.length != 0) {
                    return toast('一个节点下最多开一个')
                }
                $.ajax({
                    url: BASE_URL + 'api.php',
                    type: 'get',
                    headers:{
                        'token':token
                    },
                    dataType: 'json',
                    data: {open:old.key},
                    success: function (res) {
                        if (res.code == 0) {
                            toast('打开成功')
                            getNode()
                        } else {
                            toast(res.message)
                        }

                    }
                })

            }

        }
    }

    function getList() {
        var height = document.body.offsetHeight
        document.querySelector('.message_tabel').style.height = height - 334 + 'px'
        $.ajax({
            url: BASE_URL + 'msg_i.php',
            type: 'get',
            dataType: 'json',
            headers:{
                'token':token
            },
            data: {},
            success: function (res) {
                if (res.code == 0) {
                    unreadMessag = res.message;
                    $('.unread_message').html(unreadMessag)
                    $.get('./tpl/table.tpl.html', function (htmls) {
                        var render = template.compile(htmls);
                        htmls = render({ data: res.data });
                        $(".message_tabel").html(htmls);

                    })
                } else {

                }

            }
        })
    }
    function toast(val, url) {
        $('.fail-ts').find('p').html(val);
        $('.fail-ts').show();
        $('.fail-ts').delay(2000).hide(0);
    }
    function openIp(old) {
        $.ajax({
            url: BASE_URL + 'api.php?open=' + old.key,
            type: 'get',
            dataType: 'json',
            headers: {
                'token': token
            },
            data: {},
            success: function (res) {
                if (res.code == 0) {
                    getNode()
                    toast('打开成功')
                } else {
                    toast(res.message)
                }

            }
        })
    }
    function closeIp(old) {
        $.ajax({
            url: BASE_URL + 'api.php?close=' + old.key,
            type: 'get',
            dataType: 'json',
            data: {},
            headers: {
                'token': token
            },
            success: function (res) {
                if (res.code == 0) {
                    getNode()
                    toast('关闭成功')
                } else {
                    toast(res.message)
                }

            }
        })
    }
    function treeNodeClick() {
        var parentIndex = $(this).attr('data-parent-index')
        var index = $(this).attr('data-index')
        var old = treeNodeList[parentIndex][index]
        var nodeDataArray = treeNodeList[parentIndex]
        if (!old.select) {
            var sublings = nodeDataArray.filter(item => item.parent == old.parent).filter(item => item.select);
            if (sublings.length != 0) {
                toast('一个节点下最多开一个')
                return
            }
        }
        if (!old.select) {
            openIp(old)
        }else{
            closeIp(old)
        }

    }
    function getNode() {
        $.ajax({
            url: BASE_URL + 'api.php',
            type: 'get',
            dataType: 'json',
            data: {},
            headers: {
                'token': token
            },
            success: function (res) {
                if (res.code == 0) {
                    list = res.data
                    for (var i = 0; i < res.data.length; i++) {
                        res.data[i].map(function(item){
                            if(item.level == 2 ){
                                nodeOperator[item.key] = res.data[i]
                            }
                        })
                    }
                    $.get('./tpl/tree.tpl.html', function (htmls) {
                        var render = template.compile(htmls);
                        htmls = render({ data: res.data });
                        $(".tree").html(htmls);

                    })
                    treeNodeList = res.data

                }

            }

        })
    }

</script>
</body>

</html>