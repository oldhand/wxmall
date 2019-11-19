<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" /> 
    <title>地图导航</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>   
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="http://api.map.baidu.com/api?v=1.3"></script> 
	<style>
	  {literal} 
		 .img-responsive { display: block; height: auto; width: 100%; }   
	 {/literal} 
	</style>
	{include file='theme.tpl'} 
</head>
<body>  
 
<div class="mui-inner-wrap">
	{if $supplier_info.showheader eq '0'}
	<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
		 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
		 <h1 class="mui-title">地图导航</h1> 
	</header> 
	{/if} 
	{include file='footer.tpl'}   
    <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">   
                 <div id="list" class="mui-table-view" >    
					    <input id="loadstatus" value="" type="hidden" >  
						<div id="mapcontainer" style="width:100%;"></div>
				 </div>
				{include file='copyright.tpl'}
         </div>
	</div>
</div>  
<script>
	var map,marker,point; 
    var to_longitude = "{$longitude}";
	var to_latitude = "{$latitude}"; 
	
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

//http://api.map.baidu.com/geoconv/v1/?coords=112.9863,28.14282&from=3&to=5&ak=8tz6qyjPPVAwqq5FMy24sXrg
function weixin_to_baidu(latitude, longitude)
{  
    Zepto.ajax({
        type: 'GET',
        url: "http://api.map.baidu.com/geoconv/v1/",
        data: 'coords='+longitude+','+latitude+'&from=3&to=5&ak=8tz6qyjPPVAwqq5FMy24sXrg',
		dataType:"jsonp",
        success: function(json) {   
			var lng = json.result[0].x;
			var lat = json.result[0].y;  
			loadbaidumap(lng,lat);
        }
    });  
} 


function loadbaidumap(from_longitude,from_latitude)
{  
	if ( from_longitude != "" && from_latitude != "" )
	{
		Zepto("#loadstatus").val('ok');  
		Zepto("#mapcontainer").css("height","800px");
		map = new BMap.Map("mapcontainer");
	    map.enableScrollWheelZoom();
	    map.enableContinuousZoom();
	    map.addControl(new BMap.NavigationControl()); 
		
		var point = new BMap.Point(from_longitude,from_latitude);    // 创建点坐标
		map.centerAndZoom(point, 19);                     // 初始化地图,设置中心点坐标和地图级别。 
		var driving =new BMap.DrivingRoute(map, {
		    renderOptions: {
		        map: map, 
				enableDragging: false
		    }
		}); 
		driving.search(new BMap.Point(from_longitude, from_latitude), new BMap.Point(to_longitude, to_latitude));
	} 
	else
	{
		var loadstatus = Zepto("#loadstatus").val();
		if (loadstatus != 'ok')
		{
			var html = '<img src="http://api.map.baidu.com/staticimage?center='+to_longitude+','+to_latitude+'&amp;zoom=17&amp;width=550&amp;height=1024&amp;markers='+to_longitude+','+to_latitude+'"  width="100%"/>';
			Zepto("#mapcontainer").html(html);
			Zepto("#loadstatus").val('ok'); 
		}
 	}
}  
 
setTimeout('loadbaidumap("","");',4000);  
 	  
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
				 Zepto("#loadstatus").val('ok'); 
				 weixin_to_baidu(latitude,longitude);
                 //loadbaidumap(longitude,latitude); 
 			  },
               cancel: function (res) { 
                    loadbaidumap("","");
               }
         }); 
});
{/literal} 
</script>
<script type="text/javascript"> 
	{literal}	 
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等  
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
	{/literal} 
</script>
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>