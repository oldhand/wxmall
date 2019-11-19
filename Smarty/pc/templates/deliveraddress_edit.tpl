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
<style type="text/css">
{literal}
.person-dj-tit ul{
  width: 320px;
}
.findKey-form input{
  color: #666;
}
.findKey-form li{
  margin: 16px 0;
}
{/literal}
</style>
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
          <!--收货地址管理-->
         
          <div class="deposlt border">
            <div class="person-linetit clearfix">
              <h3 class="f16">添加收货地址</h3>
            </div>
            <!--person-linetit-->
            <div class="findKey-form-box border-t1">
              <div class="blank20"></div>
              <form class="mui-input-group" name="frm" id="frm" method="post" action="deliveraddress.php">
              <ul class="findKey-form clearfix" style="margin-left:150px; width:600px;">
                  <li> <span>收货人姓名：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" placeholder="请输入收货人姓名"  value="{$deliveraddressinfo.consignee}" name="consignee">
                      </p>
                    </div>
                  </li>
                  <li>
                  <span>收货人地址：</span>
                      <div id="distpicker">
                        <div class="form-group">
                          <label class="sr-only" for="province4">省</label>
                          <select class="form-control" id="province4"></select>
                        </div>
                        <div class="form-group">
                          <label class="sr-only" for="city4">市</label>
                          <select class="form-control" id="city4"></select>
                        </div>
                        <div class="form-group">
                          <label class="sr-only" for="district4" >区</label>
                          <select class="form-control" id="district4"></select>
                        </div>
                        <input id="province" name="province" type="hidden" value="{$deliveraddressinfo.province}">
                  <input id="city" name="city" type="hidden" value="{$deliveraddressinfo.city}">
                  <input id="district" name="district" type="hidden" value="{$deliveraddressinfo.district}">
                  <input name="type" value="submit" type="hidden" >
                  <input name="record" type="hidden" value="{$deliveraddressinfo.recordid}">
                  <input type="hidden" id="orderid" name="orderid" value="{$orderid}"/>
                  <input data-options='{ldelim}"province":"{$deliveraddressinfo.province}","city":"{$deliveraddressinfo.city}","district":"{$deliveraddressinfo.district}"{rdelim}' required="required" id="citypicker" name="citypicker" value="{$deliveraddressinfo.province} {$deliveraddressinfo.city} {$deliveraddressinfo.district}" type="text" class="mui-input-clear citypicker required" placeholder="地区信息" hidden>
                      </div>
                </li>
                <li> <span>收货人地址：</span>
                  <div>
                    <p>
                      <input type="text" class="text w400 gray-c" value="{$deliveraddressinfo.shortaddress}" placeholder="请输入详细地址" name="address">
                    </p>
                  </div>
                </li>
                <li> <span>手机：</span>
                  <div>
                    <p>
                      <input type="text" class="text w400 gray-c mui-input-clear required" value="{$deliveraddressinfo.mobile}" required="required" parsley-rangelength="[11,11]" id="mobile" name="mobile" value="{$deliveraddressinfo.mobile}" type="number" maxlength="11" placeholder="11位手机号" parsley-error-message="请输入正确的手机号码">
                    </p>
                  </div>
                </li>
                <li> <span>邮编：</span>
                  <div>
                  <p>
                    <input class="text w400 gray-c" name="zipcode" type="number" value="{$deliveraddressinfo.zipcode}" placeholder="邮政编码">
                  </p>
                </li>
                <li> <span>&nbsp;</span>
                  <div>
                    <input type="buttom" value="保存" style="color: #fff;" class="btn btn-danger btn-sm btn120 save">
                  </div>
                </li>
              </ul>
              </form>
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
<script src="/public/pc/js/distpicker.data.js"></script>
<script src="/public/pc/js/distpicker.js"></script>
<script type="text/javascript"> 
{literal} 
  var $distpicker = $('#distpicker');

  $distpicker.distpicker({

  });

  $('.save').click(function(){
    var province = $('#province4').val();
    var city = $('#city4').val();
    var district = $('#district4').val();
    $('#province').val(province);
    $('#city').val(city);
    $('#district').val(district);
    document.frm.submit();
  });
{/literal}
</script>  
</html>
