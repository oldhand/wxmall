<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>售后服务</title>
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
          <h3 class="pull-left">售后服务</h3>
        </div>
        <div class="row-item-tit clearfix border fill-tit">
          <p>订单信息</p>
        </div>
        <form  method="post" name="frm" action="aftersaleservice.php" >
		 <input name="type"  value="submit" type="hidden" > 
		 <input id="orderid" name="record"  value="{$orderinfo.orderid}" type="hidden" >  
		 <input id="localids" name="localids"  value="" type="hidden" > 
		 <input id="imagesserverids" name="imagesserverids"  value="" type="hidden" >
        <!--row-item-tit-->
        <div class="fill-first border">
          <div class="fill-first-tit">
            <h4 class="f16">订单号：{$orderinfo.order_no}</h4>
            <h4 class="f16">订单状态：{$orderinfo.order_status}</h4>
            {if $orderinfo.returnedgoodsapplyid neq ''}
            <h4 class="f16">退货状态：{$orderinfo.returnedgoodsapplysstatus}</h4>
            <h4 class="f16">退货提交时间：{$orderinfo.aftersaleservices_time}</h4>
	            {if $orderinfo.returnedgoodsapplysstatus eq '已退货' || $orderinfo.returnedgoodsapplysstatus eq '已退款' || $orderinfo.returnedgoodsapplysstatus eq '退货中'}
	            <h4 class="f16">退货金额：{$orderinfo.returnedgoodsamount}</h4>
	            <h4 class="f16">退货数量：{$orderinfo.returnedgoodsquantity}</h4>
	            {/if}
	        <h4 class="f16">退货操作人：{$orderinfo.operator}</h4>
            {/if}
          </div>
          {if $orderinfo.delivery neq ''}
          <div class="fill-first-tit">
            <h4 class="f16">发货时间：{$orderinfo.deliverytime}</h4>
            <h4 class="f16">物流公司：{$orderinfo.deliveryname}</h4>
            <h4 class="f16">发货单号：{$orderinfo.invoicenumber}</h4>
          </div>
          {/if}
          {if $orderinfo.confirmreceipt eq 'receipt'}
          <div class="fill-first-tit">
          	{if $orderinfo.autosettlement eq 'timeout'}
          		<h4 class="f16" style="color: #c9302c;">订单已经超过允许的退货期限，您已经不能退货了。</h4>
          	{else}
          		<h4 class="f16" style="color: #c9302c;">{$orderinfo.autosettlement}</h4>
          	{/if}
          </div>
          {/if}
          {if $orderinfo.tipmsg neq ''}
			<div class="fill-first-tit">
				<h4 class="f16" style="color: #c9302c;">
					{$orderinfo.tipmsg}
				</h4> 
            </div>
		  {/if}
        </div>
        <!--fill-first 填写订单 第一次登录时-->
        <div class="blank20"></div>
        <div class="row-item-tit clearfix border">
          <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
              <td width="13%"></td>
              <td width="36%" align="left">商品信息</td>
              <td width="21%">退货数量</td>
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
                {if $orderinfo.returnedgoodsapplyid eq ''}
                	<ul class="numcontrl clearfix">
	                        <a href="javascript:;" class="sub-num" ><li>-</li></a>
	                        <li class="arial f12">
	                          <input class="get-num" readonly  value="0" id="qty_item_{$orders_products_info.id}" name="qty_item_{$orders_products_info.id}" type="text" />
	                        </li>
	                        <a href="javascript:;" class="add-num" data-min='0' data-max='{$orders_products_info.quantity}'><li>+</li></a>
	                </ul>
	              {else}
	              	<p class='mui-ellipsis'>单价：<span class="price">¥{$orders_products_info.shop_price}</span></p> 
					<p class='mui-ellipsis'>退货数量：{$orders_products_info.returnedgoodsquantity}件</p> 
	              {/if}
                  </div>
                 </td>
              </tr>
            </table>
            <div class="bot-line"></div>
          </div>
        </div>
        {/foreach}
        <div class="blank20"></div>
      <div class="bz border">
        <div class="bzlist">
          <h6>- 填写理由</h6>
          <div class="bz-box  border">
          {if $orderinfo.returnedgoodsapplyid eq ''}
            <textarea style="color: #666;" placeholder="亲，您一定要详细说明退货理由！" id="reason" name="reason" class="textarea border text gray-c"></textarea>
          {else}
          	<textarea style="color: #666;" id="reason" name="reason" class="textarea border text gray-c" disabled>{$orderinfo.reason}</textarea>
          {/if}
          </div>
        </div>
        {if $orderinfo.returnedgoodsapplyid eq '' && $orderinfo.autosettlement neq 'timeout'}
        <input type="button" style="margin-left: 920px;" value="保存" class="f14 btn btn-danger btn-lg btn160 confirmpayment savethis">
        {else}
        <a href="orders_aftersaleservice.php" > 
	 	<input type="button" style="margin-left: 920px;" value="确认" class="f14 btn btn-danger btn-lg btn160 confirmpayment"></a>
		{/if}
      </div>
      </form>
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
<script type="text/javascript">
var ordersproductids = {$ordersproductids};
{literal}
$(function() {
	$('.add-num').each(function(){
		var max = $(this).attr('data-max');
		$(this).click(function(){
			var input = $(this).parent().find('li input.get-num');
			console.log(input);
			if(input.val() < max){
				var tmp = parseInt(input.val()) +1;
				input.val(tmp);
			}
		});
	})
	$('.sub-num').each(function(){
		$(this).click(function(){
			var input = $(this).parent().find('li input.get-num');
			if(input.val() > 0){
				var tmp = parseInt(input.val()) -1;
				input.val(tmp);
			}
		})
	})
	$('.savethis').click(function() {
		var reason = $("#reason").val();
		var product_qty = false;
		for(var i=0;i<ordersproductids.length;i++)
         { 
			 var qty_item = $('#qty_item_'+ordersproductids[i]).val(); 
             if( parseInt(qty_item,10) > 0 ) 
             { 
                product_qty = true;
             }
         }
		if (reason == "") {
			alert("请填写退货理由！");
		} else if(!product_qty){
			alert("您至少需要退一件商品！");
		}
		else{
			document.frm.submit();
		}
	});
});
{/literal}
</script>
</html>
