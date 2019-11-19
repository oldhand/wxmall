 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>联盟推广</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />   
	<link href="public/css/sweetalert.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/common.js" type="text/javascript" charset="utf-8"></script> 
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
		 .mui-table-view .mui-media-object {
		  /* margin-top: 10px;*/
		 }
	  	 .order-link-cell
	  	 {
		     line-height: 30px;
		     height: 30px;
		     padding: 0px 5px;  
	  	 } 
	  	 .order-link-cell a
	  	 { 
			 color:#cc3300;
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
	 	.fenge
	 	{   
			padding-left:10px;
			padding-right:10px;
	 		background: url(public/css/images/fenge5.jpg) no-repeat center left;
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
				 <h1 class="mui-title">联盟推广</h1> 
			</header> 
			{/if} 
			{include file='footer.tpl'}   
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                    <div class="mui-scroll">   
						 <input id="page" value="1" type="hidden" >
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
				Zepto('#list').html(''); 
				add_more();
				mui('#pullrefresh').pullRefresh().refresh(true);
				mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
			}, 1000);
		}
		
		function profile_info_html(v) {	 
				var sb=new StringBuilder();  
sb.append('<div class="mui-card" style="margin: 3px 3px;" >'); 
sb.append('		 <ul class="mui-table-view" style="color: #333;background: #f3f3f3;">');
sb.append('				 <li class="mui-table-view-cell order-link-cell">'); 
sb.append('					<div class="mui-media-body  mui-pull-left">');
sb.append('						<span class="mui-table-view-label">昵称：</span>'+v.givenname);
sb.append('					</div>');
sb.append('                </li>');
sb.append('				<li class="mui-table-view-cell">');  
sb.append('					        <img class="mui-media-object mui-pull-left"  src="'+v.headimgurl+'">');
sb.append('							<div class="mui-media-body">');  
sb.append('								<p class="mui-ellipsis">手机：<span class="price">'+v.mobile+'</span></p>');  
sb.append('								<p class="mui-ellipsis">所在城市：<span class="price">'+v.city+'</span></p>');
sb.append('							</div> '); 
sb.append('				 </li> '); 
sb.append(' 	 	</ul>'); 
sb.append('</div>');  
				 return sb.toString(); 
		 }
		 function profile_info_empty_html() 
		 {
var sb=new StringBuilder();  
sb.append('<div class="mui-content-padded">'); 
sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>'); 
sb.append('					      <p class="msgbody">您还没有邀请到一位好友？<br>'); 
sb.append('							  <a href="index.php">>>>&nbsp;先去逛逛买点东西吧！</a> '); 
sb.append('						  </p>  '); 
sb.append(' </div>'); 
return sb.toString(); 
		 }
		   										   
		 
	    function add_more() {
			var page = Zepto('#page').val();
	            Zepto('#page').val(parseInt(page,10) + 1);
	            mui.ajax({
	                type: 'POST',
	                url: "lianmengtui.ajax.php",
	                data: 'page=' + page,
	                success: function(json) {   
	                    var msg = eval("("+json+")");
	                    if (msg.code == 200) {  
							if (msg.data.length == 0 && page == 1)
							{
								  Zepto('#list').html(profile_info_empty_html()); 
								  mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
							}
							else
							{
		                        Zepto.each(msg.data, function(i, v) {    
									    var nd = profile_info_html(v);
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