<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>收货地址管理</title>
	<link href="public/css/mui.css" rel="stylesheet"/>
	<link href="public/css/public.css" rel="stylesheet"/>
	<link href="public/css/iconfont.css" rel="stylesheet"/>
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<style>
		{literal}
		.img-responsive {
			display: block;
			height: auto;
			width: 100%;
		}

		.mui-radio.mui-left label {
			padding-right: 3px;
			padding-left: 25px;
		}

		.mui-radio.mui-left input[type='radio'] {
			left: 3px;
		}

		.mui-table-view .mui-media-object {
			line-height: 100px;
			max-width: 100px;
			height: 100px;
		}

		.mui-ellipsis {
			line-height: 20px;
			margin-bottom: 0px;
		}

		.tishi {
			color: #fe4401;
			width: 100%;
			text-align: center;
			padding-top: 10px;
		}

		.tishi .mui-icon {
			font-size: 4.4em;
		}

		.msgbody {
			width: 100%;
			font-size: 1.4em;
			line-height: 25px;
			color: #333;
			text-align: center;
			padding-top: 10px;
		}

		.msgbody a {
			font-size: 1.0em;
		}

		.mui-bar-tab .mui-tab-item .mui-icon {
			width: auto;
		}

		.mui-tab-item .mui-icon {
			font-size: 16px;
		}

		.mui-ellipsis {
			line-height: 17px;
		}

		.mui-table-view-cell.mui-radio.mui-left, .mui-table-view-cell.mui-checkbox.mui-left {
			padding-left: 15px;
		}

		.deliveraddress-cell {
			position: relative;
			overflow: hidden;
			padding: 11px 15px;
			background-color: inherit;
			-webkit-touch-callout: none;
		}

		.deliveraddress-edit-cell {
			padding-top: 20px;
			padding-left: 12px;
		}

		.deliveraddress-edit-cell span {
			font-size: 2.2em;
		}

		{/literal}
	</style>
	{include file='theme.tpl'}
</head>

<body>
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
	<div class="mui-inner-wrap">
		{if $return neq ''}
			{if $supplier_info.showheader eq '0'}
			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
				<a class="mui-icon mui-icon-back mui-pull-left" href="{$return}.php"></a>
				<h1 class="mui-title">收货地址管理</h1>
			</header>
			{/if} 
			<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
				<a class="mui-tab-item add" href="{$weixin_deliveraddress_url}">
					<span class="mui-icon iconfont icon-weixin">&nbsp;微信导入</span>
				</a>
				<a class="mui-tab-item add" href="deliveraddress_edit.php?orderid={$orderid}">
					<span class="mui-icon iconfont icon-zengjia">&nbsp;新增</span>
				</a>
				<a class="mui-tab-item return" href="{$return}.php">
					<span class="mui-icon iconfont icon-queren01">&nbsp;返回</span>
				</a>
			</nav>
		{else}
			{if $supplier_info.showheader eq '0'}
			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
				<a class="mui-icon mui-icon-back mui-pull-left" href="/submitorder.php"></a>
				<h1 class="mui-title">收货地址管理</h1>
			</header>
			{/if} 
			<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
				<a class="mui-tab-item add" href="{$weixin_deliveraddress_url}">
					<span class="mui-icon iconfont icon-weixin">&nbsp;微信导入</span>
				</a>
				<a class="mui-tab-item add" href="deliveraddress_edit.php?orderid={$orderid}">
					<span class="mui-icon iconfont icon-zengjia">&nbsp;新增</span>
				</a>
				<a class="mui-tab-item submit" href="#">
					<span class="mui-icon iconfont icon-queren01">&nbsp;确认</span>
				</a>
			</nav>
		{/if}

		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
			<div class="mui-scroll">
				<div class="mui-card" style="margin: 0 3px;">
					<form class="mui-input-group">
						<input type="hidden" id="orderid" name="orderid" value="{$orderid}"/>
						<ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
							{foreach name="deliveraddress" key=deliveraddressid item=deliveraddress_info  from=$deliveraddress}
								<li class="deliveraddress-cell mui-input-row mui-radio mui-left" style="height:80px;" id="shoppingcart_wrap_{$deliveraddressid}">
									<a href="deliveraddress_edit.php?record={$deliveraddressid}&orderid={$orderid}" class="deliveraddress-edit-cell mui-pull-right" style="width:10%">
										<span class="mui-icon iconfont icon-bianji"></span>
									</a>
									<div class="mui-pull-left" style="width:90%">
										<label>

											<div class="mui-media-body">
												<p class='mui-ellipsis' style="color:#333">
												<div class="mui-media-body  mui-pull-left">
													收货人：{$deliveraddress_info.consignee}
												</div>
												<div class="mui-media-body mui-pull-right">
													{$deliveraddress_info.mobile}
												</div>
												</p>
											</div>
											<div class="mui-media-body">
												<p class='mui-ellipsis' style="color:#333">
													收货地址：{$deliveraddress_info.province}{$deliveraddress_info.city}{$deliveraddress_info.district}
													<br>{$deliveraddress_info.shortaddress}
											</div>

										</label>
										<input {if $deliveraddress_info.selected eq '1'}checked{/if} value="{$deliveraddressid}" id="deliveraddress_{$deliveraddressid}" name="deliveraddress" type="radio" style="margin-top:20px;">
									</div>
								</li>
								{foreachelse}
								<div class="mui-content-padded">
									<p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>
									<p class="msgbody">您的还没有收货地址，快点新建您的收货地址吧!<br>
									</p>
								</div>
							{/foreach}
						</ul>
					</form>

				</div>
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

<script>
	{literal}
	mui.init({
				 pullRefresh: {
					 container: '#pullrefresh'
				 },
			 });
	mui.ready(function ()
			  {
				  mui('#pullrefresh').scroll();

				  mui('header.mui-bar').on('tap', 'a.mui-icon-back', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });

				  mui('.mui-table-view').on('tap', 'a.deliveraddress-edit-cell', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });
				  mui('.mui-bar').on('tap', 'a.add', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });
				  mui('.mui-bar').on('tap', 'a.return', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });
				  mui('.mui-bar').on('tap', 'a.submit', function (e)
				  {
					  var deliveraddressid = "";
					  var orderid = "";
					  Zepto('input[name=orderid]').each(function (index)
															   {
																   orderid = Zepto(this).val();
															   });

					  Zepto('input[name=deliveraddress]').each(function (index)
															   {
																   if (this.checked)
																   {
																	   deliveraddressid = Zepto(this).val();
																   }
															   });

					  if (deliveraddressid == "")
					  {
						  mui.toast('请新建或选择收货地址！');
					  }
					  else
					  {
						  mui.openWindow({
											 url: 'submitorder.php?deliveraddressid=' + deliveraddressid + '&record='+orderid,
											 id: 'info'
										 });
					  }
				  });

			  });
	{/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>