 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>累计评价</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/mui.zoom.js"></script>
	<script src="public/js/mui.previewimage.js"></script> 
	<style>
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
            <header class="mui-bar mui-bar-nav">
                <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
                <h1 class="mui-title">累计评价</h1>
            </header>
			{/if}  
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                    <div class="mui-scroll">  
						 <input id="page" value="1" type="hidden" > 
						 <input id="productid" value="{$productinfo.productid}" type="hidden" > 
				         <div  style="margin: 5px 5px;"> 
								 <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" id="productlogo" src="{$productinfo.productlogo}">
					     </div>
	                     <!--detail-->
	                     <div class="mui-content-padded">
	                         <p style="margin-bottom: 0;">
	                             <span class="detail-title">{$productinfo.productname}</span>
	                         </p>
						 </div>
   		                 <div class="mui-table-view" >   
		 		 					<ul id="list"  class="mui-table-view" >
										  
		 		 					</ul> 
									<ul class="mui-table-view" style="background-color: #efeff4;">
										<li class="mui-table-view-cell mui-media"> 
												<img class="img-responsive" src="/images/baozhang.png"> 
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
				down: {
					callback: pulldownRefresh
				},
	            up: {
	                contentrefresh: "正在加载...", //可选，正在加载状态时，上拉加载控件上显示的标题内容
	                contentnomore: '没有更多数据了', //可选，请求完毕若没有更多数据时显示的提醒内容；
	                callback: add_more //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
	            }
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
		function pulldownRefresh() { 
			Zepto('#sortbar').css("display","none");
			setTimeout(function() {  
				Zepto('#sortbar').css("display","");
	            Zepto('#page').val(1);  
				Zepto('#list').html('');  
				add_more();
				mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
			}, 1000);
		}
		
		function appraise_html(v) {	 
				var sb=new StringBuilder();    
sb.append('			 <li class="mui-table-view-cell" style="padding: 8px 5px;"> ');  
sb.append('			 	<div class="mui-media-body">');   
sb.append('			 		<img class="mui-media-object mui-pull-left" style="width:20px;height:20px;" src="'+v.headimgurl+'">'); 
sb.append('			 		<span style="width:80px;text-align:left;display:inline-block;">'+v.givenname+'</span>【'+v.praise_info+'】'); 
sb.append('			 	</div> '); 
sb.append('			 	<div class="mui-media-body" style="padding-left:30px;color:#4d4d4d">');   
sb.append('			 		 '+v.remark); 
sb.append('			 	</div>');    

if (v.hasimages > 0)
{
	sb.append('			 	<div class="mui-media-body" style="padding-left:30px;">');   
    Zepto.each(v.images , function(ii, vv) {    
		       sb.append('<img class="mui-media-object mui-pull-left" src="'+vv+'" data-preview-src="" data-preview-group="appraise">');
    }); 
	sb.append('			 	</div>');    
}

sb.append('			 	<div class="mui-media-body" style="padding-left:30px;color:#999">');   
sb.append('			 		 '+v.published); 
sb.append('			 	</div>'); 
sb.append('			 </li>'); 	
				 return sb.toString(); 
		 } 
 
		   										   
		 
	    function add_more() {
			var page = Zepto('#page').val();
			var productid = Zepto('#productid').val();
	            Zepto('#page').val(parseInt(page,10) + 1);
	            mui.ajax({
	                type: 'POST',
	                url: "detail_appraise.ajax.php",
	                data: 'productid='+productid+'&page=' + page,
	                success: function(json) {   
	                    var msg = eval("("+json+")");
	                    if (msg.code == 200) {  
							if (msg.data.length == 0 && page == 1)
							{
								  Zepto('#list').html(''); 
								  mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
							}
							else
							{
		                        Zepto.each(msg.data, function(i, v) {    
									    var nd = appraise_html(v); 
			                            Zepto('#list').append(nd);  
		                        });    
		                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
							} 
	                    } else {
	                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
	                    }
	                }
	            }); 
	    } 
	    //触发第一页
	    if (mui.os.plus) {
	        mui.plusReady(function() {
	            setTimeout(function() {
	                mui('#pullrefresh').pullRefresh().pullupLoading();
	            }, 1000);

	        });
	    } else {
	        mui.ready(function() {
	            Zepto('#page').val(1);  
				Zepto('#list').html('');  
	            mui('#pullrefresh').pullRefresh().pullupLoading();
	        });
	    } 
		
	   
	{/literal} 
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>