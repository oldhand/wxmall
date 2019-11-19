<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");
require_once (dirname(__FILE__) . "/index.func.php");

global $loginprofileid; 
if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
	$loginprofileid = $_SESSION['profileid']; 
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
	$loginprofileid = $_SESSION['accessprofileid']; 
}
else
{
	$loginprofileid = "anonymous";
}

 

if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '{"code":201,"data":[]}';
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
 
if(isset($_SESSION['width']) && $_SESSION['width'] !='')
{
	$width = $_SESSION['width']; 
}
else
{
	$width = "320";
	$_SESSION['width'] = $width;  
}

try{
	$products = mall_products($page,$supplierid);
	if (count($products) > 0)
	{
		echo '{"code":200,"data":'.json_encode($products).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":202,"data":[]}'; 
die();  
  
 
?>