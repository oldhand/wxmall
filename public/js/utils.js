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