<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$supplier_info.suppliername}-首页</title>
  <link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/pc/css/public.css" rel="stylesheet">
  <link href="/public/pc/css/index.css" rel="stylesheet"> 
</head>
<body> 
  {include file='rightbar.tpl'}  
  {include file='leftbar.tpl'}   
  <div id="warp">
     {include file='header.tpl'} 
    <div class="bannerbox">
      <div class="banner">
        <div class="bannerinner">
          <ul class="51buypic">
              {foreach name="ads" item=ad_info from=$ads} 
	              <li>
	                <a href="{$ad_info.link}" target="_blank">
	                  <img src="{$ad_info.webimage}" width="1112" height="228"/>
	                </a>
	              </li> 
	           {/foreach} 
          </ul>
          <div class="num">
            <ul></ul>
          </div>
        </div>
      </div>
    </div>
    <!--jumbtron 导航部分-->
    <div class="cont" id="left-bar-cont1">
      <div class="container">
        <div class="daydaylist" id="Day">
          <div class="daydaylist-tit tit mb-10 clearfix">
            <h3>推荐商品</h3>
          </div>
          <div class="daydaylist-body clearfix" id="dayday">
            <ul>
				      {foreach name="products"  item=product_info from=$products} 
                <li>
	                  <div class="col">
	                    <a href="detail.php?from=index&productid={$product_info.productid}">
	                      <img class="lazy" src="/public/pc/images/load.gif" data-original="{$product_info.productlogo}" width="350" height="250"></a>
	                  </div>
	                  <div class="pro-name">
	                    <p>
	                      <a href="detail.php?from=index&productid={$product_info.productid}" class="white f16" style="display:inline-block;overflow:hidden;white-space: nowrap;text-overflow:ellipsis;width: 20em;">{$product_info.productname}</a>
	                    </p>
	                  </div>
	                  <div class="pro-js">
	                    <div class="pro-js-left hui"> <del>￥{$product_info.market_price}</del>
	                      <span>微逛价：</span>
	                    </div>
	                    <div class="pro-js-right">
	                      <div class="zanbox">
	                        <h2 class="pull-left">￥{$product_info.shop_price}</h2>
	                        <div class="zan pull-right">
		                    	<span class="iconfont icon-tezan" style="display:inline-block;margin-top:-3px;font-size: 1.5em;"></span>
							            </div>
	                        <span class="pull-right mr-10 mt-10">{$product_info.praise}</span>
	                      </div>
	                    </div>
	                  </div>
                </li> 
				      {/foreach} 
            </ul>
          </div>
        </div> 
        <!--猜你喜欢-->
        <div class="lifebox" id="life">
          <div class="lifebox-tit tit clearfix">
            <!-- <a href="life-favorable.html" class="pull-right" name="003" id="003">>> 更多</a> -->
            <h3 class="pull-left">猜你喜欢</h3>
            <span class='life_tag'>LOVE</span>
          </div>
          <div id="tabbox">
            <div class="lifebox-body clearfix">
              <a href="javascript:;" class="prev"></a>
              <a href="javascript:;" class="next"></a>
              <div class="lifebox-list">
                <ul>
					{foreach name="products"  item=product_info from=$salevolume_products} 
		                    <li class="carbox">
		                      <a href="detail.php?from=index&productid={$product_info.productid}">
		                        <img class="lazy" src="/public/pc/images/load.gif" data-original="{$product_info.productlogo}" width="379" height="297">
		                      </a>
		                      <div class="life-js-txt">
		                        <a href="detail.php?from=index&productid={$product_info.productid}">{$product_info.productname}</a>
		                        <p><span>&yen;{$product_info.shop_price}</span>&nbsp;&nbsp;<del>&yen;{$product_info.market_price}</del></p>                      
		                      </div>
		                    </li>  
					{/foreach} 
                </ul>
              </div>
            </div>
          </div>
        </div> 
        <!-- 新品上架/厂家特惠 -->
        <div class='clearfix tab_goods' id='tab_goods'>
          <div class='goods_tit'>
            <h5 class='cur'>新品上架</h5>
          </div>
          <div class='goods_content'>
            <ul class='cur' id='new_arrivals'>
				        {foreach name="products"  item=product_info from=$newproducts}  
		                <li>
		                  <a href="detail.php?from=index&productid={$product_info.productid}"><img class="lazy" src="/public/pc/images/load.gif" data-original="{$product_info.productlogo}" width="379" height="297"></a>
		                  <div class='goodsitems'>
		                    <a href="detail.php?from=index&productid={$product_info.productid}">{$product_info.productname}</a>
		                    <p><span>&yen;{$product_info.shop_price}</span>&nbsp;&nbsp;<del>&yen;{$product_info.market_price}</del></p>                      
		                  </div>
		                </li>
				        {/foreach} 
            </ul> 
          </div>
        </div>
        <div class="blank60"></div> 
    </div> 
    <!--link 链接-->
	{include file='footbar.tpl'}
    {include file='footer.tpl'}
  </div>
  <!--warp 外层--> 
</body>
<script src="/public/pc/js/jquery-1.11.3.min.js"></script>
<script src="/public/pc/js/jquery.lazyload.min.js"></script> 
<script src="/public/pc/js/jquery.superslide.2.1.1.js" type="text/javascript"></script>
<script src="/public/pc/js/index.js"></script>
<script>
{literal}
 
$('.user-shopcat').hover(function(){
  $('.btn-group-right>ul>li>hr').css({'border-top':'1px solid #cc3300'});
},function(){
  $('.btn-group-right>ul>li>hr').css({'border-top':'1px solid #444'});
})
//banner图片调用
$(".bannerinner").slide({
  titCell: ".num ul",
  mainCell: ".51buypic",
  effect: "fold",
  autoPlay: true,
  delayTime: 1000,
  autoPage: true
});
 
//滚动
var oBox = $('.lifebox-body');
gd(oBox.eq(0));
var OLi = $('#tabtit').find('li');

OLi.click(function() {
  var oIndex = OLi.index($(this));
  gd(oBox.eq(oIndex));
});
//右侧
oRight(60); //如果需要调用左侧辅助导航需要两个函数oRight()与oLeftbur()两个函数，oRight()需要传一个内容到头部的差值
oLeftbur();
//本地生活选项卡
oTab($("#tabtit li"), $("div#tabbox > div"));
//弹出层产品详情
oJx();
//点赞
oZan();
//右侧导航
rightBar('.btn-group-right ul', '.group-open');
 
$('#tabbox').hover(function(){
  $('.prev,.next').fadeIn();
},function(){
  $('.prev,.next').fadeOut();
});
{/literal}
</script>
</html>