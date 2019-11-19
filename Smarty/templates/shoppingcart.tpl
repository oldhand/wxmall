<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>{if $islogined neq 'true' || $supplier_info.showheader eq '1'}购物车{/if}</title>
	<link href="public/css/mui.css" rel="stylesheet"/>
	<link href="public/css/public.css" rel="stylesheet"/>
	<link href="public/css/iconfont.css" rel="stylesheet"/>
	<link href="public/css/sweetalert.css" rel="stylesheet"/>
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/sweetalert.min.js"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
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

		.mui-checkbox.mui-left label {
			padding-right: 3px;
			padding-left: 35px;
		}

		.mui-checkbox.mui-left input[type='checkbox'] {
			left: 3px;
		}

		.mui-table-view .mui-media-object {
			line-height: 100px;
			max-width: 100px;
			height: 100px;
		}

		.mui-input-row {
			margin: 2px;
		}

		.mui-input-group .mui-input-row:after {
			left: 0px;
		}

		.mui-input-row .mui-numbox {
			float: left;
			margin: 2px 2px;
		}

		.mui-numbox {
			width: 120px;
			height: 30px;
			padding: 0 40px 0 40px;
		}

		.mui-card .mui-ellipsis {
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

		.mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
			color: #cc3300;
		}

		.mui-ellipsis {
			line-height: 17px;
		}

		.price {
			color: #fe4401;
		}

		.deleteshoppingcart {
			color: #cc3300;
			font-size: 1.1em;
		}

		.deleteshoppingcart span {
			font-size: 1.1em;
		}

		{/literal}
		
	</style>
	{include file='theme.tpl'}
	 
</head>

<body>
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
	{include file='leftmenu.tpl'}
	<div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
		<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
			<a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
			<h1 class="mui-title">购物车</h1>
		</header>
		{/if}
		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
			<div class="mui-tab-item" style="width:70%;color:#929292;">
				<input value="{$supplier_info.totalpricefreeshipping}" id="totalpricefreeshipping" type="hidden"/>
				<input value="{$supplier_info.totalquantityfreeshipping}" id="totalquantityfreeshipping" type="hidden"/>
				<span class="mui-tab-label">
					<div id="allselect_div" class="mui-input-row mui-checkbox mui-left mui-pull-left" style="width:60px">
						<label>全选</label>
						<input id="allselect" name="allselect" {if $shoppingcarts|@count > 0} checked{/if} value="1" type="checkbox">
					</div>
					<div class="mui-pull-right" style="line-height: 20px;text-align: left;">
						合计：<span class="price" id="total_money">¥{$total_money}</span>元<br>
						共计&nbsp;<span class="price" id="total_quantity">{$total_quantity}</span>&nbsp;件商品
					</div>
				</span>
			</div>
			<a class="mui-tab-item confirmpayment" href="#" style="width:30%;">
				<span class="mui-icon iconfont icon-feiyongshuomingicon">&nbsp;结算</span>
			</a>
		</nav>

		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if};bottom: 50px;">
			<div class="mui-scroll">
				<div class="mui-card" style="margin: 0 3px 3px;">
					<form class="mui-input-group" method="post" name="frm" action="submitorder.php">
						<input name="token" value="{$token}" type="hidden">
						<ul id="shoppingcart_wrap" class="mui-table-view mui-table-view-chevron" style="color: #333;">
							{foreach name="shoppingcarts" item=shoppingcart_info  from=$shoppingcarts}
								<li class="mui-input-row mui-checkbox mui-left" style="min-height:100px;height: auto;"
									id="shoppingcart_wrap_{$shoppingcart_info.id}">
									<label>
										<img class="mui-media-object mui-pull-left" data-id="{$shoppingcart_info.id}" src="{$shoppingcart_info.productthumbnail}">
										<div class="mui-media-body">
											<p class='mui-ellipsis' style="color:#333">{$shoppingcart_info.productname}</p>
											{if $shoppingcart_info.vendorname neq '' && $supplier_info.showvendor eq '1'} 
											<p class='mui-ellipsis'>供应商：{$shoppingcart_info.vendorname}</p>
											{/if} 
											{if $shoppingcart_info.propertydesc neq ''}
												<p class='mui-ellipsis'>属性：{$shoppingcart_info.propertydesc}</p>
											{/if}
											<div class='mui-ellipsis' style="width:200px">
												<div class="mui-numbox" data-numbox-step='1' data-numbox-min='1' data-numbox-max='{if $shoppingcart_info.activitymode eq "1"}1{else}{if $shoppingcart_info.uniquesale eq '1'}1{else}99{/if}{/if}'>
													<button class="mui-btn mui-numbox-btn-minus" type="button" data-id="{$shoppingcart_info.id}">-</button>
													<input value="{$shoppingcart_info.productid}" id="productid_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.shop_price}" id="shop_price_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.old_shop_price}" id="old_shop_price_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.postage}" id="postage_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.includepost}" id="includepost_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.mergepostage}" id="mergepostage_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.zhekou}" id="zhekou_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.bargains_count}" id="bargains_count_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.activitymode}" id="activitymode_{$shoppingcart_info.id}" type="hidden"/>
													<input value="{$shoppingcart_info.bargainrequirednumber}" id="bargainrequirednumber_{$shoppingcart_info.id}" type="hidden"/>
													<input readonly data-id="{$shoppingcart_info.id}" value="{$shoppingcart_info.quantity}"
														   id="qty_item_{$shoppingcart_info.id}" name="qty_item_{$shoppingcart_info.id}"
														   class="mui-numbox-input" type="number"/>
													<button class="mui-btn mui-numbox-btn-plus" type="button" data-id="{$shoppingcart_info.id}">+</button>
												</div>

											</div>
											<p class='mui-ellipsis'>{if $shoppingcart_info.zhekou neq ''}{if $shoppingcart_info.activitymode eq '1'}底价{else}活动价{/if}：
													<span class="price">¥{$shoppingcart_info.shop_price}</span>
													<span style="color:#878787;margin-left:5px;text-decoration:line-through;">
													¥{$shoppingcart_info.old_shop_price}</span>{else}单价：<span class="price">
													¥{$shoppingcart_info.shop_price}</span>{/if}</p>
											{if $shoppingcart_info.activitymode eq '1'}
												<p class='mui-ellipsis'>
													砍价：{if $shoppingcart_info.bargains_count eq 0}还没有好友帮忙砍价{else}已有 {$shoppingcart_info.bargains_count} 位好友帮忙砍价{/if}
												</p>
											{/if}
											<p id="postage_panel_{$shoppingcart_info.id}" class='mui-ellipsis' style="display: {if $shoppingcart_info.postage|@floatval gt 0 && ($shoppingcart_info.includepost|@intval eq 0 || $shoppingcart_info.includepost|@intval gt $shoppingcart_info.productallcount|@intval) && ($supplier_info.totalpricefreeshipping|@floatval eq 0 || $supplier_info.totalpricefreeshipping|@floatval gt $total_money) && ($supplier_info.totalquantityfreeshipping|@intval eq 0 || $supplier_info.totalquantityfreeshipping|@intval gt $total_quantity)}block{else}none{/if};">
												邮费：
												<span id="postage_span_{$shoppingcart_info.id}" class="price">
													¥{if $shoppingcart_info.mergepostage|@intval eq 1}{$shoppingcart_info.postage}{else}{$shoppingcart_info.postage*$shoppingcart_info.quantity|string_format:"%0.2f"}{/if}
												</span>
												{*<span>
													{if $shoppingcart_info.mergepostage|@intval eq 1}合{else}分{/if}
												</span>*}
												{if $shoppingcart_info.includepost|@intval gt 0}
													<span style="color:#878787;margin-left:10px;">({$shoppingcart_info.includepost}
																								  件包邮)</span>
												{/if}
											</p>
											<p class='mui-ellipsis'>小计：<span id="total_price_{$shoppingcart_info.id}"
																			 class="price">¥{$shoppingcart_info.total_price}</span>
												<a class="deleteshoppingcart mui-pull-right button-color" data-id="{$shoppingcart_info.id}"
												   href="javascript:;"><span class="mui-icon iconfont icon-shanchu"></span>删除</a>
											</p>
										</div>
									</label>
									<input name="shoppingcart[]" value="{$shoppingcart_info.id}" id="shoppingcart_{$shoppingcart_info.id}"
										   checked type="checkbox" style="margin-top:45px;">
								</li>
								{foreachelse}
								<div class="mui-content-padded">
									<p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>
									<p class="msgbody">您的购物车还是空的，快去选购商品吧<br>
										<a href="index.php">>>>&nbsp;去逛逛</a>
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
	var idlists = {$idlists};
	var errmsg = "{$errorMsg}";
	{literal}
	mui.init({
				 pullRefresh: {
					 container: '#pullrefresh'
				 },
			 });

	mui.ready(function ()
			  {
				  mui('#pullrefresh').scroll();

				  if (errmsg != "")
				  {
					  swal("", errmsg.replace(/<br>/g, "\n"), "warning");
				  }

				  mui('.msgbody').on('tap', 'a', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });

				  mui('.mui-bar').on('tap', 'div#allselect_div', function (e)
				  {
					  setTimeout("allselect();", 50);
				  });
				  mui('.mui-bar').on('tap', 'a.confirmpayment', function (e)
				  {
					  var total_quantity = Zepto("#total_quantity").html();
					  if (parseInt(total_quantity, 10) > 0)
					  {
						  document.frm.submit();
					  }
					  else
					  {
						  mui.toast("购物车没有需要结算的商品！");
					  }
				  });

				  mui('.mui-table-view').on('tap', 'input[type=checkbox]', function (e)
				  {
					  setTimeout("recalc();", 50);
				  });

				  mui('.mui-table-view').on('tap', 'img.mui-media-object', function (e)
				  {
					  var shoppingcartid = this.getAttribute('data-id');
					  Zepto("#shoppingcart_" + shoppingcartid).attr("checked", !Zepto("#shoppingcart_" + shoppingcartid).attr("checked"));
					  Zepto("#shoppingcart_" + shoppingcartid).prop("checked", !Zepto("#shoppingcart_" + shoppingcartid).prop("checked"));
					  setTimeout("recalc();", 50);
				  });

				  mui('.mui-table-view').on('change', 'input[type=number]', function ()
				  {
					  setTimeout("recalc();", 50);
					  var shoppingcartid = this.getAttribute('data-id');
					  var qty_item       = Zepto("#qty_item_" + shoppingcartid).val();
					  productqty_change(shoppingcartid);
					  mui.ajax({
								   type: 'POST',
								   url: "shoppingcart_update.ajax.php",
								   data: 'record=' + shoppingcartid + '&qty_item=' + qty_item,
								   success: function (json)
								   {
								   }
							   });
				  });

				  mui('.mui-table-view').on('tap', 'a.deleteshoppingcart', function (e)
				  {
					  var shoppingcartid = this.getAttribute('data-id');
					  swal({
							   title: "提示",
							   text: "您确定需要删除商品吗？",
							   type: "warning",
							   showCancelButton: true,
							   closeOnConfirm: true,
							   confirmButtonText: "删除",
							   confirmButtonColor: "#ec6c62"
						   }, function ()
						   {
							   Zepto("#shoppingcart_wrap_" + shoppingcartid).remove();
							   mui.ajax({
											type: 'POST',
											url: "shoppingcart_delete.ajax.php",
											data: 'record=' + shoppingcartid,
											success: function (json)
											{
												Zepto.each(idlists, function (i, v)
												{
													if (v === shoppingcartid)
													{
														idlists.splice(i, 1);
													}
												});
												recalc();
												if (Zepto("#shoppingcart_wrap").children().size() <= 0)
												{
													Zepto("#shoppingcart_wrap").html('<div class="mui-content-padded">' +
																					 '<p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>' +
																					 '<p class="msgbody">您的购物车还是空的，快去选购商品吧<br>' +
																					 '<a href="index.php">>>>&nbsp;去逛逛</a></p></div>');
												}
											}
										});
						   });
				  });
			  });
	function productqty_change(shoppingcartid){
		var qty_item       = Zepto("#qty_item_" + shoppingcartid).val();
		var pice           = Zepto("#shop_price_" + shoppingcartid).val();
		var includepost    = Zepto("#includepost_" + shoppingcartid).val();
		var postage        = Zepto("#postage_" + shoppingcartid).val();
		var mergepostage   = Zepto("#mergepostage_" + shoppingcartid).val();
		var old_pice       = Zepto("#old_shop_price_" + shoppingcartid).val();
		var zhekou         = Zepto("#zhekou_" + shoppingcartid).val();
		var bargains       = Zepto("#bargains_count_" + shoppingcartid).val();
		var activitymode   = Zepto("#activitymode_" + shoppingcartid).val();
		var bargainsnumber = Zepto("#bargainrequirednumber_" + shoppingcartid).val();

		var total_price    = parseFloat(pice, 10) * parseInt(qty_item, 10);
		if(zhekou != "" && parseInt(activitymode,10) == '1'){
			total_price = parseFloat(old_pice,10) - parseFloat(old_pice,10) * (10 - parseFloat(zhekou,10)) / 10 / parseInt(bargainsnumber,10) * parseInt(bargains,10);
		}
		var totalpricefreeshipping = Zepto("#totalpricefreeshipping").val();
		var totalquantityfreeshipping = Zepto("#totalquantityfreeshipping").val();
		var productallmoney = productallselectmoney();
		var productallcount = productallselectcount();
		if((parseFloat(totalpricefreeshipping,10) <= 0 || parseFloat(totalpricefreeshipping,10) > productallmoney) && (parseInt(totalquantityfreeshipping,10) <= 0 || parseInt(totalquantityfreeshipping,10) > productallcount))
		{
			if (parseFloat(postage, 10) > 0)
			{
				if (parseInt(mergepostage, 10) != 1)
				{
					postage = parseFloat(postage, 10) * parseInt(qty_item, 10);
				}
				Zepto("#postage_span_" + shoppingcartid).html('¥' + parseFloat(postage, 10).toFixed(2));
				var pct = productcount(Zepto("#productid_" + shoppingcartid).val());
				if (parseInt(includepost, 10) > 0 && parseInt(includepost, 10) <= pct)
				{
					Zepto("#postage_panel_" + shoppingcartid).css('display', 'none;');
				}
				else
				{
					Zepto("#postage_panel_" + shoppingcartid).css('display', 'block;');
					total_price = parseFloat(total_price, 10) + parseFloat(postage, 10);
				}
			}
		}else{
			Zepto("#postage_panel_" + shoppingcartid).css('display', 'none;');
		}
		Zepto("#total_price_" + shoppingcartid).html('¥' + parseFloat(total_price, 10).toFixed(2));
	}
	function productcount(pid){
		var pc = 0;
		Zepto.each(idlists, function (i, v){
			var checked = Zepto("#shoppingcart_" + v).prop("checked");
			if (checked){
				var product         = Zepto("#productid_" + v).val();
				if(product == pid){
					var qty_item     = Zepto("#qty_item_" + v).val();
					pc += parseInt(qty_item, 10);
				}
			}
		});
		return pc;
	}
	function productallselectcount(){
		var pc = 0;
		Zepto.each(idlists, function (i, v){
			var checked = Zepto("#shoppingcart_" + v).prop("checked");
			if (checked){
				var qty_item = Zepto("#qty_item_" + v).val();
				pc += parseInt(qty_item, 10);
			}
		});
		return pc;
	}
	function productallselectmoney(){
		var pc = 0;
		Zepto.each(idlists, function (i, v){
			var checked = Zepto("#shoppingcart_" + v).prop("checked");
			if (checked){
				var qty_item = Zepto("#qty_item_" + v).val();
				var pice     = Zepto("#shop_price_" + v).val();
				pc += parseInt(qty_item, 10) * parseFloat(pice,10);
			}
		});
		return pc;
	}
	function recalc()
	{
		var total_money    = 0;
		var total_quantity = 0;
		var allpostage     = 0;
		var allmergepost   = 0;
		var isall          = true;
		var totalpricefreeshipping = Zepto("#totalpricefreeshipping").val();
		var totalquantityfreeshipping = Zepto("#totalquantityfreeshipping").val();
		var productallmoney = productallselectmoney();
		var productallcount = productallselectcount();
		Zepto.each(idlists, function (i, v)
		{
			productqty_change(v);
			var checked = Zepto("#shoppingcart_" + v).prop("checked");
			if (checked)
			{
				var qty_item     = Zepto("#qty_item_" + v).val();
				var pice         = Zepto("#shop_price_" + v).val();
				var includepost  = Zepto("#includepost_" + v).val();
				var postage      = Zepto("#postage_" + v).val();
				var mergepostage = Zepto("#mergepostage_" + v).val();
				var old_pice	 = Zepto("#old_shop_price_" + v).val();
				var zhekou 		 = Zepto("#zhekou_" + v).val();
				var bargains	 = Zepto("#bargains_count_" + v).val();
				var activitymode = Zepto("#activitymode_" + v).val();
				var bargainsnumber = Zepto("#bargainrequirednumber_" + v).val();

				var total_price  = parseFloat(pice, 10) * parseInt(qty_item, 10);
				if(zhekou != "" && parseInt(activitymode,10) == '1'){
					total_price = parseFloat(old_pice,10) - parseFloat(old_pice,10) * (10 - parseFloat(zhekou,10)) / 10 / parseInt(bargainsnumber,10) * parseInt(bargains,10);
				}
				if((parseFloat(totalpricefreeshipping,10) <= 0 || parseFloat(totalpricefreeshipping,10) > productallmoney) && (parseInt(totalquantityfreeshipping,10) <= 0 || parseInt(totalquantityfreeshipping,10) > productallcount))
				{
					if (parseFloat(postage, 10) > 0)
					{
						var pct = productcount(Zepto("#productid_" + v).val());
						if (parseInt(includepost, 10) <= 0 || parseInt(includepost, 10) > pct)
						{
							if (parseInt(mergepostage, 10) != 1)
							{
								allmergepost += parseFloat(postage, 10) * parseInt(qty_item, 10);
							}
							else if (parseFloat(postage, 10) > parseFloat(allpostage, 10))
							{
								allpostage = parseFloat(postage, 10);
							}

						}
					}
				}
				total_money += parseFloat(total_price, 10);
				total_quantity += parseInt(qty_item, 10);
			}
			else
			{
				isall = false;
			}
		});
		if(idlists.length <= 0){
			isall = false;
		}
		total_money += allpostage + allmergepost;
		Zepto("#total_money").html('¥' + total_money.toFixed(2));
		Zepto("#total_quantity").html(total_quantity);
		$("#allselect").attr("checked", isall);
		$("#allselect").prop("checked", isall);
	}
	function allselect()
	{
		var checked = Zepto("#allselect").prop("checked");
		if (checked)
		{
			Zepto.each(idlists, function (i, v)
			{
				Zepto("#shoppingcart_" + v).attr("checked", true);
				Zepto("#shoppingcart_" + v).prop("checked", true);
			});
			recalc();
		}
		else
		{
			Zepto("#total_money").html("¥0.00");
			Zepto("#total_quantity").html("0");
			Zepto.each(idlists, function (i, v)
			{
				Zepto("#shoppingcart_" + v).attr("checked", null);
				Zepto("#shoppingcart_" + v).prop("checked", false);
				productqty_change(v);
			});
		}
	}
	{/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>