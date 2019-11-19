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
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>


    <script src="public/js/parsley.min.js"></script>
    <script src="public/js/parsley.zh_cn.js"></script>
    <style>
        {literal}
        .img-responsive{ display:block; height:auto; width:100%; }

        .mui-input-row label{
            line-height:21px;
            height:21px;
        }

        .menuicon{
            color:#fe4401;
            padding-right:5px;
        }

        .mui-grid-view .mui-media{
            color:#fe4401;
            background:#FFFFFF;
            padding:5px;
        }

        #orders .mui-table-view.mui-grid-view .mui-table-view-cell{
            padding:10px 0 5px 0;
            font-size:1.4em;
        }

        #orders .mui-table-view.mui-grid-view .mui-table-view-cell .mui-icon{
            font-size:2.0em;
        }

        #orders .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body{
            font-size:12px;
            text-overflow:clip;
            color:#333;
        }

        #orders .mui-icon .mui-badge{
            font-size:10px;
            line-height:1.4;
            position:absolute;
            top:0px;
            left:100%;
            margin-left:-40px;
            padding:1px 5px;
            color:red;
            background:white;
            border:1px solid red;
        }
        .mui-table-view:before {
            height: 0px;
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
            <a href="webim.php" class="mui-icon mui-action-menu mui-icon-chat mui-twinkling mui-pull-right"></a>
            <h1 class="mui-title" id="pagetitle">我的粉丝</h1>
        </header>
		{/if} 
        {include file='footer.tpl'}
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body">
                                <a href="javascript:;">
                                    <img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}">
                                    <div class="mui-media-body">
                                        <p class='mui-ellipsis' style="color:#333">昵称：{$profile_info.givenname}</p>
                                        <p class='mui-ellipsis'><span style="color:#333">等级：</span>{include file='profilerank.tpl'} </p>

                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;background-color: #efeff4;">
                    <div class="mui-card" style="margin: 3px 3px;">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body  mui-pull-left">
                                <span class="mui-table-view-label">全部粉丝：</span><span
                                        class="price">{$levelsourcers.total}</span>人
                            </div>
                        </li>
                    </div>
                    <div class="mui-card" style="margin: 3px 3px;">
                        <li class="mui-table-view-cell">
                            <a href="levelsourcer.php?type=onelevelsourcer" class="mui-navigate-right deliveraddress">
                                <div class="mui-media-body  mui-pull-left">
                                    <span class="mui-table-view-label">一级粉丝：</span> <span class="price">{$levelsourcers.onelevelsourcer}</span>人
                                </div>
                            </a>
                        </li>
                        <li class="mui-table-view-cell">
                            <a href="levelsourcer.php?type=twolevelsourcer" class="mui-navigate-right deliveraddress">
                                <div class="mui-media-body  mui-pull-left">
                                    <span class="mui-table-view-label">二级粉丝：</span> <span class="price">{$levelsourcers.twolevelsourcer}</span>人
                                </div>
                            </a>
                        </li>
                        <li class="mui-table-view-cell">
                            <a href="levelsourcer.php?type=threelevelsourcer" class="mui-navigate-right deliveraddress">
                                <div class="mui-media-body  mui-pull-left">
                                    <span class="mui-table-view-label">三级粉丝：</span> <span class="price">{$levelsourcers.threelevelsourcer}</span>人
                                </div>
                            </a>
                        </li>
                    </div>
                </ul> 
				{include file='copyright.tpl'} 
            </div>
        </div>

        <!-- end pullrefresh  -->
    </div>



<script type="text/javascript">
    {literal}
    var mask = null;
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

    });
    {/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>