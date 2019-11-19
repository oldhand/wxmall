 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>收货地址管理</title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />    
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script> 
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
					 <h1 class="mui-title">发票管理</h1>
				</header> 
				{/if} 
				<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">  
					<a class="mui-tab-item mui-active back" href="submitorder.php?fapiao=">  
						<span class="mui-icon iconfont icon-quxiao2">&nbsp;取消</span>
					</a> 
					<a class="mui-tab-item mui-active ok" href="#">  
						<span class="mui-icon iconfont icon-queren01">&nbsp;确定</span>
					</a>
				</nav>
				 
		        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
		            <div class="mui-scroll">
						<div class="mui-card" style="margin: 0 3px;">
							 <form class="mui-input-group">
			 					<div class="mui-input-row">
			 						<label>单位全称:</label>
			 						<input type="text" id="fapiao" value="{$fapiao}" class="mui-input-clear" maxlength="40" placeholder="您的单位全称">
			 					</div> 
						     </form>  
					    </div>
						<ul class="mui-table-view" style="background-color: #efeff4;">
							<li class="mui-table-view-cell mui-media"> 
									<img class="img-responsive" src="/images/baozhang.png"> 
							</li>
						</ul>	
					</div>
				</div>
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
			 
			mui('.mui-table-view').on('tap','a.deliveraddress-edit-cell',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			}); 
			mui('.mui-bar').on('tap','a.back',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			});  
			mui('.mui-bar').on('tap','a.ok',function(e){
				var fapiao = Zepto("#fapiao").val();  
				mui.openWindow({
					url: 'submitorder.php?fapiao='+fapiao,
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