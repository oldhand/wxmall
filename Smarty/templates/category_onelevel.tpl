<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>{if $islogined neq 'true' || $supplier_info.showheader eq '1'}分类{/if}</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />   
	<link href="public/css/category.css" rel="stylesheet" /> 
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="public/js/lazyload.min.js" type="text/javascript" charset="utf-8"></script> 
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
    <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>  
	
	<style>
	{literal}	
	<!-- 
	 .mui-search input[type='search']{
		margin-bottom: 0px; 
		padding: 5px 30px; 
	 } 
	 .category-view-cell
	 {
		border-right:1px solid #e8e8e8;
	 }
	 .mui-bar .tit-searchbar{
	 	left: 0;
	 	right: 0;
	 	margin-top: 40px; 
		color:#000;
	 }
	 
	 .mui-bar .mui-title .mui-icon {
	   margin-top:10px; 
	 } 
	 
	 img {
	   vertical-align: middle;
	 }
	 .img-responsive {
	   display: block;
	   height: auto;
	   width: 100%; 
	 } 
	 .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {
	   font-size: 12px;
	   height: 20px;
	   line-height: 20px;
	   margin-top: 0px;
	 }
	 .price {font-size: 14px;color:#CF2D28; }
	 .mui-grid-view.mui-grid-9 .mui-table-view-cell > a:not(.mui-btn) { 
	   padding: 0px;
	 }
	 .ball{position: absolute; width: 20px; height: 20px; background-color: #000; border-radius: 10px; }
	 
	 #pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell {
	   border-top: 2px solid #8d9297;  
	   margin: 0px;
	   padding: 0px;
	   background-color:#000;
	  
	 }
	 .mui-table-view-cell:after {  left: -1px; }
	 
	 .list {background-color:#8d9297;}
	 
	 .price1 {text-decoration:line-through; color:#8a8888;  }  
	 .price3 {text-decoration:line-through; color:#999;  } 
	 .price2 {color:#dcb66b; font-size:1.4em; font-weight:700; }
	 .price1 span,.price2 span,span.price{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; margin-left:0}
	 .price2 span {font-size:1.1em}
	 
	 .cnt{width:100%;height:40px;; font-size:14px;border-top: 1px solid #8d9297; }
	 .cnt ul{margin:0;padding:0;}
	 .cnt ul li{list-style-type:none;} 
	 .cnt ul .wg1{width:70%;height:40px;float:left;text-align:left;line-height:1.5em;padding-left:2px;font-size:12px;padding-top:1px;}
		 .cnt ul .wg3{width:30%;height:40px;float:left;}
	 .cnt ul .wg3 a{font-size:14px;display:block;color:#C30; text-decoration:none;text-align:center;padding-top:7px;width:100%;height:21px;}
	 
	
	
	{/literal}	
	{if $supplier_info.categoryscolumns eq '2'}
	{literal}	
		 #pullrefresh .mui-table-view.mui-grid-view > li:nth-child(2n+1) { 
		     border-left: 2px solid #8d9297;
			 border-right: 1px solid #8d9297;
		 }
 
		 #pullrefresh .mui-table-view.mui-grid-view > li:nth-child(2n) {  
			 border-left: 1px solid #8d9297;
		     border-right: 2px solid #8d9297;
		 }
		 #pullrefresh .mui-table-view.mui-grid-view { 
		   padding: 0px; 
		   border-bottom: 2px solid #8d9297;
		 }
		 .mui-table-view.mui-grid-view .mui-table-view-cell > a:not(.mui-btn) {
		   margin: 0px;
		 }
		 #pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {
		    text-align:left; 
		    color:#fff;
		 }
		 .mui-grid-view.mui-grid-9 .mui-media{  padding: 0px;  }

 
    {/literal}	
	{else}
	{literal}	
		#childcategorys_list .mui-table-view-cell {
		      padding: 5px 2px; 
		}
		#childcategorys_list .mui-table-view-cell > a:not(.mui-btn) {
		     margin: -5px -5px; 
		}
	    .mui-table-view-chevron .mui-table-view-cell > a:not(.mui-btn) {
		    margin-right: -78px;
		}
		 
		#childcategorys_list   .mui-media-object.mui-pull-left {
		    margin-right: 5px;
			padding : 0px; 
			margin-left : 5px; 
		    max-width: 100px;
		    height: 100px;
		}
	{/literal}	
	{/if}
 
  
 
 	 --> 
	 </style>
   
	{include file='theme.tpl'}  
	
	{if $supplier_info.themecolor neq ''}
		<style>
		<!--
		    #pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell {ldelim} background-color:{$supplier_info.productbackgroundcolor};  {rdelim}
			#pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {ldelim} color: {$supplier_info.textcolor}; {rdelim}
			{if $supplier_info.themecolor eq $supplier_info.buttoncolor }
			 	.special-button-color {ldelim}  color: #fff;  {rdelim} 
			{else}
			 	.special-button-color {ldelim}  color: {$supplier_info.buttoncolor};  {rdelim} 
			{/if}
			#pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell .price2 {ldelim}  color: {$supplier_info.productpricecolor};  {rdelim} 
		-->
		</style>
	{/if}
	
</head>

<body>  
    <!-- 侧滑导航根容器 -->
	<div id="animate-wrap"></div>
    <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
        <!-- 菜单容器 -->
        {include file='leftmenu.tpl'} 
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
            <!-- 主页面标题 -->
			{if $supplier_info.showheader eq '1'}
            <header class="mui-bar mui-bar-nav" style="height:40px;background-color:#fff"> 
                 <div class="mui-title tit-searchbar" id="searchbar" style="margin-top: 0px;">
                     <ul class="mui-table-view" style="line-height: 27px;">
			   			 <form action="search.php" onSubmit="return searchgo()">  
								  <div class="mui-table-view-cell" style="padding: 5px 10px;margin-top:-7px;">     
					   				<div class="mui-input-row mui-search">
					   					<input id="keywords" name="keywords" type="search" class="mui-input-clear" placeholder="搜索你喜欢的商品">
					   				</div> 
							     </div> 
					     </form>
 				    </ul> 
                 </div>
            </header>
			{else}
            <header class="mui-bar mui-bar-nav">
                 <a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
                 {if $supplier_info.presalesconsultation eq '0'}
                 <a href="webim.php" class="mui-icon mui-action-menu mui-icon-chat mui-twinkling mui-pull-right"></a>
                 {/if}
                 <h1 class="mui-title">{$supplier_info.suppliername}</h1> 
                 <div class="mui-title tit-searchbar" id="searchbar">
                     <ul class="mui-table-view" style="line-height: 27px;">
			   			 <form action="search.php" onSubmit="return searchgo()">  
								  <div class="mui-table-view-cell" style="padding: 5px 10px;margin-top:-7px;">     
					   				<div class="mui-input-row mui-search">
					   					<input id="keywords" name="keywords" type="search" class="mui-input-clear" placeholder="搜索你喜欢的商品">
					   				</div> 
							     </div> 
					     </form>
 				    </ul> 
                 </div>
            </header>
			{/if}
			{include file='footer.tpl'}   
             <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '1'}40px;{else}80px;{/if}"> 
                 <div class="mui-scroll">   
						  <!--<h5 class="mui-content-padded">优选商品</h5> --> 
				          
						  <!--<h6 class="mui-content-padded" style="height:1px;margin:0px">&nbsp;</h6>  -->     
		                  
						  <input id="page" name="page" type="hidden" value="1">
						  <input id="categoryid" name="categoryid" type="hidden" value="{$categoryid}">
						  <input id="categoryscolumns"  value="{$supplier_info.categoryscolumns}" type="hidden">  
	                      <ul id="list" class="mui-table-view list">
 							 <div class="mui-table-view  mui-pull-left" style="width:30%;" > 
 		 							 <ul id="categorys_list" class="mui-table-view">
 					                    {foreach name="categorys" from=$categorys item=v key=k}
 			 								<li class="mui-table-view-cell mui-media main-category {if $smarty.foreach.categorys.iteration > 1}category-view-cell{/if}">
 			 									<a class="mui-navigate-right" data-id="{$v.id}" href="javascript:;">
 			 										 <div class="mui-media-body">{$v.name}</div>
 			 									</a>
 			 								</li> 
 					                    {/foreach} 
 		 							</ul> 
 							</div>
 							<div class="mui-table-view-cell  mui-pull-right" style="width:70%;padding: 0px;"> 
								{if $supplier_info.categoryscolumns eq '2'}
								 	<ul id="childcategorys_list" class="mui-table-view mui-grid-view "> 
								{else}
									<ul id="childcategorys_list" class="mui-table-view mui-table-view-chevron">
								{/if} 
									</ul>
 							</div>
	                     </ul>   
                 </div> 
             </div> 
            <div class="mui-backdrop" style="display:none;"></div>
        </div>  
    </div>
 
<script type="text/javascript">
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
	
	function pulldownRefresh() {  
		Zepto('#searchbar').css("display","none");
		setTimeout(function() {   
			Zepto('#childcategorys_list').html(''); 
			Zepto('#page').val(0);
			Zepto('#searchbar').css("display","");
			add_more();
			mui('#pullrefresh').pullRefresh().refresh(true);
			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed 
			//mui(document).imageLazyload({ placeholder: 'images/lazyload.png' }); 
			$(".lazy").lazyload();
		}, 1000);
	}
    function add_more() {
		var page = Zepto('#page').val();
        Zepto('#page').val(parseInt(page,10) + 1);
		var record =  Zepto("#categoryid").val();
        show_child_categorys(record);    
    }  
function category_html(v,categoryscolumns) { 
   		 
   		  if (categoryscolumns == "2")
   		  {
	   		     var sb=new StringBuilder();	
			   	 sb.append('     <li class="mui-table-view-cell mui-media mui-col-xs-6">');
				 sb.append('         	<a href="detail.php?from=index&productid='+v.productid+'">');
				 sb.append('            <img id="product_item_'+v.productid+'" class="lazy img-responsive" src="images/lazyload.png" data-original="'+v.productlogo+'">');
				 sb.append('             <div class="mui-media-body" style="padding-left:3px;">'+v.productname+'</div> ');
				 sb.append('     		</a>');
				 sb.append('     		<div class="mui-media-body" style="height:40px;">');
				 sb.append('                 <div class="cnt">');
				 sb.append('                 	<ul style="list-style: none;">');
				 sb.append('                     	<li class="wg1">'); 
				 sb.append('                         <span class="price1">¥'+v.market_price+'</span><br> ');
				 sb.append('                         <span class="price2">¥'+v.shop_price+'</span> ');
				 sb.append('                         </li>'); 
				 if (v.hasproperty == '1')
				 {
				 		sb.append('                         <li class="wg3"><a  href="detail.php?from=index&productid='+v.productid+'"><span class="mui-icon iconfont icon-xinzeng special-button-color" style="font-size: 1.8em;"></span></a> </li>');
			 	 }
				 else
				 {
			 			sb.append('                         <li class="wg3"><a  class="add_shoppingcart" data-id="'+v.productid+'" href="javascript:;"><span class="mui-icon iconfont icon-xinzeng special-button-color" style="font-size: 1.8em;"></span></a> </li>');
		 	 	 }
				 sb.append('                     </ul>');
				 sb.append('                 </div>');
				 sb.append('     		</div> ');
				 sb.append('     </li> ');
				 return sb.toString(); 
 		  }	
 		  else
 		  {
	 	   		 return product_singlerow_classic_html(v);  
	 	  } 
 }
  function product_singlerow_classic_html(v) { 
	   var sb=new StringBuilder();
		 sb.append('<li class="mui-table-view-cell mui-left" style="min-height:104px;">');
		 if (v.hasproperty == "1")
		 { 
			 sb.append('<a class="mui-navigate-right" href="detail.php?from=index&productid='+v.productid+'">');
			 sb.append('        <img class="lazy mui-media-object mui-pull-left" id="product_item_'+v.productid+'" src="images/lazyload.png" data-lazyload="'+v.productthumbnail+'">');
			 sb.append('		<div class="mui-media-body" style="min-width:90px;">');
			 sb.append('			<p class="mui-ellipsis" style="color:#333;font-size:1.3em;">'+v.productname+'</p>'); 
			 if (v.vendorname != "")
			 {
				 sb.append('		    <p class="mui-ellipsis" style="padding-top:4px;">'+v.vendorname+'</p> ');  
		 		 sb.append('		    <p class="mui-ellipsis" style="padding-top:px;">市场价：¥'+v.market_price+'</p>');
				 sb.append('		    <p class="mui-ellipsis" style="padding-top:0px;">销售价：¥'+v.shop_price+'</p> ');
			 }
			 else
			 { 
		 		 sb.append('		    <p class="mui-ellipsis" style="padding-top:10px;">市场价：¥'+v.market_price+'</p>');
				 sb.append('		    <p class="mui-ellipsis" style="padding-top:10px;">销售价：¥'+v.shop_price+'</p> ');
			 }
			  
			 sb.append('		</div> ');  
			 sb.append('</a>'); 
			 sb.append('</li>'); 
		 }
		 else
		 {
			  sb.append('<a class="mui-navigate-right" href="javascript:;">');
	 		  sb.append('       <img data-url="detail.php?from=index&productid='+v.productid+'" class="lazy mui-media-object mui-pull-left detailimage" id="product_item_'+v.productid+'" src="images/lazyload.png" data-lazyload="'+v.productlogo+'">');
	 		  sb.append('		<div data-id="'+v.productid+'" class="mui-media-body add_shoppingcart" style="min-width:90px;">');
	 		  sb.append('			<p class="mui-ellipsis" style="color:#333;font-size:1.3em;">'+v.productname+'</p>'); 
 			 if (v.vendorname != "")
 			 {
 				 sb.append('		    <p class="mui-ellipsis" style="padding-top:4px;">'+v.vendorname+'</p> ');  
 		 		 sb.append('		    <p class="mui-ellipsis" style="padding-top:px;">市场价：¥'+v.market_price+'</p>');
 				 sb.append('		    <p class="mui-ellipsis" style="padding-top:0px;">销售价：¥'+v.shop_price+'</p> ');
 			 }
 			 else
 			 { 
 		 		 sb.append('		    <p class="mui-ellipsis" style="padding-top:10px;">市场价：¥'+v.market_price+'</p>');
 				 sb.append('		    <p class="mui-ellipsis" style="padding-top:10px;">销售价：¥'+v.shop_price+'</p> ');
 			 } 
	 		  sb.append('		</div> ');  
	 		  sb.append('</a>');
	 		  sb.append('</li>'); 
		 }
		
	   return sb.toString();
	  
  }

			
 function product_singlerow_html(v) { 
	 var sb=new StringBuilder();
		 sb.append('<li class="mui-table-view-cell mui-media singlerow" style="width:100%">');
 		 sb.append('<a href="detail.php?from=index&productid='+v.productid+'">');
		 sb.append('	 <img style="width:100%;height:auto;max-width:100%;" id="product_item_'+v.productid+'" class="lazy mui-media-object img-responsive"  src="images/lazyload.png" data-lazyload="'+v.productlogo+'">');
 		 sb.append('</a>');
		 sb.append('	 <div class="cp_miaoshu">');
		 sb.append('		 <div class="ms_left"></div>');
		 sb.append('		 <div class="ms_right">');
		 sb.append('			 <div class="tit">'+v.productname+'</div>');
		 sb.append('			 <div class="cnt">');
		 sb.append('				 <ul>');
		 sb.append('					 <li class="wg1"><span class="tit01">市场价:&nbsp;</span><span class="price1">¥'+v.market_price+'</span><br><span class="price2">¥'+v.shop_price+'</span><br></li>'); 		 
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
	 return sb.toString(); 
}
	 
	function show_child_categorys(record) 
	{  
		    var categoryid = Zepto("#categoryid").val();
			var page = Zepto("#page").val(); 			 			
	        mui.ajax({
	            type: 'POST',
	            url: "category.php",
	            data: 'categorylevel=1&record=' + record + "&page=" + page,
	            success: function(json) {   
	                var msg = eval("("+json+")");
	                if (msg.code == 200) {  
						if (msg.data.length == 0 )
						{ 
							 mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
						}
						else
						{
							var categoryscolumns = $("#categoryscolumns").val();
							Zepto.each(msg.data, function(i, v) {    
							    var nd = category_html(v,categoryscolumns);
	                            Zepto('#childcategorys_list').append(nd);   
	                        });    
							//mui(document).imageLazyload({ placeholder: 'images/lazyload.png' }); 
							$(".lazy").lazyload();
	                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
						} 
                    } else {
                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
                    }  
	            }
	        }); 
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
		mui('#pullrefresh').scroll();  
		mui('#categorys_list').on('tap', 'a', function(e) {
			Zepto('#childcategorys_list').html('');
			Zepto("#page").val('1');
			var record =  this.getAttribute('data-id');
			Zepto("#categoryid").val(record);
			show_child_categorys(record);
			Zepto(".main-category").addClass("category-view-cell"); 
			Zepto(this).parent().removeClass("category-view-cell");   
			Zepto(".mui-scroll").css("-webkit-transform","translate3d(0px, 0px, 0px) translateZ(0px)"); 			 			  
		});
		mui('#childcategorys_list').on('tap','a.add_shoppingcart',function(){  
			var record =  this.getAttribute('data-id');  
			add_shoppingcart(record);
		}); 
		mui('.mui-bar').on('tap','a',function(e){ 
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		});
		mui('#childcategorys_list').on('tap','img.detailimage',function(e){ 
			var url = this.getAttribute('data-url');
			mui.openWindow({
				url: url,
				id: 'info'
			});
			return false;  
		});
		mui('#childcategorys_list').on('tap','div.add_shoppingcart',function(e){ 
			var record =  this.getAttribute('data-id'); 
			add_shoppingcart(record); 
		});
		var categoryid = Zepto("#categoryid").val();
	    //触发第一页
	    setTimeout("show_child_categorys('"+categoryid+"')", 1);
	});
{/literal}
</script>
 {include file='weixin.tpl'}  
 <script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>