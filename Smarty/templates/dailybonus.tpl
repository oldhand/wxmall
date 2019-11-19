 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>每日签到</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
	<link href="public/css/parsley.css" rel="stylesheet" >  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>   
	<script type="text/javascript" src="public/js/jweixin.js"></script>   
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	
	  
	<style>
	  {literal} 
		  .img-responsive { display: block; height: auto; width: 100%; } 
   		  .checking
   		  {
   			   color: #cc3300;
   			   font-weight:900;
   			   font-size: 1.1em; 
   			   height:30px;
   			   line-height: 30px;
			   padding-top: 5px;
   		  }
   		  .icon-checking
   		  {
   			   color: #cc3300; 
   			   font-size: 2.8em;   
   		  } 	 
		  .paymentsuccess
		  {
			   color: #008537;
			   font-weight:900;
			   font-size: 1.1em; 
			   height:30px;
			   line-height: 30px;
		  }
		  .icon-paymentsuccess
		  {
			   color: #008537; 
			   font-size: 3.8em;  
			   padding-top: 5px;
		  }
		  .mui-table-view-cell .mui-table-view-label
		  {
		  	    width:60px;
		  		text-align:right;
		  		display:inline-block;
		  } 
		  .totalprice {color:#CF2D28; font-size:1.2em; font-weight:500; }
	  
		  .ordersn { font-size:1.2em; font-weight:500; } 
		  
	  	  .mui-input-row label { 
	  		  line-height: 21px; 
	  		  height: 21px;  
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
					 <a id="mui-action-back" class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title" id="pagetitle">
						每日签到
					 </h1>
				</header> 
				{/if}  
				{include file='footer.tpl'}  
				 
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">  
		                <div class="mui-scroll"> 
							<div class="mui-card" style="margin: 0 3px;"> 
								 <input id="needverifycode" value="{$needverifycode}" type="hidden" >
								 <input id="orderid" name="orderid"  value="{$orderinfo.orderid}" type="hidden" > 
								 <input id="tradestatus" name="tradestatus"  value="{$orderinfo.tradestatus}" type="hidden" > 
								 <input id="notify"  value="0" type="hidden" >
						         <ul class="mui-table-view">  
									        
						                    <li class="mui-table-view-cell" id="paymentsuccess-wrap"> 
						                    		{if $dailybonus.status eq 'ok'}
  											        <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-dingdanchenggong icon-paymentsuccess"></span></div>
													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">恭喜您: </p> 
  														<p class='mui-ellipsis paymentsuccess'>{$dailybonus.msg}</p> 
  														
  													</div> 
  													{else}
  													 <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-dingdanchenggong icon-paymentsuccess"></span></div>
													<div class="mui-media-body" style="padding-top:10px;"> 
  														<p class='mui-ellipsis paymentsuccess'>{$dailybonus.msg}</p> 
  														
  													</div> 
  													{/if}
						                     </li> 
			                               
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
	    var mask = null;   
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
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
			mui('.mui-table-view').on('tap','a.orderlink',function(e){
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