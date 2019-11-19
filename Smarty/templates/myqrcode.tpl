<!DOCTYPE html>
   <html> 
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
       <title>我的二维码</title>
       <link href="public/css/mui.css" rel="stylesheet" />
       <link href="public/css/public.css" rel="stylesheet" />
   	<link href="public/css/iconfont.css" rel="stylesheet" />  
       <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
   	<script type="text/javascript" src="public/js/jweixin.js"></script> 
   	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
   	<script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
   	<script type="text/javascript" src="public/js/jweixin.js"></script> 
   	<style>
   	  {literal} 
   		 .img-responsive { display: block; height: auto; width: 100%; }   
		 .mui-table-view-cell:after { 
		   left: 0px; 
		 }
   	 {/literal} 
   	</style>
	{include file='theme.tpl'} 
   </head>
   <body>  
   <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
   	{include file='leftmenu.tpl'}  	
   		<div class="mui-inner-wrap">
			{if $supplier_info.showheader eq '0'}
   			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
   				 <a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
   				 <h1 class="mui-title">我的二维码</h1> 
   			</header> 
			{/if} 
   			{include file='footer.tpl'}   
   	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                       <div class="mui-scroll">   
      		                 <div id="list" class="mui-table-view" >     
    										<ul class="mui-table-view" >
												<div style="margin: 0 3px;">  
											         <ul class="mui-table-view">   
								                                <li class="mui-table-view-cell"> 
																		<div class="mui-media-body" > 
																	        <a href="javascript:;">
																				<img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}">
																				<div class="mui-media-body">
																					<p class='mui-ellipsis' style="color:#333">昵称：{$profile_info.givenname}</p>
																					<p class='mui-ellipsis' ><span style="color:#333">等级：</span>
																					             {foreach item=rank_info  from=$profile_info.rankinfo}
																								  <img src="{$rank_info}" style="width:20px;height:20px;"> 
																								 {/foreach}</p>
					
																				</div>
																			</a>
							 											</div>  
								                                </li> 
			    												<li class="mui-table-view-cell mui-media"> 
			    														<img alt="二维码" class="img-responsive" src="myqrcode_png.php"> 
			    												</li> 
																<p style="text-align:center;height:30px;line-height:30px;">收银员扫描二维码可以获取您的资料</p>
													  </ul>
													  
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