 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我的收益</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<style>
	  {literal} 
		 .img-responsive { display: block; height: auto; width: 100%; }  
	   	 .mui-bar .tit-sortbar{
	   	 	left: 0;
	   	 	right: 0;
	   	 	margin-top: 45px; 
	   	 }
		 .mui-bar .mui-segmented-control {
		   top: 0px; 
		 }
		 .mui-segmented-control.mui-segmented-control-inverted .mui-control-item {
		   color: #333; 
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
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
	{include file='leftmenu.tpl'}  
			<div class="mui-inner-wrap">
				{if $supplier_info.showheader eq '1'}
					<header class="mui-bar mui-bar-nav" style="padding-right: 15px;background-color:#fff">   
		                 <div class="mui-title mui-content tit-sortbar" id="sortbar" style="margin-top: 0px;"> 
				 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
				 					<a class="mui-control-item" href="profit_share.php">分享收益</a>
				 					<a class="mui-control-item mui-active" href="profit_commission.php">提成收益</a> 
									<a class="mui-control-item" href="profit_popularize.php">推广收益</a>
				 				</div> 
		                 </div>
					</header>
				{else}
					<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
						 <a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
						 <h1 class="mui-title">我的收益</h1>
		                 <div class="mui-title mui-content tit-sortbar" id="sortbar"> 
				 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
				 					<a class="mui-control-item" href="profit_share.php">分享收益</a>
				 					<a class="mui-control-item mui-active" href="profit_commission.php">提成收益</a> 
									<a class="mui-control-item" href="profit_popularize.php">推广收益</a>
				 				</div> 
		                 </div>
					</header> 
				{/if} 
			{include file='footer.tpl'}   
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '1'}40px;{else}85px;{/if}">
                    <div class="mui-scroll" style="border-top:1px #ccc solid;">  
						<input id="page" value="1" type="hidden" > 
   		                 <div class="mui-table-view" >   
		 		 					<ul id="list"  class="mui-table-view">
										  
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
			
		});  
		function pulldownRefresh() { 
			Zepto('#sortbar').css("display","none");
			setTimeout(function() {  
				Zepto('#sortbar').css("display","");
	            Zepto('#page').val(1);  
				Zepto('#list').html(profit_title_html());  
				add_more();
				mui('#pullrefresh').pullRefresh().refresh(true);
				mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
			}, 1000);
		}
		
		function profit_html(v) {	 
				var sb=new StringBuilder();   
sb.append('			 <li class="mui-table-view-cell"> ');  
sb.append('				<div class="mui-media-body  mui-pull-left">');  
sb.append('					<span style="width:100px;text-align:left;display:inline-block;margin-top: 10px; ">'+v.published+'</span>');  
sb.append('					<span style="display:inline-block;margin-top: 10px;">'+v.orders_no+'</span>');  
sb.append('				</div>');  
sb.append('				<div class="mui-media-body mui-pull-right" style="text-align:center;">');  
sb.append('					【'+v.status+'】<br><span class="price">¥'+v.amount+'</span>');  
sb.append('				</div> ');  
sb.append('           </li>');   
				 return sb.toString(); 
		 }

 		function profit_title_html() {	 
 				var sb=new StringBuilder();   
 sb.append('			 <h5><li class="mui-table-view-cell profit-title" style="background-color: #efeff4;"> ');  
 sb.append('				<div class="mui-media-body  mui-pull-left">');  
 sb.append('					<span style="width:100px;text-align:left;display:inline-block;">提成日期</span>店铺/订单');  
 sb.append('				</div>');  
 sb.append('				<div class="mui-media-body mui-pull-right">');  
 sb.append('				 收益');  
 sb.append('				</div> ');  
 sb.append('           </li></h5>');   
 				 return sb.toString(); 
 		 }		
		
		 function profit_empty_html() 
		 {
var sb=new StringBuilder();  
sb.append('<div class="mui-content-padded">'); 
sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>'); 
sb.append('					      <p class="msgbody">您的收益还是空的，快去选购商品吧<br>'); 
sb.append('							  <a href="index.php">>>>&nbsp;去逛逛</a> '); 
sb.append('						  </p>  '); 
sb.append(' </div>'); 
return sb.toString(); 
		 }
		   										   
		 
	    function add_more() {
			var page = Zepto('#page').val();
	            Zepto('#page').val(parseInt(page,10) + 1);
	            mui.ajax({
	                type: 'POST',
	                url: "profit.ajax.php",
	                data: 'type=commission&page=' + page,
	                success: function(json) {   
	                    var msg = eval("("+json+")");
	                    if (msg.code == 200) {  
							if (msg.data.length == 0 && page == 1)
							{
								  Zepto('#list').html(profit_empty_html()); 
								  mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
							}
							else
							{
		                        Zepto.each(msg.data, function(i, v) {    
									    var nd = profit_html(v); 
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
				Zepto('#list').html(profit_title_html());  
	            mui('#pullrefresh').pullRefresh().pullupLoading();
	        });
	    } 
		
	   
	{/literal} 
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>