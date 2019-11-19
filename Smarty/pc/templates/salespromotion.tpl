<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$supplier_info.suppliername}-促销活动</title>
  <link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/pc/css/public.css" rel="stylesheet">
  <link href="/public/pc/css/person.css" rel="stylesheet">
  <link href="/public/pc/css/seckill_area.css" rel="stylesheet">
  <!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  {include file='leftbar.tpl'}
  <!--左侧悬浮导航-->
  <div id="warp">
    {include file='header.tpl'}
    <!--cont 主体-->
    <!-- 秒杀专区 -->
    <div class='seckill_area'>
      <div class='seckill_tit'>
        <h4>促销活动</h4>
        <img src="/public/pc/images/seckill3.png">
      </div>
      <div class='clearfix seckill_goods'>
        <ul>
        {foreach name="salesactivitys" key=salesactivityid item=salesactivity_info  from=$salesactivitylist} 
          <li>
            <a href='salesactivity.php?record={$salesactivity_info.id}'><img src="{$salesactivity_info.homepage}"></a>
            <div>
              <span class="ms_stop_tit" style="width: 100%;height:122px;display: inline-block;white-space:normal;word-break:break-all;">{$salesactivity_info.activityname}</span>
              <!-- <p style="color: #cc3300;margin-top: 7px;">仅剩<span class='num'>2</span>件</p>
              <p></p>
              <p>秒杀价&nbsp;<span class='fh'>&yen;</span><em>11</em></p>
              <p>原价<del>&yen;110</del></p> -->
              <a style="margin-top: 30px;" class='btn_ms start' href="salesactivity.php?record={$salesactivity_info.id}">马上进入</a>
            </div>
          </li>
        {/foreach} 
        </ul>
      </div>
    </div>
    <div class="blank90"></div>

    <!--link 链接-->
    {include file='footbar.tpl'}
    {include file='footer.tpl'}
</body>
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="/public/pc/js/jquery.lazyload.min.js"></script> 
<script src="/public/pc/js/index.js"></script>
</html>