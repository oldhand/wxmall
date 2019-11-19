 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
	<link href="public/css/parsley.css" rel="stylesheet" >  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>   
	<script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>   
	<script type="text/javascript" src="public/js/jweixin.js"></script>   
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>  
	{include file='theme.tpl'}  
</head>

<body>
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper"> 
	<div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
		<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
			  <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			  <h1 class="mui-title" id="pagetitle">推广方案</h1>
		</header>  
		{/if} 
	    {include file='footer.tpl'}   
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                <div class="mui-scroll"> 
					<div class="mui-card" style="margin: 0 3px;">  
		 				<ul class="mui-table-view">
		 					<li class="mui-table-view-cell mui-collapse mui-active">
		 						<a class="mui-navigate-right" href="#">方案一、直接分享首页</a>
		 						<div class="mui-collapse-content"> 
		 							<h4>直接分享首页</h4> 
		 							<p style="text-indent: 2em;">
		 								 进入首页后，您可以直接点击右上角分享按钮，分享到朋友圈或发送给朋友（如下图）。 
		 							 </p> 
				   			         <div  style="margin: 5px 5px;"> 
				   							 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;"  src="/images/popularize/share.jpg">
				   				     </div>
		 							 <p style="text-indent: 2em;"> 
										 当朋友圈有人购买时，您会收到如下图的微信消息通知。
					   			        
		 							 </p>
				   			         <div  style="margin: 5px 5px;"> 
				   							 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;"  src="/images/popularize/ticheng.jpg">
				   				     </div>
		 							 <p style="text-indent: 2em;"> 
										 当购买人收货成功时，您会收到具体的推广佣金到账的通知。 
		 							 </p>
				   			         <div  style="margin: 5px 5px;"> 
				   							 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;"  src="/images/popularize/billwater.jpg">
				   				     </div>
		 							 <p style="text-indent: 2em;"> 
										 您想试一试吗？ 
		 							 </p>
			  					     <div class="mui-content-padded" style="margin:0px;margin-top: 5px;background:{$supplier_info.themecolor};"> 
			  	               			<a href="index.php"><h5 class="show-content" style="padding: 10px;"><span class="mui-icon iconfont icon-mainpage"></span> 现在就回到首页，我要推广试试</h5></a>
			  	  				     </div>  
	 							     <p><br></p>
		 						</div>
		 					</li>
		 					<li class="mui-table-view-cell mui-collapse">
		 						<a class="mui-navigate-right" href="#">方案二、直接分享商品</a>
		 						<div class="mui-collapse-content"> 
		 							<h4>直接分享商品</h4> 
		 							<p style="text-indent: 2em;">
		 								 您可以直接将商品分享，分享到朋友圈或发送给朋友（如下图）。 
		 							 </p> 
				   			         <div  style="margin: 5px 5px;"> 
				   							 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;"  src="/images/popularize/share_product.jpg">
				   				     </div>
		 							 <p style="text-indent: 2em;"> 
										 方案二可以达到方案一相同的效果，区别是对象仅针对特定商品，目标明确，推广可控。 
		 							 </p> 
		 							 <p style="text-indent: 2em;"> 
										  方案二同样可以收到的推广佣金，以及微信通知。
		 							 </p>
		 							 <p style="text-indent: 2em;"> 
										 您想试一试吗？ 进入商品详情直接分享就行了。
		 							 </p>  
	 							     <p><br></p>
		 						</div>
		 					</li>
		 					<li class="mui-table-view-cell mui-collapse">
		 						<a class="mui-navigate-right" href="#">方案三、分享商品二维码</a>
		 						<div class="mui-collapse-content"> 
		 							<h4>分享商品二维码</h4>  
		 							 <p style="text-indent: 2em;">
		 								 方案三是一种类微商的推广方案，您可以直接在商品详情中，将商品图片，二维码，保存到您的手机相册（长按图片）。然后，可以以微商的方式将发享到朋友圈。 
		 							 </p> 
		 							 <p style="text-indent: 2em;"> 
										 您的朋友若需要购买，可以长按二维码，直接定位到该商品进行购买。 
		 							 </p>
				   			         <div  style="margin: 5px 5px;"> 
				   							 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;"  src="/images/popularize/weishang.jpg">
				   				     </div>
		 							 <p style="text-indent: 2em;"> 
										 方案三跟目前微商的推广类似，但我们提供了一个可以直接购买下单的平台。 
		 							 </p> 
		 							 <p style="text-indent: 2em;"> 
										  方案三，跟方案二所获得的佣金完全一样。
		 							 </p>
		 							 <p style="text-indent: 2em;"> 
										 您想试一试吗？ 操作也不是很复杂的！
		 							 </p>  
	 							     <p><br></p>
		 						</div>
		 					</li>
							
		 				</ul>
					</div>  
				   
				   {include file='copyright.tpl'}
				</div>
		</div> 
    </div>
</div> 

	      
	<script type="text/javascript"> 
	{literal}	
	    var mask = null;   
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
			mui('.mui-content-padded').on('tap','a',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			});
			mui('.mui-table-view-cell').on('tap','a.mui-navigate-right',function(e){
				Zepto(".mui-scroll").css("-webkit-transform","translate3d(0px, 0px, 0px) translateZ(0px)"); 	
			});
		 
		});  
	{/literal} 
	</script>
{include file='weixin.tpl'}  
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>  
</html>