 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>支付成功</title>
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
						 {if $orderinfo.tradestatus eq 'trade'}
						 支付成功
						 {else}
						 正在等待微信返回付款通知
						 {/if}
					 </h1>
				</header> 
				{/if} 
				 {if $profile_info.mobile eq ''}
					<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;"> 
						 <a id="defaultTab" class="mui-tab-item" href="/index.php" style="width:30%">  
							<span class="mui-icon iconfont icon-dianpu" style="top:0px;"></span>
							<span id="save_button">回到首页</span>
						</a>
						<a id="save" class="mui-tab-item save" href="#" style="width:30%">  
							<span class="mui-icon iconfont icon-save" style="top:0px;"></span>
							<span id="save_button">保存</span>
						</a>
					</nav>
				{else}
				    {include file='footer.tpl'} 
				{/if} 
				 
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">  
		                <div class="mui-scroll"> 
							<div class="mui-card" style="margin: 0 3px;"> 
								 <input id="needverifycode" value="{$needverifycode}" type="hidden" >
								 <input id="orderid" name="orderid"  value="{$orderinfo.orderid}" type="hidden" > 
								 <input id="tradestatus" name="tradestatus"  value="{$orderinfo.tradestatus}" type="hidden" > 
								 <input id="notify"  value="0" type="hidden" >
						         <ul class="mui-table-view">  
									        {if $orderinfo.tradestatus eq 'trade'}
						                    <li class="mui-table-view-cell" id="paymentsuccess-wrap"> 
  											        <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-dingdanchenggong icon-paymentsuccess"></span></div>
													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">{$businesse_info.businessename}恭喜您: </p> 
  														<p class='mui-ellipsis paymentsuccess'>订单支付成功！</p>  
  													</div> 
						                     </li>
											{else}
						                    <li class="mui-table-view-cell" id="paymentsuccess-wrap" style="display:none;"> 
  											        <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont icon-dingdanchenggong icon-paymentsuccess"></span></div>
													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">{$businesse_info.businessename}恭喜您: </p> 
  														<p class='mui-ellipsis paymentsuccess'>订单支付成功！</p>  
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
										    {/if} 
			                                <li class="mui-table-view-cell"> 
													<div class="mui-media-body  mui-pull-left">
														<span class="mui-table-view-label">订单号：</span>  <span class="ordersn">{$orderinfo.order_no}</span><br> 
														<span class="mui-table-view-label">订单总额：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.sumorderstotal}</span></b>
														</span><br> 
														{if $orderinfo.vipdeductionmoney neq '0'}
														<span class="mui-table-view-label">折扣优惠：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.vipdeductionmoney}</span></b>
														</span><br>
														{/if}
														{if $orderinfo.userank eq '1'}
														<span class="mui-table-view-label">积分抵扣：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.rankmoney}</span></b>
														</span><br>
														{/if}
														<span class="mui-table-view-label">余额支付：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.usemoney}</span></b>
														</span><br> 
														<span class="mui-table-view-label">微信支付：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.paymentamount}</span></b>
														</span><br> 
														<span class="mui-table-view-label">卡券优惠：</span>
														    <span class="totalprice">￥<b><span style="font-size: 16px;">{$orderinfo.discount}</span></b>
														</span>
													</div>  
			                                </li>
			                               
											{if $profile_info.mobile eq ''}
			                                <li class="mui-table-view-cell">
												<div class="mui-media-body" style="text-align:center;color:#cc3300"> 
													 您的订单目前无法保存，请正确填写您的手机！  
												</div>
			                                </li> 
		      							    <form class="mui-input-group" name="frm" id="frm" method="post" action="completepayment.php"  parsley-validate>
											    <input  name="record"  value="{$orderinfo.orderid}" type="hidden" > 
												<input  name="type"  value="submit" type="hidden" > 
				   			 					<div class="mui-input-row" style="margin-top:3px;">
				   			 						<label style="height:45px;">手机号码:</label>
				   			 						<input required="required" parsley-rangelength="[11,11]" id="mobile" name="mobile" value="" type="number" style="font-size: 12px;" class="mui-input-clear required" maxlength="11" placeholder="您的常用手机号码" parsley-error-message="请输入正确的手机号码">
				   			 					</div>  
												{if $needverifycode eq 'yes'}
				   			 					<div class="mui-input-row" style="margin-top:3px;">
				   			 						<div class="mui-media-body mui-col-sm-8 mui-pull-left" style="padding-left:10px;">
					   			 						<input required="required" parsley-rangelength="[6,6]" id="verifycode" name="verifycode" value="" type="number" style="font-size: 12px;" class="required" maxlength="6" placeholder="6位验证码" parsley-error-message="请输入正确的验证码">
 													</div>
				   			 						<div class="mui-media-body mui-col-sm-3 mui-pull-right" style="padding-right:1px;">
										 		        <button id="send_verifycode" disabled type="button" class="mui-btn mui-btn-success" style="width:100%;">获取验证码</button>
					   			 					 </div>
				   			 					</div> 
												{/if} 
												<!--
				   			 					<div class="mui-input-row" style="margin-top:3px;">
				   			 						<label style="height:45px;">登录密码:</label>
				   			 						<input required="required" parsley-rangelength="[5,20]" id="password" name="password" value="" type="password" style="font-size: 12px;" class="mui-input-clear required" maxlength="11" placeholder="您的登录密码" >
				   			 					</div>-->
				                                <div id="verifycode-wrap" class="mui-table-view-cell" style="margin-top:3px;display:none;">
													<div id="verifycode-errormsg" class="mui-media-body" style="color:#cc3300;text-align:center"> 
														 验证码错误，请输入正确的验证码！ 
													</div>
				                                </div>
											</form> 
											{else}
			                                <li class="mui-table-view-cell">
			                                    <a href="orderdetail.php?record={$orderinfo.orderid}" class="mui-navigate-right orderlink"> 
													<div class="mui-media-body" style="text-align:center;">
														 点击查看<b style="color:#CF2D28">【订单详情】</b>
													</div> 
												</a>
			                                </li>
			                                <li class="mui-table-view-cell">
			                                    <a href="orders_payment.php" class="mui-navigate-right orderlink"> 
													<div class="mui-media-body" style="text-align:center;">
														 点击查看<b style="color:#CF2D28">【全部已付款订单】</b>
													</div> 
												</a>
			                                </li>
			                                <li class="mui-table-view-cell">
			                                    <a href="usercenter.php" class="mui-navigate-right orderlink"> 
													<div class="mui-media-body" style="text-align:center;">
														 点击查看<b style="color:#CF2D28">【用户中心】</b>
													</div> 
												</a>
			                                </li>
											{/if}
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
			mui('.mui-bar').on('tap','a.save',function(e){ 
				var validate = Zepto( '#frm' ).parsley( 'validate' );
				if (validate)
				{
					var mobile = Zepto('#mobile').val();  
					var verifycode = Zepto('#verifycode').val();
					var needverifycode = Zepto('#needverifycode').val();
					var password = Zepto('#password').val(); 
		            mui.ajax({
		                type: 'POST',
		                url: "verifycode.php",
		                data: 'mobile=' + mobile + '&verifycode=' + verifycode + '&needverifycode=' + needverifycode + '&password=' + password +'&type=submit&m='+ Math.random(),
		                success: function(json) 
						{  
			                var jsondata = eval("("+json+")");
			                if (jsondata.code == 200) 
							{   
   							    var orderid = Zepto('#orderid').val(); 
   						 	    window.location.href = 'completepayment.php?record='+orderid; 
							}
							else
							{
								Zepto('#verifycode-wrap').css("display",''); 
								Zepto('#verifycode-errormsg').html(jsondata.msg); 
							} 
						}  
		            }); 
					//document.frm.submit();
				} 
			}); 
			mui('.mui-table-view').on('tap','button#send_verifycode',function(e){ 
				var validate = Zepto( '#mobile' ).parsley( 'validate' );
				if (validate)
				{
					sendverifycode(120);
				} 
			}); 
			mui('.mui-table-view').on('change', 'input#mobile', function() { 
				var validate = Zepto( '#mobile' ).parsley( 'validate' );
				if (validate)
				{ 
					 Zepto('#send_verifycode').prop("disabled",'');
				} 
				else
				{
					 Zepto('#send_verifycode').prop("disabled",'disabled');
				}
			}); 
		}); 
		function sendverifycode(time)
		{
		    var newtime = time - 1;
			if (newtime == 0)
			{
				Zepto('#mobile').prop("disabled",''); 
				Zepto('#send_verifycode').prop("disabled",'');
			    Zepto("#send_verifycode").removeClass("mui-btn-danger");  
				Zepto("#send_verifycode").addClass("mui-btn-success");  
				Zepto('#send_verifycode').html('重新获取验证码');
			}
			else
			{
				if (time == 120)
				{
					var mobile = Zepto('#mobile').val();  
		            mui.ajax({
		                type: 'POST',
		                url: "verifycode.php",
		                data: 'mobile=' + mobile +'&type=send&m='+ Math.random(),
		                success: function(json) 
						{ 
			                var jsondata = eval("("+json+")");
			                if (jsondata.code == 200) 
							{   
								Zepto('#verifycode-wrap').css("display",'none'); 
								Zepto('#verifycode-errormsg').html(''); 
								Zepto('#mobile').prop("disabled",'disabled'); 
								Zepto('#send_verifycode').prop("disabled",'disabled');
							    Zepto("#send_verifycode").removeClass("mui-btn-success");  
								Zepto("#send_verifycode").addClass("mui-btn-danger");  
								Zepto('#send_verifycode').html('发送成功<span class="mui-badge mui-btn-danger">'+newtime+'</span>'); 
								setTimeout('sendverifycode('+newtime+');', 1000);
							}
							else
							{
								Zepto('#verifycode-wrap').css("display",''); 
								Zepto('#verifycode-errormsg').html(jsondata.msg); 
							} 
						}  
		            }); 
				}
				else
				{
					Zepto('#send_verifycode').html('发送成功<span class="mui-badge mui-btn-danger">'+newtime+'</span>'); 
					setTimeout('sendverifycode('+newtime+');', 1000);
				}
			}
		    
		}
	    function check_notify(time) 
		{
			    var orderid = Zepto('#orderid').val();  
	            mui.ajax({
	                type: 'POST',
	                url: "payment.ajax.php",
	                data: 'record=' + orderid +'&m='+ Math.random(),
	                success: function(json) {  
	                    var msg = eval("("+json+")");
	                    if (msg.code == 200) 
						{   
							Zepto('#notify').val('1'); 
							if (mask != null)
							{
								mask.close(); 
							}
							 Zepto('#pagetitle').html('支付成功'); 
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