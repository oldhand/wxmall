<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <title></title>
    <link href="public/css/mui.css" rel="stylesheet"/>
    <link href="public/css/public.css" rel="stylesheet"/>
    <link href="public/css/iconfont.css" rel="stylesheet"/>
    <link href="public/css/parsley.css" rel="stylesheet">
    <link href="public/css/mui.picker.css" rel="stylesheet"/>
    <link href="public/css/mui.dtpicker.css" rel="stylesheet"/>
    <link href="public/css/mui.listpicker.css" rel="stylesheet"/>
    <link href="public/css/mui.poppicker.css" rel="stylesheet"/>

    <link href="public/css/sweetalert.css" rel="stylesheet"/>

    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="public/js/jweixin.js"></script>
    <script src="public/js/common.js" type="text/javascript" charset="utf-8"></script>

    <script src="public/js/mui.picker.js"></script>
    <script src="public/js/mui.dtpicker.js"></script>
    <script src="public/js/mui.listpicker.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/mui.poppicker.js" type="text/javascript" charset="utf-8"></script>
    <script src="public/js/city.data.js" type="text/javascript" charset="utf-8"></script>


    <script src="public/js/parsley.min.js"></script>
    <script src="public/js/parsley.zh_cn.js"></script>

    <script type="text/javascript" src="public/js/sweetalert.min.js"></script>

    <style>
        {literal}
        .img-responsive{ display:block; height:auto; width:100%; }

        .mui-input-row label{
            line-height:21px;
            height:21px;
        }

        .menuicon{ font-size:1.2em; color:#fe4401; padding-right:10px; }

        .menuitem a{ font-size:1.1em; }

        #save_button{
            font-size:20px;
            color:#cc3300;
            padding-left:5px;
        }

        .mui-bar-tab .mui-tab-item .mui-icon{
            width:auto;
        }

        .mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active{
            color:#cc3300;
        }

        .mui-input-row label{
            text-align:right;
            width:30%;
        }

        .mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea{
            float:right;
            width:70%;
        }

        .mui-input-row label{
            line-height:21px;
            padding:10px 10px;
        }

        .mui-input-clear{
            font-size:12px;
        }

        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success{
            color:#468847;
            background-color:#DFF0D8;
            border:1px solid #D6E9C6;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error{
            color:#B94A48;
            background-color:#F2DEDE;
            border:1px solid #EED3D7;
        }

        .parsley-errors-list{
            margin:2px 0 3px;
            padding:0;
            list-style-type:none;
            font-size:0.9em;
            line-height:0.9em;
            opacity:0;
            transition:all .3s ease-in;
            -o-transition:all .3s ease-in;
            -moz-transition:all .3s ease-in;
            -webkit-transition:all .3s ease-in;
        }

        .parsley-errors-list.filled{
            opacity:1;
        }

        .mui-table-view input[type='radio']{
            line-height:21px;  
            height:30px;
            float:left;
            border:0;
            outline:0 !important;
            background-color:transparent;
            -webkit-appearance:none;
        }

        .mui-input-row label.radio{
            line-height:5px;   
            text-align:left;
            padding:10px 3px;
        }

        .mui-table-view input[type='radio']{
        }

        .mui-table-view input[type='radio']:before{
            content:'\e411';
        }

        .mui-table-view input[type='radio']:checked:before{
            content:'\e441';
        }

        .mui-table-view input[type='radio']:checked:before{
            color:#007aff;
        }

        .mui-table-view input[type='radio']:before{
            font-family:Muiicons;
            font-size:20px;
            font-weight:normal;
            line-height:1;
            text-decoration:none;
            color:#aaa;
            border-radius:0;
            background:none;
            -webkit-font-smoothing:antialiased;
        }

        {/literal}
    </style>
    {include file='theme.tpl'}
</head>

<body>
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
    {include file='leftmenu.tpl'}
    <div class="mui-inner-wrap">
		{if $supplier_info.showheader eq '0'}
        <header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
            <a id="offCanvasShow" href="#offCanvasSide"
               class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
            <h1 class="mui-title" id="pagetitle">会员VIP认证</h1>
        </header> 
		{/if}  
             {if $authentication.authenticationstatus eq '0'}
	            <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
	                <a class="mui-tab-item mui-active save" href="#">
	                    <span class="mui-icon iconfont icon-check">&nbsp;<span style="font-size:20px;">确认认证</span></span>
	                </a>
	            </nav>
			 {elseif $authentication.authenticationstatus eq '1'}
	            <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
	                <a class="mui-tab-item mui-active save" href="#">
	                    <span class="mui-icon iconfont icon-check">&nbsp;<span style="font-size:20px;">增加认证</span></span>
	                </a>
	            </nav>
	         {else} 
                <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
                  <a class="mui-tab-item mui-active mui-action-back">
                      <span class="mui-icon iconfont icon-queren01">&nbsp;<span style="font-size:20px;">返回</span></span>
                  </a>
                </nav>
            {/if} 
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body">
                                <a href="javascript:;">
                                    <img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}">
                                    <div class="mui-media-body">
                                        <p class='mui-ellipsis' style="color:#333">昵称：{$profile_info.givenname}  {if $authentication.authenticationstatus eq '1' || $authentication.authenticationstatus eq '2'}【已认证】{else}【未认证】{/if}</p>
										{if $authentication.authenticationstatus eq '1' || $authentication.authenticationstatus eq '2'}
										<p class='mui-ellipsis'>等级：【{$authentication.rankname}】</p> 
										{else}
										<p class='mui-ellipsis'>等级：【普通会员】</p> 
										{/if}
                                    </div>
                                </a>
                            </div>
                        </li>
						<!--
						{if $authentication.authenticationstatus eq '1' || $authentication.authenticationstatus eq '2'}
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body  mui-pull-left">
                                <span class="mui-table-view-label">认证日期：</span> 【{$authentication.published}】
                            </div>
                        </li>
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body  mui-pull-left">
                                <span class="mui-table-view-label">保证金：</span> 【{$authentication.depositmoney}元】
                            </div>
                        </li>
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body  mui-pull-left">
                                <span class="mui-table-view-label">剩余保证金：</span> 【{$authentication.remainmoney}元】
                            </div>
                        </li>
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body  mui-pull-left">
                                <span class="mui-table-view-label">已经退还次数：</span> 【{$authentication.returncount}次】
                            </div>
                        </li> 
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body  mui-pull-left">
                                <span class="mui-table-view-label">可享折扣：</span> 【{$authentication.rankdiscount}折】
                            </div>
                        </li>
						{/if}-->
                    </ul>
                </div>
				{if $authentication.authenticationstatus eq '1' || $authentication.authenticationstatus eq '2'}
				<div class="mui-card" style="margin: 3px 3px;"> 
					  <ul class="mui-table-view">
			                <li class="mui-table-view-cell">
			                    <a href="depositwaters.php" class="mui-navigate-right"> 
									<div class="mui-media-body  mui-pull-left">
										<span class="mui-table-view-label">消费承诺金流水 </span>  
									</div> 
									<div class="mui-media-body  mui-pull-right" style="color:#888;padding-right:20px;">
										查看您的消费承诺金明细
									</div>
								</a>
			                </li>
					  </ul>
				</div>
				{/if}
                <div class="mui-card" style="margin: 3px 3px;">
                    <ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;"> 
                         <form class="mui-input-group" name="frm" id="frm" method="post" action="authentication_confirmpayment.php"
                              parsley-validate> 
							  {if $profileranks|@count gt 0}
							    <div class="mui-input-row" style="margin-top:3px;margin-left:10px;">  
						                <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
						                  <tbody>
	                                           {foreach name="profileranks" key=rankid item=profilerank_info from=$profileranks} 
											   		{if $profilerank_info.allow eq '1'}
		   						                    <tr>
		   						                      <td><input class="radio" type="radio" value="{$rankid}" id="radio-{$smarty.foreach.profileranks.iteration}" name="authenticationid" {if $profilerank_info.selected eq '1'}checked{/if} >
		   											       <label style="width:auto;" class="radio" for="radio-2">{$profilerank_info.rankname}【可享{$profilerank_info.rankdiscount}折】{if $profilerank_info.needmoney neq $profilerank_info.depositmoney}还{/if}需交纳保证金{$profilerank_info.needmoney}元</label></td> 
		   						                    </tr> 
													{else}
		   						                    <tr>
		   						                      <td><input class="radio" type="radio" disabled value="{$rankid}" id="radio-{$smarty.foreach.profileranks.iteration}" name="authenticationid"  >
		   											       <label style="width:auto;" class="radio" for="radio-2">{$profilerank_info.rankname}【可享{$profilerank_info.rankdiscount}折】【已经认证】</label></td> 
		   						                    </tr>
													{/if}
	   						 	               {/foreach} 
										</tbody>
										</table>	 
                                </div>
							   {/if} 
                        </form>  
                    </ul>
                </div>
				
                {foreach name="profileranks" key=rankid item=profilerank_info from=$profileranks} 
	                 <div class="mui-card profilerank_description" id="profilerank_description_{$rankid}" style="margin: 3px 3px;{if $profilerank_info.selected neq '1'}display:none;{/if} ">
                          <div class="mui-content-padded" style="margin:0px;margin-top: 5px;"> 
						         <div class="show-content"> 
 										{$profilerank_info.description}
						         </div>
						 </div>
	                 </div>
                {/foreach}
                {include file='copyright.tpl'}
            </div>
        </div> 
        <!-- end pullrefresh  -->

    </div>
</div>


<script type="text/javascript">

    {literal}
    var mask = null;

     
    mui.init({
        pullRefresh: {
            container: '#pullrefresh'
        },
    });
    mui.ready(function () {
        mui('#pullrefresh').scroll();
        mui('.mui-bar').on('tap', 'a', function (e) {
            mui.openWindow({
                url: this.getAttribute('href'),
                id: 'info'
            });
        });


       
        mui('.mui-bar').on('tap', 'a.save', function (e) {
            var validate = Zepto('#frm').parsley('validate');
            if (validate) {
                 document.frm.submit();
            }
        });
        
        mui('.mui-table-view').on('change', 'input.radio', function () {
             var rankid = Zepto(this).val();  
			 Zepto(".profilerank_description").css("display","none"); 
             Zepto('#profilerank_description_'+rankid).css("display",""); 
             
        });  
       
    });
    

    {/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>