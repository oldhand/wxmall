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
          <div class="person-dj border bn">
            <div class="person-post-tit person-linetit">
              <h3 class="f16 pull-left">我的订单</h3>
              <ul class="pull-left">
                <li><a href="orders_payment.php">全部已付款订单</a>|</li>
                <li><a href="orders_pendingpayment.php">待付款</a>|</li>
                <li><a href="orders_sendout.php">待发货</a>|</li>
                <li><a href="orders_receipt.php">待收货</a>|</li>
                <li><a href="orders_appraise.php" class="active">待评价</a></li>
              </ul>
            </div>
            <!--person-post-tit-->
            <div id="tabbox" class="border-t1">
              <div class="person-dj-body">
                <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui">
                  <thead>
                    <tr class=" h35 black">
                      <td width="2%"></td>
                      <td width="13%" align="left">商品信息</td>
                      <td width="28%"></td>
                      <td width="11%">数量</td>
                      <td width="11%">订单金额</td>
                      <td width="11%">订单状态</td>
                      <td width="11%">操作</td>
                    </tr>
                  </thead>
                </table>
                <div class="clearfix"></div>
                <div class="dj-lr border-t1">
                <input id="page"  type="hidden" value="1"> 
                  <ul id="list">
                  </ul>
                </div>
                <div class='clearfix' style="text-align: center;border-top: 1px solid #e6e6e6;">
                    <div class="btn btn-default" style="padding: 10px 50px;margin: 20px 0;" id="addMore"><span class="iconfont icon-loading rolling"></span><span class="btntext">加载更多</span></div>
                </div>
              </div>
            </div>
            <!--商品列表-->
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
<script>
 {literal}
 $(document).ready(function() { //jquery代码随着document加载完毕而加载 
  $('#page').val(1);
  ajaxForPages();
  //加载更多
  $("#addMore").click(function() {
    $(".btntext").html('');
    $(".rolling").show();
    ajaxForPages();
  });
  
});
//分页查询 
  function ajaxForPages() {
    var page = $('#page').val(); 
    $('#page').val(parseInt(page) + 1);
    $.ajax({
      type: 'POST',
      url: "orders.ajax.php",
      data: 'type=appraise&page=' + page,
      success: function callbackFun(data) {
        //解析json 
        var info = eval("(" + data + ")");
        $('.result_num').html(info.data.length);
        if (info.code == 200) {
          $(".rolling").hide();
          $(".btntext").html('加载更多');
          //清空数据
          $.each(info.data,function(i,value) { 
            var nd = product_html(value);
            $("#list").append(nd);
          })
        }else{
          $(".rolling").hide();
          $(".btntext").html('没有更多了');
          setTimeout(function(){$(".btntext").html('加载更多');},1500)
        }
      }
    });
  }

  function product_html(v) {
    var sb = new StringBuilder();
    sb.append('<li>');
    sb.append('<div class="dj-lr-list ">');
    sb.append('<div class="dj-lr-t bg-f5 black">');
    sb.append('<p><span>订单号：' + v.mall_orders_no + '</span></p>');
    sb.append('</div>');
    sb.append('<table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">');
    sb.append('<tbody>');
    sb.append('<tr height="140">');
    sb.append('<td width="2%"></td>');
    sb.append('<td width="13%" align="left"><a href="orderdetail.php?record='+v.orderid+'" target="_blank"><img src="' + v.thumbnail + '" width="100" height="100"></a></td>');
    sb.append('<td width="28%" align="left"><p><a href="orderdetail.php?record='+v.orderid+'" target="_blank">' + v.ordername + '</a></p></td>');
    sb.append('<td width="11%"><span class="arial">' + v.productcount + '</span></td>');
    sb.append('<td width="11%"><span class="arial">￥' + v.sumorderstotal + '</span></td>');
    sb.append('<td width="11%"><span class="red">' + v.order_status + '</span></td>');
    sb.append('<td width="11%"><p class="mb-5"><a href="appraise_submit.php?record=' + v.orderid + '" class="btn btn-danger btn-sm btn-p10">去评价</a></p>');
    sb.append('<p class="text-center"><a class="deleteorder" href="aftersaleservice.php?record=' + v.orderid + '">售后服务</a></p></td>');
    sb.append('</tr>');
    sb.append('</tbody>');
    sb.append('</table>');
    sb.append('</div>');
    sb.append('</li>');
    return sb.toString();
  }

  var $div_li =$(".person-dj-tit li");
  $('#tabbox>div').eq(0).show();
  $div_li.click(function(){
      $(this).find('a').addClass("red")            
           .end().siblings().find('a').removeClass("red");
      var index =  $div_li.index(this); 
      $('#tabbox>div')  
          .eq(index).show()   
          .siblings().hide();
      return false;
  });

 {/literal}
</script>
</html>
