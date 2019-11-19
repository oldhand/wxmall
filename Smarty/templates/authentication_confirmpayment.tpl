<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>确认认证</title>
	<link href="public/css/mui.css" rel="stylesheet"/>
	<link href="public/css/public.css" rel="stylesheet"/>
	<link href="public/css/iconfont.css" rel="stylesheet"/>
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<style>
		{literal}
		.img-responsive {
			display: block;
			height: auto;
			width: 100%;
		}

		.menuicon {
			font-size: 1.2em;
			color: #fe4401;
			padding-right: 10px;
		}

		.menuitem a {
			font-size: 1.1em;
		}

		#payment_button {
			font-size: 20px;
			padding-left: 5px;
		}

		.mui-bar-tab .mui-tab-item .mui-icon {
			width: auto;
		}

		.mui-table-view-cell .mui-table-view-label {
			width: 100px;
			text-align: right;
			display: inline-block;
		}

		.totalprice {
			color: #CF2D28;
			font-size: 1.2em;
			font-weight: 500;
		}

		.btn {
			display: inline-block;
			padding: 6px 12px;
			margin-bottom: 0;
			font-weight: normal;
			line-height: 1.428571429;
			text-align: center;
			white-space: nowrap;
			vertical-align: middle;
			cursor: pointer;
			background-image: none;
			border: 1px solid transparent;
			border-radius: 4px;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			-o-user-select: none;
			user-select: none;
		}

		.btn-default {
			color: #333;
			background-color: #fff;
			border-color: #ccc;
		}

		.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active {
			color: #333;
			background-color: #ebebeb;
			border-color: #e53348;
			background: url(images/checked.png) no-repeat bottom right;
		}

		#radiogroup .mui-icon {
			font-size: 1.2em;
			color: #fe4401;
		}

		.mui-checkbox.mui-left input[type='checkbox'] {
			left: 5px;
			top: 0px;
		}

		.mui-checkbox.mui-left label {
			padding-right: 0px;
			padding-left: 36px;
		}

		.mui-radio.mui-left input[type='radio'] {
			left: 5px;
			top: 0px;
		}

		.mui-radio.mui-left label {
			padding-right: 10px;
			padding-left: 36px;
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
			<h1 class="mui-title" id="payment_title">确认认证</h1>
		</header>
		{/if}  
		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
			<a id="confirmpayment" class="mui-tab-item mui-active confirmpayment" href="#" style="width:30%">
				<span class="mui-icon iconfont icon-qian" id="payment_icon" style="top:0px;"></span><span id="payment_button">确定支付</span>
			</a>
		</nav>

		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}bottom: 50px;">
			<div class="mui-scroll"> 
				<input id="type" name="type" value="" type="hidden"> 
				<input id="orderid" name="orderid" value="" type="hidden"> 
				<input id="authenticationid" name="authenticationid" value="{$profilerank.authenticationid}" type="hidden"> 
				<div class="mui-card" style="margin: 0 3px 5px 3px;">
					<ul class="mui-table-view"> 
						<li class="mui-table-view-cell">
							<div class="mui-media-body  mui-pull-left">
								<span class="mui-table-view-label">认证目标： </span>{$profilerank.rankname}
							</div>
						</li> 
						<li class="mui-table-view-cell">
							<div class="mui-media-body  mui-pull-left">
								<span class="mui-table-view-label">认证可获得折扣： </span>{$profilerank.rankdiscount}<b><span style="font-size: 16px;">折</span></b></span>
							</div>
						</li>
						<li class="mui-table-view-cell">
							<div class="mui-media-body  mui-pull-left">
								<span class="mui-table-view-label">需支付保证金： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$profilerank.needmoney}</span></b></span>
							</div>
						</li>
							 
							<li class="mui-table-view-cell" id="paymentwaygroup">
								<div class="mui-media-body  mui-pull-left">
									<span class="mui-table-view-label">支付方式：</span>
								</div>
								<div class="yanse_xz mui-pull-left" id="radiogroup">
									<a class="btn btn-default radiogroup active" groupid="payment" paymentway="weixin" href="javascript:;">
										<span class="mui-icon iconfont icon-weixin" style="font-size: 3.5em;"></span>
										<div style="display:none">
											<input class="paymentway" id="weixin" type="radio" checked="true" name="paymentway" value="weixin"/>
										</div>
									</a> 
								</div>
							</li>
						</div>

					</ul>
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
</div> 

<script type="text/javascript"> 
	{literal}
	mui.init({
				 swipeBack: true, //启用右滑关闭功能
				 pullRefresh: {
					 container: '#pullrefresh'
				 },
			 });
	mui.ready(function ()
			  {
				  mui('#pullrefresh').scroll();

				  mui('header.mui-bar').on('tap', 'a.mui-icon-back', function (e)
				  {
					  if(this.getAttribute('href') != "" && this.getAttribute('href'))
					  {
						  mui.openWindow({
											 url: this.getAttribute('href'),
											 id: 'info'
										 });
					  }
				  });

				  mui('#radiogroup').on('tap', 'a', function (e)
				  {
					  if (Zepto('#type').val() == 'submit') return;
					  var paymentway = this.getAttribute('paymentway');
					  Zepto(".radiogroup").removeClass("active");
					  Zepto(this).addClass("active");

					  Zepto(".paymentway").attr("checked", false);
					  Zepto(".paymentway").prop("checked", false);
					  Zepto("#" + paymentway).attr("checked", true);
					  Zepto("#" + paymentway).prop("checked", true);
				  });

				 
				  mui('.mui-bar').on('tap', 'a.confirmpayment', function (e)
				  {
					  if (Zepto('#type').val() == 'submit') return;
					  confirmpayment();
				  });
				  
			  });

 

	function confirmpayment()
	{
		var paymentway = "";
		Zepto('input[name=paymentway]').each(function (index)
											 {
												 if (Zepto(this).attr("checked") == "true")
												 {
													 paymentway = Zepto(this).val();
												 }
											 });
 

		if (paymentway == "")
		{
			mui.toast("请选择支付方式！");
			return;
		} 
		Zepto('#type').val('submit');

		var authenticationid  = Zepto('#authenticationid').val(); 
		Zepto("#payment_button").html('正在提交,请等待!');
		Zepto("#payment_icon").removeClass("icon-qian");
		Zepto("#payment_icon").addClass("icon-loading");
		Zepto("#payment_icon").addClass("mui-rotation");
		Zepto("#confirmpayment").removeClass("confirmpayment");
		 
		mui.ajax({
					 type: 'POST',
					 url: "authentication_confirmpayment.php",
					 data: 'type=confirmpayment&authenticationid=' + authenticationid + '&paymentway=' + paymentway,
					 success: function (json)
					 {
						// alert(json);
						 var jsondata = eval("(" + json + ")");
						 if (jsondata.code == 200)
						 { 
							 var orderid = jsondata.orderid; 
							 if (jsondata.paymentway == 'weixin')
							 { 
								 Zepto('#orderid').val(orderid);
								 callpay(jsondata.json);
							 } 
							 else
							 {
								 mui.toast('支付失败');
								 setTimeout("back_confirmpayment();", 1500);
							 }

						 }
						 else
						 {
							
							 mui.toast(jsondata.msg);
							 setTimeout("back_confirmpayment();", 1500);
						 }
					 }
				 });
	}
	function back_confirmpayment()
	{
		Zepto('input[name=paymentway]').prop("disabled", '');
		Zepto('#type').val('');
		Zepto('#usemoney').prop("disabled", '');

		Zepto("#payment_button").html('确定支付');

		Zepto("#payment_icon").removeClass("icon-loading");
		Zepto("#payment_icon").removeClass("mui-rotation");

		Zepto("#payment_icon").addClass("icon-qian");

		Zepto("#confirmpayment").addClass("confirmpayment");
		 
	}
	function jsApiCall(jsondata)
	{
		WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				jsondata,
				function (res)
				{
					//WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+res.err_desc+res.err_msg); 
					if (res.err_msg == "get_brand_wcpay_request:cancel")
					{
						mui.toast("您的支付已经取消！");
						setTimeout("back_confirmpayment();", 1500);
					}
					else if (res.err_msg == "get_brand_wcpay_request:ok")
					{ 
						var orderid = Zepto('#orderid').val(); 
						window.location.href = 'authentication_completepayment.php?authenticationorderid=' + orderid;
					}
					else if (res.err_msg == "get_brand_wcpay_request:fail")
					{
						mui.toast("支付失败！");
						setTimeout("back_confirmpayment();", 1500);
					}
					else
					{
						mui.toast(res.err_msg);
						setTimeout("back_confirmpayment();", 1500);
					}
				}
		);
	}

	function callpay(jsondata)
	{
		if (typeof WeixinJSBridge == "undefined")
		{
			if (document.addEventListener)
			{
				document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			}
			else if (document.attachEvent)
			{
				document.attachEvent('WeixinJSBridgeReady', jsApiCall);
				document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			}
		}
		else
		{
			jsApiCall(jsondata);
		}
	}
	{/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>