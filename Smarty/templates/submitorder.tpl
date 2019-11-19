<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>确认订单</title>
	<link href="public/css/mui.css" rel="stylesheet"/>
	<link href="public/css/public.css" rel="stylesheet"/>
	<link href="public/css/iconfont.css" rel="stylesheet"/>
	<link href="public/css/mui.picker.css" rel="stylesheet"/>
	<link href="public/css/mui.listpicker.css" rel="stylesheet"/>
	<link href="public/css/mui.dtpicker.css" rel="stylesheet"/>
	<link href="public/css/sweetalert.css" rel="stylesheet"/>
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/mui.picker.js"></script>
	<script src="public/js/mui.listpicker.js"></script>
	<script src="public/js/mui.dtpicker.js"></script>
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/sweetalert.min.js"></script>
	<style>
		{literal}
		.img-responsive {
			display: block;
			height: auto;
			width: 100%;
		}

		.menuicon {
			font-size: 1.4em;
			color: #fe4401;
			padding-right: 10px;
		}

		.menuitem a {
			font-size: 1.3em;
		}

		.mui-radio.mui-left label {
			padding-right: 3px;
			padding-left: 35px;
		}

		.mui-radio.mui-left input[type='radio'] {
			left: 3px;
		}

		.mui-table-view .mui-media-object {
			line-height: 84px;
			max-width: 84px;
			height: 84px;
		}

		.mui-input-row {
			margin: 2px;
		}

		.mui-ellipsis {
			line-height: 20px;
			margin-bottom: 0px;
		}

		.mui-bar-tab .mui-tab-item .mui-icon {
			width: auto;
		}

		.mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
			color: #cc3300;
		}

		.mui-ellipsis {
			line-height: 17px;
		}

		.price {
			color: #fe4401;
		}

		.mui-radio input[type='radio'], .mui-checkbox input[type='checkbox'] {
			top: 0px;
		}

		.mui-table-view-cell .mui-table-view-label {
			width: 60px;
			text-align: right;
			display: inline-block;
		}

		#expectedconsumptiontime {
			line-height: 25px;
			width: 100%;
			height: 25px;
			margin-bottom: 0px;
			padding: 2px 5px;
			font-size: 12px;
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
			<a class="mui-icon mui-icon-back mui-pull-left" href="{$returnbackatcion}"></a>
			<h1 class="mui-title">确认订单</h1>
		</header>
		{/if} 
		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
			<div class="mui-tab-item" style="width:70%;color:#929292;">
						<span class="mui-tab-label"> 
							<div class="mui-pull-right" style="line-height: 20px;">
								合计：<span class="price" id="total_money">¥{$total_money}</span>元<br>共计&nbsp;<span class="price" id="total_quantity">{$total_quantity}</span>&nbsp;件商品
							</div>
					    </span>
			</div> 
				{if $allowpayment eq 'true'}
				<a class="mui-tab-item confirmpayment" href="#" style="width:30%">
					<span class="mui-icon iconfont icon-queren01">&nbsp;确认</span>
				</a>
				{/if}  
		</nav>

		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
			<div class="mui-scroll">
				<form method="post" name="frm" action="/confirmpayment.php">
					<input name="token" value="{$token}" type="hidden">
					<input name="record" value="{$orderid}" type="hidden">
					<input id="sumorderstotal" value="{$total_money}" type="hidden">
					<input id="deliveraddress_count" value="{$deliveraddress|@count}" type="hidden">
					<div class="mui-card" style="margin: 0 3px;">
						<ul class="mui-table-view">
							{if $deliveraddress|@count eq 0}
								<li class="mui-table-view-cell">
									<a href="/deliveraddress.php" class="mui-navigate-right deliveraddress">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">收货地址：</span> 您还没有收货地址，赶快去创建吧！
										</div>
									</a>
								</li>
							{else}
								<input name="deliveraddress" value="{$deliveraddress.recordid}" type="hidden"/>
								<li class="mui-table-view-cell">
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">收货人：</span>{$deliveraddress.consignee}
									</div>
									<div class="mui-media-body mui-pull-right">
										{$deliveraddress.mobile}
									</div>
								</li>
								{if $tradestatus eq 'pretrade'}
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">收货地址：</span>{$deliveraddress.province}{$deliveraddress.city}{$deliveraddress.district}
											<br>
											<span class="mui-table-view-label">&nbsp;</span>{$deliveraddress.shortaddress}
										</div>
									</li>
								{else}
									<li class="mui-table-view-cell">
										<a href="deliveraddress.php?orderid={$orderid}" class="mui-navigate-right deliveraddress">
											<div class="mui-media-body  mui-pull-left">
												<span class="mui-table-view-label">收货地址：</span>{$deliveraddress.province}{$deliveraddress.city}{$deliveraddress.district}
												<br>
												<span class="mui-table-view-label">&nbsp;</span>{$deliveraddress.shortaddress}
											</div>
										</a>
									</li>
								{/if}
							{/if}
							<!--
							{if $tradestatus eq 'pretrade'}
								<li class="mui-table-view-cell">
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">发票：</span>{if $fapiao eq ''}无需发票{else}{$fapiao}{/if}
										<input name="fapiao" value="{$fapiao}" type="hidden"/>
									</div>
								</li>
							{else}
								{if $total_money gt 99}
									<li class="mui-table-view-cell">
										<a href="fapiao.php" class="mui-navigate-right fapiao">
											<div class="mui-media-body  mui-pull-left">
												<span class="mui-table-view-label">发票：</span>{if $fapiao eq ''}无需发票{else}{$fapiao}{/if}
												<input name="fapiao" value="{$fapiao}" type="hidden"/>
											</div>
										</a>
									</li>
								{else}
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">发票：</span>订单总额&nbsp;<span class="price">¥100</span>&nbsp;以上,可以开发票
											<input name="fapiao" value="" type="hidden"/>
										</div>
									</li>
								{/if}
							{/if}-->
							{if $supplier_info.vipauthentication eq '1' && $authenticationid neq ''}  
								<li class="mui-table-view-cell">
									<div class="mui-media-body" style="color:#cc3300;text-align:center"> 您已经认证{$rankname},可享受<!--{$rankdiscount}-->折扣优惠! 
									</div>
								</li>
								<input id="authenticationid" name="authenticationid" value="{$authenticationid}" type="hidden">
							{else}
								 <input id="authenticationid" name="authenticationid" value="" type="hidden">
							{/if}
							{if $supplier_info.rankcost eq 0 && $profile_info.rank gt 0}
								<li class="mui-table-view-cell">
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">可用积分： </span><span class="totalprice"><b><span style="font-size: 16px;">{$profile_info.rank}</span></b></span>
									</div>
								</li> 
								<li class="mui-table-view-cell">
									<div class="mui-media-body  mui-pull-left">
										<div class="mui-checkbox mui-left mui-pull-left" style="width:110px;height:26px;line-height:26px;">
											<label style="padding-right:0px;">使用积分</label>
											<input id="userank" name="userank" value="1" checked type="checkbox">
										</div>
										<div class="mui-pull-left" style="height:26px;line-height:26px;">
											【可抵扣<span class="totalprice" id="allowmoney">{$rankdeductionmoney}</span>元】
										</div>
									</div> 
								</li>
							{else}
								 <input id="userank" name="userank" value="0" type="hidden">
							{/if}

							<div id="delivermode-wrap" class="mui-table-view-cell" style="margin-top:3px;display:none;">
								<div id="delivermode-errormsg" class="mui-media-body" style="color:#cc3300;text-align:center">
								</div>
							</div>

							{if $addpostage|floatval gt 0 }
								<li class="mui-table-view-cell">
									<div class="mui-media-body">
										偏远地区附加邮费：<span class="price">¥{$addpostage}</span>
									</div>
								</li>
							{/if}

							<li class="mui-table-view-cell" style="padding: 4px 15px;">
								<textarea {if $tradestatus eq 'pretrade'}disabled="disabled"{/if} style="margin-bottom: 0px;padding: 5px;font-size: 15px;" class="mui-input-clear required" placeholder="这里您可以留言" id="buyermemo" name="buyermemo" rows="2">{$customersmsg}</textarea>
							</li>
							{if $allowpayment neq 'true'}
							 <div class="mui-table-view-cell"
                                     style="margin-top:3px;">
                                    <div  class="mui-media-body"
                                         style="color:#cc3300;text-align:center">
	                                         您必须通过会员认证后，才能支付下单！
                                    </div>
                                </div>
							{/if}
						</ul>
					</div>
					<div class="mui-card" style="margin: 5px 3px;">
						<ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
							{foreach name="shoppingcarts" item=shoppingcart_info  from=$shoppingcarts}
								<li class="mui-table-view-cell mui-left" style="min-height:104px;height: auto;  padding-right: 5px;">
									<img class="mui-media-object mui-pull-left" src="{$shoppingcart_info.productthumbnail}">
									<div class="mui-media-body">
										<p class='mui-ellipsis' style="color:#333">{$shoppingcart_info.productname}</p>
										{if $shoppingcart_info.propertydesc neq ""}
											<p class='mui-ellipsis'>属性：{$shoppingcart_info.propertydesc}</p>
										{/if}
										<p class='mui-ellipsis'>数量：{$shoppingcart_info.quantity}件</p>
										<p class='mui-ellipsis'>{if $shoppingcart_info.zhekou neq ''}{if $shoppingcart_info.activitymode eq '1'}底价{else}活动价{/if}：
												<span class="price">¥{$shoppingcart_info.shop_price}</span>
												<span style="color:#878787;margin-left:5px;text-decoration:line-through;">
												¥{$shoppingcart_info.old_shop_price}</span>{else}单价：<span class="price">
												¥{$shoppingcart_info.shop_price}</span>{/if}</p>
										{if $shoppingcart_info.activitymode eq '1'}
											<p class='mui-ellipsis'>
												砍价：{if $shoppingcart_info.bargains_count eq 0}还没有好友帮忙砍价{else}已有 {$shoppingcart_info.bargains_count}位好友帮忙砍价{/if}
											</p>
										{/if}
										{if $shoppingcart_info.postage|@floatval gt 0 && ($shoppingcart_info.includepost|@intval eq 0 || $shoppingcart_info.includepost|@intval gt $shoppingcart_info.productallcount|@intval) && ($supplier_info.totalpricefreeshipping|@floatval eq 0 || $supplier_info.totalpricefreeshipping|@floatval gt $total_money) && ($supplier_info.totalquantityfreeshipping|@intval eq 0 || $supplier_info.totalquantityfreeshipping|@intval gt $total_quantity)}
											<p class='mui-ellipsis'>
												邮费：
												<span class="price">
													¥{if $shoppingcart_info.mergepostage|@intval eq 1}{$shoppingcart_info.postage}{else}{$shoppingcart_info.postage*$shoppingcart_info.quantity|string_format:"%0.2f"}{/if}
												</span>
												{if $shoppingcart_info.includepost|@intval gt 0}
													<span style="color:#878787;margin-left:10px;">({$shoppingcart_info.includepost}
																								  件包邮)</span>
												{/if}
											</p>
										{/if}
										<p class='mui-ellipsis'>
											小计：<span id="total_price_{$shoppingcart_info.id}" class="price">¥{$shoppingcart_info.total_price}</span>
										</p>
									</div>
								</li>
							{/foreach}
						</ul>
					</div>
				</form> 
			</div>
		</div>
		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;bottom: 50px;">
			<ul class="mui-table-view" style="background-color: #efeff4;">
				<li class="mui-table-view-cell mui-media">
					<img class="img-responsive" src="/images/baozhang.png">
				</li>
			</ul>
		</nav>
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
	mui.ready(function ()
			  {
				  mui('#pullrefresh').scroll();

				  mui('.msgbody').on('tap', 'a', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });

				  mui('.mui-table-view').on('tap', 'a.deliveraddress', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });
				  mui('.mui-table-view').on('tap', 'a.fapiao', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });
				  mui('.mui-bar').on('tap', 'a.confirmpayment', function (e)
				  {
					  var deliveraddress_count = $("#deliveraddress_count").val();
					  if (deliveraddress_count != "0")
					  {
						  document.frm.submit();
					  }
					  else
					  {
						  sweetAlert("警告", "请先创建收货地址，谢谢！", "error");
					  }

				  });
				  mui('header.mui-bar').on('tap', 'a.mui-icon-back', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });

			  });

	{/literal}
</script>
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>