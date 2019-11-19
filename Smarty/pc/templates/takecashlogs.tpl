<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>提现申请</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet">
<link href="/public/pc/css/login.css" rel="stylesheet">
<script src="public/pc/js/common.js" type="text/javascript" charset="utf-8"></script>
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="warp">
  {include file='header.tpl'}
  <div class="line3"></div></div>
  <!--head 头部-->
  <div class="cont">
    <div class="container">
      <div class="break-person clearfix">
        <p><a href="#">首页</a><em>&gt;</em><a href="usercenter.php">个人中心</a></p>
      </div>
      <!--break-person-->
      <div class="personbox">
        {include file='usercenterL.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right hui">
          <div class="safebox border bn">
            <div class="person-linetit clearfix">
              <h3 class="f16 pull-left w100">提现申请</h3>
              <p class="pull-left"><!-- 当前可用资金
                <span class="red arial p-lr5">{$profile_info.money}</span> 元，冻结中资金<span class="red arial p-lr5">{$frozencommission}</span> 元 累计收益<span class="red arial p-lr5">{$profile_info.accumulatedmoney}</span> 元 --></p>
            </div>
            <!--person-linetit-->
            <div class="curtain-tit border-t1 clearfix">
              <ul style="margin-left:380px;">
                <li><a href="takecashs.php">提现申请</a>|</li>
                <li><a href="takecashlogs.php" class="active">历史提现记录</a></li>
              </ul>
            </div>
            <div class="curtain-body">
              <div class="curtain-body-lr">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="zbtable">
                  <thead>
                    <tr class="black">
                      <td>开户行</td>
                      <td>收款人姓名</td>
                      <td>银行账号</td>
                      <td>提现金额</td>
                      <td>身份证</td>
                      <td>提现日期</td>
                      <td>驳回原因</td>
                    </tr>
                  </thead>
                  <input id="page" value="1" type="hidden" > 
                  <tbody id="list">
                    
                  </tbody>
                </table>
              </div>
              <div class='clearfix' style="text-align: center;border-top: 1px solid #e6e6e6;">
                  <div class="btn btn-default" style="padding: 10px 50px;margin: 20px 0;" id="addMore"><span class="iconfont icon-loading rolling"></span><span class="btntext">加载更多</span></div>
              </div>
              <div style="border-top: 1px solid #e8e8e8;">
              </div>
            </div>
          </div>
          <!--safe-box-->
          <div class="blank20"></div>
          <div class="pagebox clearfix">
           <!--  <ul class="page pull-right list-inline">
              <li ><a href="#" class="pageprev">&lt;&nbsp;上一页</a></li>
              <li class="active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#" class="none">....</a></li>
              <li ><a href="#" class="pagenext">下一页&nbsp;&gt;</a></li>
            </ul> -->
            <!--分页-->
          </div>
          <div class="blank20"></div>
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
      url: "takecashlogs.ajax.php",
      data: 'page=' + page,
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
          $('.deleteorder').each(function(){
            $(this).click(function(){
              var orderid = $(this).attr('data-id');
              if(confirm('确定删除订单吗？')){
                window.location.href = 'orders_pendingpayment.php?type=delete&record=' + orderid;
              }
            })
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
    sb.append('<tr>');
    sb.append('<td>'+v.bank+'</td>');
    sb.append('<td>'+v.realname+'</td>');
    sb.append('<td>'+v.account+'</td>');
    sb.append('<td>'+v.amount+'</td>');
    sb.append('<td>'+v.idcard+'</td>');
    sb.append('<td>'+v.published+'</td>');
    if (v.takecashsstatus == "驳回申请")
{
    sb.append('<td>'+v.rejectreason+'</td></tr>');
}
    return sb.toString();
  }

  $('#tabbox>div').eq(0).show();

 {/literal}
</script>
</html>
