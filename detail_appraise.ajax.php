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
 
if(isset($_REQUEST['productid']) && $_REQUEST['productid'] !='')
{
	$productid = $_REQUEST['productid'];  
} 

try{
	global $count;
	$count = 0; 
	$appraises = mall_appraises($supplierid,$page,$productid);
	if (count($appraises) > 0)
	{
		echo '{"code":200,"length":'.$count.',"data":'.json_encode($appraises).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":202,"data":[]}'; 
die();  
  
function  appraise_images($images) 
{
	global $APISERVERADDRESS;
	$images = (array)$images;
	$newimages = array();
	foreach($images as $image_info)
	{ 
		if (isset($image_info) && $image_info != "")
		{
			$width = 320;
			$image_info = $APISERVERADDRESS.$image_info."?width=".$width;
			$newimages[] = $image_info;
		}
	}
	return $newimages;
}
	
function  mall_appraises($supplierid,$page,$productid) 
{ 
	global $count;
	$query = XN_Query::create ( 'YearContent' )->tag('mall_appraises_'.$supplierid)
				->filter ( 'type', 'eic', 'mall_appraises') 
				//  	
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.productid', '=', $productid)  
				->order("published",XN_Order::DESC)
				->begin(($page-1)*10)
				->end($page*10);
	$mall_appraises	= $query->execute(); 
	$count = $query->getTotalCount();
	$appraises = array(); 
	$profileids = array();
	$key = 1;
	foreach($mall_appraises as $mall_appraise_info)
	{
		$profileids[] = $mall_appraise_info->my->profileid; 
		$appraises[$key]['profileid'] = $mall_appraise_info->my->profileid; 
		$appraises[$key]['remark'] = $mall_appraise_info->my->remark; 
		$appraises[$key]['hasimages'] = intval($mall_appraise_info->my->hasimages);  
		$appraises[$key]['published'] = date("Y-m-d H:i",strtotime($mall_appraise_info->published)); 
		$images = $mall_appraise_info->my->images;
		$appraises[$key]['images'] = appraise_images($images);
		$praise = $mall_appraise_info->my->praise;
		$appraises[$key]['praise'] = $praise;
		if ($praise == '1')
		{
			$appraises[$key]['praise_info'] = '好评';
		}
		else if ($praise == '2')
		{
			$appraises[$key]['praise_info'] = '中评';
		}
		else if ($praise == '3')
		{
			$appraises[$key]['praise_info'] = '差评';
		}
		else
		{
			$appraises[$key]['praise_info'] = '好评';
		} 
		$key = $key + 1;
	}
	$profiles = getProfileInfoArrByids($profileids);

	foreach($appraises as $key => $appraise_info)
	{
		$profileid = $appraise_info['profileid'];
		if(isset($profiles[$profileid]) && $profiles[$profileid] != "")
		{
			$profile_info = $profiles[$profileid];
		
			$appraises[$key]['givenname'] = $profile_info['givenname'];
			$appraises[$key]['headimgurl'] = $profile_info['headimgurl'];
		}  
	} 
    return $appraises; 
}

function replace_star($string) 
{
    $count = mb_strlen($string, 'UTF-8'); //此处传入编码，建议使用utf-8。此处编码要与下面mb_substr()所使用的一致
    if (!$count) {
        return $string;
    }
	$start = 1;
	$end = $count;
	if ($count > 2)
	{
		$end = $count-1;
	} 
	if ($count > 7)
	{
		$start = 2;
		$end = $count-2;
	}  

    $i = 0;
    $returnString = '';
    while ($i < $count) {
        $tmpString = mb_substr($string, $i, 1, 'UTF-8'); // 与mb_strlen编码一致
        if ($start <= $i && $i < $end) {
            $returnString .= '*';
        } else {
            $returnString .= $tmpString;
        }
        $i ++;
    }
    return $returnString;
}
function getProfileInfoArrByids($ids)
{      
	if (count($ids) == 0) return array();
    $infos=XN_Profile::loadMany($ids,"id","profile");
    $givenNames=array();
    foreach($infos as $info){
        $givenname = $info->givenname;

        if ($givenname == "")
        {
            $fullName = $info->fullName;

            if(preg_match('.[#].', $fullName))
            {
                $fullNames = explode('#', $fullName);
                $fullName = $fullNames[0];
            }
            $givenname = $fullName;
        }
		$givenname = replace_star($givenname);
		$headimgurl = $info->link; 
		if ($headimgurl == "")
		{
			$headimgurl = 'images/user.jpg';
		}  
        $givenNames[$info->profileid]=array('givenname'=>$givenname,'headimgurl'=>$headimgurl);
    } 
    return $givenNames;
}


 
 
?>