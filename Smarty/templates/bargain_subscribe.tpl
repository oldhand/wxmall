 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
    <link href="public/css/iconfont.css" rel="stylesheet" /> 
	  
	<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
	<script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>  
	<script type="text/javascript" src="public/js/jweixin.js"></script> 
	<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>  
	
	
	<style>
	{literal}
	 .name,.detail{ -webkit-margin-start:0px;  }
	 .img-responsive { display: block; height: auto; width: 100%; } 
	 .price1 {text-decoration:line-through; color:#000;  }  
	 .price3 {text-decoration:line-through; color:#999;  } 
	 .price2 {padding-left:5px;color:#CF2D28; font-size:1.2em; font-weight:500; }
	 .price1 span,.price2 span,span.price{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; margin-left:0}
	 .price2 span {font-size:1.1em}
	 
	 .totalprice{
	   color:#CF2D28; 
	   margin-top: 9px;
	 }
	 #inventory_label
	 {
		color:#CF2D28; 
		font-size: 16px;
	 } 
 	 
	.mui-table-view-cell:after { 
	  left: 0px; 
	}
	{/literal}
	</style>
	{include file='theme.tpl'}  
</head>

<body> 
        <!-- 主页面容器 -->
        <div class="mui-inner-wrap">
            <!-- 主页面标题 -->
			{if $supplier_info.showheader eq '0'}
            <header class="mui-bar mui-bar-nav"> 
				{if $bargain eq '1'}
                <h1 class="mui-title">砍价成功!</h1>
				{elseif $bargain eq '2'}
				 <h1 class="mui-title">哼！你记着！</h1>
				 {else}
				 <h1 class="mui-title">快来参加砍价活动</h1>
				{/if}
            </header> 
			{/if}  
             
            <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                <div class="mui-scroll"> 
                    <!--detail-->
					<input type="hidden" id="productid" value="{$productinfo.productid}">
					<input type="hidden" id="salesactivityid" value="{$productinfo.salesactivityid}">
                    <div class="mui-content-padded">
                        <p style="margin-bottom: 0;">
                            <span class="detail-title">{$productinfo.productname}</span>
                        </p> 
						<ul id="propertygroup" class="mui-table-view" style="background: #f3f3f3;"> 
							{if $bargain eq '1'}
						 	<li class="mui-table-view-cell" >
								 <span class="mui-pull-left" style="color:#CF2D28; font-size:1.2em; font-weight:500;">感谢帮我砍价！若您也想参加这个活动，长按二维码图片，即可参与！</span> 
							</li>  
							{elseif $bargain eq '2'}
						 	 <li class="mui-table-view-cell" >
								 <span class="mui-pull-left" style="color:#CF2D28; font-size:1.2em; font-weight:500;">虽然你不帮我！但我还是推荐你参加这个活动，长按二维码图片，即可参与！</span> 
							</li>
							{else}
							<li class="mui-table-view-cell" >
								 <span class="mui-pull-left" style="color:#CF2D28; font-size:1.2em; font-weight:500;">也想参加活动，只需长按二维码图片，即可参与！</span> 
							{/if}
							<li class="mui-table-view-cell mui-media" style="padding: 0px;">
								<img class="img-responsive" src="bargain_qrcode.php">
							</li>
						</ul>  
                         
                        </div>
						{include file='copyright.tpl'}
                    </div> 
                    <!--end detail--> 
                </div> 
            </div>
            <div class="mui-backdrop" style="display:none;"></div>
        </div> 
	


	<script type="text/javascript"> 
	{literal} 
	    mui.init({
	        pullRefresh: {
	            container: '#pullrefresh' 
	        },
	    }); 
		mui.ready(function() { 
			mui('#pullrefresh').scroll(); 
			mui('#copyright').on('tap','a',function(e){
				mui.openWindow({
					url: this.getAttribute('href'),
					id: 'info'
				});
			});
		 
		}); 
	{/literal}
	</script> 
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>   
</html>