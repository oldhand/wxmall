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
		.logisticbills
		{
			color:#333;
			margin-left:0px;
			margin-right:10px;
		}
        .paymentsuccess
		  {
			   color: #008537;
			   font-weight:900;
			   font-size: 1.8em; 
			   height:30px;
			   line-height: 30px;
		  }
	   .icon-paymentsuccess
		  {
			   color: #008537; 
			   font-size: 3.8em;  
			   padding-top: 5px;
		  }
        {/literal}
    </style>
    {include file='theme.tpl'}
</head>

<body>
 
    <div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
        <header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
            <a href="logistic.php" class="mui-icon mui-icon-back mui-pull-left"></a> 
		    <h1 class="mui-title" id="pagetitle">{$logistictrip_info.mall_logistictrips_no}</h1>
        </header> 
		{/if} 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                  
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                        	 	<div class="mui-media-body">    
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
              
                
				<h5 class="mui-content-padded" style="margin: 5px 5px;">已经揽件列表</h5>  
				{assign var="logisticbill_info" value=$logistictrip_info.logisticbills}
				{if $logisticbill_info|@count gt 0}
	        	 	{foreach name="logisticbill_info" item=logisticbill_info key=vendorid from=$logisticbill_info}
						<div class="mui-card" style="margin: 3px 3px;">
		                    <ul  class="mui-table-view" style="padding-top: 5px;">  
								<li class="mui-table-view-cell order-link-cell">
									<div class="mui-media-body  mui-pull-left"> 供应商：{$logisticbill_info.vendorname}</div>
									{if $logistictrip_info.logistictripstatus eq '0' || $logistictrip_info.logistictripstatus eq '1'}
									<div class="mui-media-body mui-pull-right">
											<a class="deletevendor  button-color" data-id="{$vendorid}" data-vendorname="{$logisticbill_info.vendorname}" href="javascript:;"><span class="mui-icon iconfont icon-shanchu" ></span>删除</a> 
									</div>
									{/if}
								</li> 
						 
		                        <li class="mui-table-view-cell"> 
		                        	 	<div class="mui-media-body">   
			                        	 	{foreach name="logisticbills" item=logisticbill_item_info key=billid from=$logisticbill_info.bills}
			                        	 			<p class='mui-pull-left' style="padding-right:5px;" ><a class="logisticbill" data-id="{$billid}" href="javascript:;">{$logisticbill_item_info.logisticbills_no}【{$logisticbill_item_info.mall_logisticbillsstatus}】</a></p>  
			                        	 	{/foreach} 
		                                </div> 
		                        </li>
		                    </ul>
		                </div>
	        	 	{/foreach}
	            {else}
	           		<div class="mui-card" style="margin: 0 3px;">
	                       <ul class="mui-table-view">   
				                    <li class="mui-table-view-cell" id="paymentsuccess-wrap"> 
									        <div class="mui-media-body">
												<p class='mui-ellipsis' style="color:#333">提示:</p> 
												<p class='mui-ellipsis paymentsuccess'>还没有进行揽件！</p>  
											</div> 
				                     </li>
						   </ul>     
	                </div>
                {/if}
			    {if $logistictrip_info.logistictripstatus eq '0' || $logistictrip_info.logistictripstatus eq '1'}
					<h5 class="mui-content-padded" style="margin: 5px 5px;">等待揽件列表</h5>  
					{assign var="logisticbill_info" value=$logistictrip_info.bills}
					{if $logisticbill_info|@count gt 0}
		        	 	{foreach name="logisticbill_info" item=logisticbill_info key=vendorid from=$logisticbill_info}
							<div class="mui-card" style="margin: 3px 3px;">
			                    <ul  class="mui-table-view" style="padding-top: 5px;">  
									<li class="mui-table-view-cell order-link-cell">
										<div class="mui-media-body  mui-pull-left"> 供应商：{$logisticbill_info.vendorname}</div>
										<div class="mui-media-body mui-pull-right">
												<a class="embrace  button-color" data-id="{$vendorid}" data-vendorname="{$logisticbill_info.vendorname}" href="javascript:;"><span class="mui-icon iconfont icon-dianji" ></span>揽件</a> 
										</div>
									</li> 
						 
			                        <li class="mui-table-view-cell"> 
			                        	 	<div class="mui-media-body">   
				                        	 	{foreach name="logisticbills" item=logisticbill_item_info key=billid from=$logisticbill_info.bills}
				                        	 			<p class='mui-pull-left' style="padding-right:5px;" ><a class="logisticbill" data-id="{$billid}" href="javascript:;">{$logisticbill_item_info.logisticbills_no}【{$logisticbill_item_info.mall_logisticbillsstatus}】</a></p>  
				                        	 	{/foreach} 
			                                </div> 
			                        </li>
			                    </ul>
			                </div>
		        	 	{/foreach} 
		            {else}
		           		<div class="mui-card" style="margin: 0 3px;">
		                       <ul class="mui-table-view">   
							                    <li class="mui-table-view-cell" id="paymentsuccess-wrap"> 
	  											        <div class="mui-media-body">
															<p class='mui-ellipsis' style="color:#333">提示:</p> 
															{if $logistictrip_info.logisticpackageid eq ''}
															<p class='mui-ellipsis paymentsuccess'>请先选择线路！</p>
															{else}
	  														<p class='mui-ellipsis paymentsuccess'>所有包裹已经揽件！</p>  
															{/if}
	  													</div> 
							                     </li>
							   </ul>     
		                </div>
	                {/if}
				{/if}
				<!--
                {if $logistictrip_info.logistictripstatus eq '0' || $logistictrip_info.logistictripstatus eq '1'}
                <div class="mui-card" style="margin: 3px 3px;">
                    <ul  class="mui-table-view" style="text-align:center;">   
                        <li class="mui-table-view-cell">
                  			<a id="scanqrcode" href="javascript:;"><h5 class="show-content" style=""><span class="mui-icon iconfont icon-erweima"></span>  物流扫码</h5></a>
                        </li>
                    </ul>
                </div>
                {/if}
					-->
				{include file='copyright.tpl'} 
            </div>
        </div>

        <!-- end pullrefresh  -->
    </div>
 
  <input type="hidden" id="logisticpackageid" value="{$logistictrip_info.logisticpackageid}"> 
  <input type="hidden" id="logistictripid" value="{$logistictrip_info.logistictripid}"> 

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
		
      
        mui('.mui-table-view').on('tap', 'a#scanqrcode', function (e) {
          	scanqrcode();
        });
        
        mui('.mui-table-view').on('tap', 'a.embrace', function (e) {
             var vendorid = this.getAttribute('data-id');
			 var vendorname = this.getAttribute('data-vendorname');
			 var logisticpackageid = $("#logisticpackageid").val();
			 var logistictripid = $("#logistictripid").val();
             embrace(vendorid,vendorname,logisticpackageid,logistictripid); 
        });
        mui('.mui-table-view').on('tap', 'a.deletevendor', function (e) {
             var vendorid = this.getAttribute('data-id');
			 var vendorname = this.getAttribute('data-vendorname');
			 var logisticpackageid = $("#logisticpackageid").val();
			 var logistictripid = $("#logistictripid").val();
             deletevendor(vendorid,vendorname,logisticpackageid,logistictripid); 
        });
        
    });
    function deletevendor(vendorid,vendorname,logisticpackageid,logistictripid)
    {
	    swal({
				  title: "是否取消揽件？",
				  text: "您确定给【"+vendorname+"】取消揽件吗？",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonColor: "#DD6B55",
				  confirmButtonText: "确定取消",
				  closeOnConfirm: false
				},
				function(){
				  mui.openWindow({
		                url: "logisticpackage.php?type=deletevendor&vendorid="+vendorid+"&logistictripid="+logistictripid+"&logisticpackageid="+logisticpackageid,
		                id: 'info'
		            });
				});    
	}
    function embrace(vendorid,vendorname,logisticpackageid,logistictripid)
    {
	    swal({
				  title: "是否揽件？",
				  text: "您确定给【"+vendorname+"】揽件吗？",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonColor: "#DD6B55",
				  confirmButtonText: "确定揽件",
				  closeOnConfirm: false
				},
				function(){
				  mui.openWindow({
		                url: "logisticpackage.php?type=embrace&vendorid="+vendorid+"&logistictripid="+logistictripid+"&logisticpackageid="+logisticpackageid,
		                id: 'info'
		            });
				});    
	}
    {/literal}
</script>
	
	
<script type="text/javascript"> 


wx.config(
{ldelim}
   // debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '{$share_info.appid}', // 必填，公众号的唯一标识
    timestamp: {$share_info.timestamp}, // 必填，生成签名的时间戳
    nonceStr: '{$share_info.noncestr}', // 必填，生成签名的随机串
    signature: '{$share_info.signature}',// 必填，签名，见附录1
    jsApiList: ['scanQRCode'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
{rdelim}); 
 
wx.error(function(res)
{ldelim}
    wx.hideOptionMenu(); 
{rdelim}); 

function scanqrcode()
{ldelim} 

	wx.scanQRCode({ldelim}
	    needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	    scanType: ["barCode"], // 可以指定扫二维码还是一维码，默认二者都有 ["qrCode","barCode"]
	    success: function (res) {ldelim}
			//alert(JSON.stringify(res)); 
			var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
			var errMsg = res.errMsg; // scanQRCode:ok
			if (errMsg == "scanQRCode:ok") 
			{ldelim}
				json = eval("(" + result + ")");
				var scan_result = json.scan_code.scan_result; //CODE_128,PKG160717002
				var scan_type = json.scan_code.scan_type;  //barcode
				   mui.ajax({ldelim}
			            type: 'POST',
			            url: "logisticpackage.ajax.php",
			            data: "type=scan&scan_type="+scan_type+"&scan_result="+scan_result,
			            success: function(json) 
			            {ldelim}   
			                var jsondata = eval("("+json+")");
			                if (jsondata.code == 200) 
			                {ldelim} 
			                    swal({ldelim}
								  title: "扫箱包条码成功",
								  text: "【"+jsondata.serialname+"】箱包加入当前车次！",
								  type: "success",
								  timer: 3000,
								  showConfirmButton: false,
								  closeOnConfirm: false, 
								  showCancelButton: true,
								  cancelButtonText: "关闭"
								{rdelim},
								function(){ldelim} 
										setTimeout(function(){ldelim} scanqrcode(); {rdelim}, 100);
								{rdelim});  
								var logisticpackageid = $("#logisticpackageid").val();
								$("#package_"+logisticpackageid).css("display","none");
								logisticpackageid = jsondata.logisticpackageid; 
								$("#logisticpackageid").val(logisticpackageid);
								$("#package_"+logisticpackageid).css("display","");
								
								serialname = jsondata.serialname;
								
								var html="<div class='mui-card' style='margin: 3px 3px;'>"
						+ "<ul  class='mui-table-view' style='padding-top: 5px;'>"  
						+ "		<li class='mui-table-view-cell order-link-cell'>"
						+ "			<div class='mui-media-body  mui-pull-left'>  <span class='mui-icon iconfont icon-xiangbao  menuicon button-color'></span> 箱包【"+serialname+"】<span id='package_"+logisticpackageid+"' style='color:red;'>【选中】</span> </div>"	
						+ "			<div class='mui-media-body mui-pull-right'><a class='deletelogisticpackage  button-color' data-id='"+logisticpackageid+"' href='javascript:;'><span class='mui-icon iconfont icon-shanchu' ></span>删除</a></div>"
						+ "		</li> "
                        + "		<li class='mui-table-view-cell'> "
                        + "	 		<div class='mui-media-body' id='package_container_"+logisticpackageid+"'> </div>"
                        + "		</li>"
                        + "</ul>"
                        + "</div>";
								$("#packages").append(html);
								
								mui('.mui-table-view').on('tap', 'a.deletelogisticpackage', function (e) {ldelim} 
						             var logisticpackageid = this.getAttribute('data-id');
						             deletelogisticpackage(logisticpackageid); 
						        {rdelim});
        
							{rdelim} 
							else if (jsondata.code == 202) 
			                {ldelim} 
			                    swal({ldelim}
								  title: "扫箱包条码成功",
								  text: jsondata.msg,
								  type: "success",
								  timer: 3000,
								  showConfirmButton: false,
								  closeOnConfirm: false, 
								  showCancelButton: true,
								  cancelButtonText: "关闭"
								{rdelim},
								function(){ldelim} 
										setTimeout(function(){ldelim} scanqrcode(); {rdelim}, 100);
								{rdelim});   
										var logisticpackageid = $("#logisticpackageid").val();
										$("#package_"+logisticpackageid).css("display","none");
										logisticpackageid = jsondata.logisticpackageid; 
										$("#logisticpackageid").val(logisticpackageid);
										$("#package_"+logisticpackageid).css("display",""); 
							{rdelim} 
							else if (jsondata.code == 300) 
			                {ldelim} 
			                    swal({ldelim}
								  title: "扫包裹条码成功",
								  text: "【"+jsondata.logisticbills_no+"】包裹加入当前车次！",
								  type: "success",
								  timer: 3000,
								  showConfirmButton: false,
								  closeOnConfirm: false, 
								  showCancelButton: true,
								  cancelButtonText: "关闭"
								{rdelim},
								function(){ldelim}  
										setTimeout(function(){ldelim} scanqrcode(); {rdelim}, 100);
								{rdelim});   
								$("#logisticbill_"+jsondata.logisticbills_no).remove();
								$("#package_container_"+jsondata.logisticpackageid).append("<p id='logisticbill_"+jsondata.logisticbills_no+"' class='logisticbills mui-pull-left'><a href='javascript:;'>"+jsondata.logisticbills_no+"</a></p>"); 
							{rdelim} 
							else
							{ldelim}
							swal({ldelim}
								  title: "错误",
								  text: jsondata.msg,
								  type: "error",
								  timer: 5000,
								  showConfirmButton: false,
								  closeOnConfirm: false, 
								  showCancelButton: true,
								  cancelButtonText: "关闭"
								{rdelim});
								//mui.toast(jsondata.msg);
							{rdelim}
			            {rdelim}
					 {rdelim}); 
			{rdelim}
			
		{rdelim}
    {rdelim});
{rdelim}

wx.ready(function(){ldelim}   
      wx.showOptionMenu();  
{rdelim});  

</script>
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>