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
 	<link href="public/css/sweetalert.css" rel="stylesheet"/>
 
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" src="public/js/sweetalert.min.js"></script>

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
		.logisticbills
		{
			color:#333;
			margin-left:0px;
			margin-right:10px;
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
		    <h1 class="mui-title" id="pagetitle">{$logistictrip_info.mall_logistictrips_no}</h1>
        </header> 
		{/if} 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                  
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                        	 	<div class="mui-media-body">    
                                        <p class='mui-ellipsis' style="color:#333">状态：{$logistictrip_info.mall_logistictripsstatus}</p> 
                                        <p class='mui-ellipsis' style="color:#333">创建时间：{$logistictrip_info.published}</p> 
										 
										{if $logistictrip_info.startdate neq ''}
											<p class='mui-ellipsis' style="color:#333">出发时间：{$logistictrip_info.startdate}</p>
										{/if}
										{if $logistictrip_info.enddate neq ''}
											<p class='mui-ellipsis' style="color:#333">结束时间：{$logistictrip_info.enddate}</p>
										{/if}
										{if $logistictrip_info.swaptime neq ''}
											<p class='mui-ellipsis' style="color:#333">持续时间：{$logistictrip_info.swaptime}</p>
										{/if}
                                        <p class='mui-ellipsis' style="color:#333">包裹数量：{$logistictrip_info.billcount}</p> 
										{if $logistictrip_info.serialname neq ''}
											<p class='mui-ellipsis' style="color:#333">路线：{$logistictrip_info.serialname}</p>
										{/if}
										  
                                </div> 
                        </li>
                    </ul>
                </div>  
				{assign var="logisticbill_info" value=$logistictrip_info.bills}
				{if $logisticbill_info|@count gt 0}
	        	 	{foreach name="logisticbill_info" item=logisticbill_info key=vendorid from=$logisticbill_info}
						<div class="mui-card" style="margin: 3px 3px;">
		                    <ul  class="mui-table-view" style="padding-top: 5px;">  
								<li class="mui-table-view-cell order-link-cell">
									<div class="mui-media-body  mui-pull-left"> 供应商：{$logisticbill_info.vendorname}</div>
								 </li> 
						 
		                        <li class="mui-table-view-cell"> 
		                        	 	<div class="mui-media-body">   
			                        	 	{foreach name="logisticbills" item=logisticbill_item_info key=billid from=$logisticbill_info.bills}
			                        	 			<p class='mui-pull-left' style="padding-right:5px;" ><a class="logisticbill" data-id="{$billid}" href="javascript:;">{$logisticbill_item_info.logisticbills_no}</a></p>  
			                        	 	{/foreach} 
		                                </div> 
		                        </li>
		                    </ul>
		                </div>
	        	 	{/foreach} 
                {/if}
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
	
 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>