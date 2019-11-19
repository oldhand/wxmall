<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<style>
	{literal} 
	 .img-responsive { display: block; height: auto; width: 100%; } 
	 .menuicon { font-size: 1.4em; color:#fe4401; padding-right:10px;} 
	 .menuitem a { font-size: 1.3em;} 
	{/literal}
	</style>
	{include file='theme.tpl'}  
</head>

	<body>
		<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
			 {include file='leftmenu.tpl'} 
			<div class="mui-inner-wrap">
				{if $supplier_info.showheader eq '0'}
				<header class="mui-bar mui-bar-nav" >
                     <a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
                     <a href="webim.php" class="mui-icon mui-action-menu mui-icon-chat mui-twinkling mui-pull-right"></a>
					 <h1 class="mui-title">更多</h1>
				</header>
				{/if} 
				{include file='footer.tpl'}  
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
		            <div class="mui-scroll">
						<div class="mui-card" style="margin-bottom: 0px;">
							<ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
								<li class="mui-table-view-cell menuitem">
									<a class="mui-navigate-right" href="fubusi.php">
										<span class="mui-icon iconfont icon-fubushi menuicon"></span>
										  福布斯榜  
									</a>
								</li> 
								<!--<li class="mui-table-view-cell menuitem">
									<a class="mui-navigate-right" href="downloadapp.php">
										<span class="mui-icon iconfont icon-app1 menuicon"></span>
										  特赞APP  
									</a>
								</li>-->
								<li class="mui-table-view-cell menuitem">
									<a class="mui-navigate-right" href="recruit.php">
										<span class="mui-icon iconfont icon-xinjianzhaopin menuicon"></span>
										  招贤纳士  
									</a>
								</li>
								<li class="mui-table-view-cell menuitem">
									<a class="mui-navigate-right" href="contactus.php">
										<span class="mui-icon iconfont icon-6  menuicon"></span>
										  联系我们  
									</a>
								</li>
							</ul>
					    </div>
						{include file='copyright.tpl'}
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
			mui('.mui-bar').on('tap','a',function(e){
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