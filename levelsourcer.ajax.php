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

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
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

if(isset($_REQUEST['type']) && $_REQUEST['type'] !='')
{
	$type = $_REQUEST['type'];
}
else
{
	$type = 'onelevelsourcer';
}

 
 

try{
	$profiles = mall_profiles($supplierid,$profileid,$page,$type);
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
 

function  mall_profiles($supplierid,$profileid,$page,$type)
{

	$query = XN_Query::create ( 'Content' ) ->tag("supplier_profile")
				->filter('type', 'eic', 'supplier_profile')
				->filter('my.deleted', '=', '0')
				->filter('my.supplierid', '=', $supplierid)
				->filter('my.'.$type, '=', $profileid)
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*5)
				->end($page*5);
	$supplier_profiles = $query->execute();
	$noofrows = $query->getTotalCount();  
	$profilelist = array();
	if (count($supplier_profiles) > 0)
	{  
		foreach($supplier_profiles as $supplier_profile_info)
		{
			$profile_id = $supplier_profile_info->my->profileid;

			$profile_info = XN_Profile::load($profile_id,"id","profile_".$profileid);

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