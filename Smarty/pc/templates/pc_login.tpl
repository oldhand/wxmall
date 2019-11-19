<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{$supplier_info.suppliername}-登录</title>
        <link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
        <link href="/public/pc/css/public.css" rel="stylesheet">
        <link href="/public/pc/css/login.css" rel="stylesheet">  
	    <link href="public/css/iconfont.css" rel="stylesheet" />
    </head>
    <body>
        <div id="warp" class="loginwarp">
            <!--头部内容 开始-->
            <div class="head clearfix">
                <div class="head-top border-t0">
                    <div class="container">
                        <!--logo 开始-->
                        <div class="logo pull-left">
                            <div class="logo_l pull-left">
                                <a href="index.html"><img src="/public/pc/images/logo.png" width="200" height="100"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cont">
                <div class="container">
                    <!--登录 开始--> 
                        <input type="hidden" name="post_token" value='{$post_token}'> 
                        <input type="hidden" name="type" value='login'>
                        <div class="loginbox">
                            <div class="login-form-box clearfix">
                                <div class="pull-left"><img class='login-img' src="/public/pc/images/login-img.png"> </div>
                                <div class="pull-left login-form" style="margin-left:200px;padding-bottom:35px;">
                                    <div class="m-lr10">
                                        <h3 class="font-14 m-b30" style='text-align:center;'>
                                            <strong style='font-size:18px;'>欢迎登录</strong>
                                            {if $safeinfo eq "true"}
                                                <font color='red'><br><br>(为了您的帐户安全，请重新登录)</font>
                                            {/if}
                                            {if $login_info neq "" }
                                                <font color='red'><br><br>&nbsp;&nbsp;&nbsp;{$login_info}</font>
                                            {/if}
                                        </h3>
                                        <div class="field m-b20">
                                            <input type="text" id='tel' class="text w280 p-l30 gray-c" name="name"  placeholder="已验证手机" maxlength="11">
                                            <span class="iconfont icon-usercenter" style="display:inline-block;margin-top:3px;color:#ccc;font-size: 1.1em;"></span>
                                            
                                        </div>
                                        <div class="field m-b10">
                                            <input type="text" id="pwd" name="password" class="text w280 p-l30 gray-c" placeholder="密码" maxlength="20">
                                            <span class="iconfont icon-mima" style="display:inline-block;margin-top:3px;color:#ccc;font-size: 1.1em;"></span>
									    </div>
                                        <div class="safe clearfix mb-10">
                                            <div class="clearfix">
                                                <a href="pc_register.html" class="pull-right" style='margin-top:2px;'>免费注册</a>
                                                <a href="javascript:void(0);" class="pull-right" style='margin-top:2px;'>&nbsp;&nbsp;|&nbsp;&nbsp;</a>
                                                <a href="pc_forgetpassword.html" class="pull-right" style='margin-top:2px;'>忘记密码</a>
                                                <div class="niceform chklist" style='margin-bottom:10px'>
                                                    <input type="checkbox" name="autologin" />
                                                    <label><em class="fn">下次自动登录</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-b20">
                                            <button type="button" onClick="loginSystem()" class="btn btn-danger btn-sm btn-block h45">立即登录</button>
                                        </div> 
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                   
                </div>
            </div>
            <!--link 链接--> 
            {include file="footer.tpl"}
        </div>
    </body>
    <script src="/public/pc/js/jquery-1.11.3.min.js"></script>
    <script src="/public/pc/js/index.js"></script>
    <script language="javascript" type="text/javascript" src="/public/pc/js/niceforms.js"></script>
    <script type="text/javascript" src="/public/pc/js/popover.js"></script>
    <script type="text/javascript" language=JavaScript charset="UTF-8">
     {literal}
	  document.onkeydown=function(event){
            var e = event || window.event || arguments.callee.caller.arguments[0];
            if(e && e.keyCode==27){ // 按 Esc 
                //要做的事情
              }
            if(e && e.keyCode==113){ // 按 F2 
                 //要做的事情
               }            
             if(e && e.keyCode==13){ // enter 键
                loginSystem(); //要做的事情
            }
        }; {/literal}
</script>
    <script language="JavaScript">
        {literal}
        var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
        function loginSystem(){
            if($('#tel').val().length<=0){
              $('#tel').pointOut({'msgText':'手机号不能为空'});
              return false;
            }
            if(!reg.test($('#tel').val())){
              $('#tel').pointOut({'msgText':'请输入正确的手机号'});
              return false;
            }
            if($('#pwd').val().length<6){
              $('#pwd').pointOut({'msgText':'密码不能少于6位'});
              return false;
            }
			
			$.ajax({
				url: "pc_login.php",
				async: false,//改为同步方式
				type: "POST",
				data: 'name='+$('#tel').val()+'&password=' + $('#pwd').val()+ '&type=login',
				success: function (data) { 
					if(data==1){
						window.location.href="index.html";
					}else{
						 $('#tel').pointOut({'msgText':'账号或密码错误'});
					} 
				}
			});/**/ 
        }

        function jh(){
            location = "login-step1.html?stype=1";
        }

        $(function(){
            oSet(1);
            $('.chklist').hcheckbox();
        });

        $('#pwd').bind('input propertychange',function(e){
            $(this).attr('type','password');
            var len = this.value.length;
            this.setSelectionRange(len,len);
        });
        {/literal}
    </script>
</html>
