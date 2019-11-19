<!DOCTYPE html>
   <html> 
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
       <title>商家资讯</title>
       <link href="public/css/mui.css" rel="stylesheet" />
       <link href="public/css/public.css" rel="stylesheet" />
   	   <link href="public/css/iconfont.css" rel="stylesheet" />  
       <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
   	   <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
       <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
   	   <script type="text/javascript" src="public/js/jweixin.js"></script> 
   	<style>
   	  {literal} 
   		.img-responsive { display: block; height: auto; width: 100%; } 
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
		
	 	.mui-table-view .mui-media-object {
	 	  line-height: 100px;
	 	  max-width: 100px;
	 	  height: 100px;
	 	} 
	 	 .mui-table-view .mui-media-body p.mui-ellipsis {
	  	    white-space: normal;
	 	 }
	 	 .mui-table-view-chevron .mui-table-view-cell {
	 	   padding-right: 45px;
	 	 }
	 	 .mui-navigate-right:after, .mui-push-right:after {
	 	   right: 25px; 
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
   				 <h1 class="mui-title">商家资讯</h1>
                 
   			</header> 
			{/if} 
   			{include file='footer.tpl'}   
   	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                       <div class="mui-scroll">   
						     <input id="page" value="1" type="hidden" > 
      		                 <div id="list" class="mui-table-view" >     
									<ul class="mui-table-view" >
										 
									</ul>  
   						    </div>
						   {include file='copyright.tpl'}
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
				setTimeout(function() {   
		            Zepto('#page').val(1);  
					Zepto('#list').html(''); 
					add_more();
					mui('#pullrefresh').pullRefresh().refresh(true);
					mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
				}, 1000);
			}
		
			function content_html(v) {	
				var type = Zepto('#type').val();
					var sb=new StringBuilder();   
	sb.append('			 	<li class="mui-table-view-cell mui-media">'); 
	sb.append('			 		<a class="mui-navigate-right" href="view.php?id='+v.id+'">'); 
	sb.append('			 			<img class="mui-media-object mui-pull-left" src="'+v.image+'">'); 
	sb.append('			 			<div class="mui-media-body">');
	sb.append('			 				'+v.articletitle); 
	sb.append('			 				<p class="mui-ellipsis">'+v.description+'</p>'); 
	sb.append('			 			</div>'); 
	sb.append('			 		</a>'); 
	sb.append('			 	</li>');  
					 return sb.toString(); 
			 }
		
		
			 function content_empty_html() 
			 {
	var sb=new StringBuilder();  
	sb.append('<div class="mui-content-padded">'); 
	sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>'); 
	sb.append('					      <p class="msgbody">目前还没有资讯数据！<br>'); 
	sb.append('							  <a href="index.php">>>>&nbsp;回到首页</a> '); 
	sb.append('						  </p>  '); 
	sb.append(' </div>'); 
	return sb.toString(); 
			 }
		   										   
		 
		    function add_more() {
				var page = Zepto('#page').val(); 
		            Zepto('#page').val(parseInt(page,10) + 1);
		            mui.ajax({
		                type: 'POST',
		                url: "news.ajax.php",
		                data: 'page=' + page + '&_=' + Math.random(),
		                success: function(json) {   
		                    var msg = eval("("+json+")");
		                    if (msg.code == 200) {  
								if (msg.data.length == 0 && page == 1)
								{
									  Zepto('#list').html(content_empty_html()); 
									  mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
								}
								else
								{
			                        Zepto.each(msg.data, function(i, v) {    
										    var nd = content_html(v); 
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