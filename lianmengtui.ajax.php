<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
	$profileid = $_SESSION['profileid']; 
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
	$profileid = $_SESSION['accessprofileid']; 
}
else
{
	$profileid = "anonymous";
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
	$profiles = mall_profiles($profileid,$page);
	if (count($profiles) > 0)
	{
		echo '{"code":200,"data":'.json_encode($profiles).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":200,"data":[]}'; 
die();  
 

function  mall_profiles($profileid,$page)
{ 
	$query = XN_Query::create ( 'Profile' ) ->tag("profile_".$profileid)	 
				->filter('type','=','wxuser')
				->filter ('sourcer', '=', $profileid) 
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*5)
				->end($page*5);
	$profiles = $query->execute();
	$noofrows = $query->getTotalCount();  
	$profilelist = array();
	if (count($profiles) > 0)
	{  
		foreach($profiles as $profile_info)
		{
			$profile_id = $profile_info->profileid;
			$headimgurl = $profile_info->link;
			$givenname = strip_tags($profile_info->givenname);
			if ($headimgurl == "")
			{
				$headimgurl = 'images/ranks/new.png';
			}     
			$profilelist[$profile_id]['profileid'] = $profile_id;  
			$profilelist[$profile_id]['mobile'] = get_hidden_mobile($profile_info->mobile);
			$profilelist[$profile_id]['identitycard'] = $profile_info->identitycard;  
			$profilelist[$profile_id]['birthdate'] = $profile_info->birthdate; 
			$profilelist[$profile_id]['gender'] = $profile_info->gender; 
			$profilelist[$profile_id]['headimgurl'] = $headimgurl; 
			$profilelist[$profile_id]['givenname'] = $givenname; 
			$profilelist[$profile_id]['invitationcode'] = $profile_info->invitationcode;
			$profilelist[$profile_id]['sourcer'] = $profile_info->sourcer;
			$profilelist[$profile_id]['province'] = $profile_info->province; 
			$profilelist[$profile_id]['city'] = $profile_info->city; 
		     
		} 
	}  
    return $profilelist; 
}
 
?>