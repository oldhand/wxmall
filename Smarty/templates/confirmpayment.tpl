<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>确认支付</title>
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
			width: 60px;
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
			<a id="mui-action-back" class="mui-icon {if $returnbackatcion eq ""}mui-action-back{/if} mui-icon-back mui-pull-left" {if $returnbackatcion neq ""}href="{$returnbackatcion}"{/if}></a>
			<h1 class="mui-title" id="payment_title">确认支付</h1>
		</header>
		{/if} 
		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
			<a id="confirmpayment" class="mui-tab-item mui-active confirmpayment" href="#" style="width:30%">
				<span class="mui-icon iconfont icon-qian" id="payment_icon" style="top:0px;"></span><span id="payment_button">确定支付</span>
			</a>
		</nav>

		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
			<div class="mui-scroll">
				<input id="orderid" name="orderid" value="{$orderid}" type="hidden">
				<input id="type" name="type" value="" type="hidden">
				<input id="totalprice" name="totalprice" value="{$total_money}" type="hidden">
				<input id="money" name="money" value="{$availablenumber}" type="hidden">
				<input id="allmoney" name="allmoney" value="{$profile_info.money}" type="hidden">
				<input id="moneypaymentrate" name="moneypaymentrate" value="{$moneypaymentrate}" type="hidden">
				<input id="needpayable" name="needpayable" value="{$total_money}" type="hidden">
				<input id="vipcardusageid" name="vipcardusageid" value="{$vipcard_usage_info.id}" type="hidden">
				<input id="vipcardusageamount" name="vipcardusageamount" value="{$vipcard_usage_info.amount}" type="hidden">
				<div class="mui-card" style="margin: 0 3px 5px 3px;">
					<ul class="mui-table-view">
						<li class="mui-table-view-cell">
							<div class="mui-media-body  mui-pull-left">
								<span class="mui-table-view-label">收货人：</span>{$deliveraddress.consignee}
							</div>
							<div class="mui-media-body mui-pull-right">
								{$deliveraddress.mobile}
							</div>
						</li>
						<li class="mui-table-view-cell">
							<div class="mui-media-body  mui-pull-left">
								<span class="mui-table-view-label">收货地址：</span>{$deliveraddress.province}{$deliveraddress.city}{$deliveraddress.district}
								<br>
								<span class="mui-table-view-label">&nbsp;</span>{$deliveraddress.shortaddress}
							</div>
						</li>
						{if $isshare eq '1'}
								{if $supplier_info.vipauthentication eq '1' && $rankname neq ''}  
								<li class="mui-table-view-cell">
									<div class="mui-media-body" style="color:#cc3300;text-align:center"> 分享人{$source}已经认证{$rankname}会员,您可享受折扣优惠! 
									</div>
								</li> 
								{/if}
								<li class="mui-table-view-cell">
									<div class="mui-media-body  mui-pull-left"> 
										<span class="mui-table-view-label">总金额： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$show_total_money}</span></b></span>
									</div>
								</li>  
								{if $supplier_info.vipauthentication eq '1' && $vipdeductionmoney neq ''}  
								<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left"> 
											<span class="mui-table-view-label">折扣优惠： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$vipdeductionmoney}</span></b></span>
										</div>
								</li> 
								{/if}  
								<div id="paymentgroup"> 
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">还需支付：</span>
											<span class="totalprice">￥<b>
													<span style="font-size: 16px;" id="needpayment">{$total_money}</span></b>
											</span>
										</div>
									</li>
									<li class="mui-table-view-cell" id="paymentwaygroup">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">支付方式：</span>
										</div>
										<div class="yanse_xz mui-pull-left" id="radiogroup">
											<a class="btn btn-default radiogroup" groupid="payment" paymentway="weixin" href="javascript:;">
												<span class="mui-icon iconfont icon-weixin" style="font-size: 3.5em;"></span>
												<div style="display:none">
													<input class="paymentway" id="weixin" type="radio" name="paymentway" value="weixin"/>
												</div>
											</a>  
										</div>
									</li>
								</div>
						{else}
							{if $supplier_info.vipauthentication eq '1' && $rankname neq ''}  
								<li class="mui-table-view-cell">
									<div class="mui-media-body" style="color:#cc3300;text-align:center"> 您已经认证{$rankname},可享受<!--{$rankdiscount}-->折扣优惠! 
									</div>
								</li>  
							{/if}
							{if $userank eq '1'}
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left"> 
											<span class="mui-table-view-label">总金额： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$show_total_money}</span></b></span>
										</div>
									</li> 
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left"> 
											<span class="mui-table-view-label">积分抵扣： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$rankmoney}</span></b></span>
										</div>
									</li>
									{if $supplier_info.vipauthentication eq '1' && $vipdeductionmoney neq ''}  
										<li class="mui-table-view-cell">
											<div class="mui-media-body  mui-pull-left"> 
												<span class="mui-table-view-label">折扣优惠： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$vipdeductionmoney}</span></b></span>
											</div>
										</li>  
									{/if}
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left"> 
											<span class="mui-table-view-label">支付金额： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$total_money}</span></b></span>
										</div>
									</li>
							{else}
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left"> 
											<span class="mui-table-view-label">总金额： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$show_total_money}</span></b></span>
										</div>
									</li>
								    {if $supplier_info.vipauthentication eq '1' && $vipdeductionmoney neq ''}  
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left"> 
											<span class="mui-table-view-label">折扣优惠： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$vipdeductionmoney}</span></b></span>
										</div>
									</li> 
									{/if} 
							{/if} 
							{if $rankmoney eq $show_total_money} 
								<li class="mui-table-view-cell">
									<div class="mui-media-body" style="color:#cc3300;text-align:center">积分可以抵扣全部支付金额,确认抵扣吗?
										<input style="display:none;" type="radio" name="paymentway" checked="true" value="rankpayment"/>
									</div>
								</li>
							{else}
								<div id="paymentgroup">
									{if $profile_info.money gt 0}
										<li class="mui-table-view-cell">
											<div class="mui-media-body  mui-pull-left">
												<span class="mui-table-view-label">您的余额： </span><span class="totalprice">￥<b><span style="font-size: 16px;">{$profile_info.money}</span></b></span>
											</div>
										</li>
										{if $frozenstatus eq 'Frozen'}
											<li class="mui-table-view-cell" style="text-align:center;">
												  <span style="color:#CF2D28; font-size:1.1em; font-weight:500;">账号异常冻结，禁止使用余额！请联系客服! </span>
											</li>
										{/if}
										<li class="mui-table-view-cell">
											<div class="mui-media-body  mui-pull-left">
												<div class="mui-checkbox mui-left mui-pull-left" style="width:90px;height:26px;line-height:26px;">
													<label>使用余额</label>
													<input {if $frozenstatus eq 'Frozen'}disabled{/if} id="usemoney" name="usemoney" value="1" type="checkbox">
												</div>
												<div class="mui-pull-left" style="height:26px;line-height:26px;">
													【本单余额可用为<span class="totalprice" id="allowmoney">￥{$availablenumber}</span>】
												</div>
											</div>
		
										</li>
									{/if}
									{if $vipcardusagelist|@count gt 0}
										<li class="mui-table-view-cell">
											<a href="#vipcards" class="mui-navigate-right vipcards">
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">卡券优惠：</span>
													<span id="vipcard_msg">{$vipcard_usage_info.vipcardname}
														{if $vipcard_usage_info.orderamount eq '0'}
															【下单可用,{if $vipcard_usage_info.timelimit eq '0'}限次{else}不限次{/if}】
														{else}
															【满{$vipcard_usage_info.orderamount}元可用,{if $vipcard_usage_info.timelimit eq '0'}限次{else}不限次{/if}】
														{/if}</span>
												</div>
											</a>
										</li>
										<li class="mui-table-view-cell">
											<div class="mui-media-body  mui-pull-left">
												<span class="mui-table-view-label">优惠金额：</span>
												<span class="totalprice">￥<b>
														<span style="font-size: 16px;" id="discount">{$vipcard_usage_info.amount}</span></b>
												</span>
											</div>
										</li>
									{else}
										<li class="mui-table-view-cell">
											<div class="mui-media-body  mui-pull-left">
												<span class="mui-table-view-label">卡券优惠：</span>您没有可用的卡券
											</div>
										</li>
									{/if}
		
									<li class="mui-table-view-cell">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">还需支付：</span>
											<span class="totalprice">￥<b>
													<span style="font-size: 16px;" id="needpayment">{if $vipcard_usage_info|@count gt 0}{$vipcard_usage_info.total_money}{else}{$total_money}{/if}</span></b>
											</span>
										</div>
									</li>
									<li class="mui-table-view-cell" id="paymentwaygroup">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">支付方式：</span>
										</div>
										<div class="yanse_xz mui-pull-left" id="radiogroup">
											<a class="btn btn-default radiogroup" groupid="payment" paymentway="weixin" href="javascript:;">
												<span class="mui-icon iconfont icon-weixin" style="font-size: 3.5em;"></span>
												<div style="display:none">
													<input class="paymentway" id="weixin" type="radio" name="paymentway" value="weixin"/>
												</div>
											</a> 
											<!--<a class="btn btn-default radiogroup" groupid="payment" paymentway="alipay" href="javascript:;" >
												<span class="mui-icon iconfont icon-alipaylogo" style="font-size: 3.5em;"></span>
												<div style="display:none">
													<input class="paymentway" id="alipay" type="radio" name="paymentway" value="alipay" />
												</div>
											</a>
											<a class="btn btn-default radiogroup" groupid="payment" paymentway="uppay" href="javascript:;" >
												<span class="mui-icon iconfont icon-unionpaylogo" style="font-size: 3.5em;"></span>
												<div style="display:none">
													<input class="paymentway" id="uppay" type="radio" name="paymentway" value="uppay" />
												</div>
											</a>-->
										</div>
									</li>
								</div>
							{/if}  
						{/if}
						
					

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
<div id="vipcards" class="mui-popover mui-popover-action mui-popover-bottom">
	<ul class="mui-table-view">
		{foreach name="vipcardusages" item=vipcardusage_info  from=$vipcardusagelist}
			<li class="mui-table-view-cell">
				<a href="#" data-id="{$vipcardusage_info.id}" data-amount="{$vipcardusage_info.amount}">{$vipcardusage_info.vipcardname}
					{if $vipcardusage_info.orderamount eq '0'}
						【下单可用,{if $vipcardusage_info.timelimit eq '0'}限次{else}不限次{/if}】
					{else}
						【满{$vipcardusage_info.orderamount}元可用,{if $vipcardusage_info.timelimit eq '0'}限次{else}不限次{/if}】
					{/if}
				</a>
			</li>
		{/foreach}
	</ul>
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<a href="#vipcards" data-id="" data-amount="" style="font-weight:900;">本次不使用卡券</a>
		</li>
	</ul>
</div>

<script type="text/javascript">
	var returnbackatcion = "{$returnbackatcion}";
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

				  mui('#paymentmodegroup').on('change', 'input', function ()
				  {
					  if (Zepto('#type').val() == 'submit') return;
					  var paymentway = Zepto(this).val();
					  if (paymentway == "1")
					  {
						  Zepto("#paymentgroup").css("display", "");
						  Zepto("#payment_button").html('确定支付');
						  Zepto("#payment_title").html('确认支付');
						  Zepto("#payment_icon").removeClass("icon-queren01");
						  Zepto("#payment_icon").addClass("icon-feiyongshuomingicon");
					  }
					  else
					  {
						  Zepto("#paymentgroup").css("display", "none");
						  Zepto("#payment_button").html('确定下单');
						  Zepto("#payment_title").html('确认下单');
						  Zepto("#payment_icon").removeClass("icon-feiyongshuomingicon");
						  Zepto("#payment_icon").addClass("icon-queren01");
					  }
				  });
				  mui('.mui-bar').on('tap', 'a.confirmpayment', function (e)
				  {
					  if (Zepto('#type').val() == 'submit') return;
					  confirmpayment();
				  });
				  mui('.mui-table-view-cell').on('change', 'input#usemoney', function (e)
				  {
					  onusemoneychange();
				  });

				  mui('#vipcards').on('tap', 'a', function ()
				  {
					  var a = this, parent;
					  //根据点击按钮，反推当前是哪个actionsheet
					  for (parent = a.parentNode; parent != document.body; parent = parent.parentNode)
					  {
						  if (parent.classList.contains('mui-popover-action'))
						  {
							  break;
						  }
					  }
					  //关闭actionsheet
					  mui('#' + parent.id).popover('toggle');

					  var usageid = Zepto(this).attr("data-id");
					  var amount  = Zepto(this).attr("data-amount");
					  Zepto('#vipcardusageid').val(usageid);
					  Zepto('#vipcardusageamount').val(amount);
					  Zepto('#vipcard_msg').html(a.innerHTML);
					  onusemoneychange();
				  })
			  });

	function onusemoneychange()
	{
		if(Zepto('#usemoney').is(":checked"))
//		if (Zepto('#usemoney').prop('checked'))
		{
			var totalprice         = Zepto("#totalprice").val();
			var money              = Zepto("#money").val();
			var vipcardusageamount = Zepto("#vipcardusageamount").val();
			var moneypaymentrate   = Zepto("#moneypaymentrate").val();
			var allmoney           = Zepto("#allmoney").val();

			var newtotalprice       = parseFloat(totalprice, 10);
			var newmoney            = parseFloat(money, 10);
			var newallmoney         = parseFloat(allmoney, 10);
			var newmoneypaymentrate = parseFloat(moneypaymentrate, 10);

			var newvipcardusageamount;
			if (vipcardusageamount == "")
			{
				newvipcardusageamount = 0;
			}
			else
			{
				newvipcardusageamount = parseFloat(vipcardusageamount, 10);
			}
			Zepto("#discount").html(newvipcardusageamount.toFixed(2));
			if (newmoneypaymentrate == 100)
			{
				if ((newmoney + newvipcardusageamount) >= newtotalprice)
				{
					Zepto("#needpayment").html('0.00');
					Zepto("#needpayable").val('0');
					Zepto("#paymentwaygroup").css("display", "none");
				}
				else
				{
					var needpayment = newtotalprice - newmoney - newvipcardusageamount;
					Zepto("#needpayable").val(needpayment);
					Zepto("#needpayment").html(needpayment.toFixed(2));
					Zepto("#paymentwaygroup").css("display", "");
				}
			}
			else
			{
				var needpayment = newtotalprice - newvipcardusageamount;
				var allowmoney  = needpayment - newtotalprice * (100 - newmoneypaymentrate) / 100;
				if (newallmoney > allowmoney)
				{
					var remain = needpayment - allowmoney;
					Zepto("#allowmoney").html('￥' + allowmoney.toFixed(2));
					Zepto("#money").val('￥' + allowmoney.toFixed(2));
					Zepto("#needpayable").val(remain);
					Zepto("#needpayment").html(remain.toFixed(2));
				}
				else
				{
					var remain = needpayment - newallmoney;
					Zepto("#allowmoney").html('￥' + newallmoney.toFixed(2));
					Zepto("#money").val('￥' + newallmoney.toFixed(2));
					Zepto("#needpayable").val(remain);
					Zepto("#needpayment").html(remain.toFixed(2));
				}
				Zepto("#paymentwaygroup").css("display", "");
			}

		}
		else
		{
			var totalprice         = Zepto("#totalprice").val();
			var newtotalprice      = parseFloat(totalprice, 10);
			var vipcardusageamount = Zepto("#vipcardusageamount").val();
			var newvipcardusageamount;
			if (vipcardusageamount == "")
			{
				newvipcardusageamount = 0;
			}
			else
			{
				newvipcardusageamount = parseFloat(vipcardusageamount, 10);
			}

			Zepto("#discount").html(newvipcardusageamount.toFixed(2));
			var needpayment = newtotalprice - newvipcardusageamount;
			Zepto("#needpayable").val(needpayment);
			Zepto("#needpayment").html(needpayment.toFixed(2));
			Zepto("#paymentwaygroup").css("display", "");
		}
	}

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

		var needpayable = Zepto('#needpayable').val();

		if (paymentway == "" && parseFloat(needpayable, 10) > 0)
		{
			mui.toast("请选择支付方式！");
			return;
		}

		var vipcardusageid     = Zepto('#vipcardusageid').val();
		var vipcardusageamount = Zepto('#vipcardusageamount').val();

		Zepto('input[name=paymentway]').prop("disabled", 'disabled');
		Zepto('#type').val('submit');

		var orderid  = Zepto('#orderid').val();
		var usemoney = '0';
		if(Zepto('#usemoney').is(":checked")) 
		{
			usemoney = '1';
		}
		Zepto('#usemoney').prop("disabled", 'disabled');

		Zepto("#payment_button").html('正在提交,请等待!');
		Zepto("#payment_icon").removeClass("icon-qian");
		Zepto("#payment_icon").addClass("icon-loading");
		Zepto("#payment_icon").addClass("mui-rotation");
		Zepto("#confirmpayment").removeClass("confirmpayment");
		if(returnbackatcion != ""){
			Zepto("#mui-action-back").removeAttr("href");
		}else
		{
			Zepto("#mui-action-back").removeClass("mui-action-back");
		}
		mui.ajax({
					 type: 'POST',
					 url: "saveorder.php",
					 data: 'orderid=' + orderid + '&paymentway=' + paymentway + '&usemoney=' + usemoney + '&needpayable=' + needpayable + '&vipcardusageid=' + vipcardusageid + '&vipcardusageamount=' + vipcardusageamount,
					 success: function (json)
					 {
						// alert(json);
						 var jsondata = eval("(" + json + ")");
						 if (jsondata.code == 200)
						 { 
							 if (jsondata.paymentway == 'weixin')
							 {
								 callpay(jsondata.json);
							 }
							 else if (jsondata.paymentway == 'tzb')
							 {
								 var orderid          = Zepto('#orderid').val();
								 window.location.href = 'completepayment.php?record=' + orderid;
							 }
							 else if (jsondata.paymentway == 'official')
							 {
								 var orderid          = Zepto('#orderid').val();
								 window.location.href = 'completepayment.php?record=' + orderid;
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
		if(returnbackatcion != ""){
			Zepto("#mui-action-back").attr("href",returnbackatcion);
		}else
		{
			Zepto("#mui-action-back").addClass("mui-action-back");
		}
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
						var orderid          = Zepto('#orderid').val();
						window.location.href = 'completepayment.php?record=' + orderid;
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