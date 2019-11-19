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
												<div class="container " style="min-height: 737px;">
												               
													
													<div class="content ">
												        <div class="content-body">

    
												            <div class="promote-card">
												            <div class="clearfix">
												                <h1 class="pull-left font-size-16 promote-card-name">农夫帮10元代金券</h1>
												             </div>
    
												        <p class="center promote-value">
												            <span class="promote-value-sign">￥</span>
												            10.00        </p>
												        <p class="center font-size-14 promote-limit">
												            订单满 100.00 元 (含运费)        </p>
												        <p class="center font-size-14 transparent-color">
												            有效日期：
												            2015-07-13 10:41 -
												            2015-12-31 10:41        </p>
												        <div class="dot"></div>
												    </div>

												                    <div class="get-promote-card">
												                <!-- 三种情况: 1:多商品跳至promocard/goodslist 2:单商品跳至商品详情页 3.全站通用优惠券跳至商品主页 -->
                                
												                    <a href="http://wap.koudaitong.com/v2/showcase/homepage?kdt_id=835526" class="btn btn-block btn-green">立即使用</a>
                
												                <p class="center">
												                    <a href="http://wap.koudaitong.com/v2/showcase/coupon/list" class="font-size-12 c-blue promote-card-list-link">查看我的卡券</a>
												                </p>
												               </div>
        
                
												        <!-- 卡券核销二维码待App上线后开放 -->
												        

        

												       
												</div>           
												 
												        <div id="shop-nav"></div>    
													  </div>   
													 </div> 
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