<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" /> 
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
    <script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<style>
	{literal}	
	.mui-table-view .mui-media .mui-icon{
	  color:#fe4401; 
	  padding: 1px;
	  font-size: 1.0em;
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
		padding-bottom: 20px;
	}

	.msgbody a {
		font-size: 1.0em;
	}
	
	{/literal}	
	</style>
	{include file='theme.tpl'} 
</head>

<body>  
    <!-- 侧滑导航根容器 -->
    <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
            <!-- 主页面标题 -->
			{if $supplier_info.showheader eq '0'}
			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
			    <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
				<h1 class="mui-title">实体店列表</h1> 
			</header>   
			{/if} 
			{include file='footer.tpl'} 
             <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                 <div class="mui-scroll">  
					     <input id="loadstatus" value="" type="hidden" >
	                     <ul id="list" class="mui-table-view list" style="padding-top: 3px;background-color:#efeff4;">  
							<ul id="businesse_list" class="mui-table-view mui-table-view-chevron mui-media-large"> 
								<li class="mui-table-view-cell" style="padding-right:0px;"  id="loading"> 
										<div class="mui-media-body" style="color:red;text-align:center;">  
											 <span class="mui-icon iconfont icon-loading1 mui-rotation"></span><span> 正在努力加载中，请稍候。。。</span>
										</div> 
                                </li>  
								<!-- <li class="mui-table-view-cell mui-media">
									<a class="mui-navigate-right"  href="selectbusinesse.php?businesseid=123">
										<img style="margin-top: 10px;" class="mui-media-object mui-pull-left" src="http://cs.saasw.com/storage/cs/2015/June/20150626163331358.jpg">
										<div class="mui-media-body">
											CBD
				  							<p class='mui-ellipsis'>天心区-芙蓉南路</p>
											<p class='mui-ellipsis'>湖南省长沙市天心区芙蓉南路1段-92</p>
											<p class='mui-ellipsis'> <span class="mui-icon iconfont icon-shouji"></span>15974160338</p>
											<p class='mui-ellipsis'> <span class="mui-icon iconfont icon-ditudaohang"></span>>10KM</p>
										</div>
									</a>
								</li> -->
							</ul> 
	                     </ul>   
                 </div> 
             </div> 
            <div class="mui-backdrop" style="display:none;"></div>
        </div>  
    </div>
        <input id="longitude" type="hidden" value="0"/>
        <input id="latitude" type="hidden" value="0"/>
<script type="text/javascript">
	
wx.config(
    {ldelim}
        //debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{$share_info.appid}', // 必填，公众号的唯一标识
        timestamp:'{$share_info.timestamp}', // 必填，生成签名的时间戳
        nonceStr: '{$share_info.noncestr}', // 必填，生成签名的随机串
        signature: '{$share_info.signature}',// 必填，签名，见附录1
        jsApiList: ['getLocation'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    {rdelim}); 

{literal}		  
wx.ready(function()
{ 
	 wx.hideOptionMenu(); 
     wx.getLocation(
		 {
              success: function (res) 
              {
                //alert(JSON.stringify(res));
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度 
				Zepto('#longitude').val(longitude);
				Zepto('#latitude').val(latitude); 
			  	var loadstatus = Zepto("#loadstatus").val();
			  	if (loadstatus != 'ok')
			  	{
  				  Zepto("#loadstatus").val('ok');
                  add_more();
			    } 
			  },
              cancel: function (res) {
  				    Zepto('#longitude').val('0');
  				    Zepto('#latitude').val('0'); 
				  	var loadstatus = Zepto("#loadstatus").val();
				  	if (loadstatus != 'ok')
				  	{
	  				  Zepto("#loadstatus").val('ok');
	                  add_more();
				    }
              }
        }); 
});

setTimeout('time_add_more();',7000);  

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
 
 /*
 * 下拉刷新具体业务实现
 */
function pulldownRefresh() { 
	setTimeout(function() {  
		Zepto('#list').html(''); 
        add_more();
		mui('#pullrefresh').pullRefresh().refresh(true);
		mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
	}, 1000);
}
function businesse_html(v) 
{	 
		 var sb=new StringBuilder(); 
	    sb.append('<li class="mui-table-view-cell mui-media">');
	    sb.append('<a class="mui-navigate-right" href="mapnavigation.php?id='+v.id+'">');
		sb.append('<img style="margin-top: 10px;" class="mui-media-object mui-pull-left" src="'+v.image+'">');
		sb.append('<div class="mui-media-body">'+v.storename+''); 
		sb.append('	<p class="mui-ellipsis">'+v.address+'</p>'); 
		sb.append('	<p class="mui-ellipsis"><span class="mui-icon iconfont icon-weizhi"></span>'+v.distanceinfo+'</p>'); 
		sb.append('</div></a </li>'); 
		 return sb.toString(); 
 }
function businesse_empty_html()
{
	var sb = new StringBuilder();
	sb.append('<div class="mui-content-padded">');
	sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>');
	sb.append('					      <p class="msgbody">商城目前没有设置实体店。<br>'); 
	sb.append('						  </p>  ');
	sb.append(' </div>');
	return sb.toString();
}
function time_add_more()
{
	var loadstatus = Zepto("#loadstatus").val();
	if (loadstatus != 'ok')
	{
		Zepto("#loadstatus").val('ok');
		Zepto('#longitude').val('0');
		Zepto('#latitude').val('0');
		add_more(); 
	}
}
 
function add_more() 
{ 
    var longitude = Zepto('#longitude').val(),
        latitude = Zepto('#latitude').val(); 
        mui.ajax({
            type: 'POST',
            url: "physicalstore.php",
            data: 'type=ajax&longitude=' + longitude + '&latitude=' + latitude,
            success: function(json) {  
				//alert(json);  
				Zepto('#loading').css('display',"none"); 
                var msg = eval("("+json+")");
                if (msg.code == 200) {   
					if (msg.length == 0)
					{   
					    var nd = businesse_empty_html();
                        Zepto('#list').append(nd);  
					}
					else
					{
	                    Zepto.each(msg.data, function(i, v) {  
							    var nd = businesse_html(v);
	                            Zepto('#list').append(nd);  
	                    });  
					}
                   
                    mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
                } else { 
                    mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
                }
            }
        }); 
    } 
mui.ready(function() {  
	mui('.mui-table-view').on('tap', 'a', function(e) {
		mui.openWindow({
			url: this.getAttribute('href'),
			id: 'info'
		});
	});
	mui('.mui-bar').on('tap','a',function(e){
		mui.openWindow({
			url: this.getAttribute('href'),
			id: 'info'
		});
	});
});	
	
{/literal}		
 </script>	
 <script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>