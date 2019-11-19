<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>商品列表</title>
	<link href="public/css/mui.css" rel="stylesheet"/>
	<link href="public/css/public.css" rel="stylesheet"/>
	<link href="public/css/iconfont.css" rel="stylesheet"/>
	<link href="public/css/sweetalert.css" rel="stylesheet"/>
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

		.mui-bar .tit-sortbar {
			left: 0;
			right: 0;
			margin-top: 45px;
		}

		.price {
			color: #fe4401;
		}

		.mui-table-view-cell .mui-table-view-label {
			width: 60px;
			text-align: right;
			display: inline-block;
		}

		.mui-table-view .mui-media-object {
			margin-top: 8px;
			line-height: 50px;
		    max-width: 50px;
		    height: 50px;
		}

		.order-link-cell {
			line-height: 30px;
			height: 30px;
			padding: 0px 5px;
		}

		.order-link-cell a {
			font-size: 12px;
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

		{/literal}
	</style>
	{include file='theme.tpl'}
</head>
<body>
 
	<div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
		<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
		    <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
			<h1 class="mui-title">商品列表</h1>
		</header>
		{/if} 
		{include file='footer.tpl'}
		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
			<div class="mui-scroll">
				<input id="page" value="1" type="hidden">
				<div class="mui-table-view">
					<div class="mui-control-content mui-active">
						<ul id="list" class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;">
						</ul>
					</div>
				</div> 
				{include file='copyright.tpl'}
			</div>
		</div>
	</div> 

<script type="text/javascript">
	{literal}
	mui.init({
				 pullRefresh: {
					 container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
					 down: {
						 callback: pulldownRefresh
					 },
					 up: {
						 contentrefresh: "正在加载...", //可选，正在加载状态时，上拉加载控件上显示的标题内容
						 contentnomore: '没有更多数据了', //可选，请求完毕若没有更多数据时显示的提醒内容；
						 callback: add_more //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
					 }
				 },
			 });
	mui.ready(function ()
			  {
				  mui('#pullrefresh').scroll();
				  mui('.mui-bar').on('tap', 'a', function (e)
				  {
					  mui.openWindow({
										 url: this.getAttribute('href'),
										 id: 'info'
									 });
				  });
				  mui('.mui-table-view').on('tap', 'a.confirmreceipt', function (e)
				  {
					  var orderid = this.getAttribute('data-id');
					  swal({
							   title: "提示",
							   text: "您确定已经收到此商品吗？",
							   type: "warning",
							   showCancelButton: true,
							   closeOnConfirm: true,
							   confirmButtonText: "确定收到",
							   confirmButtonColor: "#ec6c62"
						   }, function ()
						   {
							   window.location.href = 'appraise_submit.php?type=confirmreceipt&record=' + orderid;
						   });
				  });

			  });
	function pulldownRefresh()
	{
		setTimeout(function ()
				   {
					   Zepto('#page').val(1);
					   Zepto('#list').html('');
					   add_more();
					   mui('#pullrefresh').pullRefresh().refresh(true);
					   mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
				   }, 1000);
	}

	function product_html(v)
	{
		var sb = new StringBuilder();
		sb.append('<div class="mui-card" style="margin: 3px 3px;" >');
		sb.append('		 <ul class="mui-table-view" style="color: #333;background: #f3f3f3;">'); 
		sb.append('				<li class="mui-table-view-cell">'); 
		sb.append('					        <img class="mui-media-object mui-pull-left" src="' + v.productlogo + '" >');
		sb.append('							<div class="mui-media-body">');
		sb.append('								<p class="mui-ellipsis" style="color:#333">商品名称：' + v.productname + '</p>'); 
		sb.append('								<p class="mui-ellipsis"><span style="text-decoration: line-through;">市场价：<span class="price" >¥' + v.market_price + '</span></span><span style="padding-left:10px;"> 销售价：<span class="price">¥' + v.shop_price + '</span></span></p>');
		sb.append('								<p class="mui-ellipsis">分佣比率：<span class="price">' + v.finishedmemberrate + '%</span> <span style="padding-left:10px;">预计收益：<span class="price">¥' + v.anticipatedincome + '</span></span></p>');
		//sb.append('								<p class="mui-ellipsis">店员收益：<span class="price">¥' + v.shop_price + '</span></p>');
		//sb.append('								<p class="mui-ellipsis">店主收益：<span class="price">¥' + v.shop_price + '</span></p>');
		
		  
		
		sb.append('							</div> '); 
		sb.append('				 </li> '); 
		sb.append(' 	 	</ul>');
		sb.append('</div>');
		return sb.toString();
	}
	function product_empty_html()
	{
		var sb = new StringBuilder();
		sb.append('<div class="mui-content-padded">');
		sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>');
		sb.append('					      <p class="msgbody">您的推广收益明细还是空的！<br>');
		sb.append('							  >>&nbsp;先去推广一下吧! ');
		sb.append('						  </p>  ');
		sb.append(' </div>');
		return sb.toString();
	}

	function add_more()
	{
		var page = Zepto('#page').val();
		Zepto('#page').val(parseInt(page, 10) + 1);
		mui.ajax({
					 type: 'POST',
					 url: "physicalstore_products.php",
					 data: 'type=ajax&page=' + page,
					 success: function (json)
					 {
						 var msg = eval("(" + json + ")");
						 if (msg.code == 200)
						 {
							 if (msg.data.length == 0 && page == 1)
							 {
								 Zepto('#list').html(product_empty_html());
								 mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
							 }
							 else
							 {
								 Zepto.each(msg.data, function (i, v)
								 {
									 var nd = product_html(v);
									 //alert(nd);
									 Zepto('#list').append(nd);
								 });
								 mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
							 }
						 }
						 else
						 {
							 mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
						 }
					 }
				 });
	}
	//触发第一页
	if (mui.os.plus)
	{
		mui.plusReady(function ()
					  {
						  setTimeout(function ()
									 {
										 mui('#pullrefresh').pullRefresh().pullupLoading();
									 }, 1000);

					  });
	}
	else
	{
		mui.ready(function ()
				  {
					  Zepto('#page').val(1);
					  mui('#pullrefresh').pullRefresh().pullupLoading();
				  });
	}

	{/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>