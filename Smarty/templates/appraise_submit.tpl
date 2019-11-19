 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>{if $orderinfo.appraisestatus eq 'yes'}查看评价{else}去评价{/if}</title>
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
		  .totalprice {color:#CF2D28; font-size:1.2em; font-weight:500; }
		  .price {color:#CF2D28; }
	  
		  .ordersn { font-size:1.2em; font-weight:500; }
		  
	 	  #orders_products .mui-table-view .mui-media-object {
		 	   line-height: 84px;
		 	   max-width: 84px;
		 	   height: 84px;
	 	  }
		  #orders_products .mui-ellipsis {
		  	  line-height: 17px; 
		  }
		  
		  .mui-table-view-chevron .mui-table-view-cell { padding-right: 25px; }
		  .mui-navigate-right:after, .mui-push-right:after {  right: 45px;  }
		  
		  
		  .no_appraise{
		    font-family: Muiicons;
		    font-size: inherit;
		    line-height: 1;
		    position: absolute;
		    top: 50%;
			right: 5px;
			color:#CF2D28; 
			font-size: 1.6em;
		    display: inline-block;
		    -webkit-transform: translateY(-50%);
		    transform: translateY(-50%);
		    text-decoration: none; 
		    -webkit-font-smoothing: antialiased;
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
					 <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title" id="pagetitle">{if $orderinfo.appraisestatus eq 'yes'}查看评价{else}去评价{/if}</h1>
				</header> 
				{/if}  
				  {include file='footer.tpl'}  
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top:{if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}"> 
		                <div class="mui-scroll"> 
							<div class="mui-card" style="margin: 0 3px;"> 
								 <input id="orderid" name="orderid"  value="{$orderinfo.orderid}" type="hidden" > 
								 <input id="tradestatus" name="tradestatus"  value="{$orderinfo.tradestatus}" type="hidden" > 
								 <input id="notify"  value="0" type="hidden" >
						         <ul class="mui-table-view">  
									         
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left">
														<span class="mui-table-view-label">订单号：</span><span class="ordersn">{$orderinfo.order_no}</span><br>
													    <span class="mui-table-view-label">订单状态：</span><span class="ordersn">{$orderinfo.order_status}</span><br> 
														<span class="mui-table-view-label">订单总额：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.sumorderstotal}</span></b>
														</span> 
													</div>  
			                                </li>  
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left">
														<span class="mui-table-view-label">付款时间：</span>
														    <span class="ordersn">{$orderinfo.paymenttime}</span>
														</span><br> 
				 										<span class="mui-table-view-label" style="width:85px;">确认收货时间：</span>
														     <span class="ordersn">{$orderinfo.confirmreceipt_time}</span>
														</span><br> 
													</div>  
			                                </li> 
 			                                <li class="mui-table-view-cell" id="msg-wrap"> 
 													<div class="mui-media-body" id="msg-wrap-body" style="color:red;text-align:center;"> 
													  评价能提高您的积分，将获得平台更高的优惠政策！
 													</div>  
 			                                </li>
								 </ul> 
								
					    </div>
						<div class="mui-card" style="margin: 0 3px;margin-top: 5px;" id="orders_products"> 
								 <ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
									  {foreach name="orders_products" item=orders_products_info  from=$orderinfo.orders_products}
		  									<li class="mui-table-view-cell mui-left" style="height:104px;"> 
												<a class="mui-navigate-right" href="appraise_product_submit.php?record={$orders_products_info.id}">
  											        <img class="mui-media-object mui-pull-left" src="{$orders_products_info.productthumbnail}">
  													<div class="mui-media-body mui-pull-left" style="width:180px">
														<p class='mui-ellipsis' style="color:#333;">{$orders_products_info.productname}</p> 
  														<p class='mui-ellipsis'>属性：{$orders_products_info.propertydesc}</p>
														<p class='mui-ellipsis'>数量：{$orders_products_info.quantity}件</p> 
  														<p class='mui-ellipsis'>{if $orders_products_info.zhekou neq ''}活动价：<span class="price">¥{$orders_products_info.shop_price}</span> <span style="color:#878787;margin-left:5px;text-decoration:line-through;">¥{$orders_products_info.old_shop_price}</span>{else}单价：<span class="price">¥{$orders_products_info.shop_price}</span>{/if}</p>
  														<p class='mui-ellipsis' style="color:#333">
															{if $orders_products_info.praiseid neq ''}
															    <span style="color:#CF2D28;" class="mui-icon iconfont icon-yipingjia"></span>&nbsp;已评价
															{else}
																<span style="color:#CF2D28;" class="mui-icon iconfont icon-daipingjia"></span>&nbsp;待评价【点击评价】
															{/if} 
														</p>
  													</div>  
												</a> 
		  									</li> 
							   		  {/foreach}
						 	 	</ul> 
						</div>
						<div class="mui-card" style="margin: 0 3px;margin-top: 5px;"> 
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
			mui('.mui-card').on('tap','a',function(e){
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