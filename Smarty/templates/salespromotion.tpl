<!DOCTYPE html>
   <html> 
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
       <title>促销活动</title>
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
		 .cp_miaoshu{width:100%;height:35px;position:absolute;right:0;bottom:0px;} 
		 .ms_right{width:75%;height:70px;float:right;} 
		 .tit{ 
			 width:100%;
			 height:30px;
			 text-align:left;
			 font-size:14px;
			 color:#000; 
			 line-height:30px;
			 padding-left:8px; 
			 overflow:hidden;
			 background-color: rgba(240,240,240,0.5);
			 border-top-left-radius:5px;
			 border-bottom-left-radius:5px;
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
   				 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
   				 <h1 class="mui-title">促销活动</h1>
                 
   			</header> 
			{/if} 
   			{include file='footer.tpl'}   
   	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                       <div class="mui-scroll">   
      		                 <div id="list" class="mui-table-view">    
								            <div style="height: 5px;">&nbsp;</div>
					 						<div class="mui-card" style="margin: 0 3px;"> 
					 								 <ul class="mui-table-view" style="color: #333;">
														  <li class="mui-table-view-cell mui-media" style="padding:3px;">
															    <a style="margin:0px;" href="newproducts.php"> 
					 									  	 	<img class="img-responsive" src="/images/xinhou.jpg"> 
																</a>
														  </li> 
					 						 	 	</ul> 
					 						 </div> 
											 
 					 						
											 
	  									  {foreach name="salesactivitys" key=salesactivityid item=salesactivity_info  from=$salesactivitylist} 
    					 						<div class="mui-card" style="margin: 0 3px;margin-top: 5px;"> 
    					 								 <ul class="mui-table-view" style="color: #333;"> 
    														  <li class="mui-table-view-cell mui-media" style="padding:3px;">
    								  								 <a style="margin:0px;" href="salesactivity.php?record={$salesactivity_info.id}">
    								  									 <img class="mui-media-object" style="width: 100%;max-width: 100%;height: auto;" src="{$salesactivity_info.homepage}">
    								   								 </a>
																	 <!--
    								  	 					        <div class="cp_miaoshu"> 
    								  	 					            <div class="ms_right">
    								  	 					            	<div class="tit">活动日期：{$salesactivity_info.begindate}至{$salesactivity_info.enddate}</div> 
    								  	 					            </div>
    								  	 					        </div>-->
    								  						</li> 
    					 						 	 	</ul> 
    					 						 </div>
	  							   		  {/foreach}
										  
    											 
   											<ul class="mui-table-view" style="background-color: #efeff4;margin-top: 5px;">
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
   			mui('.mui-table-view').on('tap','a',function(e){
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