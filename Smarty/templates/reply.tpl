<!DOCTYPE html>
<html> 
	<head>
		<title></title>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	    <title></title>
	    <link href="public/css/mui.css" rel="stylesheet" />
	    <link href="public/css/public.css" rel="stylesheet" /> 
	    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>   
		<style>
		{literal} 
		 .img-responsive { display: block; height: auto; width: 100%; }  
		 .mui-content-padded {  margin: 0px; }
		 p { margin: 0;  }
		{/literal}
		</style>
	    {include file='theme.tpl'} 
	</head>

	<body>
		 <div class="mui-inner-wrap">
			 {if $supplier_info.showheader eq '0'}
			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">
				<h1 class="mui-title">{$replytitle}</h1> 
			</header>
			{/if} 
	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="bottom: 50px;padding-top: {if $supplier_info.showheader eq '0'}50px;{else}5px;{/if}">
	            <div class="mui-scroll">    
						<div class="mui-content-padded">
							<p>
								 {$reply}    
							</p> 
						</div>   
			    </div>
			</div>
		</div> 
		<script type="text/javascript">
		{literal}
		    mui.init({
		        pullRefresh: {
		            container: '#pullrefresh' 
		        },
		    });
			mui.ready(function() { 
				mui('#pullrefresh').scroll(); 
			});
		{/literal}
		</script>
		<script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
	</body> 
</html>
 
