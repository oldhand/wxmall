<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{if $orderinfo.appraisestatus eq 'yes'}查看评价{else}去评价{/if}</title>
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
          <h3 class="pull-left">{if $orderinfo.appraisestatus eq 'yes'}查看评价{else}去评价{/if}</h3>
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
            <h4 class="f16">订单状态：{$orderinfo.order_status}</h4>
            <h4 class="f16">订单总额：￥{$orderinfo.sumorderstotal}</h4>
          </div>
          <div class="fill-first-tit">
            <h4 class="f16">付款时间：{$orderinfo.paymenttime}</h4>
            <h4 class="f16">确认收货时间：{$orderinfo.confirmreceipt_time}</h4>
            <h4 class="f16" style="color: #c9302c;">*评价能提高您的积分，将获得平台更高的优惠政策！</h4>
          </div>
        </div>
        <!--fill-first 填写订单 第一次登录时-->
        <div class="blank20"></div>
        <div class="row-item-tit clearfix border">
          <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
              <td width="13%"></td>
              <td width="36%" align="left">商品信息</td>
              <td width="21%">数量</td>
              <td width="19%">订单金额</td>
              <td width="11%">操作</td>
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
                <td width="36%" align="left"><p><a href="javascript:;" target="_blank">{$orders_products_info.productname}</a></p>
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
                <td width="11%">
                  {if $orders_products_info.praiseid neq ''}
                      <p>已评价</p>
                  {else}
                    <a href="appraise_product_submit.php?record={$orders_products_info.id}" type="button" class="btn btn-danger btn-sm btn-p10">去评价</a>
                  {/if} 
                </td>
              </tr>
            </table>
            <div class="bot-line"></div>
          </div>
        </div>
        {/foreach}
      </div>
    </div>
    <!--order-index-->
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
