 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>收货地址管理</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />   
	<link href="public/css/mui.picker.css" rel="stylesheet" /> 
    <link href="public/css/mui.listpicker.css" rel="stylesheet" />
    <link href="public/css/mui.poppicker.css" rel="stylesheet" />
	<link href="public/css/parsley.css" rel="stylesheet" >
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>    

    <script src="public/js/parsley.min.js"></script>   
    <script src="public/js/parsley.zh_cn.js"></script>
	  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/mui.picker.js"></script>
	<script src="public/js/mui.listpicker.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/mui.poppicker.js" type="text/javascript" charset="utf-8"></script>
	<script src="public/js/city.data.js" type="text/javascript" charset="utf-8"></script> 
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	
	<style>
	{literal} 
	 .img-responsive { display: block; height: auto; width: 100%; }  
 
	 
	.mui-bar-tab .mui-tab-item .mui-icon {
	  width: auto;
	} 
 	 
	.mui-tab-item .mui-icon {
	  font-size: 16px;
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
	.mui-listpicker ul li {
	  overflow: hidden;
	  white-space:nowrap; 
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
				{if $supplier_info.showheader eq '0'}
				<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
					 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
					 <h1 class="mui-title">银行卡管理</h1>
				</header> 
				{/if} 
				<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">  
					{if $bankcardinfo.recordid neq ''}
					<a class="mui-tab-item delete" href="bank.php?type=delete&record={$bankcardinfo.recordid}">  
						<span class="mui-icon iconfont icon-shanchu01">&nbsp;删除</span>
					</a>  
					{/if}
					<a class="mui-tab-item save" href="#">  
						<span class="mui-icon iconfont icon-save">&nbsp;保存</span>
					</a>
				</nav>
				 
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
		            <div class="mui-scroll">
						<div class="mui-card" style="margin: 0 3px;"> 
							 <form class="mui-input-group" name="frm" id="frm" method="post" action="bank.php"  parsley-validate>
									<input name="type" value="submit" type="hidden" >
									<input name="record" type="hidden" value="{$bankcardinfo.recordid}">
			 					<div class="mui-input-row">
			 						<label><span>收款人</span>:</label>
			 						<input required="required" id="realname" value="{$bankcardinfo.realname}" name="realname" type="text" class="mui-input-clear required" maxlength="20" placeholder="您的真实姓名">
			 					</div>
			 					<div class="mui-input-row">
			 						<label>银行账号:</label>
			 						<input required="required" parsley-rangelength="[16,20]" id="account" name="account" value="{$bankcardinfo.account}" type="number" class="mui-input-clear required" maxlength="11" placeholder="银行账号" parsley-error-message="请输入正确的银行账号">
			 					</div> 
			 					<div class="mui-input-row">
			 						<label>开户行:</label>
									<select name="bank" id="bank"  class="required" required="required"  >											
								               <option value="" >请选择银行</option>  
											   <option value="中国农业银行" {if $bankcardinfo.bank eq '中国农业银行'}selected{/if}>中国农业银行</option>  
											   <option value="中国工商银行" {if $bankcardinfo.bank eq '中国工商银行'}selected{/if}>中国工商银行</option>  
											   <option value="中国建设银行" {if $bankcardinfo.bank eq '中国建设银行'}selected{/if}>中国建设银行</option>  
											   <option value="中国银行" {if $bankcardinfo.bank eq '中国银行'}selected{/if}>中国银行</option>  
											   <option value="交通银行" {if $bankcardinfo.bank eq '交通银行'}selected{/if}>交通银行</option> 
											   <option value="交通银行" {if $bankcardinfo.bank eq '中国邮政储蓄银行'}selected{/if}>中国邮政储蓄银行</option>
											   <option value="交通银行" {if $bankcardinfo.bank eq '长沙银行'}selected{/if}>长沙银行</option>
											   <option value="交通银行" {if $bankcardinfo.bank eq '农村商业银行'}selected{/if}>农村商业银行</option>  
									 </select>
			 					</div> 
			 					  
						     </form>  
					    </div>
					</div>
				</div>
				<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;bottom: 50px;">
					<ul class="mui-table-view" style="background-color: #efeff4;">
						<li class="mui-table-view-cell mui-media">
							<img class="img-responsive" src="/images/baozhang.png">
						</li>
					</ul>
				</nav>
		    </div>
	    </div> 
	<script> 
	{literal}  
	    
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
	        },
	    });
		mui.ready(function() { 
			mui('#pullrefresh').scroll();   
			 
			mui('.mui-bar').on('tap','a.delete',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			});  
			mui('.mui-bar').on('tap','a.save',function(e){ 
				var validate = Zepto( '#frm' ).parsley( 'validate' );
				if (validate)
				{
					document.frm.submit();
				} 
			});  
			 
		});  
	 
        
	{/literal} 
	</script>
{include file='weixin.tpl'} 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>