 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>提现申请</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />   
	<link href="public/css/parsley.css" rel="stylesheet" >  
		
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	
    <script src="public/js/parsley.min.js"></script>   
    <script src="public/js/parsley.zh_cn.js"></script>
	
	<style>
	  {literal} 
		 .img-responsive { display: block; height: auto; width: 100%; }  
	   	 .mui-bar .tit-sortbar{
	   	 	left: 0;
	   	 	right: 0;
	   	 	margin-top: 45px; 
	   	 }
		 .mui-bar .mui-segmented-control {
		   top: 0px; 
		 }
		 #submit_button
		 { 
			 font-size: 20px;
			 padding-left: 5px;
		 }
		 .mui-segmented-control.mui-segmented-control-inverted .mui-control-item {
		   color: #333; 
		 } 
	 	 .price {
	 	  color:#fe4401;
	 	 } 
	  	 .mui-table-view-cell .mui-table-view-label
	  	 {
	  	    width:90px;
	  		text-align:right;
	  		display:inline-block;
	  	 } 
		 
		 
		 
		 
		 
 	  	.mui-input-row label { 
 	  	  text-align: right; 
 	  	  width: 30%;
		  font-family: "Microsoft Yahei",微软雅黑,Arial,宋体,Arial Narrow,serif;
 	  	}  
 	  	.mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea {
 	  	  float: right;
 	  	  width: 70%; 
		  font-size: 12px;
		  padding: 10px 15px;
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
	</style>
	{include file='theme.tpl'} 
</head>
<body>  
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper"> 
		<div class="mui-inner-wrap">
			{if $supplier_info.showheader eq '1'}
			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;background-color:#fff">   
                 <div class="mui-title mui-content tit-sortbar" id="sortbar" style="margin-top: 0px;"> 
		 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
		 					<a class="mui-control-item mui-active" href="takecashs.php">提现申请</a>
		 					<a class="mui-control-item" href="takecashlogs.php">历史提现记录</a> 
		 				</div> 
                 </div>
			</header>
			{else}
			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
				 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
				 <h1 class="mui-title">提现</h1>
                 <div class="mui-title mui-content tit-sortbar" id="sortbar"> 
		 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
		 					<a class="mui-control-item mui-active" href="takecashs.php">提现申请</a>
		 					<a class="mui-control-item" href="takecashlogs.php">历史提现记录</a> 
		 				</div> 
                 </div>
			</header>  
			{/if}
			<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;"> 
				{if $takecashs.takecash eq 'open'}
					<a class="mui-tab-item mui-active submit" href="#" style="width:30%">  
						<span class="mui-icon iconfont icon-save" id="submit_icon" style="top:0px;"></span><span id="submit_button" >确定提交</span>
					</a>
				{else}
					<a class="mui-tab-item mui-active" href="index.php" style="width:30%">  
						<span class="mui-icon iconfont icon-queren01" style="top:0px;"></span><span style="font-size: 20px;padding-left: 5px;">返回首页</span>
					</a>
				{/if}
			</nav>
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '1'}40px;{else}85px;{/if}">  
                    <div class="mui-scroll">   
   		                 <div id="list" class="mui-table-view" >     
	 							<ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;"> 
									<div class="mui-card" style="margin: 3px 3px;"> 
									 <li class="mui-table-view-cell"> 
											<div class="mui-media-body  mui-pull-left">
												<span class="mui-table-view-label">当前可用资金：</span><span class="price">¥{$takecashs.money}</span>
											</div> 
	                                </li>  
								    </div>
								    <!--
									<div class="mui-card" style="margin: 3px 3px;">
		                                <li class="mui-table-view-cell"> 
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">分享收益：</span> <span class="price">¥{$takecashs.share}</span> 
												</div>  
												<div class="mui-media-body mui-pull-right" style="text-align:right;">  
													{if $takecashs.allow_share eq '1'} 【可提现】 {else} 【不可提现】{/if}
												</div>
		                                </li>
		                                <li class="mui-table-view-cell"> 
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">提成收益：</span> <span class="price">¥{$takecashs.commission}</span>
												</div>  
												<div class="mui-media-body mui-pull-right" style="text-align:right;">  
													{if $takecashs.allow_commission eq '1'} 【可提现】 {else} 【不可提现】{/if}
												</div>
		                                </li>
		                                <li class="mui-table-view-cell"> 
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">推广收益：</span> <span class="price">¥{$takecashs.popularize}</span>
												</div> 
												<div class="mui-media-body mui-pull-right" style="text-align:right;">  
													{if $takecashs.allow_popularize eq '1'} 【可提现】 {else} 【不可提现】{/if}
												</div> 
		                                </li>
									 </div>
									<div class="mui-card" style="margin: 3px 3px;">
										 <li class="mui-table-view-cell"> 
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">总收益：</span><span class="price">¥{$takecashs.total}</span>
												</div> 
		                                 </li>
										 <li class="mui-table-view-cell"> 
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">已提现资金：</span><span class="price">¥{$takecashs.historytakecash}</span>
												</div> 
		                                </li>
		                                <li class="mui-table-view-cell"> 
												<div class="mui-media-body  mui-pull-left">
													<span class="mui-table-view-label">可提现金额：</span> <span class="price">¥{$takecashs.allowtakecash}.00</span>
												</div>  
												<div class="mui-media-body mui-pull-right" style="text-align:right;">  
													【达到<span class="price">¥{$takecashs.takecashlimit}</span>可提现】
												</div>
		                                </li>
									</div> 
									-->
									{if $takecashs.msg neq ''}
									<div class="mui-card" style="margin: 3px 3px;">
										 <li class="mui-table-view-cell"> 
												<div class="mui-media-body" style="text-align: center;color: #fe4401;">
													<span>{$takecashs.msg}</span>
												</div> 
		                                 </li> 
									</div>
									{/if}
									{if $takecashs.takecash eq 'open'}
										<form class="mui-input-group" name="frm" id="frm" method="post" action="takecashs.php"  parsley-validate>
										<input  id="type" name="type"  value="submit" type="hidden" > 
										<input  id="token" name="token" value="{$takecashs.token}" type="hidden" >
									
										<div class="mui-card" style="margin: 3px 3px;"> 
											 <div class="mui-input-row">
			                                    <label style="height:45px;">手机号码:</label> 
			                                    <input readonly id="mobile" name="mobile" value="{$profile_info.mobile}" type="number" style="font-size: 12px;" > 
			                                 </div>
			                                {if $needverifycode eq 'yes'}
			                                    <div class="mui-input-row" style="margin-top:3px;" id="verifycode-wrap">
			                                        <div class="mui-media-body mui-col-sm-8 mui-pull-left" style="padding-left:10px;">
			                                            <input required="required" parsley-rangelength="[6,6]" id="verifycode"
			                                                   name="verifycode" value="" type="number" style="font-size: 12px;"
			                                                   class="required" maxlength="6" placeholder="6位验证码"
			                                                   parsley-error-message="请输入正确的验证码">
			                                        </div>
			                                        <div class="mui-media-body mui-col-sm-3 mui-pull-right" style="padding-right:1px;">
			                                            <button id="send_verifycode" type="button" class="mui-btn mui-btn-success" style="width:100%;">获取验证码
			                                            </button>
			                                        </div>
			                                    </div>
			                                {/if} 
											<div class="mui-input-row">
			                                    <a href="bank.php" class="mui-navigate-right" style="color:#000"> 
													<label style="height:45px;">到账银行卡：</label> 
													<input readonly id="account" value="{$takecashs.account}" type="text" style="font-size: 12px;"  class="mui-input-clear required"  >  
												</a>
			                                </div> 
											<div class="mui-input-row">
						 						<label style="height:45px;">银行:</label>  
												<input readonly id="bank" disabled name="bank" value="{$takecashs.bank}" type="text" style="font-size: 12px;"  class="mui-input-clear required"  > 
						 					</div> 
			   			 					<div class="mui-input-row" style="margin-top:3px;">
			   			 						<label style="height:45px;">收款人姓名:</label>
			   			 						<input readonly id="realname" disabled name="realname" value="{$takecashs.realname}" type="text" style="font-size: 12px;" class="mui-input-clear required"  >
			   			 					</div>
			   			 					<div class="mui-input-row" style="margin-top:3px;">
			   			 						<label style="height:45px;">提现金额:</label>
			   			 						<input id="amount" name="amount" placeholder="代扣银行手续费" value="" type="number" style="font-size: 12px;" class="mui-input-clear number required" parsley-min="{$takecashs.takecashlimit}"  parsley-max="{$takecashs.allowtakecash}">
			   			 					</div> 
										</div>
										</form>
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
			 mui('.mui-table-view').on('tap', 'button#send_verifycode', function (e) { 
	                sendverifycode(120); 
	        });
			mui('.mui-bar').on('tap','a.submit',function(e){   
				 
				var validate = Zepto( '#frm' ).parsley( 'validate' );
				 
				if (validate)
				{
					if (Zepto('#type').val() != "submit") return; 
					Zepto('#type').val("");
					var bank = Zepto('#bank').val();  
					var account = Zepto('#account').val();
					var realname = Zepto('#realname').val();
					var amount = Zepto('#amount').val(); 
					var verifycode = Zepto('#verifycode').val();
					var token = Zepto('#token').val();
					 
					Zepto("#submit_button").html('正在提交,请等待!'); 
				    Zepto("#submit_icon").removeClass("icon-save"); 
					Zepto("#submit_icon").addClass("icon-loading1");  
					Zepto("#submit_icon").addClass("mui-rotation");  
			        mui.ajax({
			            type: 'POST',
			            url: "takecashs.php",
			            data: 'type=submit&bank='+bank+'&account='+account+'&realname='+realname+'&amount='+amount+'&verifycode='+verifycode+'&token='+token,
			            success: function(json) {  
			                var jsondata = eval("("+json+")");
			                if (jsondata.code == 200) {    
								Zepto("#submit_button").html('提交成功'); 
							    Zepto("#submit_icon").removeClass("icon-loading1"); 
								setTimeout("window.location.href = 'takecashs.php';",500); 
			                } 
							else
							{
								 mui.toast(jsondata.msg); 
								 setTimeout("back_takecashs();",500);
							} 
						  }
			        }); 
				} 
			}); 
	   }); 
	   
	function back_takecashs()
	{
		Zepto('#type').val("submit");
		Zepto("#submit_button").html('提交'); 
	    Zepto("#submit_icon").removeClass("icon-loading1"); 
		Zepto("#submit_icon").addClass("icon-save");  
		Zepto("#submit_icon").removeClass("mui-rotation");  
	}
	function onbankchange(bank)
	{
		if (bank == "支付宝")
		{
			$("#account_label").html("支付宝账号:");  
		}
		else if (bank == "微信号")
		{
			$("#account_label").html("微信号:");  
		}
		else
		{
			$("#account_label").html("银行账号:");  
		} 
	}
	
	function sendverifycode(time) {
        var newtime = time - 1;
        if (newtime == 0) { 
            Zepto('#send_verifycode').prop("disabled", '');
            Zepto("#send_verifycode").removeClass("mui-btn-danger");
            Zepto("#send_verifycode").addClass("mui-btn-success");
            Zepto('#send_verifycode').html('重新获取验证码');
        }
        else {
            if (time == 120) {
                var mobile = Zepto('#mobile').val();
                mui.ajax({
                    type: 'POST',
                    url: "takecashs.php",
                    data: 'mobile=' + mobile + '&type=send&m=' + Math.random(),
                    success: function (json) {
                        var jsondata = eval("(" + json + ")");
                        if (jsondata.code == 200) {
                            Zepto('#verifycode-wrap-div').css("display", 'none');
                            Zepto('#verifycode-errormsg').html('');
                            Zepto('#mobile').prop("disabled", 'disabled');
                            Zepto('#send_verifycode').prop("disabled", 'disabled');
                            Zepto("#send_verifycode").removeClass("mui-btn-success");
                            Zepto("#send_verifycode").addClass("mui-btn-danger");
                            Zepto('#send_verifycode').html('发送成功<span class="mui-badge mui-btn-danger">' + newtime + '</span>');
                            setTimeout('sendverifycode(' + newtime + ');', 1000);
                        }
                        else {
                            Zepto('#verifycode-wrap-div').css("display", '');
                            Zepto('#verifycode-errormsg').html(jsondata.msg);
                        }
                    }
                });
            }
            else {
                Zepto('#send_verifycode').html('发送成功<span class="mui-badge mui-btn-danger">' + newtime + '</span>');
                setTimeout('sendverifycode(' + newtime + ');', 1000);
            }
        }

    }
	{/literal} 
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>