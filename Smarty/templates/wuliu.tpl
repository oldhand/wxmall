<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
	<title>查看物流</title>
	<link href="public/css/mui.css" rel="stylesheet"/>
	<link href="public/css/public.css" rel="stylesheet"/>
	<link href="public/css/iconfont.css" rel="stylesheet"/>
	<link href="public/css/wuliu.css" rel="stylesheet"/>
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<style>
		{literal}
		.img-responsive {
			display: block;
			height: auto;
			width: 100%;
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
				<a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a>
				<h1 class="mui-title">查看物流结果</h1>
			</header>
			<nav class="mui-bar mui-bar-tab" style="height:35px;line-height:35px;top: 44px;">
				<div>
					<span id="result-comname" class="smart-result-comname">{$type}</span>
					<span id="result-kuaidinum" class="smart-result-kuaidinum">{$invoicenumber}</span>
				</div>
			</nav>
		{else}
			<nav class="mui-bar mui-bar-tab" style="height:35px;line-height:35px;top: 0px;">
				<div>
					<span id="result-comname" class="smart-result-comname">{$type}</span>
					<span id="result-kuaidinum" class="smart-result-kuaidinum">{$invoicenumber}</span>
				</div>
			</nav>
		{/if} 
		
		{include file='footer.tpl'}
		<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}80px;{else}44px;{/if}">
			<div class="mui-scroll">
				<div id="list" class="mui-table-view">
					<ul id="wuliuinfo_ul" class="mui-table-view" style="height:100%;">
						<div class="smart-result" style="margin-bottom: 10px;" data-role="content" role="main">
							<div class="content-primary">

								<table id="queryResult" cellspacing="0" cellpadding="0">
									{if $logisticroutes|@count gt 0}
										{foreach name="logisticroutes" item=logisticroute_info from=$logisticroutes}
										    {if $logisticroute_info.pos eq 'start'}
										    	<tr class="even first-line">
										    {elseif $logisticroute_info.pos eq 'end'}
										        <tr class="even last-line checked">
										    {else}
										        <tr class="odd">
										    {/if}
		                        	 		
											<td class="col1"><span class="result-date">{$logisticroute_info.date}</span><span class="result-time">{$logisticroute_info.time}</span></td><td class="colstatus"></td>
											<td class="col2"><span>{$logisticroute_info.route}</span></td>
											</tr>   
		                        	 	{/foreach} 
	                        	 	{else}
										<tr>
											<td>
												<li class="mui-table-view-cell" style="padding-right:0px;" id="loading">
													<div class="mui-media-body" style="color:red;text-align:center;">
														<span class="mui-icon iconfont icon-loading1 mui-rotation"></span><span> 正在努力加载中，请稍候。。。</span>
													</div>
												</li>
											</td>
										</tr>
									{/if}
								</table>
							</div>
						</div>
					</ul>
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
	{if $logisticroutes|@count eq 0}
	mui.ajax({ldelim}
				 type: 'GET',
				 url: "wuliuinfo.php?type={$type}&postid={$invoicenumber}",
				 data: '',
				 success: function (json)
				 {ldelim}
					 var resultJson = eval("(" + json + ")");
					 if (resultJson.status == 200)
					 {ldelim}
						 var resultTable = $("#queryResult");
						 resultTable.empty();
						 var resultData = resultJson.data;
						 for (var i = resultData.length - 1; i >= 0; i--)
						 {ldelim}
							 var className = "odd";
							 if (i % 2 == 0)
							 {ldelim}
								 className = "even";
								 {rdelim}
							 if (resultData.length == 1)
							 {ldelim}
								 if (resultJson.ischeck == 1) className += " checked";
								 else className += " wait";
								 {rdelim}
							 else if (i == resultData.length - 1)
							 {ldelim}
								 className += " first-line";
								 {rdelim}
							 else if (i == 0)
							 {ldelim}
								 className += " last-line";
								 if (resultJson.ischeck == 1) className += " checked";
								 else className += " wait";
								 {rdelim}

							 var index       = resultData[i].ftime.indexOf(" ");
							 var result_date = resultData[i].ftime.substring(0, index);
							 var result_time = resultData[i].ftime.substring(index + 1);

							 var s_index = result_time.lastIndexOf(":");
							 result_time = result_time.substring(0, s_index);

							 resultTable.append("<tr class='" + className + "'><td class='col1'><span class='result-date'>" + result_date + "</span><span class='result-time'>" + result_time + "</span></td><td class='colstatus'></td><td class='col2'><span>" + resultData[i].context + "</span></td></tr>");
							 {rdelim}
						 {rdelim}
					 else
					 {ldelim}
						 var resultTable = $("#queryResult");
						 resultTable.empty();
						 resultTable.append('<tr><td><div class="smart-error-div"><div class="smart-error-content"><p class="icon-error">该单号暂无查询记录</p></div><div class="smart-opera-btn"></div></div></td></tr>');
						 {rdelim}
					 {rdelim}
				 {rdelim});
	{/if}
	{literal}
	mui.init({
				 pullRefresh: {
					 container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
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
			  });

	{/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>