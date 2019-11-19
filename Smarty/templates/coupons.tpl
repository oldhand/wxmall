 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>卡券优惠</title>
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
	   	 .mui-bar .tit-sortbar{
	   	 	left: 0;
	   	 	right: 0;
	   	 	margin-top: 45px; 
	   	 }
		 .mui-bar .mui-segmented-control {
		   top: 0px; 
		 }
		 .mui-segmented-control.mui-segmented-control-inverted .mui-control-item {
		   color: #333; 
		 } 
	 	 .price {
	 	  color:#fe4401;
	 	 } 
	  	 .mui-table-view-cell .mui-table-view-label
	  	 {
	  	    width:60px;
	  		text-align:right;
	  		display:inline-block;
	  	 } 
	 	.tishi
	 	{
	 		color:#fe4401; 
	 		width:100%; 
	 		text-align:center;
	 		padding-top:10px;
	 	}
	 	.tishi .mui-icon
	 	{ 
	 		font-size: 4.4em; 
	 	}
	 	.msgbody
	 	{ 
	 		width:100%;
	 		font-size: 1.4em;
	 		line-height: 25px;
	 		color:#333;
	 		text-align:center;
	 		padding-top:10px;
	 	} 
	 	.msgbody a 
	 	{   
	 		font-size: 1.0em; 
	 	} 
	 {/literal} 
	</style>
	{include file='theme.tpl'} 
</head>
<body>  
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper"> 
		<div class="mui-inner-wrap">
			{if $supplier_info.showheader eq '1'}
				<header class="mui-bar mui-bar-nav" style="padding-right: 15px;background-color:#fff">   
	                 <div class="mui-title mui-content tit-sortbar" id="sortbar" style="margin-top: 0px;"> 
			 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
			 					<a class="mui-control-item mui-active" href="coupons.php">卡券优惠</a>
			 					<a class="mui-control-item" href="couponsofme.php">我的卡券使用记录</a> 
			 				</div> 
	                 </div>
				</header>
			{else}
				<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
					 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title">卡券优惠</h1>
	                 <div class="mui-title mui-content tit-sortbar" id="sortbar"> 
			 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
			 					<a class="mui-control-item mui-active" href="coupons.php">卡券优惠</a>
			 					<a class="mui-control-item" href="couponsofme.php">我的卡券使用记录</a> 
			 				</div> 
	                 </div>
				</header> 
			{/if}
			{include file='footer.tpl'}   
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '1'}40px;{else}85px;{/if}">
                    <div class="mui-scroll">   
   		                 <div id="list" class="mui-table-view" >     
		 		 							<ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;">
												<div class="promote-card-list" style="margin: 10px 10px;">  
			  									  {foreach name="vipcards" key=vipcardid item=vipcard_info  from=$vipcardlist} 
											           {assign var="iteration" value=$smarty.foreach.vipcards.iteration} 
   													   <li class="promote-item {if $iteration  is  odd }coupon-style-0{else}coupon-style-1{/if}">
   									                         <a class="clearfix" href=" {if $vipcard_info.usagesid eq ''}coupons_fetch.php{else}coupons_view.php{/if}?record={$vipcardid}">
   									                             <div class="promote-left-part">
   									                                 <div class="inner">
   									                                     <h4 class="promote-shop-name font-size-14">{$vipcard_info.vipcardname} 【{if $vipcard_info.timelimit eq '0'}单次{else}不限次{/if}】</h4>
   									                                     {if $vipcard_info.cardtype eq '0'}
																			 <div class="promote-card-value">
	   									                                         <span>￥</span><i>{$vipcard_info.amount}</i>
	   									                                     </div>
	   									                                     <div class="promote-condition font-size-12">订单满{$vipcard_info.orderamount}.00元</div>
																		 {elseif $vipcard_info.cardtype eq '1'}
																			 <div class="promote-card-value">
	   									                                         <span>￥</span><i>{$vipcard_info.amount}</i>
	   									                                     </div>
	   									                                     <div class="promote-condition font-size-12">下单直接送</div>
																		 {elseif $vipcard_info.cardtype eq '2'}
																			 <div class="promote-card-value">
	   									                                        <i>{$vipcard_info.discount}</i> <span>折</span>
	   									                                     </div>
																			 {if $vipcard_info.orderamount eq '0'}
																			 	 <div class="promote-condition font-size-12">下单直接送</div>
																			 {else}
	   									                                    	 <div class="promote-condition font-size-12">订单满{$vipcard_info.orderamount}.00元</div>
																			 {/if}
																		 {/if} 
																		 <div class="promote-condition font-size-12">已领{$vipcard_info.remaincount}张，总计{$vipcard_info.count}张</div>
   									                                 </div>
   									                             </div>
   									                             <div class="promote-right-part center font-size-12">
   									                                 <div class="inner">
   									                                     <div>
   									                                         <p>使用期限</p>
   									                                         <p>{$vipcard_info.starttime}</p>
   									                                         <p>{$vipcard_info.endtime}</p>
   									                                     </div>
																		 {if $vipcard_info.usagesid eq ''}
																		     <div class="promote-use-state font-size-16">未领用</div>
																		 {else}
																		     <div class="promote-use-state font-size-16" style="color:#18d07b">{$vipcard_info.usagesstatus}</div>
																		 {/if}
   									                                     
   									                             </div>
   									                             <i class="expired-icon"></i>
   									                             <i class="left-dot-line"></i>
   									                         </a>
   									                  </li>
			  							   		  {foreachelse}
			  										    <div class="mui-content-padded">
			  						 					   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi" style="color: #fe4401;"></span></p>
			  												      <p class="msgbody">本店铺暂时没有设置卡券!<br> 
			  													  </p>  
			  							   	            </div>
			  							   		  {/foreach}
													
													
													<!--
													
													 <li class="promote-item coupon-style-0">
									                         <a class="clearfix" href="coupons_view.php">
									                             <div class="promote-left-part">
									                                 <div class="inner">
									                                     <h4 class="promote-shop-name font-size-14">甜甜圈10元代金券</h4>
									                                     <div class="promote-card-value">
									                                         <span>￥</span><i>10.00</i>
									                                     </div>
									                                     <div class="promote-condition font-size-12">
									                                         订单满 100.00 元 (含运费)                                    </div>
									                                 </div>
									                             </div>
									                             <div class="promote-right-part center font-size-12">
									                                 <div class="inner">
									                                     <div>
									                                         <p>使用期限</p>
									                                         <p>2015.07.13</p>
									                                         <p>2015.12.31</p>
									                                     </div>
									                                     <div class="promote-use-state font-size-16">
									                                         未使用                                    </div>
									                                 </div>
									                             </div>
									                             <i class="expired-icon"></i>
									                             <i class="left-dot-line"></i>
									                         </a>
									                  </li>
													  
													  <li class="promote-item coupon-style-1">
								                          <a class="clearfix" href="coupons_fetch.php">
								                              <div class="promote-left-part">
								                                  <div class="inner">
								                                      <h4 class="promote-shop-name font-size-14">甜甜圈5元代金券</h4>
								                                      <div class="promote-card-value">
								                                          <span>￥</span><i>5.00</i>
								                                      </div>
								                                      <div class="promote-condition font-size-12">
								                                          订单满 50.00 元 (含运费)                                    </div>
								                                  </div>
								                              </div>
								                              <div class="promote-right-part center font-size-12">
								                                  <div class="inner">
								                                      <div>
								                                          <p>使用期限</p>
								                                          <p>2015.07.13</p>
								                                          <p>2015.12.31</p>
								                                      </div>
								                                      <div class="promote-use-state font-size-16">
								                                          未使用                                    </div>
								                                  </div>
								                              </div>
								                              <i class="expired-icon"></i>
								                              <i class="left-dot-line"></i>
								                          </a>
								                      </li>
													  <li class="promote-item coupon-style-1">
								                          <a class="clearfix" href="coupons_display.php">
								                              <div class="promote-left-part">
								                                  <div class="inner">
								                                      <h4 class="promote-shop-name font-size-14">甜甜圈5元代金券</h4>
								                                      <div class="promote-card-value">
								                                          <span>￥</span><i>5.00</i>
								                                      </div>
								                                      <div class="promote-condition font-size-12">
								                                          订单满 50.00 元 (含运费)                                    </div>
								                                  </div>
								                              </div>
								                              <div class="promote-right-part center font-size-12">
								                                  <div class="inner">
								                                      <div>
								                                          <p>使用期限</p>
								                                          <p>2015.07.13</p>
								                                          <p>2015.12.31</p>
								                                      </div>
								                                      <div class="promote-use-state font-size-16">
								                                          未使用                                    </div>
								                                  </div>
								                              </div>
								                              <i class="expired-icon"></i>
								                              <i class="left-dot-line"></i>
								                          </a>
								                      </li>-->
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