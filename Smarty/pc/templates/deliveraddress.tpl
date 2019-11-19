<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>个人中心(收货地址)</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<link href="/public/pc/css/login.css" rel="stylesheet">
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
        <p><a href="#">首页</a><em>&gt;</em><a href="usercenter.php">个人中心</a><em>&gt;</em><span>收货地址</span></p>
      </div>
      <!--break-person-->
      <div class="personbox">
        {include file='usercenterL.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right hui">
          <div class="address-top">
            <div class="person-linetit clearfix  border">
              <h3 class="f16 pull-left">收货地址管理</h3>
              <p class="pull-right text-right greey">（最多保存10个有效地址。每月只能新增或修改10次）</p>
            </div>
            <!--person-linetit-->
            <div class="blank20"></div>
            <div class="address-top-body clearfix">
            <input type="hidden" id="orderid" name="orderid" value="{$orderid}"/>
                {foreach name="deliveraddress" key=deliveraddressid item=deliveraddress_info  from=$deliveraddress}
                  <div class="addresslist {if $deliveraddress_info.selected eq '1'}choicebox{/if}"> <span class="choice"></span>
                    <ul class="address-view">
                      <li class="clearfix"><span>收货人：</span><em class="greey">{$deliveraddress_info.consignee}</em></li>
                      <li class="clearfix"><span>收货人地址：</span><em class="greey">{$deliveraddress_info.province}{$deliveraddress_info.city}{$deliveraddress_info.district}{$deliveraddress_info.shortaddress}</em>
                      <li class="clearfix"><span>收货人电话：</span><em class="greey">{$deliveraddress_info.mobile}</em>
                    </ul>

                    <p class="address-contorl bg-f5 h35 text-center"> <!-- <a href="deliveraddress_edit.php?orderid={$orderid}">修改</a>| -->{if $deliveraddressinfo.recordid neq ''}<a href="deliveraddress.php?type=delete&record={$deliveraddressinfo.recordid}">删除</a>|{/if}<a class="confirmadress" href="javascript:;" data-id="{$deliveraddressid}">确认</a>  </p>
                    <!-- <input {if $deliveraddress_info.selected eq '1'}checked{/if} value="{$deliveraddressid}" id="deliveraddress_{$deliveraddressid}" name="deliveraddress" type="hidden" style="margin-top:20px;"> -->
                  </div>
                {foreachelse}
                <div class="mui-content-padded">
                  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>
                  <p class="msgbody">您的还没有收货地址，快点新建您的收货地址吧!<br>
                  </p>
                </div>
              {/foreach}  
             
            </div>
          </div>
          <!--收货地址管理-->
          <div class="blank20"></div>
          <div class="deposlt border">
            <div class="person-linetit clearfix">
              <h3 class="f16">添加收货地址</h3>
            </div>
            <!--person-linetit-->
            <div class="findKey-form-box border-t1">
              <div class="blank20"></div>
              <ul class="findKey-form clearfix" style="margin-left:35px; width:600px;">
                  <div>
                    <a href="deliveraddress_edit.php?orderid={$orderid}" type="button" class="btn btn-danger btn120" style="margin-bottom: 20px;">新建地址</a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <!--deposlt 提现申请-->
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
<script type="text/javascript">
  {literal}
  $('.confirmadress').each(function(index){
    $(this).click(function(){
    var deliveraddressid = "";
    var orderid = "";

    orderid = $('input[name=orderid]').val();

    deliveraddressid = $(this).attr('data-id');

    if (deliveraddressid == "")
    {
      alert('请新建或选择收货地址！');
    }
    else
    {
      window.location.href='submitorder.php?deliveraddressid=' + deliveraddressid + '&record='+orderid;
    }
    })
  });
  {/literal}
</script>
</html>
