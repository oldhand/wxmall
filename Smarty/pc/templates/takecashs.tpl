<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>提现申请</title>
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
        <p><a href="#">首页</a><em>&gt;</em><a href="usercenter.php">个人中心</a></p>
      </div>
      <!--break-person-->
      <div class="personbox">
        {include file='usercenterL.tpl'}
        <!--person-left 个人中心左侧-->
        <div class="person-right pull-right hui">
          <div class="safebox border bn">
            <div class="person-linetit clearfix">
              <h3 class="f16 pull-left w100">提现申请</h3>
              <p class="pull-left"><!-- 当前可用资金
                <span class="red arial p-lr5">{$profile_info.money}</span> 元，冻结中资金<span class="red arial p-lr5">{$frozencommission}</span> 元 累计收益<span class="red arial p-lr5">{$profile_info.accumulatedmoney}</span> 元 --></p>
            </div>
            <!--person-linetit-->
            <div class="curtain-tit border-t1 clearfix">
              <ul style="margin-left:380px;">
                <li><a href="takecashs.php" class="active">提现申请</a>|</li>
                <li><a href="takecashlogs.php">历史提现记录</a></li>
              </ul>
            </div>
            <!--curtain-tit-->
            <div class="curtain-body">
              <div class="curtain-body-tit bg-f5 h35" style="padding-left: 35px;">
                <p class="fw">当前可用资金：<span class="red arial">¥{$takecashs.money}</span></p>
              </div>
              <div class="curtain-body-lr">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" class="zbtable">
                  <thead>
                    <tr class="black">
                      <td>分享收益：¥ {$takecashs.share}</td>
                      <td class="red">{if $takecashs.allow_share eq '1'} <font color="black">【可提现】</font> {else} 【不可提现】{/if}</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="black">
                      <td>提成收益：¥ {$takecashs.commission}</td>
                      <td class="red">{if $takecashs.allow_commission eq '1'} <font color="black">【可提现】</font> {else} 【不可提现】{/if}</td>
                    </tr>
                    <tr class="black">
                      <td>推广收益：¥ {$takecashs.popularize}</td>
                      <td class="red">{if $takecashs.allow_popularize eq '1'} <font color="black">【可提现】</font> {else} 【不可提现】{/if}</td>
                    </tr>
                    <tr class="black">
                      <th></th>
                      <th class="red"></th>
                    </tr>
                    <tr class="black">
                      <td>总收益：¥ {$takecashs.total}</td>
                      <td class="red"></td>
                    </tr>
                    <tr class="black">
                      <td>已提现资金：¥ {$takecashs.historytakecash}</td>
                      <td class="red"></td>
                    </tr>
                    <tr class="black">
                      <td>可提现金额：¥ {$takecashs.allowtakecash}.00</td>
                      <td class="red">【达到<span>¥ {$takecashs.takecashlimit}</span>可提现】</td>
                    </tr>
                  </tbody>
                </table>
                {if $takecashs.msg neq ''}
                <div class="findKey-form-box border-t1">
              <div class="blank20"></div>
              <ul class="clearfix" style="width:100%;">
                <li>
                  <div>
                    <center>{$takecashs.msg}
                    </center>
                  </div>
                </li>
                </ul>
                </div>
                  {/if}
                {if $takecashs.takecash eq 'open'}
                    <form class="mui-input-group" name="frm" id="frm" method="post" action="takecashs.php"  parsley-validate>
                    <input  id="type" name="type"  value="submit" type="hidden" > 
                    <input  id="token" name="token" value="{$takecashs.token}" type="hidden" >
                  
                    <div class="mui-card" style="margin: 3px 3px;"> 
                      <div class="mui-input-row">
                        <label style="height:45px;">银行:</label>  
                              <select name="bank" id="bank" data-toggle="selectpicker" class="required"  data-width="200" onchange="onbankchange(this.value);" >                      
                                   <option value="" >请选择银行</option> 
                             <!--<option value="微信号">微信号</option>-->
                             <option value="支付宝">支付宝</option> 
                             <option value="中国农业银行">中国农业银行</option>  
                             <option value="中国工商银行">中国工商银行</option>  
                             <option value="中国建设银行">中国建设银行</option>  
                             <option value="中国银行">中国银行</option>  
                             <option value="交通银行">交通银行</option>   
                              </select> 
                      </div>
                        <div class="mui-input-row" style="margin-top:3px;">
                          <label style="height:45px;" id="account_label">银行账号:</label>
                          <input id="account" name="account" value="" type="text" style="font-size: 12px;"  class="mui-input-clear required"  >
                        </div>
                        <div class="mui-input-row" style="margin-top:3px;">
                          <label style="height:45px;">收款人姓名:</label>
                          <input id="realname" name="realname" value="" type="text" style="font-size: 12px;" class="mui-input-clear required"  >
                        </div>
                        <div class="mui-input-row" style="margin-top:3px;">
                          <label style="height:45px;">提现金额:</label>
                          <input id="amount" name="amount" placeholder="代扣银行手续费" value="" type="number" style="font-size: 12px;" class="mui-input-clear number required" parsley-min="{$takecashs.takecashlimit}"  parsley-max="{$takecashs.allowtakecash}">
                        </div>
                        <div class="mui-input-row" style="margin-top:3px;">
                          <label style="height:45px;">身份证:</label>
                          <input id="idcard" name="idcard" placeholder="" value="" type="text" style="font-size: 12px;" class="mui-input-clear required"  parsley-trigger="keyup" parsley-rangelength="[18,18]" parsley-error-message="请输入18位身份证号码">
                        </div>
                    </div>
                    </form>
                  {/if}
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
