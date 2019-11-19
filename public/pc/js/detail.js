function recalc()
{
	var qty_item = $('#qty_item').val(); 
	
	var inventory = $('#inventory1').val();
	var shop_price = $('#shop_price1').val();
	var zhekou = $('#zhekou').val();
	var includepost    = $("#includepost").val();
	var postage        = $("#postage").val();
	var mergepostage   = $("#mergepostage").val();
	
	var new_qty_item = parseInt(qty_item,10); 
	if (new_qty_item < 1 || isNaN(new_qty_item))
	{
		$('#qty_item').val("1"); 
		new_qty_item = 1;
	}
	var newinventory = parseInt(inventory,10);
	var newshop_price = parseFloat(shop_price,10);
	var newzhekou = parseFloat(zhekou,10) * 0.1;

	if (newinventory > 0 )
	{ 
		if (new_qty_item > newinventory )
		{
			new_qty_item = newinventory;
			$('#qty_item').val(newinventory);
		}
		var total = new_qty_item * newshop_price;
		if (newzhekou > 0)
		{
			total = new_qty_item * newshop_price * newzhekou ;
		}

		if (parseFloat(postage, 10) > 0)
		{
			if (parseInt(mergepostage, 10) != 1)
			{
				postage = parseFloat(postage, 10) * parseInt(new_qty_item, 10);
			}
			$("#postage_span").html(parseFloat(postage, 10).toFixed(2));
			if (parseInt(includepost, 10) > 0 && parseInt(includepost, 10) <= parseInt(new_qty_item, 10))
			{
				$("#postage_panel").css('display', 'none;');
			}
			else
			{
				$("#postage_panel").css('display', 'block;');
				total = parseFloat(total, 10) + parseFloat(postage, 10);
			}
		}
		$("#totalprice").html(total.toFixed(2));
		$("#total_price1").val(total);
		$("#type").val('submit');
	}
	else
	{
		$("#totalprice").html('0.00'); 
		$("#type").val('');  
	}
}
function product_recalc(shop_price,inventory) {
	var zhekou = $('#zhekou').val();
	var newzhekou = parseFloat(zhekou,10) * 0.1;
	
	var newinventory = parseInt(inventory,10);
	var qty_item = $('#qty_item').val(); 
	var new_qty_item = parseInt(qty_item,10);
	var price = parseFloat(shop_price,10);
	var includepost    = $("#includepost").val();
	var postage        = $("#postage").val();
	var mergepostage   = $("#mergepostage").val();
	if (newinventory > 0 )
	{
		if (new_qty_item > newinventory )
		{
			new_qty_item = newinventory;
			$('#qty_item').val(newinventory);
		}
		var total = new_qty_item * price;
		if (newzhekou > 0)
		{
			total = new_qty_item * price * newzhekou;
		}
		if (parseFloat(postage, 10) > 0)
		{
			if (parseInt(mergepostage, 10) != 1)
			{
				postage = parseFloat(postage, 10) * parseInt(new_qty_item, 10);
			}
			$("#postage_span").html(parseFloat(postage, 10).toFixed(2));
			if (parseInt(includepost, 10) > 0 && parseInt(includepost, 10) <= parseInt(new_qty_item, 10))
			{
				$("#postage_panel").css('display', 'none;');
			}
			else
			{
				$("#postage_panel").css('display', 'block;');
				total = parseFloat(total, 10) + parseFloat(postage, 10);
			}
		}
		$("#totalprice").html(total.toFixed(2));
		$("#total_price1").val(total);
		$("#type").val('submit');
	} 
	else
	{
		$("#totalprice").html('0.00'); 
		$("#type").val('');  
	}
}

function change_price(propertys) 
{
    var property_type_count = $('#property_type_count').val(); 
	var propertygroup = [];
    for(var i=1;i<=property_type_count;i++)
    {
		var radio = $('input[name=propertygroup_'+i+'][checked]'); 
		if (radio)
		{
			var propertyid = radio.attr("propertyid");
			
			if (propertyid)
			{
				propertygroup.push(propertyid); 
			} 
		} 
    }  
	var propertygroupstr = propertygroup.sort().toString();  
    $.each(propertys, function(i, v) {  
		var propertyids = v.propertyids; 
		var propertyarray = propertyids.split(','); 
        if (propertygroupstr == propertyarray.sort().toString())
        {
			var zhekou = $('#zhekou').val();
			var newzhekou = parseFloat(zhekou,10) * 0.1;
			if (newzhekou > 0)
			{
				$("#old_shop_price").html(v.shop_price);
				var promotional_price = v.shop_price * newzhekou;
				$("#shop_price").html(promotional_price.toFixed(2));  
				$("#shop_price1").val(v.shop_price);
			}
			else
			{
				$("#shop_price").html(v.shop_price);
				$("#shop_price1").val(v.shop_price);
			}
			$("#productlogo").attr("src",v.imgurl);
			$("#product_property_id").val(v.propertytypeid);
			$("#market_price").html(v.market_price);
			
			$("#productlogo").html(v.imgurl); 
			$("#inventory_label").html(v.inventory); 
			$("#inventory1").val(v.inventory);
			product_recalc(v.shop_price,v.inventory)
		}
		
    });  
}


function checkshoppingcart(shoppingcarturl)
{ 
    var inventory = $('#inventory1').val();
    var newinventory = parseInt(inventory,10);
    if ( newinventory <= 0)
    {
		alert('您选择的商品已经卖完了！'); 
        return false;
    }
	if ($('#type').val() == "")
	{
        var property_type_count = $('#property_type_count').val();
        for(var i=1;i<=property_type_count;i++)
        {
			var radio = $('input[name=propertygroup_'+i+'][checked=true]');
            if( radio.val() == undefined ) 
            { 
                mui.toast('请选择商品的'+$('#propertygroup_label_'+i).val()); 
                return false;
            }
        } 
		alert('您还需要选择的商品属性！');  
		return false;
	}
	else
	{
		var qty_item = $('#qty_item').val(); 
		var productid = $('#productid').val();
		var product_property_id = $('#product_property_id').val();  
	 
	    var postbody = 'shoppingcart=1&record=' + productid + '&quantity=' + qty_item;
	    if (product_property_id != "" && product_property_id != undefined)
		{
			postbody = 'type=detail&shoppingcart=1&record=' + productid + '&quantity=' + qty_item + '&propertyid=' + product_property_id;
		}
		var salesactivityid = $('#salesactivityid').val();
		var salesactivity_product_id = $('#salesactivity_product_id').val();
		if (salesactivityid != "" && salesactivity_product_id != "")
		{
			postbody += '&salesactivityid='+salesactivityid + '&salesactivitys_product_id='+salesactivity_product_id;
		}
	     
        mui.ajax({
	            type: 'POST',
	            url: "shoppingcart_add.ajax.php",
	            data: postbody,
	            success: function(json) {   
	                var jsondata = eval("("+json+")");
	                if (jsondata.code == 200) {   
						mui.openWindow({
							url: shoppingcarturl,
							id: 'info'
						});
	                } 
					else
					{
						 mui.toast(jsondata.msg);
					}
	            }
			 }); 
	}
}
function addshoppingcart()
{ 
    var inventory = $('#inventory1').val();
    var newinventory = parseInt(inventory,10); 
    if ( newinventory <= 0)
    { 
 	    swal({
 	         title: "提示", 
 	         text: "您选择的商品已经卖完了！", 
 	         type: "error", 
 	       }, function() { });
        return false;
    }
	if ($('#type').val() == "")
	{
        var property_type_count = $('#property_type_count').val();
        for(var i=1;i<=property_type_count;i++)
        {
			var radio = $('input[name=propertygroup_'+i+'][checked=true]');
            if( radio.val() == undefined ) 
            { 
		 	    swal({
		 	         title: "提示", 
		 	         text: '请选择商品的'+$('#propertygroup_label_'+i).val(), 
		 	         type: "warning", 
		 	       }, function() { });  
                return false;
            }
        } 
 	    swal({
 	         title: "提示", 
 	         text: "您还需要选择的商品属性！", 
 	         type: "error", 
 	       }, function() { }); 
		return false;
	}
	else
	{
		var qty_item = $('#qty_item').val(); 
		var productid = $('#productid').val();
		var product_property_id = $('#product_property_id').val();  
	 
	    var postbody = 'record=' + productid + '&quantity=' + qty_item;
	    if (product_property_id != "" && product_property_id != undefined)
		{
			postbody = 'type=detail&record=' + productid + '&quantity=' + qty_item + '&propertyid=' + product_property_id;
		}
		var salesactivityid = $('#salesactivityid').val();
		var salesactivity_product_id = $('#salesactivity_product_id').val();
		if (salesactivityid != "" && salesactivity_product_id != "")
		{
			postbody += '&salesactivityid='+salesactivityid + '&salesactivitys_product_id='+salesactivity_product_id;
		}
	    jQuery.post("shoppingcart_add.ajax.php", postbody,
		function (json, textStatus) { 
            var jsondata = eval("("+json+")");
            if (jsondata.code == 200) {   
				 //mui.toast(jsondata.msg); 
				 flyItem("addshoppingcart"); 
				 $('#shoppingcart_header').html(jsondata.shoppingcart);  
				 $('#shoppingcart_rightbar').html(jsondata.shoppingcart);  
            } 
			else
			{
		 	    swal({
		 	         title: "提示", 
		 	         text: jsondata.msg, 
		 	         type: "error", 
		 	       }, function() { }); 
				 
			}
		}); 
	}
}

function addcollectionicon()
{
  var productid = $('#productid').val();
  var status = $('#mycollection').val();
  if (status == "0")
  {
		var postbody = 'record=' + productid + '&status=1';
	    jQuery.post("mycollection_add.ajax.php", postbody,
		function (json, textStatus) {  
			  $('#mycollection').val("1"); 
			  $('#addcollectionicon').html("取消收藏");  
			  flyCollectioniconItem("addcollectionicon");
		}); 
  }
  else
  {
	  	var postbody = 'record=' + productid + '&status=0';
	      jQuery.post("mycollection_add.ajax.php", postbody,
	  	function (json, textStatus) {  
	  		  $('#mycollection').val("0"); 
			  $('#addcollectionicon').html("收藏"); 
	  	});   
  }
}

function flyCollectioniconItem(flyItem) 
{ 
	if ($("#dcNum").html() != null)return;
    var objImg=$("#"+flyItem);
    var obj='<span id="flycollectioniconitem" class="iconfont icon-collection"></span>';
    $('body').append(obj);
    $('#flycollectioniconitem').css({
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
    $('#flycollectioniconitem').animate({
        top:$('#icon-collection').offset().top,
        left:$('#icon-collection').offset().left+20,
        width:25,
        height:25
    },1200,function(){ 
        $(this).remove(); 
    });
} 