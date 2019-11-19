<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>{if $supplier_info.mainpagetitle neq '0' || $islogined neq 'true' || $supplier_info.showheader eq '1'}{$supplier_info.suppliername}{/if}</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />
	<link href="public/css/index.css" rel="stylesheet" /> 
	<link href="public/css/global.css" rel="stylesheet" /> 
	{include file='theme.tpl'}  
	{if $vendors|@count gt 0} 
	<style>
	  {literal} 
		  .img-responsive { display: block; height: auto; width: 100%; } 
   		  
		  .mui-table-view-cell .mui-table-view-label
		  {
		  	    width:60px;
		  		text-align:right;
		  		display:inline-block;
		  }  
		  .mui-table-view .mui-media-object {
		      line-height: 100px;
		      max-width: 100px;
		      height: auto;
		  }
		  
		  .mui-table-view-chevron .mui-table-view-cell { padding-right: 25px; }
		  .mui-navigate-right:after, .mui-push-right:after {  right: 45px;  }
		  #vendors .mui-table-view-cell:after {  background-color: #c8c7cc; }
	 	 {/literal}  	 	 
	</style>
	{/if}
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/lazyload.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script>   
</head>

<body>  
    <!-- 侧滑导航根容器 -->
    <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
        <!-- 菜单容器 -->
        {include file='leftmenu.tpl'} 
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
		    {if $supplier_info.mainpagetitle eq '0' && $supplier_info.showheader eq '0'}
            <!-- 主页面标题 -->
            <header class="mui-bar mui-bar-nav">
                 <a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
                 {if $supplier_info.presalesconsultation eq '0'}
                 <a href="webim.php" class="mui-icon mui-action-menu mui-icon-chat mui-twinkling mui-pull-right"></a>
                 {/if}
                 <h1 class="mui-title">{$supplier_info.suppliername}</h1>
            </header>
		    {/if}
			{include file='footer.tpl'}   
             <div id="pullrefresh" class="mui-content mui-scroll-wrapper"  {if $supplier_info.mainpagetitle eq '0' && $supplier_info.showheader eq '0'}style="padding-top: 45px;"{/if}> 
                 <div class="mui-scroll"> 
	                  	 {if $supplier_info.topadlogo neq ''}
	                  	 	<div>
						  		<img class="img-responsive"  src="{$supplier_info.topadlogo}">
						  	</div>
						 {/if}
					 	 {if $supplier_info.mainpageslider eq '1'}
		                 <!--slider--> 
			             <div id="slider" class="mui-slider" >
			                     <div class="mui-slider-group mui-slider-loop">
				                        {assign var="ads_count" value=$ads|@count}                                        
                                        {foreach name="ads" item=ad_info from=$ads} 
                                            {if $smarty.foreach.ads.iteration eq 1}
		 			                             <div class="mui-slider-item mui-slider-item-duplicate">
		 			                                 <a href="{$ad_info.link}">
		 			                                     <img style="" src="{$ad_info.banner}" alt="{$ad_info.adname}">
		 			                                 </a>
		 			                                 <p class="mui-slider-title">{$ad_info.adtitle}</p>
		 			                             </div>  
	 			                             {/if}
						 	           {/foreach}   
					                   {foreach item=ad_info from=$ads} 
	 			                             <div class="mui-slider-item">
	 			                                 <a href="{$ad_info.link}">
	 			                                     <img style="" src="{$ad_info.banner}" alt="{$ad_info.adname}">
	 			                                 </a>
	 			                                 <p class="mui-slider-title">{$ad_info.adtitle}</p>
	 			                             </div>  
						 	           {/foreach}   
						 	           {foreach name="ads" item=ad_info from=$ads} 
                                            {if $smarty.foreach.ads.iteration eq $ads_count}
		 			                             <div class="mui-slider-item mui-slider-item-duplicate">
		 			                                 <a href="{$ad_info.link}">
		 			                                     <img style="" src="{$ad_info.banner}" alt="{$ad_info.adname}">
		 			                                 </a>
		 			                                 <p class="mui-slider-title">{$ad_info.adtitle}</p>
		 			                             </div>  
	 			                             {/if}
						 	           {/foreach}                       
                                         
								 </div>
			                     <div class="mui-slider-indicator mui-text-right">
				                   {foreach item=ad_info from=$ads} 
 			                            <div class="mui-indicator"></div>
					 	           {/foreach}                            
			 				     </div>
			              </div>
						   <!-- end slider -->
						 {/if}  
						 <!--
						 <div class="mui-content-padded" style="height:30px;width:95%;background-color:#dededd">
							 <div style="float: left;font-size: 17px;font-weight: bold;text-align: center;color: #fff;height: 30px;width: 60px;line-height: 30px;background-color: #c9392c;border-right: 1px solid #FFF;">
								 快讯
							 </div>
							 <div style="padding-left:5px;float: left;font-size: 12px;height: 30px;line-height: 30px;">{$ORDERNEWS} </div>
						  </div> 
						 -->
						 {if $supplier_info.mainpagetitle eq '0'}
				   			 <form action="search.php" onSubmit="return searchgo()"> 
								  <div class="mui-table-view">
									  <div class="mui-table-view-cell">     
						   				<div class="mui-input-row mui-search">
						   					<input id="keywords" name="keywords" type="search" class="mui-input-clear" placeholder="搜索你喜欢的商品">
						   				</div> 
								     </div>
							      </div>
						     </form>
						 
		                      <!--icon menu-->
		                      {include file='iconmenu.tpl'}   
		                      <!--end icon menu-->   
							  <h6 class="mui-content-padded" style="height:3px;margin:0px">&nbsp;</h6> 
						  {/if} 
						 
						  {foreach name="salesactivitys" key=salesactivityid item=salesactivity_info  from=$salesactivitylist} 
	 						<div> 
 								 <ul class="mui-table-view"> 
									  <li class="mui-table-view-cell mui-media salesactivity" >
			  								 <a style="margin:0px;" href="salesactivity.php?record={$salesactivity_info.id}">
			  									 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;" src="{$salesactivity_info.homepage}">
			   								 </a> 
			  						</li> 
 						 	 	</ul> 
	 						 </div>
				   		  {/foreach}
						   
		                  <input id="page" name="page" value="2" type="hidden">    
						 	 {if $vendors|@count gt 0} 
							 <ul id="vendors" class="mui-table-view mui-table-view-chevron" style="color: #333;">
								 	{foreach name="vendors"  item=vendor_info  from=$vendors} 
								 		<li class="mui-table-view-cell mui-left" style="height:104px;"> 
											<a class="mui-navigate-right" href="category.php?vendorid={$vendor_info.vendorid}">
										        <img class="mui-media-object mui-pull-left" src="{$vendor_info.image}">
												<div class="mui-media-body mui-pull-left" style="width:180px">
													<p class='mui-ellipsis' style="color:#333;font-size:1.3em;">{$vendor_info.vendorname}</p> 
													{if $vendor_info.address eq ''}
														<p class='mui-ellipsis'  style="padding-top:4px;line-height: 24px;">联系人：{$vendor_info.contact}</p>
														<p class='mui-ellipsis' style="line-height: 24px;">电话：{$vendor_info.telphone}</p>  
													{else}
														<p class='mui-ellipsis' style="padding-top:4px;line-height: 16px;">联系人：{$vendor_info.contact}</p>
														<p class='mui-ellipsis' style="padding-top:0px;line-height: 16px;">电话：{$vendor_info.telphone}</p>  
														<p class='mui-ellipsis' style="padding-top:0px;line-height: 16px;">地址：{$vendor_info.address}</p>  
													{/if}
												</div>  
											</a> 
	  									</li> 
						   		  {/foreach}
					 	 	</ul>
							{else}
		                      <ul id="list" class="mui-table-view mui-grid-view list" {if $supplier_info.mainpageproductshowmode eq '1'}style="border:0px;position: static;"{/if}>  
							  </ul>
							{/if}  
						  {if $supplier_info.bottomadlogo neq ''}
						  		<img class="lazy img-responsive" src="images/lazyload.png" data-original="{$supplier_info.bottomadlogo}">
						  {/if}
						   {include file='copyright.tpl'}
                 </div> 
				
             </div> 
            <div class="mui-backdrop" style="display:none;"></div>
        </div>  
    </div>
	
{if $seekattention eq 'yes'}
	<div id="seekattention" style="z-index: 999;position: fixed;right:0px; top: 65%; display: block;">
		<a href="seekattention.php">
			<img src="images/qrcode/seekattention.gif" style="width:120px;">
		</a>
	</div>
{/if}

<script type="text/javascript">
var indexcolumns = {$supplier_info.indexcolumns};
var mainpageproductshowmode = {$supplier_info.mainpageproductshowmode};
var product_count = 1;
var lock = 0;
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
	/**
	 * 下拉刷新具体业务实现
	 */
	function pulldownRefresh() { 
		setTimeout(function() { 
            Zepto('#page').val(1);
            Zepto('.list').html(''); 
			product_count = 1;
            add_more();
			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed   	
			
		}, 1000);  
	}
	
	function add_shoppingcart(record) 
	{  
        mui.ajax({
            type: 'POST',
            url: "shoppingcart_add.ajax.php",
            data: 'record=' + record,
            success: function(json) {   
                var jsondata = eval("("+json+")");
                if (jsondata.code == 200) {   
					 mui.toast(jsondata.msg);
					 flyItem("product_item_"+record);
                     Zepto('#shoppingcart_badge').html('<span class="mui-badge">'+jsondata.shoppingcart+'</span>'); 
                } 
				else
				{
					 mui.toast(jsondata.msg);
				}
            }
        }); 
    } 

	mui.ready(function() {  
		setTimeout(function() {              
			Zepto(".mui-scroll").css("-webkit-transform","translate3d(0px, 0px, 0px) translateZ(0px)");  			
		}, 100); 
		mui('#seekattention').on('tap','a',function(){
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		});  
		mui('#slider').on('tap','a',function(){
			var adurl = this.getAttribute('href');
			if (adurl != "")
			{
				mui.openWindow({
					url: adurl,
					id: 'info'
				});
			} 
		});  
		mui('.mui-table-view').on('tap','a.add_shoppingcart',function(){
			var record =  this.getAttribute('data-id'); 
			add_shoppingcart(record);
		}); 
		mui('.mui-table-view').on('tap','li.praise_product',function(){
			var productid =  this.getAttribute('data-id');  
			praise_product(productid);
		});
		
		mui('.mui-bar').on('tap','a',function(e){
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		});
		
		mui('.mui-table-view').on('tap', 'a', function(e) {
			 var href = this.getAttribute('href');
			  if (href && href != "")
			  {   
				  mui.openWindow({
									 url: this.getAttribute('href'),
									 id: 'info'
							 });
			  } 
		});
		
		
	});	  
	
function praise_product(productid) {
			_hmt.push(['_setAutoPageview', false]);
			_hmt.push(['_trackPageview', "praises.php"]);
	       mui.ajax({
			        type: 'POST',
			        url: "praise.php",
			        data: 'productid=' + productid + "&m="+Math.random(),
			        success: function(data) {  
						$("#praise_product_"+productid).html(data);
			        }
			    }); 
	
	}	 
 
function product_singlerow_html(v,index) { 
	 var sb=new StringBuilder();
	 //mainpageproductshowmode = '2'
	 if (mainpageproductshowmode == "1")
	 {
		
		 sb.append('<li class="mui-table-view-cell mui-media singlerow"  style="border:0px;">');
 		 sb.append('<a href="detail.php?from=index&productid='+v.productid+'">');
 		 if (index <= 2)
		 {
			 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object  img-responsive"  src="'+v.productlogo+'">');
		 }
		 else
		 {
			 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object lazy img-responsive"  src="images/lazyload.png" data-original="'+v.productlogo+'">');
		 }  
 		 sb.append('</a>'); 
		 sb.append('</li>');
	 }
	 else if (mainpageproductshowmode == "2")
	 {
		
		 sb.append('<li class="mui-table-view-cell mui-media singlerow"  style="border:0px;">');
 		 sb.append('<a href="detail.php?from=index&productid='+v.productid+'">');
 		 if (index <= 2)
		 {
			 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object  img-responsive"  src="'+v.productlogo+'">');
		 }
		 else
		 {
			 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object lazy img-responsive"  src="images/lazyload.png" data-original="'+v.productlogo+'">');
		 }  
 		 sb.append('</a>');  		 
 		 sb.append('<div>');
		 sb.append('		 <div style="width: 50%;height: 40px;line-height: 40px;float: left;font-size:1.8em;text-align:left">'+v.productname+'</div>');
		 
		 sb.append('		 <div style="width: 50%;height: 40px;line-height: 40px;float: left;text-align:right;"><span class="price2" style="height: 40px;line-height: 40px;color:#d32d25;font-size:1.4em;vertical-align:top">¥&nbsp;'+v.shop_price+'</span>');
		 
		  sb.append('		 <img style="width:60px;margin-top:-12px" src="images/nfb.png">');
		 if (v.hasproperty == "1")
		 { 
			 sb.append('<a href="detail.php?from=index&productid='+v.productid+'"><span style="color:#bba16b;font-size:2.4em;height: 40px;line-height: 40px;padding-left:5px;padding-right:5px;" class="mui-icon iconfont icon-yuanxinggouwuche"></span></a>');
		 }
		 else
		 {
			 sb.append('<a class="add_shoppingcart" data-id="'+v.productid+'" href="javascript:;"><span style="color:#bba16b;font-size:2.4em;height: 40px;line-height: 40px;padding-left:5px;padding-right:5px;" class="mui-icon iconfont icon-yuanxinggouwuche"></span></a>');
		 }
		 sb.append('</div >');		 
 		 sb.append('</div >');
		 sb.append('</li>');
	 }
	 else
	 {
		 sb.append('<li class="mui-table-view-cell mui-media singlerow">');
 		 sb.append('<a href="detail.php?from=index&productid='+v.productid+'">');
 		 if (index <= 2)
		 {
			 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object  img-responsive"  src="'+v.productlogo+'">');
		 }
		 else
		 {
			 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object lazy img-responsive"  src="images/lazyload.png" data-original="'+v.productlogo+'">');
		 } 
 		 sb.append('</a>');
		 sb.append('	 <div class="cp_miaoshu">');
		 sb.append('		 <div class="ms_left"></div>');
		 sb.append('		 <div class="ms_right">');
		 sb.append('			 <div class="tit">'+v.productname+'</div>');
		 sb.append('			 <div class="cnt">');
		 sb.append('				 <ul>');
		 sb.append('					 <li class="wg1"><span class="tit01">市场价:&nbsp;</span><span class="price1">¥'+v.market_price+'</span><br><span class="price2">¥'+v.shop_price+'</span><br></li>');
		 sb.append('					 <li class="wg2 praise_product" id="praise_product_'+v.productid+'" data-id="'+v.productid+'"><span class="mui-icon iconfont icon-tezan"></span>'+v.praise+'</li>');
		 if (v.hasproperty == "1")
		 { 
			 sb.append('<li class="wg3"><a href="detail.php?from=index&productid='+v.productid+'"><span class="mui-icon iconfont icon-xinzeng special-button-color"></span></a></li>');
		 }
		 else
		 {
			 sb.append('<li class="wg3"><a class="add_shoppingcart" data-id="'+v.productid+'" href="javascript:;"><span class="mui-icon iconfont icon-xinzeng special-button-color"></span></a></li>');
		 }
		 sb.append('				 </ul>');
		 sb.append('			 </div>');
		 sb.append('		 </div>');
		 sb.append('	 </div>');
		 sb.append('</li>');
	 }
		
	 return sb.toString(); 
}

function product_doublerow_html(v,align) { 
	 var sb=new StringBuilder();
	 //mainpageproductshowmode = '2'
	 if (mainpageproductshowmode == "1")
	 {
		 sb.append('<li class="mui-table-view-cell mui-media singlerow" style="border:0px;">');
 		 sb.append('<a href="detail.php?from=index&productid='+v.productid+'">');
		 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object lazy img-responsive"  src="images/lazyload.png" data-original="'+v.productlogo+'">');
 		 sb.append('</a>'); 
		 sb.append('</li>');
	 }
	 else if (mainpageproductshowmode == "2")
	 {
		 sb.append('<li class="mui-table-view-cell mui-media '+align+' mui-col-xs-6 doublerow">');
 		 sb.append('<a href="detail.php?from=index&productid='+v.productid+'">');
		 sb.append('	 <img id="product_item_'+v.productid+'" class="mui-media-object lazy img-responsive"  src="images/lazyload.png" data-original="'+v.productlogo+'">');
 		 sb.append('</a>'); 
 		 sb.append('<div>');
		 sb.append('		 <div style="width: 60%;height: 30px;line-height: 30px;float: left;font-size:1.1em;text-align:left">'+v.productname+'</div>');
		 
		 sb.append('		 <div style="width: 40%;height: 30px;line-height: 30px;float: left;text-align:right;">'); 		 
		 if (v.hasproperty == "1")
		 { 
			 sb.append('<a href="detail.php?from=index&productid='+v.productid+'"><img style="width:45px;margin-top:-6px" src="images/nfb.png"><span style="color:#bba16b;font-size:1.6em;height: 30px;line-height: 30px;padding-left:1px;padding-right:2px;" class="mui-icon iconfont icon-yuanxinggouwuche"></span></a>');
		 }
		 else
		 {
			 sb.append('<a class="add_shoppingcart" data-id="'+v.productid+'" href="javascript:;"><img style="width:45px;margin-top:-6px" src="images/nfb.png"><span style="color:#bba16b;font-size:1.6em;height: 30px;line-height: 30px;padding-left:1px;padding-right:2px;" class="mui-icon iconfont icon-yuanxinggouwuche"></span></a>');
		 }
		 sb.append('</div >');	
		 sb.append('		 <div style="width: 100%;height: 20px;line-height: 20px;float: left;text-align:left;"><span class="price2" style="height: 20px;line-height: 20px;color:#d32d25;font-size:1.4em;vertical-align:top">¥&nbsp;'+v.shop_price+'</span>');	
		 sb.append('</div >'); 
 		 sb.append('</div >');
		 sb.append('</li>');
	 }
	 else
	 {
		 sb.append('<li class="mui-table-view-cell mui-media '+align+' mui-col-xs-6 doublerow">');
		 sb.append('    	<a href="detail.php?from=index&productid='+v.productid+'">');
		 sb.append('        <img id="product_item_'+v.productid+'" class="mui-media-object lazy img-responsive" src="images/lazyload.png" data-original="'+v.productlogo+'">');
		 sb.append('        <div class="mui-media-body">'+v.productname+'</div> ');
		 sb.append('		</a>');
		 sb.append('		<div class="mui-media-body" style="height:40px;">');
 		 sb.append('           <div class="cnt">');
  		 sb.append('          	<ul>');
  		 sb.append('              	<li class="wg1"><span class="price1">¥'+v.market_price+'</span><br><span class="price2">¥'+v.shop_price+'</span></li>');
   		 sb.append('                	<li class="wg2 praise_product" id="praise_product_'+v.productid+'" data-id="'+v.productid+'"><span class="mui-icon iconfont icon-tezan"></span>'+v.praise+'</li>');
		 if (v.hasproperty == "1")
		 { 
			 sb.append('               <li class="wg3"><a href="detail.php?from=index&productid='+v.productid+'"><span class="mui-icon iconfont icon-xinzeng special-button-color"></span></a> </li>');
		 }
		 else
		 {
			 sb.append('               <li class="wg3"><a class="add_shoppingcart" data-id="'+v.productid+'" href="javascript:;"><span class="mui-icon iconfont icon-xinzeng special-button-color"></span></a> </li>');
 		 }
		 sb.append('                </ul>');
		 sb.append('            </div>');
		 sb.append('		</div> ');
		 sb.append('</li> ');
	 }
	 return sb.toString(); 
}

function product_html(v,index) 
{ 
	if (indexcolumns == 1)
	{
		return product_singlerow_html(v,index);
	}
	else if (indexcolumns == 2)
	{
		if (product_count % 2 == 1)
		{
			product_count = product_count + 1;
			return product_doublerow_html(v,'left');
		}
		else
		{   
			product_count = product_count + 1;
			return product_doublerow_html(v,'right');
		}
		
	}
	else if (indexcolumns == 3) //12121212
	{
		if (product_count % 3 == 1)
		{
			product_count = product_count + 1;
			return product_singlerow_html(v,index);
		} 
		else
		{
			if (product_count % 3 == 2)
			{
				product_count = product_count + 1;
				return product_doublerow_html(v,'left');
			}
			else
			{   
				product_count = product_count + 1;
				return product_doublerow_html(v,'right');
			}
		} 
	}
	else if (indexcolumns == 4) //112112112
	{
		if (product_count % 4 == 1 || product_count % 4 == 2)
		{
			product_count = product_count + 1;
			return product_singlerow_html(v,index);
		} 
		else
		{
			if (product_count % 4 == 3)
			{
				product_count = product_count + 1;
				return product_doublerow_html(v,'left');
			}
			else
			{   
				product_count = product_count + 1;
				return product_doublerow_html(v,'right');
			}
		} 
	}
	else if (indexcolumns == 5) //221221
	{
		if (product_count % 5 == 0)
		{
			product_count = product_count + 1;
			return product_singlerow_html(v,index);
		} 
		else
		{
			if (product_count % 5 == 1 || product_count % 5 == 3)
			{
				product_count = product_count + 1;
				return product_doublerow_html(v,'left');
			}
			else
			{   
				product_count = product_count + 1;
				return product_doublerow_html(v,'right');
			}
		} 
	}
	else if (indexcolumns == 6) //11121112
	{
		if (product_count % 5 == 1 || product_count % 5 == 2 || product_count % 5 == 3)
		{
			product_count = product_count + 1;
			return product_singlerow_html(v,index);
		} 
		else
		{
			if (product_count % 5 == 4 )
			{
				product_count = product_count + 1;
				return product_doublerow_html(v,'left');
			}
			else
			{   
				product_count = product_count + 1;
				return product_doublerow_html(v,'right');
			}
		} 
	}
	else if (indexcolumns == 7) //22212221
	{
		if (product_count % 7 == 0)
		{
			product_count = product_count + 1;
			return product_singlerow_html(v,index);
		} 
		else
		{
			if (product_count % 7 == 1 || product_count % 7 == 3 || product_count % 7 == 5)
			{
				product_count = product_count + 1;
				return product_doublerow_html(v,'left');
			}
			else
			{   
				product_count = product_count + 1;
				return product_doublerow_html(v,'right');
			}
		} 
	}
	else
	{
		
	} 
}
    function add_more() {
		   if (lock == 0) 
		   {
		   	    lock = 1;
				setTimeout(function() { 
				var page = Zepto('#page').val();
		            Zepto('#page').val(parseInt(page) + 1);
		            mui.ajax({
		                type: 'POST',
		                url: "index.ajax.php",
		                data: 'page=' + page,
		                success: function(json) {  
		                    var msg = eval("("+json+")");
		                    if (msg.code == 200) { 
			                    var index = 1;
		                        Zepto.each(msg.data, function(i, v) {  
									    var nd = product_html(v,index); 
									    index += 1;
			                            Zepto('.list').append(nd);  
		                        });    
								//mui(document).imageLazyload({ placeholder: 'images/lazyload.png' });  
		                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
								$(".lazy").lazyload();
		                    } else {
		                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
		                    }
		                }
		            });   
				 }, 500); 
		   } 
		   setTimeout(function() { lock = 0 }, 3000); 
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
{if $loginswap eq '0' }
   function loginlog()
   {ldelim} 
   mui.ajax({ldelim} 
       type: 'POST',
       url: "loginlog.php",
       data: 'm=' + Math.random(),
       success: function(json) {ldelim}   {rdelim}
   {rdelim}); 
   {rdelim}
   setTimeout(loginlog,100);
{/if}
</script>

{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script> 
</body> 
</html>