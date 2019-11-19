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
    <link href="public/css/parsley.css" rel="stylesheet">
    <link href="public/css/mui.picker.css" rel="stylesheet"/>
    <link href="public/css/mui.dtpicker.css" rel="stylesheet"/>
    <link href="public/css/mui.listpicker.css" rel="stylesheet"/>
    <link href="public/css/mui.poppicker.css" rel="stylesheet"/>

    <link href="public/css/sweetalert.css" rel="stylesheet"/>

    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>

    <script src="public/js/mui.picker.js"></script>
    <script src="public/js/mui.dtpicker.js"></script>
    <script src="public/js/mui.listpicker.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/mui.poppicker.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/city.data.js" type="text/javascript" charset="utf-8"></script>


    <script src="public/js/parsley.min.js"></script>
    <script src="public/js/parsley.zh_cn.js"></script>

    <script type="text/javascript" src="public/js/sweetalert.min.js"></script>

    <style>
        {literal}
        .img-responsive{ display:block; height:auto; width:100%; }

        .mui-input-row label{
            line-height:21px;
            height:21px;
        }

        .menuicon{ font-size:1.2em; color:#fe4401; padding-right:10px; }

        .menuitem a{ font-size:1.1em; }

        #save_button{
            font-size:20px;
            color:#cc3300;
            padding-left:5px;
        }

        .mui-bar-tab .mui-tab-item .mui-icon{
            width:auto;
        }

        .mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active{
            color:#cc3300;
        }

        .mui-input-row label{
            text-align:right;
            width:30%;
        }

        .mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea{
            float:right;
            width:70%;
        }

        .mui-input-row label{
            line-height:21px;
            padding:10px 10px;
        }

        .mui-input-clear{
            font-size:12px;
        }

        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success{
            color:#468847;
            background-color:#DFF0D8;
            border:1px solid #D6E9C6;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error{
            color:#B94A48;
            background-color:#F2DEDE;
            border:1px solid #EED3D7;
        }

        .parsley-errors-list{
            margin:2px 0 3px;
            padding:0;
            list-style-type:none;
            font-size:0.9em;
            line-height:0.9em;
            opacity:0;
            transition:all .3s ease-in;
            -o-transition:all .3s ease-in;
            -moz-transition:all .3s ease-in;
            -webkit-transition:all .3s ease-in;
        }

        .parsley-errors-list.filled{
            opacity:1;
        }
 

        {/literal}
    </style>
    {include file='theme.tpl'}
</head>

<body>
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
    {include file='leftmenu.tpl'}
    <div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
        <header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
            <a id="offCanvasShow" href="#offCanvasSide"
               class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
            <h1 class="mui-title" id="pagetitle">商城代运营</h1>
        </header>
        {/if} 
            {if $potenitalsuppliers.type eq 'add'}
                <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
                    <a class="mui-tab-item save" href="#">
                        <span class="mui-icon iconfont icon-save button-color">&nbsp;<span
                                    style="font-size:20px;">新增</span></span>
                    </a>
                </nav>
            {elseif $potenitalsuppliers.type eq 'edit'}
                <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
                    <a class="mui-tab-item save" href="#">
                        <span class="mui-icon iconfont icon-save button-color">&nbsp;<span
                                    style="font-size:20px;">保存</span></span>
                    </a>
                </nav>
            {else}
                <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
                    <a class="mui-tab-item mui-action-back">
                        <span class="mui-icon iconfont icon-queren01 button-color">&nbsp;<span style="font-size:20px;">返回</span></span>
                    </a>
                </nav>
            {/if}
 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body">
                                <a href="javascript:;"> 
                                    <div class="mui-media-object mui-pull-left" ><span class="mui-icon iconfont icon-pinpaiyingxiao menuicon" style="font-size: 2.5em;padding-top:20px;"></span></div>
                                    <div class="mui-media-body">
                                        <p class='mui-ellipsis' style="color:#333; white-space: normal;">想为你的品牌和产品，打造一个如【{$supplier_info.suppliername}】一样的商城吗？</p>
                                        <p class='mui-ellipsis'>那就给我们留言吧！威微牛<span class="mui-icon iconfont icon-wwn" ></span>为您服务。</p>

                                    </div>
                                </a>
                            </div>
                        </li>
                        
                    </ul>
                </div>
                <div class="mui-card" style="margin: 3px 3px;">
                    <ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;">

                        <form class="mui-input-group" name="frm" id="frm" method="post" action="potenitalsuppliers.php"
                              parsley-validate> 
                            
                                <div class="mui-input-row" style="margin-top:3px;">
                                    <label style="height:45px;">公司名称:</label> 
                                    <input required="required"  id="suppliername" name="suppliername"
                                           value="{$potenitalsuppliers.suppliername}" type="text" style="font-size: 12px;"
                                           class="mui-input-clear required"  placeholder="您的公司名称">
                                </div>
                                <div class="mui-input-row" style="margin-top:3px;">
                                    <label style="height:45px;">联系人:</label> 
                                    <input required="required"  id="profilename" name="profilename"
                                           value="{$potenitalsuppliers.name}" type="text" style="font-size: 12px;"
                                           class="mui-input-clear required"  placeholder="联系人姓名">
                                </div>
                                <div class="mui-input-row" style="margin-top:3px;">
                                    <label style="height:45px;">手机号码:</label>
                                  
                                    <input required="required" parsley-rangelength="[11,11]" id="mobile" name="mobile"
                                           value="{$potenitalsuppliers.mobile}" type="number" style="font-size: 12px;"
                                           class="mui-input-clear required" maxlength="11" placeholder="联系用手机"
                                           parsley-error-message="请输入正确的手机号码">
                                </div>
                                <input id="type" name="type" value="submit" type="hidden">
                        </form> 

                    </ul>
                </div>
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body">  
                                     <div class="mui-media-body">
                                        <p class='mui-ellipsis' style="color:#333; white-space: normal;">请您保持手机畅通，我们将会尽快联系您。</p>
                                      </div> 
                            </div>
                        </li>
                        
                    </ul>
                </div>
                {include file='copyright.tpl'}
            </div>
        </div>
 
        <!-- end pullrefresh  -->

    </div>
</div>


<script type="text/javascript">

    {literal} 

     
    mui.init({
        pullRefresh: {
            container: '#pullrefresh'
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

 
        mui('.mui-bar').on('tap', 'a.save', function (e) {
            var validate = Zepto('#frm').parsley('validate');
            if (validate) { 
				document.frm.submit();
            }
        }); 
    }); 
    {/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>