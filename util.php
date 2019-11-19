<?php
	
	
require_once (dirname(__FILE__) . "/config.error.php");	


if (!function_exists('checkisweixin')) { 
	function checkisweixin()
	{
		$useragent = $_SERVER["HTTP_USER_AGENT"];
	
		if( preg_match( "|(MicroMessenger[^;^)^(]*)|i", $useragent))
		{
			return true;
		} 
		return false; 
	}
}

function check_frozenlist($profileid)
{
	$frozenlists = XN_Query::create ( 'MainContent' )->tag('supplier_frozenlists')
						->filter ( 'type', 'eic', 'supplier_frozenlists' )
						->filter (  'my.deleted', '=', '0' ) 
						->filter (  'my.profileid', '=', $profileid ) 
						->filter (  'my.frozenliststatus','=', 'Frozen' ) 
						->execute ();
	if(count($frozenlists) > 0) 
	{
		return true;
	}
	return false;
}


function getAreaPicklists($fieldname,$parentnode) {
	$query = XN_Query::create ( 'SimpleContent' ) ->tag('picklists')
		->filter ('type','eic','picklists')
		->filter ('my.name','=',$fieldname)
		->filter('my.parentnode','eic',$parentnode)
		->order ('my.sequence',XN_Order::ASC_NUMBER)
		->begin(0)->end(-1)
		->execute();
	foreach($query as $info){
		$arr[] = $info->my->$fieldname;
	}
	return $arr;
}
	

	


	
function getProfileSharebonus($rank)
{
	if ($rank < 0) $rank = 0;
    try{
        $profilerankconfig=XN_MemCache::get("profilerank_".XN_Application::$CURRENT_URL);
        foreach($profilerankconfig as $profilerank_info)
        {
            $sharebonus = $profilerank_info['sharebonus'];
            $minrank = $profilerank_info['minrank'];
            if ($rank >= $minrank)
                return $sharebonus;
        }
        $profilerank_info = $profilerankconfig[0];
        return $profilerank_info['sharebonus'];
    }
    catch(XN_Exception $e){
        return 0;
    } 
}	
	
function  checkrecommend() {
	global $WX_DOMAIN;  
	
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
	
	$share_title = '特赞商城';
	$share_description = '特赞商城，每天为您从百万微店中精选最新鲜的好商品，帮您发现特赞的微店，还有各种有奖活动，秒杀，粉丝专享折扣福利，天天有惊喜！特赞商城，让您购物更方便！';
	$share_logo = "";
	
	if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
	{
		$supplierid = $_SESSION['supplierid']; 
	    try{  
				$shoppingcart = 0; 
				if ($loginprofileid == "anonymous")
				{
					$shoppingcarts = XN_Query::create ( 'YearContent_Count' )->tag('mall_shoppingcarts_'.$loginprofileid )
									->filter ( 'type', 'eic', 'mall_shoppingcarts' )
									  	
									->filter (  'my.supplierid', '=',$supplierid )  
									->filter (  'my.sessionid', '=',session_id())  
									->filter (  'my.deleted', '=','0')
									->rollup('my.quantity')
									->end(-1)
									->execute ();
				}
				else
				{
					$shoppingcarts = XN_Query::create ( 'YearContent_Count' )->tag('mall_shoppingcarts_'.$loginprofileid )
									->filter ( 'type', 'eic', 'mall_shoppingcarts' )
									  	
									->filter (  'my.supplierid', '=',$supplierid )   	
									->filter (  'my.profileid', '=',$loginprofileid)  
									->filter (  'my.deleted', '=','0')
									->rollup('my.quantity')
									->end(-1)
									->execute ();
				} 
	
				if (count($shoppingcarts) > 0)
				{
					$shoppingcart_info = $shoppingcarts[0];
					$shoppingcart = $shoppingcart_info->my->quantity;
					if ($shoppingcart > 99) $shoppingcart = 99;
				}  
				
				if ($loginprofileid == "anonymous")
				{
					$mall_shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts_'.$loginprofileid )
									->filter ( 'type', 'eic', 'mall_shoppingcarts' )
									  	
									->filter (  'my.supplierid', '=',$supplierid )  
									->filter (  'my.sessionid', '=',session_id())  
									->filter (  'my.deleted', '=','0') 
									->end(-1)
									->execute ();
				}
				else
				{
					$mall_shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts_'.$loginprofileid )
									->filter ( 'type', 'eic', 'mall_shoppingcarts' )
									  	
									->filter (  'my.supplierid', '=',$supplierid )   	
									->filter (  'my.profileid', '=',$loginprofileid)  
									->filter (  'my.deleted', '=','0') 
									->end(-1)
									->execute ();
				} 
				$shoppingcarts = array();
				if (count($mall_shoppingcarts) > 0)
				{ 
					foreach ($mall_shoppingcarts as $shoppingcart_info)
					{
						 $shoppingcartid = $shoppingcart_info->id;
						 $productthumbnail = $shoppingcart_info->my->productthumbnail;
		 				 global $APISERVERADDRESS,$width;
		 				 if (isset($productthumbnail) && $productthumbnail != "")
		 				 {
		 					$width = 50; 
		 					$productthumbnail = $APISERVERADDRESS.$productthumbnail;
		 				 }
						 $shoppingcarts[$shoppingcartid]['shoppingcartid'] = $shoppingcartid;
						 $shoppingcarts[$shoppingcartid]['productname'] = $shoppingcart_info->my->productname;
						 $shoppingcarts[$shoppingcartid]['productid'] = $shoppingcart_info->my->productid;
						 $shoppingcarts[$shoppingcartid]['propertydesc'] = $shoppingcart_info->my->propertydesc;
						 $shoppingcarts[$shoppingcartid]['quantity'] = $shoppingcart_info->my->quantity;
						 $shoppingcarts[$shoppingcartid]['shop_price'] = $shoppingcart_info->my->shop_price;
						 $shoppingcarts[$shoppingcartid]['total_price'] = $shoppingcart_info->my->total_price;
						 $shoppingcarts[$shoppingcartid]['productthumbnail'] = $productthumbnail;
						  
					}
				}
				 
				$supplierinfo = get_supplier_info();
				if (isset($supplierinfo['share_title']) && $supplierinfo['share_title'] != '')
				{
					$share_title = $supplierinfo['share_title'];
				}
				if (isset($supplierinfo['share_description']) && $supplierinfo['share_description'] != '')
				{
					$share_description = $supplierinfo['share_description'];
				} 
				if (isset($supplierinfo['share_logo']) && $supplierinfo['share_logo'] != '')
				{
					$share_logo = $supplierinfo['share_logo'];
				}
				
			    try{
			        $badges = XN_MemCache::get("mall_badges_".$supplierid."_".$loginprofileid); 
			    }
			    catch(XN_Exception $e)
				{
					$badges = array();
					$badges['new_billwater'] = 'no';
					$billwaters = XN_Query::create ( 'MainYearContent' )->tag('mall_billwaters_'.$loginprofileid)
									->filter ( 'type', 'eic', 'mall_billwaters' ) 
									 
									->filter (  'my.deleted', '=', '0' ) 
									->filter (  'my.supplierid', '=',$supplierid )  
									->filter (  'my.profileid', '=',$loginprofileid )   
									->order("published",XN_Order::DESC)
			                        ->end(1) 
									->execute ();
            
					if (count($billwaters) > 0 )
					{
						$billwater_info = $billwaters[0]; 		  
						$badge = $billwater_info->my->badge;  
						if ($badge != 'yes')
						{
							$badges['new_billwater'] = 'yes';
						}  
					}   
					
					 
					$query = XN_Query::create ( 'YearContent_Count' )->tag('mall_orders_'.$loginprofileid)
								->filter ( 'type', 'eic', 'mall_orders')  
								->filter ( 'my.deleted', '=', '0') 
								->filter ( 'my.supplierid', '=', $supplierid)
								->filter ( 'my.profileid', '=', $loginprofileid) 
								->filter ( 'my.tradestatus', '=', 'pretrade') 
								->rollup()
								->end(-1);
					$query->execute();
					if ($query->getTotalCount() > 0 )
					{
						$badges['new_order'] = 'yes';
					} 
					else
					{
						$query = XN_Query::create ( 'YearContent_Count' )->tag('mall_orders_'.$loginprofileid)
									->filter ( 'type', 'eic', 'mall_orders')  
									->filter ( 'my.deleted', '=', '0') 
									->filter ( 'my.supplierid', '=', $supplierid)
									->filter ( 'my.profileid', '=', $loginprofileid) 
									->filter ( 'my.tradestatus', '=', 'trade') 
									->filter ( 'my.confirmreceipt', '!=', 'receipt')   
									->filter ( 'my.needconfirmreceipt', '=', 'yes') 
									->rollup()
									->end(-1);
						$query->execute();
						if ($query->getTotalCount() > 0 )
						{
							$badges['new_order'] = 'yes';
						} 
						else
						{
							$query = XN_Query::create ( 'YearContent_Count' )->tag('mall_orders_'.$loginprofileid)
										->filter ( 'type', 'eic', 'mall_orders')  
										->filter ( 'my.deleted', '=', '0') 
										->filter ( 'my.supplierid', '=', $supplierid)
										->filter ( 'my.profileid', '=', $loginprofileid) 
										->filter ( 'my.tradestatus', '=', 'trade') 
										->filter ( 'my.confirmreceipt', '=', 'receipt')   
										->filter ( 'my.appraisestatus', '=', 'no') 
										->rollup()
										->end(-1);
							$query->execute();
							if ($query->getTotalCount() > 0 )
							{
								$badges['new_order'] = 'yes';
							} 
							else
							{
								$query = XN_Query::create ( 'YearContent_Count' )->tag('mall_orders_'.$loginprofileid)
											->filter ( 'type', 'eic', 'mall_orders')  
											->filter ( 'my.deleted', '=', '0') 
											->filter ( 'my.supplierid', '=', $supplierid)
											->filter ( 'my.profileid', '=', $loginprofileid) 
											->filter ( 'my.tradestatus', '=', 'trade')   
											->filter ( 'my.aftersaleservicestatus', '=', 'yes') 
											->rollup()
											->end(-1);
								$query->execute();
								if ($query->getTotalCount() > 0 )
								{
									$badges['new_order'] = 'yes';
								} 
								else
								{
						 
								}
							}
						}
					} 
					
					XN_MemCache::put($badges,"mall_badges_".$supplierid."_".$loginprofileid);   
			    } 
	    }
	    catch(XN_Exception $e)
		{ 
			$shoppingcart = 0;  
			$badges = array();
			$badges['new_billwater'] = 'no';
			$badges['new_order'] = 'no';
			
		} 
	}   
	else
	{
		$shoppingcart = 0;  
		$badges = array();
		$badges['new_billwater'] = 'no';
		$badges['new_order'] = 'no';
	}
  
   
    if (isset($share_logo) && $share_logo != "")
	{
		 $logo = 'http://'.$WX_DOMAIN.$share_logo;
	}
	else
	{
		global $configfile;
		$configfile = $_SERVER['DOCUMENT_ROOT'].'/config.sharelogos.php';
		if (!@file_exists($configfile)) ReWriteShareLogosConfig();

		$sharelogos = array();
		require_once ($configfile);
	
		if (is_array($sharelogos))
		{
			$rand = mt_rand(0,count($sharelogos));
			$logo = 'http://'.$WX_DOMAIN.'/public/sharelogos/'.$sharelogos[$rand];
		} 
		
	}
	
	
	$shareurl = 'http://'.$WX_DOMAIN.'/index.php?u='.$loginprofileid.'&sid='.$supplierid;
 
	    
    global $WX_APPID,$WX_SECRET; 
	
	require_once (XN_INCLUDE_PREFIX."/XN/Wx.php"); 

	XN_WX::$APPID = $WX_APPID;
	XN_WX::$SECRET = $WX_SECRET;

	$signPackage = XN_WX::GetSignPackage();  
 

	return array('appid'=>$WX_APPID,
			'timestamp'=>$signPackage["timestamp"],
			'noncestr'=>$signPackage["nonceStr"],
			'signature'=>$signPackage["signature"],
			'profileid'=>$loginprofileid, 
			'badges'=>$badges, 
			'shoppingcart'=>$shoppingcart,  
			'shoppingcarts'=>$shoppingcarts,   
			'share_title'=>$share_title, 
			'share_description'=>$share_description,
			'share_logo'=>$logo,
			'share_url'=>$shareurl);
		
	 
	
}

 
 

function get_overall_info($value) 
{
	$overall_rating = floatval($value);  

	$overall_rating_info = array(); 
	$red_stars = array_fill(1,floor($overall_rating),'images/star2.png'); 
	if ($red_stars  && count($red_stars) > 0)
	{
		$overall_rating_info = $red_stars;
	} 
	if ($overall_rating != intval($overall_rating))
	{
		$overall_rating_info[] = 'images/halfstar.png';
	}

	$gray_stars = array_fill(1,5-ceil($overall_rating),'images/star1.png');

	if ($gray_stars && count($gray_stars) > 0)
	{
		if (count($overall_rating_info) > 0)
		{  
			$overall_rating_info = array_merge($overall_rating_info,$gray_stars);
		}
		else
		{ 
			$overall_rating_info = $gray_stars;
		}
	} 
	return $overall_rating_info;
}


//更新每天的实时点赞数
function ReWritePraisesConfig($productid,$count)
{ 
	 XN_MemCache::put($count,"praises_".$productid); 
} 

function check_wx_login_repeat_submit($key)
{  
	$now = strtotime("now");
	try
	{ 
		 $time = XN_MemCache::get($key);
		 $difftime = $now - intval($time);
		 if ($difftime < 20)
		 {
			 return true;
		 }
	}
	catch (XN_Exception $e) 
	{
         XN_MemCache::put($now,$key,"2"); 
	}
	return false;
}


function checkloginlock($profileid)
{
	return;
	/*$loginlocks = XN_Query::create('Content')->tag('loginlocks_'.$profileid)
						->filter('type','eic','loginlocks')
					    ->filter('my.profileid','=',$profileid)    
						->begin(0)->end(1) 
						->execute();
	if(count($loginlocks) > 0)
	{ 
		$loginlock_info = $loginlocks[0];
		$newsession = session_id(); 
		if ($newsession != $loginlock_info->my->sessionid)
		{  
			$system = $loginlock_info->my->system;
			$updated = $loginlock_info->updated;
		    messagebox('错误','<div style="text-align:left;">您的账号于'.$updated.'，在'.$system.'设备上登录，如果这不是你的操作，你的登录密码可能已经泄露。请及时修改您的登录密码！</div>');
		    die();    
		}
	}  */
}
function loginlock_match( $Agent, $Patten )
{
	if( preg_match( $Patten, $Agent, $Tmp ) )
	{
		return $Tmp[1];
	}
	else
	{
		return false;
	}
}

function loginlock($profileid)
{
	
	$loginlocks = XN_Query::create('Content')->tag('loginlocks_'.$profileid)
						->filter('type','eic','loginlocks')
					    ->filter('my.profileid','=',$profileid)    
						->begin(0)->end(1) 
						->execute();
	if(count($loginlocks) == 0)
	{ 
		    $useragent = $_SERVER["HTTP_USER_AGENT"];
			if( $System = loginlock_match( $useragent, "|(Windows NT[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Windows Phone[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Windows[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(iPhone OS[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Mac[^;^)]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(unix)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Android[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Linux[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(SunOS[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(BSD[\ 0-9\.]*)|i" ) );
			else 
			{
				$System = '其它';
			}
		 
			$newcontent = XN_Content::create('loginlocks','',false); 
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->sessionid = session_id(); 
			$newcontent->my->system = trim( $System );  
			$newcontent->save('loginlocks,loginlocks_'.$profileid);   
	}  
	else
	{
		$loginlock_info = $loginlocks[0];
		$newsession = session_id(); 
		if ($newsession != $loginlock_info->my->sessionid)
		{  
		    $useragent = $_SERVER["HTTP_USER_AGENT"];
			if( $System = loginlock_match( $useragent, "|(Windows NT[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Windows Phone[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Windows[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(iPhone OS[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Mac[^;^)]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(unix)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Android[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(Linux[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(SunOS[\ 0-9\.]*)|i" ) );
			else if( $System = loginlock_match( $useragent, "|(BSD[\ 0-9\.]*)|i" ) );
			else 
			{
				$System = '其它';
			}
			$loginlock_info->my->sessionid = $newsession;  
			$loginlock_info->my->system = trim( $System );  
			$loginlock_info->save('loginlocks,loginlocks_'.$profileid);   
		}
	}
}


?>