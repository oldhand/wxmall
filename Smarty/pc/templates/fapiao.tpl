<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>填写发票</title>
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
        <p><a href="#">首页</a><em>&gt;</em><span>填写发票</span></p>
      </div>
      <!--break-person-->
      <div class="personbox">
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right hui" style="width: 100%">
          <!--收货地址管理-->
         
          <div class="deposlt border">
            <div class="person-linetit clearfix">
              <h3 class="f16">填写发票</h3>
            </div>
            <!--person-linetit-->
            <div class="findKey-form-box border-t1">
              <div class="blank20"></div>
              <form class="mui-input-group">
              <ul class="findKey-form clearfix" style="margin:0 auto; width:600px;">
                  <li> <span>单位全称：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" id="fapiao" value="{$fapiao}" class="mui-input-clear" maxlength="40" placeholder="您的单位全称">
                      </p>
                    </div>
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
<script type="text/javascript"> 
{literal} 
  $('.save').click(function(){
        var fapiao = $("#fapiao").val();
        window.location.href='submitorder.php?fapiao='+fapiao
      }); 
{/literal}
</script>  
</html>
