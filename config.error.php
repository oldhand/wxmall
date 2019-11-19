<?php

	if (!function_exists('errorprint'))
	{
		function errorprint($title, $msg)
		{
			header('Content-Type:text/html;charset=utf-8');
			$html = '
<html>
<head> 
<title>'.$title.'</title>
<meta http-equiv=content-type content="text/html; charset=utf8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<style type=text/css>
body { font-size: 12px; font-family: tahoma }
td { font-size: 12px; font-family: tahoma }
a:link { color: #636363; text-decoration: none }
a:visited { color: #838383; text-decoration: none }
a:hover { color: #a3a3a3; text-decoration: underline }
body { background-color: #cccccc }
.maintable {
	overflow:hidden;
	border:1px solid #d3d3d3;
	background:#fefefe;
	width:100%;
	margin:5% auto 0;
	-moz-border-radius:15px; /* FF1+ */
	-webkit-border-radius:15px; /* Saf3-4 */
	border-radius:15px;
	-moz-box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
	-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
}
</style>
<script src="public/js/baidu.js?_=20140821" type="text/javascript"></script>
</head>
<body style="table-layout: fixed; word-break: break-all" topmargin="10" marginwidth="10" marginheight="10"> 
<table height="95%" cellspacing=0 cellpadding=0 width="100%" align=center border=0>
  <tbody>
  <tr valign=center align=middle>
    <td>
      <table class="maintable" cellspacing=0 cellpadding=0 width="96%" bgcolor=#ffffff border=0>
        <tbody>
        <tr>
          <td width=20  height=20></td>
          <td width=108 background="/images/rbox/rbox_2.gif"  height=20></td>
          <td width=56><img height=20 src="/images/rbox/rbox_ring.gif" width=56></td>
          <td width=100 background="/images/rbox/rbox_2.gif"></td>
          <td width=56><img height=20 src="/images/rbox/rbox_ring.gif" width=56></td>
          <td width=108 background="/images/rbox/rbox_2.gif"></td>
          <td width=20  height=20></td>
	    </tr>
        <tr>
          <td align=left rowspan=2></td>
          <td align=middle bgcolor=#eeeeee colspan=5 height=80>
            <p><strong>糟糕，'.$msg.'&#8230;&#8230;<br><br></strong></p></td>
          <td align=left rowspan=2></td>
	    </tr>
        <tr>
          <td align=left colspan=5 height=80>
            <p align=center><br><p id=lid2>请选择您要进行的下一步操作：</p>
            <ul>
              <li id=list1>赶紧联系管理员。<br>
              <li id=list2>自己动手转到 <a href="index.php">首页</a> 主页。 
              <li id=list3>单击<a href="javascript:history.back(1)">后退</a>按钮，尝试其他链接。 </li>
		    </ul>
            <div align=right><br>特赞商城</div></td>
		</tr>
        <tr>
          <td align=left height=20></td>
          <td align=left colspan=5 height=20></td>
          <td align=left height=20></td></tr>
	  </tbody></table>
  </td></tr>
</tbody>
</table> 
</body> 
</html>
';
			echo $html;
			die();
		}
	}

	if (!function_exists('messagebox'))
	{
		function messagebox($title, $msg = '', $redirecturl = '', $sleep = 0)
		{
			if ($msg == '')
			{
				$msg   = $title;
				$title = '';
			}
			header('Content-Type:text/html;charset=utf-8');
			echo '<!DOCTYPE html>
		<html> 
			<head>
				<title>'.$title.'</title>
				<meta charset="utf-8">
			    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
			    <title></title>
			    <link href="public/css/mui.css" rel="stylesheet" />
			    <link href="public/css/public.css" rel="stylesheet" />  
				<link href="public/css/iconfont.css" rel="stylesheet" />
			    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
			    <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script> 
			    <script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>
				<script src="public/js/baidu.js?_=20140821" type="text/javascript"></script>  
				<style>
				.tishi
				{
					color:#fe4401; 
					width:100%; 
					text-align:center;
					padding-top:10px;
				}
				.tishi .mui-icon
				{ 
					font-size: 4.4em; 
				}
				.msgbody
				{ 
					width:100%;
					font-size: 1.4em;
					color:#333;
					text-align:center;
					padding-top:10px;
				}
				#time
				{
					font-size: 1.0em;
					color:#333;
				}
				</style>
			</head>

			<body>
			     <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
				 <div class="mui-inner-wrap">
					<header class="mui-bar mui-bar-nav">
					    <a class="'.($redirecturl == "" ? "mui-action-back" : "").' mui-icon mui-icon-left-nav mui-pull-left" '.($redirecturl == "" ? "" : "href='".$redirecturl."'").'></a>
						<h1 class="mui-title">'.$title.'</h1> 
					</header>
			        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: 45px;"> 
			            <div class="mui-scroll">    
								<div class="mui-content-padded">';

								if ($title == '提示')
								{
									echo '<p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>
															  <p class="msgbody">'.$msg.'</p>';
								}
								else if ($title == '错误')
								{
									echo '<p class="tishi"><span class="mui-icon iconfont icon-cuowu"></span></p>
															  <p class="msgbody">'.$msg.'</p>';
								}
								else if ($title == '警告')
								{
									echo '<p class="tishi"><span class="mui-icon iconfont icon-warning "></span></p>
															  <p class="msgbody">'.$msg.'</p>';
								}
								else
								{
									echo '<p style="color:#333;">'.$msg.'</p>';
								}
								if ($redirecturl != '' && $sleep > 0)
								{
									echo '<p style="color:#333;" class="msgbody"><span id="time">'.$sleep.'</span>&nbsp;秒后自动跳转！</p>';
								}

								echo '
			    				</div>
					    </div>
					</div>
				</div> 
				</div> 
				<script type="text/javascript">  
				    mui.init({
				        pullRefresh: {
				            container: "#pullrefresh"
				        },
				    });
					mui.ready(function() { 
						mui("#pullrefresh").scroll(); 
					});';
			if($redirecturl != "")
				echo '
					mui("header.mui-bar").on("tap", "a.mui-icon-left-nav", function (e)
				  	{
					  	mui.openWindow({
										 url: this.getAttribute("href"),
										 id: "info"
									 });
				  	});
					';
				echo '
					function sleeptime(time)
					{ 
						if (time > 0)
						{
							var newtime = time - 1; 
							Zepto("#time").html(newtime);
							setTimeout("sleeptime("+newtime+");",1000);
						}
					} 
					';
			if ($redirecturl != '' && $sleep > 0)
			{
				echo 'setTimeout("sleeptime('.$sleep.');",1000);';
				echo 'setTimeout("window.location.href = \''.$redirecturl.'\';",'.($sleep * 1000).');';
			}
			echo '</script>
			</body> 
		</html>';
			die();
		}
	}

	if (!function_exists('apperrorprint'))
	{
		function apperrorprint($title, $msg)
		{
			header('Content-Type:text/html;charset=utf-8');
			$html = '
<!DOCTYPE html>
<html lang="zh-CN">
 <head>
   <meta charset="utf-8">
   <title>'.$title.'</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <!-- Bootstrap -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="ttwz.css">
   <script src="js/jquery-1.6.2.min.js" type="text/javascript"></script> 
 </head>
 <body style="background:#fff"> 
   <div class="content user tishi" style="padding-top:30%">
     <div class="tubiao"><img src="images/tishi_03.gif" width="12%"></div>
     <div class="tishi_tit">'.$title.'</div>
     <div class="tishi_text">'.$msg.'</div> 
   </div>
   <div class="wz_logo2"><img src="images/wz_logo.jpg" class="img-responsive" width="30%"></div>';
			$html .= '</div>
<script src="js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>';
			echo $html;
			die();
		}
	}

	if (!function_exists('msgprint'))
	{
		function msgprint($title, $msg)
		{
			header('Content-Type:text/html;charset=utf-8');
			$html = '
 <!DOCTYPE html>
 <html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>'.$title.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="ttwz.css">
    <script src="js/jquery-1.6.2.min.js" type="text/javascript"></script> 
  </head>
  <body style="background:#fff"> 
    <div id="ajaxloading" class="container" style="background:#fff;margin-bottom:20px;">
  	<div class="detailed_top navbar navbar-default navbar-fixed-top clearfix" role="navigation">
      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="18%" height="45" align="left"></td>
              <td width="57%" height="45" align="center"><span class="ny">'.$title.'</span></td>
              <td width="25%" height="45" align="center" ></td>
            </tr>
          </table>
      </div>
    <div class="content user tishi" style="padding-top:30%"> 
      <div class="tishi_tit">'.$title.'</div>
      <div class="tishi_text">'.$msg.'</div> 
    </div>
	<div class="wz_logo2"><img src="images/wz_logo.jpg" class="img-responsive" width="30%"></div>';
			if ($_SESSION['u'] == $_SESSION['profileid'])
			{
				$html .= '<nav id="navbar" class="navbar navbar-default navbar-fixed-bottom bottommenu" role="navigation">
  	<div id="sy_bottom">
         <ul>
             <li class="new_xx"><a href="index.php"><img src="images/nav01.png"></a></li> 
             <li class="new_xx"><a href="locallive.php"><img src="images/nav02.gif"></a></li>
             <li class="new_xx"><a href="usercenter.php"><img src="images/nav4.png"></a></li>
             <li class="new_xx"><a href="more.php"><img src="images/nav5.png"></a></li>
             <li class="gw"><a href="shoppingcart.php"><img src="images/gwc01.png"></a><div class="shuzi"></div></li>
         </ul>
 	</div>   
 </nav> ';
			}
			else
			{
				$html .= '<nav id="navbar" class="navbar navbar-default navbar-fixed-bottom bottommenu" role="navigation">
 	<div id="sy_bottom">
        <ul>
	        <li class="new_xx"><a href="index.php"><img src="images/ttwg02.png"></a></li> 
	        <li class="new_xx"><a href="locallive.php"><img src="images/nav02.gif"></a></li>
	        <li class="new_xx"><a href="payment.php"><img src="images/ddwl02.png"></a></li>
	        <li class="new_xx"><a href="more.php"><img src="images/nav5.png"></a></li>
	        <li class="gw"><a href="shoppingcart.php"><img src="images/gwc01.png"></a><div class="shuzi"></div></li>
        </ul>
	</div>   
</nav> ';
			}
			$html .= '</div>
 <script src="js/baidu.js?_=20140821" type="text/javascript"></script>
 </body>
 </html>';
			echo $html;
			die();
		}
	}

?>