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
      <div class="loginbox">
      <div class="findKey-form-box">
        <div class="text-center">
          <img style="margin-top: 120px;" src="/public/pc/images/success.png">
          <p style="font-size: 25px;margin-top: 20px;margin-bottom: 120px;">支付成功！<a href="index.php" class="red h35">返回首页</a></p>
        </div>
      </div>
    </div>
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
<script src="/public/pc/js/index.js"></script>
<script language="javascript" type="text/javascript" src="/public/pc/js/niceforms.js"></script>
<script src="/public/pc/js/jquery.qrcode.min.js"></script>
<script type="text/javascript">

</script>
<script src="/public/pc/js/jquery.lazyload.min.js"></script>
</html>
