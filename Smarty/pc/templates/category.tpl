<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$supplier_info.suppliername}-列表页</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
{include file='rightbar.tpl'}
<!--左侧悬浮导航-->
<div id="warp">
  {include file='header.tpl'}
  <!--head 头部-->
  <div class="cont">
    <div class="container">
      <div class="blank20"></div>
      <div class="choicebox border" >
        <div class="choicebox-tit">
          <p><a href="index.html">首页</a><em class="pull-left fw">&gt;</em><a href="#" class="btn-greey">山货野味<span class="headbg del"></span></a><a href="#" class="btn-greey">衣服<span class="headbg del"></span></a></p>
        </div>
        <div class="choicebox-body clearfix" style="padding-bottom:0px;">
          <div class="contorl-more" id="more"><a href="javascript:;" class="greey">更多选项</a></div>
          <dl class="clearfix">
            <dt>产地：</dt>
            <dd>
              <div class="dd-list"><span>更多</span> <a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a>
                <div class="mt-10"> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a><a href="#">怀化</a> <a href="#">岳阳</a> </div>
                <div class="mt-10"> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a></div>
              </div>
            </dd>
          </dl>
          <dl class="clearfix">
            <dt>口味：</dt>
            <dd>
              <div class="dd-list"><span>更多</span><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a></div>
            </dd>
          </dl>
          <dl class="clearfix">
            <dt>产地：</dt>
            <dd>
              <div class="dd-list"><span>更多</span><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a></div>
            </dd>
          </dl>
          <dl class="clearfix">
            <dt>产地：</dt>
            <dd>
              <div class="dd-list"><span>更多</span><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a></div>
            </dd>
          </dl>
          <dl class="clearfix">
            <dt>产地：</dt>
            <dd>
              <div class="dd-list"><span>更多</span><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a></div>
            </dd>
          </dl>
          <dl class="clearfix">
            <dt>产地：</dt>
            <dd>
              <div class="dd-list"><span>更多</span><a href="#">怀化</a> <a href="#">岳阳</a> <a href="#">衡阳</a> <a href="#">耒阳</a> <a href="#">株洲</a> <a href="#">永州</a> <a href="#">郴州</a> <a href="#">贵州</a> <a href="#">泰州</a> <a href="#">常德</a></div>
            </dd>
          </dl>
        </div>
      </div>
      <!--choicebox 筛选-->
      <div class="blank20"></div>
      <div class="productbox">
        <div class="product-tit black border">
          <div class="pull-right product-tit-right"> <span class="pull-left">共 <em class="red">1254545</em> 个商品</span>
            <p class="pull-right"><a href="#">&lt;</a><em class="red">1</em>/25<a href="#">&gt;</a></p>
          </div>
          <strong class="m-l10">默认排序</strong>
          <ul class="cotorl">
            <li><a href="#">销量 <span class="headbg up"></span></a></li>
            <li><a href="#">价格 <span class="headbg up"></span></a></li>
            <li><a href="#">人气 <span class="headbg down"></span></a></li>
          </ul>
          <ul class="textcorl">
            <li class="textbox">
              <input type="text" class="text txt arial gray-c" value="￥">
            </li>
            </li>
            <li>-</li>
            <li class="textbox">
              <input type="text" class="text txt arial gray-c" value="￥">
            </li>
            </li>
            <li>
              <input type="button" value="确定" class="btn btn-default btn-gray">
            </li>
          </ul>
        </div>
        <div class="blank20"></div>
        <div class="product-body Recent-body clearfix">
          <ul>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg1.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg2.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg3.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg4.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg5.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg6.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg1.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg2.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg3.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg4.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg5.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg6.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg1.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg2.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg3.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg4.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            
          </Ul>
        </div>
      </div>
      <!--product-->
      <div class="blank20"></div>
      <div class="pagebox clearfix">
        <ul class="page pull-right list-inline">
          <li ><a href="#" class="pageprev gray"><span>&lt;</span>&nbsp;上一页</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#" class="none">....</a></li>
          <li ><a href="#" class="pagenext">下一页&nbsp;<span>&gt;</span></a></li>
        </ul>
        <!--分页-->
      </div>
      <!--分页-->
      <div class="blank60"></div>
      <div class="Recent">
        <div class="Recent-tit">
          <h4>最近浏览</h4>
        </div>
        <div class="Recent-body">
          <ul>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg1.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg2.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg3.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
            <li>
              <div class="imgbox"><a href="index-detail.html"><img src="/public/pc/images/listimg4.jpg" width="260" height="165"></a>
                <p><a href="index-hot.html">天天热卖 HOT!</a></p>
              </div>
              <div class="imgbox-bot">
                <p class="name"><a href="index-detail.html" title="珍湘茶籽调和油5L有机食用油健康食用油绿色食品">珍湘茶籽调和油5L有机食用油健康食用油绿色食品</a></p>
                <p class="red">买一送一</p>
                <p class="price red arial"><span class="price-icon">￥</span>96<del>￥96</del></p>
              </div>
            </li>
           
          </Ul>
        </div>
      </div>
      <!--Recent 最近浏览-->
    </div>
  </div>
  <!--cont 主体-->
  <div class="blank90"></div>
  <!--link 链接-->
  <div class="foot">
    <div class="container">
      <div class="linkbox clearfix">
        <dl class="linkbox-l pull-left">
          <dt><a href="#"><img src="/public/pc/images/weixin.gif" width="141" height="173"></a></dt>
          <dd>
            <div class="linkbox-contact">
              <h4>客服热线：</h4>
              <h2 class="arial">400-8616-860</h2>
              <p class="greey">09:30 - 18:30（周一至周五）</p>
            </div>
          </dd>
        </dl>
        <!--link-l-->
        <div class="linkbox-r pull-right">
          <div class="row">
            <div class="col-md-4">
              <h4>新手入门</h4>
              <ul class="list-inline">
                <li><a href="#">注册协议</a></li>
                <li><a href="#">积分说明</a></li>
                <li><a href="#">优惠券</a></li>
                <li><a href="#">用户指南 </a></li>
                <li><a href="#">怎么赚</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <h4>购物指南</h4>
              <ul class="list-inline">
                <li><a href="#">购物流程</a></li>
                <li><a href="#"> 常见问题</a></li>
                <li><a href="#">联系客服</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <h4>配送方式</h4>
              <ul class="list-inline">
                <li><a href="#">物流配送</a></li>
                <li><a href="#">上门自提</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <h4>支付方式</h4>
              <ul class="list-inline">
                <li><a href="#">在线支付</a></li>
                <li><a href="#">支付问题</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <h4>售后服务</h4>
              <ul class="list-inline">
                <li><a href="#">退换货政策</a></li>
                <li><a href="#">退换货流程</a></li>
                <li><a href="#">退换货说明</a></li>
                <li><a href="#">退款说明 </a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--link 底部上半部连接-->
      <div class="foot-inner">
        <ul class="pull-left list-inline mt-10 foot-l">
          <li><a href="#"><img src="/public/pc/images/footlink1.gif"></a></li>
          <li><a href="#"><img src="/public/pc/images/footlink2.gif"></a></li>
          <li><a href="#"><img src="/public/pc/images/footlink3.gif"></a></li>
        </ul>
        <div class="pull-left foot-r">
          <ul class="clearfix">
            <li><a href="#">返回首页</a>|</li>
            <li><a href="#">关于我们</a>|</li>
            <li><a href="#">关于供应商</a>|</li>
            <li><a href="#">关于代理商</a>|</li>
            <li><a href="#">福布斯榜</a>|</li>
            <li><a href="#">隐私条款</a>|</li>
            <li><a href="#">友情链接</a></li>
          </ul>
          <p>长沙<strong>千里及</strong>网络技术有限公司<span class="greey">Copyright © 2010-2014 ttwz168.com All Rights Reserved.湘ICP备13009493号-1</span></p>
          </li>
        </div>
      </div>
      <!--foot-inner 底部下半部分-->
    </div>
    <!--foot 底部-->
  </div>
</div>
<!--warp 外层-->
</body>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="/public/pc/js/index.js"></script>
<script>
{literal}
//右侧导航
rightBar('.btn-group-right ul','.group-open');
var oDl = $(".choicebox-body dl:gt(3)");
oDl.hide();
$('.contorl-more').click(function(){	
	if(oDl.css('display')!='block'){
		oDl.show();
		$(this).find('a').addClass('active');
		$(this).find('a').text('收起更多');
	}else{
		oDl.hide();
		$(this).find('a').removeClass('active');
		$(this).find('a').text('更多选项');
	}
});

var aDl = $(".choicebox-body dl");
for(var i=0; i<aDl.length;i++){	
	oMore(aDl.eq(i));
}
function oMore(obj){	
	var oSpan = obj.find('span');
	var oDiv = obj.find('div');
	
	if(oDiv.height()>22){	
		oSpan.show();
	}else{	
		oSpan.hide();
	}
	
	oSpan.click(function(){	
		if(obj.height() == oDiv.height() ){	
		 	obj.height('22px'); 
			$(this).removeClass('active');
		}else{	
			obj.height(oDiv.height());
			$(this).addClass('active');
		}
	});
}

$('.choicebox-tit a.btn-greey').click(function(){	
	$(this).addClass('active').siblings().removeClass('active');
});
{/literal} 
</script>
</html>
