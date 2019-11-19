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
    <link href="public/css/wuliu.css" rel="stylesheet"/>
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
	<input id="supplierid" type="hidden" value="{$supplier_info.supplierid}"/>
{if $package_info|@count gt 0}
    <div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
        <header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
             <h1 class="mui-title" id="pagetitle">{$supplier_info.mylogisticname}-线路【{$package_info.serialname}】</h1>
        </header> 
		{/if} 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                 {assign var="logistictrip_info" value=$package_info.logistictrip}
				{if $logistictrip_info|@count gt 0}
	                <div class="mui-card" style="margin: 0 3px;">
	                    <ul class="mui-table-view">
	                        <li class="mui-table-view-cell">
                            	 	<div class="mui-media-body">  
										    <p class='mui-ellipsis' style="color:#333;text-align:center;">【当前执行车次】</p>
	                                        <p class='mui-ellipsis' style="color:#333">车次：{$logistictrip_info.mall_logistictrips_no}</p> 
	                                        <p class='mui-ellipsis' style="color:#333">司机：{$logistictrip_info.drivername}</p> 
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
				<h5 class="mui-content-padded" style="margin: 5px 5px;">已经揽件列表</h5>  
				{assign var="logisticbill_info" value=$package_info.logisticbills}
				{if $logisticbill_info|@count gt 0}
	        	 	{foreach name="logisticbill_info" item=logisticbill_info key=vendorid from=$logisticbill_info}
						<div class="mui-card" style="margin: 3px 3px;">
		                    <ul  class="mui-table-view" style="padding-top: 5px;">  
								<li class="mui-table-view-cell order-link-cell">
									<div class="mui-media-body  mui-pull-left"> 供应商：{$logisticbill_info.vendorname}</div>
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
												<p class='mui-ellipsis paymentsuccess'>还没有进行揽件！</p>  
											</div> 
				                     </li>
						   </ul>     
	                </div>
                {/if}
			    
				<h5 class="mui-content-padded" style="margin: 5px 5px;">等待揽件列表</h5>  
				{assign var="logisticbill_info" value=$package_info.bills}
				{if $logisticbill_info|@count gt 0}
	        	 	{foreach name="logisticbill_info" item=logisticbill_info key=vendorid from=$logisticbill_info}
						<div class="mui-card" style="margin: 3px 3px;">
		                    <ul  class="mui-table-view" style="padding-top: 5px;">  
								<li class="mui-table-view-cell order-link-cell">
									<div class="mui-media-body  mui-pull-left"> 供应商：{$logisticbill_info.vendorname}</div>
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
  														<p class='mui-ellipsis paymentsuccess'>没有需要投递的新包裹！</p>  
  													</div> 
						                     </li>
						   </ul>     
	                </div>
                {/if}  
				 
                {if $package_info.logisticpointreceive  eq 'open'}
                <input id="profileid" type="hidden" value="{$package_info.profileid}"/>
                <input id="packageid" type="hidden" value="{$package_info.packageid}"/>
                <div class="mui-card" style="margin: 3px 3px;">
                    <ul id="orders" class="mui-table-view" style="padding-top: 5px;text-align:center;"> 
                         <li class="mui-table-view-cell">
                            <a id="logisticpointreceive" data-id="{$package_info.packageid}" class="mui-navigate-right">
                                <div class="mui-media-body  mui-pull-left">
                                    <span class="mui-icon iconfont icon-history  menuicon button-color"></span>配送点接收
                                </div>
                                <div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
                                   确认物流到达
                                </div>
                            </a>
                        </li> 
                    </ul>
                </div>
                {/if}
				{include file='copyright.tpl'} 
            </div>
        </div>

        <!-- end pullrefresh  -->
    </div>
{/if} 

{if $bill_info|@count gt 0}
    <div class="mui-inner-wrap">
        <header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
	         {if $showbackaction eq 'true'}
	        	 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
	         {/if}
             <h1 class="mui-title" id="pagetitle">{$supplier_info.mylogisticname}-【{$bill_info.logisticbills_no}】</h1>
        </header> 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: 50px;">
            <div class="mui-scroll">  
	                <div class="mui-card" style="margin: 0 3px;">
	                    <ul class="mui-table-view">
	                        <li class="mui-table-view-cell">
                            	 	<div class="mui-media-body">   
	                                        <p class='mui-ellipsis' style="color:#333">收货人：{$bill_info.consignee}</p> 
	                                        <p class='mui-ellipsis' style="color:#333">手机：{$bill_info.mobile}</p> 
	                                        <p class='mui-ellipsis' style="color:#333">收货地址：{$bill_info.province} {$bill_info.city} {$bill_info.district}</p> 
	                                       <p class='mui-ellipsis' style="color:#333;padding-left:60px;">{$bill_info.address}</p>  										   <p class='mui-ellipsis' style="color:#333">订单号：{$bill_info.mall_orders_no}</p> 
	                                       <p class='mui-ellipsis' style="color:#333">状态：{$bill_info.mall_logisticbillsstatus}</p>										   {if $bill_info.drivername neq ''}
	                                       <p class='mui-ellipsis' style="color:#333">司机：{$bill_info.drivername}</p>
	                                       {/if}
	                                       {if $bill_info.mall_logistictrips_no neq ''}
	                                       <p class='mui-ellipsis' style="color:#333">车次：{$bill_info.mall_logistictrips_no}</p>
	                                       {/if}
	                                       {if $bill_info.serialname neq ''}
	                                       <p class='mui-ellipsis' style="color:#333">箱包：{$bill_info.serialname}</p>
	                                       {/if} 
                                    </div> 
	                        </li>
	                    </ul>
	                </div> 
	             {assign var="logisticroutes" value=$bill_info.logisticroutes}
	             {if $logisticroutes|@count gt 0}
	             <div class="mui-card" style="margin: 0 3px;margin-top: 5px;margin-bottom: 10px;">   
				 <ul id="wuliuinfo_ul" class="mui-table-view" >
						<div class="smart-result"   data-role="content" role="main">
							<div class="content-primary"> 
								<table id="queryResult" cellspacing="0" cellpadding="0">
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
	                        	 	<!--
									<tr class="even first-line">
										<td class="col1"><span class="result-date">2016-06-12</span><span class="result-time">18:11</span></td><td class="colstatus"></td>
										<td class="col2"><span>开封市|收件|开封市【开封市尉氏县】，【段闯/13137840042】已揽收</span></td></tr>
									<tr class="odd">
										<td class="col1"><span class="result-date">2016-06-12</span><span class="result-time">20:40</span></td><td class="colstatus"></td>
										<td class="col2"><span>开封市|发件|开封市【开封市尉氏县】，正发往【郑州转运中心】</span></td></tr>
									<tr class="even">
										<td class="col1"><span class="result-date">2016-06-13</span><span class="result-time">00:01</span></td><td class="colstatus"></td>
										<td class="col2"><span>郑州市|到件|到郑州市【郑州转运中心】</span></td></tr> 
									<tr class="even last-line checked">
										<td class="col1"><span class="result-date">2016-06-14</span><span class="result-time">15:54</span></td><td class="colstatus"></td>
										<td class="col2"><span>长沙市|签收|长沙市【天心区一部】，百事汇通小王代签18975120477 已签收</span></td></tr>-->
										</table>
							</div>
						</div>
					</ul>
				</div>
				{/if}
				{include file='copyright.tpl'} 
            </div>
        </div>

        <!-- end pullrefresh  -->
    </div>
{/if} 

{if $type eq 'logisticpointreceive'}
    <div class="mui-inner-wrap">
        <header class="mui-bar mui-bar-nav" style="padding-right: 15px;"> 
	         <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
	         <h1 class="mui-title" id="pagetitle">{$supplier_info.mylogisticname}</h1>
        </header> 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: 50px;">
            <div class="mui-scroll">  
	                <div class="mui-card" style="margin: 0 3px;">
	                       <ul class="mui-table-view">   
						                    <li class="mui-table-view-cell" id="paymentsuccess-wrap"> 
  											        <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-dingdanchenggong icon-paymentsuccess"></span></div>
													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">配送点操作:</p> 
  														<p class='mui-ellipsis paymentsuccess'>箱包已经确认接收！</p>  
  													</div> 
						                     </li>
						   </ul>     
	                </div>  
				{include file='copyright.tpl'} 
            </div>
        </div>

        <!-- end pullrefresh  -->
    </div>
{/if} 

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
        mui('.mui-table-view').on('tap', 'a.logisticbill', function (e) {
	        var supplierid = Zepto('#supplierid').val();
	        var billid =  this.getAttribute('data-id'); 
            mui.openWindow({
                url: "logisticinfo.php?supplierid="+supplierid+"&billid="+billid,
                id: 'info'
            });
        });
         mui('.mui-table-view').on('tap', 'a#logisticpointreceive', function (e) {
	        var supplierid = Zepto('#supplierid').val();
	        var packageid =  this.getAttribute('data-id'); 
	         swal({
		                title: "提示",
		                text: "您确定当前箱包已经到达您的配送点吗？",
		                type: "warning",
		                showCancelButton: true,
		                closeOnConfirm: true,
		                confirmButtonText: "确定",
		                confirmButtonColor: "#ec6c62"
		            }, function () {
			            var supplierid = $("#supplierid").val();
			            var profileid = $("#profileid").val();
			            var packageid = $("#packageid").val();
			            mui.openWindow({
			                url: "logisticinfo.php?type=logisticpointreceive&supplierid="+supplierid+"&profileid="+profileid+"&packageid="+packageid ,
			                id: 'info'
			            });
		            });  
        });
        
    });
    {/literal}
</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>