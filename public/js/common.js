 
var URL = 'http://'+window.location.host;

var ua = navigator.userAgent.toLowerCase();
var sys = ua.match(/html5plus/);

if (sys != 'html5plus') {
	mui.openWindow = function openWindow(param,target,options) {
		if(param.target == '_blank'){
			window.open(param.url);
		}else{
			window.location.href = param.url;
		}
	}
}

function StringBuilder() {
    this.__strings__ = new Array();
}
StringBuilder.prototype.append = function (str) {
    this.__strings__.push(str);
    return this;    //方便链式操作
}
StringBuilder.prototype.toString = function () {
    return this.__strings__.join("");
}


mui.ready(function() {
	// 失去焦点补救
	mui('.mui-inner-wrap').on('tap','input,textarea',function(){
		this.focus();
	});

	mui('#pullrefresh').scroll();
	mui('#slider').slider({
		interval: 3000
	});
	//侧滑容器父节点
	var offCanvasWrapper = mui('#offCanvasWrapper');
	//主界面容器
	var offCanvasInner = offCanvasWrapper[0].querySelector('.mui-inner-wrap');
	//菜单容器
	var offCanvasSide = document.getElementById("offCanvasSide");
	// var mask = mui.createMask();//callback为用户点击蒙版时自动执行的回调；
	// mask.show();//显示遮罩
	// mask.close();//关闭遮罩
	if (document.getElementById('offCanvasShow'))
	{
		document.getElementById('offCanvasShow').addEventListener('tap', function() {
			//offCanvasWrapper.offCanvas('show');
			mui('.mui-off-canvas-wrap').offCanvas('show');
			Zepto('.mui-backdrop').show();
		});
	} 
	
	/*Zepto('#offCanvasShow').on('tap', function() {
		Zepto('#offCanvasShow').offCanvas('show');
		Zepto('.mui-backdrop').show();
	});*/
	
	 //主界面向右滑动，若菜单未显示，则显示菜单；否则不做任何操作；
	/*window.addEventListener("swiperight", function() {
		offCanvasWrapper.offCanvas('show');
		Zepto('.mui-backdrop').show();
	});
	 //主界面向左滑动，若菜单已显示，则关闭菜单；否则，不做任何操作；
	window.addEventListener("swipeleft", function() {
		offCanvasWrapper.offCanvas('close');
		Zepto('.mui-backdrop').hide();
	});*/

	Zepto('.mui-backdrop').on('tap',function(){
		Zepto('.mui-backdrop').hide();
		//offCanvasWrapper.offCanvas('close');
		mui('.mui-off-canvas-wrap').offCanvas('close');
	})

	mui('.mui-table-view,.user-info,.tap-a').on('tap', 'a', function(e) {
		 var href = this.getAttribute('href');
		  if (href && href != "")
		  {   
			  mui.openWindow({
								 url: this.getAttribute('href'),
								 id: 'info'
						 });
		  } 
	});
	mui('.mui-bar').on('tap','a',function(e){
		mui.openWindow({
			url: this.getAttribute('href'),
			id: 'info'
		});
	})
	 
	/*关闭弹出层*/
	mui('#popover').on('tap','.close-popover',function(){
		mui('.mui-popover').popover('toggle');
	}); 
	

});

function flyItem(flyItem) 
{  
	if (Zepto("#dcNum").html() != null)return;
    var objImg=Zepto("#"+flyItem);
    var obj='<div id="dcNum">+1</div>';
    Zepto('body').append(obj);
    Zepto('#dcNum').css({
        'z-index':9999,
        'position':'absolute',
        'top':objImg.offset().top+(objImg.width()-25)/2,
        'left':objImg.offset().left+(objImg.height()-25)/2,
        'width':'20px',
        'height':'20px',
        'border-radius':'50%',
        'line-height':'20px',
        'text-align':'center',
        'background-color':'rgba(255,0,0,.5)',
        'color':'#fff'
    });
    $('#dcNum').animate({
        top:Zepto('#shoppingcart').offset().top,
        left:Zepto('#shoppingcart').offset().left+20,
        width:25,
        height:25
    },800,function(){ 
        $(this).remove(); 
    });
} 
