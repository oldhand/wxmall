<script type="text/javascript"> 
{if $supplier_info.allowshare eq '1'}
wx.config(
{ldelim}
   // debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '{$share_info.appid}', // 必填，公众号的唯一标识
    timestamp: {$share_info.timestamp}, // 必填，生成签名的时间戳
    nonceStr: '{$share_info.noncestr}', // 必填，生成签名的随机串
    signature: '{$share_info.signature}',// 必填，签名，见附录1
    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
{rdelim});   
wx.ready(function(){ldelim}   
     wx.hideOptionMenu(); 
{rdelim}); 	  
{else}
wx.config(
{ldelim}
   // debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '{$share_info.appid}', // 必填，公众号的唯一标识
    timestamp: {$share_info.timestamp}, // 必填，生成签名的时间戳
    nonceStr: '{$share_info.noncestr}', // 必填，生成签名的随机串
    signature: '{$share_info.signature}',// 必填，签名，见附录1
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
{rdelim}); 
 
wx.error(function(res)
{ldelim}
    wx.hideOptionMenu(); 
{rdelim}); 

 

wx.ready(function(){ldelim}   
      wx.showOptionMenu(); 
	  wx.onMenuShareTimeline( 
	    {ldelim}
		    title: '{$share_info.share_title}', // 分享标题
		    link: '{$share_info.share_url}', // 分享链接
		    imgUrl: '{$share_info.share_logo}', // 分享图标
		    success: function ()  
			{ldelim}
		        // 用户确认分享后执行的回调函数 
			 $.post("checkshare.php", "sharepage={$actionname}", function (data, textStatus) {ldelim} {rdelim});
		    {rdelim},
		    cancel: function () 
			{ldelim}
		        // 用户取消分享后执行的回调函数
		    {rdelim}
	  {rdelim});

	  wx.onMenuShareAppMessage( 
	      {ldelim}
		    desc: '{$share_info.share_description}', // 分享描述
	  	    title: '{$share_info.share_title}', // 分享标题
	  	    link: '{$share_info.share_url}', // 分享链接
	  	    imgUrl: '{$share_info.share_logo}', // 分享图标
	  	    success: function ()  
	  		{ldelim}
	  	        // 用户确认分享后执行的回调函数
			    $.post("checkshare.php", "sharepage={$actionname}", function (data, textStatus) {ldelim} {rdelim});
	    	{rdelim},
	  	    cancel: function () 
	  		{ldelim}
	  	        // 用户取消分享后执行的回调函数
	  	    {rdelim}
	    {rdelim});  
		
  	  wx.onMenuShareQQ( 
  	      {ldelim}
  		    desc: '{$share_info.share_description}', // 分享描述
  	  	    title: '{$share_info.share_title}', // 分享标题
  	  	    link: '{$share_info.share_url}', // 分享链接
  	  	    imgUrl: '{$share_info.share_logo}', // 分享图标
  	  	    success: function ()  
  	  		{ldelim}
  	  	        // 用户确认分享后执行的回调函数
  	    	    {rdelim},
  	  	    cancel: function () 
  	  		{ldelim}
  	  	        // 用户取消分享后执行的回调函数
  	  	    {rdelim}
  	    {rdelim}); 
		
  	  wx.onMenuShareWeibo( 
  	      {ldelim}
  		    desc: '{$share_info.share_description}', // 分享描述
  	  	    title: '{$share_info.share_title}', // 分享标题
  	  	    link: '{$share_info.share_url}', // 分享链接
  	  	    imgUrl: '{$share_info.share_logo}', // 分享图标
  	  	    success: function ()  
  	  		{ldelim}
  	  	        // 用户确认分享后执行的回调函数
  	    	    {rdelim},
  	  	    cancel: function () 
  	  		{ldelim}
  	  	        // 用户取消分享后执行的回调函数
  	  	    {rdelim}
  	    {rdelim});   
{rdelim}); 
{/if}
 </script>