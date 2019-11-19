    <link href="public/css/iconfont.css" rel="stylesheet" />
    <div class="head clearfix" style="position:relative">
      <div class="menu-bg-img"></div> 
      <div class="head-top">
        <div class="container">
          <div class="logo pull-left">
            <div class="logo_l pull-left">
              <a href="index.php">
                <img src="/public/pc/images/logo.png" title="{$supplier_info.suppliername}" width="200" height="100"></a>
            </div>
			        {if $profile_info.profileid neq '' && $profile_info.profileid neq 'anonymous'}
	            <div class="logo-m pull-left line-l2">
	              <img src="{$profile_info.headimgurl}" class="user-head">
	            </div>
	            <div class="logo-r pull-left">
	              <h5 class="red">欢迎光临</h5>
	              <h5><strong>{$profile_info.givenname}</strong>
	              </h5>
	            </div>
			        {else}
	            <div class="logo-m pull-left line-l2">
	              <img src="/public/pc/images/profile.jpg" class="user-head">
	            </div>
	            <div class="logo-r pull-left">
	              <h5 class="red">欢迎光临</h5>
	              <h5>
                  <strong>{$supplier_info.suppliername}</strong>
	              </h5>
	            </div>
			        {/if}
           
          </div>
          <!--logo-->
          <div class="head-top-r pull-right">
            <div class="head-top-tool clearfix">
              <ul class="list-unstyled list-inline pull-right">
                <li class="per-login menu-li">
          					{if $profile_info.profileid neq '' && $profile_info.profileid neq 'anonymous'}
          					<span class="red">【</span><a href="pc_logout.php" class="red">退出</a><span class="red">】</span>
          					{else}
	                  请
                    <span class="red">【</span>
					          <a href="pc_login.php" class="red">登陆</a> 
	                  <span class="red">】</span>
	                  <span class="red">【</span>
	                  <a href="pc_register.php" class="red">注册</a>
	                  <span class="red">】</span>
					          {/if}
                </li>
                <li>
                  <ul class="list-unstyled list-inline" id="Top-menu">
                    <li class="menu-li">
                      <a href="javascript:;" class="m1">
                        <i class="icon-updown"></i>
                        怎么赚
                        <img src="/public/pc/images/TISHI.gif" width="6" height="6" class="ts"></a>
                      <div class="submenu">
                        <i class="icon-sj"></i>
                        <ul>
                          <li>
                            <a href="#" class="unread">
                              如何去赚钱
                              <img src="/public/pc/images/TISHI.gif" width="6" height="6" class="ts"></a>
                          </li>
                          <li>
                            <a href="#">怎么拿到钱</a>
                          </li>
                          <li>
                            <a href="#">能赚那些钱</a>
                          </li>
                          <li>
                            <a href="#">赚钱有新招</a>
                          </li>
                          <li>
                            <a href="#">有钱一起赚</a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li class="menu-li">
                      <a href="#" class="m1">
                        <i class="icon-updown"></i>
                        服务中心
                      </a>
                      <div class="submenu">
                        <i class="icon-sj"></i>
                        <ul>
                          <li>
                            <a href="#">如何去赚钱</a>
                          </li>
                          <li>
                            <a href="#">怎么拿到钱</a>
                          </li>
                          <li>
                            <a href="#">能赚那些钱</a>
                          </li>
                          <li>
                            <a href="#">赚钱有新招</a>
                          </li>
                          <li>
                            <a href="#">有钱一起赚</a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li class="menu-li">
                      <a href="http://www.ttzan.com/" class="m1"> 特赞官网
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
            <!--head-top-tool-->
            <div class="head-top-bot">
              <ul class="btn-toolbar list-unstyled list-inline pull-right">
                <li>
                  <a href="shoppingcart.php" class="m2"> 
                    <span>
						        <span class="iconfont icon-shoppingcart" style="display:inline-block;margin-top:-3px;font-size: 2.1em;"></span>
                      <em class="circle" style="border-radius: 100px;background-color: #dd524d;" id="shoppingcart_header">{$share_info.shoppingcart}</em>
                    </span>
                    购物车
                  </a>
				          {if $share_info.shoppingcarts|@count gt 0}
                  <div class="toolbar-submenu carlist">
                    <i class="icon-sj"></i>
                    <ul>
						        {foreach name="shoppingcarts" item=shoppingcart_info  from=$share_info.shoppingcarts} 
	                        <li id="shoppingcart_{$shoppingcart_info.shoppingcartid}">
	                          <a href="/detail_{$shoppingcart_info.productid}.html?from=index" class="pull-left mr-10">
	                            <img  src="{$shoppingcart_info.productthumbnail}"  width="50" height="50"></a>
	                          <p>
	                            <em class="red pull-right"><strong class="carliststrong">￥</strong>
	                              {$shoppingcart_info.shop_price}&nbsp;<i class="glyphicon glyphicon-remove" style="display:inline-block;margin-top:-3px;font-size: 8px;"></i>&nbsp;{$shoppingcart_info.quantity}件</em> 
	                            <a href="/detail_{$shoppingcart_info.productid}.html?from=index"> {$shoppingcart_info.productname}</a>
	                          </p>
	                          <p class="greey">
	                            <a class="black pull-right" style="margin-bottom: 8px;" onclick="delete_shoppingcart({$shoppingcart_info.shoppingcartid});" href="javascript:;"><i class="glyphicon glyphicon-trash" style="display:inline-block;margin-top:-3px;font-size: 10px;"></i></span> 删除</a>
	                             {$shoppingcart_info.propertydesc}
	                          </p>
	                        </li>
						        {/foreach} 
                    </ul>
                    <!--ul 列表清单-->
	                    <div class="carlist-Rache">
                      
	                    </div>
                    <!--carlist-Rache 结算-->
				     </div>
				   {/if}
                </li>
                <li>
                  <a href="orders_payment.php">
                    <span>
                    	<span class="iconfont icon-myorders" style="display:inline-block;margin-top:-3px;font-size: 2.1em;"></span>
                    </span>
                    我的订单
                  </a>
                </li>
                <li>
                  <a href="usercenter.php"  class="m2">
                    <span>
                    	<span class="iconfont icon-usercenter" style="display:inline-block;margin-top:-3px;font-size: 2.1em;"></span>
                    </span>
                    个人中心
                  </a> 
                </li>
                <li>
                  <a href="mycollections.php">
                    <span>
                    	<span class="iconfont icon-collection" style="display:inline-block;margin-top:-3px;font-size: 2.1em;"></span>
                    </span>
                    收藏
                  </a>
                </li>
              </ul>
              <!--btn-toolbar 按钮-->
              <div class="input-group pull-right">
              <form action="search.php" onSubmit="return searchgo()"> 
                <input type="text" name="keywords" class="form-control text gray-c" id="text" value="搜索你喜欢的商品">
                <input type="submit" class="headbg search-btn" value=""></form>
              </div>
              <!-- /input-group 搜索框--> </div>
            <!--head-top-bot--> </div>
          <!--head_top_r--> </div>
      </div>
      <!--head-top 头部上部分-->
      <div class="head-nav " id="left-bar-cont">
        <div class="container clearfix">
          <div class="category-menu pull-left">
            <h3>商品分类</h3>
            <div class="category-menu-nav border">
              <ul class="category-menucategory-menu">
				  {foreach name="categorys" item=category_info from=$supplier_info.categorys} 
	                  <li class="open">
	                    <a href="search.php?categoryid={$category_info.id}" class="m3">
		                    <span class="iconfont icon-chanpinfenlei" style="display:inline-block;margin-top:0px;font-size: 1.1em;"></span>
		                    {$category_info.name}</a>
						{assign var="childcategorys" value=$category_info.childs}
						{if $childcategorys|@count gt 0}
		                    <div class="leftsubmenu leftnavmenu">
		                      <h4>
		                        <span class="m-r5">
		                          <span class="iconfont icon-chanpinfenlei" style="display:inline-block;margin-top:0px;font-size: 2.0em;"></span>
		                          {$category_info.name}
		                      </h4> 
		                      <div class="menu-list">
		                        <ul>
		                          <li>
		                            |
		                            <a href="search.php?categoryid={$category_info.id}" class="fw">全部</a>
		                          </li>
								    {foreach name="childcategorys" item=childcategory_info from=$childcategorys} 
			                        <li>
			                          |
			                          <a href="search.php?categoryid={$childcategory_info.id}">{$childcategory_info.name}</a>
			                        </li>
									{/foreach}
		                        </ul>
		                      </div>
		                    </div>
						{/if} 
	                  </li>
				  {/foreach}  
              </ul>
            </div>
          </div>
          <!--category-menu 商品分类-->
          <ul class="menu list-unstyled pull-left">
            <li class="active">
              <a href="index.php" >
				<span class="iconfont icon-mainpage" style="display:inline-block;margin-top:0px;font-size: 1.2em;"></span>
                店铺首页
              </a>
            </li>
            <li class="nopm">|</li>
            <li>
              <a href="coupons.php">
				<span class="iconfont icon-kaquanqianbao" style="display:inline-block;margin-top:0px;font-size: 1.2em;"></span>
                卡券优惠
              </a>
            </li>
            <li class="nopm">|</li>
            <li>
              <a href="salespromotion.php">
				<span class="iconfont icon-cuxiaohuodong" style="display:inline-block;margin-top:0px;font-size: 1.2em;"></span>
                促销活动
              </a>
            </li>
            <li class="nopm">|</li>
            <li>
              <a href="news.php">
				<span class="iconfont icon-mynews" style="display:inline-block;margin-top:0px;font-size: 1.2em;"></span>
                商城资讯
              </a>
            </li>
            <li class="nopm">|</li>
            <li>
              <a href="contactus.php">
				<span class="iconfont icon-introduce" style="display:inline-block;margin-top:0px;font-size: 1.2em;"></span>
                联系我们
              </a>
            </li>
          </ul>
        </div>
      </div>
      <!--head-nav 导航部分--> </div>
    <!--head 头部-->