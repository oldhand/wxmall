<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>{if $islogined neq 'true' || $supplier_info.showheader eq '1'}分类{/if}</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
	<script src="public/js/mui.lazyload.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/mui.lazyload.img.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
    <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
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
	   height: 60px;
	   width: 60px; 
	 } 
	 
	{/literal}	
	</style>
	{include file='theme.tpl'}  
</head>

<body>  
    <!-- 侧滑导航根容器 -->
    <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
        <!-- 菜单容器 -->
        {include file='leftmenu.tpl'} 
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
            <!-- 主页面标题 -->
			{if $supplier_info.showheader eq '1'}
            <header class="mui-bar mui-bar-nav" style="height:40px;background-color:#fff"> 
                 <div class="mui-title tit-searchbar" style="margin-top: 0px;">
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
                 <h1 class="mui-title">分类</h1> 
                 <div class="mui-title tit-searchbar">
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
 				 		        <ul id="childcategorys_list" class="mui-table-view mui-grid-view mui-grid-9">
				                    {foreach from=$childcategorys item=v key=k}
	 				 		            <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
											<a href="search.php?categoryid={$v.id}"> 
												<span class="mui-icon"> <img class="img-responsive" src="{$v.picurl}"></span>
	 				 		                    <div class="mui-media-body">{$v.name}</div>
											</a>
										</li>
				                    {/foreach} 
											
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
            container: '#pullrefresh' 
        },
    }); 
	
	function category_html(categoryid,categoryname,picurl) {	 
			 var sb=new StringBuilder(); 
	            sb.append('<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">');
				sb.append('<a href="search.php?categoryid='+categoryid+'"> ');
				sb.append('<span class="mui-icon"> <img class="img-responsive" src="'+picurl+'"></span>');
	            sb.append('<div class="mui-media-body">'+categoryname+'</div>');
				sb.append('</a></li>');  
			 return sb.toString(); 
	 }
	function show_child_categorys(record) 
	{  
	        mui.ajax({
	            type: 'POST',
	            url: "category.php",
	            data: 'record=' + record,
	            success: function(json) {  
					Zepto('#childcategorys_list').html('');  
	                var msg = eval("("+json+")");
	                if (msg.code == 200) {   
	                    Zepto.each(msg.data, function(i, v) { 
							    var categoryid = v.id; 
							    var categoryname = v.name;
							    var picurl = v.picurl;
							    var pid = v.pid;  
							    var nd = category_html(categoryid,categoryname,picurl);
	                            Zepto('#childcategorys_list').append(nd);  
	                    });   
	                } 
	            }
	        }); 
	    } 
		
	mui.ready(function() {  
		mui('#categorys_list').on('tap', 'a', function(e) {
			var record =  this.getAttribute('data-id');
			show_child_categorys(record);
			Zepto(".main-category").addClass("category-view-cell"); 
			Zepto(this).parent().removeClass("category-view-cell");   
		});
		mui('#childcategorys_list').on('tap', 'a', function(e) {
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		});
		mui('.mui-bar').on('tap','a',function(e){
			mui.openWindow({
				url: this.getAttribute('href'),
				id: 'info'
			});
		})  
	});
{/literal}
</script>
 {include file='weixin.tpl'}  
 <script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>