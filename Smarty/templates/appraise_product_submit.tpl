 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>{if $appraise_info.praiseid eq ''}评价{else}查看评价{/if}</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
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
		  .price {color:#CF2D28; }
	  
		  .ordersn { font-size:1.2em; font-weight:500; } 
		  
	 	 .btn {
	 	   display: inline-block;
	 	   padding: 6px 12px;
	 	   margin-bottom: 0; 
	 	   font-weight: normal;
	 	   line-height: 1.428571429;
	 	   text-align: center;
	 	   white-space: nowrap;
	 	   vertical-align: middle;
	 	   cursor: pointer;
	 	   background-image: none;
	 	   border: 1px solid transparent;
	 	   border-radius: 4px;
	 	   -webkit-user-select: none;
	 	   -moz-user-select: none;
	 	   -ms-user-select: none;
	 	   -o-user-select: none;
	 	   user-select: none;
	 	 }
	 	 .btn-default {
	 	   color: #333;
	 	   background-color: #fff;
	 	   border-color: #ccc;
	 	 }
	 	  .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active  {
	 	   color: #333;
	 	   background-color: #ebebeb;
	 	   border-color: #e53348;
	 	   background: url(images/checked.png) no-repeat bottom right;
	 	 }
		 #radiogroup .mui-icon
		 {
			 font-size: 1.2em; 
			 color:#fe4401; 
		 }
	 {/literal} 
	 {if $appraise_info.praiseid eq ''}
		 {literal} 
			  .mui-bar-tab .mui-tab-item .mui-icon {
			  	  width: auto;
			  } 
			  .mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
			   	  color: #cc3300; 
			  } 
		 {/literal} 
	 {/if}
	</style>
	{include file='theme.tpl'} 
</head>

	<body>
		<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper"> 
			<div class="mui-inner-wrap">
				{if $supplier_info.showheader eq '0'}
				<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
					 <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title" id="pagetitle">{if $appraise_info.praiseid eq ''}评价{else}查看评价{/if}</h1>
				</header> 
				{/if}  
				{if $appraise_info.praiseid eq ''}
				<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">  
					<a class="mui-tab-item save" href="#">  
						<span class="mui-icon iconfont icon-save button-color">&nbsp;<span style="font-size:20px;">提交评价</span></span>
					</a>
				</nav> 
				{else}
				     {include file='footer.tpl'}   
				{/if}
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}"> 
		                <div class="mui-scroll">  
							<div class="mui-card" style="margin: 0 3px;"> 
								 <form  method="post" name="frm" action="appraise_product_submit.php" >
								 <input name="type"  value="submit" type="hidden" > 
								 <input id="orderid" name="orderid"  value="{$appraise_info.orderid}" type="hidden" > 
								 <input id="productid" name="productid"  value="{$appraise_info.productid}" type="hidden" > 
								 <input id="orders_productid" name="record"  value="{$appraise_info.id}" type="hidden" >
								 <input id="praiseid" name="praiseid"  value="{$appraise_info.praiseid}" type="hidden" > 
								 <input id="localids" name="localids"  value="" type="hidden" > 
								 <input id="imagesserverids" name="imagesserverids"  value="" type="hidden" >  
								  
						         <ul class="mui-table-view">  
											<li class="mui-table-view-cell" style="height:64px;"> 
											       <img class="mui-media-object mui-pull-left" src="{$appraise_info.productthumbnail}">
													<div class="mui-media-body mui-pull-left">
														<p class='mui-ellipsis' style="color:#333">{$appraise_info.productname}</p>  
														<p class='mui-ellipsis' style="color:#333">单价：<span class="price">¥{$appraise_info.shop_price}</span></p> 
													</div>  
											</li>  
			                                 
			                                 
											 {if $appraise_info.praiseid eq ''}
						                        <li class="mui-table-view-cell" id="praisegroup">
						                        <div class="yanse_xz" id="radiogroup" style="text-align:center;">  
						                            <a class="btn btn-default active radiogroup" data-id="1" href="javascript:;" >
														<span class="mui-icon iconfont icon-pingjiamanyi" style="font-size: 1.5em;">好评</span>
						                                <div style="display:none">
						                                    <input class="praise" checked id="praise_1" type="radio" name="praise" value="1" />
						                                </div>
						                            </a> 
						                            <a class="btn btn-default radiogroup" data-id="2" href="javascript:;" >
														<span class="mui-icon iconfont icon-pingjiayiban" style="font-size: 1.5em;">中评</span>
						                                <div style="display:none">
						                                    <input class="praise" id="praise_2" type="radio" name="praise" value="2" />
						                                </div>
						                            </a>
						                            <a class="btn btn-default radiogroup" data-id="3" href="javascript:;" >
														<span class="mui-icon iconfont icon-pingjiabumanyi " style="font-size: 1.5em;">差评</span>
						                                <div style="display:none">
						                                    <input class="praise" id="praise_3" type="radio" name="praise" value="3" />
						                                </div>
						                            </a>
											    </div>
												</li> 
				   			 					<li class="mui-input-row" style="margin-top:3px;">
				   			 						<div class="mui-media-body mui-pull-left" style="width:80%;padding-left: 13px">
														<textarea  style="margin-bottom: 0px;padding: 5px;font-size: 15px;" class="mui-input-clear required" placeholder="亲，写点什么吧，您的意见对其他朋友有很大帮助！" id="remark" name="remark" rows="2" ></textarea>
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
	 															<!--<li class="mui-media-body mui-pull-left" style="width:90px;height: 90px;padding:3px;">
	    							   							       <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="/storage/cs/2015/July/20150714155114904.JPG">
	 														 	</li>
	 															<li class="mui-media-body mui-pull-left" style="width:90px;padding:3px;">
	    							   							       <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="/storage/cs/2015/July/20150714155114904.JPG">
	 														 	</li>
	 															<li class="mui-media-body mui-pull-left" style="width:90px;padding:3px;">
	    							   							       <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="/storage/cs/2015/July/20150714155114904.JPG">
	 														 	</li>
	 															<li class="mui-media-body mui-pull-left" style="width:90px;padding:3px;">
	    							   							       <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="/storage/cs/2015/July/20150714155114904.JPG">
	 														 	</li>
	 															<li class="mui-media-body mui-pull-left" style="width:90px;padding:3px;">
	    							   							       <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="/storage/cs/2015/July/20150714155114904.JPG">
	 														 	</li>-->
															<ul> 
												</li>
											{else}
												 <li class="mui-table-view-cell" >
							                        <div class="yanse_xz" style="text-align:center;">  
							                            <a class="btn btn-default {if $appraise_info.praise eq '1'}active{/if}" data-id="1" href="javascript:;" >
															<span class="mui-icon iconfont icon-pingjiamanyi" style="font-size: 1.5em;">好评</span>
							                                <div style="display:none">
							                                    <input class="praise" {if $appraise_info.praise eq '1'}checked{/if} id="praise_1" type="radio" name="praise" value="1" />
							                                </div>
							                            </a> 
							                            <a class="btn btn-default {if $appraise_info.praise eq '2'}active{/if}" data-id="2" href="javascript:;" >
															<span class="mui-icon iconfont icon-pingjiayiban" style="font-size: 1.5em;">中评</span>
							                                <div style="display:none">
							                                    <input class="praise" {if $appraise_info.praise eq '2'}checked{/if} id="praise_2" type="radio" name="praise" value="2" />
							                                </div>
							                            </a>
							                            <a class="btn btn-default {if $appraise_info.praise eq '3'}active{/if}" data-id="3" href="javascript:;" >
															<span class="mui-icon iconfont icon-pingjiabumanyi " style="font-size: 1.5em;">差评</span>
							                                <div style="display:none">
							                                    <input class="praise"  {if $appraise_info.praise eq '3'}checked{/if} id="praise_3" type="radio" name="praise" value="3" />
							                                </div>
							                            </a>
												    </div>
												</li> 
												{if $appraise_info.remark neq ''}
					   			 					<li class="mui-input-row" style="margin-top:3px;">
					   			 						<div class="mui-media-body mui-pull-left" style="width:100%;padding-left: 13px;padding-right: 13px">
															<textarea  style="margin-bottom: 0px;padding: 5px;font-size: 15px;" disabled class="mui-input-clear required"  id="remark" name="remark" rows="2" >{$appraise_info.remark}</textarea>
											 			</div> 
					   			 					</li>
												{/if}
												 {assign var="images" value=$appraise_info.images}
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
							     </form> 
					    </div>
						 
						<div class="mui-card" style="margin: 0 3px;margin-top: 5px;"> 
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
	    var mask = null;  
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
				praisesubmit();
			});  
			mui('#radiogroup').on('tap', 'a', function(e) {  
				var praise =  this.getAttribute('data-id');
				Zepto(".radiogroup").removeClass("active"); 
				Zepto(this).addClass("active"); 
				
				Zepto(".praise").attr("checked",false); 
				Zepto(".praise").prop("checked",false); 
				Zepto("#praise_"+praise).attr("checked",true);  
				Zepto("#praise_"+praise).prop("checked",true);  
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
							 //alert(localids[i]);
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
	 function praisesubmit()
	 {
		 var orderid = Zepto("#orderid").val();
		 var localids = Zepto("#localids").val();
		 var productid = Zepto("#productid").val();
		 var orders_productid = Zepto("#orders_productid").val();
		 upload(0); 
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