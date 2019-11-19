

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
	<script src="public/js/lazyload.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/detail.js" type="text/javascript" charset="utf-8"></script>
	<style>
	{literal}
	 .name,.detail{ -webkit-margin-start:0px;  }
	 .img-responsive { display: block; height: auto; width: 100%; } 
	 .price1 {text-decoration:line-through; color:#000;  }  
	 .price3 {text-decoration:line-through; color:#999;  } 
	 .price2 {padding-left:5px;color:#CF2D28; font-size:1.2em; font-weight:500; }
	 .price1 span,.price2 span,span.price{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; margin-left:0}
	 .price2 span {font-size:1.1em}
	 .mycollections {font-size:1.8em;color:#fe4401;}
	 
	 .yanse_xz a{color:#ccc;margin-bottom:2px;margin-top:2px;}
	 
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
	 .totalprice{
	   color:#CF2D28; 
	   margin-top: 9px;
	 }
	 #inventory_label
	 {
		color:#CF2D28; 
		font-size: 16px;
	 } 
 	.mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
 	  color: #cc3300;
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
                <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
                <h1 class="mui-title">商品详情</h1>
            </header>
			{/if} 
			<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
				<a class="mui-tab-item mui-active home" href="/index.php" > 
					<span class="mui-tab-label">
						  <span class="mui-icon iconfont icon-mainpage"></span>&nbsp;&nbsp;首页
				    </span>
				</a> 
				<a class="mui-tab-item addshoppingcart" href="#" > 
					<span class="mui-tab-label">
						  <span class="mui-icon iconfont icon-jiarugouwuche mui-twinkling"></span>&nbsp;&nbsp;加入购物车
				    </span>
				</a> 
				<a class="mui-tab-item shoppingcart" href="shoppingcart.php" > 
					<span class="mui-tab-label">
						  <span class="mui-icon iconfont icon-shoppingcart " id="shoppingcart"> 
							   <span id="shoppingcart_badge">{if $share_info.shoppingcart neq '0' && $share_info.shoppingcart neq '' }<span class="mui-badge">{$share_info.shoppingcart}</span>{/if}</span>
					      </span> 
						   &nbsp;&nbsp;立即结算
				   </span>
				</a>
			</nav>
             
            <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                <div class="mui-scroll">
                    <!-- 主界面具体展示内容 -->
			         <div  style="margin: 5px 5px;"> 
							 <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" id="productlogo" src="{$productinfo.productlogo}">
				     </div> 
					 <input type="hidden" id="property_type_count" value="{$property_type_count}" />
					 <input type="hidden" id="from" value="{$from}" />
					 <input type="hidden" id="product_property_id" name="product_property_id" value="" />
					 <input type="hidden" id="pagenum" name="pagenum" value="{$pagenum}">
					 <input type="hidden" id="scrolltop" name="scrolltop" value="{$scrolltop}"> 
					 <input type="hidden" id="productid" name="productid" value="{$productid}">
					 <input type="hidden" name="total_price" id="total_price1" value="{$productinfo.shop_price}" />
					 <input type="hidden" name="shop_price" id="shop_price1" value="{$productinfo.shop_price}" />
					 <input type="hidden" name="inventory" id="inventory1" value="{$productinfo.inventory}" />
					 <input type="hidden" name="zhekou" id="zhekou" value="{$productinfo.zhekou}" />
					 <input type="hidden" name="salesactivityid" id="salesactivityid" value="{$productinfo.salesactivityid}" />
					 <input type="hidden" name="salesactivity_product_id" id="salesactivity_product_id" value="{$productinfo.salesactivity_product_id}" />
					 <input type="hidden" name="type" id="type" value="{$type}" />
                    <!--detail-->
                    <div class="mui-content-padded">
                        <p style="margin-bottom: 0;">
                            <span class="detail-title">{$productinfo.productname}</span>
                        </p>
                        <p class="info">
							{if $supplier_info.showtradeorderrecord eq '0'}
								<span class="mui-badge mui-badge-primary">{$tradecount}成交</span>
							{else}
								<span class="mui-badge mui-badge-primary" style="background-color:inherit;">&nbsp;</span>
							{/if}
							{if $supplier_info.productappraises eq '0'}<span class="mui-badge mui-badge-primary">{$appraisecount}评</span>{/if}
							{if $productinfo.activityname neq '' &&  $productinfo.zhekoulabel neq '' && $productinfo.zhekou neq ''}
								<span class="price">￥<b style="font-size: 32px;" id="shop_price">{$productinfo.promotional_price}</b>元</span>
							{else}
								<span class="price">￥<b style="font-size: 32px;" id="shop_price">{$productinfo.shop_price}</b>元</span>
							{/if}
                        </p>
						<ul id="propertygroup" class="mui-table-view" style="background: #f3f3f3;">
							{if $productinfo.vendorname neq '' && $supplier_info.showvendor eq '1'}
							<li class="mui-table-view-cell" >
								  <span class="mui-pull-left">供应商 : &nbsp;&nbsp; </span>
								  <div class="mui-pull-left">
					  				 <span>{$productinfo.vendorname}</span>
	 							  </div>
	 			            </li>
							{/if}
							<li class="mui-table-view-cell" >
								 <span class="mui-pull-left">市场价 : <span class="price1">￥<span id="market_price">{$productinfo.market_price}</span></span></span>
			                     <span class="mui-pull-right">
								 <input type="hidden" id="mycollection" value="{$mycollections}">
								 {if $mycollections eq '0'}
								     <a class="mycollection" href="#" >
			                            <span id="mycollectionicon" class="mui-icon iconfont icon-nocollection mui-slow-twinkling mycollections"></span>
								 	 </a>
			                     {else}
			                          <a class="mycollection" href="#" >
			                            <span id="mycollectionicon" class="mui-icon iconfont icon-collection mycollections"></span>
								 	  </a>
			                     {/if}
							     </span>
							</li>
							{if $productinfo.activityname neq '' &&  $productinfo.zhekoulabel neq '' && $productinfo.zhekou neq ''}
								<li class="mui-table-view-cell" >
									 <span class="mui-pull-left">促销原价 : <span class="price1">￥<span id="old_shop_price">{$productinfo.shop_price}</span></span>
								</li>
								<li class="mui-table-view-cell" >
									 <span class="mui-pull-left">促销活动 : <span class="price">{$productinfo.activityname}【{$productinfo.zhekoulabel}】</span></span>
								</li>
							{/if}
			                {foreach name="propertygroup" item=property_info key=property_name from=$property_type}
			                   <li class="mui-table-view-cell" style="padding: 5px 15px;">
								    <span class="mui-pull-left">{$property_name} : &nbsp;&nbsp; </span>
			                        <div class="yanse_xz mui-pull-left">
			                        <input type="hidden" id="propertygroup_label_{$smarty.foreach.propertygroup.iteration}" value="{$property_name}" />
			                        {foreach name="property" item=property key=propertyid from=$property_info}
			                            <a class="btn btn-default propertygroup_{$smarty.foreach.propertygroup.iteration}" groupid="{$smarty.foreach.propertygroup.iteration}" propertyid="{$propertyid}" href="javascript:;"  >{$property}
			                                <div style="display:none">
			                                    <input class="propertygroup_input_{$smarty.foreach.propertygroup.iteration}" id="property_{$smarty.foreach.propertygroup.iteration}_{$propertyid}" type="radio" name="propertygroup_{$smarty.foreach.propertygroup.iteration}" propertyid="{$propertyid}" value="{$property}" />
			                                </div>
			                            </a>
			                        {/foreach}
								    </div>
			                   </li>
			                {/foreach}
							<li class="mui-table-view-cell" >
								  <span class="mui-pull-left">购买数量 : &nbsp;&nbsp; </span>
								  <div class="mui-pull-left">
					  				<div class="mui-numbox" data-numbox-step='1' data-numbox-min='1' data-numbox-max='{if $productinfo.uniquesale eq '1'}1{else}999{/if}'>
					  					<button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
										<input value="{$productinfo.postage}" id="postage" type="hidden"/>
										<input value="{$productinfo.includepost}" id="includepost" type="hidden"/>
										<input value="{$productinfo.mergepostage}" id="mergepostage" type="hidden"/>
					  					<input onkeyup="recalc();" readonly   id="qty_item" name="qty_item" class="mui-numbox-input" type="number" />
					  					<button  class="mui-btn mui-numbox-btn-plus" type="button">+</button>
					  				</div>

	 							  </div>
	 							  {if $productinfo.uniquesale neq '1'}
	 							  <!--
								  <div class="mui-pull-left" style="padding-left:5px;">
									  <button id="numaddten" class="mui-btn mui-numbox-btn-plus" type="button" style="height:36px;">+10</button>
								  </div>-->
								  {/if}
	 			            </li>
							<li class="mui-table-view-cell" >
								  <span class="mui-pull-left">库存数量 : &nbsp;&nbsp; </span>
								  <div class="mui-pull-left">
					  				 <span id="inventory_label">{$productinfo.inventory}</span>&nbsp;件
	 							  </div>
	 			            </li>
							<li id="postage_panel" class="mui-table-view-cell"  style="display: {if $productinfo.postage|@floatval gt 0 }block{else}none{/if};">
								<span class="mui-pull-left">邮费 : &nbsp;&nbsp; </span>
								<div class="mui-pull-left">
									<span class="totalprice">￥<b><span id="postage_span" style="font-size: 16px;">{$productinfo.postage}</span></b>元</span>
									{*<span>
										{if $productinfo.mergepostage|@intval eq 1}合{else}分{/if}
									</span>*}
									{if $productinfo.includepost|@intval gt 0}
										<span style="color:#878787;margin-left:10px;">({$productinfo.includepost}
																					  件包邮)</span>
									{/if}
								</div>
							</li>
							<li class="mui-table-view-cell" >
								  <span class="mui-pull-left">合计 : &nbsp;&nbsp; </span>
								  <div class="mui-pull-left">
									  {if $productinfo.activityname neq '' &&  $productinfo.zhekoulabel neq '' && $productinfo.zhekou neq ''}
									   		<span class="totalprice">￥<b><span id="totalprice" style="font-size: 16px;">{$productinfo.promotional_price+$productinfo.postage|string_format:"%.2f"}</span></b>元</span>
									  {else}
									  	 	<span class="totalprice">￥<b><span id="totalprice" style="font-size: 16px;">{$productinfo.shop_price+$productinfo.postage|string_format:"%.2f"}</span></b>元</span>
									  {/if}
	 							  </div>
	 			            </li>
						</ul>

						{if $supplier_info.productdisplaymode eq '0'}
                       		<a id="detail_image" href="javascript:;"><h5 class="show-content" style="padding: 10px;">图文详情【点击查看更多图文详情】</h5></a>
                        {/if}
                        <div id="segmentedControl" class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
                            <a class="mui-control-item mui-active" href="#item1mobile">商品描述</a>
                            {if $supplier_info.productappraises eq '0'}
                            <a class="mui-control-item" href="#item2mobile">累计评价</a>
                            {/if}
                           {if $supplier_info.showtradeorderrecord eq '0'} <a class="mui-control-item" href="#item3mobile">成交记录</a>{/if}
                           <!-- <a class="mui-control-item" href="#item4mobile">服务声明</a>-->
                        </div>
                        <div class="mui-content-padded-2" style="background: #FFFFFF; padding: 10px;">
                            <div id="item1mobile" class="mui-control-content mui-active">
								{$productinfo.simple_desc}
							</div>
						    {if $supplier_info.productappraises eq '0'}
                            <div id="item2mobile" class="mui-control-content">
								{if $appraises|@count eq 0}
								 <h5 class="show-content" style="padding: 10px;background: #ff5400;">目前没有评价。</h5>
								{else}
									<ul class="mui-table-view">
										{foreach name="appraises" item=appraises_info   from=$appraises}
											<li class="mui-table-view-cell" style="padding: 8px 5px;">
												<div class="mui-media-body">
													<img class="mui-media-object mui-pull-left" style="width:20px;height:20px;" src="{$appraises_info.headimgurl}">
													<span style="width:80px;text-align:left;display:inline-block;">{$appraises_info.givenname}</span>
													【{$appraises_info.praise_info}】
												</div>
												<div class="mui-media-body" style="padding-left:30px;color:#4d4d4d">
													 {$appraises_info.remark}
												</div>
												{if $appraises.hasimages gt 0}
												<div class="mui-media-body" style="padding-left:30px;">
													  {foreach name="appraise_images" item=appraise_image_info   from=$appraises.images}
													      <img class="mui-media-object mui-pull-left" src="{$appraise_image_info}">
													  {/foreach}
												</div>
												{/if}
												<div class="mui-media-body" style="padding-left:30px;color:#999">
													 {$appraises_info.published}
												</div>
										    </li>
										{/foreach}
									</ul>
									 <a id="moreappraises" href="javascript:;"><h5 class="show-content" style="padding: 10px;background: #ff5400;">【点击查看更多评价】</h5></a>
								{/if}
							</div>
							{/if}
							{if $supplier_info.showtradeorderrecord eq '0'}
                            <div id="item3mobile" class="mui-control-content">
								<ul class="mui-table-view">
									<li class="mui-table-view-cell" style="padding: 8px 5px;">
										<div class="mui-media-body  mui-pull-left">
											<span style="width:80px;text-align:left;display:inline-block;">买家</span>价格
										</div>
										<div class="mui-media-body mui-pull-right">
											<span style="width:105px;text-align:right;display:inline-block;">购买时间</span>
										</div>
										<div class="mui-media-body mui-pull-right">
											<span style="width:30px;text-align:left;display:inline-block;">数量</span>
										 </div>
								    </li>
									{foreach name="transactionrecords" item=transactionrecord_info   from=$transactionrecords}
										<li class="mui-table-view-cell" style="padding: 8px 5px;">
											<div class="mui-media-body  mui-pull-left">
												<span style="width:80px;text-align:left;display:inline-block;">{$transactionrecord_info.givenname}</span>¥{$transactionrecord_info.shop_price}
											</div>
											<div class="mui-media-body mui-pull-right">
												<span style="width:105px;text-align:right;display:inline-block;">{$transactionrecord_info.published}</span>
											</div>
											<div class="mui-media-body mui-pull-right">
												<span style="width:30px;text-align:left;display:inline-block;">{$transactionrecord_info.quantity}</span>
											 </div>
									    </li>
									{/foreach}
								</ul>
							</div>
							{/if}
							<!--
                            <div id="item4mobile" class="mui-control-content">
							         <h3 class="show-content"><em>卖家承诺以下服务</em></h3>
							         <div class="show-content">
							   		         <dl class="seven-days">
							       			            <dd class="name" >7天无理由退货</dd>
							                            <dd class="detail">
							                               <p>该商品支持7天无理由退货，自商品签收之日起7天内:</p>
							                               <p>1、商品外包装完整，相关附（配）件齐全；</p>
														   <p>2、商品表面无划痕、无破损、无使用等痕迹；如商品使用留下记录参数，则不支持退；</p>
														   <p>3、（若有）防伪标识码未刮开或撕损</p>
														   <p>可申请无理由退货，包邮商品需要买家承担退货邮费，非包邮商品需要买家承担发货和退货邮费。</p>
							                           </dd>
							       			</dl>

							                <dl>
							                           <dd class="name">消费者保障服务</dd>
							                           <dd class="detail">
	       						                            <p>在确认收货7天内，如有商品质量问题、描述不符或未收到货等，您有权申请退款或退货，来回邮费由卖家承担。</p>
							                           </dd>
							       			</dl>

							         </div>
							</div>-->
                        </div>
						{if $supplier_info.productdisplaymode eq '1'}
						<div class="mui-content-padded" style="background: #FFFFFF; padding: 5px;margin:0px;margin-top: 5px;">
							 {$productinfo.description}
 			            </div>
                        {/if}
						{if $supplier_info.productqrcode eq '0'}
						<div class="mui-content-padded" style="margin:0px;margin-top: 5px;">
	   							 <img class="mui-media-object" style="border-radius: 6px;width: 100%;max-width: 100%;height: auto;" src="productqrcode.php?productid={$productid}">
	   				    </div>
                        <div class="mui-table-view-cell" id="checking-wrap-tip">
                            <div class="mui-media-body" style="color:red;text-align:center">
                               以上二维码图片可以保存后转发，效果更佳！
                            </div>
                        </div>
                        {/if}
						<div class="show-content" style="padding-top: 15px;"><img class="img-responsive" src="images/baozhang.png"></div>
                    </div>
                    <!--end detail-->
                </div>
            </div>
            <div class="mui-backdrop" style="display:none;"></div>
        </div>
	


	<script type="text/javascript">
	    var propertys = {$propertys}; 
	{literal} 
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
	        },
	    });
	    
		mui.ready(function() { 
			mui('#pullrefresh').scroll(); 
			$(".lazy").lazyload(); 
			mui('.mui-bar').on('tap','a.addshoppingcart',function(e){
				addshoppingcart();
			});
			mui('.mui-bar').on('tap','a.shoppingcart',function(e){ 
				var shoppingcarturl = this.getAttribute('href');
				checkshoppingcart(shoppingcarturl); 
			});
			mui('.mui-bar').on('tap', 'a.home', function (e){
				  mui.openWindow({url: this.getAttribute('href'), id: 'info'});
			});
			
			
			
			mui('#propertygroup').on('tap','a.mycollection',function(e){ 
				  var productid = Zepto('#productid').val();
				  var status = Zepto('#mycollection').val();
				  if (status == "0")
				  {
					  
					   var postbody = 'record=' + productid + '&status=1';
	 		           mui.ajax({
	 			            type: 'POST',
	 			            url: "mycollection_add.ajax.php",
	 			            data: postbody,
	 			            success: function(json) {   
	 								 mui.toast("成功添加到收藏！"); 
	 								  Zepto('#mycollection').val("1");
	 								  Zepto("#mycollectionicon").addClass("icon-collection");
	 								  Zepto("#mycollectionicon").removeClass("icon-nocollection");
	 								  Zepto("#mycollectionicon").removeClass("mui-slow-twinkling"); 
		 			            }
	 					 }); 
				  }
				  else
				  {
					   
					   var postbody = 'record=' + productid + '&status=0';
	 		           mui.ajax({
	 			            type: 'POST',
	 			            url: "mycollection_add.ajax.php",
	 			            data: postbody,
	 			            success: function(json) {   
	 								 mui.toast("成功取消收藏！"); 
	 								 Zepto('#mycollection').val("0");
	 								 Zepto("#mycollectionicon").addClass("icon-nocollection");
	 								 Zepto("#mycollectionicon").addClass("mui-slow-twinkling"); 
	 								  Zepto("#mycollectionicon").removeClass("icon-collection");
	 			            }
	 					 }); 
					  
				  }
			});
			mui('#detail_image').on('tap','h5',function(e){ 
				detail_image();
			});
			mui('#moreappraises').on('tap','h5',function(e){ 
				var from = Zepto('#from').val();
				var pagenum = Zepto('#pagenum').val(); 
				var scrolltop = Zepto('#scrolltop').val();
				var productid = Zepto('#productid').val();
				var url = 'detail_appraise.php?productid='+productid+'&scrolltop='+scrolltop+'&pagenum='+pagenum+'&from='+from;
				mui.openWindow({
					url: url,
					id: 'info'
				});
			});
			mui('#propertygroup').on('tap', 'a', function(e) {
				
				var groupid =  this.getAttribute('groupid');
				var propertyid =  this.getAttribute('propertyid');    
				
				Zepto(".propertygroup_"+groupid).removeClass("active"); 
				Zepto(this).addClass("active"); 
				
				Zepto(".propertygroup_input_"+groupid).attr("checked",false); 
				Zepto("#property_"+groupid+"_"+propertyid).attr("checked",true); 
				Zepto("#type").val('');  
				change_price();
			});  
			mui('.mui-numbox').on('change', 'input', function() { 
			     recalc();
			});  
			mui('.mui-table-view-cell').on('tap', 'button#numaddten', function() { 
				 var qty_item = Zepto('#qty_item').val();
				 var new_qty_item = parseInt(qty_item,10) + 10; 
				 Zepto('#qty_item').val(new_qty_item);
				  recalc();
			});  
			
		});
		function detail_image()
		{ 
			var from = Zepto('#from').val();
			var pagenum = Zepto('#pagenum').val(); 
			var scrolltop = Zepto('#scrolltop').val();
			var productid = Zepto('#productid').val();
			var url = 'detail_image.php?productid='+productid+'&scrolltop='+scrolltop+'&pagenum='+pagenum+'&from='+from;
			mui.openWindow({
				url: url,
				id: 'info'
			});
		}
		function checkshoppingcart(shoppingcarturl)
		{ 
            var inventory = Zepto('#inventory1').val();
            var newinventory = parseInt(inventory,10);
            if ( newinventory <= 0)
            {
				mui.toast('您选择的商品已经卖完了！'); 
                return false;
            }
			if (Zepto('#type').val() == "")
			{
	            var property_type_count = Zepto('#property_type_count').val();
	            for(var i=1;i<=property_type_count;i++)
	            {
					var radio = Zepto('input[name=propertygroup_'+i+'][checked=true]');
	                if( radio.val() == undefined ) 
	                { 
	                    mui.toast('请选择商品的'+Zepto('#propertygroup_label_'+i).val()); 
	                    return false;
	                }
	            } 
				mui.toast('您还需要选择的商品属性！');  
				return false;
			}
			else
			{
				var qty_item = Zepto('#qty_item').val(); 
				var productid = Zepto('#productid').val();
				var product_property_id = Zepto('#product_property_id').val();  
			 
			    var postbody = 'shoppingcart=1&record=' + productid + '&quantity=' + qty_item;
			    if (product_property_id != "" && product_property_id != undefined)
				{
					postbody = 'type=detail&shoppingcart=1&record=' + productid + '&quantity=' + qty_item + '&propertyid=' + product_property_id;
				}
				var salesactivityid = Zepto('#salesactivityid').val();
				var salesactivity_product_id = Zepto('#salesactivity_product_id').val();
				if (salesactivityid != "" && salesactivity_product_id != "")
				{
					postbody += '&salesactivityid='+salesactivityid + '&salesactivitys_product_id='+salesactivity_product_id;
				}
			     
		        mui.ajax({
			            type: 'POST',
			            url: "shoppingcart_add.ajax.php",
			            data: postbody,
			            success: function(json) {   
			                var jsondata = eval("("+json+")");
			                if (jsondata.code == 200) {   
								mui.openWindow({
									url: shoppingcarturl,
									id: 'info'
								});
			                } 
							else
							{
								 mui.toast(jsondata.msg);
							}
			            }
					 }); 
			}
		}
		function addshoppingcart()
		{ 
            var inventory = Zepto('#inventory1').val();
            var newinventory = parseInt(inventory,10);
            if ( newinventory <= 0)
            {
				mui.toast('您选择的商品已经卖完了！'); 
                return false;
            }
			if (Zepto('#type').val() == "")
			{
	            var property_type_count = Zepto('#property_type_count').val();
	            for(var i=1;i<=property_type_count;i++)
	            {
					var radio = Zepto('input[name=propertygroup_'+i+'][checked=true]');
	                if( radio.val() == undefined ) 
	                { 
	                    mui.toast('请选择商品的'+Zepto('#propertygroup_label_'+i).val()); 
	                    return false;
	                }
	            } 
				mui.toast('您还需要选择的商品属性！');  
				return false;
			}
			else
			{
				var qty_item = Zepto('#qty_item').val(); 
				var productid = Zepto('#productid').val();
				var product_property_id = Zepto('#product_property_id').val();  
			 
			    var postbody = 'record=' + productid + '&quantity=' + qty_item;
			    if (product_property_id != "" && product_property_id != undefined)
				{
					postbody = 'type=detail&record=' + productid + '&quantity=' + qty_item + '&propertyid=' + product_property_id;
				}
				var salesactivityid = Zepto('#salesactivityid').val();
				var salesactivity_product_id = Zepto('#salesactivity_product_id').val();
				if (salesactivityid != "" && salesactivity_product_id != "")
				{
					postbody += '&salesactivityid='+salesactivityid + '&salesactivitys_product_id='+salesactivity_product_id;
				}
			     
		        mui.ajax({
			            type: 'POST',
			            url: "shoppingcart_add.ajax.php",
			            data: postbody,
			            success: function(json) {   
			                var jsondata = eval("("+json+")");
			                if (jsondata.code == 200) {   
								 mui.toast(jsondata.msg);
								 flyItem("productlogo");
			                     Zepto('#shoppingcart_badge').html('<span class="mui-badge">'+jsondata.shoppingcart+'</span>'); 
			                } 
							else
							{
								 mui.toast(jsondata.msg);
							}
			            }
					 }); 
			}
		}
		 
		
		function recalc()
		{
			var qty_item = Zepto('#qty_item').val(); 
			var inventory = Zepto('#inventory1').val();
			var shop_price = Zepto('#shop_price1').val();
			var zhekou = Zepto('#zhekou').val();
			var includepost    = Zepto("#includepost").val();
			var postage        = Zepto("#postage").val();
			var mergepostage   = Zepto("#mergepostage").val();
			
			var new_qty_item = parseInt(qty_item,10);
			var newinventory = parseInt(inventory,10);
			var newshop_price = parseFloat(shop_price,10);
			var newzhekou = parseFloat(zhekou,10) * 0.1;

			if (newinventory > 0 )
			{ 
				if (new_qty_item > newinventory )
				{
					new_qty_item = newinventory;
					Zepto('#qty_item').val(newinventory);
				}
				var total = new_qty_item * newshop_price;
				if (newzhekou > 0)
				{
					total = new_qty_item * newshop_price * newzhekou ;
				}

				if (parseFloat(postage, 10) > 0)
				{
					if (parseInt(mergepostage, 10) != 1)
					{
						postage = parseFloat(postage, 10) * parseInt(new_qty_item, 10);
					}
					Zepto("#postage_span").html(parseFloat(postage, 10).toFixed(2));
					if (parseInt(includepost, 10) > 0 && parseInt(includepost, 10) <= parseInt(new_qty_item, 10))
					{
						Zepto("#postage_panel").css('display', 'none;');
					}
					else
					{
						Zepto("#postage_panel").css('display', 'block;');
						total = parseFloat(total, 10) + parseFloat(postage, 10);
					}
				}
				Zepto("#totalprice").html(total.toFixed(2));
				Zepto("#total_price1").val(total);
				Zepto("#type").val('submit');
			}
			else
			{
				Zepto("#totalprice").html('0.00'); 
				Zepto("#type").val('');  
			}
		}
	    function product_recalc(shop_price,inventory) {
			var zhekou = Zepto('#zhekou').val();
			var newzhekou = parseFloat(zhekou,10) * 0.1;
			
			var newinventory = parseInt(inventory,10);
			var qty_item = Zepto('#qty_item').val(); 
			var new_qty_item = parseInt(qty_item,10);
			var price = parseFloat(shop_price,10);
			var includepost    = Zepto("#includepost").val();
			var postage        = Zepto("#postage").val();
			var mergepostage   = Zepto("#mergepostage").val();
			if (newinventory > 0 )
			{
				if (new_qty_item > newinventory )
				{
					new_qty_item = newinventory;
					Zepto('#qty_item').val(newinventory);
				}
				var total = new_qty_item * price;
				if (newzhekou > 0)
				{
					total = new_qty_item * price * newzhekou;
				}
				if (parseFloat(postage, 10) > 0)
				{
					if (parseInt(mergepostage, 10) != 1)
					{
						postage = parseFloat(postage, 10) * parseInt(new_qty_item, 10);
					}
					Zepto("#postage_span").html(parseFloat(postage, 10).toFixed(2));
					if (parseInt(includepost, 10) > 0 && parseInt(includepost, 10) <= parseInt(new_qty_item, 10))
					{
						Zepto("#postage_panel").css('display', 'none;');
					}
					else
					{
						Zepto("#postage_panel").css('display', 'block;');
						total = parseFloat(total, 10) + parseFloat(postage, 10);
					}
				}
				Zepto("#totalprice").html(total.toFixed(2));
				Zepto("#total_price1").val(total);
				Zepto("#type").val('submit');
			} 
			else
			{
				Zepto("#totalprice").html('0.00'); 
				Zepto("#type").val('');  
			}
		}
 
		function change_price() 
		{
            var property_type_count = Zepto('#property_type_count').val(); 
			var propertygroup = [];
            for(var i=1;i<=property_type_count;i++)
            {
				var radio = Zepto('input[name=propertygroup_'+i+'][checked=true]');
				if (radio)
				{
					var propertyid = radio.attr("propertyid");
					if (propertyid)
					{
						propertygroup.push(propertyid); 
					} 
				} 
            } 
			var propertygroupstr = propertygroup.sort().toString(); 
            Zepto.each(propertys, function(i, v) {  
				var propertyids = v.propertyids; 
				var propertyarray = propertyids.split(','); 
                if (propertygroupstr == propertyarray.sort().toString())
                {
					var zhekou = Zepto('#zhekou').val();
					var newzhekou = parseFloat(zhekou,10) * 0.1;
					if (newzhekou > 0)
					{
						Zepto("#old_shop_price").html(v.shop_price);
						var promotional_price = v.shop_price * newzhekou;
						Zepto("#shop_price").html(promotional_price.toFixed(2));  
						Zepto("#shop_price1").val(v.shop_price);
					}
					else
					{
						Zepto("#shop_price").html(v.shop_price);
						Zepto("#shop_price1").val(v.shop_price);
					}
					Zepto("#productlogo").attr("src",v.imgurl);
					Zepto("#product_property_id").val(v.propertytypeid);
					Zepto("#market_price").html(v.market_price);
					
					Zepto("#productlogo").html(v.imgurl); 
					Zepto("#inventory_label").html(v.inventory); 
					Zepto("#inventory1").val(v.inventory);
					 product_recalc(v.shop_price,v.inventory)
				}
				
            });  
		} 
	{/literal}
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>   
</html>