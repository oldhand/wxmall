<!DOCTYPE html>
<html lang='zh-cn' class='m-company m-company-index'>
<head>
    <meta charset="utf-8">
    <title>{$supplier_info.suppliername}</title>
    <meta name="keywords" content="{$supplier_info.suppliername}">
    <meta name="description" content="{$supplier_info.suppliername}"> 
    <link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<link href="/public/pc/css/news.css" rel="stylesheet">
<script src="public/pc/js/common.js" type="text/javascript" charset="utf-8"></script>
	{include file='header.tpl'} 
</head>
<body>	
<div class="cont">
<div class="container">
<div class='blocks' data-region='all-banner'></div>
      <ul class="breadcrumb"><li><span class='breadcrumb-title'>当前位置：</span><a href='/' >首页</a>
      </li><li>商城资讯</li></ul>
                  <input id="page"  type="hidden" value="1"> 
                <div class="clearfix seckill_goods">
                <ul class="newslist" id="list">
                </ul>
                </div>
                  
                <div class='clearfix' style="text-align: center;border-top: 1px solid #e6e6e6;">
                    <div class="btn btn-default" style="padding: 10px 50px;margin: 20px 0;" id="addMore"><span class="iconfont icon-loading rolling"></span><span class="btntext">加载更多</span></div>
                </div>
                <div style="border-top: 1px solid #e8e8e8;">
		            </div>
</div>
</div>
{include file='footer.tpl'} 
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
			url: "news.ajax.php",
		    data: 'page=' + page + '&_=' + Math.random(),
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
					//$('#list').html(usage_empty_html());
					setTimeout(function() {
						$(".btntext").html('加载更多');
					}, 1500)
				}
			}
		});
	}
			
	function product_html(v) {
		var sb = new StringBuilder();
		sb.append('<a href="view.php?id='+v.id+'"><li>');
		sb.append('<div class="newscol1"><img src="'+v.image+'" width="100%"></div>');
		sb.append('<div class="newscol2">');
		sb.append('<h1>'+v.articletitle+'</h1>');
		sb.append('<p>'+v.description+'</p>');
		sb.append('</div></li></a>');
		return sb.toString();
	}

	function usage_empty_html() {
		var sb = new StringBuilder();

		sb.append("<div style=\"margin: 300px 0;\">");
		sb.append("<p class=\"msgbody\" style=\"font-size: 30px;text-align: center;margin-top: 20px;\"><span class=\"iconfont icon-tishi\" style=\"color: #fe4401;font-size: 1em;\"></span>目前还没有资讯数据！<br> ");
		sb.append("</p>  ");
		sb.append('<p><a href="index.php"><input type="button" value="回到首页" class="btn btn-danger btn-sm btn120"></a></p>');
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
</body>
</html>
 