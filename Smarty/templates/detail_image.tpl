 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>图文详情</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" /> 
	<link href="public/css/index.css" rel="stylesheet" /> 
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script> 
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="public/js/lazyload.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script> 
	<style>
	{literal} 
	 .img-responsive { display: block; height: auto; width: 100%; }  
	{/literal}
	</style>
	{include file='theme.tpl'} 
</head>

	<body>
		<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
			<div class="mui-inner-wrap">
				{if $supplier_info.showheader eq '0'}
				<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
					 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title">图文详情</h1>
				</header>
				{/if} 
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
		            <div class="mui-scroll">
						<div class="mui-content-padded" style="margin: 1px;">
							<ul class="mui-table-view">
								<li class="mui-table-view-cell">
								{$productinfo.simple_desc}
								</li> 
								<li class="mui-table-view-cell" style="padding: 0px;"> 
									{$productinfo.description}
								</li>
								<li class="mui-table-view-cell" style="padding: 0px;"> 
									<div style="padding-top: 15px;padding-bottom: 15px"><img class="img-responsive" src="images/baozhang.png"></div>
								</li>
							<ul> 
						</div>
						
					</div>
				</div>
		    </div>
	    </div>

	<script>
	{literal} 
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
	        },
	    });
		mui.ready(function() { 
			mui('#pullrefresh').scroll(); 
			$(".lazy").lazyload(); 
		});

		 
	{/literal} 
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>