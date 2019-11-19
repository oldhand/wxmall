<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>收款银行管理</title>
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

		.bankcard-cell {
			position: relative;
			overflow: hidden;
			padding: 11px 15px;
			background-color: inherit;
			-webkit-touch-callout: none;
		}

		.bankcard-edit-cell {
			padding-top: 20px;
			padding-left: 12px;
		}

		.bankcard-edit-cell span {
			font-size: 2.2em;
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
			<a class="mui-icon mui-icon-back mui-pull-left" href="{$return}.php"></a>
			<h1 class="mui-title">收款银行管理</h1>
		</header>
		{/if} 
		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;"> 
			<a class="mui-tab-item add" href="bank_edit.php">
				<span class="mui-icon iconfont icon-zengjia">&nbsp;新增</span>
			</a>
			<a class="mui-tab-item return" href="takecashs.php">
				<span class="mui-icon iconfont icon-queren01">&nbsp;返回</span>
			</a>
		</nav>
		 

		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
			<div class="mui-scroll">
				<div class="mui-card" style="margin: 0 3px;">
					<form class="mui-input-group">
						<input type="hidden" id="orderid" name="orderid" value="{$orderid}"/>
						<ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
							{foreach name="banks" key=bankid item=bank_info  from=$banks}
								<li class="bankcard-cell mui-input-row mui-radio mui-left" style="height:80px;" id="shoppingcart_wrap_{$bankcardid}">
									{if $bank_info.authenticationstatus neq '2'}
									<a href="bank_edit.php?record={$bankid}" class="bankcard-edit-cell mui-pull-right" style="width:10%">
										<span class="mui-icon iconfont icon-bianji"></span>
									</a>
									{/if }
									<div class="mui-pull-left" style="width:90%">
										<label> 
											<div class="mui-media-body">
												<p class='mui-ellipsis' style="color:#333">
												<div class="mui-media-body  mui-pull-left">
													收款人：{$bank_info.realname}<br>
													开户行：{$bank_info.bank}<br>
													银行账号：{$bank_info.account}<br> 
													{if $bank_info.authenticationstatus eq '0'}
													认证状态：刚刚提交&nbsp;(<span style="color:red">请等待管理员认证审核！</span>)
													{elseif $bank_info.authenticationstatus eq '1'}
													认证状态：拒绝认证&nbsp;(<span style="color:red">请检查账号信息！</span>)
													{elseif $bank_info.authenticationstatus eq '2'}
													认证状态：接受认证
													{/if}
												</div> 
												</p>
											</div>  
										</label>
										<input {if $bank_info.authenticationstatus neq '2'}disabled{/if } {if $bank_info.selected eq '1'}checked{/if} value="{$bankid}" id="bankcard_{$bankid}" name="bankcard" type="radio" style="margin-top:30px;">
									</div>
								</li>
								{foreachelse}
								<div class="mui-content-padded">
									<p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>
									<p class="msgbody">您的还没有银行卡记录，快点新建您的银行卡吧!<br>
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

				  mui('.mui-table-view').on('tap', 'a.bankcard-edit-cell', function (e)
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
		  		  
				  mui('.mui-card').on('change', 'input', function() { 
					  var bankcardid = "";  
					  Zepto('input[name=bankcard]').each(function (index)
								   { 
									   if (this.checked)
									   {
										   bankcardid = Zepto(this).val();
									   }
								   });
					  mui.openWindow({
										 url: 'bank.php?record=' + bankcardid + '&type=selected',
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