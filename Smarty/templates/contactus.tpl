<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>联系我们</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<style>
	  {literal} 
		.img-responsive { display: block; height: auto; width: 100%; } 
	  	.mui-table-view-cell .mui-table-view-label
	  	{
	  	    width:60px;
	  		text-align:right;
	  		display:inline-block;
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
		 <h1 class="mui-title">联系我们</h1> 
	</header> 
	{/if} 
	{include file='footer.tpl'}   
    <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">   
                 <div id="list" class="mui-table-view" >   
								<div class="mui-card" style="margin: 3px 3px;"> 
				                        <ul class="mui-table-view">  
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body  mui-pull-left">
															<span class="mui-table-view-label">企业名称：</span>{$supplier_info.suppliername} 
														</div>
				                                </li>
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body  mui-pull-left">
															<span class="mui-table-view-label">企业地址：</span>{$supplier_info.address} 
														</div>
				                                </li>
												<li class="mui-table-view-cell" style="padding:0px;">
													<div class="mui-media-body">
														<img class="img-responsive" src="http://api.map.baidu.com/staticimage?center={$supplier_info.longitude},{$supplier_info.latitude}&amp;zoom=17&amp;width=320&amp;height=320&amp;markers={$supplier_info.longitude},{$supplier_info.latitude}" />
													</div>
												</li>
										</ul>  
							    </div>
								<!--
							    {if $supplier_info.supplierid neq '7962'}
								<div class="mui-card" style="margin: 3px 3px;"> 
				                        <ul class="mui-table-view">  
				                                <li class="mui-table-view-cell"> 
								                        <a id="potenitalsuppliers" href="potenitalsuppliers.php"><h5 class="show-content" style="padding: 10px;">【商城合作请点击<span class="mui-icon iconfont icon-dianji"></span>】</h5></a>
											    </li>
		                       		</ul>  
							    </div>
								{/if}
								-->
								<div class="mui-card" style="margin: 3px 3px;"> 
				                        <ul class="mui-table-view">  
											   {assign var="copyrights" value=$supplier_info.copyrights}
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body" style="text-align:center"> 
															平台由{$copyrights.trademark}提供技术支持
														</div>
				                                </li> 
				                               <!-- <li class="mui-table-view-cell">
														<div class="mui-media-body  mui-pull-left">
															<span class="mui-table-view-label">特赞官网：</span><a href="http://www.tezan.cn">http://www.tezan.cn</a>
														</div> 
				                                </li>
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body" style="text-align:center">
															    <img style="width:120px;" src="/images/qrcode_tzsc.jpg"><br>
															   <span style="font-size: 1.4em; color:#fe4401;">公众号：tezansc</span>
														</div>
				                                </li>-->
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body" style="text-align:center"> 
															   <span style="font-size: 11px;font-family: Arial Narrow,Arial">Copyright © 2010-2015 <a href="http://www.{$copyrights.site}">{$copyrights.site}</a> All Rights Reserved. </span><br>
															   {if $supplier_info.supplierid eq '7962'}
																    <span style="font-size: 12px;">红星花卉大市场</span><br>
																    <span style="font-size: 12px;">{$copyrights.company}</span><br> 
															   {else} 
																    <span style="font-size: 12px;">{$copyrights.company}</span><br>  
															   {/if} 
															   
															   <span style="font-size: 12px;">{$copyrights.icp}</span>
														</div>
				                                </li>
										</ul>  
							    </div> 
				 </div>    
         </div>
	</div>
</div> 
 
	      
	<script type="text/javascript"> 
	{literal}	 
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等  
	        },
	    });
		mui.ready(function() {  
			mui('#pullrefresh').scroll();
			mui('.mui-bar').on('tap','a',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			});  
			mui('.mui-table-view-cell').on('tap','a',function(e){
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