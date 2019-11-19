 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>保证金支付成功</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
	<link href="public/css/parsley.css" rel="stylesheet" >  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>   
	<script type="text/javascript" src="public/js/jweixin.js"></script>   
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	
	 

    <script src="public/js/parsley.min.js"></script>   
    <script src="public/js/parsley.zh_cn.js"></script>
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
			   font-size: 1.8em; 
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
	 {if $profile_info.mobile eq ''}
	 {literal} 
		  .menuicon { font-size: 1.2em; color:#fe4401; padding-right:10px;} 
		  .menuitem a { font-size: 1.1em;} 
		  #save_button
		  { 
			 font-size: 20px;
			 color:#cc3300; 
			 padding-left: 5px;
		  }
		  .mui-bar-tab .mui-tab-item .mui-icon {
		   	   width: auto;
		  } 
	 	  .mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
	 	       color: #cc3300;
	 	  } 
		  
	  	.mui-input-row label { 
	  	  text-align: right; 
	  	  width: 30%;
	  	}  
	  	.mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea {
	  	  float: right;
	  	  width: 70%; 
	  	}
	  	.mui-input-row label { 
	  	  line-height: 21px; 
	  	  padding: 10px 10px;
	  	} 
	  	.mui-input-clear
	  	{
	  		font-size: 12px;
	  	}
		
		
	  	input.parsley-success,
	  	select.parsley-success,
	  	textarea.parsley-success {
	  	  color: #468847;
	  	  background-color: #DFF0D8;
	  	  border: 1px solid #D6E9C6;
	  	}

	  	input.parsley-error,
	  	select.parsley-error,
	  	textarea.parsley-error {
	  	  color: #B94A48;
	  	  background-color: #F2DEDE;
	  	  border: 1px solid #EED3D7;
	  	}

	  	.parsley-errors-list {
	  	  margin: 2px 0 3px;
	  	  padding: 0;
	  	  list-style-type: none;
	  	  font-size: 0.9em;
	  	  line-height: 0.9em;
	  	  opacity: 0;

	  	  transition: all .3s ease-in;
	  	  -o-transition: all .3s ease-in;
	  	  -moz-transition: all .3s ease-in;
	  	  -webkit-transition: all .3s ease-in;
	  	}

	  	.parsley-errors-list.filled {
	  	  opacity: 1;
	  	}
		
	  {/literal}
	  {/if}
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
						 正在等待微信返回付款通知 
					 </h1>
				</header>  
				{/if}  
               {include file='footer.tpl'}   
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}"> 
		                <div class="mui-scroll"> 
							<div class="mui-card" style="margin: 0 3px;">  
								 <input id="authenticationorderid" name="authenticationorderid"  value="{$authenticationorderid}" type="hidden" > 
								 <input id="tradestatus" name="tradestatus"  value="" type="hidden" > 
								 <input id="notify"  value="0" type="hidden" >
						         <ul class="mui-table-view">   
						                     
						                    <li class="mui-table-view-cell" id="paymentsuccess-wrap" style="display:none;"> 
  											        <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-dingdanchenggong icon-paymentsuccess"></span></div>
													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">恭喜您: </p> 
  														<p class='mui-ellipsis paymentsuccess'>保证金支付成功！</p>  
  													</div> 
						                     </li>
						                     <li class="mui-table-view-cell" id="checking-wrap"> 
											        <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-loading mui-rotation icon-checking"></span></div>
													<div class="mui-media-body"> 
														<p class='mui-ellipsis checking' id="checkmsg">正在等待微信返回付款通知【<span id="check_time">1</span>秒】...</p>  
													</div> 
										     </li>
 			                                <li class="mui-table-view-cell" id="checking-wrap-tip"> 
 													<div class="mui-media-body  mui-pull-left" style="color:red"> 
														 请不要关闭窗口，耐心等待，一会儿就好！ 
 													</div>  
 			                                </li>   
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left">
														<span class="mui-table-view-label">认证VIP：</span>  <span class="ordersn">{$rankname}</span><br>  
														<span class="mui-table-view-label">微信支付：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$amount}</span></b>
														</span> 
													</div>  
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
		    var tradestatus = Zepto('#tradestatus').val(); 
			if (tradestatus != 'trade')
			{
			    mask = mui.createMask(mask_func);//callback为用户点击蒙版时自动执行的回调；
				mask.show();//显示遮罩 
	            setTimeout("check_notify(1);", 1000);
		    } 
		 
		});  
	    function check_notify(time) 
		{
			    var authenticationorderid = Zepto('#authenticationorderid').val();  
	            mui.ajax({
	                type: 'POST',
	                url: "authentication_completepayment.ajax.php",
	                data: 'record=' + authenticationorderid +'&m='+ Math.random(),
	                success: function(json) {  
	                    var msg = eval("("+json+")");
	                    if (msg.code == 200) 
						{   
							Zepto('#notify').val('1'); 
							if (mask != null)
							{
								mask.close(); 
							}
							 Zepto('#pagetitle').html('保证金支付成功'); 
							 Zepto('#checking-wrap').css('display','none');
							 Zepto('#checking-wrap-tip').css('display','none');
							 Zepto('#paymentsuccess-wrap').css('display','');
						}
						else
						{
	   						 var newtime = time + 1;
	   						 Zepto('#check_time').html(newtime); 
	   						 setTimeout('check_notify('+newtime+');', 1000);
						}
					 
					 }
	            }); 
	       } 
		function mask_func()
		{  
			var notify = Zepto('#notify').val(); 
			if (notify == '1')
			{
				return true;
			}
			return false;
		}
	{/literal} 
	</script>
{include file='weixin.tpl'}  
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>  
</html>