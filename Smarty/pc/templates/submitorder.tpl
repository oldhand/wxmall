<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-立即购买</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/order.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="warp">
  {include file='header.tpl'}
  <!--head 头部-->
  <div class="blank20"></div>
  <div class="cont">
    <div class="container">
      <div class="order-index">
        <div class="order-tit clearfix">
          <h3 class="pull-left">填写订单</h3>
          <ul class="pull-right order-tit-step">
            <li><a href="javascript:;">查看购物车<span></span></a></li>
            <li><a href="javascript:;" class="red w105">填写订单 <span></span></a></li>
            <li><a href="javascript:;">确认订单，去付款<span></span></a></li>
            <li><a href="javascript:;">完成购买</a></li>
          </ul>
        </div>
        <!--order-tit-->
        <div class="row-item-tit clearfix border fill-tit">
          <p>请填写并核对以下信息</p>
        </div>
        <!--row-item-tit-->
        <div class="blank20"></div>
        <form method="post" name="frm" action="/confirmpayment.php">
          <input name="token" value="{$token}" type="hidden">
          <input name="record" value="{$orderid}" type="hidden">
          <input id="sumorderstotal" value="{$total_money}" type="hidden">
          <input id="deliveraddress_count" value="{$deliveraddress|@count}" type="hidden">
        <div class="fill-first border">
        {if $deliveraddress|@count eq 0}
                <div class="fill-first-tit">
                  <h4 class="f16">您还没有收货地址，赶快去创建吧！</h4>
                  <a href="/deliveraddress.php"><input type="button" value="添加收货地址" class="btn btn160 btn-danger f12 fw contolr-sur"></a>
                </div>
                {else}
                <input name="deliveraddress" value="{$deliveraddress.recordid}" type="hidden"/>
          <div class="fill-first-tit">
            <h4 class="f16">收货人：{$deliveraddress.consignee}</h4>
            <p class="niceform chklist">
              联系电话：{$deliveraddress.mobile}</p>
          </div>
          {if $tradestatus eq 'pretrade'}
          <div class="fill-first-tit">
            <h4 class="f16">收货地址：{$deliveraddress.province}{$deliveraddress.city}{$deliveraddress.district}</h4>
            <h4>{$deliveraddress.shortaddress}</h4>
          </div>
          {else}
          <div class="fill-first-tit">
           
            <h4 class="f16">收货地址：{$deliveraddress.province}{$deliveraddress.city}{$deliveraddress.district}</h4>
            <h4>{$deliveraddress.shortaddress}</h4>
            <a href="deliveraddress.php?orderid={$orderid}" type="button" class="btn btn-danger btn120" style="margin-bottom: 20px;">更改收货地址</a>
          </div>
            {/if}
          {/if}
        </div>
        <!--fill-first 填写订单 第一次登录时-->
        <div class="blank20"></div>
        <div class="row-item-tit clearfix border">
          <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
              <td width="13%"></td>
              <td width="47%" align="left">商品信息</td>
              <td width="21%">数量</td>
              <td width="19%">订单金额</td>
            </tr>
          </table>
        </div>
        <!--row-item-tit-->
        <div class="blank20"></div>
        {foreach name="shoppingcarts" item=shoppingcart_info  from=$shoppingcarts}
        <div class="row-item row-item-shop">
          <div class="shop-body border">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td width="13%"><a href="javascript:;" target="_blank"><img src="{$shoppingcart_info.productthumbnail}" width="100" height="100"></a></td>
                <td width="47%" align="left"><p><a href="javascript:;" target="_blank">{$shoppingcart_info.productname}</a></p>
                {if $shoppingcart_info.propertydesc neq ""}
                <p class="greey">属性：{$shoppingcart_info.propertydesc}</p></td>
                    {/if}
                  
                <td width="21%"><div>
                    <p class="numcontrol arial"><em class="f12" style="background: #eee">{$shoppingcart_info.quantity}</em></p>
                  </div></td>
                <td width="19%"><p class=" arial">{if $shoppingcart_info.zhekou neq ''}{if $shoppingcart_info.activitymode eq '1'}底价{else}活动价{/if}：{$shoppingcart_info.shop_price}{else}单价： ¥{$shoppingcart_info.shop_price}{/if}</p></td>
              </tr>
            </table>
            <div class="bot-line"></div>
          </div>
        </div>
        {/foreach}
        </form>
      </div>
      <!--row-item-->
      <div class="blank20"></div>
      <div class="bz border">
        <div class="bz-r">
          <p class="pos-right">共计商品<span class="red p-lr5 arial">{$total_quantity}</span> 件<!-- <span class="m-l15">合计：<em class="arial">￥</em><em class="arial f24 pricenum">{$shoppingcart_info.total_price}</em></span> --></p>
        </div>
        <div class="bzlist">
          <h6>- 添加备注</h6>
          <div class="bz-box  border">
            <textarea style="color: #666;" {if $tradestatus eq 'pretrade'}disabled="disabled"{/if} class="textarea border text gray-c" id="buyermemo" name="buyermemo" placeholder="这里您可以留言">{$customersmsg}</textarea>
          </div>
        </div>
        <div class="bzlist">
          <h6>- 索要发票</h6>
          {if $tradestatus eq 'pretrade'}
                <div class="bz-box border mt-5 bz-form">
                  <p><span>发票抬头：</span>
                    <input type="text" class="text w300" value="{if $fapiao eq ''}无需发票{else}{$fapiao}{/if}" disabled>
                    <input name="fapiao" value="{$fapiao}" type="hidden"/>
                  </p>
                </div>
              {else}
                {if $total_money gt 99}
                <div class="bz-box border mt-5 bz-form">
                  <p><span>发票抬头：</span>
                  <a href="fapiao.php" class="mui-navigate-right fapiao">
                    <input type="text" class="text w300" value="{if $fapiao eq ''}无需发票{else}{$fapiao}{/if}" disabled></a>
                    <input name="fapiao" value="{$fapiao}" type="hidden"/>
                  </p>
                </div>
                {else}
                  <div class="bz-box border mt-5 bz-form">
                  <p><span>发票抬头：</span>
                    <span>*发票：</span>订单总额&nbsp;<span class="price">¥100</span>&nbsp;以上,可以开发票*</span>
                    <input name="fapiao" value="" type="hidden"/>
                  </p>
                </div>
                {/if}
              {/if}
        </div>
        <div class="blank20"></div>
        <p class="text-right ">您需支付商品总金额：<span class="red arial">￥<em class="f24 arial">{$total_money}</em></span></p>
        <p class="text-right mt-10">
        {if $allowpayment eq 'true'}
          <input type="button" value="去付款" class="f14 btn btn-danger btn-lg btn160 confirmpayment">
          {/if}
          
        </p>
      </div>
      <!--bz等信息-->
    </div>
    <!--order-index 购物车主页-->
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
<script type="text/javascript">
{literal}
$(function(){
  $('.confirmpayment').click(function ()
          {
            var deliveraddress_count = $("#deliveraddress_count").val();
            if (deliveraddress_count != "0")
            {
              document.frm.submit();
            }
            else
            {
              alert("警告", "请先创建收货地址，谢谢！", "error");
            }
  });
});
{/literal}
</script>
</html>
