<?php
/*+**********************************************************************************
 * The contents of this file are subject to the 361CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  361CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
function get_host_domain()
{
  $url = $_SERVER['HTTP_HOST']; 
  $data = explode('.', $url);
  $data = $data[count($data) - 2] . '.' . $data[count($data) - 1]; 
  return $data;
}
 

if(isset($_REQUEST['appid']) && $_REQUEST['appid'] !='' &&
   isset($_REQUEST['code']) && $_REQUEST['code'] !='' && 
   isset($_REQUEST['type']) && $_REQUEST['type'] == 'init')
{
	$code = $_REQUEST["code"];  
	$appid = $_REQUEST["appid"];
	$domain = get_host_domain();
	
		
	$redirect_uri = sprintf('http://o2o.'.$domain.'/index.php?type=init&code=%s&appid=%s',$code,$appid); 
	header("Location:".$redirect_uri); 
	die(); 
	 
}   



?>