<?php

 
if(isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] !='' &&
   isset($_REQUEST['profileid']) && $_REQUEST['profileid'] !='' &&
   isset($_REQUEST['token']) && $_REQUEST['token'] !='')
{
	$token = $_REQUEST['token'];  
	$supplierid = $_REQUEST['supplierid'];  
	$profileid = $_REQUEST['profileid'];  
	try
	{ 
		  $wwn_token = XN_MemCache::get("wwn_token_".$supplierid."_".$profileid);
		  if ($token == $wwn_token)
		  {
		  	  session_start();
		      unset($_SESSION['accessprofileid']);
		      $_SESSION['profileid'] = $profileid;
		      $_SESSION['supplierid'] = $supplierid;
		      $_SESSION['u'] = $profileid;
              $supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
                  ->filter('type', 'eic', 'supplier_wxsettings')
                  ->filter('my.deleted', '=', '0')
                  ->filter('my.supplierid', '=', $supplierid)
                  ->end(1)
                  ->execute();
              if (count($supplier_wxsettings) > 0)
              {
                  $supplier_wxsetting_info = $supplier_wxsettings[0];
                  $appid = $supplier_wxsetting_info->my->appid;
				  $_SESSION['appid'] = $appid;
			  }
			 
		      header("Location: index.php" );
			  die();
		  }
		  else
		  {
	  	    messagebox('错误','威微牛商城传递Token参数错误！');
	  	    die();
		  }
	}
	catch (XN_Exception $e) 
	{ 
	    messagebox('错误','威微牛商城传递Token参数异常！');
	    die();
	} 
}
else
{
    messagebox('错误','威微牛商城传递参数异常！');
    die();
}
 



?>