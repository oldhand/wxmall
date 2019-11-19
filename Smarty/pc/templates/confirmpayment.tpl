<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-在线付款</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/order.css" rel="stylesheet">
<script src="public/pc/js/common.js" type="text/javascript" charset="utf-8"></script>
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
          <h3 class="pull-left">在线付款</h3>
          <input id="orderid" name="orderid" value="{$orderid}" type="hidden">
        <input id="type" name="type" value="" type="hidden">
        <input id="totalprice" name="totalprice" value="{$total_money}" type="hidden">
        <input id="money" name="money" value="{$availablenumber}" type="hidden">
        <input id="allmoney" name="allmoney" value="{$profile_info.money}" type="hidden">
        <input id="moneypaymentrate" name="moneypaymentrate" value="{$moneypaymentrate}" type="hidden">
        <input id="needpayable" name="needpayable" value="{$total_money}" type="hidden">
        <input id="vipcardusageid" name="vipcardusageid" value="{$vipcard_usage_info.id}" type="hidden">
        <input id="vipcardusageamount" name="vipcardusageamount" value="{$vipcard_usage_info.amount}" type="hidden">
          <ul class="pull-right order-tit-step">
            <li><a href="javascript:;">查看购物车<span></span></a></li>
            <li><a href="javascript:;" class="w105">填写订单 <span></span></a></li>
            <li><a href="javascript:;" class="red">确认订单，去付款<span></span></a></li>
            <li><a href="javascript:;">完成购买</a></li>
          </ul>
        </div>
        <!--order-tit-->
        <div class="pay">
          <div class="pay-top">
            <h4>您的订单已成功，现在就去付款吧</h4>
            <div class="pay-top-inner">
              <p><span><strong>收货人：</strong><em class="red arial">{$deliveraddress.consignee}（{$deliveraddress.mobile}）</em></span><span><strong>应付金额：</strong><em class="red arial">{$total_money}</em> 元</span><span><strong>支付方式：</strong>在线支付</span>
                <psna>
                <strong>收货地址： </strong>{$deliveraddress.province}{$deliveraddress.city}{$deliveraddress.district}</span></p>
              <p>{$deliveraddress.shortaddress}</p>
            </div>
          </div>
          <!--pay-top-->
          <div class="blank20"></div>
          <div id="vipcards" class="mui-popover mui-popover-action mui-popover-bottom f16" style="margin-bottom: 20px;padding-left: 40px;">
            <ul class="mui-table-view">
              {foreach name="vipcardusages" item=vipcardusage_info  from=$vipcardusagelist}
                <li class="mui-table-view-cell">
                  <a href="#" data-id="{$vipcardusage_info.id}" data-amount="{$vipcardusage_info.amount}">{$vipcardusage_info.vipcardname}
                    {if $vipcardusage_info.orderamount eq '0'}
                      【下单可用,{if $vipcardusage_info.timelimit eq '0'}限次{else}不限次{/if}】
                    {else}
                      【满{$vipcardusage_info.orderamount}元可用,{if $vipcardusage_info.timelimit eq '0'}限次{else}不限次{/if}】
                    {/if}
                  </a>
                </li>
              {/foreach}
            </ul>
            <ul class="mui-table-view">
              <li class="mui-table-view-cell">
                <a href="#vipcards" data-id="" data-amount="" style="font-weight:900;">本次不使用卡券</a>
              </li>
            </ul>
          </div>
          <div class="pay-bot border">
          {if $profile_info.money gt 0}
            <div class="pay-bot-top">
              <h4>
                <div class="niceform chklist">
                  <input {if $frozenstatus eq 'Frozen'}disabled{/if} id="usemoney" class="tph_checkbox" name="usemoney" value="1" type="checkbox"/>
                  <label><span class="fn">使用余额支付</span></label>
                  <p style="line-height: 150%;margin-top: 20px;margin-left: 22px;">您的余额：<span class="totalprice" id="allowmoney">【{$profile_info.money}</span>元】</p>
                  {if $frozenstatus eq 'Frozen'}
                  <p class="mui-table-view-cell" style="text-align:center;">
                      <span style="color:#CF2D28; font-size:1.1em; font-weight:500;">账号异常冻结，禁止使用余额！请联系客服! </span>
                  </p>
                  {/if}
                  <p style="line-height: 150%;margin-left: 22px;">本单余额可用为【<span class="totalprice" id="allowmoney">￥{$availablenumber}</span>】</p>
                </div>
              </h4>
              <h4 class="m-l22"><strong>您还需要支付：<span class="red arial" id="needpayment">{if $vipcard_usage_info|@count gt 0}{$vipcard_usage_info.total_money}{else}{$total_money}{/if}</span>元</strong></h4>
            </div>
            {/if}
            <!--pay-bot-top-->
            <div class="pay-bot-bank" id="paymentwaygroup">
              <p class="mb-10">选择支付方式：</p>
              <ul class="clearfix" id="radiogroup">
                <li><a title="微信支付" class="bank-wx radiogroup"  groupid="payment" paymentway="weixin" href="javascript:;"></a>
                <div style="display:none">
                      <input class="paymentway" id="weixin" type="radio" name="paymentway" value="weixin"/>
                    </div>  
                <span></span></li>
              </ul>
            </div>
          </div>
          <div class="blank20"></div>
          
          <p class="btn-pay text-center">
            <input type="button" class="btn btn-lg btn-danger btn160 h45 f14 p0 fw" id="confirmpaymentbtn" value="去付款">
          </p>
        </div>
        <!--pay 订单支付-->
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
<div class="order-alert order-alert2 pop1" style="display: none;text-align: center;">
  <div class="order-alert-tit closealert"> <a href="javascript:;" class="alertclose pull-right" style="line-height: 100%;">x</a>
    <h4>请扫描以下二维码付款</h4>
  </div>
  <img src="/public/pc/images/wxpay.jpg" width="80%" style="margin-top: 20px">
  <div style="margin: 0 auto;width:284px;height:284px;padding: 10px;border:5px solid #333;margin-top: 20px;">
    
    <span class="iconfont icon-loading rolling" style="font-size: 30px;text-align: center;line-height: 274px;"></span>
    <div id="qrcode"></div>
  </div>
  <p class="tips"></p>
</div>
<!--warp 外层-->
</body>
<div id="zhezhao" style="width: 1903px; height: 3893px; display: none;"></div>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="/public/pc/js/index.js"></script>
<script language="javascript" type="text/javascript" src="/public/pc/js/niceforms.js"></script>
<script src="/public/pc/js/jquery.qrcode.min.js"></script>
<script type="text/javascript">
var returnbackatcion = "{$returnbackatcion}";
  {literal}
   $(document).ready(function() { //jquery代码随着document加载完毕而加载 
  $("#usemoney").click(function() {
    onusemoneychange();
  });
  $(".alertclose").click(function() {
    $('.pop1').hide();
    $('.icon-loading').hide();
    $('#zhezhao').hide();
  });
  $("#zhezhao").click(function() {
    $('.pop1').hide();
    $('.icon-loading').hide();
    $('#zhezhao').hide();
  });
  $('.radiogroup').each(function ()
          {
            $(this).click(function(){
            if ($('#type').val() == 'submit') return;
            var paymentway = $(this).attr('paymentway');
            $(".radiogroup").removeClass("active");
            $(this).addClass("active");

            $(".paymentway").attr("checked", false);
            $(".paymentway").prop("checked", false);
            $("#" + paymentway).attr("checked", true);
            $("#" + paymentway).prop("checked", true);
          })
          });
  $('#confirmpaymentbtn').click(function ()
          {
            var paymentway = "";
            $('input[name=paymentway]').each(function (index)
             {
               if ($(this).attr("checked") == "checked")
               {
                 paymentway = $(this).val();
               }
             });

            var needpayable = $('#needpayable').val();
            if (paymentway == "" && parseFloat(needpayable, 10) > 0)
            {
              alert("请选择支付方式！");
              return;
            }else{
              $('#zhezhao').show();
            $('.pop1').show(); 
            }
            if ($('#type').val() == 'submit') return;
            confirmpayment();
          });
  $('#vipcards a').each(function(){
    $(this).click(function ()
          {
            var a = this, parent;
            //根据点击按钮，反推当前是哪个actionsheet
            for (parent = a.parentNode; parent != document.body; parent = parent.parentNode)
            {
              if (parent.classList.contains('mui-popover-action'))
              {
                break;
              }
            }
            //关闭actionsheet
            //$('#' + parent.id).slideToggle();

            var usageid = $(this).attr("data-id");
            var amount  = $(this).attr("data-amount");
            $('#vipcardusageid').val(usageid);
            $('#vipcardusageamount').val(amount);
            $('#vipcard_msg').html(a.innerHTML);
            onusemoneychange();
          })
  })
  
});
function onusemoneychange()
  {
    if($('#usemoney').is(":checked"))
//    if ($('#usemoney').prop('checked'))
    {
      var totalprice         = $("#totalprice").val();
      var money              = $("#money").val();
      var vipcardusageamount = $("#vipcardusageamount").val();
      var moneypaymentrate   = $("#moneypaymentrate").val();
      var allmoney           = $("#allmoney").val();

      var newtotalprice       = parseFloat(totalprice, 10);
      var newmoney            = parseFloat(money, 10);
      var newallmoney         = parseFloat(allmoney, 10);
      var newmoneypaymentrate = parseFloat(moneypaymentrate, 10);

      var newvipcardusageamount;
      if (vipcardusageamount == "")
      {
        newvipcardusageamount = 0;
      }
      else
      {
        newvipcardusageamount = parseFloat(vipcardusageamount, 10);
      }
      $("#discount").html(newvipcardusageamount.toFixed(2));
      if (newmoneypaymentrate == 100)
      {
        if ((newmoney + newvipcardusageamount) >= newtotalprice)
        {
          $("#needpayment").html('0.00');
          $("#needpayable").val('0');
          $("#paymentwaygroup").css("display", "none");
        }
        else
        {
          var needpayment = newtotalprice - newmoney - newvipcardusageamount;
          $("#needpayable").val(needpayment);
          $("#needpayment").html(needpayment.toFixed(2));
          $("#paymentwaygroup").css("display", "");
        }
      }
      else
      {
        var needpayment = newtotalprice - newvipcardusageamount;
        var allowmoney  = needpayment - newtotalprice * (100 - newmoneypaymentrate) / 100;
        if (newallmoney > allowmoney)
        {
          var remain = needpayment - allowmoney;
          $("#allowmoney").html('￥' + allowmoney.toFixed(2));
          $("#money").val('￥' + allowmoney.toFixed(2));
          $("#needpayable").val(remain);
          $("#needpayment").html(remain.toFixed(2));
        }
        else
        {
          var remain = needpayment - newallmoney;
          $("#allowmoney").html('￥' + newallmoney.toFixed(2));
          $("#money").val('￥' + newallmoney.toFixed(2));
          $("#needpayable").val(remain);
          $("#needpayment").html(remain.toFixed(2));
        }
        $("#paymentwaygroup").css("display", "");
      }

    }
    else
    {
      var totalprice         = $("#totalprice").val();
      var newtotalprice      = parseFloat(totalprice, 10);
      var vipcardusageamount = $("#vipcardusageamount").val();
      var newvipcardusageamount;
      if (vipcardusageamount == "")
      {
        newvipcardusageamount = 0;
      }
      else
      {
        newvipcardusageamount = parseFloat(vipcardusageamount, 10);
      }

      $("#discount").html(newvipcardusageamount.toFixed(2));
      var needpayment = newtotalprice - newvipcardusageamount;
      $("#needpayable").val(needpayment);
      $("#needpayment").html(needpayment.toFixed(2));
      $("#paymentwaygroup").css("display", "");
    }
  }

  function confirmpayment()
  {
    var paymentway = "";
    $('input[name=paymentway]').each(function (index)
                       {
                         if ($(this).attr("checked") == "checked")
                         {
                           paymentway = $(this).val();
                         }
                       });

    var needpayable = $('#needpayable').val();
    if (paymentway == "" && parseFloat(needpayable, 10) > 0)
    {
      alert("请选择支付方式！");
      return;
    }

    var vipcardusageid     = $('#vipcardusageid').val();
    var vipcardusageamount = $('#vipcardusageamount').val();

    $('input[name=paymentway]').prop("disabled", 'disabled');
    $('#type').val('submit');

    var orderid  = $('#orderid').val();
    var usemoney = '0';
    if($('#usemoney').is(":checked"))
//    if ($('#usemoney').prop('checked'))
    {
      usemoney = '1';
    }
    $('#usemoney').prop("disabled", 'disabled');

    $('.pop1 .icon-loading').show();
    if(returnbackatcion != ""){
      $("#mui-action-back").removeAttr("href");
    }else
    {
      $("#mui-action-back").removeClass("mui-action-back");
    }
    // alert(orderid);
    // alert(paymentway);
    // alert(usemoney);
    // alert(needpayable);
    // alert(vipcardusageid);
    // alert(vipcardusageamount);
    $.ajax({
           type: 'POST',
           url: "pcsaveorder.php",
           data: 'orderid=' + orderid + '&paymentway=' + paymentway + '&usemoney=' + usemoney + '&needpayable=' + needpayable + '&vipcardusageid=' + vipcardusageid + '&vipcardusageamount=' + vipcardusageamount,
           success: function (json)
           {
             //console.log(json);
             var jsondata = eval("(" + json + ")");
             console.log(jsondata);
             $('.icon-loading').hide();
             $('#qrcode').qrcode(jsondata.json);
             if (jsondata.code == 200)
             {

               if (jsondata.paymentway == 'weixin')
               {
                setInterval(check_notify(1),50)
                 //callpay(jsondata.json);
               }
               else if (jsondata.paymentway == 'tzb')
               {
                 var orderid          = $('#orderid').val();
                 window.location.href = 'completepayment.php?record=' + orderid;
               }
               else
               {
                 alert('支付失败');
                 setTimeout("back_confirmpayment();", 1500);
               }

             }
             else
             {
               //alert(jsondata.msg);
               setTimeout("back_confirmpayment();", 1500);
             }
           }
         });
  }
  function check_notify(time) 
    {
          var orderid = $('#orderid').val();  
              $.ajax({
                  type: 'POST',
                  url: "payment.ajax.php",
                  data: 'record=' + orderid +'&m='+ Math.random(),
                  success: function(json) {  
                      var msg = eval("("+json+")");
                      //console.log(msg);
                      if (msg.code == 200) 
            {   
                window.location.href = 'completepayment.php?record=' + orderid;
            }
            else
            {
                 var newtime = time + 1;
                 $('#check_time').html(newtime); 
                 setTimeout('check_notify('+newtime+');', 1000);
                 $('.tips').html('确认支付中。。。请稍后');
            }
           
           }
              }); 
         } 
  function back_confirmpayment()
  {
    $('input[name=paymentway]').prop("disabled", '');
    $('#type').val('');
    $('#usemoney').prop("disabled", '');

    $("#payment_button").html('确定支付');

    $("#payment_icon").removeClass("icon-loading");
    $("#payment_icon").removeClass("mui-rotation");

    $("#payment_icon").addClass("icon-qian");

    $("#confirmpayment").addClass("confirmpayment");
    if(returnbackatcion != ""){
      $("#mui-action-back").attr("href",returnbackatcion);
    }else
    {
      $("#mui-action-back").addClass("mui-action-back");
    }
    alert("支付失败！请重新支付");
  }
  function jsApiCall(jsondata)
  {
    wx.invoke(
        'getBrandWCPayRequest',
        jsondata,
        function (res)
        {
          //WeixinJSBridge.log(res.err_msg);
          //alert(res.err_code+res.err_desc+res.err_msg); 
          if (res.err_msg == "get_brand_wcpay_request:cancel")
          {
            alert("您的支付已经取消！");
            setTimeout("back_confirmpayment();", 1500);
          }
          else if (res.err_msg == "get_brand_wcpay_request:ok")
          {
            var orderid          = $('#orderid').val();
            window.location.href = 'completepayment.php?record=' + orderid;
          }
          else if (res.err_msg == "get_brand_wcpay_request:fail")
          {
            alert("支付失败！");
            setTimeout("back_confirmpayment();", 1500);
          }
          else
          {
            alert(res.err_msg);
            setTimeout("back_confirmpayment();", 1500);
          }
        }
    );
  }

  function callpay(jsondata)
  {
    if (typeof WeixinJSBridge == "undefined")
    {
      if (document.addEventListener)
      {
        setInterval(function(){
          var orderid = $('#orderid').val();
        window.location.href = 'completepayment.php?record=' + orderid;
        },10000);
        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
      }
      else if (document.attachEvent)
      {
        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
      }
    }
    else
    {
      jsApiCall(jsondata);
    }
  }
{/literal}
  </script>
  <script src="/public/pc/js/jquery.lazyload.min.js"></script>
</html>
