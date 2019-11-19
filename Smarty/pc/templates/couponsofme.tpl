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
                <li><a href="coupons.php">卡券优惠</a>|</li>
                <li><a href="couponsofme.php" class="active">我的卡券使用记录</a></li>
              </ul>
            </div>

            <div class="complaintbox border-t1">
              <div class="complaintbox-t favorable">
                <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui jl">
                  <input id="page"  type="hidden" value="1"> 
                  <tbody id="list">

                  </tbody>
                </table>
                <div class='clearfix' style="text-align: center;border-top: 1px solid #e6e6e6;">
                    <div class="btn btn-default" style="padding: 10px 50px;margin: 20px 0;" id="addMore"><span class="iconfont icon-loading rolling"></span><span class="btntext">加载更多</span></div>
                </div>
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
			url: "coupons.ajax.php",
	        data: 'page=' + page,
			success: function callbackFun(data) {
				//解析json 
				var info = eval("(" + data + ")");
				$('.result_num').html(info.data.length);
				if (info.code == 200) {
					$(".rolling").hide();
					$(".btntext").html('加载更多');
					//清空数据
					if (info.data.length == 0 && page == 1) {
						$('#list').html(usage_empty_html());
					} else {
						$.each(info.data, function(i, value) {
							var nd = product_html(value);
							$("#list").append(nd);
						})
					}
				} else {
					$(".rolling").hide();
					$(".btntext").html('没有更多了');
					$('#list').html(usage_empty_html());
					setTimeout(function() {
						$(".btntext").html('加载更多');
					}, 1500)
				}
			}
		});
	}

	function product_html(v) {
		var sb = new StringBuilder();
		sb.append("<tr>");
		sb.append("                      <td align=\"center\"><a href=\"#\">"+v.published+"</a></td>");
		sb.append("                      <td>"+v.order_no+"</td>");
		sb.append("                      <td><span class=\"arial\">"+v.vipcardname+"<span></td>");
		sb.append("                      <td><p>金额：¥"+v.sumorderstotal+"</p></td>");
		sb.append("                      <td width=\"14%\">");
		sb.append("                        <p>优惠：¥"+v.discount+"</p></td>");
		sb.append("                    </tr>");
		return sb.toString();
	}

	function usage_empty_html() {
		var sb = new StringBuilder();

		sb.append("<div style=\"margin: 20px 0;\">");
		sb.append("<p class=\"msgbody\" style=\"font-size: 30px;text-align: center;margin-top: 20px;\"><span class=\"iconfont icon-tishi\" style=\"color: #fe4401;font-size: 1em;\"></span>您的卡券使用还是空的，先去选购商品吧<br> ");
		sb.append("</p>  ");
		sb.append('<p><a href="index.php"><input type="button" value="进店逛逛" class="btn btn-danger btn-sm btn120"></a></p>');
		sb.append("</div>");
		return sb.toString();
	}

	var $div_li = $(".person-dj-tit li");
	$('#tabbox>div').eq(0).show();
	$div_li.click(function() {
		$(this).find('a').addClass("red")
			.end().siblings().find('a').removeClass("red");
		var index = $div_li.index(this);
		$('#tabbox>div')
			.eq(index).show()
			.siblings().hide();
		return false;
	});

{/literal}
</script>
</html>
