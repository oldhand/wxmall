<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>商城介绍</title>
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
		 <h1 class="mui-title">商城介绍</h1> 
	</header> 
	{/if}
	{include file='footer.tpl'}   
    <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if};">  
            <div class="mui-scroll">   
				<div class="mui-card" style="margin: 0 3px;"> 
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
                                <li class="mui-table-view-cell"> 
										<div class="mui-media-body "> 
										 <a id="detail_image" href="album.php" style="width:100%"><h5 class="show-content" style="padding: 10px;">商家相册【点击查看商家相册】</h5></a>
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
				{include file='copyright.tpl'}
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