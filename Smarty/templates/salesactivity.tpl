<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" /> 
	<link href="public/css/global.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="public/js/mui.lazyload.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/mui.lazyload.img.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<style>
	{literal}	 
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
	 	margin-top: 3px; 
		color:#000;
	 }
	  .mui-bar .tit-searchbar .mui-table-view:after { 
	   height: 0px; 
	 }
	 
	 .mui-bar .mui-title .mui-icon {
	   margin-top:10px; 
	 } 
	 
	 img {
	   vertical-align: middle;
	 }
	 .img-responsive {
	   display: block;
	   height: 60px;
	   width: 60px; 
	 } 
	 
	 .mui-bar .tit-searchbar .mui-search  input {
	   background-color: #fff; 
	 }
	 .mui-bar .tit-sortbar{
	 	left: 0;
	 	right: 0;
	 	margin-top: 45px;
	 	background: #1D85D0;
	 }
	 .tit-sortbar .mui-grid-view.mui-grid-9 .mui-table-view-cell>a:not(.mui-btn){
	 	padding: 0;
	 }
	 #list .price {color:#CF2D28; }
	 #list .price b {color:#CF2D28; font-size:1.4em; }
	 #list .link {color:#428bca; font-size:1.1em;  }
	 .icon-sort{ color:#fe4401; font-size:1.1em;padding-left:1px;}
	  
	
	 
	 .mui-grid-view.mui-grid-9 .mui-media {
	   color: #333; 
	 }
	 .mui-grid-view.mui-grid-9 .active {
	   color: #fe4401; 
	 }	
	 
	 
	 
	 #pullrefresh .mui-table-view.mui-grid-view > li:nth-child(2n+1) { 
	     border-left: 2px solid #8d9297;
		 border-right: 1px solid #8d9297;
	 }
	 
	 #pullrefresh .mui-table-view.mui-grid-view > li:nth-child(2n) {  
		 border-left: 1px solid #8d9297;
	     border-right: 2px solid #8d9297;
	 }
	 
	{/literal}	
	</style>
	{include file='theme.tpl'} 
	{if $supplier_info.themecolor neq ''}
		<style>
		<!--
		    #pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell {ldelim} background-color:{$supplier_info.themecolor};  {rdelim}
			#pullrefresh .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {ldelim} color: {$supplier_info.textcolor}; {rdelim}
			.cnt ul .wg1 {ldelim} border-right: 1px solid {$supplier_info.textcolor}; {rdelim}
			.cnt ul .wg2 {ldelim} border-right: 1px solid {$supplier_info.textcolor}; {rdelim}
			{if $supplier_info.themecolor eq $supplier_info.buttoncolor }
			 	.special-button-color {ldelim}  color: #fff;  {rdelim} 
			{else}
			 	.special-button-color {ldelim}  color: {$supplier_info.buttoncolor};  {rdelim} 
			{/if}
		-->
		</style>
	{/if}
</head>

<body>   
<!-- 侧滑导航根容器 -->
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper" style="position: fixed;">
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
            <!-- 主页面标题 -->
			{if $supplier_info.showheader eq '1'}
	            <header class="mui-bar mui-bar-nav" style="height:40px;background-color:#fff">   
	                  <div class="mui-title tit-sortbar" id="sortbar" style="margin-top: 0px;">
	                      <ul class="mui-table-view mui-grid-view mui-grid-9" style="line-height: 27px;">
	  						  <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item active" data-sort="asc" data-order="published">默认</li>
	                          <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item" data-sort="asc" data-order="price">价格<span id="icon_price" style="display:none" class="icon-sort iconfont icon-sort-desc"></span></li>
	                          <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item" data-sort="asc" data-order="salevalue">销量<span id="icon_salevalue" style="display:none" class="icon-sort iconfont icon-sort-desc"></li>
	                          <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item" data-sort="desc" data-order="praise">人气<span id="icon_praise" style="display:none" class="icon-sort iconfont icon-sort-desc"></li> 
	  				      </ul> 
	                  </div>
	            </header>
			{else}
	            <header class="mui-bar mui-bar-nav">   
	 				   <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
	                   <h1 class="mui-title">{$salesactivityinfo.activityname}</h1>  
	                  <div class="mui-title tit-sortbar" id="sortbar">
	                      <ul class="mui-table-view mui-grid-view mui-grid-9" style="line-height: 27px;">
	  						  <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item active" data-sort="asc" data-order="published">默认</li>
	                          <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item" data-sort="asc" data-order="price">价格<span id="icon_price" style="display:none" class="icon-sort iconfont icon-sort-desc"></span></li>
	                          <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item" data-sort="asc" data-order="salevalue">销量<span id="icon_salevalue" style="display:none" class="icon-sort iconfont icon-sort-desc"></li>
	                          <li class="mui-table-view-cell mui-media mui-col-xs-3 bar-item" data-sort="desc" data-order="praise">人气<span id="icon_praise" style="display:none" class="icon-sort iconfont icon-sort-desc"></li> 
	  				      </ul> 
	                  </div>
	            </header>
			{/if}
			{include file='footer.tpl'}   
             <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '1'}40px;{else}80px;{/if}"> 
                 <div class="mui-scroll">   
						 <input id="page"  type="hidden" value="1">
						 <input id="salesactivityid"  type="hidden" value="{$salesactivityinfo.id}"> 
						 <input id="sort"  type="hidden" value="asc">
						 <input id="order"  type="hidden" value="published">
		                 <ul id="list" class="mui-table-view mui-grid-view list" >  
				 			     
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
	mui.ready(function() { 
		mui('#pullrefresh').scroll();   
		mui('.mui-bar').on('tap','a',function(e){
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		}); 
		mui('#list').on('tap','a',function(e){
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		});
		mui('#sortbar').on('tap','li.mui-media',function(){
			var order =  this.getAttribute('data-order'); 
			var sort =  this.getAttribute('data-sort');
			Zepto(".bar-item").removeClass("active"); 
			Zepto(this).addClass("active"); 
			if (order == "price") 
			{
				Zepto("#icon_price").css("display",""); 
				if (sort == "asc")
				{
					Zepto("#icon_price").removeClass("icon-sort-asc");
					Zepto("#icon_price").addClass("icon-sort-desc");
					Zepto(this).attr("data-sort","desc");
				}
				else
				{
					Zepto("#icon_price").removeClass("icon-sort-desc");
					Zepto("#icon_price").addClass("icon-sort-asc");
					Zepto(this).attr("data-sort","asc");
				}
				
				Zepto("#icon_salevalue").css("display","none"); 
				Zepto("#icon_praise").css("display","none");  
			}
			else if (order == "salevalue") 
			{
				Zepto("#icon_salevalue").css("display",""); 
				if (sort == "asc")
				{
					Zepto("#icon_salevalue").removeClass("icon-sort-asc");
					Zepto("#icon_salevalue").addClass("icon-sort-desc");
					Zepto(this).attr("data-sort","desc");
				}
				else
				{
					Zepto("#icon_salevalue").removeClass("icon-sort-desc");
					Zepto("#icon_salevalue").addClass("icon-sort-asc");
					Zepto(this).attr("data-sort","asc");
				}
				
				Zepto("#icon_price").css("display","none"); 
				Zepto("#icon_praise").css("display","none");  
			}
			else if (order == "praise") 
			{
				Zepto("#icon_praise").css("display",""); 
				if (sort == "asc")
				{
					Zepto("#icon_praise").removeClass("icon-sort-asc");
					Zepto("#icon_praise").addClass("icon-sort-desc");
					Zepto(this).attr("data-sort","desc");
				}
				else
				{
					Zepto("#icon_praise").removeClass("icon-sort-desc");
					Zepto("#icon_praise").addClass("icon-sort-asc");
					Zepto(this).attr("data-sort","asc");
				}
				
				Zepto("#icon_price").css("display","none"); 
				Zepto("#icon_salevalue").css("display","none");  
			}
			else
			{
				Zepto("#icon_price").css("display","none"); 
				Zepto("#icon_salevalue").css("display","none");
				Zepto("#icon_praise").css("display","none");  
			} 
			Zepto("#sort").val(sort);
			Zepto("#order").val(order);
            Zepto('#page').val(1);
            Zepto('.list').html('');  
            add_more();
			mui('#pullrefresh').pullRefresh().refresh(true);
		});
		mui('.mui-table-view').on('tap','a.add_shoppingcart',function(){
			var record =  this.getAttribute('data-id'); 
			var salesactivityid =  this.getAttribute('salesactivity-id'); 
			var salesactivitys_product_id =  this.getAttribute('salesactivitys_product-id');  
			add_shoppingcart(record,salesactivityid,salesactivitys_product_id);
		}); 
	});
		
	/**
	 * 下拉刷新具体业务实现
	 */
	function pulldownRefresh() { 
		Zepto('#sortbar').css("display","none");
		setTimeout(function() { 
            Zepto('#page').val(1);
            Zepto('.list').html(''); 
			Zepto('#sortbar').css("display","");
            add_more();
			mui('#pullrefresh').pullRefresh().refresh(true);
			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
			mui(document).imageLazyload({ placeholder: 'images/lazyload.png' }); 
		}, 1000);
	}
	  
	
	function add_shoppingcart(record,salesactivityid,salesactivitys_product_id) 
	{   
        mui.ajax({
            type: 'POST',
            url: "shoppingcart_add.ajax.php",
            data: 'record=' + record +"&salesactivityid="+salesactivityid+"&salesactivitys_product_id="+salesactivitys_product_id,
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
	function product_html(v) { 
 	   		  var sb=new StringBuilder();		
			 sb.append('     <li class="mui-table-view-cell mui-media mui-col-xs-6 doublerow">'); 
			 sb.append('         	<a href="detail.php?from=salesactivity&productid='+v.productid+'&salesactivityid='+v.salesactivityid+'">');
 			 sb.append('            <img id="product_item_'+v.productid+'" class="mui-media-object" src="images/lazyload.png" data-lazyload="'+v.productlogo+'">');
			 sb.append('             <div class="mui-media-body" style="padding-left:3px;">'+v.productname+'</div> ');
			 sb.append('     		</a>');
			 sb.append('     		<div class="mui-media-body" style="height:40px;">');
			 sb.append('                 <div class="cnt">');
			 sb.append('                 	<ul style="list-style: none;">');
			 sb.append('                     	<li class="wg1">'); 
			 sb.append('                         <span class="price1">¥'+v.shop_price+'</span><br> ');
			 sb.append('                         <span class="price2">¥'+v.promotional_price+'</span> ');
			 sb.append('                         </li>');
 			 sb.append('                       	<li class="wg2" style="width:34%"><span class="mui-icon iconfont icon-zhekou" style="font-size: 1.3em;"></span>'+v.label+'</li>');
			 sb.append('                         <li class="wg3" style="width:18%"><a  class="add_shoppingcart" salesactivitys_product-id="'+v.salesactivitys_product_id+'" salesactivity-id="'+v.salesactivityid+'" data-id="'+v.productid+'" href="javascript:;"><span class="mui-icon iconfont icon-xinzeng special-button-color" style="font-size: 1.8em;"></span></a> </li>');
			 sb.append('                     </ul>');
			 sb.append('                 </div>');
			 sb.append('     		</div> ');
			 sb.append('     </li> ');
			 return sb.toString(); 
	 }
	 
 	function bargain_product_html(v) { 
  	   		  var sb=new StringBuilder();		
 			 sb.append('     <li class="mui-table-view-cell mui-media mui-col-xs-6 doublerow">'); 
 			 sb.append('         	<a href="detail_bargain.php?from=salesactivity&productid='+v.productid+'&salesactivityid='+v.salesactivityid+'">');
  			 sb.append('            <img id="product_item_'+v.productid+'" class="mui-media-object" src="images/lazyload.png" data-lazyload="'+v.productlogo+'">');
 			 sb.append('             <div class="mui-media-body" style="padding-left:3px;">'+v.productname+'</div> ');
 			 sb.append('     		</a>');
 			 sb.append('     		<div class="mui-media-body" style="height:40px;">');
 			 sb.append('                 <div class="cnt">');
 			 sb.append('                 	<ul style="list-style: none;">');
 			 sb.append('                     	<li class="wg1">'); 
 			 sb.append('                         <span class="price1">¥'+v.shop_price+'</span><br> ');
 			 sb.append('                         <span class="price2">¥'+v.promotional_price+'</span> ');
 			 sb.append('                         </li>');
  			 sb.append('                       	<li class="wg2" style="width:34%"><span class="mui-icon iconfont icon-zhekou" style="font-size: 1.3em;"></span>砍价</li>');
 			 sb.append('                         <li class="wg3" style="width:18%"><a href="detail_bargain.php?from=salesactivity&productid='+v.productid+'&salesactivityid='+v.salesactivityid+'"><span class="mui-icon iconfont icon-xinzeng special-button-color" style="font-size: 1.8em;"></span></a> </li>');
 			 sb.append('                     </ul>');
 			 sb.append('                 </div>');
 			 sb.append('     		</div> ');
 			 sb.append('     </li> ');
 			 return sb.toString(); 
 	 }
						 
	    function add_more() {
			var page = Zepto('#page').val();
		    var salesactivityid = Zepto('#salesactivityid').val();
			var sort = Zepto('#sort').val();
		    var order = Zepto('#order').val(); 
	            Zepto('#page').val(parseInt(page) + 1);
	            mui.ajax({
	                type: 'POST',
	                url: "salesactivity.ajax.php",
	                data: 'page='+page+'&salesactivityid='+salesactivityid+'&order='+order+'&sort='+sort,
	                success: function(json) {   
	                    var msg = eval("("+json+")");
	                    if (msg.code == 200) {  
	                        Zepto.each(msg.data, function(i, v) {   
									 var nd;
						   			 if (v.activitymode == '1')
						   			 {
			 						 	 nd = bargain_product_html(v);
						   			 } 
									 else
									 {
									 	 nd = product_html(v);
									 }
								     
		                             Zepto('.list').append(nd);  
	                        });   
							mui(document).imageLazyload({ placeholder: 'images/lazyload.png' });   
	                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
	                    } else {
	                        mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
	                    }
	                }
	            }); 
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
</script>
{include file='weixin.tpl'}  
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>