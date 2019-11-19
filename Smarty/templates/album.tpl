<html> 
	<head>
		<meta charset="utf-8">
		<title>商家相册</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<!--标准mui.css-->
	    <link href="public/css/mui.css" rel="stylesheet" />
	    <link href="public/css/public.css" rel="stylesheet" />
		<link href="public/css/iconfont.css" rel="stylesheet" /> 
        <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>   
        <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
        <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
    	<script type="text/javascript" src="public/js/jweixin.js"></script>
		<!--App自定义的css-->
		<style type="text/css">
		{literal} 
			.img-responsive { display: block; height: auto; width: 100%; } 
			.mui-preview-image.mui-fullscreen {
				position: fixed;
				z-index: 20;
				background-color: #000;
			}
			.mui-preview-header,
			.mui-preview-footer {
				position: absolute;
				width: 100%;
				left: 0;
				z-index: 10;
			}
			.mui-preview-header {
				height: 44px;
				top: 0;
			}
			.mui-preview-footer {
				height: 50px;
				bottom: 0px;
			}
			.mui-preview-header .mui-preview-indicator {
				display: block;
				line-height: 25px;
				color: #fff;
				text-align: center;
				margin: 15px auto 4;
				width: 70px;
				background-color: rgba(0, 0, 0, 0.4);
				border-radius: 12px;
				font-size: 16px;
			}
			.mui-preview-image {
				display: none;
				-webkit-animation-duration: 0.5s;
				animation-duration: 0.5s;
				-webkit-animation-fill-mode: both;
				animation-fill-mode: both;
			}
			.mui-preview-image.mui-preview-in {
				-webkit-animation-name: fadeIn;
				animation-name: fadeIn;
			}
			.mui-preview-image.mui-preview-out {
				background: none;
				-webkit-animation-name: fadeOut;
				animation-name: fadeOut;
			}
			.mui-preview-image.mui-preview-out .mui-preview-header,
			.mui-preview-image.mui-preview-out .mui-preview-footer {
				display: none;
			}
			.mui-zoom-scroller {
				position: absolute;
				display: -webkit-box;
				display: -webkit-flex;
				display: flex;
				-webkit-box-align: center;
				-webkit-align-items: center;
				align-items: center;
				-webkit-box-pack: center;
				-webkit-justify-content: center;
				justify-content: center;
				left: 0;
				right: 0;
				bottom: 0;
				top: 0;
				width: 100%;
				height: 100%;
				margin: 0;
				-webkit-backface-visibility: hidden;
			}
			.mui-zoom {
				-webkit-transform-style: preserve-3d;
				transform-style: preserve-3d;
			}
			.mui-slider .mui-slider-group .mui-slider-item img {
				width: auto;
				height: auto;
				max-width: 100%;
				max-height: 100%;
			}
			.mui-android-4-1 .mui-slider .mui-slider-group .mui-slider-item img {
				width: 100%;
			}
			.mui-android-4-1 .mui-slider.mui-preview-image .mui-slider-group .mui-slider-item {
				display: inline-table;
			}
			.mui-android-4-1 .mui-slider.mui-preview-image .mui-zoom-scroller img {
				display: table-cell;
				vertical-align: middle;
			}
			.mui-preview-loading {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				display: none;
			}
			.mui-preview-loading.mui-active {
				display: block;
			}
			.mui-preview-loading .mui-spinner-white {
				position: absolute;
				top: 50%;
				left: 50%;
				margin-left: -25px;
				margin-top: -25px;
				height: 50px;
				width: 50px;
			}
			.mui-preview-image img.mui-transitioning {
				-webkit-transition: -webkit-transform 0.5s ease, opacity 0.5s ease;
				transition: transform 0.5s ease, opacity 0.5s ease;
			}
			@-webkit-keyframes fadeIn {
				0% {
					opacity: 0;
				}
				100% {
					opacity: 1;
				}
			}
			@keyframes fadeIn {
				0% {
					opacity: 0;
				}
				100% {
					opacity: 1;
				}
			}
			@-webkit-keyframes fadeOut {
				0% {
					opacity: 1;
				}
				100% {
					opacity: 0;
				}
			}
			@keyframes fadeOut {
				0% {
					opacity: 1;
				}
				100% {
					opacity: 0;
				}
			}
			p img {
				max-width: 100%;
				height: auto;
			}
		{/literal} 
		</style> 
		{include file='theme.tpl'} 
	</head> 
	<body>
	<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
   		<div class="mui-inner-wrap">
			{if $supplier_info.showheader eq '0'}
   			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
				<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
				<h1 class="mui-title">商家相册</h1> 
   			</header>
			{/if}  
   	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">  
                       <div class="mui-scroll">   
						     <input id="page" value="1" type="hidden" > 
      		                 <div id="list" class="mui-table-view" >     
									<div class="mui-content-padded">
							 	           {foreach name="albums" item=album_info from=$albums} 
								 				<p>{$album_info.description}</p>
								 				<p>
								 					<img src="{$album_info.image}" data-preview-src="" data-preview-group="albums" />
								 				</p>
							 	           {/foreach} 
									</div>
   						    </div>
						   {include file='copyright.tpl'}
                    </div>
   			</div>
   	    </div> 
	</div>
	</body> 
	<script src="public/js/mui.zoom.js"></script>
	<script src="public/js/mui.previewimage.js"></script> 
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
				mui.previewImage();
    	   }); 
    	{/literal} 
    	</script>
    {include file='weixin.tpl'} 
    <script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</html>