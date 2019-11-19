 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
    <link href="public/css/iconfont.css" rel="stylesheet" />

    <link href="public/css/sweetalert.css" rel="stylesheet"/>
	  
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script> 
	
	<script type="text/javascript" src="public/js/sweetalert.min.js"></script>
	
	
	<style>
	{literal}
	 .name,.detail{ -webkit-margin-start:0px;  }
	 .img-responsive { display: block; height: auto; width: 100%; } 
	 .price1 {text-decoration:line-through; color:#000;  }  
	 .price3 {text-decoration:line-through; color:#999;  } 
	 .price2 {padding-left:5px;color:#CF2D28; font-size:1.2em; font-weight:500; }
	 .price1 span,.price2 span,span.price{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; margin-left:0}
	 .price2 span {font-size:1.1em}
	 
	 .totalprice{
	   color:#CF2D28; 
	   margin-top: 9px;
	 }
	 #inventory_label
	 {
		color:#CF2D28; 
		font-size: 16px;
	 } 
 	 
	.mui-table-view-cell:after { 
	  left: 0px; 
	}
	{/literal}
	</style>
	{include file='theme.tpl'}  
</head>

<body> 
 
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
            <!-- 主页面标题 -->
			{if $supplier_info.showheader eq '0'}
            <header class="mui-bar mui-bar-nav"> 
                <h1 class="mui-title">{$productinfo.share_givenname}请您帮忙砍价</h1>
            </header> 
            {/if}   
            <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                <div class="mui-scroll">
                    <!-- 主界面具体展示内容 -->
			         <div  style="margin: 5px 5px;"> 
							 <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" id="productlogo" src="{$productinfo.productlogo}">
				     </div>  
                    <!--detail-->
					<input type="hidden" id="productid" value="{$productinfo.productid}">
					<input type="hidden" id="salesactivityid" value="{$productinfo.salesactivityid}">
                    <div class="mui-content-padded">
                        <p style="margin-bottom: 0;">
                            <span class="detail-title">{$productinfo.productname}</span>
                        </p>
                        <p class="info" style="height:34px;"> 
							<span class="price">底价：￥<b style="font-size: 32px;" id="shop_price">{$productinfo.total_money}</b>元</span>
						 </p>   
						<ul id="propertygroup" class="mui-table-view" style="background: #f3f3f3;"> 
						 	<li class="mui-table-view-cell" >
								 <span class="mui-pull-left">目前价格：￥<span class="price" style="font-size: 16px;color:#CF2D28; ">{$productinfo.promotional_price}</span>元</span> 
							</li> 
							{if $productinfo.bargain  eq '0'}
						 	<li class="mui-table-view-cell" >
								 <span class="mui-pull-left" style="color:#CF2D28; font-size:1.2em; font-weight:500;">已有{$productinfo.bargaincount}位亲友帮我砍价了，当前价格为{$productinfo.promotional_price}元，请您高抬贵手，帮忙点一下喽！</span> 
							</li>
							<li class="mui-table-view-cell" >
 								 <span class="mui-pull-left" style="padding-left:20px;">
 									<button id="help_bargain" type="button" class="mui-btn mui-btn-success mui-btn-outlined">
 										<span class="mui-icon iconfont icon-bargain" style="padding-right:5px;"></span>帮忙砍价
 									</button>
 								 </span>
 			                     <span class="mui-pull-right" style="padding-right:20px;">
 					 				<button id="refuse_help" type="button" class="mui-btn mui-btn-danger mui-btn-outlined" >
 					 					<span class="mui-icon iconfont icon-jujue" style="padding-right:5px;"></span>拒绝帮忙
 					 				</button>
 							     </span>
 							</li>
							{elseif $productinfo.bargain  eq '1'}
							 	<li class="mui-table-view-cell" style="text-align:center;">
									 <span style="color:#CF2D28; font-size:1.2em; font-weight:500;">您已经帮我砍过价了！感谢！</span> 
								</li>
								<li class="mui-table-view-cell" style="text-align:center;">
								  <span>
									<button id="join_bargain" type="button" class="mui-btn mui-btn-success mui-btn-outlined">
 										<span class="mui-icon iconfont icon-bargain" style="padding-right:5px;"></span>我也要参加活动
 									</button> 
 								  </span>	
								</li>
							{elseif $productinfo.bargain  eq '2'}
							 	<li class="mui-table-view-cell" style="text-align:center;">
									 <span style="color:#CF2D28; font-size:1.2em; font-weight:500;">哼！你记着！下次我也不会帮你！</span> 
								</li>
								<li class="mui-table-view-cell" style="text-align:center;">
								  <span>
									<button id="join_bargain" type="button" class="mui-btn mui-btn-success mui-btn-outlined">
 										<span class="mui-icon iconfont icon-bargain" style="padding-right:5px;"></span>我也要参加活动
 									</button> 
 								  </span>	
								</li>
							{else}
								<li class="mui-table-view-cell" style="text-align:center;">
									 <span style="color:#CF2D28; font-size:1.2em; font-weight:500;">不能砍自己呵，还是请你的好友来吧！</span> 
								</li>
							{/if}
						 	<li class="mui-table-view-cell" >
								 <span class="mui-pull-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原价 : <span class="price1">￥<span id="old_shop_price" style="color:#CF2D28;">{$productinfo.shop_price}</span>元</span> 
							</li>
							<li class="mui-table-view-cell" >
								 <span class="mui-pull-left">&nbsp;&nbsp;&nbsp;&nbsp;市场价 : <span class="price1">￥<span style="color:#CF2D28;">{$productinfo.market_price}</span>元</span> 
							</li>
							<li class="mui-table-view-cell" >
								 <span class="mui-pull-left">促销活动 : <span class="price">{$productinfo.activityname}</span></span> 
							</li>    
							<li class="mui-table-view-cell" >
								  <span class="mui-pull-left">库存数量 : &nbsp;&nbsp; </span>
								  <div class="mui-pull-left">
					  				 <span id="inventory_label">{$productinfo.inventory}</span>&nbsp;件
	 							  </div>
	 			            </li>   
							{if $productinfo.bargains|@count gt '0'}
							<li id="bargains_profile" class="mui-table-view-cell bargains-profile" >
								<ul class="mui-table-view">
									{foreach key=profileid item=profileinfo from=$productinfo.bargains}
										<li class="mui-table-view-cell" style="padding: 8px 5px;">
											<div class="mui-media-body">
														<img class="mui-media-object mui-pull-left" style="width:20px;height:20px;" src="{$profileinfo.headimgurl}">
														<span style="width:120px;text-align:left;display:inline-block;">{$profileinfo.givenname}</span> 
														 {if $profileinfo.bargain eq '1'}
														<span style="float: right;padding-left:5px;color:#CF2D28;"><span class="mui-icon iconfont icon-bargain"></span></span>
														{else}
														<span style="float: right;color:#999;padding-left:5px;"><span class="mui-icon iconfont icon-jujue"></span></span>
														{/if}
														<span style="float: right;color:#999">{$profileinfo.published}</span>
													</div>
										</li>
									{/foreach}
								</ul>
							</li>
							{/if} 
							 
						</ul> 
							
                        <a id="detail_image" href="javascript:;"><h5 class="show-content" style="padding: 10px;">图文详情【点击查看更多图文详情】</h5></a>
                         
                         
                        </div>
						{include file='copyright.tpl'}
                    </div> 
                    <!--end detail--> 
                </div> 
            </div>
            <div class="mui-backdrop" style="display:none;"></div>
        </div> 
 
	


	<script type="text/javascript"> 
	{literal} 
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
	        },
	    }); 
		mui.ready(function() { 
			mui('#pullrefresh').scroll(); 
			mui('#copyright').on('tap','a',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			});
			mui('#pullrefresh').on('tap','button#join_bargain',function(e){ 
				var productid = Zepto('#productid').val();
				var salesactivityid = Zepto('#salesactivityid').val();
				var url = 'bargain_subscribe.php?bargain=join&salesactivityid='+salesactivityid+'&productid='+productid;
				mui.openWindow({
					url: url,
					id: 'info'
				});
			}); 
			mui('#pullrefresh').on('tap','button#help_bargain',function(e){ 
				var productid = Zepto('#productid').val();
				var salesactivityid = Zepto('#salesactivityid').val();
				var url = 'bargain_subscribe.php?bargain=help&salesactivityid='+salesactivityid+'&productid='+productid;
				mui.openWindow({
					url: url,
					id: 'info'
				});
			}); 
			 
			mui('#pullrefresh').on('tap','button#refuse_help',function(e){ 
                swal({
                    title: "提示",
                    text: "您真的跟我那么不对路吗？帮这点小忙都不行？",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    confirmButtonText: "坚决不帮",
                    confirmButtonColor: "#ec6c62"
                }, function () {
					var productid = Zepto('#productid').val();
					var url = 'bargain_subscribe.php?productid='+productid;
					var productid = Zepto('#productid').val();
					var salesactivityid = Zepto('#salesactivityid').val();
					var url = 'bargain_subscribe.php?bargain=refuse&salesactivityid='+salesactivityid+'&productid='+productid;
					mui.openWindow({
						url: url,
						id: 'info'
					});
                });
			}); 
			 
			mui('#detail_image').on('tap','h5',function(e){ 
				detail_image();
			}); 
		});
		function detail_image()
		{  
			var productid = Zepto('#productid').val();
			var url = 'detail_image.php?productid='+productid;
			mui.openWindow({
				url: url,
				id: 'info'
			});
		} 
	{/literal}
	</script> 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>   
</html>