<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet"/>
    <link href="public/css/public.css" rel="stylesheet"/>
    <link href="public/css/iconfont.css" rel="stylesheet"/>
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>

    <style>
        {literal}
        .img-responsive{ display:block; height:auto; width:100%; }

        .mui-bar .tit-sortbar{
            left:0;
            right:0;
            margin-top:45px;
        }

        .price{
            color:#fe4401;
        }

        .mui-table-view-cell .mui-table-view-label{
            width:60px;
            text-align:right;
            display:inline-block;
        }

        .mui-table-view .mui-media-object{
            margin-top:10px;
        }

        .order-link-cell{
            line-height:30px;
            height:30px;
            padding:0px 5px;
        }

        .order-link-cell a{
            font-size:12px;
        }

        .tishi{
            color:#fe4401;
            width:100%;
            text-align:center;
            padding-top:10px;
        }

        .tishi .mui-icon{
            font-size:4.4em;
        }

        .msgbody{
            width:100%;
            font-size:1.4em;
            line-height:25px;
            color:#333;
            text-align:center;
            padding-top:10px;
        }

        .msgbody a{
            font-size:1.0em;
        }

        {/literal}
    </style>
    {include file='theme.tpl'}
</head>
<body>
<div class="mui-inner-wrap">
	{if $supplier_info.showheader eq '0'}
    <header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
        <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a>
        <h1 class="mui-title">推广名片</h1>
    </header>
	{/if} 
    {include file='footer.tpl'}
    <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
        <div class="mui-scroll">
            <input id="page" value="1" type="hidden">
            <div class="mui-table-view">
                <div class="mui-control-content mui-active">
                    <ul id="list" class="mui-table-view" style="padding-top: 0px;padding-bottom: 5px;">
                        <li class="mui-table-view-cell" style="padding-right:0px;" id="loading">
                            <div class="mui-media-body" style="color:red;text-align:center;">
                                <span class="mui-icon iconfont icon-loading mui-rotation"></span><span> 正在努力制作您的名片，请稍候。。。</span>
                            </div>
                        </li>
                    </ul> 
                </div>
            </div>
			{include file='copyright.tpl'} 
        </div>
    </div>
</div>


<script type="text/javascript">
    {literal}
    mui.init({
        pullRefresh: {
            container: '#pullrefresh' //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等

        },
    });
    mui.ready(function () {
        mui('#pullrefresh').scroll();
        mui('.mui-bar').on('tap', 'a', function (e) {
            mui.openWindow({
                url: this.getAttribute('href'),
                id: 'info'
            });
        });
        setTimeout('load_qrcodecard_info();', 10);
    });

    function load_qrcodecard_info() {
        mui.ajax({
            type: 'POST',
            url: "qrcodecard.ajax.php",
            data: '',
            success: function (json) {
                Zepto('#loading').css('display', "none");
                var msg = eval("(" + json + ")");
                if (msg.code == 200) {
                    var profileid = msg.profileid;
                    var supplierid = msg.supplierid;
                    Zepto('#list').html('<img class="img-responsive" src="qrcodecard_image.php?supplierid='+supplierid+'&profileid='+profileid+'">');
                } else {
                    Zepto('#list').html(qrcodecard_msg_html(msg.msg));
                }
            }
        });
    }
    function qrcodecard_msg_html(msg)
    {
        var sb=new StringBuilder();
        sb.append('<div class="mui-content-padded">');
        sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>');
        sb.append('					      <p class="msgbody">'+msg+'<br>');
        sb.append('						  </p>  ');
        sb.append(' </div>');
        return sb.toString();
    }

    {/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>