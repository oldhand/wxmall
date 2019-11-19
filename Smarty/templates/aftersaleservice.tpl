 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>售后服务</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
	<link href="public/css/sweetalert.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>
	<script type="text/javascript" src="public/js/sweetalert.min.js"></script> 
	<style>
	  {literal} 
		  .img-responsive { display: block; height: auto; width: 100%; } 
   		  
		  .mui-table-view-cell .mui-table-view-label
		  {
		  	    width:60px;
		  		text-align:right;
		  		display:inline-block;
		  } 
		  .totalprice {color:#CF2D28; font-size:1.2em; font-weight:500; }
	  
		  .ordersn { font-size:1.2em; font-weight:500; }
		  
	 	  #orders_products .mui-table-view .mui-media-object {
		 	   line-height: 84px;
		 	   max-width: 84px;
		 	   height: 84px;
	 	  }
		  #orders_products .mui-ellipsis {
		  	  line-height: 17px; 
		  }
	 	 .mui-numbox { 
	 	   width: 120px;
	 	   height: 30px; 
	 	   padding: 0 40px 0 40px; 
	 	 }
		  .mui-bar-tab .mui-tab-item .mui-icon {
		  	  width: auto;
		  } 
		  .mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
		   	  color: #cc3300; 
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
					 <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title" id="pagetitle">售后服务</h1>
				</header> 
				{/if}
		 		 {if $orderinfo.returnedgoodsapplyid eq '' && $orderinfo.autosettlement neq 'timeout'}
	 		 		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">  
	 		 			<a class="mui-tab-item save" href="#">  
	 		 				<span class="mui-icon iconfont icon-save button-color">&nbsp;<span style="font-size:20px;">保存</span></span>
	 		 			</a>
	 		 		</nav>
				 {else}
	 		 		<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">  
	 		 			<a class="mui-tab-item" href="orders_aftersaleservice.php" >  
	 		 				<span class="mui-icon iconfont icon-queren01 button-color">&nbsp;<span style="font-size:20px;">确认</span></span>
	 		 			</a>
	 		 		</nav>
				 {/if} 
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}"> 
		                <div class="mui-scroll"> 
							 <form  method="post" name="frm" action="aftersaleservice.php" >
							 <input name="type"  value="submit" type="hidden" > 
							 <input id="orderid" name="record"  value="{$orderinfo.orderid}" type="hidden" >  
							 <input id="localids" name="localids"  value="" type="hidden" > 
							 <input id="imagesserverids" name="imagesserverids"  value="" type="hidden" >
							 <div class="mui-card" style="margin: 0 3px;">  
							         <ul class="mui-table-view"> 
				                                <li class="mui-table-view-cell"> 
														<div class="mui-media-body  mui-pull-left">
															<span class="mui-table-view-label">订单号：</span><span class="ordersn">{$orderinfo.order_no}</span><br>
															<span class="mui-table-view-label">订单状态：</span><span class="ordersn">{$orderinfo.order_status}</span><br> 
															{if $orderinfo.returnedgoodsapplyid neq ''}
															     <span class="mui-table-view-label">退货状态：</span><span class="ordersn">{$orderinfo.returnedgoodsapplysstatus}</span><br> 
															     <span class="mui-table-view-label" style="width:84px;">退货提交时间：</span><span class="ordersn">{$orderinfo.aftersaleservices_time}</span><br> 
															    {if $orderinfo.returnedgoodsapplysstatus eq '已退货' || $orderinfo.returnedgoodsapplysstatus eq '已退款' || $orderinfo.returnedgoodsapplysstatus eq '退货中'}
																 <span class="mui-table-view-label">退货金额：</span><span class="ordersn">{$orderinfo.returnedgoodsamount}</span><br> 
															     <span class="mui-table-view-label">退货数量：</span><span class="ordersn">{$orderinfo.returnedgoodsquantity}</span><br> 
																 {/if}
															     <span class="mui-table-view-label" style="width:72px;">退货操作人：</span><span class="ordersn">{$orderinfo.operator}</span><br> 
															{/if}
														</div>  
				                                </li>  
												{if $orderinfo.delivery neq ''}
													<li class="mui-table-view-cell"> 
															<div class="mui-media-body"> 
																<span class="mui-table-view-label">发货时间：</span><span class="ordersn">{$orderinfo.deliverytime}</span><br>
																<span class="mui-table-view-label">物流公司：</span><span class="ordersn">{$orderinfo.deliveryname}</span><br>
																<span class="mui-table-view-label">发货单号：</span><span class="ordersn">{$orderinfo.invoicenumber}</span><br>
															</div> 
					                                </li>
												{/if}
												{if $orderinfo.confirmreceipt eq 'receipt'}
													
												    {if $orderinfo.autosettlement eq 'timeout'}
														<li class="mui-table-view-cell"> 
																<div class="mui-media-body" style="color:red;text-align:center;"> 
																	订单已经超过允许的退货期限，您已经不能退货了。
																</div> 
						                                </li>
													{else} 
														<li class="mui-table-view-cell"> 
																<div class="mui-media-body" style="color:red;text-align:center;"> 
																	{$orderinfo.autosettlement}
																</div> 
						                                </li>
													{/if}
												{/if}
												
												{if $orderinfo.tipmsg neq ''}
													<li class="mui-table-view-cell"> 
															<div class="mui-media-body" style="color:red;text-align:center;"> 
																{$orderinfo.tipmsg}
															</div> 
					                                </li>
												{/if}
									 </ul>  
						     </div>
							 <div class="mui-card" style="margin: 0 3px;margin-top: 5px;" id="orders_products"> 
									 <ul class="mui-table-view mui-table-view-chevron" style="color: #333;">
										  {foreach name="orders_products" item=orders_products_info  from=$orderinfo.orders_products}
			  									<li class="mui-table-view-cell mui-left" style="height:104px;"> 
	  											        <img class="mui-media-object mui-pull-left"  src="{$orders_products_info.productthumbnail}">
	  													<div class="mui-media-body">
															<p class='mui-ellipsis' style="color:#333">{$orders_products_info.productname}</p> 
	  														<p class='mui-ellipsis'>属性：{$orders_products_info.propertydesc}</p>
															<p class='mui-ellipsis'>数量：{$orders_products_info.quantity}件</p> 
															{if $orderinfo.returnedgoodsapplyid eq ''}
	  														<p class='mui-ellipsis'>
		  														<div class='mui-ellipsis' style="margin-top: 4px;">
		  															<div class="mui-numbox" data-numbox-step='1' data-numbox-min='0' data-numbox-max='{$orders_products_info.quantity}'>
		  											  					<button class="mui-btn mui-numbox-btn-minus" type="button">-</button> 
		  											  					<input  readonly  value="0" id="qty_item_{$orders_products_info.id}" name="qty_item_{$orders_products_info.id}" class="mui-numbox-input" type="number" />
		  											  					<button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
		  											  				</div>
		  														</div>
															</p>
															{else}
															    <p class='mui-ellipsis'>单价：<span class="price">¥{$orders_products_info.shop_price}</span></p> 
																<p class='mui-ellipsis'>退货数量：{$orders_products_info.returnedgoodsquantity}件</p> 
															{/if}
	  													</div> 
			  									</li> 
								   		  {/foreach}
							 	 	</ul> 
							</div>
							 <div class="mui-card" style="margin: 0 3px;margin-top: 5px;"> 
								 <ul class="mui-table-view" style="color: #333;">
									{if $orderinfo.returnedgoodsapplyid eq ''}
					 					<li class="mui-input-row" style="margin-top:3px;">
					 						<div class="mui-media-body mui-pull-left" style="width:80%;padding-left: 13px">
												<textarea  style="margin-bottom: 0px;padding: 5px;font-size: 15px;" class="mui-input-clear required" placeholder="亲，您一定要详细说明退货理由！" id="reason" name="reason" rows="2" ></textarea>
								 			</div>
					 						<div class="mui-media-body  mui-pull-right" style="width:20%;padding:5px;">
								 		        <button id="chooseimage" type="button" class="mui-btn mui-btn-success" style="width:100%;"><span class="mui-icon iconfont icon-camera" style="font-size: 1.5em;"></span></button>
			   			 					 </div>
					 					</li>
			                            <li class="mui-table-view-cell" id="msg-wrap" style="display:none;"> 
												<div class="mui-media-body" id="msg-wrap-body" style="color:red;text-align:center;"> 
									  
												</div>  
			                            </li>
										<li class="mui-table-view-cell" id="chooseimage-wrap" style="display:none;"> 
												    <ul class="mui-table-view" id="chooseimage-img"> 
													<ul> 
										</li>
									{else}
		   			 					<li class="mui-input-row" style="margin-top:3px;">
		   			 						<div class="mui-media-body mui-pull-left" style="width:100%;padding-left: 13px;padding-right: 13px">
												<textarea  style="margin-bottom: 0px;padding: 5px;font-size: 15px;" disabled class="mui-input-clear required"  id="reason" name="reason" rows="2" >{$orderinfo.reason}</textarea>
								 			</div> 
		   			 					</li>
									 {assign var="images" value=$orderinfo.images}
									 {if $images|@count gt 0}
										<li class="mui-table-view-cell" >
											<h5 class="mui-content-padded">有图【{$images|@count}张】：</h5>
										    <ul class="mui-table-view">
  												 	 {foreach name="images" item=image_info  from=$images}
														<li class="mui-media-body mui-pull-left" style="width:100%;padding:3px;">
							   							       <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="{$image_info}">
													 	</li>
  													 {/foreach} 
											<ul>
										</li> 
									 {/if}
									{/if}
								</ul> 
							</div>
							 <div class="mui-card" style="margin: 0 3px;margin-top: 5px;"> 
									<ul class="mui-table-view" style="background-color: #efeff4;">
		                                <li class="mui-table-view-cell"> 
												<div class="mui-media-body" style="color:red;text-align:left;"> 
							  		                <strong>关于退货</strong><br>
							  		                1. 此流程只接受退换货售后需求；<br>
							  		                2. 退货申请提交后，商家将会主动联系您；<br>
							  		                3. 如72小时内无响应，将自动全额退款给您；<br>
													4. 退货流程只能执行一次，多次退货请联系商家。
												</div>  
		                                </li>
										<li class="mui-table-view-cell mui-media"> 
												<img class="img-responsive" src="/images/baozhang.png"> 
										</li>
									</ul>
							</div> 
						</form>
					 </div> 
				</div>
		    </div>
	    </div>   
	      
	<script type="text/javascript"> 
		var ordersproductids = {$ordersproductids};
	{literal}	
		var localids = [];
		var imagesserverids = [];
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
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
			mui('.mui-bar').on('tap','a.save',function(e){ 
				aftersaleservice_submit();
			});   
			mui('.mui-table-view').on('tap','button#chooseimage',function(e){ 
			   	 wx.chooseImage({
			   	     count: 1, // 默认9
			   	     sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
			   	     sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
			   	     success: function (res) {
			   	         localids = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片 
						 $("#chooseimage-img").html('');
						 var imgdiv = $("#chooseimage-img"); 
						 if (localids.length > 6)
						 {
						 	 $("#msg-wrap").css("display","");
							 $("#msg-wrap-body").html("您选定照片不能超过6个！");
							  $("#chooseimage-wrap").css("display","none");
							 return;
						 }
						 for(var i=0;i<localids.length;i++)
						 {
							 var html = '<li class="mui-media-body mui-pull-left" style="width:90px;height: 90px;padding:3px;">';
							    html += '<img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="'+localids[i]+'">';
						 	    html += '</li>';
							 imgdiv.append(html);
						 }
						 $("#chooseimage-wrap").css("display","");
					 	 $("#msg-wrap").css("display","none");
						 $("#msg-wrap-body").html("");
							 
			   	     }
			   	 });
			});  
		}); 
   	 function aftersaleservice_submit()
   	 {
   		 var orderid = Zepto("#orderid").val(); 
		 var reason = Zepto("#reason").val(); 
		 if (reason == "")
		 {
	 	    swal({
	 	         title: "提示", 
	 	         text: "退货理由您还没有填写！", 
	 	         type: "warning", 
	 	       }, function() { });
			 return;
		 }
		 var product_qty = false;
         for(var i=0;i<ordersproductids.length;i++)
         { 
			 var qty_item = Zepto('#qty_item_'+ordersproductids[i]).val(); 
             if( parseInt(qty_item,10) > 0 ) 
             { 
                product_qty = true;
             }
         } 
		 if (!product_qty)
		 {
 	 	     swal({
 	 	         title: "提示", 
 	 	         text: "您至少需要退一件商品！", 
 	 	         type: "warning", 
 	 	       }, function() { });
			 return;
		 }
	     swal({
	         title: "提示", 
	         text: "您确定需要退货吗？", 
	         type: "warning",
	         showCancelButton: true,
	         closeOnConfirm: true,
	         confirmButtonText: "确定需要",
	         confirmButtonColor: "#ec6c62"
	       }, function() { 
				  upload(0); 
	       }); 
   	 }  
 
   	 function upload(pos)
   	 { 
   		 if (localids.length != 0)
   		 {
   		     localid = localids[pos]; 
   	         wx.uploadImage({
   	           localId: localid, // 需要上传的图片的本地ID，由chooseImage接口获得
   	           isShowProgressTips: 1, // 默认为1，显示进度提示
   	           success: function (res) {
   	             var serverId = res.serverId; // 返回图片的服务器端ID 
   				 imagesserverids.push(res.serverId);
   				 if (pos == localids.length-1)
   				 {
   					$("#imagesserverids").val(JSON.stringify(imagesserverids)); 
   					//alert(JSON.stringify(imagesserverids))
   				 	document.frm.submit();
   				 }
   				 else
   				 {
   					 upload(pos+1);
   				 }
   	           },
   	           fail:function(res){ 
   			 	  $("#msg-wrap").css("display","");
   				  $("#msg-wrap-body").html(res.errMsg);
   				  $("#chooseimage-wrap").css("display","none");
   	             // alert(JSON.stringify(res))
   	           }
   	         });
   		 }
   		 else
   		 { 
   		 	document.frm.submit();
   		 }
		    
   	}  
	{/literal} 
	</script>
<script type="text/javascript">
wx.config(
    {ldelim}
        //debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{$share_info.appid}', // 必填，公众号的唯一标识
        timestamp:'{$share_info.timestamp}', // 必填，生成签名的时间戳
        nonceStr: '{$share_info.noncestr}', // 必填，生成签名的随机串
        signature: '{$share_info.signature}',// 必填，签名，见附录1
        jsApiList: ['chooseImage','uploadImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    {rdelim}); 

{literal}		  
wx.ready(function()
{ 
	 wx.hideOptionMenu();  
});
{/literal} 
</script>
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>