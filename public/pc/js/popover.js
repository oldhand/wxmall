/*
 * e.g: $('对象1').pointOut({'msgText':'提示信息','inputCtr':'对象2','btnText':'交互事件','toSrc':'链接'});
 * 		对象1：弹出框将会显示在此对象的后面，
 * 		对象2：监听这个对象，具体看76行用法，此对象也可以为空，当他为空时，他默认对象1
 */
// 弹出提示框
(function($,window){
	var defaults = {
		body : $('body'),				// body容器
		inputCtr : undefined,			// 文本输入框
		msgText : undefined,			// 提示的内容
		btnText : undefined,			// 事件交互的文本信息
		toSrc : undefined				// 跳转的链接
	};
	$.fn.pointOut = function(options){
		var $this = $(this),
			opts = $.extend({},defaults,options),
			o = undefined,
			html = 	"<div class='pointOutBox'>"+
						"<div class='pointOutArrow pointOutArrow_top'></div>"+
						"<div class='pointOutMsg'></div>"+
					"</div>";
		if(opts.msgText === undefined) return false;
		return this.each(function(){
			o = $.meta ? $.extend({}, opts, $this.data()) : opts;
			if(o.inputCtr === undefined){
				o.inputCtr = $this;
			}else if(typeof(o.inputCtr) !== 'object'){
				o.inputCtr = $(o.inputCtr);
			}

			var styleHtml = "";
			if(o.body.find('.pointOutBox').length <= 0){
				o.body.append(html);
				$('.pointOutBox').css({
					'position':'absolute',
					'display':'none',
					'zIndex':'1100',
					'border':'1px solid rgba(255,0,0,0.3)',
					'border-radius':'3px',
					'max-width':'300px',
					'word-wrap':'break-word',
					'color':'#ff0000'
				});
				$('.pointOutMsg').css({'padding':'9px 14px','white-space':'normal'});
				styleHtml = ".pointOutArrow:before,.pointOutArrow:after{"+
								"content: ''; position: absolute; left: -15px;"+
								"border-top: 8px solid transparent; border-right: 8px solid #fff;"+
								"border-bottom: 8px solid transparent; border-left: 8px solid transparent;"+
							"}"+
							".pointOutArrow:before{"+
								"border-right-color:rgba(255,0,0,0.3); left:-17px;"+
							"}";
				$('style').append(styleHtml);
			}

			$('.pointOutBox').fadeIn();
			$('.pointOutBox>.pointOutMsg').empty().text(o.msgText);
			if(o.btnText !== undefined){
				o.toSrc === undefined ? o.toSrc = 'javascript:void(0)' : o.toSrc = o.toSrc;
				$('.pointOutBox>.pointOutMsg').append("&nbsp;<a class='btnText' href='"+o.toSrc+"' style='color:#333;'>"+o.btnText+"</a>");
			}
			$('.pointOutBox').css({
				'left': $this.offset().left + $this.outerWidth(true) + 10 + 'px',					
				'top': $this.offset().top + ($this.outerHeight(true) - $('.pointOutBox>.pointOutMsg').outerHeight(true)) / 2 + 'px'
			});

			styleHtml = ".pointOutArrow_top:before,.pointOutArrow_top:after{"+
							"top:"+($('.pointOutMsg').outerHeight(true)-16)/2+"px;"+
						"}";
			// var abc = "#getCode{ background-color: #f3f3f3; color: #ccc; height: 40px; line-height: 40px; width: 90px; float: right; text-align: center; padding: 0px; margin-left: 20px; cursor: pointer; }#protocol{ clear:both; width:100%; margin-bottom: 0px; }#protocol img{ float: left; }#protocol p{ float: left; margin-left: 10px; }.pointOutArrow:before,.pointOutArrow:after{content: ''; position: absolute; left: -15px;border-top: 8px solid transparent; border-right: 8px solid #fff;border-bottom: 8px solid transparent; border-left: 8px solid transparent;}.pointOutArrow:before{border-right-color:rgba(255,0,0,0.3); left:-17px;}";
			// console.log('data:'+abc.replace(/\#getCode\{[^\}]+\}/,'#getCode{ 2121 }'));
			// var objHTML = $.trim($('style')[0].innerHTML);
			// objHTML.replace(/\.pointOutArrow_top:before,.pointOutArrow_top:after\{[^\}]+\}/, '.pointOutArrow_top:before,.pointOutArrow_top:after{ top: '+($('.pointOutMsg').outerHeight(true)-16)/2+'px; }')
			$('style').append(styleHtml);

			// 监听文本输入
			o.inputCtr.bind('input propertychange',function(){
				$('.pointOutBox').fadeOut();
			    // var len = this.value.length;
			    // this.setSelectionRange(len,len);
			});

			// hide弹出提示框
			o.body.on('click',function(e){
				var t = e.target || e.srcElement;
				if(t.className === 'pointOutMsg' || t.className === 'btnText'){
					$('.pointOutBox').fadeOut();
				}
			});
		});
	};
})(jQuery,window);