

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title></title>
	<link href="public/css/mui.css" rel="stylesheet" />
	<link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />
	<link href="public/css/sweetalert.css" rel="stylesheet" />
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/sweetalert.min.js"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>

	<style>
		{literal}
		.img-responsive { display: block; height: auto; width: 100%; }
		.mui-bar .tit-sortbar{
			left: 0;
			right: 0;
			margin-top: 45px;
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
		.mui-table-view .mui-media-object {
			margin-top: 10px;
		}
		.order-link-cell
		{
			line-height: 30px;
			height: 30px;
			padding: 0px 5px;
		}
		.order-link-cell a
		{
			font-size: 12px;
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
 
	<div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
		<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
			 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
			<h1 class="mui-title">我的全部车次</h1> 
		</header>
		{/if} 
		{include file='footer.tpl'}
		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
			<div class="mui-scroll">
				<input id="page" value="1" type="hidden" >
				<input id="allowreturngoods" value="{$supplier_info.allowreturngoods}" type="hidden" >
				<div class="mui-table-view" >
					<div class="mui-control-content mui-active">
						<ul  id="list"  class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;">
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
	mui.ready(function() {
		mui('#pullrefresh').scroll();
		mui('.mui-bar').on('tap','a',function(e){
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		}); 
		 mui('.mui-table-view').on('tap', 'a.logistictrip', function (e) {
            mui.openWindow({
                url: this.getAttribute('href'),
                id: 'info'
            });
        });

	});
	function pulldownRefresh() {
		setTimeout(function() {
			Zepto('#page').val(1);
			Zepto('#list').html('');
			add_more();
			mui('#pullrefresh').pullRefresh().refresh(true);
			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
		}, 1000);
	}

	function logistichistorytrip_html(v) {
		var sb=new StringBuilder();
		sb.append('<div class="mui-card" style="margin: 3px 3px;" >');
		sb.append('		 <ul class="mui-table-view" style="color: #333;background: #f3f3f3;">');
		sb.append('				 <li class="mui-table-view-cell order-link-cell">');
		sb.append('					<div class="mui-media-body  mui-pull-left">');
		sb.append('						<span class="mui-table-view-label">车次：</span>'+v.mall_logistictrips_no);
		sb.append('					</div>'); 
		sb.append('				<li class="mui-table-view-cell">');  
		sb.append('					 <a href="logistictrip.php?record='+v.id+'" class="mui-navigate-right logistictrip"> ');
		sb.append('							<div class="mui-media-body">'); 
		sb.append('								<p class="mui-ellipsis">状态：<span class="price">'+v.mall_logistictripsstatus+'</span></p>');
		sb.append('								<p class="mui-ellipsis">创建时间：<span class="price">'+v.published+'</span></p>');
		sb.append('								<p class="mui-ellipsis">出发时间：<span class="price">'+v.startdate+'</span></p>');
		sb.append('								<p class="mui-ellipsis">结束时间：<span class="price">'+v.enddate+'</span></p>');
		if (v.swaptime != "")
		{
			sb.append('								<p class="mui-ellipsis">持续时间：<span class="price">'+v.swaptime+'</span></p>');
		}
		sb.append('								<p class="mui-ellipsis">线路：<span class="price">'+v.serialname+'&nbsp;</span></p>');
		sb.append('								<p class="mui-ellipsis">包裹数量：<span class="price">'+v.billcount+'&nbsp;件</span></p>');
		sb.append('							</div> '); 
		sb.append('				 	 </a> '); 
		sb.append('				 </li> '); 
		 
		sb.append(' 	 	</ul>');
		sb.append('</div>');
		return sb.toString();
	}
	
 
	function logistichistorytrip_empty_html()
	{
		var sb=new StringBuilder();
		sb.append('<div class="mui-content-padded">');
		sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>');
		sb.append('					      <p class="msgbody">还没有您经手的车次，快去创建车次吧<br>'); 
		sb.append('						  </p>  ');
		sb.append(' </div>');
		return sb.toString();
	}


	function add_more() {
		var page = Zepto('#page').val();
		Zepto('#page').val(parseInt(page,10) + 1);
		mui.ajax({
			type: 'POST',
			url: "logistichistorytrip.ajax.php",
			data: 'type=trade&page=' + page,
			success: function(json) {
				var msg = eval("("+json+")");
				if (msg.code == 200) {
					if (msg.data.length == 0 && page == 1)
					{
						Zepto('#list').html(logistichistorytrip_empty_html());
						mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
					}
					else
					{
						Zepto.each(msg.data, function(i, v) {
							var nd = logistichistorytrip_html(v);
							//alert(nd);
							Zepto('#list').append(nd);
						});
						mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
					}
				} else {
					mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
				}
			}
		});
	}
	//触发第一页
	if (mui.os.plus) {
		mui.plusReady(function() {
			setTimeout(function() {
				mui('#pullrefresh').pullRefresh().pullupLoading();
			}, 1000);

		});
	} else {
		mui.ready(function() {
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