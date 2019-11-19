<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-个人中心</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<link href="/public/pc/css/coupons.css" rel="stylesheet">
<script src="public/pc/js/common.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="public/js/jweixin.js"></script>
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="warp">
  {include file='header.tpl'}
   <div class="line3"></div>
  <!--head 头部-->
  <div class="cont" id="offCanvasWrapper">
    <div class="container">
      <div class="break-person clearfix">
        <p><a href="#">首页</a><em>&gt;</em><span>个人中心</span></p>
      </div>
      <!--break-person-->
      <div class="personbox" >
       {include file='usercenterL.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right">
          <div class="person-post border hui bn">
            <div class="person-post-tit person-linetit">
              <h3 class="f16 pull-left">我的卡券包</h3>
              <ul class="pull-left">
                <li><a href="coupons.php" class="active">卡券优惠</a>|</li>
                <li><a href="couponsofme.php">我的卡券使用记录</a></li>
              </ul>
            </div>
            <div class="complaintbox border-t1">
              <div class="complaintbox-t favorable">
                {foreach name="vipcards" key=vipcardid item=vipcard_info  from=$vipcardlist} 
		           {assign var="iteration" value=$smarty.foreach.vipcards.iteration} 
		            <a class="clearfix" href=" {if $vipcard_info.usagesid eq ''}coupons_fetch.php{else}coupons_view.php{/if}?record={$vipcardid}"><div class="stamp {if $iteration  is  odd }stamp02{else}stamp04{/if}">
					    <div class="par">
						    <p>{$vipcard_info.vipcardname} 【{if $vipcard_info.timelimit eq '0'}单次{else}不限次{/if}</p>
						     {if $vipcard_info.cardtype eq '0'}
								<sub class="sign">￥</sub>
							    <span>{$vipcard_info.amount}</span>
							    <p>订单满{$vipcard_info.orderamount}.00元</p>
							 {elseif $vipcard_info.cardtype eq '1'}
								<sub class="sign">￥</sub>
							    <span>{$vipcard_info.amount}</span>
							    <p>下单直接送</p>
							 {elseif $vipcard_info.cardtype eq '2'}
                                 <span>{$vipcard_info.discount}折</span>
								 {if $vipcard_info.orderamount eq '0'}
								 	<p>下单直接送</p>
								 {else}
								 	<p>订单满{$vipcard_info.orderamount}.00元</p>
								 {/if}
							 {/if} 
						    <sub>已领{$vipcard_info.remaincount}张，总计{$vipcard_info.count}张</sub>
					    </div>
					    <div class="copy">副券<p>{$vipcard_info.starttime}<br>{$vipcard_info.endtime}</p>
					    {if $vipcard_info.usagesid eq ''}
									     <p style="margin-top: 5px;">未领用</p>
									 {else}
									     <p style="margin-top: 5px;">{$vipcard_info.usagesstatus}</p>
									 {/if}
					    </div>
					    <i></i>
					</div></a>
		   		  {foreachelse}
					    <div>
					      <p class="msgbody" style="font-size: 30px;text-align: center;margin-top: 20px;"><span class="iconfont icon-tishi" style="color: #fe4401;font-size: 1em;"></span>本店铺暂时没有设置卡券!<br> 
						  </p>  
	   	        </div>
		   		  {/foreach}
		   		  <div style="border-top: 1px solid #e8e8e8;">
		            </div>
              </div>
            </div>
            <!--complaintbox-->
              <!-- <div class="blank20"></div>
              	          <div class="safe-tishi border hui">
              	            <p class="fw"> 特别提示：</p>
              	            <ul>
              	              <li>1.当您从购物车中去结算时，在订单确认页面可以选择（或输入）您的现金券券号，获得相应的优惠。</li>
              	              <li>2.每个订单限使用一张现金券，联营代发（品牌季）商品订单除外。</li>
              	              <li>3.现金券有不同的类型，如仅限某品牌、某品类使用的现金券等。</li>
              	              <li>4.请注意：现金券是有过期时间的哦！请在过期之前使用。</li>
              	              <li>5.现金券在激活状态下才能使用，已激活且未过期的现金券在您的现金券列表中状态显示为“未使用”。</li>
              	              <li>6.若您获得满额返券的订单发生退货，因该订单所返的所有现金券都将被取消。</li>
              	              <li>7.当您使用现金券购买的商品发生退货时，将不会退还该现金券分摊优惠至每个商品中的金额。</li>
              	              <li>8.独家品牌券在购买芙优润、梵柏莎、水果诱惑、悦己美、颜绯、凯莉丝汀、隐泉之语、肌御坊、优仪肽、河马家、花田色、
              	                
              	                净颜小筑这12个独家品牌商品时可使用。</li>
              	            </ul>
              	          </div> -->
          </div>
        </div>
        <!--person-right 个人中心右侧-->
      </div>
      <!---personbox 个人中心-->
    </div>
  </div>
  <!--cont 主体-->
  <div class="blank90"></div>
  <!--link 链接-->
  {include file='footbar.tpl'}
  {include file='footer.tpl'}
</div>
<!--warp 外层-->
</body>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
</html>
