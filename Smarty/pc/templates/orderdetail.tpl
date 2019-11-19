<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-订单详情</title>
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
          <h3 class="pull-left">订单详情</h3>
          <input id="orderid" name="orderid" value="{$orderinfo.orderid}" type="hidden" > 
          <input id="tradestatus" name="tradestatus" value="{$orderinfo.tradestatus}" type="hidden" > 
          <input id="notify" value="0" type="hidden" >
        </div>
        <div class="row-item-tit clearfix border fill-tit">
          <p>订单信息</p>
        </div>
        <!--row-item-tit-->
        <div class="fill-first border">
          <div class="fill-first-tit">
            <h4 class="f16">订单号：{$orderinfo.order_no}</h4>
            <h4 class="f16">成交状态：{if $orderinfo.tradestatus eq 'trade'}成交{else}未成交{/if}</h4>
            <h4 class="f16">订单状态：{$orderinfo.order_status}</h4>
            <h4 class="f16">支付方式：{if $orderinfo.paymentmode eq '1'}立即支付{/if}{if $orderinfo.paymentmode eq '2'}货到付款{/if}{if $orderinfo.paymentmode eq '3'}到店付款{/if}</h4>
          </div>
          {if $orderinfo.tradestatus eq 'trade'}
            <div class="fill-first-tit">
              <h4 class="f16">付款时间：{$orderinfo.paymenttime}</h4>
              <h4 class="f16">支付通道：{$orderinfo.payment}</h4>
              <h4 class="f16">余额支付：{$orderinfo.usemoney}</h4>
              <h4 class="f16">卡券优惠：{$orderinfo.discount}</h4>
              <h4 class="f16">微信支付：{$orderinfo.paymentamount}</h4>
            </div>
          {/if}
          <div class="fill-first-tit">
            <h4 class="f16">收货人：{$orderinfo.consignee}</h4>
            <h4 class="f16">收货手机：{$orderinfo.mobile}</h4>
            <h4 class="f16">收货地址：{$orderinfo.address}</h4>
          </div>
        </div>
        {if $orderinfo.delivery neq ''}
        <div class="blank20"></div>
        <div class="row-item-tit clearfix border fill-tit">
          <p>物流信息</p>
        </div>
        <div class="fill-first border">
          <div class="fill-first-tit">
            <h4 class="f16">发货时间：{$orderinfo.deliverytime}</h4>
            <h4 class="f16">物流公司：{$orderinfo.deliveryname}</h4>
            <h4 class="f16">发货单号：{$orderinfo.invoicenumber}</h4>
            <!-- <a href="wuliu.php?type={$orderinfo.deliveryname}&invoicenumber={$orderinfo.invoicenumber}" type="button" class="btn btn-danger btn120">点击查看物流信息</a> -->
          </div>
        </div>
        {/if}
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
        {foreach name="orders_products" item=orders_products_info  from=$orderinfo.orders_products}
        <div class="row-item row-item-shop">
          <div class="shop-body border">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td width="13%"><a href="javascript:;" target="_blank"><img src="{$orders_products_info.productthumbnail}" width="100" height="100"></a></td>
                <td width="47%" align="left"><p><a href="javascript:;" target="_blank">{$orders_products_info.productname}</a></p>
                {if $orders_products_info.propertydesc neq ""}
                <p class="greey">属性：{$orders_products_info.propertydesc}</p></td>
                {/if}
                <td width="21%"><div>
                    <p class="numcontrol arial"><em class="f12" style="background: #eee">{$orders_products_info.quantity}</em></p>
                  </div></td>
                <td width="19%"><p class=" arial">
                {if $orders_products_info.zhekou neq '' && $orders_products_info.zhekou|floatval gt 0 }
                {if $orders_products_info.activitymode eq '1'}底价{else}活动价{/if}：¥{$orders_products_info.shop_price}{else}单价： ¥{$orders_products_info.shop_price}{/if}</p>

                {if $orders_products_info.postage|@floatval gt 0 && ($orders_products_info.includepost|@intval eq 0 || $orders_products_info.includepost|@intval gt $orders_products_info.productallcount|@intval)}
                  <p class='arial' style="color: #c9302c">
                    邮费：
                    <span class="price">
                      ¥{if $orders_products_info.mergepostage|@intval eq 1}{$orders_products_info.postage}{else}{$orders_products_info.postage*$orders_products_info.quantity}{/if}
                    </span>
                    {if $orders_products_info.includepost|@intval gt 0}
                      <span style="color:#878787;margin-left:10px;">({$orders_products_info.includepost}件包邮)</span>
                    {/if}
                  </p>
                {/if}
                </td>
              </tr>
            </table>
            <div class="bot-line"></div>
          </div>
        </div>
        {/foreach}
      </div>
      <!--row-item-->
      <div class="blank20"></div>
      <div class="bz border">
        <!-- <div class="bz-r">
          <p class="pos-right">共计商品<span class="red p-lr5 arial">{$orderinfo.total_quantity}</span> 件</p>
        </div> -->
        <p class="text-right ">订单总额：<span class="red arial">￥<em class="f24 arial">{$orderinfo.sumorderstotal}</em></span></p>
        {if $orderinfo.addpostage|floatval gt 0 }
           <p class="text-right">
               偏远地区附加邮费：<span class="price">¥{$orderinfo.addpostage}</span>
            </p>
         {/if}
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
              sweetAlert("警告", "请先创建收货地址，谢谢！", "error");
            }
  });
});
{/literal}
</script>
</html>
