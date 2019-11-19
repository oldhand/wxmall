{if $supplier_info.showleftmenu eq '0'}
<style>
	<!--
	.user-info .mui-ellipsis .iconfont {ldelim}width:18px;color: #fff; {rdelim}
	-->
</style>
<aside class="mui-off-canvas-left">
    <div class="mui-scroll-wrapper">
        <div class="mui-scroll">
            <!-- 菜单具体展示内容 -->
            <div class="user-info">
				{if $profile_info.profileid neq 'anonymous'}
				   {if $profile_info.givenname neq ''}
				        <a href="javascript:;">
							<img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}">
							<div class="mui-media-body">
								{$profile_info.givenname}，您好！
								<p class='mui-ellipsis' >等级：{include file='profilerank.tpl'}</p>
							
							</div>
						</a>
						<p class='mui-ellipsis' style="margin:0px;">可用资金：<span class="price2">¥{$profile_info.money}</span></p>
						<p class='mui-ellipsis' style="margin:0px;">累积收益：<span>¥{$profile_info.accumulatedmoney}</span></p> 
						<p class='mui-ellipsis' style="margin:0px;">积分：<span>{$profile_info.rank}分</span></p> 
						<p class='mui-ellipsis' style="margin:0px;">累积积分：<span>{$profile_info.accumulatedrank}分</span></p> 
				   {else}
				        <a href="javascript:;">
							<img class="mui-media-object mui-pull-left" src="{if $supplier_info.logo neq ''}{$supplier_info.logo}{else}images/logo.png{/if}">
							<div class="mui-media-body">
								尊敬的客人，您好！<br> 
								<p class='mui-ellipsis'>关注之后内容更精彩!</p>   
							</div>
						</a>
				   {/if}
					
				{else}
			        <a href="javascript:;">
						<img class="mui-media-object mui-pull-left" src="{if $businesse_info.logo neq ''}{$businesse_info.logo}{else}images/logo.png{/if}">
						<div class="mui-media-body">
							{$businesse_info.businessename}
							<p class='mui-ellipsis'>注册之后内容更精彩!</p>
						</div>
					</a>
					<p>{$businesse_info.share_description}</p>
					<p style="text-align: center;">
						<a href="login.php" class="mui-btn mui-btn-outlined mui-btn-primary">登陆 </a>
						<a href="register.php" class="mui-btn mui-btn-outlined mui-btn-primary">注册 </a>
					</p>
				{/if}
			</div>
			<ul class="mui-table-view mui-table-view-chevron mui-table-view-inverted">
				<li class="mui-table-view-cell">
					<a href="index.php" class="mui-navigate-right mui-ellipsis"><span class="mui-icon iconfont icon-mainpage"></span> 店铺首页 </a>
				</li>
				<!--<li class="mui-table-view-cell">
					<a href="usercenter.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-gerenzhongxin"></span> 会员中心 
						<span class="left-desc"></span>
					</a>
				</li>-->
			    {assign var="badges" value=$share_info.badges} 
				<li class="mui-table-view-cell">
					<a href="accountbook.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-zhangdanchaxun"></span> 我的账簿 {if $badges.new_billwater eq 'yes'}<span style="font-size: 20px;padding: 1px 3px;" class="mui-badge mui-badge-danger iconfont icon-newbadge"></span>{/if}
						<span class="left-desc"></span>
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a href="orders_receipt.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-daichulidingdan"></span> 我的待处理订单{if $badges.new_order eq 'yes'}<span style="font-size: 20px;padding: 1px 3px;" class="mui-badge mui-badge-danger iconfont icon-newbadge"></span>{/if}
						<span class="left-desc"></span>
					</a>
				</li> 
				<li class="mui-table-view-cell">
					<a href="orders_payment.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-dingdan"></span> 全部已付款订单
						<span class="left-desc"></span>
					</a>
				</li>
				{if $supplier_info.allowtakecash eq '1'} 
					<li class="mui-table-view-cell">
						<a href="takecashs.php" class="mui-navigate-right mui-ellipsis">
							<span class="mui-icon iconfont icon-money"></span> 提现申请
							<span class="left-desc"></span>
						</a>
					</li>
				{/if}
				<li class="mui-table-view-cell">
					<a href="mycollections.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-shoucang"></span> 我的收藏
						<span class="left-desc"></span>
					</a>
				</li> 
				{if $supplier_info.showfubisi eq '0'} 
				<li class="mui-table-view-cell">
					<a href="fubusi.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-fubushi"></span> 福布斯榜
						<span class="left-desc"></span>
					</a>
				</li>
				{/if}
				<li class="mui-table-view-cell">
					<a href="contactus.php" class="mui-navigate-right mui-ellipsis">
						<span class="mui-icon iconfont icon-lianxiwomen"></span> 联系我们
						<span class="left-desc"></span>
					</a>
				</li>
				{if $sysinfo.http_user_agent eq 'tezan'} 
				    {assign var="copyrights" value=$supplier_info.copyrights}
				    <script type="text/javascript" charset="utf-8" src="/public/js/cordova.js"></script>
					<li class="mui-table-view-cell">
						<a id="back_tezan" class="mui-navigate-right mui-ellipsis">
							<span class="mui-icon iconfont icon-logo"></span> 返回{$copyrights.trademark}
							<span class="left-desc"></span>
						</a>
					</li>
					<script type="text/javascript"> 
					{literal}	
					mui.ready(function() {  						 
						document.getElementById('back_tezan').addEventListener('tap', function() {
							 Cordova.exec(null, null, "PhoneGapPlugin", "PluginFunction", [{"type":"historyback"}]); 
						});
					});  
					{/literal} 
					</script>
				{/if}
			</ul>
        </div>
    </div>
</aside>
{/if}