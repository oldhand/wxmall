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
                <div class="tph_rich">
                <h1 style="text-align: center">{$article_info.articletitle}</h1>
                <img src="{$article_info.image}">
                
                <div class="con">{$article_info.articletext}</div>
                </div>
                </div>
                <div style="border-top: 1px solid #e8e8e8;">
		            </div>
</div>
</div>
{include file='footer.tpl'} 
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
</body>
</html>
 