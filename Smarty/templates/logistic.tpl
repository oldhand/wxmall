<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet"/>
    <link href="public/css/public.css" rel="stylesheet"/>
    <link href="public/css/iconfont.css" rel="stylesheet"/>
 	<link href="public/css/sweetalert.css" rel="stylesheet"/>
 
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" src="public/js/sweetalert.min.js"></script>

    <style>
        {literal}
        .img-responsive{ display:block; height:auto; width:100%; }

        .mui-input-row label{
            line-height:21px;
            height:21px;
        }

        .menuicon{
            color:#fe4401;
            padding-right:5px;
        }

        .mui-grid-view .mui-media{
            color:#fe4401;
            background:#FFFFFF;
            padding:5px;
        }

        #orders .mui-table-view.mui-grid-view .mui-table-view-cell{
            padding:10px 0 5px 0;
            font-size:1.4em;
        }

        #orders .mui-table-view.mui-grid-view .mui-table-view-cell .mui-icon{
            font-size:2.0em;
        }

        #orders .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body{
            font-size:12px;
            text-overflow:clip;
            color:#333;
        }

        #orders .mui-icon .mui-badge{
            font-size:10px;
            line-height:1.4;
            position:absolute;
            top:0px;
            left:100%;
            margin-left:-40px;
            padding:1px 5px;
            color:red;
            background:white;
            border:1px solid red;
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
		    <h1 class="mui-title" id="pagetitle">{$supplier_info.mylogisticname}管理平台</h1>
        </header> 
		{/if} 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body">
                                <a href="javascript:;">
                                    <a href="usercenter.php?profileid={$profile_info.profileid}" class="refreshprofile"><img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}"></a>
                                    <div class="mui-media-body">
										{if $logistic_info.type eq 'logisticdriver'}
	                                        <p class='mui-ellipsis' style="color:#333">司机：{$logistic_info.drivername}【{$profile_info.givenname}】</p>
	                                        <p class='mui-ellipsis'><span style="color:#333">车牌号码：{$logistic_info.licenseplate}【{$logistic_info.vehiclemodel}】</span>
												  
											</p> 
										{/if}
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                {if $errormsg neq ''}
                    <div class="mui-card" style="margin: 0 3px;">
	                    <ul class="mui-table-view">
	                        <li class="mui-table-view-cell">
                            	 	<div class="mui-media-body">  
										    <p class='mui-ellipsis' style="color:red;text-align:center;">{$errormsg}</p> 
                                    </div> 
	                        </li>
	                    </ul>
	                </div>
                {/if}
				{if $logistictrip_info|@count gt 0}
	                <div class="mui-card" style="margin: 0 3px;">
	                    <ul class="mui-table-view">
	                        <li class="mui-table-view-cell">
                            	 	<div class="mui-media-body"> 
										 
										    <p class='mui-ellipsis' style="color:#333;text-align:center;">【当前执行车次】</p>
	                                        <p class='mui-ellipsis' style="color:#333">车次：{$logistictrip_info.mall_logistictrips_no}</p> 
	                                        <p class='mui-ellipsis' style="color:#333">状态：{$logistictrip_info.mall_logistictripsstatus}</p> 
	                                        <p class='mui-ellipsis' style="color:#333">创建时间：{$logistictrip_info.published}</p> 
											{if $logistictrip_info.startdate neq ''}
												<p class='mui-ellipsis' style="color:#333">出发时间：{$logistictrip_info.startdate}</p>
											{/if}
	                                        <p class='mui-ellipsis' style="color:#333">包裹数量：{$logistictrip_info.billcount}</p> 
											{if $logistictrip_info.serialname neq ''}
												<p class='mui-ellipsis' style="color:#333">路线：{$logistictrip_info.serialname}</p>
											{/if}
											  
                                    </div> 
	                        </li>
	                    </ul>
	                </div>
				{/if}
                <div class="mui-card" style="margin: 3px 3px;">
                    <ul id="orders" class="mui-table-view" style="padding-top: 5px;text-align:center;"> 
                        {if $logistictrip_info|@count eq 0}
	                        <li class="mui-table-view-cell">
	                            <a id="createlogistictrip" class="mui-navigate-right">
	                                <div class="mui-media-body  mui-pull-left">
	                                    <span class="mui-icon iconfont icon-xinjian  menuicon button-color"></span>创建车次
	                                </div>
	                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
	                                    创建车次，开启物流配送
	                                </div>
	                            </a>
	                        </li>
						{else}
							 {if $logistictrip_info.logistictripstatus eq '0'}
	                        <li class="mui-table-view-cell">
	                            <a href="#vipcards" class="mui-navigate-right vipcards">
	                                <div class="mui-media-body  mui-pull-left">
	                                    <span class="mui-icon iconfont icon-fenxiao  menuicon button-color"></span>选择路线
	                                </div>
	                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
	                                    给当前车次的选择路线
	                                </div>
	                            </a>
	                        </li>
						    {/if}
							{if $logistictrip_info.logisticpackageid neq '' }
	                        <li class="mui-table-view-cell">
	                            <a href="logisticpackage.php" class="mui-navigate-right link">
	                                <div class="mui-media-body  mui-pull-left">
	                                    <span class="mui-icon iconfont icon-youxiang  menuicon button-color"></span>包裹管理
	                                </div>
	                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
	                                    对当前车次的包裹进行揽件
	                                </div>
	                            </a>
	                        </li>
							{/if}
						{/if}
						
                        
                         
	                         {if $logistictrip_info.logistictripstatus eq '1'}
		                          <li class="mui-table-view-cell">
		                            <a id="startlogistictrip" class="mui-navigate-right">
		                                <div class="mui-media-body  mui-pull-left">
		                                    <span class="mui-icon iconfont icon-dingwei  menuicon button-color"></span>发车
		                                </div>
		                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
		                                    出发，将包裹送到目的地
		                                </div>
		                            </a>
		                        </li>
	                         {/if}
	                         {if $logistictrip_info.logistictripstatus eq '2'}
		                        <li class="mui-table-view-cell">
		                            <a id="uploadlocation" class="mui-navigate-right">
		                                <div class="mui-media-body  mui-pull-left">
		                                    <span class="mui-icon iconfont icon-dingwei  menuicon button-color"></span>上报当前位置
		                                </div>
		                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
		                                    将地理位置上报平台
		                                </div>
		                            </a>
		                        </li>
								
		                        <li class="mui-table-view-cell">
		                            <a id="endlogistictrip" class="mui-navigate-right">
		                                <div class="mui-media-body  mui-pull-left">
		                                    <span class="mui-icon iconfont icon-tingzhi  menuicon button-color"></span>结束车次
		                                </div>
		                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
		                                    当次运输到达目的地，结束车次
		                                </div>
		                            </a>
		                        </li>
	                        {/if} 
						
						
                        <li class="mui-table-view-cell">
                            <a href="logistichistorytrip.php" class="mui-navigate-right link">
                                <div class="mui-media-body  mui-pull-left">
                                    <span class="mui-icon iconfont icon-history  menuicon button-color"></span>全部历史车次
                                </div>
                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
                                   查看您经手的所有车次
                                </div>
                            </a>
                        </li>
                      
                         
                    </ul>
                </div>
				{include file='copyright.tpl'} 
            </div>
        </div>

        <!-- end pullrefresh  -->
    </div>
<input id="logistictripid" type="hidden" value="{$logistictrip_info.logistictripid}"/> 
<div id="vipcards" class="mui-popover mui-popover-action mui-popover-bottom">
	<ul class="mui-table-view">
		{foreach name="logisticpackages" item=serialname key=logisticpackageid  from=$logisticpackages}
			<li class="mui-table-view-cell">
				<a href="#" data-id="{$logisticpackageid}" >路线：{$serialname}</a>
			</li>
		{/foreach}
	</ul> 
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<a href="#vipcards" data-id="" data-amount="" style="font-weight:900;">不更换路线</a>
		</li>
	</ul>
</div>

<script type="text/javascript">
    {literal}
    var mask = null;
    mui.init({
        pullRefresh: {
            container: '#pullrefresh'
        },
    });
    mui.ready(function () {
        mui('#pullrefresh').scroll();
		
        mui('.mui-bar').on('tap', 'a', function (e) {
            mui.openWindow({
                url: this.getAttribute('href'),
                id: 'info'
            });
        });
		
	  mui('#vipcards').on('tap', 'a', function ()
	  {
		  var a = this, parent;
		  //根据点击按钮，反推当前是哪个actionsheet
		  for (parent = a.parentNode; parent != document.body; parent = parent.parentNode)
		  {
			  if (parent.classList.contains('mui-popover-action'))
			  {
				  break;
			  }
		  }
		  //关闭actionsheet
		  mui('#' + parent.id).popover('toggle');

		  var logisticpackageid = Zepto(this).attr("data-id"); 
		  var logistictripid = Zepto("#logistictripid").val(); 
		  if (logisticpackageid != "")
		  {
	          mui.openWindow({
	              url: "logistic.php?type=selectpackage&logisticpackageid="+logisticpackageid+"&logistictripid="+logistictripid,
	              id: 'info'
	          });
		  } 
	 
	  })
      
        mui('.mui-table-view').on('tap', 'a#createlogistictrip', function (e) {
            swal({
                title: "提示",
                text: "您确定需要创建车次吗？",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: "确定",
                confirmButtonColor: "#ec6c62"
            }, function () {
	            mui.openWindow({
	                url: "logistic.php?type=tripcreate",
	                id: 'info'
	            });
            });
        });
        
          mui('.mui-table-view').on('tap', 'a#startlogistictrip', function (e) {
            swal({
                title: "提示",
                text: "出发后，箱包不可以修改！\n您确定需要出发吗？",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: "确定",
                confirmButtonColor: "#ec6c62"
            }, function () {
	           mui.openWindow({
	                url: "logistic.php?type=start",
	                id: 'info'
	            });
            });
        });
        mui('.mui-table-view').on('tap', 'a#uploadlocation', function (e) {
	         var longitude = $("#longitude").val();
	         var latitude = $("#latitude").val();
	         if ( longitude == "0" ||  latitude == "0")
	         {
		         swal({
								  title: "错误",
								  text: "目前还没有定位成功！",
								  type: "error",
								  timer: 5000,
								  showConfirmButton: false,
								  closeOnConfirm: false, 
								  showCancelButton: true,
								  cancelButtonText: "关闭"
						});
		     }
		     else
		     {
					 swal({
		                title: "提示",
		                text: "您确定需要上传当前位置吗？",
		                type: "warning",
		                showCancelButton: true,
		                closeOnConfirm: true,
		                confirmButtonText: "确定",
		                confirmButtonColor: "#ec6c62"
		            }, function () {
			           mui.openWindow({
			                url: "logistic.php?type=uploadlocation&latitude="+latitude+"&longitude="+longitude,
			                id: 'info'
			            });
		            }); 
			 }
            
        });
        mui('.mui-table-view').on('tap', 'a#endlogistictrip', function (e) {
            swal({
                title: "提示",
                text: "您确定需要结束车次吗？",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: "确定",
                confirmButtonColor: "#ec6c62"
            }, function () {
	            mui.openWindow({
	                url: "logistic.php?type=end",
	                id: 'info'
	            });
            });
        });
        mui('.mui-table-view').on('tap', 'a.link', function (e) {
            mui.openWindow({
                url: this.getAttribute('href'),
                id: 'info'
            });
        });
    });
    {/literal}
</script>
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
			Zepto('#longitude').val(lng);
			Zepto('#latitude').val(lat);  
        }
    });  
} 
  
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
				//Zepto('#longitude').val(longitude);
				//Zepto('#latitude').val(latitude);
				weixin_to_baidu(latitude, longitude);
			  },
              cancel: function (res) {
  				    Zepto('#longitude').val('0');
  				    Zepto('#latitude').val('0');  
              }
        }); 
});
{/literal}		
 </script>	
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>