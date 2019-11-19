<!DOCTYPE html>
	 <html> 
	 <head>
	     <meta charset="utf-8">
	     <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	     <title>餐厅预订</title>
	    <link href="public/css/mui.css" rel="stylesheet" />
	    <link href="public/css/public.css" rel="stylesheet" />
		<link href="public/css/iconfont.css" rel="stylesheet" />  
		<link href="public/css/mui.picker.css" rel="stylesheet" />
		<link href="public/css/mui.listpicker.css" rel="stylesheet" />
		<link href="public/css/mui.dtpicker.css" rel="stylesheet" /> 
	    <link href="public/css/mui.poppicker.css" rel="stylesheet" />
		<link href="public/css/sweetalert.css" rel="stylesheet" /> 
		<link href="public/css/parsley.css" rel="stylesheet" >   
		
	    <script src="public/js/mui.min.js" type="text/javascript" charset="utf-8"></script>  
		<script type="text/javascript" src="public/js/jweixin.js"></script> 
		<script src="public/js/zepto.js" type="text/javascript" charset="utf-8"></script>
		<script src="public/js/mui.picker.js"></script>
		<script src="public/js/mui.listpicker.js"></script>
		<script src="public/js/mui.dtpicker.js"></script>  
		<script src="public/js/mui.poppicker.js" type="text/javascript" charset="utf-8"></script>
		<script src="public/js/utils.js" type="text/javascript" charset="utf-8"></script>  
		<script type="text/javascript" src="public/js/sweetalert.min.js"></script> 
	    <script src="public/js/parsley.min.js"></script>   
	    <script src="public/js/parsley.zh_cn.js"></script>		
		
		
	 	<style>
	 	  {literal} 
	 		 .img-responsive { display: block; height: auto; width: 100%; }  
			 
	 	   	 .mui-bar .tit-sortbar{
	 	   	 	left: 0;
	 	   	 	right: 0;
	 	   	 	margin-top: 45px; 
	 	   	 }
	 		 .mui-bar .mui-segmented-control {
	 		   top: 0px; 
	 		 }
	 		 .mui-segmented-control.mui-segmented-control-inverted .mui-control-item {
	 		   color: #333; 
	 		 } 
	 	 	 .price {
	 	 	  color:#fe4401;
	 	 	 } 
	 	  	 .mui-table-view-cell .mui-table-view-label
	 	  	 {
	 	  	    width:60px;
	 	  		text-align:right;
	 	  		display:inline-block;
	 	  	 } 
		 	.mui-bar-tab .mui-tab-item .mui-icon {
		 	  width: auto;
		 	} 
		  	.mui-bar-tab .mui-tab-item, .mui-bar-tab .mui-tab-item.mui-active {
		  	  color: #cc3300; 
		  	} 
		 	 
		 	.mui-input-row label { 
		 	  text-align: right; 
		 	  width: 30%;
		 	}  
		 	.mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea {
		 	  float: right;
		 	  width: 70%; 
		 	}
		 	.mui-input-row label { 
		 	  line-height: 21px; 
		 	  padding: 10px 10px;
		 	} 
		 	.mui-input-clear
		 	{
		 		font-size: 12px;
		 	}
		 	.mui-listpicker ul li {
		 	  overflow: hidden;
		 	  white-space:nowrap; 
		 	}  
	
		 	input.parsley-success,
		 	select.parsley-success,
		 	textarea.parsley-success {
		 	  color: #468847;
		 	  background-color: #DFF0D8;
		 	  border: 1px solid #D6E9C6;
		 	}

		 	input.parsley-error,
		 	select.parsley-error,
		 	textarea.parsley-error {
		 	  color: #B94A48;
		 	  background-color: #F2DEDE;
		 	  border: 1px solid #EED3D7;
		 	}

		 	.parsley-errors-list {
		 	  margin: 2px 0 3px;
		 	  padding: 0;
		 	  list-style-type: none;
		 	  font-size: 0.9em;
		 	  line-height: 0.9em;
		 	  opacity: 0;

		 	  transition: all .3s ease-in;
		 	  -o-transition: all .3s ease-in;
		 	  -moz-transition: all .3s ease-in;
		 	  -webkit-transition: all .3s ease-in;
		 	}

		 	.parsley-errors-list.filled {
		 	  opacity: 1;
		 	}
		
	 	 {/literal} 
	 	</style>
	{include file='theme.tpl'} 
	 </head>
	 <body>  
	 <div class="mui-off-canvas-wrap mui-draggable" id="offCanvasWrapper"> 
	 		<div class="mui-inner-wrap">
	 			<header class="mui-bar mui-bar-nav" style="padding-right: 15px;">  
	 				 <a class="mui-icon mui-action-back mui-icon-back mui-pull-left"></a> 
	 				 <h1 class="mui-title">餐厅预订</h1>
	                  <div class="mui-title mui-content tit-sortbar" id="sortbar"> 
	 		 				<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-negative">
	 		 					<a class="mui-control-item mui-active" href="reserve.php">餐厅预订</a>
	 		 					<a class="mui-control-item" href="reserveofme.php">我的预订记录</a> 
	 		 				</div> 
	                  </div>
	 			</header> 
				<nav class="mui-bar mui-bar-tab" style="height:40px;line-height:40px;">  
					{if $reserves_info.mall_reservesstatus neq 'JustCreated' &&  $reserves_info.id neq '' }
						<a class="mui-tab-item" href="reserveofme.php">  
							<span class="mui-icon iconfont icon-queren01">&nbsp;<span style="font-size:20px;">确认</span></span>
						</a> 
					{else}
						<a class="mui-tab-item save" href="#">  
							<span class="mui-icon iconfont icon-save">&nbsp;<span style="font-size:20px;">保存</span></span>
						</a>
					{/if}
					
				</nav>   
	 	        <div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="padding-top: 85px;">  
	                     <div class="mui-scroll">   
	    		                 <div id="list" class="mui-table-view" >     
	 		 		 							<ul class="mui-table-view" style="padding-top: 5px;padding-bottom: 5px;">
													 <form class="mui-input-group" name="frm" id="frm" method="post" action="reserveofme.php"  parsley-validate>
									 					<input name="record" type="hidden" value="{$reserves_info.id}">
														<input name="type" type="hidden" value="submit">
														<div class="mui-input-row">
									 						<label><span>联系人</span>:</label>
									 						<input required="required" id="consignee" value="{$reserves_info.consignee}" name="consignee" type="text" class="mui-input-clear required" maxlength="20" placeholder="您的真实姓名">
									 					</div>
									 					<div class="mui-input-row">
									 						<label>手机号码:</label>
									 						<input required="required" parsley-rangelength="[11,11]" id="mobile" name="mobile" value="{$reserves_info.mobile}" type="number" class="mui-input-clear required" maxlength="11" placeholder="11位手机号" parsley-error-message="请输入正确的手机号码">
									 					</div>
									 					<div class="mui-input-row">
									 						<label>选择就餐时间:</label>
															<input id="reservetime"  value="{$reserves_info.reservetime}" data-options='{ldelim}"value":"{$reserves_info.reservetime}"{rdelim}' readonly name="reservetime" type="text" class="mui-input-clear required" maxlength="20" placeholder="请选择日期时间"> 
									 					</div> 
									 					<div class="mui-input-row">
									 						<label>选择就餐信息:</label>  
															<input id="numberofpeople" name="numberofpeople" type="hidden" value="{$reserves_info.numberofpeople}">
															<input id="deskofpeople" name="deskofpeople" type="hidden" value="{$reserves_info.deskofpeople}">
															<input id="place" name="place" type="hidden" value="{$reserves_info.place}">
									 						<input required="required" id="infopicker" data-options='{ldelim}"place":"{$reserves_info.place}","deskofpeople":"{$reserves_info.deskofpeople}","numberofpeople":"{$reserves_info.numberofpeople}"{rdelim}' value="{if $reserves_info.place_info neq ''}{$reserves_info.place_info} {$reserves_info.deskofpeople}桌 {$reserves_info.numberofpeople}人{/if}" type="text" readonly class="mui-input-clear infopicker required" placeholder="请选择人数与位置要求">
									 					</div> 
									 					<div class="mui-input-row" style="height:auto;">
									 						<label style="height:65px;">留言:</label>
															<textarea  class="mui-input-clear" placeholder="请具明您的特殊要求信息" id="memo" name="memo" rows="2" >{$reserves_info.memo}</textarea>
						 			 					</div> 
												     </form>
	 		 		 							</ul> 
	 											<ul class="mui-table-view" style="background-color: #efeff4;">
	 												<li class="mui-table-view-cell mui-media"> 
	 														<img class="img-responsive" src="/images/baozhang.png"> 
	 												</li>
	 											</ul> 
	 						 </div>    
	                  </div>
	 			</div>
	 	    </div> 
	  </div>  
	      
	 	<script type="text/javascript"> 
	 	{literal}	 
	 	    mui.init({
	 	        pullRefresh: {
	 	            container: '#pullrefresh', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等  
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
	 			mui('.mui-table-view').on('tap','a',function(e){
	 				mui.openWindow({
	 					url: this.getAttribute('href'),
	 					id: 'info'
	 				});
	 			});
				var pickers = {};
				mui('.mui-table-view').on('tap','input#reservetime',function(e){
					var optionsJson = this.getAttribute('data-options') || '{}';
					var options = JSON.parse(optionsJson);
					var id = this.getAttribute('id');
					/*
					 * 首次显示时实例化组件
					 * 示例为了简洁，将 options 放在了按钮的 dom 上
					 * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
					 */
					pickers[id] = pickers[id] || new mui.DtPicker(options);
					pickers[id].show(function(rs) {
						/*
						 * rs.value 拼合后的 value
						 * rs.text 拼合后的 text
						 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
						 * rs.m 月，用法同年
						 * rs.d 日，用法同年
						 * rs.h 时，用法同年
						 * rs.i 分（minutes 的第二个字母），用法同年
						 */ 
						Zepto("#reservetime").val(rs.text);
						Zepto('#reservetime' ).parsley( 'validate' );
					});
				});
				var infoPicker = new mui.PopPicker({ layer: 3}); 
				var numberofpeopleData = [{value: "1",text: "1人" },{value: "2",text: "2人" },{value: "3",text: "3人" },
						               {value: "4",text: "4人" },{value: "5",text: "5人" },{value: "6",text: "6人" },
								       {value: "7",text: "7人" },{value: "8",text: "8人" },{value: "9",text: "9人" },
						 			   {value: "10",text: "10人" },{value: "11",text: "11人" },{value: "12",text: "12人" },
						               {value: "13",text: "13人" },{value: "14",text: "14人" },{value: "15",text: "15人" },
								       {value: "16",text: "16人" },{value: "17",text: "17人" },{value: "18",text: "18人" },
									   {value: "19",text: "19人" },{value: "20",text: "20人" },{value: "20+",text: "20人以上" }];
				var deskData = [{value: "1",text: "1桌",children: numberofpeopleData},{value: "2",text: "2桌",children: numberofpeopleData },
								{value: "3",text: "3桌",children: numberofpeopleData },{value: "4",text: "4桌",children: numberofpeopleData },
								{value: "5",text: "5桌",children: numberofpeopleData },{value: "6",text: "6桌",children: numberofpeopleData },
						        {value: "7",text: "7桌",children: numberofpeopleData },{value: "8",text: "8桌",children: numberofpeopleData },
								{value: "9",text: "9桌",children: numberofpeopleData }, {value: "10",text: "10桌",children: numberofpeopleData },
								{value: "10+",text: "10桌以上",children: numberofpeopleData }];
				var infoData = [
					       {value: 'room',
							text: '包房',
							children: deskData
						   },
					       {value: 'hall',
							text: '大厅',
							children: deskData
						   }];
				infoPicker.setData(infoData);
				
				mui('.mui-scroll').on('tap','input.infopicker',function(e){ 
					var optionsJson = this.getAttribute('data-options') || '{}'; 
					var selectvalues = JSON.parse(optionsJson); 
					
					if (infoPicker.pickers[0].getSelectedIndex() == 0)
					{
						infoPicker.pickers[0].setSelectedValue(selectvalues.place);
					} 
					if (infoPicker.pickers[1].getSelectedIndex() == 0)
					{
						infoPicker.pickers[1].setSelectedValue(selectvalues.deskofpeople);
					} 
					if (infoPicker.pickers[2].getSelectedIndex() == 0)
					{
						infoPicker.pickers[2].setSelectedValue(selectvalues.numberofpeople);
					} 
					
					infoPicker.show(function(items) {  
							Zepto("#place").val(items[0].value );
							Zepto("#deskofpeople").val(items[1].value );
							Zepto("#numberofpeople").val(items[2].value );
							
							Zepto("#infopicker").val(items[0].text + ' ' + items[1].text + ' ' + items[2].text );
							Zepto('#infopicker' ).parsley( 'validate' ); 
					});
				});
				mui('.mui-bar').on('tap','a.save',function(e){ 
					var validate = Zepto( '#frm' ).parsley( 'validate' );
					if (validate)
					{
						document.frm.submit();
					} 
				}); 
	 	   }); 
	 	{/literal} 
	 	</script>
	 {include file='weixin.tpl'} 
	 <script src="/public/js/baidu.js?_=20140821" type="text/javascript"></script>
	 </body> 
	 </html>