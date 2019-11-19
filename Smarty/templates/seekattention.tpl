<!DOCTYPE html>
   <html> 
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
       <title>{$profile_info.givenname}邀请您关注</title>
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
   	 {/literal} 
   	</style>
	{include file='theme.tpl'} 
   </head>
   <body>  
   		<div class="mui-inner-wrap"> 
   			{include file='footer.tpl'}    
   	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: 0px;">  
                       <div class="mui-scroll">   
      		                 <div id="list" class="mui-table-view" >     
    											<ul class="mui-table-view" >
    												<li class="mui-table-view-cell mui-media" style="padding: 0px;"> 
    														<img class="img-responsive" src="seekattention_qrcode.php"> 
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