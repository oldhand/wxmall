{if $supplier_info.footerstyle eq '1'}  
<style>
	<!-- 
	.mui-bar-tab .mui-tab-item.mui-active  {ldelim} color: {$supplier_info.textcolor}; background-color: {$supplier_info.selectnavbarcolor}; {rdelim}
	.mui-bar-tab .mui-tab-item  {ldelim} color: {$supplier_info.navbarcolor}; {rdelim} 
	-->
</style> 
{else}
<style>
	<!-- 
	.mui-bar-tab .mui-tab-item.mui-active  {ldelim} color: {$supplier_info.selectnavbarcolor}; {rdelim}
	.mui-bar-tab .mui-tab-item  {ldelim} color: {$supplier_info.navbarcolor}; {rdelim} 
	-->
</style> 
{/if}
<nav class="mui-bar mui-bar-tab">
	<a id="defaultTab" class="mui-tab-item {if $sysinfo.action eq 'index'}mui-active{/if}" href="/index.php">
		<span class="mui-icon iconfont {if $supplier_info.footericonstyle eq '0'}icon-mainpage{else}icon-yuanxingshouye{/if}"></span>
		<span class="mui-tab-label">首页</span>
	</a>
	{if $supplier_info.showcategory eq '0'}
	<a class="mui-tab-item {if $sysinfo.action eq 'category'}mui-active{/if}" href="/category.php">
		<span class="mui-icon iconfont {if $supplier_info.footericonstyle eq '0'}icon-fenlei{else}icon-yuanxingfenlei{/if}"></span>
		<span class="mui-tab-label">{$businesse_info.productprefix}分类</span>
	</a> 
	{/if}
	<a class="mui-tab-item {if $sysinfo.action eq 'usercenter'}mui-active{/if}" href="/usercenter.php">
			<span class="mui-icon iconfont {if $supplier_info.footericonstyle eq '0'}icon-usercenter{else}icon-yuanxingyonghu{/if}"></span>
			<span class="mui-tab-label">会员中心</span>
	</a>
	{if $supplier_info.showpromotioncenter eq '0' && $profile_info.givenname neq ''}
	<a class="mui-tab-item {if $sysinfo.action eq 'promotioncenter'}mui-active{/if}" href="/promotioncenter.php">
			<span class="mui-icon iconfont {if $supplier_info.footericonstyle eq '0'}icon-tuiguangzhongxin{else}icon-yuanxingtuiguangzhongxin{/if}"></span>
			<span class="mui-tab-label">推广中心</span>
	</a>
	{/if}
	<a class="mui-tab-item {if $sysinfo.action eq 'shoppingcart'}mui-active{/if}" href="/shoppingcart.php">
		<span class="mui-icon iconfont {if $supplier_info.footericonstyle eq '0'}icon-shoppingcart{else}icon-yuanxinggouwuche{/if}" id="shoppingcart">
			<span id="shoppingcart_badge">{if $share_info.shoppingcart neq '0' && $share_info.shoppingcart neq '' }<span class="mui-badge">{$share_info.shoppingcart}</span>{/if}</span>
		</span> 
		<span class="mui-tab-label">购物车</span>
	</a>
</nav>


