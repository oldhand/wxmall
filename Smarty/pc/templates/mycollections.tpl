<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-个人中心(我的收藏)</title>
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
      <div class="break-person clearfix">
        <p><a href="#">首页</a><em>&gt;</em><a href="usercenter.php">个人中心</a><em>&gt;</em><span>我的收藏</span></p>
      </div>
      <!--break-person-->
      <div class="personbox" id="offCanvasWrapper">
        {include file='usercenterL.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right hui">
          <div class="safebox border">
            <div class="person-linetit clearfix">
              <h3 class="f16">我的收藏</h3>
            </div>
            <!--person-linetit-->
            <div class="person-dj-lr collect-lr clearfix border-t1" id="pullrefresh">
                <input id="page"  type="hidden" value="1"> 
                <ul id="list" class="mui-table-view mui-grid-view list">
                </ul>
                
            </div>
            <div class='clearfix' style="text-align: center;border-top: 1px solid #e6e6e6;">
                <div class="btn btn-default" style="padding: 10px 50px;margin: 20px 0;" id="addMore"><span class="iconfont icon-loading rolling"></span><span class="btntext">加载更多</span></div>
            </div>
          </div>
          <!--safe-box-->
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
});
//分页查询 
  function ajaxForPages() {
    var page = $('#page').val(); 
    $('#page').val(parseInt(page) + 1);
    $.ajax({
      type: 'POST',
      url: "mycollections.ajax.php",
      data: 'page='+page,
      success: function callbackFun(data) {
        //解析json 
        var info = eval("(" + data + ")");
        $('.result_num').html(info.data.length);
        if (info.code == 200) {
          $(".rolling").hide();
          $(".btntext").html('加载更多');
          //清空数据
          $.each(info.data,function(i,value) { 
            var nd = product_html(value);
            $("#list").append(nd);
          }) 
          $("img.lazy").lazyload();
          $('.getcar').each(function(){
            $(this).click(function(){
              var record = $(this).attr('data-id');
              add_shoppingcart(record);
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
           sb.append('<li> <a href="detail.php?from=index&productid='+v.productid+'"><img id="product_item_'+v.productid+'" class="mui-media-object lazy" src="/public/pc/images/load.gif" data-original="'+v.productlogo+'"></a>');
                    sb.append(' <p class="tph_productname"><a href="detail.php?from=index&productid='+v.productid+'" class="black">'+v.productname+'</a></p>');
                    sb.append('<p class="price red arial"><span class="price-icon"></span><em class="f24">¥'+v.market_price+'</em><a href="javascript:;" class="getcar pull-right" data-id="'+v.productid+'" id="addshoppingcart'+v.productid+'"><i class="iconfont icon-jiarugouwuche"></i></a><span class="pull-right" style="font-size: 12px;margin:6px 11px 0 0;">'+v.praise+'</span><span class="iconfont icon-tezan pull-right" style="font-size: 1em;"></span></p>');
                    
                   sb.append('</li> '); 
          return sb.toString(); 
   }
  function add_shoppingcart(record) 
  {  
        $.ajax({
            type: 'POST',
            url: "shoppingcart_add.ajax.php",
            data: 'record=' + record,
            success: function(json) {   
                var jsondata = eval("("+json+")");
                if (jsondata.code == 200) {   
                  flyItem("addshoppingcart"+record); 
                  $('#shoppingcart_header').html(jsondata.shoppingcart);  
                  $('#shoppingcart_rightbar').html(jsondata.shoppingcart); 
                } 
                else
                {
                  alert('添加失败！');
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
