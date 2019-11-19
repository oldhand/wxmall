 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>订单详情</title>
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
	  
		  .ordersn { font-size:1.2em; font-weight:500; }
		  
	 	  #orders_products .mui-table-view .mui-media-object {
		 	   line-height: 84px;
		 	   max-width: 84px;
		 	   height: 84px;
	 	  }
		  #orders_products .mui-ellipsis {
		  	  line-height: 17px; 
		  }
	  	  .price {
	  	      color:#fe4401;
	  	  }
 		 .mui-table-view-cell:after { 
 		   left: 0px; 
 		 }
 		 .mui-table-view-chevron .mui-table-view-cell {
 		 	padding-right: 0px; 
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
					 <h1 class="mui-title" id="pagetitle">订单详情</h1>
				</header> 
				{/if} 
				  {include file='footer.tpl'}  
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
		                <div class="mui-scroll"> 
							<div class="mui-card" style="margin: 0 3px;"> 
								 <input id="orderid" name="orderid"  value="{$orderinfo.orderid}" type="hidden" > 
								 <input id="tradestatus" name="tradestatus"  value="{$orderinfo.tradestatus}" type="hidden" > 
								 <input id="notify"  value="0" type="hidden" >
						         <ul class="mui-table-view">  
									         
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left">
														<span class="mui-table-view-label">订单号：</span><span class="ordersn">{$orderinfo.order_no}</span><br>
														<span class="mui-table-view-label">成交状态：</span><span class="ordersn">{if $orderinfo.tradestatus eq 'trade'}成交{else}未成交{/if}</span><br> 
														<span class="mui-table-view-label">订单状态：</span><span class="ordersn">{$orderinfo.order_status}</span><br> 
														<span class="mui-table-view-label">支付方式：</span>{if $orderinfo.paymentmode eq '1'}立即支付{/if}{if $orderinfo.paymentmode eq '2'}货到付款{/if}{if $orderinfo.paymentmode eq '3'}到店付款{/if}<br>
														<span class="mui-table-view-label">订单总额：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.sumorderstotal}</span></b>
														</span> 
													</div>  
			                                </li>   
											{if $orderinfo.delivery neq ''}
												<li class="mui-table-view-cell"> 
														<div class="mui-media-body"> 
															<span class="mui-table-view-label">发货时间：</span><span class="ordersn">{$orderinfo.deliverytime}</span><br>
															<span class="mui-table-view-label">物流公司：</span><span class="ordersn">{$orderinfo.deliveryname}</span><br>
															<span class="mui-table-view-label">发货单号：</span><span class="ordersn">{$orderinfo.invoicenumber}</span><br>
														</div> 
				                                </li>
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body "> 
														    <a id="wuliu" href="wuliu.php?type={$orderinfo.deliveryname}&invoicenumber={$orderinfo.invoicenumber}" style="width:100%"><h5 class="show-content" style="padding: 10px;">物流信息【点击查看物流信息】</h5></a>
														</div>  
				                                </li> 
											{/if}
											{if $orderinfo.autoconfirmreceipt neq ''}
												<li class="mui-table-view-cell"> 
														<div class="mui-media-body" style="color:red;text-align:center;"> 
															{$orderinfo.autoconfirmreceipt}
														</div> 
				                                </li>
											{/if}
											{if $orderinfo.autosettlement neq ''}
												<li class="mui-table-view-cell"> 
														<div class="mui-media-body" style="color:red;text-align:center;"> 
															{$orderinfo.autosettlement}
														</div> 
				                                </li>
											{/if} 
											{if $orderinfo.tradestatus eq 'trade'}
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left">
														<span class="mui-table-view-label">付款时间：</span>
														    <span class="ordersn">{$orderinfo.paymenttime}</span>
														</span><br> 
				 										<span class="mui-table-view-label">支付通道：</span>
														     <span class="ordersn">{$orderinfo.payment}</span>
														</span><br>
														<span class="mui-table-view-label">余额支付：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.usemoney}</span></b>
														</span><br> 
														<span class="mui-table-view-label">卡券优惠：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.discount}</span></b>
														</span><br>
														<span class="mui-table-view-label">微信支付：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.paymentamount}</span></b>
														</span>
													</div>  
			                                </li>
											{/if}
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left"> 
														 <span class="mui-table-view-label">收货人：</span>{$orderinfo.consignee}<br>
														<span class="mui-table-view-label">收货手机：</span>{$orderinfo.mobile}<br>
														<span class="mui-table-view-label">收货地址：</span>{$orderinfo.address}
													</div> 
			                                </li>
											 {if $orderinfo.addpostage|floatval gt 0 }
												 <li class="mui-table-view-cell">
													 <div class="mui-media-body">
														 偏远地区附加邮费：<span class="price">¥{$orderinfo.addpostage}</span>
													 </div>
												 </li>
											 {/if}
								 </ul> 
								
					    </div>
						<div class="mui-card" style="margin: 0 3px;margin-top: 5px;" id="orders_products"> 
								 <ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
									  {foreach name="orders_products" item=orders_products_info  from=$orderinfo.orders_products}
		  									<li class="mui-table-view-cell mui-left" style="min-height:104px;">
  											        <img class="mui-media-object mui-pull-left"  src="{$orders_products_info.productthumbnail}">
  													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">{$orders_products_info.productname}</p> 
  														<p class='mui-ellipsis'>属性：{$orders_products_info.propertydesc}</p>
														<p class='mui-ellipsis'>数量：{$orders_products_info.quantity}件</p> 
  														<p class='mui-ellipsis'>{if $orders_products_info.zhekou neq '' && $orders_products_info.zhekou|floatval gt 0 }{if $orders_products_info.activitymode eq '1'}底价{else}活动价{/if}：<span class="price">¥{$orders_products_info.shop_price}</span> <span style="color:#878787;margin-left:5px;text-decoration:line-through;">¥{$orders_products_info.old_shop_price}</span>{else}单价：<span class="price">¥{$orders_products_info.shop_price}</span>{/if}</p>
														{if $orders_products_info.activitymode eq '1'}
															<p class='mui-ellipsis'>
																砍价：{if $orders_products_info.bargains_count eq 0}还没有好友帮忙砍价{else}已有 {$orders_products_info.bargains_count} 位好友帮忙砍价{/if}
															</p>
														{/if}
														{if $orders_products_info.postage|@floatval gt 0 && ($orders_products_info.includepost|@intval eq 0 || $orders_products_info.includepost|@intval gt $orders_products_info.productallcount|@intval)}
															<p class='mui-ellipsis'>
																邮费：
																<span class="price">
																	¥{if $orders_products_info.mergepostage|@intval eq 1}{$orders_products_info.postage}{else}{$orders_products_info.postage*$orders_products_info.quantity}{/if}
																</span>
																{if $orders_products_info.includepost|@intval gt 0}
																	<span style="color:#878787;margin-left:10px;">({$orders_products_info.includepost}
																												  件包邮)</span>
																{/if}
															</p>
														{/if}
														{if $orders_products_info.postage|@floatval gt 0 && ($orders_products_info.includepost|@intval eq 0 || $orders_products_info.includepost|@intval gt $orders_products_info.productallcount|@intval)}
															<p class='mui-ellipsis'>小计：<span id="total_price_{$shoppingcart_info.id}" class="price">¥{$orders_products_info.total_price+$orders_products_info.postage|string_format:"%.2f"}</span></p>
														{else}
															<p class='mui-ellipsis'>小计：<span id="total_price_{$shoppingcart_info.id}" class="price">¥{$orders_products_info.total_price}</span></p>
														{/if}
  													</div> 
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
			mui('.mui-table-view-cell').on('tap','a#wuliu',function(e){
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