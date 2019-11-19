<!DOCTYPE html>
	 <html> 
	 <head>
	     <meta charset="utf-8">
	     <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	     <title>我的预订记录</title>
	    <link href="public/css/mui.css" rel="stylesheet" />
	    <link href="public/css/public.css" rel="stylesheet" />
		<link href="public/css/iconfont.css" rel="stylesheet" />   
		
	    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
		<script type="text/javascript" src="public/js/jweixin.js"></script> 
		<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
		<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>   
		
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
	 	 {/literal} 
	 	</style>
	{include file='theme.tpl'} 
	 </head>
	 <body>  
	 <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper"> 
	 		<div class="mui-inner-wrap">
	 			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
	 				 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
	 				 <h1 class="mui-title">我的预订记录</h1>
	                  <div class="mui-title mui-content tit-sortbar" id="sortbar"> 
	 		 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
	 		 					<a class="mui-control-item" href="reserve.php">餐厅预订</a>
	 		 					<a class="mui-control-item  mui-active" href="reserveofme.php">我的预订记录</a> 
	 		 				</div> 
	                  </div>
	 			</header> 
				{include file='footer.tpl'}   
	 	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: 85px;">  
	                     <div class="mui-scroll">   
	    		                 <div class="mui-table-view" >   
									    <input id="page" value="1" type="hidden" >
							            <div style="height: 5px;">&nbsp;</div>
										<div id="list" >  
					 						 
									     </div>  
			 		 							 
										<ul class="mui-table-view" style="background-color: #efeff4;margin-top: 5px;">
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
	 			mui('.mui-table-view').on('tap','a',function(e){
	 				mui.openWindow({
	 					url: this.getAttribute('href'),
	 					id: 'info'
	 				});
	 			}); 
	 	   }); 
		   
   		function pulldownRefresh() {  
			Zepto('#sortbar').css("display","none");
   			setTimeout(function() {   
   	            Zepto('#page').val(1);  
   				Zepto('#list').html('');  
   				add_more();
   				mui('#pullrefresh').pullRefresh().refresh(true);
   				mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
   			}, 1000);
   		}
		
   		function reserve_html(v) {	 
   				var sb=new StringBuilder();  
   sb.append('<div class="mui-card" style="margin: 0 3px;margin-top: 5px;"> '); 
   sb.append('		 <ul class="mui-table-view" style="color: #333;">'); 
   sb.append('			  <li class="mui-table-view-cell mui-media" >'); 
   sb.append('                <a href="reserve.php?record='+v.id+'" class="mui-navigate-right"> '); 
   sb.append('					<div class="mui-media-body  mui-pull-left" style="width:60%;">'); 
   sb.append('						<p class="mui-ellipsis" style="color:#333">联系人：'+v.consignee+'</p> '); 
   sb.append('						<p class="mui-ellipsis" style="color:#333">手机：'+v.mobile+'</p>');  
   sb.append('						<p class="mui-ellipsis" style="color:#333">就餐时间：'+v.reservetime+'</p>');  
   sb.append('					</div> '); 
   sb.append('					<div class="mui-media-body  mui-pull-right" style="width:35%;padding-right:20px;">'); 
   sb.append('						<p class="mui-ellipsis"  style="color:#333">位置：'+v.place+'</p>'); 
   sb.append('						<p class="mui-ellipsis"  style="color:#333">桌数：'+v.deskofpeople+'桌</p>'); 
   sb.append('						<p class="mui-ellipsis"  style="color:#333">人数：'+v.numberofpeople+'人</p>'); 
   sb.append('					</div>'); 
   sb.append('					<div class="mui-media-body  mui-pull-left" style="width:1000%;">'); 
   sb.append('						<p class="mui-ellipsis" style="color:#333">留言：'+v.memo+'</p>'); 
   sb.append('						<p class="mui-ellipsis" style="color:#333">状态：<span style="color:#CF2D28">'+v.mall_reservesstatus+'</span></p>'); 
   sb.append('					</div>'); 
   sb.append('				</a>'); 
   sb.append('			  </li> '); 
   sb.append(' 	 	</ul> '); 
   sb.append('</div>');  
   				 return sb.toString(); 
   		 } 	 
		 
		 
   		 function reserve_empty_html() 
   		 {
   var sb=new StringBuilder();  
   sb.append('<div class="mui-content-padded">'); 
   sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>'); 
   sb.append('					      <p class="msgbody">您的预订记录还是空的，去首页逛逛吧！<br>'); 
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
   	                url: "reserve.ajax.php",
   	                data: 'type=trade&page=' + page,
   	                success: function(json) {   
   	                    var msg = eval("("+json+")");
   	                    if (msg.code == 200) {  
							Zepto('#sortbar').css("display","");
   							if (msg.data.length == 0 && page == 1)
   							{
   								  Zepto('#list').html(reserve_empty_html()); 
   								  mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
   							}
   							else
   							{
   		                        Zepto.each(msg.data, function(i, v) {    
   									    var nd = reserve_html(v);
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