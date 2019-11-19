<?php

session_start(); 
	
require_once (dirname(__FILE__) . "/config.error.php");  	


if(isset($_GET['profileid']) && $_GET['profileid'] !='' &&
	isset($_GET['supplierid']) && $_GET['supplierid'] !='' &&
	isset($_GET['token']) && $_GET['token'] !='')
{
	try 
	{  
		$profileid = $_GET['profileid'];
		$supplierid = $_GET['supplierid'];
		$token = $_GET['token'];
		
		try
		{ 
			   $takecash_token = XN_MemCache::get("goto_supplier_".$profileid);
			   XN_MemCache::put("","goto_supplier_".$profileid,"120"); 
			   if ($takecash_token == $token)
			   { 
				    $_SESSION['supplierid'] = $supplierid;
					$_SESSION['profileid'] = $profileid;
					$_SESSION['u'] = $profileid;
					unset($_SESSION['accessprofileid']); 
					header("Location: index.php");
					die(); 
			   }
			   messagebox('错误',"token已经过期！",'http://mall.tezan.cn/home.php',5);
			   die();
		}
		catch (XN_Exception $e) 
		{ 
			 messagebox('错误',"token已经过期！",'http://mall.tezan.cn/home.php',5);  
			 die(); 		     
		}   
		 
	}
	catch ( XN_Exception $e ) 
	{ 
		errorprint('错误',$e->getMessage());	  
		die();
	}
}
else
{
	errorprint('错误','系统禁止的调用!');	 
}