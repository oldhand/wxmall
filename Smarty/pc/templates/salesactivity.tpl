<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-促销活动</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<script src="public/pc/js/common.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="public/js/jweixin.js"></script> 
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
   {literal}
   .mui-media-object{
    width: 100%;
   }
   {/literal}
</style>
</head>
<body>
{include file='rightbar.tpl'}
<div id="warp">
  {include file='header.tpl'}
  <div class="line3"></div></div>
  <!--head 头部-->
  <div class="cont">
    <div class="container">
      <!--choicebox 筛选-->
      <div class="blank20"></div>
      <div class="productbox">
        <div class="product-tit black border">
          <div class="pull-right product-tit-right"> <!-- <span class="pull-left">共 <em class="red"></em> 个商品</span> -->
          </div>
          <strong class="m-l10" class="sort" data-sort="asc" data-order="published" style="cursor: pointer;">默认排序</strong>
          <ul class="cotorl">
            <li><a href="javascript:;" class="sort" data-sort="asc" data-order="salevalue">销量 <span class="iconfont icon-paixu"></span></a></li>
            <li><a href="javascript:;" class="sort" data-sort="asc" data-order="price">价格 <span class="iconfont icon-paixu"></span></a></li>
            <li><a href="javascript:;" class="sort" data-sort="desc" data-order="praise">人气 <span class="iconfont icon-paixu"></span></a></li>
          </ul>
        </div>
        <div class="blank20"></div>
        <div class="product-body Recent-body clearfix">
          <input id="page"  type="hidden" value="1">
		  <input id="salesactivityid"  type="hidden" value="{$salesactivityinfo.id}"> 
		  <input id="sort"  type="hidden" value="asc">
		  <input id="order"  type="hidden" value="published">
          <ul id="list" style="min-height: 242px;">
          </ul>
        </div>
      </div>
      <div class='clearfix' style="text-align: center;border-top: 1px solid #e6e6e6;">
            <div class="btn btn-default" style="padding: 10px 50px;margin: 20px 0;" id="addMore"><span class="iconfont icon-loading rolling"></span><span class="btntext">加载更多</span></div>
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
<script src="/public/pc/js/detail.js"></script>
<script type="text/javascript">
{literal}
$(document).ready(function() { //jquery代码随着document加载完毕而加载 
  $('#page').val(1);
  ajaxForPages();
  //右侧导航
  rightBar('.btn-group-right ul', '.group-open');
  //加载更多
  $("#addMore").click(function() {
    $(".btntext").html('');
    $(".rolling").show();
    ajaxForPages();
  });
  $('.sort').each(function(){
  	$(this).click(function(){
  		var order =  $(this).attr('data-order'); 
		var sort =  $(this).attr('data-sort');
		if (order == "price") 
			{
				if (sort == "asc")
				{
					$(this).attr("data-sort","desc");
				}
				else
				{
					$(this).attr("data-sort","asc");
				}
			}
			else if (order == "salevalue") 
			{
				if (sort == "asc")
				{
					$(this).attr("data-sort","desc");
				}
				else
				{
					$(this).attr("data-sort","asc");
				} 
			}
			else if (order == "praise") 
			{
				if (sort == "asc")
				{
					$(this).attr("data-sort","desc");
				}
				else
				{
					$(this).attr("data-sort","asc");
				}
			}
			$("#sort").val(sort);
			$("#order").val(order);
            $('#page').val(1);
            $('#list').html('');
            ajaxForPages();
  	})
  });
});
//分页查询 
  function ajaxForPages() {
    var page = $('#page').val();
	var salesactivityid = $('#salesactivityid').val();
	var sort = $('#sort').val();
	var order = $('#order').val(); 
    $('#page').val(parseInt(page) + 1);
    $.ajax({
      type: 'POST',
      url: "salesactivity.ajax.php",
	  data: 'page='+page+'&salesactivityid='+salesactivityid+'&order='+order+'&sort='+sort,
      success: function callbackFun(data) {
        //解析json 
        var info = eval("(" + data + ")");
        $('.result_num').html(info.data.length);
        if (info.code == 200) {
          $(".rolling").hide();
          $(".btntext").html('加载更多');
          //清空数据
          $.each(info.data,function(i,value) { 
            var nd;
            if (value.activitymode == '1')
			 {
			 	 //nd = bargain_product_html(v);
			 } 
			 else
			 {
			 	 nd = product_html(value);
			 }
            $("#list").append(nd);
          }) 
          $("img.lazy").lazyload();
          $('.getcar').each(function(){
            $(this).click(function(){
              var record = $(this).attr('data-id');
			  var salesactivityid =  $(this).attr('salesactivity-id'); 
			  var salesactivitys_product_id =  $(this).attr('salesactivitys_product-id');  
			  add_shoppingcart(record,salesactivityid,salesactivitys_product_id);
            })
          });
        }else{
          $(".rolling").hide();
          $(".btntext").html('没有更多了');
          setTimeout(function(){$(".btntext").html('加载更多');},1500)
        }
      }
    });
  }

  function product_html(v) { 
          var sb=new StringBuilder();
           sb.append('<li><div class="imgbox"><a href="detail.php?from=index&productid='+v.productid+'"><img id="product_item_'+v.productid+'" class="mui-media-object lazy" src="/public/pc/images/load.gif" data-original="'+v.productlogo+'"></a>');
                    sb.append('<p class="tph_productname"><a href="detail.php?from=index&productid='+v.productid+'" class="black">'+v.productname+'</a></p></div>');
                     sb.append('<p class="price red arial"><span class="price-icon"></span><em class="f24">¥'+v.promotional_price+'</em><del>¥'+v.shop_price+'</del><a href="javascript:;" class="getcar pull-right" salesactivitys_product-id="'+v.salesactivitys_product_id+'" salesactivity-id="'+v.salesactivityid+'" data-id="'+v.productid+'" id="addshoppingcart'+v.productid+'"><i class="iconfont icon-jiarugouwuche"></i></a></p>');
                    
                   sb.append('</li> '); 
          return sb.toString(); 
   }
  function add_shoppingcart(record,salesactivityid,salesactivitys_product_id) 
  {  
        $.ajax({
            type: 'POST',
            url: "shoppingcart_add.ajax.php",
            data: 'record=' + record +"&salesactivityid="+salesactivityid+"&salesactivitys_product_id="+salesactivitys_product_id,
            success: function(json) {   
                var jsondata = eval("("+json+")");
                if (jsondata.code == 200) {   
                  flyItem("addshoppingcart"+record); 
                  $('#shoppingcart_header').html(jsondata.shoppingcart);  
                  $('#shoppingcart_rightbar').html(jsondata.shoppingcart); 
                } 
                else
                {
                  alert(2);
                }
            }
        }); 
    }  
{/literal}
</script>
{include file='weixin.tpl'}  
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
<script src="/public/pc/js/jquery.lazyload.min.js"></script>
</html>
