// JavaScript Document
$(function(){	
  //---------头部JS区
  //头部下拉菜单 
  var oLi = $('.m1');
  //设置ul的left值
  for(var i=0; i<oLi.length; i++){	
  	 oLi.eq(i).next().css('left',( oLi.eq(i).parent().outerWidth()-100)/2 + 'px');
	 if(oLi.eq(i).has('i')){	
	    oLi.eq(i).next().css('left',( oLi.eq(i).parent().outerWidth()-100)/2 - 9 + 'px');
	 }
  }

  $('.m1').parent().mouseover(function(){	
  		$(this).addClass("active").siblings().removeClass("active");		
  }).mouseout(function(){	
  		$(this).removeClass("active");	
  });
  
  //输入框的提示文字隐藏与显示
    $(".text").focus(function(){
		  if($(this).val() ==this.defaultValue){  
			  $(this).val("");  
			  $(this).css('color','#333');
		  } 
	}).blur(function(){
		 $(this).removeClass("focus");
		 if ($(this).val() == '') {
			$(this).val(this.defaultValue);
			$(this).css('color','#ccc');
		 }
	});
  
  //头部区域 右侧工具栏JS效果
  var oSubmenu = $('.toolbar-submenu');
  for(var i=0; i<oSubmenu.length; i++){	//设置位置
  	 oSubmenu.eq(i).css('left',((oSubmenu.eq(i).parent().outerWidth()-oSubmenu.eq(i).outerWidth())/2-4)+'px');	
  }
  
  $('.m2').parent().mouseover(function(){	
  		$(this).addClass("active").siblings().removeClass("active");		
  }).mouseout(function(){	
  		$(this).removeClass("active");	
  });
  
  //商品分类
  var timer = null; var timer1 = null;
  $('.category-menu').mouseover(function(){	
       var $Menu = $(this).next('.category-menu-nav');
  		$(this).find('h3').addClass('active');
		clearTimeout(timer1);
		$Menu.show();
  }).mouseout(function(){	
  		$(this).find('h3').removeClass('active');
		var $Menu = $(this).next('.category-menu-nav');
		timer1 = setTimeout(function(){	
			$Menu.hide();	
		},100);
  });
  
  $('.m3').mouseover(function(){	
  	    var $Menu = $(this).next();
		clearTimeout(timer);
		$('.leftsubmenu').hide();
 		$Menu.show();
		
  }).mouseout(function(){	
  		var $Menu = $(this).next();
		timer = setTimeout(function(){	
			$Menu.hide();	
		},100);
  });
  
  $('.leftsubmenu').mouseover(function(){
		clearTimeout(timer);
		$(this).show();
  }).mouseout(function(){	
 		var $this = $(this);
		timer = setTimeout(function(){	
			$this.hide();	
		},100);
  });
  //--------------------头部JS效果结束
  //左侧商品分类的下拉菜单 
   var timer2 = null;
  $('.btn-grout-icon').mouseover(function(){	
  	    var $Menu = $(this).next();
		clearTimeout(timer2);
		$('.leftsubmenu').hide();
 		$Menu.show();
		
  }).mouseout(function(){	
  		var $Menu = $(this).next();
		timer2 = setTimeout(function(){	
			$Menu.hide();	
		},100);
  });
  
  $('.btn-group-left .leftsubmenu').mouseover(function(){
		clearTimeout(timer2);
		$(this).show();
  }).mouseout(function(){	
 		var $this = $(this);
		timer2 = setTimeout(function(){	
			$this.hide();	
		},100);
  });   
  
  var screenW=$(window).width();
  var imgH=Number(screenW/13.49).toFixed(1);
  $('.menu-bg-img').css({height:imgH}); 
  
  $("img.lazy").lazyload(); 
  
});


//右侧边JS
function rightBar(obj,obj2){	
	var oH = $(window).height();
	var oDiv =$(obj).height();
	
	$(obj).css('top',(oH - oDiv)/2+'px');
	
	$(obj).parent().animate({right: '0px'},{queue:true,duration:500});
	
	$(obj).find('li').hover(function(){
		$(obj2, this).css('display','block').stop().animate({right:'40px',opacity:100},{queue:false,duration:500});
	}, function() {
		$(obj2, this).stop().animate({right:'100px',opacity:0},'slow',function(){																
			$(this).hide();
		});
	});
	
	$('#rightclose').click(function(){
		$(obj).css('overflow','hidden');
		$(obj).animate({height: '0px'},'slow',function(){	
			$(obj).parent().animate({right: '-40px'},{queue:true,duration:500});
		});
		return false;
	});
}

//滚动事件
function gd(obj){
	var now=0,timer=null;
	var oUl = $(obj).find('ul');
	oUl.html(oUl.html()+oUl.html());
	var length=oUl.find('li').length;
	var width=length*277;//277图片宽加上5px的margin
	oUl.width(width);
	$(obj).find('.prev').click(function(){
		now++;
		now=now%(length/4);
		var left=-now*277*4;
		oUl.stop().animate({left:left+'px'});
	});
	
	$(obj).find('.next').click(function(){
		if(now<=0)
		{
			now=length/8;
			oUl.css({left:-width/2+'px'});
		}
		now--;
		var left=-now*277*4;
		oUl.stop().animate({left:left+'px'});
	});
	
	timer=setInterval(function(){
		now++;
		var left=-now*277*4;
		oUl.stop().animate({left:left+'px'},function(){
			if(oUl.position().left<=width/2)
			{
				now=0;
				oUl.css({left:0+'px'});
			}
		});
	},4000);
	
	$('.box').hover(function(){
		clearInterval(timer);
	},function(){
		timer=setInterval(function(){
			now++;
			var left=-now*277*4;
			$('.boxul').stop().animate({left:left+'px'},function(){
				if($('.boxul').position().left<=width/2)
				{
					now=0;
					$('.boxul').css({left:0+'px'});
				}
			});
		},4000);
	});
}

//选项卡
function oTab(obj,oDiv){
   obj.eq(0).addClass("active") ;
   oDiv.eq(0).show();
   obj.click(function(){
			$(this).addClass("active")            
				   .siblings().removeClass("active");
            var index = obj.index(this); 
			oDiv.eq(index).show()   
					.siblings().hide();
		    return false;
	});
  
}

//右侧边辅助导航
function oRight(oH){	
	if($('.btn-group-left').length > 0)
	{
		var oL = $('#warp .container').offset().left-60;
		var iT = ($(window).height()-$('.btn-group-left').height())/2;
		$('.btn-group-left').css({left:oL+'0px',top:iT+'px'});
	
		$(window).resize(function(){setOr();});
		$(window.document).scroll(function () {	
			if($(window.document).scrollTop()>=$('#left-bar-cont').offset().top){
				setOr();
				$('.btn-group-left').show();
				if($(window.document).scrollTop()>=$('#left-bar-cont1').offset().top+60){	
				   $('.btn-group-left').css({'position':'fixed','top':iT+'px'});
				}else{	
				   var oT = $('#left-bar-cont1').offset().top;
				   $('.btn-group-left').css({'position':'absolute','top':oT+oH+'px'});
				}
			    
			}else{	
				$('.btn-group-left').hide();
			}
		
			if($(window.document).scrollTop()> 0 ){	
				$('#righttop').show();
			}else{	
				$('#righttop').hide();
			}
		});
	}
	else
	{ 
		$(window.document).scroll(function () {	  
			if($(window.document).scrollTop()> 0 ){	
				$('#righttop').show();
			}else{	
				$('#righttop').hide();
			}
		});
	}  
	function setOr(){	
		var oL = $('#warp .container').offset().left - 60;
		$('.btn-group-left').css({left:oL+'px'});
	}
}

function oLeftbur(){	
	$('.btn-group-left li').hover(function(){
		$('.grout-inner', this).stop().animate({top: '-38px'},{queue:false,duration:300});
	}, function() {
		$('.grout-inner', this).stop().animate({top:'0px'},{queue:false,duration:300});
	});
	
	$('#btn-day').click(function(){
		var oH = $('#Day').offset().top;
		$('html,body').animate({ 
                scrollTop : oH+'px' 
		}, 400);  
	});
	$('#btn-hot').click(function(){
		var oH = $('#hot').offset().top;
		$('html,body').animate({ 
                scrollTop : oH+'px' 
		}, 400);  
	});
	$('#btn-life').click(function(){
		var oH = $('#life').offset().top;
		$('html,body').animate({ 
                scrollTop : oH+'px' 
		}, 400);  
	});
	
	$('#righttop').click(function(){	
		$('html,body').animate({ 
                scrollTop : 0+'px' 
		}, 400); 						  
	
	});
}

//设置弹出层
function setOH(obj,obj2,num){	
	$(obj).hover(function(){
		$(obj2, this).stop().animate({height: num+'px'},{queue:false,duration:300});
	}, function() {
		$(obj2, this).stop().animate({height:'0px'},{queue:false,duration:300});
	});
}
//点赞
function oZan(){	
	var $zan = $('div.zan');
	  for(var i=0; i<$zan.length; i++){	
	  	 $zan.attr('Onoff',0);
	  }
	  
	  $zan.click(function(){
		  if($(this).attr('Onoff') == 1) return;
		  
		  $(this).attr('Onoff',1);
		  var num = parseInt($(this).next().text());
		  $(this).next().text(num+1);
	      var oSpan = $("<span class='iconfont icon-tezan red' style='display:inline-block;margin-top:-3px;font-size: 1.5em;'></span>");
		  $(this).html(oSpan);
		  
		  $(".icon-zan", this).animate({top:'-30px',left:'10px',opacity:0},'fast',function(){	
		      	$(this).remove();
		  });
	 });
}

//天天精选弹出
function oJx(){	
	$('.daydaylist-body li').hover(function(){
	$(this).addClass('active');
	var _this = $(this);
	$(".pro-name", this).stop().animate({height:'90px'},"fast",function(){	
		_this.find('.pro-name p').animate({opacity:1},200).css({flter:"Alpha(Opacity=100)"});
	});
},function() {
	var _this = $(this);
	 _this.find('.pro-name p').animate({opacity:0},200).css({flter:"Alpha(Opacity=0)"});
	$(".pro-name", this).stop().animate({height:'0px'},"fast",function(){	
	   _this.removeClass('active');
	});
});
}


//评论区选项卡
function oPl(){	
	var $oDiv_li =$(".Overview-review-tit li");
	   $oDiv_li.eq(0).addClass("active") ;
	   $("div.Overview-review-nr > div").eq(0).show();
	   $oDiv_li.click(function(){
				$(this).addClass("active")            
					   .siblings().removeClass("active");
				var index = $oDiv_li.index(this); 
				$("div.Overview-review-nr > div")   	
						.eq(index).show()   
						.siblings().hide();
				return false;
		});
}
//弹出层
function Oalert(obj,oparent){
		 cssHw();
		 function cssHw(){
		     $('#zhezhao').css({'width':$(window).width()+'px', 'height':$(document).height()+'px'});
		}
		//点击显示
		 
		obj.click(function(){
			$('#zhezhao').show();
			oparent.show();
		});
		//关闭
		$('#close,.closealert').click(function(){	
			$('#zhezhao').hide();
			oparent.hide();
		});
}
//登录页面的主体内容在页面居中
function oSet(num){	
	var aH = ($(window).height()-$('.head').outerHeight()-$('.foot').outerHeight() -$('.loginbox').outerHeight())/2;

	if($(window).height() < 770){	
		var oDiv = $('.findKey-flow-box');
		if(oDiv.length>0){	
			$('.loginbox').css('padding-top', '170px').css('padding-bottom','80px');
		}else{	
			$('.loginbox').css('padding-top', '50px').css('padding-bottom','50px');
		}
		
	}else{	
		if(num){
			 $('.loginbox').css('padding-top',aH-30 + 'px').css('padding-bottom',aH + 30 + 'px');
		}else{	
			$('.loginbox').css('padding-top',aH + 'px').css('padding-bottom',aH  + 'px');
		}
	}
}

function flyItem(flyItem) 
{ 
	if ($("#dcNum").html() != null)return;
    var objImg=$("#"+flyItem);
    var obj='<div id="dcNum">+1</div>';
    $('body').append(obj);
    $('#dcNum').css({
        'z-index':9999,
        'position':'absolute',
        'top':objImg.offset().top+10,
        'left':objImg.offset().left+50,
        'width':'20px',
        'height':'20px',
        'border-radius':'50%',
        'line-height':'20px',
        'text-align':'center',
        'background-color':'rgba(255,0,0,.5)',
        'color':'#fff'
    });
    $('#dcNum').animate({
        top:$('#shoppingcart').offset().top,
        left:$('#shoppingcart').offset().left+20,
        width:25,
        height:25
    },1200,function(){ 
        $(this).remove(); 
    });
} 

function delete_shoppingcart(shoppingcartid)
{ 
	if($('#shoppingcart_'+shoppingcartid).length > 0)
	{ 
		$('#shoppingcart_'+shoppingcartid).remove();
	    jQuery.post("shoppingcart_delete.ajax.php", 'record=' + shoppingcartid, function (json, textStatus) {});
	}
}
