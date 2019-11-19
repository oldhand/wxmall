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
<script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" src="public/js/jweixin.js"></script>
  <script src="public/js/zepto.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>
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
       {include file='usercenterl.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right mui-inner-wrap">
          <div class="person-jj bg-f5 border clearfix">
            <div class="person-jj-r pull-right">
              <h4 class="f14">账户安全</h4>
              <div class="person-safe clearfix m"> <span class="red">低</span>
                <ul>
                  <li class="active"></li>
                  <li class="active"></li>
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>
                <span><a href="#">提升</a> > </span> </div>
            </div>
            <div class="person-jj-l pull-left"> <img src="{$profile_info.headimgurl}" width="100" height="100"> </div>
            <div class="person-jj-m pull-left">
              <h4 class="f14 red">{$profile_info.givenname}</h4>
              <p class="clearfix hui">您是微赚普通会员，每日转发金为：0元/天</p>
              <ul class="clearfix list-inline">
                <li><span class="per-tile">总收入</span><span class="arial">￥18900.00</span></li>
                <li><span class="per-tile">销售提成</span><span class="arial">￥3580.00</span></li>
                <li><span class="per-tile">分享收益</span><span class="arial">￥820.00</span></li>
                <li><span class="per-tile">广告收益</span><span class="arial">￥2650.00</span></li>
              </ul>
              <div class="person-right shenqing text-right">
                <p class="clearfix hui mb-5" style="display:none;"><a class=" m-l10 btn btn-danger p0 pull-right btn-sq f12">申请店主</a> 您还不是店主，想成为店主吗？</p>
                <p class=" m-t20"><a href="#" class="btn btn-danger p0 pull-right btn-sj f12">升级</a></p>
              </div>
            </div>
          </div>
          <!--person-js 店主信息-->
          <div class="blank20"></div>
          <div class="person-dj border">
            <div class="person-dj-tit person-dian"> <a href="person-order.html" class="pull-right">查看全部订单 ></a>
              <h3 class="f16">我的订单</h3>
              <ul>
                <li><a href="javascript:;" class="red">待付款<span>({$badges.pendingpayment})</span></a>|</li>
                <li><a href="javascript:;">待发货<span >({$badges.nosend})</span></a>|</li>
                <li><a href="javascript:;">待收货<span >({$badges.receipt})</span></a>|</li>
                <li><a href="javascript:;">待评价<span>({$badges.appraise})</span></a></li>
              </ul>
            </div>
            <div id="tabbox">
              <div class="person-dj-body">
                <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui">
                  <thead>
                    <tr class=" h35 black">
                      <td width="2%"></td>
                      <td width="13%" align="left">商品信息</td>
                      <td width="28%"></td>
                      <td width="13%">收货人</td>
                      <td width="11%">数量</td>
                      <td width="11%">订单金额</td>
                      <td width="11%">订单状态</td>
                      <td width="11%">操作</td>
                    </tr>
                  </thead>
                </table>
                <div class="clearfix" id="pullrefresh"></div>
                <div class="dj-lr border-t1">
                  <ul>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">未付款</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="index-detail.html">取消订单</a></p></td>
                            </tr>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">未付款</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="index-detail.html">取消订单</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">待收货</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="person-logistics.html">查看物流</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">已完成</span></td>
                              <td width="11%"><p class="mb-5"><a href="#">查看</a>|<a href="#"> 删除</a></p>
                                <p class="text-center mb-5"><a href="#">评价</a>|<a href="#"> 晒单</a></p>
                                <p><a href="#">申请售后</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="person-dj-body">
                <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui">
                  <thead>
                    <tr class=" h35 black">
                      <td width="2%"></td>
                      <td width="13%" align="left">商品信息</td>
                      <td width="28%"></td>
                      <td width="13%">收货人</td>
                      <td width="11%">数量</td>
                      <td width="11%">订单金额</td>
                      <td width="11%">订单状态</td>
                      <td width="11%">操作</td>
                    </tr>
                  </thead>
                </table>
                <div class="clearfix"></div>
                <div class="dj-lr border-t1">
                  <ul>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">未付款</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="index-detail.html">取消订单</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">待收货</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="person-logistics.html">查看物流</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">已完成</span></td>
                              <td width="11%"><p class="mb-5"><a href="#">查看</a>|<a href="#"> 删除</a></p>
                                <p class="text-center mb-5"><a href="#">评价</a>|<a href="#"> 晒单</a></p>
                                <p><a href="#">申请售后</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="person-dj-body">
                <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui">
                  <thead>
                    <tr class=" h35 black">
                      <td width="2%"></td>
                      <td width="13%" align="left">商品信息</td>
                      <td width="28%"></td>
                      <td width="13%">收货人</td>
                      <td width="11%">数量</td>
                      <td width="11%">订单金额</td>
                      <td width="11%">订单状态</td>
                      <td width="11%">操作</td>
                    </tr>
                  </thead>
                </table>
                <div class="clearfix"></div>
                <div class="dj-lr border-t1">
                  <ul>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">未付款</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="index-detail.html">取消订单</a></p></td>
                            </tr>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">未付款</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="index-detail.html">取消订单</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div class="dj-lr-list ">
                        <div class="dj-lr-t bg-f5 black">
                          <p><span>订单号：DR2578986</span><span>下单时间：2014-9-26 14：42</span></p>
                        </div>
                        <table cellpadding="0" cellspacing="" border="0" width="100%" class="tousu hui ">
                          <tbody>
                            <tr height="140">
                              <td width="2%"></td>
                              <td width="13%" align="left"><a href="index-detail.html" target="_blank"><img src="/public/pc/images/person/person-img.jpg" width="100" height="100"> </a></td>
                              <td width="28%" align="left"><p><a href="index-detail.html" target="_blank">红人馆 802017#欧美风
                                  长袖双口袋双排扣毛呢
                                  外套女大衣西装秋冬</a></p></td>
                              <td width="13%"><p>欧阳MOMO</p></td>
                              <td width="11%"><span class="arial">1</span></td>
                              <td width="11%"><span class="arial">￥320.00</span></td>
                              <td width="11%"><span class="red">待收货</span></td>
                              <td width="11%"><p class="mb-5"><a href="index-detail.html" class="btn btn-danger btn-sm btn-p10">去付款</a></p>
                                <p class="text-center"><a href="person-logistics.html">查看物流</a></p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!--person-dj-->
          <div class="blank20"></div>
          <div class="person-dj-bot clearfix">
            <div class="person-dj-left border pull-left">
              <div class="person-dian clearfix">
                <h3 class="f16"><a href="person-collect.html" class="black pull-right f12">查看全部收藏 ></a>商品收藏</h3>
              </div>
              <div class="person-dj-lr">
                <ul>
                  <li> <a href="index-detail.html"><img src="/public/pc/images/img-veiw-big.jpg" width="265" height="190"></a>
                    <p><a href="index-detail.html" class="black">珍湘茶籽调和油5L有机食用油健康食用油买一送一</a></p>
                    <p class="price red arial"><span class="price-icon">￥</span><em class="f24">96</em><del>￥96</del></p>
                    <a href="#" class="getcar pull-left"></a> <a href="#" class="getrb pull-right"></a> </li>
                  <li> <a href="index-detail.html"><img src="/public/pc/images/img-veiw-big.jpg" width="265" height="190"></a>
                    <p><a href="index-detail.html" class="black">珍湘茶籽调和油5L有机食用油健康食用油买一送一</a></p>
                    <p class="price red arial"><span class="price-icon">￥</span><em class="f24">96</em><del>￥96</del></p>
                    <a href="#" class="getcar pull-left"></a> <a href="#" class="getrb pull-right"></a> </li>
                </ul>
              </div>
            </div>
            <!--person-dj-left-->
            <div class="person-dj-right pull-right border">
              <div class="person-dian clearfix">
                <h3 class="f16">卡券包</h3>
              </div>
              <div class="cardbox">
                <ul>
                  <li class="clearfix"> <a href="person-favorable-ticket.html" class="pull-left"><img src="/public/pc/images/person/card.gif" width="60" height="60" class="mr-10"></a>
                    <p>优惠券<span class="red">10</span> 张</p>
                    <p>价值：<a href="person-favorable-ticket.html" class="red pull-right">去查看 ></a><span class="hui arial">￥500</span> </p>
                  </li>
                  <li class="clearfix"> <a href="person-favorable-ticket.html" class="pull-left"><img src="/public/pc/images/person/card.gif" width="60" height="60" class="mr-10"></a>
                    <p>优惠券<span class="red">10</span> 张</p>
                    <p>价值：<a href="person-favorable-ticket.html" class="red pull-right">去查看 ></a><span class="hui arial">￥500</span> </p>
                  </li>
                </ul>
              </div>
            </div>
            <!--person-dj-right-->
          </div>
          <!--person-dj-bot-->
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
<script src="/public/pc/js/person.js"></script>
<script>
 {literal}
var $div_li =$(".person-dj-tit li");
$('#tabbox>div').eq(0).show();
$div_li.click(function(){
    $(this).find('a').addClass("red")            
         .end().siblings().find('a').removeClass("red");
    var index =  $div_li.index(this); 
    $('#tabbox>div')  
        .eq(index).show()   
        .siblings().hide();
    return false;
});

mui.init({
         pullRefresh: {
           container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
           down: {
             callback: pulldownRefresh
           },
           up: {
             contentrefresh: "正在加载...", //可选，正在加载状态时，上拉加载控件上显示的标题内容
             contentnomore: '没有更多数据了', //可选，请求完毕若没有更多数据时显示的提醒内容；
             callback: add_more //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
           }
         },
       });
  mui.ready(function ()
        {
          mui('#pullrefresh').scroll();
          mui('.mui-bar').on('tap', 'a', function (e)
          {
            mui.openWindow({
                     url: this.getAttribute('href'),
                     id: 'info'
                   });
          });

        });
  function pulldownRefresh()
  {
    Zepto('#sortbar').css("display", "none");
    setTimeout(function ()
           {
             Zepto('#sortbar').css("display", "");
             Zepto('#page').val(1);
             Zepto('#list').html('');
             add_more();
             mui('#pullrefresh').pullRefresh().refresh(true);
             mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
           }, 1000);
  }

  function order_html(v)
  {
    var sb = new StringBuilder();
    sb.append('<div class="mui-card" style="margin: 3px 3px;" >');
    sb.append('    <ul class="mui-table-view" style="color: #333;background: #f3f3f3;">');
    sb.append('        <li class="mui-table-view-cell order-link-cell">');
    sb.append('         <div class="mui-media-body  mui-pull-left">');
    sb.append('           <span class="mui-table-view-label">订单号：</span>' + v.mall_orders_no);
    sb.append('         </div>');
    sb.append('         <div class="mui-media-body mui-pull-right"> ');
    sb.append('           <a class="mui-icon iconfont icon-pingtaibaozhang button-color" href="aftersaleservice.php?record=' + v.orderid + '">&nbsp;售后服务</a> ');
    sb.append('         </div> ');
    sb.append('                </li>');
    sb.append('       <li class="mui-table-view-cell" style="height:84px;">');
    sb.append('          <a href="orderdetail.php?record=' + v.orderid + '" class="mui-navigate-right"> ');
    sb.append('                 <img class="mui-media-object mui-pull-left"  src="' + v.thumbnail + '">');
    sb.append('             <div class="mui-media-body">');
    sb.append('               <p class="mui-ellipsis" style="color:#333">' + v.ordername + '</p>');
    sb.append('               <p class="mui-ellipsis">数量：<span class="price">' + v.productcount + '</span>&nbsp;件</p>');
    sb.append('               <p class="mui-ellipsis">总金额：<span class="price">¥' + v.sumorderstotal + '</span></p>');
    sb.append('             </div> ');
    sb.append('          </a>');
    sb.append('        </li> ');
    sb.append('        <li class="mui-table-view-cell order-link-cell"> ');
    sb.append('         <div class="mui-media-body  mui-pull-left">');
    sb.append('            <a class="mui-icon iconfont" style="padding-left:55px; color:#cc3300;">订单状态：' + v.order_status + '</a>');
    sb.append('         </div> ');
    sb.append('         <div class="mui-media-body mui-pull-right fenge">');
    sb.append('            <a class="mui-icon iconfont icon-pingjia button-color" href="appraise_submit.php?record=' + v.orderid + '">&nbsp;去评价</a> ');
    sb.append('         </div>');
    sb.append('                </li>');
    sb.append('     </ul>');
    sb.append('</div>');
    return sb.toString();
  }
  function order_empty_html()
  {
    var sb = new StringBuilder();
    sb.append('<div class="mui-content-padded">');
    sb.append('               <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>');
    sb.append('               <p class="msgbody">您的已付款订单还是空的，快去选购商品吧<br>');
    sb.append('               <a href="index.php">>>>&nbsp;去逛逛</a> ');
    sb.append('             </p>  ');
    sb.append(' </div>');
    return sb.toString();
  }

  function add_more()
  {
    var page = Zepto('#page').val();
    Zepto('#page').val(parseInt(page, 10) + 1);
    mui.ajax({
           type: 'POST',
           url: "orders.ajax.php",
           data: 'type=appraise&page=' + page,
           success: function (json)
           {
             var msg = eval("(" + json + ")");
             if (msg.code == 200)
             {
               if (msg.data.length == 0 && page == 1)
               {
                 Zepto('#list').html(order_empty_html());
                 mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据
               }
               else
               {
                 Zepto.each(msg.data, function (i, v)
                 {
                   var nd = order_html(v);
                   //alert(nd);
                   Zepto('#list').append(nd);
                 });
                 mui('#pullrefresh').pullRefresh().endPullupToRefresh(false); //参数为true代表没有更多数据了。
               }
             }
             else
             {
               mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
             }
           }
         });
  }
  //触发第一页
  if (mui.os.plus)
  {
    mui.plusReady(function ()
            {
              setTimeout(function ()
                   {
                     mui('#pullrefresh').pullRefresh().pullupLoading();
                   }, 1000);

            });
  }
  else
  {
    mui.ready(function ()
          {
            Zepto('#page').val(1);
            mui('#pullrefresh').pullRefresh().pullupLoading();
          });
  }
 {/literal}
</script>
</html>
