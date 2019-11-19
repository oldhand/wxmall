<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-购物车</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet"> 
<link href="/public/pc/css/order.css" rel="stylesheet"> 
<link href="public/css/iconfont.css" rel="stylesheet" />
<link href="public/css/sweetalert.css" rel="stylesheet"/>
</head>
<body>
<div id="zhezhao"></div>
<!--弹出遮罩层-->
<div class="order-alert">
  <div class="order-alert-tit" id="close"> <a href="#" class="alertclose pull-right"></a>
    <h4>温馨提示</h4>
  </div>
  <div class="order-alert-lr">
    <h4><strong>收藏成功！</strong></h4>
    <p class="text-center"><a href="#" class="btn btn-lg btn-default btn-alert">继续购物</a><a href="person-post-collection.html" class="btn btn-lg btn-default btn-alert">去我的收藏</a></p>
  </div>
</div>
{include file='rightbar.tpl'}  
<div id="warp">
  {include file='header.tpl'} 
  <!--head 头部-->
  <div class="blank20"></div>
  <div class="cont">
    <div class="container">
      <div class="order-index">
        <div class="order-tit clearfix">
          <h3 class="pull-left">我的购物车</h3>
          <ul class="pull-right order-tit-step">
            <li><a href="shoppingcart.html" class="red">查看购物车<span></span></a></li>
            <li><a href="javascript:;" class="w105">填写订单 <span></span></a></li>
            <li><a href="javascript:;">确认订单，去付款<span></span></a></li>
            <li><a href="javascript:;">完成购买</a></li>
          </ul>
        </div>
        <!--order-tit-->
        <div class="row-item-tit clearfix border">
          <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
              <td width="4%"></td>
              <td width="12%"></td>
              <td width="42%" align="left">商品信息</td>
              <td width="14%">数量</td>
              <td width="13%">订单金额</td>
              <td width="15%">操作</td>
            </tr>
          </table>
        </div>
        <!--row-item-tit-->
        <div class="blank20"></div>
        <div class="row-item row-item-shop">
          <div class="shoplist">
            <div class="shop-name mb-10">
              <div class="item-1">
                <div class="niceform chklist">
                  <!-- <input type="checkbox" value='1' id="chk_all1" />
                  <label></label> -->
                </div>
              </div>
              <p><span class="greey">店铺名：</span><a href="#" class="black" target="_blank">{$supplier_info.suppliername}</a></p>
            </div>
            <div class="shop-body border">
            <form class="mui-input-group" method="post" name="frm" action="submitorder.php">
            <input name="token" value="{$token}" type="hidden">
              <table cellpadding="0" cellspacing="0" border="0" width="100%">
				  {foreach name="shoppingcarts" item=shoppingcart_info  from=$shoppingcarts} 
	                  <tr id="shoppingcart_wrap_{$shoppingcart_info.id}">
	                    <td width="4%"><div class="niceform chklist">
	                        <input name="shoppingcart[]" value="{$shoppingcart_info.id}" id="shoppingcart_{$shoppingcart_info.id}"
                       checked type="checkbox"/>
	                      </div></td>
	                    <td width="12%" align="left"><a href="detail.php?from=shoppingcart&productid={$shoppingcart_info.productid}" target="_blank"><img src="{$shoppingcart_info.productthumbnail}" width="100" height="100"></a></td>
	                    <td width="42%" align="left"><p><a href="detail.php?from=shoppingcart&productid={$shoppingcart_info.productid}" target="_blank">{$shoppingcart_info.productname}</a></p>
						{if $shoppingcart_info.vendorname neq '' && $supplier_info.showvendor eq '1'} 
							<p class='greey'>供应商：{$shoppingcart_info.vendorname}</p>
						{/if}
						{if $shoppingcart_info.propertydesc neq ''}
							<p class='greey'>属性：{$shoppingcart_info.propertydesc}</p>
						{/if}
						<p class='greey'>{if $shoppingcart_info.zhekou neq ''}{if $shoppingcart_info.activitymode eq '1'}底价{else}活动价{/if}：
								<span class="price">¥{$shoppingcart_info.shop_price}</span>
								<span style="color:#878787;margin-left:5px;text-decoration:line-through;">
								¥{$shoppingcart_info.old_shop_price}</span>{else}单价：<span class="price">
								¥{$shoppingcart_info.shop_price}</span>{/if}
						</p>
						{if $shoppingcart_info.activitymode eq '1'}
							<p class='greey'>
								砍价：{if $shoppingcart_info.bargains_count eq 0}还没有好友帮忙砍价{else}已有 {$shoppingcart_info.bargains_count} 位好友帮忙砍价{/if}
							</p>
						{/if}
						<p id="postage_panel_{$shoppingcart_info.id}" class='greey' style="display: {if $shoppingcart_info.postage|@floatval gt 0 && ($shoppingcart_info.includepost|@intval eq 0 || $shoppingcart_info.includepost|@intval gt $shoppingcart_info.productallcount|@intval) && ($supplier_info.totalpricefreeshipping|@floatval eq 0 || $supplier_info.totalpricefreeshipping|@floatval gt $total_money) && ($supplier_info.totalquantityfreeshipping|@intval eq 0 || $supplier_info.totalquantityfreeshipping|@intval gt $total_quantity)}block{else}none{/if};">
							邮费：
							<span id="postage_span_{$shoppingcart_info.id}" class="price">
								¥{if $shoppingcart_info.mergepostage|@intval eq 1}{$shoppingcart_info.postage}{else}{$shoppingcart_info.postage*$shoppingcart_info.quantity|string_format:"%0.2f"}{/if}
							</span>
							{if $shoppingcart_info.includepost|@intval gt 0}
								<span style="color:#878787;margin-left:10px;">({$shoppingcart_info.includepost}件包邮)</span>
							{/if}
						</p>
            <p class='greey'>小计：<span id="total_price_{$shoppingcart_info.id}"
                                       class="price"> ¥{$shoppingcart_info.total_price}</span></p>
						<input value="{$shoppingcart_info.productid}" id="productid_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.shop_price}" id="shop_price_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.old_shop_price}" id="old_shop_price_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.postage}" id="postage_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.includepost}" id="includepost_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.mergepostage}" id="mergepostage_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.zhekou}" id="zhekou_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.bargains_count}" id="bargains_count_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.activitymode}" id="activitymode_{$shoppingcart_info.id}" type="hidden"/>
						<input value="{$shoppingcart_info.bargainrequirednumber}" id="bargainrequirednumber_{$shoppingcart_info.id}" type="hidden"/>
            <input value="{$supplier_info.totalpricefreeshipping}" id="totalpricefreeshipping" type="hidden"/>
            <input value="{$supplier_info.totalquantityfreeshipping}" id="totalquantityfreeshipping" type="hidden"/>
						</td> 
	                    <td width="14%"><ul class="numcontrl clearfix">
	                        <a href="javascript:;" class="add-num" data-id="{$shoppingcart_info.id}" onclick="minus('qty_item_{$shoppingcart_info.id}','{$shoppingcart_info.id}')"><li>-</li></a>
	                        <li class="arial f12">
	                          <input readonly data-id="{$shoppingcart_info.id}" value="{$shoppingcart_info.quantity}"
														   id="qty_item_{$shoppingcart_info.id}" name="qty_item_{$shoppingcart_info.id}" type="text" >
	                        </li>
	                        <a href="javascript:;" class="sub-num" data-id="{$shoppingcart_info.id}" onclick="plus('qty_item_{$shoppingcart_info.id}','{$shoppingcart_info.id}')"><li>+</li></a>
	                      </ul></td> 
	                    <td width="13%"><p class=" arial" id="total_price_{$shoppingcart_info.id}">￥{$shoppingcart_info.total_price}</p></td>
	                    <td width="15%"><div class="item-6"><a href="javascript:;" class="btn btn-danger btn-sm order-colle deleteshoppingcart"  data-id="{$shoppingcart_info.id}">删除</a></div></td>
	                  </tr> 
				  {foreachelse}
          <div style="margin: 20px 0"><p class="msgbody" style="font-size: 30px;text-align: center;margin-top: 20px;"><span class="iconfont icon-tishi" style="color: #fe4401;font-size: 1em;"></span>您的购物车还是空的，快去选购商品吧<br> </p>  <p style="text-align: center;"><a href="index.php"><input type="button" value="去逛逛" class="btn btn-danger btn-sm btn120"></a></p></div>
				{/foreach} 
              </table>
              </form>
              <div class="bot-line"></div>
            </div>
          </div> 
        </div>
        <!--row-item-shop 购物车列表-->
        <div style="height: 45px;">
        <div class="order-button border fixbottom clearfix" id="getcar">
          <div class="pull-left btn-order-choice">
            <div class="niceform chklist pull-left m-l15 mr-10 mt-10" id="allselect_div">
              <input style="vertical-align: middle;" id="allselect" name="allselect" {if $shoppingcarts|@count > 0} checked{/if} value="1" type="checkbox"/>
            </div>
            <a>全选</a></div>
          <div class="pull-right btn-order-go" style="width:550px;">
            <a href="javascript:;" class="pull-right btn btn-danger btn-sm h35 p0 confirmpayment">结算</a>
            <p><span>已选商品<em class="red p-lr5 arial" id="total_quantity">{$total_quantity}</em>件</span><span class="m-l15" style="margin-top:-1px;">合计(免运费)：&nbsp;</span><span class="red arial"><em class="f24 plr5 prise-veiw" style="padding-top:5px;" id="total_money">￥{$total_money}</em></span></p>
          </div>
        </div>
        </div>
        <!--order-button-->
      </div>
      <!--order-index 购物车主页-->
    </div>
  </div>
  <!--cont 主体-->
  <div class="blank90"></div>
  <!--link 链接-->
   <div id="foot" class="clearfix"></div>
   	{include file='footbar.tpl'}
    {include file='footer.tpl'}
</div>
<!--warp 外层-->
</body>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="/public/pc/js/jquery.lazyload.min.js"></script> 
<script src="/public/pc/js/index.js"></script>
 <script language="javascript" type="text/javascript" src="/public/pc/js/niceforms.js"></script>
<script type="text/javascript">
var idlists = {$idlists};
var errmsg = "{$errorMsg}";
{literal}
$(function(){
	$('.chklist').hcheckbox();
	 //结算栏目固定在底部
	 var topFoot=$('#foot').offset().top+30;
	 var nav=$("#getcar");
	$(window).scroll(function(){
		if ($(window.document).scrollTop() == $(window.document).height()-$(window).height()){//如果滚动条顶部的距离大于topMain则就nav导航就添加类.nav_scroll，否则就移除。
			nav.removeClass("fixbottom");
		}
		else
		{
			nav.addClass("fixbottom");
		}
	});
  $('table input[type=checkbox]').each(function(){
    $(this).click(function(){
      setTimeout("recalc();", 50);
    })
  });
  $('#allselect_div').click(function(){
    setTimeout("allselect();", 50);
  });
  $('.confirmpayment').click(function ()
          {
            var total_quantity = $("#total_quantity").html();
            if (parseInt(total_quantity, 10) > 0)
            {
              document.frm.submit();
            }
            else
            {
              alert("购物车没有需要结算的商品！");
            }
  });
  $('.deleteshoppingcart').click(function(){
    var shoppingcartid = $(this).attr('data-id');
    if(confirm('确定删除订单吗？')){
      $("#shoppingcart_wrap_" + shoppingcartid).remove();
      $.ajax({
          type: 'POST',
          url: "shoppingcart_delete.ajax.php",
          data: 'record=' + shoppingcartid,
          success: function (json)
          {
            $.each(idlists, function (i, v)
            {
              if (v === shoppingcartid)
              {
                idlists.splice(i, 1);
              }
            });
            recalc();
            alert('删除成功');
          }
        });
    }
  });
});
//右侧导航
oRight(60); //如果需要调用左侧辅助导航需要两个函数oRight()与oLeftbur()两个函数，oRight()需要传一个内容到头部的差值
oLeftbur();
//右侧导航
rightBar('.btn-group-right ul', '.group-open');
function plus(obj,id){
  var oldv = parseInt($("#"+obj).val());
  $("#"+obj).val(oldv+1);
  setTimeout("recalc();", 50);
            var shoppingcartid = id;
            var qty_item       = $("#qty_item_" + shoppingcartid).val();
            productqty_change(shoppingcartid);
            $.ajax({
                   type: 'POST',
                   url: "shoppingcart_update.ajax.php",
                   data: 'record=' + shoppingcartid + '&qty_item=' + qty_item,
                   success: function (json)
                   {
                   }
                 });
}
function minus(obj,id){
  var oldv = parseInt($("#"+obj).val());
  if(oldv > 1){
    $("#"+obj).val(oldv-1);
    setTimeout("recalc();", 50);
            var shoppingcartid = id;
            var qty_item       = $("input#qty_item_" + shoppingcartid).val();
            productqty_change(shoppingcartid);
            $.ajax({
                   type: 'POST',
                   url: "shoppingcart_update.ajax.php",
                   data: 'record=' + shoppingcartid + '&qty_item=' + qty_item,
                   success: function (json)
                   {
                   }
                 });
  }else{
    $("#"+obj).val(oldv);
  }
}
function productqty_change(shoppingcartid){
    var qty_item       = $("#qty_item_" + shoppingcartid).val();
    var pice           = $("#shop_price_" + shoppingcartid).val();
    var includepost    = $("#includepost_" + shoppingcartid).val();
    var postage        = $("#postage_" + shoppingcartid).val();
    var mergepostage   = $("#mergepostage_" + shoppingcartid).val();
    var old_pice       = $("#old_shop_price_" + shoppingcartid).val();
    var zhekou         = $("#zhekou_" + shoppingcartid).val();
    var bargains       = $("#bargains_count_" + shoppingcartid).val();
    var activitymode   = $("#activitymode_" + shoppingcartid).val();
    var bargainsnumber = $("#bargainrequirednumber_" + shoppingcartid).val();

    var total_price    = parseFloat(pice, 10) * parseInt(qty_item, 10);
    if(zhekou != "" && parseInt(activitymode,10) == '1'){
      total_price = parseFloat(old_pice,10) - parseFloat(old_pice,10) * (10 - parseFloat(zhekou,10)) / 10 / parseInt(bargainsnumber,10) * parseInt(bargains,10);
    }
    var totalpricefreeshipping = $("#totalpricefreeshipping").val();
    var totalquantityfreeshipping = $("#totalquantityfreeshipping").val();
    var productallmoney = productallselectmoney();
    var productallcount = productallselectcount();
    if((parseFloat(totalpricefreeshipping,10) <= 0 || parseFloat(totalpricefreeshipping,10) > productallmoney) && (parseInt(totalquantityfreeshipping,10) <= 0 || parseInt(totalquantityfreeshipping,10) > productallcount))
    {
      if (parseFloat(postage, 10) > 0)
      {
        if (parseInt(mergepostage, 10) != 1)
        {
          postage = parseFloat(postage, 10) * parseInt(qty_item, 10);
        }
        $("#postage_span_" + shoppingcartid).html('¥' + parseFloat(postage, 10).toFixed(2));
        var pct = productcount($("#productid_" + shoppingcartid).val());
        if (parseInt(includepost, 10) > 0 && parseInt(includepost, 10) <= pct)
        {
          $("#postage_panel_" + shoppingcartid).css('display', 'none;');
        }
        else
        {
          $("#postage_panel_" + shoppingcartid).css('display', 'block;');
          total_price = parseFloat(total_price, 10) + parseFloat(postage, 10);
        }
      }
    }else{
      $("#postage_panel_" + shoppingcartid).css('display', 'none;');
    }
    $("#total_price_" + shoppingcartid).html('¥' + parseFloat(total_price, 10).toFixed(2));
  }
  function productcount(pid){
    var pc = 0;
    $.each(idlists, function (i, v){
      var checked = $("input#shoppingcart_" + v).prop("checked");
      if (checked){
        var product         = $("#productid_" + v).val();
        if(product == pid){
          var qty_item     = $("#qty_item_" + v).val();
          pc += parseInt(qty_item, 10);
        }
      }
    });
    return pc;
  }
  function productallselectcount(){
    var pc = 0;
    $.each(idlists, function (i, v){
      var checked = $("input#shoppingcart_" + v).prop("checked");
      if (checked){
        var qty_item = $("#qty_item_" + v).val();
        pc += parseInt(qty_item, 10);
      }
    });
    return pc;
  }
  function productallselectmoney(){
    var pc = 0;
    $.each(idlists, function (i, v){
      var checked = $("input#shoppingcart_" + v).prop("checked");
      if (checked){
        var qty_item = $("#qty_item_" + v).val();
        var pice     = $("#shop_price_" + v).val();
        pc += parseInt(qty_item, 10) * parseFloat(pice,10);
      }
    });
    return pc;
  }
  function recalc()
  {
    var total_money    = 0;
    var total_quantity = 0;
    var allpostage     = 0;
    var allmergepost   = 0;
    var isall          = true;
    var totalpricefreeshipping = $("#totalpricefreeshipping").val();
    var totalquantityfreeshipping = $("#totalquantityfreeshipping").val();
    var productallmoney = productallselectmoney();
    var productallcount = productallselectcount();
    $.each(idlists, function (i, v)
    {
      productqty_change(v);
      var checked = $("input#shoppingcart_" + v).prop("checked");
      if (checked)
      {
        var qty_item     = $("#qty_item_" + v).val();
        var pice         = $("#shop_price_" + v).val();
        var includepost  = $("#includepost_" + v).val();
        var postage      = $("#postage_" + v).val();
        var mergepostage = $("#mergepostage_" + v).val();
        var old_pice   = $("#old_shop_price_" + v).val();
        var zhekou     = $("#zhekou_" + v).val();
        var bargains   = $("#bargains_count_" + v).val();
        var activitymode = $("#activitymode_" + v).val();
        var bargainsnumber = $("#bargainrequirednumber_" + v).val();

        var total_price  = parseFloat(pice, 10) * parseInt(qty_item, 10);
        if(zhekou != "" && parseInt(activitymode,10) == '1'){
          total_price = parseFloat(old_pice,10) - parseFloat(old_pice,10) * (10 - parseFloat(zhekou,10)) / 10 / parseInt(bargainsnumber,10) * parseInt(bargains,10);
        }
        if((parseFloat(totalpricefreeshipping,10) <= 0 || parseFloat(totalpricefreeshipping,10) > productallmoney) && (parseInt(totalquantityfreeshipping,10) <= 0 || parseInt(totalquantityfreeshipping,10) > productallcount))
        {
          if (parseFloat(postage, 10) > 0)
          {
            var pct = productcount($("#productid_" + v).val());
            if (parseInt(includepost, 10) <= 0 || parseInt(includepost, 10) > pct)
            {
              if (parseInt(mergepostage, 10) != 1)
              {
                allmergepost += parseFloat(postage, 10) * parseInt(qty_item, 10);
              }
              else if (parseFloat(postage, 10) > parseFloat(allpostage, 10))
              {
                allpostage = parseFloat(postage, 10);
              }

            }
          }
        }
        total_money += parseFloat(total_price, 10);
        total_quantity += parseInt(qty_item, 10);
      }
      else
      {
        isall = false;
      }
    });
    if(idlists.length <= 0){
      isall = false;
    }
    total_money += allpostage + allmergepost;
    $("#total_money").html('¥' + total_money.toFixed(2));
    $("#total_quantity").html(total_quantity);
    //$("input#allselect").attr("checked", isall);
    $("input#allselect").prop("checked", isall);
  }
  function allselect()
  {
    var checked = $("#allselect").prop("checked");
    if (checked)
    {
      $.each(idlists, function (i, v)
      {
        $("input#shoppingcart_" + v).attr("checked", true);
        $("input#shoppingcart_" + v).prop("checked", true);
      });
      recalc();
    }
    else
    {
      $("#total_money").html("¥0.00");
      $("#total_quantity").html("0");
      $.each(idlists, function (i, v)
      {
        $("input#shoppingcart_" + v).attr("checked", null);
        $("input#shoppingcart_" + v).prop("checked", false);
        productqty_change(v);
      });
    }
  }
{/literal}
</script>

</html>
