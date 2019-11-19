<!DOCTYPE html>
   <html> 
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
       <meta name="renderer" content="webkit">
	   <meta name="HandheldFriendly" content="true">
	   <meta name="msapplication-tap-highlight" content="no"> 
	   
       <title>商家咨询</title>
       <link href="public/css/mui.css" rel="stylesheet" />
       <link href="public/css/public.css" rel="stylesheet" />
   	   <link href="public/css/iconfont.css" rel="stylesheet" />  
	   <link href="public/css/mui.imageviewer.css" rel="stylesheet" /> 
   	   <link href="public/css/webim.css" rel="stylesheet" />   
	   {include file='theme.tpl'} 
   </head>
<bodycontextmenu="return false;">   
<audio src=""  id="myaudio" autoplay="autoplay" preload="none"></audio>
<div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper">
	 {include file='leftmenu.tpl'} 
	 <div class="mui-inner-wrap"> 
		 {if $supplier_info.showheader eq '0'}
		<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
			 <a id="offCanvasShow" href="#offCanvasSide" class="mui-icon mui-action-menu mui-icon-bars mui-pull-left"></a>
			 <h1 class="mui-title">商家咨询</h1> 
		</header>  
		{/if} 
		<nav class="mui-bar mui-bar-tab">
			<footer>
				<div class="footer-left">
					<i id='msg-image' class="mui-icon mui-icon-camera" style="font-size: 28px;"></i>
				</div>
				<div class="footer-center">
					<textarea id='msg-text' type="text" class='input-text'></textarea>
					<div id='msg-sound'  class='input-sound' style="display: none;">按住说话</div>
				</div>
				<label for="" class="footer-right">
					<i id='msg-type' class="mui-icon mui-icon-mic"></i>
				</label>
			</footer>
		</nav>  
        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
                   <div class="mui-scroll">  
						 <input id="yearmonth" value="{$yearmonth}" type="hidden" >
						 <input id="max_messageid" value="0" type="hidden" >
						 <input id="min_messageid" value="0" type="hidden" >
  		                 <div id="list" class="mui-table-view" >     
								<div class="mui-content">
									<div id='msg-list'>
										 
									</div>
								</div> 
					    </div>    
                </div>
		</div>
	</div>
</div>
<div id='sound-alert' class="rprogress">
	<div class="rschedule"></div>
	<div class="r-sigh">!</div>
	<div id="audio_tips" class="rsalert">手指上滑，取消发送</div>
</div>
<script src="public/js/mui.min.js"></script>
<script src="public/js/zepto.min.js"></script> 
<script src="public/js/common.js"></script>
<script src="public/js/mui.zoom.js"></script>
<script src="public/js/mui.previewimage.js"></script> 
<script src="public/js/mui.imageViewer.js"></script>
<script src="public/js/arttmpl.js"></script> 	     
<script src="public/js/jweixin.js"></script>    
	      
<script type="text/javascript"> 
{literal}	 
 mui.init({
     pullRefresh: {
         container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等  
		 down: { 
			contentdown: '下拉可以加载更多咨询信息',
			contentover: '释放立即加载',
			contentrefresh: '正在加载更多咨询信息...',
			callback: pulldownRefresh
		 },
     },
 });
mui.ready(function() {  
	mui('#pullrefresh').scroll();
	mui('.mui-bar').on('tap','a',function(e){
		mui.openWindow({
			url: this.getAttribute('href'),
			id: 'info'
		});
	});  
	 
	mui.previewImage();
	
	document.getElementById('msg-text').addEventListener('input', function() {
		var msg = $("#msg-text").val();
		if (msg == "")
		{
			$("#msg-type").removeClass("mui-icon-paperplane");  
			$("#msg-type").addClass("mui-icon-mic");  
		}
		else
		{
			$("#msg-type").removeClass("mui-icon-mic");  
			$("#msg-type").addClass("mui-icon-paperplane");  
		} 
	});
	
	
	var setSoundAlertVisable=function(show){
		if(show){
			$("#sound-alert").css("display","block");
			$("#sound-alert").css("opacity","1");
		}else{
			$("#sound-alert").css("opacity","0");
			//fadeOut 完成再真正隐藏
			setTimeout(function(){
				$("#sound-alert").css("display","none");
			},200);
		}
	};
	
	var recordCancel = false;
	var recorder = null;
	var audio_tips = document.getElementById("audio_tips");
	var startTimestamp = null;
	var stopTimestamp = null;
	var stopTimer = null;
	var MIN_SOUND_TIME = 800;
	
	document.getElementById('msg-sound').addEventListener('hold', function(event) {
		recordCancel = false;
		if(stopTimer)clearTimeout(stopTimer);
		audio_tips.innerHTML = "手指上划，取消发送";
		$("#sound-alert").removeClass('rprogress-sigh');
		setSoundAlertVisable(true);
		startTimestamp = (new Date()).getTime();
		wx.startRecord();
	}, false);
	
	document.getElementById('msg-sound').addEventListener('release', function(event) {
		//console.log('release');
		if ($("#audio_tips").hasClass("cancel"))
		{
			$("#audio_tips").removeClass("cancel"); 
			$("#audio_tips").html("手指上划，取消发送");
		} 
		
		stopTimestamp = (new Date()).getTime();
		if (stopTimestamp - startTimestamp < MIN_SOUND_TIME) {
			$("#audio_tips").html("录音时间太短");
			$("#sound-alert").addClass('rprogress-sigh');
			recordCancel = true;
			stopTimer=setTimeout(function(){
				setSoundAlertVisable(false);
			},800);
		}else{
			setSoundAlertVisable(false);
		}
		wx.stopRecord({
		    success: function (res) {
		        var localId = res.localId;
		        if (!recordCancel)
		        {
			         wx.uploadVoice({
						    localId: localId, // 需要上传的音频的本地ID，由stopRecord接口获得
						    isShowProgressTips: 1, // 默认为1，显示进度提示
						        success: function (res) {
						        var serverId = res.serverId; // 返回音频的服务器端ID
						        sendsoundmessage(serverId);
						    }
						}); 
			    } 
		    }
		});
	}, false);
	
	document.body.addEventListener('drag', function(event) {
		//console.log('drag');
		if (Math.abs(event.detail.deltaY) > 50) {
			if (!recordCancel) {
				recordCancel = true;
				if (!$("#audio_tips").hasClass("cancel"))
				{
					$("#audio_tips").addClass("cancel");
				}
				audio_tips.innerHTML = "松开手指，取消发送";
			}
		} else {
			if (recordCancel) {
				recordCancel = false;
				if ($("#audio_tips").hasClass("cancel"))
				{
					$("#audio_tips").removeClass("cancel");
				}
				audio_tips.innerHTML = "手指上划，取消发送";
			}
		}
	}, false);
	
	mui('.mui-bar').on('tap','label.footer-right',function(){
		if ($("#msg-type").hasClass("mui-icon-paperplane"))
		{ 
			sendmessage($("#msg-text").val());
			$("#msg-text").val(''); 
			$("#msg-type").removeClass("mui-icon-paperplane");  
			$("#msg-type").addClass("mui-icon-mic");   
		}
		else if ($("#msg-type").hasClass("mui-icon-mic"))
		{
			$("#msg-type").removeClass("mui-icon-mic"); 
			$("#msg-type").addClass("mui-icon-compose"); 
			$("#msg-sound").css("display","");
			$("#msg-text").css("display","none");
		}
		else
		{
			$("#msg-type").removeClass("mui-icon-compose"); 
			$("#msg-type").addClass("mui-icon-mic"); 
			$("#msg-sound").css("display","none");
			$("#msg-text").css("display","");
		} 
		 
	}); 
	
	mui('.mui-bar').on('tap','div.footer-left',function(){  
	   	 wx.chooseImage({
	   	     count: 1, // 默认9
	   	     sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
	   	     sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
	   	     success: function (res) {
	   	            localids = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片  
	   	            for(var i=0;i<localids.length;i++)
					{
						// alert(localids[i]);
						 wx.uploadImage({
					           localId: localids[i], // 需要上传的图片的本地ID，由chooseImage接口获得
					           isShowProgressTips: 1, // 默认为1，显示进度提示
					           success: function (res) {
					             var serverId = res.serverId; // 返回图片的服务器端ID 
								 sendimagemessage(serverId) ; 
					           },
					           fail:function(res){   }
					         }); 
						  
					}
					
	   	     }
	   	 }); 
	}); 

	mui('.mui-table-view').on('tap','div.sound',function(){  
	   	var sound = this.getAttribute('msg-content'); 
	   	var myAuto = document.getElementById('myaudio');   
		myAuto.src = sound;  
	}); 
	 
	
}); 

function sendsoundmessage(sound) 
{  
    mui.ajax({
        type: 'POST',
        url: "webim.ajax.php",
        data: 'type=addsound&sound=' + sound,
        success: function(json) {  
                get_newmessage(1); 
        }
    }); 
	_hmt.push(['_setAutoPageview', false]);
	_hmt.push(['_trackPageview', "/webim_sendsound.php"]); 
} 


function sendimagemessage(image) 
{  
    mui.ajax({
        type: 'POST',
        url: "webim.ajax.php",
        data: 'type=addimage&image=' + image,
        success: function(json) {  
                get_newmessage(1); 
        }
    }); 
	_hmt.push(['_setAutoPageview', false]);
	_hmt.push(['_trackPageview', "/webim_sendimage.php"]); 
} 


function message_html(v) 
{	 
	var sb=new StringBuilder();   
if (v.source == 0)		
{
	sb.append('<div id="message_'+v.messageid+'" class="msg-item msg-item-self" msg-type="'+v.msgtype+'" msg-content="'+v.message+'">');
	sb.append('	  <i class="msg-user mui-icon mui-icon-contact"></i>'); 
}
else
{
	sb.append('<div id="message_'+v.messageid+'" class="msg-item" msg-type="'+v.msgtype+'" msg-content="'+v.message+'">');
	sb.append('	  <img class="msg-user-img" src="images/kf.jpg" alt="客服" />'); 
}
if (v.msgtype == 'sound')
{
	sb.append('	  <div class="msg-content sound" msg-content="'+v.message+'">'); 
}
else
{
	sb.append('	  <div class="msg-content">'); 
}
	
if (v.msgtype == 'text')
{
	sb.append('		<div class="msg-content-inner">'+v.message+'</div>'); 
}
else if (v.msgtype == 'image')
{
	sb.append('		<div class="msg-content-inner">'); 
	sb.append('			<img class="msg-content-image" src="'+v.message+'" style="max-width: 100px;" data-preview-src="" data-preview-group="messages" />');
	sb.append('		</div>');
}
else if (v.msgtype == 'sound')
{
	sb.append('		<div class="msg-content-inner">');
	sb.append('		<span class="mui-icon mui-icon-mic" style="font-size: 18px;font-weight: bold;"></span>'); 
	sb.append('		<span class="play-state">点击播放</span>'); 
	sb.append('		</div>');  
}
else
{
	sb.append('		<div class="msg-content-inner">'+v.message+'</div>'); 
}
	
	sb.append('		<div class="msg-content-arrow"></div>'); 
	sb.append('	  </div>'); 
	sb.append('	  <div class="mui-item-clear"></div>'); 
    sb.append('</div>');
 
	return sb.toString(); 
}
		 
		 
function message_empty_html() 
{
	var sb=new StringBuilder();  
	sb.append('<div class="mui-content-padded">'); 
	sb.append('				   		  <p class="tishi"><span class="mui-icon iconfont icon-tishi"></span></p>'); 
	sb.append('					      <p class="msgbody">您的已付款订单还是空的，快去选购商品吧<br>'); 
	sb.append('							  <a href="index.php">>>>&nbsp;去逛逛</a> '); 
	sb.append('						  </p>  '); 
	sb.append(' </div>'); 
	return sb.toString(); 
}
		 
function get_newmessage(immed) 
{
	var max_messageid = $("#max_messageid").val();
    mui.ajax({
        type: 'POST',
        url: "webim.ajax.php",
        data: 'type=newmessage&maxmessageid=' + max_messageid,
        success: function(json) {  
            var jsondata = eval("("+json+")");
            if (jsondata.code == 200) 
			{    
 
                    Zepto.each(jsondata.data, function(i, v) {   
	                        if ($("message_"+v.messageid).html() == undefined)
	                        {
		                      	var nd = message_html(v); 
							  	Zepto('#msg-list').append(nd);
		                    }
						     
                    });  
					//var scroll_height = 92;//Zepto(".mui-scroll").height() / 2; 
					//Zepto(".mui-scroll").css("-webkit-transform","translate3d(0px, -"+scroll_height+"px, 0px) translateZ(0px)"); 
		  
				if (jsondata.maxmessageid != null && jsondata.maxmessageid != "")
				{
					 $('#max_messageid').val(jsondata.maxmessageid);  
				}
				if (jsondata.minmessageid != null && jsondata.minmessageid != "")
				{
					 $('#min_messageid').val(jsondata.minmessageid);  
				}
				if (jsondata.yearmonth != null && jsondata.yearmonth != "")
				{
					 $('#yearmonth').val(jsondata.yearmonth);  
				}
            }  
			if (immed == 0)
			{
				setTimeout("get_newmessage(0);" , 30000);
			} 
        }
    }); 
}
function sendmessage(msgtext) 
{  
    mui.ajax({
        type: 'POST',
        url: "webim.ajax.php",
        data: 'type=addmsgtext&msgtext=' + msgtext,
        success: function(json) {  
               get_newmessage(1);	 
        }
    }); 
	_hmt.push(['_setAutoPageview', false]);
	_hmt.push(['_trackPageview', "/webim_sendmessage.php"]); 
} 
/**
 * 下拉刷新具体业务实现
 */
function pulldownRefresh() { 
	setTimeout(function() { 
        Zepto('#page').val(1);
        //Zepto('.list').html(''); 
        add_more();
		mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed  
	}, 1000);
}

function add_more() {
	var yearmonth = Zepto('#yearmonth').val();
    var minmessageid = $("#min_messageid").val(); 
        mui.ajax({
            type: 'POST',
            url: "webim.ajax.php",
            data: 'type=history&yearmonth=' + yearmonth + '&minmessageid=' + minmessageid,
            success: function(json) {  
                var jsondata = eval("("+json+")");
                if (jsondata.code == 200) {   
                    Zepto.each(jsondata.data, function(i, v) {    
						    var nd = message_html(v); 
                            Zepto('#msg-list').prepend(nd);  
                    });   
					if (jsondata.yearmonth != null && jsondata.yearmonth != "")
					{
						 $('#yearmonth').val(jsondata.yearmonth);  
					}
					if (jsondata.minmessageid != null && jsondata.minmessageid != "")
					{
						 $('#min_messageid').val(jsondata.minmessageid);  
					} 
                }  
            }
        }); 
    } 
setTimeout("get_newmessage(0);" , 100);
{/literal} 
</script>


<script type="text/javascript">
wx.config(
    {ldelim}
        //debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{$share_info.appid}', // 必填，公众号的唯一标识
        timestamp:'{$share_info.timestamp}', // 必填，生成签名的时间戳
        nonceStr: '{$share_info.noncestr}', // 必填，生成签名的随机串
        signature: '{$share_info.signature}',// 必填，签名，见附录1
        jsApiList: ['chooseImage','uploadImage','startRecord','stopRecord','uploadVoice'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    {rdelim}); 

{literal}		  
wx.ready(function()
{ 
	 wx.hideOptionMenu();  
});
{/literal} 
</script>
<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
</body> 
</html>