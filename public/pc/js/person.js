// JavaScript Document
$(function(){	
    //��ർ��
	 $(".m-treeview > li > a").click(function(){ // ע���õ��� ��ѡ���� (  >  )
			    var $ul = $(this).siblings("ul");
				if($ul.is(":visible")){
					$(this).parent().attr("class","m-collapsed");
					$ul.hide();
				}else{
					$(this).parent().attr("class","m-expanded");
					$ul.show();
				}
	   });
	 //���б�ɫ
	  $(".donation-list dl:odd").css("background-color","#f5f5f5");
      $(".donation-list dl:even").css("background-color","#fff");
	  
	  //��ַѡ��
	  $('.addresslist').click(function(){	
      		$(this).addClass("choicebox").siblings().removeClass("choicebox");
	  });
});