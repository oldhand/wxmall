<?php
/*+**********************************************************************************
 * The contents of this file are subject to the 361CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  361CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

session_start();
require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $supplierid = $_SESSION['supplierid'];
}
else
{
    echo '{"code":201,"msg":"没有店铺ID!"}';
    die();
}


if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
{
    $profileid = $_SESSION['profileid'];
}
elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
    echo '{"code":201,"msg":"没有profileid！"}';
    die();
}

if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '' && $_REQUEST['type'] == 'send')
{
    $mobile = $_REQUEST['mobile'];
    $mobile = trim($mobile);

    try
    {
        $profiles = XN_Query::create('Profile')->tag("profile")
            ->filter('mobile', '=', $mobile)
            ->filter('type', '=', 'wxuser')
            ->execute();
        if (count($profiles) > 0)
        {
            echo '{"code":202,"msg":"手机号已经存在!"}';
            die();
        }

        $checkcode = randomkeys(6);
        XN_MemCache::put($checkcode, "verifycode_" . $mobile, "3600");
 
        global $copyrights; 
	    $access_key_id = $copyrights['sms_access_key_id'];
	    $access_key_secret = $copyrights['sms_access_key_secret'];
	    $signname =  $copyrights['sms_signname'];
	    $templatecode = $copyrights['sms_authentication_templatecode'];
		
		if (isset($access_key_id) && $access_key_id !="" &&
			isset($access_key_secret) && $access_key_secret !="" &&
			isset($signname) && $signname !="" &&
			isset($templatecode) && $templatecode !="" )
        { 
	         
		   XN_Content::create('sendmobile', '', false, 2)
			            ->my->add('status', 'waiting') 
			            ->my->add('to_mobile', $mobile)
			            ->my->add('verifycode', $checkcode)
				        ->my->add('access_key_id', $access_key_id)
			            ->my->add('access_key_secret', $access_key_secret)
			            ->my->add('signname', urlencode($signname))
			            ->my->add('codename', 'code')
			            ->my->add('templatecode', $templatecode)	
			            ->save("sendmobile");
		 	            
            echo '{"code":200,"msg":"success"}';
			die();
        }
        else
        {
	        echo '{"code":202,"msg":"系统没有配置短信网关参数!"}';
            die(); 
        } 

    }
    catch (XN_Exception $e)
    {
        echo '{"code":201,"msg":"' . $e->getMessage() . '"}';
        die();
    }
}
else if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '' &&
    isset($_REQUEST['verifycode']) && $_REQUEST['verifycode'] != '' &&
    $_REQUEST['type'] == 'submit'
)
{
    $mobile = trim($_REQUEST['mobile']);
    $verifycode = trim($_REQUEST['verifycode']);
    $needverifycode = trim($_REQUEST['needverifycode']);
    //$password = trim($_REQUEST['password']);
    $invitationcode = trim($_REQUEST['invitationcode']);
    $province = trim($_REQUEST['province']);
    $city = trim($_REQUEST['city']);

    $gender = trim($_REQUEST['gender']);
    $birthdate = trim($_REQUEST['birthdate']);


    try
    {
        if ($needverifycode == "yes")
        {
            try
            {
                $memcache_verifycode = XN_MemCache::get("verifycode_" . $mobile);
            }
            catch (XN_Exception $e)
            {
                echo '{"code":201,"msg":"验证码已经过期！"}';
                die();
            }
        }
        else
        {
            $memcache_verifycode = $verifycode;
        }
        if ($memcache_verifycode == $verifycode)
        {
            if (isset($invitationcode) && $invitationcode != "" && $invitationcode != "undefined")
            {
                try
                {
                    make_relation($supplierid, $profileid, $invitationcode);
                    activate_profile($profileid, $mobile, $invitationcode, $province, $city, $gender, $birthdate);
                }
                catch (XN_Exception $e)
                {
                    echo '{"code":201,"msg":"'.$e->getMessage().'"}';
                    die();
                }
            }
            else
            {
                try
                {
                    $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
                        ->filter('type', 'eic', 'supplier_profile')
                        ->filter('my.profileid', '=', $profileid)
                        ->filter('my.supplierid', '=', $supplierid)
                        ->filter('my.deleted', '=', '0')
                        ->end(1)
                        ->execute();
                    if (count($supplier_profile) > 0)
                    {
                        $supplier_profile_info = $supplier_profile[0];
                        $onelevelsourcer = $supplier_profile_info->my->onelevelsourcer;
                        if (isset($onelevelsourcer) && $onelevelsourcer != "")
                        {
                            $supplier_profile_info->my->hassourcer = '1';
                            $wxopenid = $supplier_profile_info->my->wxopenid;
                            $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $profileid . ",supplier_profile_" . $supplierid;
                            $supplier_profile_info->save($tag);
                            XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $profileid);
                            popularize($supplierid, $profileid);
                        }
                        else
                        {
                            $supplier_profile_info->my->hassourcer = '2';
                            $wxopenid = $supplier_profile_info->my->wxopenid;
                            $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $profileid . ",supplier_profile_" . $supplierid;
                            $supplier_profile_info->save($tag);
                            XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $profileid);
                        }
                    }
                    activate_profile($profileid, $mobile, $invitationcode, $province, $city, $gender, $birthdate);

                }
                catch (XN_Exception $e)
                {
                    echo '{"code":201,"msg":"'.$e->getMessage().'"}';
                    die();
                }
            }

            echo '{"code":200,"msg":"success"}';
            die();
        }
        else
        {
            echo '{"code":201,"msg":"验证码错误！"}';
            die();
        }

    }
    catch (XN_Exception $e)
    {
        echo '{"code":201,"msg":"'.$e->getMessage().'"}';
        die();
    }
}
else if ($_REQUEST['type'] == 'yetactivate')
{
    if (isset($_REQUEST['invitationcode']) && $_REQUEST['invitationcode'] != '')
    {
        $invitationcode = trim($_REQUEST['invitationcode']);
        try
        {
            make_relation($supplierid, $profileid, $invitationcode);
            echo '{"code":200,"msg":"success"}';
            die();
        }
        catch (XN_Exception $e)
        {
            echo '{"code":201,"msg":"'.$e->getMessage().'"}';
            die();
        }
    }
    else if (isset($_REQUEST['invitationcode']) && $_REQUEST['invitationcode'] == '')
    {
        try
        {
            $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
                ->filter('type', 'eic', 'supplier_profile')
                ->filter('my.profileid', '=', $profileid)
                ->filter('my.supplierid', '=', $supplierid)
                ->filter('my.deleted', '=', '0')
                ->end(1)
                ->execute();
            if (count($supplier_profile) > 0)
            {
                $supplier_profile_info = $supplier_profile[0];
                $supplier_profile_info->my->hassourcer = '2';

                $wxopenid = $supplier_profile_info->my->wxopenid;
                $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $profileid . ",supplier_profile_" . $supplierid;
                $supplier_profile_info->save($tag);
                XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $profileid);
            }
            echo '{"code":200,"msg":"success"}';
            die();
        }
        catch (XN_Exception $e)
        {
            echo '{"code":201,"msg":"'.$e->getMessage().'"}';
            die();
        }
    }
    else
    {
        echo '{"code":201,"msg":"保存失败。"}';
        die();
    }
}


function check_relation($supplierid, $profileid, $onelevelsourcer)
{
    $profile_info = get_supplier_profile_info($onelevelsourcer, $supplierid);
    if (count($profile_info) == 0)
    {
        throw new XN_Exception("您填写的推荐人的手机号码有误!该会员没有关注本商城！");
        return;
    }
    if ($profile_info['onelevelsourcer'] == $profileid)
    {
        throw new XN_Exception("商城不允许填写您粉丝的手机号码(一级)!");
        return;
    }
    if ($profile_info['twolevelsourcer'] == $profileid)
    {
        throw new XN_Exception("商城不允许填写您粉丝的手机号码(二级)!");
        return;
    }
    if ($profile_info['threelevelsourcer'] == $profileid)
    {
        throw new XN_Exception("商城不允许填写您粉丝的手机号码(三级)!");
        return;
    }
    $threelevelsourcer = $profile_info['threelevelsourcer'];
    $other_profile_info = get_supplier_profile_info($threelevelsourcer, $supplierid);
    if (count($other_profile_info) > 0)
    {
        if ($other_profile_info['onelevelsourcer'] == $profileid)
        {
            throw new XN_Exception("商城不允许填写您粉丝的手机号码(四级)!");
            return;
        }
        if ($other_profile_info['twolevelsourcer'] == $profileid)
        {
            throw new XN_Exception("商城不允许填写您粉丝的手机号码(五级)!");
            return;
        }
    }
    return $profile_info;
}


function make_relation($supplierid, $profileid, $mobile)
{
    try
    {
        $profile_info = get_supplier_profile_info($profileid,$supplierid);
        if (isset($profile_info['onelevelsourcer']) && $profile_info['onelevelsourcer'] != "")
        {
            return ;
        }

        $level_profiles = XN_Query::create('Profile')->tag("profile")
            ->filter('mobile', '=', $mobile)
            ->filter('type', '=', 'wxuser')
            ->execute();
        if (count($level_profiles) == 0)
        {
            throw new XN_Exception("您填写的上级客户的手机号码有误!没有这个用户！");
            return;
        }
        $level_profile_info = $level_profiles[0];
        $onelevelsourcer = $level_profile_info->profileid;
		$onelevelsourcer_givenname = $level_profile_info->givenname;
        if ($profileid == $onelevelsourcer)
        {
            throw new XN_Exception("您不能填写自己的手机号码！");
            return;
        }

        $profile_info = check_relation($supplierid, $profileid, $onelevelsourcer);


        $twolevelsourcer = $profile_info['onelevelsourcer'];
        $threelevelsourcer = $profile_info['twolevelsourcer'];
        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
            ->filter('type', 'eic', 'supplier_profile')
            ->filter('my.profileid', '=', $profileid)
            ->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')
            ->end(1)
            ->execute();
        if (count($supplier_profile) > 0)
        {
            $supplier_profile_info = $supplier_profile[0];
            $supplier_profile_info->my->onelevelsourcer = $onelevelsourcer;
            $supplier_profile_info->my->twolevelsourcer = $twolevelsourcer;
            $supplier_profile_info->my->threelevelsourcer = $threelevelsourcer;
			$supplier_profile_info->my->hassourcer = '1';

            $wxopenid = $supplier_profile_info->my->wxopenid;
            $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $profileid . ",supplier_profile_" . $supplierid;
            $tag .= ",supplier_profile_" . $onelevelsourcer;
            $tag .= ",supplier_profile_" . $twolevelsourcer;
            $tag .= ",supplier_profile_" . $threelevelsourcer;
            $supplier_profile_info->save($tag);

            XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $profileid);
			
			$givenname = $supplier_profile_info->my->givenname;
			
			$supplier_wxsettings = XN_Query::create ( 'MainContent' ) ->tag('supplier_wxsettings')
				->filter ( 'type', 'eic', 'supplier_wxsettings')
				->filter ( 'my.deleted', '=', '0' )
				->filter ( 'my.supplierid', '=' ,$supplierid)
				->end(1)
				->execute();
			if (count($supplier_wxsettings) > 0)
			{
				$supplier_wxsetting_info = $supplier_wxsettings[0];
				$appid = $supplier_wxsetting_info->my->appid;
				require_once (XN_INCLUDE_PREFIX."/XN/Message.php");  
				$message = '恭喜您，荣幸成为【'.$onelevelsourcer_givenname.'】的粉丝。';
				XN_Message::sendmessage($profileid,$message,$appid); 
				$message = '恭喜，【'.$givenname.'】成为您的粉丝。';
				XN_Message::sendmessage($onelevelsourcer,$message,$appid); 
			} 

        }

        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
            ->filter('type', 'eic', 'supplier_profile')
            ->filter('my.onelevelsourcer', '=', $profileid)
            ->filter('my.twolevelsourcer', '!=', $onelevelsourcer)
            ->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')
            ->end(-1)
            ->execute();
        if (count($supplier_profile) > 0)
        {
            foreach ($supplier_profile as $supplier_profile_info)
            {
                $supplier_profile_info->my->twolevelsourcer = $onelevelsourcer;
                $supplier_profile_info->my->threelevelsourcer = $twolevelsourcer;
                $wxopenid = $supplier_profile_info->my->wxopenid;
                $supplier_profileid = $supplier_profile_info->my->profileid;
                $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $supplier_profileid . ",supplier_profile_" . $supplierid;
                $tag .= ",supplier_profile_" . $onelevelsourcer;
                $tag .= ",supplier_profile_" . $twolevelsourcer;
                $tag .= ",supplier_profile_" . $threelevelsourcer;
                $supplier_profile_info->save($tag);
                XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $supplier_profileid);
            }
        }
        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
            ->filter('type', 'eic', 'supplier_profile')
            ->filter('my.onelevelsourcer', '=', $profileid)
            ->filter('my.threelevelsourcer', '!=', $twolevelsourcer)
            ->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')
            ->end(-1)
            ->execute();
        if (count($supplier_profile) > 0)
        {
            foreach ($supplier_profile as $supplier_profile_info)
            {
                $supplier_profile_info->my->threelevelsourcer = $twolevelsourcer;
                $wxopenid = $supplier_profile_info->my->wxopenid;
                $supplier_profileid = $supplier_profile_info->my->profileid;
                $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $supplier_profileid . ",supplier_profile_" . $supplierid;
                $tag .= ",supplier_profile_" . $onelevelsourcer;
                $tag .= ",supplier_profile_" . $twolevelsourcer;
                $tag .= ",supplier_profile_" . $threelevelsourcer;
                $supplier_profile_info->save($tag);
                XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $supplier_profileid);
            }
        }


        popularize($supplierid, $profileid);

    }
    catch (XN_Exception $e)
    {
        throw $e;
    }
}




function popularize($supplierid, $profileid)
{
    try
    {
	    $mall_billwaters = XN_Query::create("YearContent")->tag("mall_billwaters_".$supplierid)
	                                 ->filter("type", "eic", "mall_billwaters")
	                                 ->filter("my.billwatertype", "=", 'popularize')
	                                 ->filter("my.inviteprofileid", "=", $profileid)
	                                 ->filter("my.supplierid", "=", $supplierid) 
	                                 ->end(1)
	                                 ->execute(); 
		if (count($mall_billwaters) > 0)
		{
			return ;
		}
        $profile_info = get_supplier_profile_info($profileid,$supplierid);

        $onelevelsourcer = $profile_info['onelevelsourcer'];
        $twolevelsourcer = $profile_info['twolevelsourcer'];
        $threelevelsourcer = $profile_info['threelevelsourcer'];


        $supplierinfo = get_supplier_info();
        $popularizemode = intval($supplierinfo['popularizemode']);  // 1 =>  1级推广', 2 =>  '2级推广',3 =>  '3级推广',  0 => '无推广'
        $popularizefund = floatval($supplierinfo['popularizefund']);  // 推广金

        if ($popularizemode > 0 && $popularizefund > 0)
        {
            if ($popularizemode == 1)
            {
                if (isset($onelevelsourcer) && $onelevelsourcer != "")
                {
                    execute_popularize($onelevelsourcer, $supplierid, $popularizefund, $profileid);
                }
            }
            else if ($popularizemode == 2)
            {
                if (isset($onelevelsourcer) && $onelevelsourcer != "")
                {
                    execute_popularize($onelevelsourcer, $supplierid, $popularizefund, $profileid);
                }
                if (isset($twolevelsourcer) && $twolevelsourcer != "")
                {
                    execute_popularize($twolevelsourcer, $supplierid, $popularizefund,$profileid,$onelevelsourcer,2);
                }
            }
            else if ($popularizemode == 3)
            {
                if (isset($onelevelsourcer) && $onelevelsourcer != "")
                {
                    execute_popularize($onelevelsourcer, $supplierid, $popularizefund, $profileid);
                }
                if (isset($twolevelsourcer) && $twolevelsourcer != "")
                {
                    execute_popularize($twolevelsourcer, $supplierid, $popularizefund,$profileid,$onelevelsourcer,2);
                }
                if (isset($threelevelsourcer) && $threelevelsourcer != "")
                {
                    execute_popularize($threelevelsourcer, $supplierid, $popularizefund,$profileid,$twolevelsourcer,3);
                }
            }
        }
		
		if (isset($supplierinfo['allowphysicalstore']) && $supplierinfo['allowphysicalstore'] == '0' &&
			isset($onelevelsourcer) && $onelevelsourcer != '')
		{
			$supplier_self_physicalstoreprofiles = XN_Query::create ( 'Content' ) 
			    ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
				->filter ( 'my.supplierid', '=',$supplierid)
				->filter ( 'my.profileid', '=',$profileid)
			    ->filter ( 'my.deleted', '=', '0' )
				->end(1)
			    ->execute ();
			if (count($supplier_self_physicalstoreprofiles) == 0)
			{
				$supplier_physicalstoreprofiles = XN_Query::create ( 'Content' ) 
				    ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
					->filter ( 'my.supplierid', '=',$supplierid)
					->filter ( 'my.profileid', '=',$onelevelsourcer)
				    ->filter ( 'my.deleted', '=', '0' )
					->end(1)
				    ->execute ();
				if (count($supplier_physicalstoreprofiles) > 0)
				{
					$supplier_physicalstoreprofile_info = $supplier_physicalstoreprofiles[0];
					$physicalstoreid = $supplier_physicalstoreprofile_info->my->physicalstoreid;
					$storeprofileid = $supplier_physicalstoreprofile_info->my->storeprofileid;
					$assistantprofileid = $supplier_physicalstoreprofile_info->my->assistantprofileid;
				 
				
					
			        $newcontent = XN_Content::create('supplier_physicalstoreprofiles', '', false);
			        $newcontent->my->deleted = '0';
			        $newcontent->my->profileid = $profileid;
			        $newcontent->my->supplierid = $supplierid;
			        $newcontent->my->physicalstoreid = $physicalstoreid;
					$newcontent->my->storeprofileid = $storeprofileid; 
					$newcontent->my->assistantprofileid = $assistantprofileid; 
			        $newcontent->save("supplier_physicalstoreprofiles,supplier_physicalstoreprofiles_" . $profileid. ",supplier_physicalstoreprofiles_" . $supplierid);
				 
				 
					$givenname = $profile_info['givenname'];
				 
					$supplier_wxsettings = XN_Query::create ( 'MainContent' ) ->tag('supplier_wxsettings')
						->filter ( 'type', 'eic', 'supplier_wxsettings')
						->filter ( 'my.deleted', '=', '0' )
						->filter ( 'my.supplierid', '=' ,$supplierid)
						->end(1)
						->execute();
					if (count($supplier_wxsettings) > 0)
					{
						$supplier_wxsetting_info = $supplier_wxsettings[0];
						$appid = $supplier_wxsetting_info->my->appid;
						require_once (XN_INCLUDE_PREFIX."/XN/Message.php");  
						$message = '店主您好，【'.$givenname.'】成为您的新顾客。';
						XN_Message::sendmessage($storeprofileid,$message,$appid); 
						$message = '恭喜，【'.$givenname.'】成为您的新顾客。推荐顾客成功!';
						XN_Message::sendmessage($assistantprofileid,$message,$appid); 
					} 
				}
			}  
		}

    }
    catch (XN_Exception $e)
    {
        throw $e;
    }
}


function activate_profile($profileid, $mobile, $invitationcode, $province, $city, $gender, $birthdate)
{
    $profile = XN_Profile::load($profileid, "id", "profile_" . $profileid);
    if ($profile->mobile != $mobile)
    {
        $profile->mobile = $mobile;
        $profile->fullName = $mobile;
        $sourcer = $profile->sourcer;

		if (!isset($sourcer) || $sourcer == "")
        {
	        if (isset($invitationcode) && $invitationcode != "")
	        {
		        $invitationprofiles = XN_Query::create('Profile')->tag("profile")
                ->filter('mobile', '=', $invitationcode)
                ->filter('type', '=', 'wxuser')
                ->execute();
	            if (count($invitationprofiles) > 0)
	            {
	                $invitationprofile_info = $invitationprofiles[0];
	                $invitationprofileid = $invitationprofile_info->profileid;
	                $profile->invitationcode = $invitationcode;
	                $profile->sourcer = $invitationprofileid;
	                $sourcer = $invitationprofileid;
	            }
	        } 
        }
        else
        {
	        if ($sourcer != $profileid)
	        {
		        $profile_info = XN_Profile::load($sourcer, "id", "profile_" . $sourcer);
	            $invitationcode = $profile_info->mobile;
	            if (isset($invitationcode) && $invitationcode != "")
	            {
	                $profile->invitationcode = $invitationcode;
	            }
	        }
	        else
	        {
		        if (isset($invitationcode) && $invitationcode != "")
		        {
			        $invitationprofiles = XN_Query::create('Profile')->tag("profile")
	                ->filter('mobile', '=', $invitationcode)
	                ->filter('type', '=', 'wxuser')
	                ->execute();
		            if (count($invitationprofiles) > 0)
		            {
		                $invitationprofile_info = $invitationprofiles[0];
		                $invitationprofileid = $invitationprofile_info->profileid;
		                $profile->invitationcode = $invitationcode;
		                $profile->sourcer = $invitationprofileid;
		                $sourcer = $invitationprofileid;
		            }
		        } 
	        }
    
        }

        $profile->province = $province;
        $profile->city = $city;
        if (isset($gender) && $gender != "")
        {
            $profile->gender = $gender;
        }
        if (isset($birthdate) && $birthdate != "")
        {
            $profile->birthdate = $birthdate;
        }

        $profile->activationdate = date("Y-m-d H:i:s");

        $wxopenid = $profile->wxopenid;
        $profile->save("profile,profile_" . $profileid . ",profile_" . $sourcer . ",profile_" . $wxopenid);

        XN_MemCache::delete("tezan_profile_" . $profileid);

        $supplier_profiles = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
            ->filter('type', 'eic', 'supplier_profile')
            ->filter('my.profileid', '=', $profileid)
            ->filter('my.deleted', '=', '0')
            ->end(-1)
            ->execute();

        $objs = array();

        $tag = "supplier_profile,supplier_profile_" . $profileid . ",supplier_profile_" . $wxopenid;

        foreach ($supplier_profiles as $supplier_profile_info)
        {
            if ($supplier_profile_info->my->mobile != $mobile)
            {
                $supplier_profile_info->my->mobile = $mobile;
                $supplier_profile_info->my->province = $province;
                $supplier_profile_info->my->city = $city;
                $supplier_profile_info->my->gender = $gender;
                $supplier_profile_info->my->birthdate = $birthdate;
                $other_supplierid = $supplier_profile_info->my->supplierid;
                $objs[] = $supplier_profile_info;

                XN_MemCache::delete("supplier_profile_" . $other_supplierid . '_' . $profileid);
                $tag = ",supplier_profile_" . $other_supplierid;

            }
        }
        if (count($objs) > 0)
        {
            XN_Content::batchsave($objs, $tag);
        }
    }
}
function check_repeat_submit($key)
{ 
	try
	{ 
		 $data = XN_MemCache::get($key);
		 if ($data == "submit") 
		 {
			 return true;
		 }
	}
	catch (XN_Exception $e) 
	{
         XN_MemCache::put("submit",$key,"120"); 
	}
	return false;
}
function execute_popularize($sourcer, $supplierid, $popularizefund, $popularizeprofileid, $middleman = "",$level=1)
{
    try
    { 
        $profile_info = get_supplier_profile_info($sourcer, $supplierid);

        $money = $profile_info['money'];
        $new_money = floatval($money) + floatval($popularizefund);
        $accumulatedmoney = $profile_info['accumulatedmoney'];
        $new_accumulatedmoney = floatval($accumulatedmoney) + floatval($popularizefund);


	    $mall_billwaters = XN_Query::create("YearContent")->tag("mall_billwaters_".$sourcer)
	                                 ->filter("type", "eic", "mall_billwaters")
	                                 ->filter("my.billwatertype", "=", 'popularize')
	                                 ->filter("my.inviteprofileid", "=", $popularizeprofileid)
	                                 ->filter("my.supplierid", "=", $supplierid)
	                                 ->filter("my.profileid", "=", $sourcer) 
	                                 ->end(1)
	                                 ->execute(); 
		if (count($mall_billwaters) > 0)
		{
			return ;
		}
		
        $billwater_info = XN_Content::create('mall_popularizes', '', false);
        $billwater_info->my->deleted = '0';
        $billwater_info->my->supplierid = $supplierid;
        $billwater_info->my->profileid = $sourcer;
        $billwater_info->my->inviteprofileid = $popularizeprofileid; 
        $billwater_info->save('mall_popularizes,mall_popularizes_' . $sourcer . ',mall_popularizes_' . $popularizeprofileid . ',mall_popularizes_' . $supplierid);
		

        $billwater_info = XN_Content::create('mall_billwaters', '', false, 8);
        $billwater_info->my->deleted = '0';
        $billwater_info->my->supplierid = $supplierid;
        $billwater_info->my->profileid = $sourcer;
        $billwater_info->my->inviteprofileid = $popularizeprofileid;
        $billwater_info->my->middleman = $middleman;
        $billwater_info->my->billwatertype = 'popularize';
        $billwater_info->my->submitdatetime = date("Y-m-d H:i");
        $billwater_info->my->sharedate = '';
        $billwater_info->my->orderid = '';
        $billwater_info->my->shareid = '';
        $billwater_info->my->amount = '+' . number_format($popularizefund, 2, ".", "");
        $billwater_info->my->money = number_format($new_money, 2, ".", "");
        $billwater_info->save('mall_billwaters,mall_billwaters_' . $sourcer . ',mall_billwaters_' . $popularizeprofileid . ',mall_billwaters_' . $supplierid);


        $profile_info['money'] = $new_money;
        $profile_info['accumulatedmoney'] = $new_accumulatedmoney;

        $supplierinfo = get_supplier_info();
        $takecashitem = $supplierinfo['takecashitem'];
        if (in_array('1', $takecashitem))
        {
            $maxtakecash = $profile_info['maxtakecash'];
            $new_maxtakecash = floatval($maxtakecash) + floatval($popularizefund);
            $profile_info['maxtakecash'] = $new_maxtakecash;
        }

        update_supplier_profile_info($profile_info);

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
            require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
            $message = '您获得' . number_format($popularizefund, 2, ".", "") . '元的推广收益。说明:第'.$level.'层';
            XN_Message::sendmessage($sourcer, $message, $appid);
        }
    }
    catch (XN_Exception $e)
    {

    }
}


?>