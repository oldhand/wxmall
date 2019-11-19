<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{if $appraise_info.praiseid eq ''}评价{else}查看评价{/if}</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/order.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
{literal} 
	#radiogroup{
		margin-top: 16px;
	}
	#radiogroup .active{
		color: #fff;
	 	background-color: #e53348;
	 	border-color: #e53348;
	}
{/literal}
</style>
</head>
<body>
<div id="warp">
  {include file='header.tpl'}
  <!--head 头部-->
  <div class="blank20"></div>
  <div class="cont">
    <div class="container">
      <div class="order-index">
        <div class="order-tit clearfix">
          <h3 class="pull-left">{if $appraise_info.praiseid eq ''}评价{else}查看评价{/if}</h3>
        </div>
        <div class="row-item-tit clearfix border fill-tit">
          <p>商品信息</p>
        </div>
        <form  method="post" name="frm" action="appraise_product_submit.php" >
		 <input name="type"  value="submit" type="hidden" > 
		 <input id="orderid" name="orderid"  value="{$appraise_info.orderid}" type="hidden" > 
		 <input id="productid" name="productid"  value="{$appraise_info.productid}" type="hidden" > 
		 <input id="orders_productid" name="record"  value="{$appraise_info.id}" type="hidden" >
		 <input id="praiseid" name="praiseid"  value="{$appraise_info.praiseid}" type="hidden" > 
		 <input id="localids" name="localids"  value="" type="hidden" > 
		 <input id="imagesserverids" name="imagesserverids"  value="" type="hidden" > 
        <!--row-item-tit-->
        <div class="fill-first border">
          <div class="fill-first-tit">
            <img class="mui-media-object mui-pull-left" src="{$appraise_info.productthumbnail}" height="100px">

            <h4 class="f16">{$appraise_info.productname}</h4>
            <h4 class="f16">单价：¥{$appraise_info.shop_price}</h4>
          </div>
        </div>
        <!--fill-first 填写订单 第一次登录时-->
        <div class="blank20"></div>
        <div class="row-item-tit clearfix border fill-tit">
          <p>评价</p>
        </div>
      <div class="bz border">
        <div class="bzlist">
          <h6>- 填写评价</h6>
          <div class="bz-box  border">
          {if $appraise_info.praiseid eq ''}
            <textarea  style="margin-bottom: 0px;padding: 5px;font-size: 15px;" class="textarea border text gray-c" placeholder="亲，写点什么吧，您的意见对其他朋友有很大帮助！" id="remark" name="remark" rows="2" ></textarea>
            <div id="radiogroup">
            <a class="btn btn-default active radiogroup" data-id="1" href="javascript:;" >
				<span class="mui-icon iconfont icon-pingjiamanyi" style="font-size: 1.5em;">好评</span>
                <div style="display:none">
                    <input class="praise" checked id="praise_1" type="radio" name="praise" value="1" />
                </div>
            </a> 
            <a class="btn btn-default radiogroup" data-id="2" href="javascript:;" >
				<span class="mui-icon iconfont icon-pingjiayiban" style="font-size: 1.5em;">中评</span>
                <div style="display:none">
                    <input class="praise" id="praise_2" type="radio" name="praise" value="2" />
                </div>
            </a>
            <a class="btn btn-default radiogroup" data-id="3" href="javascript:;" >
				<span class="mui-icon iconfont icon-pingjiabumanyi " style="font-size: 1.5em;">差评</span>
                <div style="display:none">
                    <input class="praise" id="praise_3" type="radio" name="praise" value="3" />
                </div>
            </a>
            </div>
          {else}
          	<textarea  style="margin-bottom: 0px;padding: 5px;font-size: 15px;" class="textarea border text gray-c" disabled id="remark" name="remark" rows="2" >{$appraise_info.remark}</textarea>
            <div id="radiogroup">
            <a class="btn btn-default {if $appraise_info.praise eq '1'}active{/if} radiogroup" data-id="1" href="javascript:;" >
				<span class="mui-icon iconfont icon-pingjiamanyi" style="font-size: 1.5em;">好评</span>
                <div style="display:none">
                    <input class="praise" {if $appraise_info.praise eq '1'}checked{/if} id="praise_1" type="radio" name="praise" value="1" />
                </div>
            </a> 
            <a class="btn btn-default {if $appraise_info.praise eq '2'}active{/if} radiogroup" data-id="2" href="javascript:;" >
				<span class="mui-icon iconfont icon-pingjiayiban" style="font-size: 1.5em;">中评</span>
                <div style="display:none">
                    <input class="praise" {if $appraise_info.praise eq '2'}checked{/if} id="praise_2" type="radio" name="praise" value="2" />
                </div>
            </a>
            <a class="btn btn-default {if $appraise_info.praise eq '3'}active{/if} radiogroup" data-id="3" href="javascript:;" >
				<span class="mui-icon iconfont icon-pingjiabumanyi " style="font-size: 1.5em;">差评</span>
                <div style="display:none">
                    <input class="praise" {if $appraise_info.praise eq '3'}checked{/if} id="praise_3" type="radio" name="praise" value="3" />
                </div>
            </a>
            </div>
          {/if}
          </div>
        </div>
        {if $appraise_info.praiseid eq ''}
        <input type="button" style="margin-left: 920px;" value="提交评价" class="f14 btn btn-danger btn-lg btn160 confirmpayment savethis">
		{/if}
      </div>
      </form>
      </div>
    </div>
    <!--order-index-->
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
$(function() {
	$('#radiogroup a').each(function() {
	$(this).click(function(){
		var praise =  $(this).attr('data-id');
		$(".radiogroup").removeClass("active"); 
		$(this).addClass("active"); 
		
		$(".praise").attr("checked",false); 
		$(".praise").prop("checked",false); 
		$("#praise_"+praise).attr("checked",true);  
		$("#praise_"+praise).prop("checked",true); 
	}) 
	}); 
	$('.savethis').click(function() {
		document.frm.submit();
	});
});
{/literal}
</script>
</html>
