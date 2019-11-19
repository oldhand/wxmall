// JavaScript Document
$(function(){	
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
      
	  //点赞
	  var $zan = $('div.zan');
	  for(var i=0; i<$zan.length; i++){	
	  	 $zan.attr('Onoff',0);
	  }
	  
	  $zan.click(function(){
		  if($(this).attr('Onoff') == 1) return;
		  
		  $(this).attr('Onoff',1);
		  var num = parseInt($(this).next().text());
		  $(this).next().text(num+1);
	      var oSpan = $("<span class='headbg icon-zan-red'></span>");
		  $(this).append(oSpan);
		  
		  $(".icon-zan", this).animate({top:'-30px',left:'10px',opacity:0},'fast',function(){	
		      	$(this).remove();
		  });
	 });
	  
	  //本地生活优惠券列表页
	  $('.cardbox-body li').hover(function(){
			$(this).addClass('active');
			var _this = $(this);
			$(".pro-name", this).stop().animate({height:'150px'},"fast",function(){	
			    _this.find('.pro-name p').animate({opacity:1},200).css({flter:"Alpha(Opacity=100)"});
			});
		},function() {
			var _this = $(this);
			 _this.find('.pro-name p').animate({opacity:0},200).css({flter:"Alpha(Opacity=0)"});
			$(".pro-name", this).stop().animate({height:'0px'},"fast",function(){	
			   _this.removeClass('active');
			});
		});
	  
	  setOH('.day-hot-body li','.pro-js',70);
	  setOH('.day-hot-list','.pro-js',70);
	  setOH('.lifebox-list li','.life-js-txt',50);
	  setOH('.Recent-body ul li','.imgbox p',25);
	  
	   
	   //产品详情页评论区选项卡
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
	  
});
//设置高度
function setOH(obj,obj2,num){	
	$(obj).hover(function(){
		$(obj2, this).stop().animate({height: num+'px'},{queue:false,duration:300});
	}, function() {
		$(obj2, this).stop().animate({height:'0px'},{queue:false,duration:300});
	});
}

