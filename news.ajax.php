<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
}


if(isset($_REQUEST['page']) && $_REQUEST['page'] !='')
{
	$page = $_REQUEST['page'];  
}
else
{
	echo '{"code":201,"data":[]}';
	die(); 
}
 
 
 

try{
	global $noofrows; 
	$supplier_news = supplier_news($supplierid,$page);
	if (count($supplier_news) > 0)
	{
		echo '{"code":200,"length":'.$noofrows.',"data":'.json_encode($supplier_news).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":200,"length":0,"data":[]}'; 
die();  
 

function  supplier_news($supplierid,$page)
{ 
	global $noofrows; 
	$query = XN_Query::create ( 'Content' ) ->tag("supplier_news_".$supplierid)	 
				->filter('type','eic','supplier_news')
				->filter ('my.supplierid', '=', $supplierid) 
				->filter ('my.approvalstatus', '=', '2')  
				->filter ('my.status', '=', '0') 
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*5)
				->end($page*5);
	$supplier_news = $query->execute();
	$noofrows = $query->getTotalCount();  
	$data = array();
	$key = 1;
	if (count($supplier_news) > 0)
	{  
		foreach($supplier_news as $supplier_new_info)
		{ 
			$published = $supplier_new_info->published;
			$published = date("Y-m-d H:i",strtotime($published));
			$description = $supplier_new_info->my->description;  
			$description = cn_substr_utf8($description,160); 
			
			$data[$key]['id'] = $supplier_new_info->id;   
			$data[$key]['articletitle'] = $supplier_new_info->my->articletitle;  
			$data[$key]['articleauthor'] = $supplier_new_info->my->articleauthor;  
			$data[$key]['description'] = $description;  
			$data[$key]['articletype'] = $supplier_new_info->my->articletype;  
			$data[$key]['articletext'] = $supplier_new_info->my->articletext;  
			$data[$key]['image'] = $supplier_new_info->my->image;  
			$data[$key]['published'] = $published;  
			$key ++;
		     
		} 
	}  
    return $data; 
}
 
?>