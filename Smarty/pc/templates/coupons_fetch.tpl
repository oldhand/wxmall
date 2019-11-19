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
<link href="/public/pc/css/coupons.css" rel="stylesheet">
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
          <div class="person-post border hui bn">
            <div class="person-post-tit person-linetit">
              <h3 class="f16 pull-left">我的卡券包</h3>
              <ul class="pull-left">
                <li><a href="coupons.php" class="active">卡券优惠</a>|</li>
                <li><a href="couponsofme.php">我的卡券使用记录</a></li>
              </ul>
            </div>
            <div class="complaintbox border-t1">
              <div class="complaintbox-t favorable">
				<img src="">
              <form class="js-form block-wrapper-form promocard-fetch-form" name="frm" id="frm" method="post" action="coupons_view.php"> 
				<input name="record" type="hidden" value="{$record}">
				<input name="type" type="hidden" value="submit">
		        <div class="block-form-item">
		        	<input type="submit" value="点击领取优惠券" class="btn btn-danger btn-sm btn120 tph_getT">
		        </div>
		      </form>
	   		  <div style="border-top: 1px solid #e8e8e8;">
	            </div>
              </div>
            </div>
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
</html>
