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
<script src="public/pc/js/common.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="public/js/jweixin.js"></script>
<!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
<![endif]-->
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
          <div class="person-data border">
            <div class="person-post-tit person-linetit">
              <h3 class="f16 pull-left">个人资料</h3>
              <!-- <ul class="pull-left">
                <li><a href="person-data.html" class="active">基本信息</a>|</li>
                <li><a href="person-data-head.html">头像照片</a></li>
              </ul> -->
            </div>
            <!--person-post-tit-->
            <div class="person-data-body clearfix border-t1 hui">
              <div class="findKey-form-box">
                <ul class="findKey-form clearfix" style="margin:41px 0 40px 100px; width:700px;">
                  <li><span>昵称：</span>
                    <div>
                       <p>
                        <input type="text" class="text w400 gray-c" value="{$profile_info.givenname}" disabled>
                      </p>
                    </div>
                  </li>
                  <li><span>等级：<em class="red mr-10">{include file='profilerank.tpl'}</em></span>
                  </li>
                  {if $profile_info.physicalstorename neq ''}
                  <li><span>店铺：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" value="{$profile_info.physicalstorename}" disabled> 
                      </p>
                    </div>
                  </li>
                  <li><span>店员：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" value="{$profile_info.assistantprofile}" disabled> 
                      </p>
                    </div>
                  </li>
					{else}
	                    {if $profile_info.onelevelsourcer neq ''}
	                    	<li><span>您的推荐人：</span>
			                    <div>
			                      <p>
			                        <input type="text" class="text w400 gray-c" value="{$profile_info.sourcergivename}" disabled> 
			                      </p>
			                    </div>
			                  </li>
	                    {/if}
                    {/if}
                  <li><span>手机号：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" value="{$profile_info.mobile}">
                        <!-- <input type="button" class="btn btn-gray f12 btn90" value="修改"> -->
                      </p>
                    </div>
                  </li>
                  <!-- <li><span>地区：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" value="138******123">
                        <input type="button" class="btn btn-gray f12 btn90" value="获取验证码">
                      </p>
                    </div>
                  </li> -->
                  <li><span>地区：</span>
                    <div>
                      <p>
                        <input type="text" class="text w400 gray-c" value="{$profile_info.province} {$profile_info.city}" disabled>
                      </p>
                    </div>
                  </li>
                  <li> <span>性别：</span>
                    <div>
                      <input type="text" class="text w400 gray-c" value="{$profile_info.gender}" disabled>
                    </div>
                  </li>
                  <!-- <li> <span>所在地：</span>
                    <div class="choice-adress">
                      <em class="mr-10">
                <div class="select_box"><div class="select_showbox" style="cursor: pointer;">请选择省</div><ul class="select_option"><li class="selected" style="cursor: pointer;">请选择省</li><li style="cursor: pointer;">Saab</li><li style="cursor: pointer;">Opel</li><li style="cursor: pointer;">Opel</li></ul></div><select class="select border gray-c">
                  <option value="1">请选择省</option>
                  <option value="2">Saab</option>
                  <option value="3">Opel</option>
                  <option value="3">Opel</option>
                </select>
                </em><em>
                <div class="select_box"><div class="select_showbox" style="cursor: pointer;">请选择市</div><ul class="select_option"><li class="selected" style="cursor: pointer;">请选择市</li><li style="cursor: pointer;">Saab</li><li style="cursor: pointer;">Opel</li></ul></div><select class="select border gray-c">
                  <option value="1">请选择市</option>
                  <option value="2">Saab</option>
                  <option value="3">Opel</option>
                </select>
                </em>
                    </div>
                  </li> -->
                  <li class="breath"><span>生日：</span>
                    <div>
                      <p>
                        <input type="text" id="calOne" class="text w400 gray-c" value="{$profile_info.birthdate}" disabled>
                      </p>
                    </div>
                  </li>
                  
                  <!-- <li> <span>&nbsp;</span>
                    <div>
                      <button type="button" class="btn btn-danger btn120">返回</button> </div>
                  </li> -->
                </ul>
              </div>
            </div>
            <!--person-data-body-->
          </div>
          <!--data 个人资料-->
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
      url: "orders.ajax.php",
      data: 'type=pendingpayment&page=' + page,
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
          $('.deleteorder').each(function(){
            $(this).click(function(){
              var orderid = $(this).attr('data-id');
              if(confirm('确定删除订单吗？')){
                window.location.href = 'orders_pendingpayment.php?type=delete&record=' + orderid;
              }
            })
          })
        }else{
          $(".rolling").hide();
          $(".btntext").html('没有更多了');
          setTimeout(function(){$(".btntext").html('加载更多');},1500)
        }
      }
    });
  }

  function product_html(v) { 
    var sb = new StringBuilder();
    sb.append('<li>');
    sb.append('<div class="dj-lr-list ">');
    sb.append('<div class="dj-lr-t bg-f5 black">');
    sb.append(' <p><span>订单号：' + v.mall_orders_no + '</span></p>');
    sb.append('</div>');
    sb.append('<table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">');
    sb.append('<tbody>');
    sb.append('<tr height="140">');
    sb.append('<td width="2%"></td>');
    sb.append('<td width="13%" align="left"><a href="detail.php?from=index&productid='+v.productid+'" target="_blank"><img src="' + v.thumbnail + '" width="100" height="100"></a></td>');
    sb.append('<td width="28%" align="left"><p><a href="detail.php?from=index&productid='+v.productid+'" target="_blank">' + v.ordername + '</a></p></td>');
    sb.append('<td width="13%"><p>欧阳MOMO</p></td>');
    sb.append('<td width="11%"><span class="arial">' + v.productcount + '</span></td>');
    sb.append('<td width="11%"><span class="arial">￥' + v.sumorderstotal + '</span></td>');
    sb.append('<td width="11%"><span class="red">' + v.order_status + '</span></td>');
    sb.append('<td width="11%"><p class="mb-5"><a href="submitorder.php" class="btn btn-danger btn-sm btn-p10">去付款</a></p>');
    sb.append('<p class="text-center"><a class="deleteorder" data-id="' + v.orderid + '" href="javascript:;">取消订单</a></p></td>');
    sb.append('</tr>');
    sb.append(' </tbody>');
    sb.append('</table>');
    sb.append('</div>');
    sb.append(' </li>');
    return sb.toString();
  }

  $('#tabbox>div').eq(0).show();
 {/literal}
</script>
</html>
