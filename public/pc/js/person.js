// JavaScript Document
$(function(){	
    //左侧导航
	 $(".m-treeview > li > a").click(function(){ // 注意用的是 子选择器 (  >  )
			    var $ul = $(this).siblings("ul");
				if($ul.is(":visible")){
					$(this).parent().attr("class","m-collapsed");
					$ul.hide();
				}else{
					$(this).parent().attr("class","m-expanded");
					$ul.show();
				}
	   });
	 //隔行变色
	  $(".donation-list dl:odd").css("background-color","#f5f5f5");
      $(".donation-list dl:even").css("background-color","#fff");
	  
	  //地址选择
	  $('.addresslist').click(function(){	
      		$(this).addClass("choicebox").siblings().removeClass("choicebox");
	  });
});