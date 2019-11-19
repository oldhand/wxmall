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
            width:20px;
            margin-top:10px;
            height:30px;
            float:left;
            border:0;
            outline:0 !important;
            background-color:transparent;
            -webkit-appearance:none;
        }

        .mui-input-row label.radio{
            line-height:21px;
            width:30px;
            height:40px;
            float:left;
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
            <h1 class="mui-title" id="pagetitle">个人资料</h1>
        </header>
		{/if} 
        {if $profile_info.mobile eq ''}
            <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
				<input id="submit_type" value="submit" type="hidden">
                <a class="mui-tab-item save" href="#"> 
                    <span id="submit_icon" class="mui-icon iconfont icon-save button-color">&nbsp;<span style="font-size:20px;" id="submit_button">保存</span></span>
                </a>
            </nav>
        {else}
            {if $profile_info.onelevelsourcer eq '' && $profile_info.hassourcer eq 0}
                <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
					<input id="submit_type" value="submit" type="hidden">
                    <a class="mui-tab-item mui-active save" href="#">
                        <span id="submit_icon" class="mui-icon iconfont icon-save">&nbsp;<span style="font-size:20px;" id="submit_button">保存</span></span>
                    </a>
                </nav>
            {else}
                <nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">
                    <a class="mui-tab-item mui-active mui-action-back">
                        <span class="mui-icon iconfont icon-queren01">&nbsp;<span style="font-size:20px;">返回</span></span>
                    </a>
                </nav>
            {/if}

        {/if}
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
            <div class="mui-scroll">
                <div class="mui-card" style="margin: 0 3px;">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body">
                                <a href="javascript:;">
                                    <img class="mui-media-object mui-pull-left" src="{$profile_info.headimgurl}">
                                    <div class="mui-media-body">
                                        <p class='mui-ellipsis' style="color:#333">昵称：{$profile_info.givenname}</p>
                                        <p class='mui-ellipsis'>等级：{include file='profilerank.tpl'}</p>

                                    </div>
                                </a>
                            </div>
                        </li>
                       
                        {if $profile_info.physicalstorename neq ''}
                            <li class="mui-table-view-cell">
                               <div class="mui-media-body  mui-pull-left">
                                   <span class="mui-table-view-label">店铺：</span> 【{$profile_info.physicalstorename}】
                               </div>
                               <div class="mui-media-body  mui-pull-right">
                                   <span class="mui-table-view-label">店员：</span> 【{$profile_info.assistantprofile}】
                               </div>
                            </li>
						{else}
	                        {if $profile_info.onelevelsourcer neq ''}
	                            <li class="mui-table-view-cell">
	                                <div class="mui-media-body  mui-pull-left">
	                                    <span class="mui-table-view-label">您的推荐人：</span> 【{$profile_info.sourcergivename}】
	                                </div>
	                            </li>
	                        {/if}
                        {/if}
                    </ul>
                </div>
                <div class="mui-card" style="margin: 3px 3px;">
                    <ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;">

                        <form class="mui-input-group" name="frm" id="frm" method="post" action="userdetail.php"
                              parsley-validate>
                            <input id="needverifycode" value="{$needverifycode}" type="hidden">
                            {if $profile_info.mobile eq ''}
                                <div class="mui-input-row" style="margin-top:3px;">
                                    <label style="height:45px;">手机号码:</label>
                                    <input id="type" name="type" value="submit" type="hidden">
                                    <input required="required" parsley-rangelength="[11,11]" id="mobile" name="mobile"
                                           value="{$profile_info.mobile}" type="number" style="font-size: 12px;"
                                           class="mui-input-clear required" maxlength="11" placeholder="您的常用手机号码"
                                           parsley-error-message="请输入正确的手机号码">
                                </div>
                                {if $needverifycode eq 'yes'}
                                    <div class="mui-input-row" style="margin-top:3px;display:none;"
                                         id="verifycode-wrap">
                                        <div class="mui-media-body mui-col-sm-8 mui-pull-left"
                                             style="padding-left:10px;">
                                            <input required="required" parsley-rangelength="[6,6]" id="verifycode"
                                                   name="verifycode" value="" type="number" style="font-size: 12px;"
                                                   class="required" maxlength="6" placeholder="6位验证码"
                                                   parsley-error-message="请输入正确的验证码">
                                        </div>
                                        <div class="mui-media-body mui-col-sm-3 mui-pull-right"
                                             style="padding-right:1px;">
                                            <button id="send_verifycode" disabled type="button"
                                                    class="mui-btn mui-btn-success" style="width:100%;">获取验证码
                                            </button>
                                        </div>
                                    </div>
                                {/if}
                                {if $profile_info.onelevelsourcer eq ''}
                                    <div class="mui-input-row" style="margin-top:3px;">
                                        <label style="height:45px;">推荐人手机:</label>
                                        <input parsley-rangelength="[11,11]" id="invitationcode" name="invitationcode"
                                               value="{$profile_info.invitationcode}" type="number" {if $supplier_info.sourcerrequired eq '0'}required="required"{/if}
                                               style="font-size: 12px;" class="mui-input-clear {if $supplier_info.sourcerrequired eq '0'}required{/if}" maxlength="11"
                                               placeholder="您的推荐人手机号码">
                                    </div>
                                {/if}
		                       
                                <div class="mui-input-row">
                                    <label style="height:45px;">选择地区:</label>
                                    <input id="province" name="province" type="hidden" value="{$profile_info.province}">
                                    <input id="city" name="city" type="hidden" value="{$profile_info.city}">
                                    <input data-options='{ldelim}"province":"{$profile_info.province}","city":"{$profile_info.city}"{rdelim}'
                                           required="required" id="citypicker" name="citypicker"
                                           value="{$profile_info.province} {$profile_info.city}" type="text" readonly
                                           class="mui-input-clear citypicker required" placeholder="地区信息">
                                </div>
                                <div class="mui-input-row">
                                    <label style="height:45px;">您的性别:</label>
											                   <span><input type="radio" value="男" id="radio-1"
                                                                            name="gender"
                                                                            {if $profile_info.gender eq '男'}checked{/if} >
											                   <label class="radio" for="radio-1">男</label></span>
											                   <span><input type="radio" value="女" id="radio-2"
                                                                            name="gender"
                                                                            {if $profile_info.gender eq '女'}checked{/if}>
											                   <label class="radio" for="radio-2">女</label></span>
										                       <span><input type="radio" value="" id="radio-3"
                                                                            name="gender"
                                                                            {if $profile_info.gender neq '男' && $profile_info.gender neq '女'}checked{/if}>
										                       <label class="radio" for="radio-3"
                                                                      style="width:50px">保密</label></span>

                                </div>
                                <div class="mui-input-row">
                                    <label style="height:45px;">您的生日:</label>
                                    <input id="birthdate" value="{$profile_info.birthdate}"
                                           data-options='{ldelim}"type":"date","beginYear":1930,"endYear":2016,"value":"{if $profile_info.birthdate eq ''}1990-01-01{else}{$profile_info.birthdate}{/if}"{rdelim}'
                                           readonly name="birthdate" type="text" class="mui-input-clear"
                                           maxlength="20" placeholder="请输入您的生日">
                                </div>
                                <div id="verifycode-wrap-div" class="mui-table-view-cell"
                                     style="margin-top:3px;display:none;">
                                    <div id="verifycode-errormsg" class="mui-media-body"
                                         style="color:#cc3300;text-align:center">
                                        验证码错误，请输入正确的验证码！
                                    </div>
                                </div>
                            {else}

                                {if $profile_info.onelevelsourcer eq '' && $profile_info.hassourcer eq 0}
                                    <div class="mui-input-row" style="margin-top:3px;">
                                        <label style="height:45px;">推荐人手机:</label>
                                        <input id="type" name="type" value="yetactivate" type="hidden">
                                        <input parsley-rangelength="[11,11]" id="invitationcode" name="invitationcode" {if $supplier_info.sourcerrequired eq '0'}required="required"{/if}
                                               value="" type="number" style="font-size: 12px;" class="mui-input-clear {if $supplier_info.sourcerrequired eq '0'}required{/if}"
                                               maxlength="11" placeholder="您的推荐人手机号码">
                                    </div>
                                {else}
                                    <div class="mui-input-row" style="margin-top:3px;">
                                        <label style="height:45px;">手机号码:</label>
                                        <input disabled id="mobile" name="mobile" value="{$profile_info.mobile}"
                                               type="number" style="font-size: 12px;" class="mui-input-clear required">
                                    </div>
                                    <div class="mui-input-row">
                                        <label style="height:45px;">地区:</label>
                                        <input value="{$profile_info.province} {$profile_info.city}" type="text"
                                               disabled class="mui-input-clear">
                                    </div>
                                    <div class="mui-input-row">
                                        <label style="height:45px;">您的性别:</label>
											                   <span><input disabled type="radio" value="男" id="radio-1"
                                                                            name="gender"
                                                                            {if $profile_info.gender eq '男'}checked{/if} >
											                   <label class="radio" for="radio-1">男</label></span>
											                   <span><input disabled type="radio" value="女" id="radio-2"
                                                                            name="gender"
                                                                            {if $profile_info.gender eq '女'}checked{/if}>
											                   <label class="radio" for="radio-2">女</label></span>
										                       <span><input disabled type="radio" value="" id="radio-3"
                                                                            name="gender"
                                                                            {if $profile_info.gender neq '男' && $profile_info.gender neq '女'}checked{/if}>
										                       <label class="radio" for="radio-3"
                                                                      style="width:50px">保密</label></span>

                                    </div>
                                    <div class="mui-input-row">
                                        <label style="height:45px;">您的生日:</label>
                                        <input value="{$profile_info.birthdate}" disabled type="text"
                                               class="mui-input-clear" maxlength="20">
                                    </div>
                                {/if}
                                <div id="verifycode-wrap-div" class="mui-table-view-cell"
                                     style="margin-top:3px;display:none;">
                                    <div id="verifycode-errormsg" class="mui-media-body"
                                         style="color:#cc3300;text-align:center">
                                    </div>
                                </div>
                            {/if}
                        </form>
                        <li class="mui-table-view-cell">
                            <div class="mui-media-body" style="text-align:center">
                                <p><a href="#xieyi">《会员注册协议》</a> <a href="#falv">《法律声明》</a></p>
                                <p><input checked disabled type="checkbox"> 本人已阅读并同意以上协议条款</p>
                            </div>
                        </li>

                    </ul>
                </div>
                {include file='copyright.tpl'}
            </div>
        </div>

        <!-- end pullrefresh  -->
        <div id="xieyi" class="mui-modal">
            <header class="mui-bar mui-bar-nav">
                <a class="mui-icon mui-icon-close mui-pull-right" href="#xieyi"></a>
                <h1 class="mui-title">会员注册协议</h1>
            </header>
            <div class="mui-content">
                <div class="mui-card" style="margin: 3px 3px;">
                    <div class="mui-content-padded">
                        <div style="color:#333;font-size:14px;line-height:18px;">
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本人已仔细阅读本协议全部条款，对各条款的含义已完全理解，并已明确自己的权利义务，愿意遵守本协议的各项规定。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="falv" class="mui-modal">
            <header class="mui-bar mui-bar-nav">
                <a class="mui-icon mui-icon-close mui-pull-right" href="#falv"></a>
                <h1 class="mui-title">法律声明</h1>
            </header>
            <div class="mui-content">
                <div class="mui-card" style="margin: 3px 3px;">
                    <div class="mui-content-padded">
                        <div style="color:#333;font-size:14px;line-height:18px;">
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本人承诺已经完全知悉并理解上述法律声明全部内容，且自愿认可、遵守上述法律声明全部内容。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end pullrefresh  -->

    </div>
</div>


<script type="text/javascript">

    {literal}
    var mask = null;

    function saveprofile() {
        var type = Zepto('#type').val();
        if (type == "submit") {
            var mobile = Zepto('#mobile').val();
            var verifycode = Zepto('#verifycode').val();
            var needverifycode = Zepto('#needverifycode').val();
            var password = Zepto('#password').val();
            var invitationcode = Zepto('#invitationcode').val();
            var birthdate = Zepto('#birthdate').val();
            var province = Zepto('#province').val();
            var city = Zepto('#city').val();

            var gender = "";
            Zepto('input[name=gender]').each(function (index) {
                if (Zepto(this).prop("checked")) {
                    gender = Zepto(this).val();
                }
            });

            var postdata = 'mobile=' + mobile + '&verifycode=' + verifycode + '&needverifycode=' + needverifycode + '&invitationcode=' + invitationcode + '&province=' + province + '&city=' + city + '&birthdate=' + birthdate + '&gender=' + gender + '&type=submit&m=' + Math.random();

        }
        else {
            var invitationcode = Zepto('#invitationcode').val();
            var postdata = 'invitationcode=' + invitationcode + '&type=yetactivate&m=' + Math.random();

        }
		
		Zepto("#submit_button").html('正在提交,请等待!'); 
	    Zepto("#submit_icon").removeClass("icon-save"); 
		Zepto("#submit_icon").addClass("icon-loading1");   
		var submit_type = Zepto("#submit_type").val();  
		if (submit_type != "submit") return;
		Zepto("#submit_type").val("");  
        mui.ajax({
            type: 'POST',
            url: "verifycode.php",
            data: postdata,
            success: function (json) {
                var jsondata = eval("(" + json + ")");
                if (jsondata.code == 200) {
                    window.location.href = 'userdetail.php';
                }
                else {
					Zepto("#submit_button").html('提交失败'); 
					 Zepto("#submit_icon").removeClass("icon-loading1"); 
                    Zepto('#verifycode-wrap-div').css("display", '');
                    Zepto('#verifycode-errormsg').html(jsondata.msg);
                }
            }
        });
    }
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


        mui('.mui-table-view').on('tap', 'a.orderdetail', function (e) {
            mui.openWindow({
                url: this.getAttribute('href'),
                id: 'info'
            });
        });
        mui('.mui-bar').on('tap', 'a.save', function (e) {
            var validate = Zepto('#frm').parsley('validate');
            if (validate) {
                var invitationcode = Zepto('#invitationcode').val();
                if (invitationcode == "") {
                    swal({
                        title: "提示",
                        text: "您确定没有推荐人吗？",
                        type: "warning",
                        showCancelButton: true,
                        closeOnConfirm: true,
                        confirmButtonText: "确定",
                        confirmButtonColor: "#ec6c62"
                    }, function () {
                        saveprofile();
                    });
                }
                else
                {
                     saveprofile();
                }
                //document.frm.submit();
            }
        });
        mui('.mui-table-view').on('tap', 'button#send_verifycode', function (e) {
            var validate = Zepto('#mobile').parsley('validate');
            if (validate) {
                sendverifycode(120);
            }
        });
        mui('.mui-table-view').on('change', 'input#mobile', function () {
            var validate = Zepto('#mobile').parsley('validate');
            if (validate) {
                Zepto('#send_verifycode').prop("disabled", '');
                Zepto('#verifycode-wrap').css("display", '');

            }
            else {
                Zepto('#send_verifycode').prop("disabled", 'disabled');
                Zepto('#verifycode-wrap').css("display", 'none');
            }
        });
        var pickers = {};
        mui('.mui-table-view').on('tap', 'input#birthdate', function (e) {
            var optionsJson = this.getAttribute('data-options') || '{}';
            var options = JSON.parse(optionsJson);
            var id = this.getAttribute('id');
            /*
             * 首次显示时实例化组件
             * 示例为了简洁，将 options 放在了按钮的 dom 上
             * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
             */
            pickers[id] = pickers[id] || new mui.DtPicker(options);
            pickers[id].show(function (rs) {
                /*
                 * rs.value 拼合后的 value
                 * rs.text 拼合后的 text
                 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
                 * rs.m 月，用法同年
                 * rs.d 日，用法同年
                 * rs.h 时，用法同年
                 * rs.i 分（minutes 的第二个字母），用法同年
                 */
                Zepto("#birthdate").val(rs.text);
                Zepto('#birthdate').parsley('validate');
            });
        });


        cityPicker = new mui.PopPicker({layer: 2});
        cityPicker.setData(cityData);
        mui('.mui-scroll').on('tap', 'input.citypicker', function (e) {
            var optionsJson = this.getAttribute('data-options') || '{}';
            var selectvalues = JSON.parse(optionsJson);


            for (var key in cityData) {
                if (cityData[key].text == selectvalues.province) {
                    cityPicker.pickers[0].setSelectedValue(cityData[key].value);
                    var citys = cityData[key].children;
                    cityPicker.pickers[1].setItems(citys);
                    for (var key in citys) {
                        if (citys[key].text == selectvalues.city) {
                            cityPicker.pickers[1].setSelectedValue(citys[key].value);
                            var districts = citys[key].children;
                            cityPicker.pickers[2].setItems(districts);
                            for (var key in districts) {
                                if (districts[key].text == selectvalues.district) {
                                    cityPicker.pickers[2].setSelectedValue(districts[key].value);
                                }
                            }
                        }
                    }
                }
            }

            cityPicker.show(function (items) {
                Zepto("#citypicker").val(items[0].text + ' ' + items[1].text);
                Zepto('#citypicker').parsley('validate');
                Zepto("#province").val(items[0].text);
                Zepto("#city").val(items[1].text);
            });

        });
    });
    function sendverifycode(time) {
        var newtime = time - 1;
        if (newtime == 0) {
            Zepto('#mobile').prop("disabled", '');
            Zepto('#send_verifycode').prop("disabled", '');
            Zepto("#send_verifycode").removeClass("mui-btn-danger");
            Zepto("#send_verifycode").addClass("mui-btn-success");
            Zepto('#send_verifycode').html('重新获取验证码');
        }
        else {
            if (time == 120) {
                var mobile = Zepto('#mobile').val();
                mui.ajax({
                    type: 'POST',
                    url: "verifycode.php",
                    data: 'mobile=' + mobile + '&type=send&m=' + Math.random(),
                    success: function (json) {
                        var jsondata = eval("(" + json + ")");
                        if (jsondata.code == 200) {
                            Zepto('#verifycode-wrap-div').css("display", 'none');
                            Zepto('#verifycode-errormsg').html('');
                            Zepto('#mobile').prop("disabled", 'disabled');
                            Zepto('#send_verifycode').prop("disabled", 'disabled');
                            Zepto("#send_verifycode").removeClass("mui-btn-success");
                            Zepto("#send_verifycode").addClass("mui-btn-danger");
                            Zepto('#send_verifycode').html('发送成功<span class="mui-badge mui-btn-danger">' + newtime + '</span>');
                            setTimeout('sendverifycode(' + newtime + ');', 1000);
                        }
                        else {
                            Zepto('#verifycode-wrap-div').css("display", '');
                            Zepto('#verifycode-errormsg').html(jsondata.msg);
                        }
                    }
                });
            }
            else {
                Zepto('#send_verifycode').html('发送成功<span class="mui-badge mui-btn-danger">' + newtime + '</span>');
                setTimeout('sendverifycode(' + newtime + ');', 1000);
            }
        }

    }

    {/literal}
</script>
{include file='weixin.tpl'}
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body>
</html>