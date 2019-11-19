<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我领到的优惠券</title>
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
				 <h1 class="mui-title">我领到的优惠券</h1>
                 
			</header> 
			{/if} 
			{include file='footer.tpl'}   
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                    <div class="mui-scroll">   
   		                 <div id="list" class="mui-table-view" >     
 		 		 							<ul class="mui-table-view coupon-result"> 
												<div class="container " style="min-height: 320px;">
												                <div class="promocard-result">
												    <div class="coupon-success-msg">
												        <figure class="bg-pic circle-bg-pic">
												        	            <div class="bg-pic-content" style="background-image: url({$profile_info.headimgurl});"></div>
												        </figure>
												                    <p class="msg">{if $justcreated}我领到了{$sysinfo.sitename}的卡券啦~{else}你已经领过该卡券{/if}</p>
												            </div>
												    <!-- 优惠券信息 start -->
												        <div class="coupon-container">
												        <div class="coupon-mini">
												            <div class="coupon-type">卡券</div>
												            <div class="coupon-info">
																{if $justcreated}
																    {if $vipcardinfo.discount gt 0}
																	    <div class="coupon-value"><span>{$vipcardinfo.discount}</span><i>折</i></div>
																	{else}
													                    <div class="coupon-value"><span>{$vipcardinfo.amount}</span><i>元</i></div>
																	{/if}
													                <p>{if $vipcardinfo.orderamount eq '0'}下单直接可用{else}订单满 {$vipcardinfo.orderamount} 元可用{/if}</p>
																{else}
													                <h3 class="coupon-error">你已经领过了</h3>
													                <p class="coupon-error">{if $vipcardinfo.timelimit eq '0'}每人限领1张{else}不限次使用{/if}</p>
																{/if}
												            </div>
												        </div>
														{if $justcreated}
												        <div class="coupon-msg">
												            <span>该券已放入您的账户</span> 
												        </div>
														{/if}
												        <div class="coupon-validity">
												                        有效期：<i>{$vipcardinfo.starttime}</i> 至 <i>{$vipcardinfo.endtime}</i>
												        </div>
												        <div class="coupon-actions">
												            <a href="index.php" class="js-tj-use btn btn-block btn-main-action">进店逛逛</a>
												        </div>
												    </div>
												        <!-- 优惠券信息 end -->
												    <div class="cloud"></div>
												</div>
												<div class="promocard-others">
												        <a href="couponsofme.php" class="block-link name-card name-card-3col name-card-promocard name-card-my">
												        <figure class="thumb">
												            <img src="{$profile_info.headimgurl}">
												        </figure>
												        <div class="detail">
												            <h3>
												                <strong>我领到的卡券总额</strong>
												            </h3>
												        </div>
												        <div class="right-col">
												            <div class="price" style="padding-right:5px;">
												                <span>{$amount}</span>元
												            </div>
												        </div>
												        </a>
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
			mui('.mui-table-view').on('tap','a',function(e){
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