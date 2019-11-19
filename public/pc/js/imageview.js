// JavaScript Document
$(function(){
	$('.poto-sma li').click(function(){
		var oLi = $(this).parent('.poto-sma').find('li');
		for(var i=0; i<oLi.length; i++){	
			oLi.eq(i).attr('index',i);
		}
		var oBigimg = $(this).parent().parent().find('.poto-big-img li');
		var _index = $(this).attr('index');
		oBigimg.eq(_index).show().siblings().hide(); 
	});
});