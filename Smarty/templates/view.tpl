<!DOCTYPE html>
   <html> 
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
       <title>商家资讯</title>
       <link href="public/css/mui.css" rel="stylesheet" />
       <link href="public/css/public.css" rel="stylesheet" />
     	<link href="public/css/iconfont.css" rel="stylesheet" />  
        <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	   	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
		<script src="public/js/lazyload.min.js" type="text/javascript" charset="utf-8"></script>
	   	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	   	<script type="text/javascript" src="public/js/jweixin.js"></script> 
   	<style>
   	  {literal} 
   		 .img-responsive { display: block; height: auto; width: 100%; } 
		 .article_info p { color:#333; }   
   	 {/literal} 
   	</style>
{include file='theme.tpl'} 
   </head>
   <body>  
 
   		<div class="mui-inner-wrap">
			{if $supplier_info.showheader eq '0'}
   			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
   				 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
   				 <h1 class="mui-title">商家资讯</h1> 
   			</header> 
			{/if} 
   			{include file='footer.tpl'}   
   	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                       <div class="mui-scroll">   
						     <h4 class="mui-content-padded" style="text-align:center;">{$article_info.articletitle}</h4>
					         <div  style="margin: 5px 5px;text-align:center"> 
									 <img class="mui-media-object" style="border-radius: 6px;width: 214px;height: auto;" src="{$article_info.image}">
						     </div>
      		                 <div id="list" class="mui-table-view" >     
										<ul class="mui-table-view" >
		  				                      <div class="mui-content-padded article_info"> 
		  				                          <p>
		  				  							 {$article_info.articletext}
		  				                          </p>
		  									   </div>
										</ul> 
										<ul class="mui-table-view" style="background-color: #efeff4;">
											<li class="mui-table-view-cell mui-media"> 
													<img class="img-responsive" src="/images/baozhang.png"> 
											</li>
										</ul> 
   						 </div>    
                    </div>
   			</div>
   	    </div>  
	      
   	<script type="text/javascript"> 
   	{literal}	 
   	    mui.init({
   	        pullRefresh: {
   	            container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等  
   	        },
   	    });
   		mui.ready(function() {  
   			mui('#pullrefresh').scroll();
			$(".lazy").lazyload();
   			mui('.mui-bar').on('tap','a',function(e){
   				mui.openWindow({
   					url: this.getAttribute('href'),
   					id: 'info'
   				});
   			});  
   	   }); 
   	{/literal} 
   	</script>
   {include file='weixin.tpl'} 
   <script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
   </body> 
   </html>