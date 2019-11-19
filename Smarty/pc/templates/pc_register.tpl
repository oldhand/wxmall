<!DOCTYPE html>
<html lang='zh-cn' class='m-user m-user-register'>
<head> 
    <title>注册 - {$supplier_info.suppliername}</title>
    <meta name="keywords" content="注册 - {$supplier_info.suppliername}">
    <meta name="description" content="注册 - {$supplier_info.suppliername}">
	
	<script>
	    {literal}  
		var config={"webRoot":"\/","cookieLife":30,"requestType":"PATH_INFO","requestFix":"-","moduleVar":"m","methodVar":"f","viewVar":"t","defaultView":"html","themeRoot":"\/theme\/","currentModule":"user","currentMethod":"register","clientLang":"zh-cn","requiredFields":"account,realname,email,password1","save":"\u4fdd\u5b58","router":"\/index.php","runMode":"front","langCode":""}
		if(typeof(v) != "object") 
			v = {};
		v.theme = {"template":"default","theme":"wide","device":"desktop"};
	    {/literal}
	</script>
	{include file='header.tpl'} 	
	<style>
	 {literal}
	 .user-control-nav{margin-bottom: 20px;}
	 .break-all {word-wrap: break-word; word-break: break-all;}
	 .pager {margin:0px;}
	 @media (max-width: 480px) { .hidden-xxs {display: none} .page {font-size: 12px}}
	 @media (max-width: 400px) { .hidden-xxxs {display: none}}

	 #reset {max-width: 450px; margin: 20px auto;}
	 @media (max-width: 767px) {#reset {margin: 10px auto;} #reset .panel {margin: 20px auto; width: 100%;} #reset .panel-heading {padding: 0 0 10px 0;} #reset .panel-body {padding: 10px 0; min-height: inherit;} .panel-body > form {padding-right: 0;}}

	 #bindForm{height: 280;}
	 .nav-primary.nav-stacked > li.nav-heading{background-image:none;}
	 #reg .panel{width: 80%; margin: 5% auto;max-width: 450px;}
	 #reg .panel-body{min-height: 230px;}
	 #reg .panel-heading{background-color: transparent;}

	 .btn-oauth{text-align: left; padding-left: 130px; font-size: 24px; line-height: 50px; position: relative; text-shadow: 0 1px 0 rgba(0,0,0,0.5); color: #333; background-color: #FCFCFC; border: 1px solid #DEDEDE;}
	 .btn-oauth:hover{color: #333; background-color: #E8E8E8; border-color: #ccc; -moz-box-shadow: 0 2px 1px rgba(0,0,0,0.1); -webkit-box-shadow: 0 2px 1px rgba(0, 0, 0, 0.1); box-shadow: 0 2px 1px rgba(0, 0, 0, 0.1);}
	 .btn-oauth .icon{font-size: 40px; position: absolute; left: 75px; text-shadow:none;}
	 .btn-oauth .icon:before{display: none;}

	 a .icon-qq, a .icon-sina, a .icon-wechat, a .icon-yangcong{ margin-bottom: -15px; display: inline-block; width: 48px; height: 48px; margin-right: 10px; background: url('/public/pc/images/socialicons.png') left top no-repeat;}
	 a .icon-sina{background-position: 0 -48px;}
	 a .icon-wechat{background-position: 0 -96px;}
	 a .icon-yangcong{background-position: 0 -144px;}

	 @media (max-width: 767px) {#reg.panel {margin: 20px auto;width: 100%;} #reg .panel-heading {padding: 0 0 10px 0;} #reg .panel-body {padding: 10px 0; min-height: inherit;} .btn-oauth {padding-left: 80px;} .btn-oauth .icon {left: 20px;}}
	 @media (min-width: 768px){#header{min-height:110px;}#navbarCollapse{left:17px;right:auto;}.nav .open>a .caret,.nav .open>a:hover .caret,.nav .open>a:focus .caret{border-top-color:#fff;border-bottom-color:#fff;}#navbarCollapse:before,#navbarCollapse:after{content:' ';position:absolute;display:block;width:9999em;background:#c8000b;top:0;bottom:0;left:-100%;}#headNav nav > a:hover,#headNav nav > span:hover{color:#c8000b !important;}#header.compatible + #navbar .collapse{position:absolute;right:0;top:0px;}#navbarCollapse:after{left:auto;right:100%;}#navbar{min-height:34px;}#navbar .nav > li > a{padding:10px 20px;color:#fff;}#navbar{font-size:16px;background-color:#f39700;}.nav .caret{border-top-color:#fff;border-bottom-color:#fff;}.navbar-nav{left:-0.8%;}#navbar .nav >li.active>a,#navbar .nav >li.active>a:hover,#navbar .nav >li.active>a:focus{background-color:#aa0707;color:#fff;background-image:none;}#navbar .nav > li > a:hover,#navbar .nav > li > a:focus,#navbar .dropdown-submenu:focus > a,#navbar .dropdown-submenu:hover > a,#navbar .dropdown-menu > li > a:focus,#navbar .dropdown-menu > li > a:hover{color:#fff;background:#b91818;}#navbar .navbar-nav{z-index:1;position:relative;}#header .wrapper{padding:0;}.m-index-index #header .wrapper{padding:0px;}#headNav{text-align:right;border-bottom:0px solid #e5e5e5;height:38px;}#headNav nav{margin-right:0px !important;right:0.5%;position:relative;}#headNav a{color:#595757;}#header.compatible #headNav{background-color:#f2f2f2;}#header.compatible #siteSlogan{top:-45px;}#headNav nav > a,#headNav nav > span{float:left;display:block;height:38px;line-height:38px;}.form-control:focus{border-color:#b3b3b3;}#header.compatible #searchbar{right:0.9%;}#searchbar .input-group{float:right;padding:4px 0 4px 5px;margin-top:58px;}#searchbar .btn{background:#c8000b;color:#fff;}#searchbar .btn:hover{color:#fff;}#header.compatible #searchbar .input-group{max-width:260px;float:right;padding:5px 0 5px 5px;}.m-index #searchbar .input-group{padding-right:0px !important;}.text-danger{color:#c8000b;}.text-danger:hover{color:#9c020b;}}

	  {/literal}
	</style>  
</head>
<body>	
<div class='page-container'>
   <!--{include file='topbar.tpl'} -->
   
    <div class='page-wrapper'>
      <div class='page-content'>
        <div class='blocks' data-region='all-banner'></div>
 	    <script src='/public/pc/js/fingerprint.js' type='text/javascript'></script>
		<div class='panel panel-body' id='reg'>
    <div class='row'>
        <div class='col-md-6'>
      <div class='panel panel-pure'>
        <div class='panel-heading'><strong>开放登录，快捷方便</strong></div>
        <div class='panel-body'>
          <a href='/user-oauthLogin-wechat-fingerprintval.html' class='btn btn-default btn-oauth btn-lg btn-block btn-wechat'><i class='icon-wechat icon'></i> 微信</a>
                </div>
      </div>
    </div>
    <div class='col-md-6'>
        <div class='panel panel-pure'>
          <div class='panel-heading'><strong>欢迎注册成为会员</strong></h4></div>
          <div class='panel-body'>
            <form method='post' id='ajaxForm' class='form-horizontal' role='form' data-checkfingerprint='1'>
			 <input type="hidden" id="type" name="type" value="submit" />
              <div class='form-group'>
                <label class='col-sm-3 control-label'>用户名</label>
                <div class='col-sm-9'><input type='text' name='account' id='account' value='' class='form-control form-control' autocomplete='off' placeholder='必须是三位以上的英文字母或数字' />
  </div>
              </div>
              <div class='form-group'>
                <label class="col-sm-3 control-label">真实姓名</label>
                <div class='col-sm-9'><input type='text' name='realname' id='realname' value='' class='form-control' />
  </div>
              </div>
              <div class='form-group'>
                <label class="col-sm-3 control-label">邮箱</label>
                <div class='col-sm-9'><input type='text' name='email' id='email' value='' class='form-control' autocomplete='off' />
  </div>
              </div>
              <div class='form-group'>
                <label class="col-sm-3 control-label">密码</label>
                <div class='col-sm-9'><input type='password' name='password1' id='password1' value='' class='form-control' autocomplate='off' placeholder='数字和字母组成，六位以上' />
  </div>
              </div>
              <div class='form-group'>
                <label class="col-sm-3 control-label">请重复密码</label>
                <div class='col-sm-9'><input type='password' name='password2' id='password2' value='' class='form-control' />
  </div>
              </div>
              <div class='form-group'>
                <label class="col-sm-3 control-label">公司/组织</label>
                <div class='col-sm-9'><input type='text' name='company' id='company' value='' class='form-control' />
  </div>
              </div>
              <div class='form-group'>
                <label class="col-sm-3 control-label">电话</label>
                <div class='col-sm-9'><input type='text' name='phone' id='phone' value='' class='form-control' />
  </div>
              </div>
              <div class='form-group'>
                <div class="col-sm-3"></div>
                <div class='col-sm-9'> <input type='submit' id='submit' class='btn btn-primary btn-block' value='注册'  data-loading='稍候...' /> 
					 
  </div>
              </div>
            </form>
          </div>
        </div>    
      </div>
    </div>
  </div>
    <div class='blocks all-bottom' data-region='all-bottom'></div>
    </div></div>    
</div>   

<script>
{literal}
v.lang = {"confirmDelete":"\u60a8\u786e\u5b9a\u8981\u6267\u884c\u5220\u9664\u64cd\u4f5c\u5417\uff1f","deleteing":"\u5220\u9664\u4e2d","doing":"\u5904\u7406\u4e2d","loading":"\u52a0\u8f7d\u4e2d","updating":"\u66f4\u65b0\u4e2d...","timeout":"\u7f51\u7edc\u8d85\u65f6,\u8bf7\u91cd\u8bd5","errorThrown":"<h4>\u6267\u884c\u51fa\u9519\uff1a<\/h4>","continueShopping":"\u7ee7\u7eed\u8d2d\u7269","required":"\u5fc5\u586b","back":"\u8fd4\u56de","continue":"\u7ee7\u7eed"};;
$().ready(function()
{
    $('a.btn-oauth').each(function()
    {
        fingerprint = getFingerprint();
        $(this).attr('href', $(this).attr('href').replace('fingerprintval', fingerprint) )
    })
});
{/literal}
</script>
{include file='footer.tpl'} 

</body>
</html>