<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>(我的账本-收益)</title>
<link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/pc/css/public.css" rel="stylesheet">
<link href="/public/pc/css/person.css" rel="stylesheet">
<link href="/public/pc/css/index.css" rel="stylesheet">
<link href="/public/pc/css/login.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./public/pc/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="warp">
  {include file='header.tpl'}
  <div class="line3"></div></div>
  <!--head 头部-->
  <div class="cont">
    <div class="container">
      <div class="break-person clearfix">
        <p><a href="#">首页</a><em>&gt;</em><a href="usercenter.php">个人中心</a><em>&gt;</em><span>我的帐本</span></p>
      </div>
      <!--break-person-->
      <div class="personbox">
        {include file='usercenterL.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right hui">
          <div class="safebox border bn">
            <div class="person-linetit clearfix">
              <h3 class="f16 pull-left w100">我的账簿</h3>
              <p class="pull-left"><!-- 当前可用资金
                <span class="red arial p-lr5">{$profile_info.money}</span> 元，冻结中资金<span class="red arial p-lr5">{$frozencommission}</span> 元 累计收益<span class="red arial p-lr5">{$profile_info.accumulatedmoney}</span> 元 --></p>
            </div>
            <!--person-linetit-->
            <div class="curtain-tit border-t1 clearfix">
              <ul style="margin-left:380px;">
                <li><a href="accountbook.php" class="active">总收益</a>|</li>
                <li><a href="billwaters.php">账单流水</a></li>
              </ul>
            </div>
            <!--curtain-tit-->
            <div class="curtain-body">
              <div class="curtain-body-tit bg-f5 h35">
                <p class="fw">总收益：<span class="red arial">¥{$total}</span></p>
              </div>
              <div class="curtain-body-lr">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="zbtable">
                  <thead>
                    <tr class="black">
                      <td>可用资金</td>
                      <td class="red">¥{$profile_info.money}</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="black">
                      <td>累积收益</td>
                      <td class="red">¥{$profile_info.accumulatedmoney}</td>
                    </tr>
                    <tr class="black">
                      <td>冻结资金</td>
                      <td class="red">¥{$frozencommission}</td>
                    </tr>
                    <tr class="black">
                      <th></th>
                      <th class="red"></th>
                    </tr>
                    <tr class="black">
                      <td>分享收益</td>
                      <td class="red">¥{$share}</td>
                    </tr>
                    <tr class="black">
                      <td>提成收益</td>
                      <td class="red">¥{$totalcommission}</td>
                    </tr>
                    <tr class="black">
                      <td>推广收益</td>
                      <td class="red">¥{$popularize}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!--safe-box-->
          <div class="blank20"></div>
          <div class="pagebox clearfix">
           <!--  <ul class="page pull-right list-inline">
              <li ><a href="#" class="pageprev">&lt;&nbsp;上一页</a></li>
              <li class="active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#" class="none">....</a></li>
              <li ><a href="#" class="pagenext">下一页&nbsp;&gt;</a></li>
            </ul> -->
            <!--分页-->
          </div>
          <div class="blank20"></div>
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
<script src="/public/pc/js/jquery.lazyload.min.js"></script> 
<script src="/public/pc/js/index.js"></script>
<script src="/public/pc/js/person.js"></script>
</html>
