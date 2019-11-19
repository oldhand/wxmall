 

<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet" />
    <link href="public/css/public.css" rel="stylesheet" />
	<link href="public/css/iconfont.css" rel="stylesheet" />  
	<link href="public/css/parsley.css" rel="stylesheet" >  
    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>   
	<script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>   
	<script type="text/javascript" src="public/js/jweixin.js"></script>   
	<script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
	
	 

    <script src="public/js/parsley.min.js"></script>   
    <script src="public/js/parsley.zh_cn.js"></script>
	<style>
	  {literal} 
		  .img-responsive { display: block; height: auto; width: 100%; }   
	  	  .mui-input-row label { 
	  		  line-height: 21px; 
	  		  height: 21px;  
	  	  }
		  .menuicon
		  {
  	 		color:#fe4401; 
  	 		padding-right:5px;
		  }
		  .mui-grid-view .mui-media {
		    color: #fe4401; 
		    padding: 5px;
		  }
		  #orders .mui-table-view.mui-grid-view .mui-table-view-cell { 
		    padding: 10px 0 5px 0; 
			font-size: 1.4em;
		  }
		  #orders .mui-table-view.mui-grid-view .mui-table-view-cell .mui-icon {  
			font-size: 2.0em; 
		  }
		  #orders .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {
		    font-size: 12px; 
		    text-overflow: clip;
		    color: #333;
		  } 
		  #orders .mui-icon .mui-badge {
		    font-size: 10px;  
		    line-height: 1.4;
		    position: absolute;
		    top: 0px;
		    left: 100%;
		    margin-left: -40px;
		    padding: 1px 5px;
		    color: red;
		    background: white;
			border: 1px solid red;
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
			 {if $supplier_info.presalesconsultation eq '0'}
                 <a href="webim.php" class="mui-icon mui-action-menu mui-icon-chat mui-twinkling mui-pull-right"></a>
                 {/if}
			 <h1 class="mui-title" id="pagetitle">推广中心</h1>
		</header>  
		{/if} 
	    {include file='footer.tpl'}   
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                <div class="mui-scroll"> 
					<div class="mui-card" style="margin: 0 3px;">  
				         <ul class="mui-table-view">   
	                                <li class="mui-table-view-cell"> 
											<div class="mui-media-body" > 
										        <a href="javascript:;">
													<a href="usercenter.php?profileid={$profile_info.profileid}" class="refreshprofile"><img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}"></a>
													<div class="mui-media-body">
														<p class='mui-ellipsis' style="color:#333">昵称：{$profile_info.givenname}</p>
														<p class='mui-ellipsis' ><span style="color:#333">等级：</span>{include file='profilerank.tpl'}</p>
					
													</div>
												</a>
 											</div>  
	                                </li>  
			                        {if $profile_info.physicalstorename neq ''}
			                            <li class="mui-table-view-cell">
			                                <div class="mui-media-body  mui-pull-left">
			                                    <span class="mui-table-view-label">店铺：</span> 【{$profile_info.physicalstorename}】
			                                </div>
			                                <div class="mui-media-body  mui-pull-right">
			                                    <span class="mui-table-view-label">店员：</span> 【{$profile_info.assistantprofile}】
			                                </div>
			                            </li>
			                        {/if}
						  </ul>
					</div> 
					<div class="mui-card" style="margin: 3px 3px;">  
				         <ul class="mui-table-view" style="text-align:center;background:{$supplier_info.themecolor};color:{$supplier_info.textcolor};">    
			                        <li class="mui-table-view-cell"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-table-view-label">获得收益：</span> <span class="price">￥{$promotioncenter.expectedprofit}</span>
										</div>  
										<div class="mui-media-body  mui-pull-right">
											<span class="mui-table-view-label">预计收益：</span> <span class="price">￥{$promotioncenter.captureprofit}</span>
										</div>
			                        </li> 
						  </ul> 
					</div>
					<div class="mui-card" style="margin: 3px 3px;">  
						  <ul class="mui-table-view">   
		                        <li class="mui-table-view-cell"> 
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">推广订单：</span> <span class="price">{$promotioncenter.ordercount} 笔</span>
									</div>  
									<div class="mui-media-body  mui-pull-right">
										<span class="mui-table-view-label">累计付款金额：</span> <span class="price">¥ {$promotioncenter.totalprice}</span>
									</div>
		                        </li>
		                        <li class="mui-table-view-cell"> 
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">累计粉丝：</span> <span class="price">{$promotioncenter.funs} 人</span>
									</div>  
									<div class="mui-media-body  mui-pull-right">
										<span class="mui-table-view-label">本月新增粉丝：</span> <span class="price">{$promotioncenter.thismonthaddfuns} 人</span>
									</div>
		                        </li>
							    {if $profile_info.isphysicalstore eq '1' || $profile_info.isassistant eq '1'}
		                        <li class="mui-table-view-cell"> 
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">顾客数：</span> <span class="price">{$promotioncenter.assistants} 人</span>
									</div>  
									<div class="mui-media-body  mui-pull-right">
										<span class="mui-table-view-label">本月新增顾客数：</span> <span class="price">{$promotioncenter.thismonthaddassistants} 人</span>
									</div>
		                        </li>
								{/if} 
						  </ul>
					</div>
					
 				   <div class="mui-card" style="margin: 3px 3px;background:{$supplier_info.themecolor};">  
 					   <div class="mui-content-padded" style="margin:0px;margin-top: 5px;"> 
 	               			<a href="physicalstore_popularize.php"><h5 class="show-content" style="padding: 10px;"><span class="mui-icon iconfont icon-hezuo"></span> 我要推广</h5></a>
 	  				   </div> 
 				   </div>
 					<div class="mui-card" style="margin: 3px 3px;">  
 				         <ul id="orders" class="mui-table-view" style="padding-top: 5px;text-align:center;">   
	                             <li class="mui-table-view-cell">
	                                 <a href="physicalstore_popularize.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-bangzhu menuicon button-color"></span>如何推广
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											多种推广方案供您选择
										</div>
									</a>
	                             </li>
	                             <li class="mui-table-view-cell">
	                                 <a href="physicalstore_products.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-shangpin menuicon button-color"></span>商品列表
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											查看全部可分佣商品
										</div>
									</a>
	                             </li> 
	                             <li class="mui-table-view-cell">
	                                 <a href="physicalstore_billwaters.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-shouyi menuicon button-color"></span>收益明细
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											查看推广收益明细
										</div>
									</a>
	                             </li>
	                             {if $profile_info.isphysicalstore eq '1'}
	                             <li class="mui-table-view-cell">
	                                 <a href="physicalstore.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-shitidian menuicon button-color"></span>实体店列表
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											查看商城内全部实体店
										</div>
									</a>
	                             </li> 
	                             <li class="mui-table-view-cell">
	                                 <a href="physicalstore_assistant.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-fuwuyuan menuicon button-color"></span>店员列表
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											查看商城内全部店员
										</div>
									</a>
	                             </li>
								 {/if}
								 {if $profile_info.isphysicalstore eq '1' || $profile_info.isassistant eq '1'}
	                             <li class="mui-table-view-cell">
	                                 <a href="physicalstore_profile.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-guke menuicon button-color"></span>顾客列表
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											查看属于您的全部顾客
										</div>
									</a>
	                             </li>
								{/if}
                                {if $supplier_info.isassistant neq '0'}
                                <li class="mui-table-view-cell">
                                    <a href="lianmengtui.php" class="mui-navigate-right"> 
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-lianmengtuiguang menuicon button-color"></span>联盟推广
										</div> 
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;"> 
											查看全部由您引荐的会员
										</div>
									</a>
                                </li>
                                {/if}
								{if $supplier_info.allowqrcode eq '0'}
								<li class="mui-table-view-cell">
									<a href="qrcodecard.php" class="mui-navigate-right">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-erweima menuicon button-color"></span>推广名片
										</div>
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
											您的专属推广二维码名片
										</div>
									</a>
								</li>
								{/if}
								{if $supplier_info.distributionmode neq '0'}
								<li class="mui-table-view-cell">
									<a href="mydistribution.php" class="mui-navigate-right">
										<div class="mui-media-body  mui-pull-left">
											<span class="mui-icon iconfont icon-fenxiao menuicon button-color"></span>我的分销
										</div>
										<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
											您的三级分销粉丝
										</div>
									</a>
								</li>
								{/if}
 						 </ul>  
 			       </div> 
				   
				   {include file='copyright.tpl'}
				</div>
		</div>
		
		<!-- end pullrefresh  -->
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
			mui('.mui-content-padded').on('tap','a',function(e){
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