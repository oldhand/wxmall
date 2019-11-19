 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>领取卡券</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
	<link href="public/css/voupons.css" rel="stylesheet" /> 
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
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
				 <h1 class="mui-title">领取卡券</h1> 
			</header>   
			{/if} 
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                    <div class="mui-scroll">   
   		                 <div id="list" class="mui-table-view" >     
		 		 							<ul class="mui-table-view promocard">
												<div class="container " style="min-height: 640px;">
												        <div class="promocard-body">
														           <div class="coupon">
																        <div class="shop-info">
																            <figure class="bg-pic circle-bg-pic">
																                <div class="bg-pic-content" style="background-image: url({$businesse_info.thumb});"></div>
																            </figure>
																            <p>{$sysinfo.sitename}</p>
																        </div>
																        <div class="coupon-msg">送你一张卡券</div>
																    </div>
																        <form class="js-form block-wrapper-form promocard-fetch-form" name="frm" id="frm" method="post" action="coupons_view.php"> 
														 					<input name="record" type="hidden" value="{$record}">
																			<input name="type" type="hidden" value="submit">
																	        <div class="block-form-item">
																	            <button type="submit" class="js-btn-get btn btn-block">点击领取优惠券</button>
																	        </div>
																	    </form> 
												        </div>    
											      </div>
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
			mui('.mui-table-view').on('tap','button.btn-block',function(e){  
					document.frm.submit(); 
			}); 
	   }); 
	{/literal} 
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>