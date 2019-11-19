<?php

if (!function_exists('matchbrowser')) {

	function matchbrowser( $Agent, $Patten )
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
}
//获取浏览器信息
if (!function_exists('getsharebrowser')) {
	function getsharebrowser()
	{
		$useragent = $_SERVER["HTTP_USER_AGENT"];
		if( $Browser = matchbrowser( $useragent, "|(myie[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(Netscape[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(Opera[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(NetCaptor[^;^^()]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(TencentTraveler)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(Firefox[0-9/\.^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(MSN[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(Lynx[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(Konqueror[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(WebTV[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(msie[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(Maxthon[^;^)^(]*)|i" ) );
		else if( $Browser = matchbrowser( $useragent, "|(QQ[0-9.\/]*) |i" ) );
		else if( $Browser = matchbrowser( $useragent, "|MicroMessenger/([^;^)^(]*)_|i" ) )
		{
			return "微信".trim( $Browser );
		}
		else if( $Browser = matchbrowser( $useragent, "|MicroMessenger/([^;^)^(]*) |i" ) )
		{
			return "微信".trim( $Browser );
		}
		else if( $Browser = matchbrowser( $useragent, "|(Scrapy[^;^)^(]*)|i" ) );
		else
		{
			$Browser = '其它';
		}
		return trim( $Browser );
	}
}


//获取操作系统版本
if (!function_exists('getsharesystem')) {
	function getsharesystem()
	{
		$useragent = $_SERVER["HTTP_USER_AGENT"];
		if( $System = matchbrowser( $useragent, "|(Windows NT[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(Windows Phone[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(Windows[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(iPhone OS[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(Mac[^;^)]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(unix)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(Android[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(Linux[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(SunOS[\ 0-9\.]*)|i" ) );
		else if( $System = matchbrowser( $useragent, "|(BSD[\ 0-9\.]*)|i" ) );
		else
		{
			$System = '其它';
		}
		return trim( $System );
	}
}

class XN_WX {
    public static $APPID = null;
	public static $SECRET = null;
	public static $ACCESS_TOKEN = null;
	public static $configfile = null;



	public static function flushaccesstoken()
	{
	    $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
		$body = self::get($url);
		$json = json_decode($body);
		if (!is_null($json->access_token))
		{
			self::$ACCESS_TOKEN = $json->access_token;
			XN_MemCache::put($json->access_token,"wx_access_token_".self::$APPID,"3600");
		}
	}

    public static function initaccesstoken()
	{
		try
		{
			$access_token = XN_MemCache::get("wx_access_token_".self::$APPID);
			if ($access_token == "")
			{
			     throw new XN_Exception("empty wx_access_token!");
			}
			self::$ACCESS_TOKEN = $access_token;
		}
		catch (XN_Exception $e)
		{
		    $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
			$body = self::get($url);
			$json = json_decode($body);
			if (!is_null($json->access_token))
			{
				self::$ACCESS_TOKEN = $json->access_token;
				XN_MemCache::put($json->access_token,"wx_access_token_".self::$APPID,"3600");
			}
		}
	}

    public static function getSignPackage() {
      $jsapiTicket = self::getJsApiTicket();

	  $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


      $timestamp = time();
      $nonceStr = self::createNonceStr();

      // 这里参数的顺序要按照 key 值 ASCII 码升序排序
      $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

      $signature = sha1($string);

      $signPackage = array(
        "appId"     => self::$APPID,
        "nonceStr"  => $nonceStr,
        "timestamp" => $timestamp,
        "url"       => $url,
        "signature" => $signature,
        "rawString" => $string
      );
      return $signPackage;
    }

    private static function getJsApiTicket()
	{
	      // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	  	try
	  	{
	  		$ticket = XN_MemCache::get("wx_share_jsapi_ticket_".self::$APPID);
	  		if ($ticket == "")
	  		{
	  		     throw new XN_Exception("empty wx_share_jsapi_ticket!");
	  		}
	  	}
	  	catch (XN_Exception $e)
	  	{
			  self::initaccesstoken();
	  		  $accessToken = self::$ACCESS_TOKEN;
	          $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
	          $res = json_decode(self::get($url));
	          $ticket = $res->ticket;
	          if ($ticket)
		  	  {
		  			XN_MemCache::put($ticket,"wx_share_jsapi_ticket_".self::$APPID,"3600");
		  	  }
		  	  else
		  	  {
			  	      self::flushaccesstoken();
			  	      $res = json_decode(self::get($url));
			          $ticket = $res->ticket;
			          if ($ticket)
				  	  {
				  			XN_MemCache::put($ticket,"wx_share_jsapi_ticket_".self::$APPID,"3600");
				  	  }
				  	  else
				  	  {
					  	    return "";
				  	  }
		  	  }
	  	}
	  	return $ticket;
    }

    private static function createNonceStr($length = 16) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }

	public static function asynchronous($url)
	{
			if (strpos($_SERVER["SERVER_SOFTWARE"],"nginx") !==false)
			{
				$domain=$_SERVER['HTTP_HOST'];
			}
			else
			{
				$domain=$_SERVER['SERVER_NAME'];
			}
		 $web_root = 'http://'.$domain.':'.$_SERVER['SERVER_PORT'];

		 $newurl = $web_root.$url;

		 $curlObj = curl_init();
		 curl_setopt($curlObj, CURLOPT_URL, $newurl); // 设置访问的url
		 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_CONNECTTIMEOUT, 1);    // 连接等待时间
		 curl_setopt($curlObj, CURLOPT_TIMEOUT, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array());
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);

         curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'GET');
         curl_setopt($curlObj, CURLOPT_HTTPGET, true);

		 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');
		 @curl_exec($curlObj);
		 curl_close($curlObj);

	}

	 public static function initprofile($wxopenid,$update=false)
	 {
		global $firsttime;
	 	$wxopenids = XN_Query::create ( 'MainContent' )->tag("profile_".$wxopenid)
	 						->filter ( 'type', 'eic', 'wxopenids')
	 						->filter ( 'my.wxopenid', '=', $wxopenid)
	 						->end(-1)
	 						->execute ();
	 	if (count($wxopenids) > 0 )
	 	{
	 		$wxopenid_info = $wxopenids[0];
			$appid = $wxopenid_info->my->appid;
			if (!isset($appid) || $appid == "")
			{
				$wxopenid_info->my->appid = self::$APPID;
				$wxopenid_info->save("profile_".$wxopenid);
			}

	 		$profileid = $wxopenid_info->my->profileid;
	 		$profile = XN_Profile::load($profileid,"id","profile_".$profileid);

	 		if ($update && ($profile->type == "unsubscribe"  || $profile->givenname == "" || $profile->link == "" || $profile->unionid == ""))
	 		{

	 		    $userinfo = self::getuserinfo($wxopenid);

	 			if (is_array($userinfo))
	 			{
	 			    if ($userinfo['subscribe'] == '1')
	 			    {
	 					$profile->status = 'True';

	 					$nickname = str_replace("'","",$userinfo['nickname']);
	 					$nickname = str_replace(" ","",$nickname);
	 					$nickname = str_replace("\\","",$nickname);
	 					$nickname = strip_tags($nickname);
	 					$profile->givenname = $nickname;

	 					$profile->country = $userinfo['country'];
	 					$profile->province = $userinfo['province'];
	 					$profile->city = $userinfo['city'];
	 					$profile->link = $userinfo['headimgurl'];

	 					if ($userinfo['sex'] == '1')
	 					{
	 						$profile->gender = '男';
	 					}
	 					else if($userinfo['sex'] == '2')
	 					{
	 						$profile->gender = '女';
	 					}
	 					else
	 					{
	 						//$profile->gender = '未知';
	 					}
	 					$profile->wxopenid = $wxopenid;
	 					//$usip=$_SERVER['REMOTE_ADDR'];
	 					//$profile->reg_ip = $usip;
	 					$profile->reg_ip = "";
	 					$money = preg_replace("/[^0-9\.]/i", "", $profile->money);
	 					$frozen_money = preg_replace("/[^0-9\.]/i", "", $profile->frozen_money);
	 					$accumulatedmoney = preg_replace("/[^0-9\.]/i", "", $profile->accumulatedmoney);
	 					if ($profile->rank == "")
	 					{
	 						$profile->rank = "0";
	 					}
	 					if ($money == "")
	 					{
	 						$profile->money = "0";
	 					}
	 					if ($frozen_money == "")
	 					{
	 						$profile->frozen_money = "0";
	 					}
	 					if ($accumulatedmoney == "")
	 					{
	 						$profile->accumulatedmoney = "0";
	 					}
	 					if (isset($userinfo['unionid']) && $profile->unionid != $userinfo['unionid'])
	 					{
	 						$profile->unionid = $userinfo['unionid'];
	 					}
					    if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
					    {
						    $profile->sourcer = $_SESSION['u'];
					    }
					    else
					    {
						    $profile->sourcer = '';
					    }

	 					$profile->type = "wxuser";
	 					$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);


	 					if (isset($userinfo['unionid']) && $userinfo['unionid'] != '')
	 					{
	 						if ($wxopenid_info->my->unionid != $userinfo['unionid'])
	 						{
	 							$wxopenid_info->my->unionid = $userinfo['unionid'];
	 							$wxopenid_info->save("profile_".$wxopenid);
	 						}
	 					}
	 				}
	 				else
	 				{
	 					if (isset($userinfo['unionid']) && $userinfo['unionid'] != '')
	 					{
	 						if ($wxopenid_info->my->unionid != $userinfo['unionid'])
	 						{
	 							$wxopenid_info->my->unionid = $userinfo['unionid'];
	 							$wxopenid_info->save("profile_".$wxopenid);
	 						}
	 					}
	 				}
	 			}
	 			else
	 			{
		 			if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
					{
					    Header("Location: index.php");
						exit();
					}
					elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
					{
					     Header("Location: index.php");
						 exit();
					}
					else
					{
					    self::flushaccesstoken();
						throw new XN_Exception("请关闭窗口重新点击>>商城菜单<<进入!");
						return "";
					}

	 			}
	 		}
			else if ($update && $profile->reg_ip == "")
			{
				$profile->reg_ip = $_SERVER['REMOTE_ADDR'];
				$profile->browser = getsharebrowser();
				$profile->system = getsharesystem();
				$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
			}

			return $profile;
	 	}
	 	else
	 	{

	 	    $userinfo = self::getuserinfo($wxopenid);

	 		if (is_array($userinfo))
	 		{
	 			if ($userinfo['subscribe'] == '1')
	 			{
		 					if (isset($userinfo['unionid']) && $userinfo['unionid'] != '')
		 					{
	 							$unionid = $userinfo['unionid'];
	 							$wxopenids = XN_Query::create ( 'MainContent' )->tag("profile_".$wxopenid)
	 											->filter ( 'type', 'eic', 'wxopenids')
	 											->filter ( 'my.unionid', '=', $unionid)
	 											->end(-1)
	 											->execute ();
	 							if (count($wxopenids) == 0 )
	 							{
	 							    $profiles = XN_Query::create ( 'Profile' )->tag('profile_'.$wxopenid)
	 										->filter( 'wxopenid', '=', $wxopenid)
	 										->begin(0)->end(1)
	 										->execute ();
	 								if (count($profiles)  ==  0 )
	 								{
	 									$application =  XN_Application::$CURRENT_URL;
	 									$profile = XN_Profile::create ( $wxopenid, "123qwe" );
	 									$profile->fullname = $wxopenid;
	 									$profile->mobile = '';
	 									$profile->status = 'True'; 

	 									$nickname = str_replace("'","",$userinfo['nickname']);
	 									$nickname = str_replace(" ","",$nickname);
	 									$nickname = str_replace("\\","",$nickname);

	 									$profile->givenname = $nickname;
	 									$profile->country = $userinfo['country'];
	 									$profile->province = $userinfo['province'];
	 									$profile->city = $userinfo['city'];
	 									$profile->link = $userinfo['headimgurl'];

	 									if ($userinfo['sex'] == '1')
	 									{
	 										$profile->gender = '男';
	 									}
	 									else if($userinfo['sex'] == '2')
	 									{
	 										$profile->gender = '女';
	 									}
	 									else
	 									{
	 										//$profile->gender = '未知';
	 									}
	 									$profile->wxopenid = $wxopenid;
					 					//$usip=$_SERVER['REMOTE_ADDR'];
					 					//$profile->reg_ip = $usip;
					 					$profile->reg_ip = "";
	 									$profile->rank = "0";
	 									$profile->type = "wxuser";
	 							        $profile->unionid = $unionid;
					 					if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
									    {
										    $profile->sourcer = $_SESSION['u'];
											$sourcertag = ",profile_".$_SESSION['u'];
									    }
									    else
									    {
										    $profile->sourcer = '';
											$sourcertag = "";
									    }
	 									$profile->save("profile,profile_".$wxopenid.$sourcertag);
	 									$newcontent = XN_Content::create('wxopenids','',false,4);
	 									$newcontent->my->profileid = $profile->profileid;
	 									$newcontent->my->wxopenid = $wxopenid;
	 									$newcontent->my->unionid = $userinfo['unionid'];
										$newcontent->my->appid = self::$APPID;
	 									$newcontent->save("profile_".$wxopenid);
	 									$firsttime = 'true';
	 								}
	 								else
	 								{
	 									$profile = $profiles[0];
	 									$nickname = str_replace("'","",$userinfo['nickname']);
	 									$nickname = str_replace(" ","",$nickname);
	 									$nickname = str_replace("\\","",$nickname);
	 									$profile->givenname = $nickname;
	 									$profile->country = $userinfo['country'];
	 									$profile->province = $userinfo['province'];
	 									$profile->city = $userinfo['city'];
	 									$profile->link = $userinfo['headimgurl'];

	 									if ($userinfo['sex'] == '1')
	 									{
	 										$profile->gender = '男';
	 									}
	 									else if($userinfo['sex'] == '2')
	 									{
	 										$profile->gender = '女';
	 									}
	 									$profile->type = "wxuser";
	 							        $profile->unionid = $unionid;
	 									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
	 									$newcontent = XN_Content::create('wxopenids','',false,4);
	 									$newcontent->my->profileid = $profile->profileid;
	 									$newcontent->my->wxopenid = $wxopenid;
	 									$newcontent->my->unionid = $unionid;
										$newcontent->my->appid = self::$APPID;
	 									$newcontent->save("profile_".$wxopenid);
	 									$firsttime = 'false';
	 								}
	 							}
	 							else
	 							{
	 								$wxopenid_info = $wxopenids[0];
	 								$profileid = $wxopenid_info->my->profileid;
									$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
 									$nickname = str_replace("'","",$userinfo['nickname']);
 									$nickname = str_replace(" ","",$nickname);
 									$nickname = str_replace("\\","",$nickname);
 									$profile->givenname = $nickname;
 									$profile->country = $userinfo['country'];
 									$profile->province = $userinfo['province'];
 									$profile->city = $userinfo['city'];
 									$profile->link = $userinfo['headimgurl'];

 									if ($userinfo['sex'] == '1')
 									{
 										$profile->gender = '男';
 									}
 									else if($userinfo['sex'] == '2')
 									{
 										$profile->gender = '女';
 									}
 									$profile->type = "wxuser";
 							        $profile->unionid = $unionid;
 									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);

	 								$newcontent = XN_Content::create('wxopenids','',false,4);
	 								$newcontent->my->profileid = $profileid;
	 								$newcontent->my->wxopenid = $wxopenid;
	 								$newcontent->my->unionid = $unionid;
									$newcontent->my->appid = self::$APPID;
	 								$newcontent->save("profile_".$wxopenid);
	 								$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
	 								$firsttime = 'false';
	 							}
	 					}
	 					else
	 					{
	 						$application =  XN_Application::$CURRENT_URL;
	 						$profile = XN_Profile::create ( $wxopenid, "123qwe" );
	 						$profile->fullname = $wxopenid;
	 						$profile->mobile = '';
	 						$profile->status = 'True'; 

	 						$nickname = str_replace("'","",$userinfo['nickname']);
	 						$nickname = str_replace(" ","",$nickname);
	 						$nickname = str_replace("\\","",$nickname);

	 						$profile->givenname = $nickname;
	 						$profile->country = $userinfo['country'];
	 						$profile->province = $userinfo['province'];
	 						$profile->city = $userinfo['city'];
	 						$profile->link = $userinfo['headimgurl'];

	 						if ($userinfo['sex'] == '1')
	 						{
	 							$profile->gender = '男';
	 						}
	 						else if($userinfo['sex'] == '2')
	 						{
	 							$profile->gender = '女';
	 						}
	 						else
	 						{
	 							//$profile->gender = '未知';
	 						}
	 						$profile->wxopenid = $wxopenid;
		 					//$usip=$_SERVER['REMOTE_ADDR'];
		 					//$profile->reg_ip = $usip;
		 					$profile->reg_ip = "";
	 						$profile->type = "wxuser";
	 				        $profile->unionid = '';
		 					if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
						    {
							    $profile->sourcer = $_SESSION['u'];
								$sourcertag = ",profile_".$_SESSION['u'];
						    }
						    else
						    {
							    $profile->sourcer = '';
								$sourcertag = "";
						    }
							$profile->save("profile,profile_".$wxopenid.$sourcertag);
	 						$newcontent = XN_Content::create('wxopenids','',false,4);
	 						$newcontent->my->profileid = $profile->profileid;
	 						$newcontent->my->wxopenid = $wxopenid;
	 						$newcontent->my->unionid = '';
							$newcontent->my->appid = self::$APPID;
	 						$newcontent->save("profile_".$wxopenid);
	 						$firsttime = 'true';
	 					}
	 			}
	 			else
	 			{
 					if (isset($userinfo['unionid']) && $userinfo['unionid'] != '')
 					{
						$unionid = $userinfo['unionid'];
						$wxopenids = XN_Query::create ( 'MainContent' )->tag("profile_".$wxopenid)
										->filter ( 'type', 'eic', 'wxopenids')
										->filter ( 'my.unionid', '=', $unionid)
										->end(-1)
										->execute ();
						if (count($wxopenids) == 0 )
						{
						    $profiles = XN_Query::create ( 'Profile' )->tag('profile_'.$wxopenid)
									->filter( 'wxopenid', '=', $wxopenid)
									->begin(0)->end(1)
									->execute ();
							if (count($profiles)  ==  0 )
							{
					    		$application =  XN_Application::$CURRENT_URL;
					    		$profile = XN_Profile::create ( $wxopenid, "123qwe" );
								$profile->fullname = $wxopenid;
								$profile->mobile = '';
								$profile->status = 'True'; 
								$profile->givenname = '';
								$profile->wxopenid = $wxopenid;
			 					//$usip=$_SERVER['REMOTE_ADDR'];
			 					//$profile->reg_ip = $usip;
			 					$profile->reg_ip = "";
								$profile->money = "0"; 
								$profile->unionid = $unionid;
								$profile->type = "unsubscribe";
			 					if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
							    {
								    $profile->sourcer = $_SESSION['u'];
									$sourcertag = ",profile_".$_SESSION['u'];
							    }
							    else
							    {
								    $profile->sourcer = '';
									$sourcertag = "";
							    }
								$profile->save("profile,profile_".$wxopenid.$sourcertag);
								$newcontent = XN_Content::create('wxopenids','',false,4);
								$newcontent->my->profileid = $profile->profileid;
								$newcontent->my->wxopenid = $wxopenid;
								$newcontent->my->unionid = $userinfo['unionid'];
								$newcontent->my->appid = self::$APPID;
								$newcontent->save("profile_".$wxopenid);
								$firsttime = 'true';
							}
							else
							{
								$profile = $profiles[0];
								if ($profile->unionid != $unionid)
								{
									$profile->unionid = $unionid;
									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
								}
								$newcontent = XN_Content::create('wxopenids','',false,4);
								$newcontent->my->profileid = $profile->profileid;
								$newcontent->my->wxopenid = $wxopenid;
								$newcontent->my->unionid = $unionid;
								$newcontent->my->appid = self::$APPID;
								$newcontent->save("profile_".$wxopenid);
							}
						}
						else
						{
							$wxopenid_info = $wxopenids[0];
							$profileid = $wxopenid_info->my->profileid;
							$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
							if ($profile->unionid != $unionid)
							{
								$profile->unionid = $unionid;
								$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
							}

							$newcontent = XN_Content::create('wxopenids','',false,4);
							$newcontent->my->profileid = $profileid;
							$newcontent->my->wxopenid = $wxopenid;
							$newcontent->my->unionid = $unionid;
							$newcontent->my->appid = self::$APPID;
							$newcontent->save("profile_".$wxopenid);
							$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
						}
					}
					else
					{
					    $profiles = XN_Query::create ( 'Profile' )->tag('profile_'.$wxopenid)
								->filter( 'wxopenid', '=', $wxopenid)
								->begin(0)->end(1)
								->execute ();
						if (count($profiles)  ==  0 )
						{
				    		$application =  XN_Application::$CURRENT_URL;
				    		$profile = XN_Profile::create ( $wxopenid, "123qwe" );
							$profile->fullname = $wxopenid;
							$profile->mobile = '';
							$profile->status = 'True'; 
							$profile->givenname = '';
							$profile->wxopenid = $wxopenid;
		 					//$usip=$_SERVER['REMOTE_ADDR'];
		 					//$profile->reg_ip = $usip;
		 					$profile->reg_ip = "";
							$profile->money = "0";
							$profile->unionid = '';
							$profile->type = "unsubscribe";
		 					if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
						    {
							    $profile->sourcer = $_SESSION['u'];
								$sourcertag = ",profile_".$_SESSION['u'];
						    }
						    else
						    {
							    $profile->sourcer = '';
								$sourcertag = "";
						    }
							$profile->save("profile,profile_".$wxopenid.$sourcertag);
							$newcontent = XN_Content::create('wxopenids','',false,4);
							$newcontent->my->profileid = $profile->profileid;
							$newcontent->my->wxopenid = $wxopenid;
							$newcontent->my->unionid = '';
							$newcontent->my->appid = self::$APPID;
							$newcontent->save("profile_".$wxopenid);
							$firsttime = 'true';
						}
						else
						{
							$profile = $profiles[0];
							$newcontent = XN_Content::create('wxopenids','',false,4);
							$newcontent->my->profileid = $profile->profileid;
							$newcontent->my->wxopenid = $wxopenid;
							$newcontent->my->unionid = '';
							$newcontent->my->appid = self::$APPID;
							$newcontent->save("profile_".$wxopenid);
						}
					}
	 			}
	 		}
	 		else
	 		{
	 				if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
					{
					    Header("Location: index.php");
						exit();
					}
					elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
					{
					     Header("Location: index.php");
						 exit();
					}
					else
					{
					    self::flushaccesstoken();
						throw new XN_Exception("请关闭窗口重新点击>>商城菜单<<进入!");
						return "";
					}

	 		}
			return $profile;
	 	}
	 }

	 public static function subscribe($appid,$profileid)
	 {
 		$subscribes = XN_Query::create ( 'Content' )
 						->filter ( 'type', 'eic', 'subscribes')
 						->filter ( 'my.deleted', '=', '0')
 						->filter ( 'my.profileid', '=', $profileid)
 						->filter ( 'my.wxappid', '=', $appid)
 						->end(1)
 						->execute ();
 		if (count($subscribes) > 0)
 		{
 			$subscribe_info = $subscribes[0];
			$subscribe_info->my->deleted="0";
 			$subscribe_info->my->watchstatus="1";
 			$subscribe_info->my->watchdate=date("Y-m-d H:i:s");
 			$subscribe_info->save("subscribes");
 		}
 		else
 		{
 			$newcontent = XN_Content::create('subscribes','',false);
			$newcontent->my->deleted="0";
 			$newcontent->my->profileid = $profileid;
 			$newcontent->my->wxappid = $appid;
 			$newcontent->my->watchstatus="1";
 			$newcontent->my->cancelwatchdate="-";
 			$newcontent->my->watchdate=date("Y-m-d H:i:s");
 			$newcontent->save("subscribes");
 		}
		$takecashs = XN_Query::create ( 'Content' )
						->filter ( 'type', 'eic', 'takecashs')
						->filter ( 'my.deleted', '=', '0')
						->filter ( 'my.profileid', '=', $profileid)
						//->filter ( 'my.takecashsstatus', 'in', array('待处理','处理中'))
						->end(-1)
						->execute ();
		if (count($takecashs) > 0)
		{
			foreach($takecashs as $takecash_info)
			{
				$takecash_info->my->subscribestatus = '0';
				$takecash_info->save("takecashs");
			}
		}
		$alipays = XN_Query::create ( 'Content' )
						->filter ( 'type', 'eic', 'alipays')
						->filter ( 'my.deleted', '=', '0')
						->filter ( 'my.profileid', '=', $profileid)
						->end(-1)
						->execute ();
		if (count($alipays) > 0)
		{
			foreach($alipays as $alipay_info)
			{
				$alipay_info->my->subscribestatus = '0';
				$alipay_info->save("alipays");
			}
		}
	 }

	 public static function unsubscribe($appid,$profileid)
	 {
		$subscribes = XN_Query::create ( 'Content' )
						->filter ( 'type', 'eic', 'subscribes')
						->filter ( 'my.deleted', '=', '0')
						->filter ( 'my.profileid', '=', $profileid)
						->filter ( 'my.wxappid', '=', $appid)
						->end(1)
						->execute ();
		if (count($subscribes) > 0)
		{
			$subscribe_info = $subscribes[0];
			$subscribe_info->my->watchstatus="2";
			$subscribe_info->my->deleted="0";
			$subscribe_info->my->cancelwatchdate=date("Y-m-d H:i:s");
			$subscribe_info->save("subscribes");
		}
		else
		{
			$newcontent = XN_Content::create('subscribes','',false);
			$newcontent->my->deleted="0";
			$newcontent->my->profileid = $profileid;
			$newcontent->my->wxappid = $appid;
			$newcontent->my->watchstatus="2";
			$newcontent->my->cancelwatchdate=date("Y-m-d H:i:s");
			$newcontent->my->watchdate="-";
			$newcontent->save("subscribes");
		}
		$takecashs = XN_Query::create ( 'Content' )
						->filter ( 'type', 'eic', 'takecashs')
						->filter ( 'my.deleted', '=', '0')
						->filter ( 'my.profileid', '=', $profileid)
						//->filter ( 'my.takecashsstatus', 'in', array('待处理','处理中'))
						->end(-1)
						->execute ();
		if (count($takecashs) > 0)
		{
			foreach($takecashs as $takecash_info)
			{
				$takecash_info->my->subscribestatus = '1';
				$takecash_info->save("takecashs");
			}
		}
		$alipays = XN_Query::create ( 'Content' )
						->filter ( 'type', 'eic', 'alipays')
						->filter ( 'my.deleted', '=', '0')
						->filter ( 'my.profileid', '=', $profileid)
						->end(-1)
						->execute ();
		if (count($alipays) > 0)
		{
			foreach($alipays as $alipay_info)
			{
				$alipay_info->my->subscribestatus = '1';
				$alipay_info->save("alipays");
			}
		}
	 }

	 public static function check_profile($wxopenid)
	 {
 	 	$wxopenids = XN_Query::create ( 'MainContent' )->tag("profile_".$wxopenid)
 	 						->filter ( 'type', 'eic', 'wxopenids')
 	 						->filter ( 'my.wxopenid', '=', $wxopenid)
 	 						->end(-1)
 	 						->execute ();
 	 	if (count($wxopenids) > 0 )
 	 	{
 	 		$wxopenid_info = $wxopenids[0];
 	 		$profileid = $wxopenid_info->my->profileid;
			$appid = $wxopenid_info->my->appid;
			if (!isset($appid) || $appid == "")
			{
				$wxopenid_info->my->appid = self::$APPID;
				$wxopenid_info->save("profile_".$wxopenid);
			}
 			return $profileid;
 	 	}
 	 	else
 	 	{

 	 	    $userinfo = self::getuserinfo($wxopenid);

 	 		if (is_array($userinfo))
 	 		{
 	 			if ($userinfo['subscribe'] == '1')
 	 			{
 		 					if (isset($userinfo['unionid']) && $userinfo['unionid'] != '')
 		 					{
 	 							$unionid = $userinfo['unionid'];
 	 							$wxopenids = XN_Query::create ( 'MainContent' )->tag("profile_".$wxopenid)
 	 											->filter ( 'type', 'eic', 'wxopenids')
 	 											->filter ( 'my.unionid', '=', $unionid)
 	 											->end(-1)
 	 											->execute ();
 	 							if (count($wxopenids) == 0 )
 	 							{
 	 							    $profiles = XN_Query::create ( 'Profile' )->tag('profile_'.$wxopenid)
 	 										->filter( 'wxopenid', '=', $wxopenid)
 	 										->begin(0)->end(1)
 	 										->execute ();
 	 								if (count($profiles)  ==  0 )
 	 								{
 	 									$application =  XN_Application::$CURRENT_URL;
 	 									$profile = XN_Profile::create ( $wxopenid, "123qwe" );
 	 									$profile->fullname = $wxopenid;
 	 									$profile->mobile = '';
 	 									$profile->status = 'True'; 

 	 									$nickname = str_replace("'","",$userinfo['nickname']);
 	 									$nickname = str_replace(" ","",$nickname);
 	 									$nickname = str_replace("\\","",$nickname);

 	 									$profile->givenname = $nickname;
 	 									$profile->country = $userinfo['country'];
 	 									$profile->province = $userinfo['province'];
 	 									$profile->city = $userinfo['city'];
 	 									$profile->link = $userinfo['headimgurl'];

 	 									if ($userinfo['sex'] == '1')
 	 									{
 	 										$profile->gender = '男';
 	 									}
 	 									else if($userinfo['sex'] == '2')
 	 									{
 	 										$profile->gender = '女';
 	 									}
 	 									else
 	 									{
 	 										//$profile->gender = '未知';
 	 									}
 	 									$profile->wxopenid = $wxopenid;
 	 									//$usip=$_SERVER['REMOTE_ADDR'];
 	 									//$profile->reg_ip = $usip;
										$profile->reg_ip = "";
 	 									$profile->type = "wxuser";
 	 							        $profile->unionid = $unionid;
 	 							        if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
									    {
										    $profile->sourcer = $_SESSION['u'];
									    }
									    else
									    {
										    $profile->sourcer = '';
									    }
 	 									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
 	 									$newcontent = XN_Content::create('wxopenids','',false,4);
 	 									$newcontent->my->profileid = $profile->profileid;
 	 									$newcontent->my->wxopenid = $wxopenid;
 	 									$newcontent->my->unionid = $userinfo['unionid'];
										$newcontent->my->appid = self::$APPID;
 	 									$newcontent->save("profile_".$wxopenid);
 	 								}
 	 								else
 	 								{
 	 									$profile = $profiles[0];
 	 									$nickname = str_replace("'","",$userinfo['nickname']);
 	 									$nickname = str_replace(" ","",$nickname);
 	 									$nickname = str_replace("\\","",$nickname);
 	 									$profile->givenname = $nickname;
 	 									$profile->country = $userinfo['country'];
 	 									$profile->province = $userinfo['province'];
 	 									$profile->city = $userinfo['city'];
 	 									$profile->link = $userinfo['headimgurl'];

 	 									if ($userinfo['sex'] == '1')
 	 									{
 	 										$profile->gender = '男';
 	 									}
 	 									else if($userinfo['sex'] == '2')
 	 									{
 	 										$profile->gender = '女';
 	 									}
 	 									$profile->type = "wxuser";
 	 							        $profile->unionid = $unionid;
 	 									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
 	 									$newcontent = XN_Content::create('wxopenids','',false,4);
 	 									$newcontent->my->profileid = $profile->profileid;
 	 									$newcontent->my->wxopenid = $wxopenid;
 	 									$newcontent->my->unionid = $unionid;
										$newcontent->my->appid = self::$APPID;
 	 									$newcontent->save("profile_".$wxopenid);
 	 								}
 	 							}
 	 							else
 	 							{
 	 								$wxopenid_info = $wxopenids[0];
 	 								$profileid = $wxopenid_info->my->profileid;
 									$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
  									$nickname = str_replace("'","",$userinfo['nickname']);
  									$nickname = str_replace(" ","",$nickname);
  									$nickname = str_replace("\\","",$nickname);
  									$profile->givenname = $nickname;
  									$profile->country = $userinfo['country'];
  									$profile->province = $userinfo['province'];
  									$profile->city = $userinfo['city'];
  									$profile->link = $userinfo['headimgurl'];

  									if ($userinfo['sex'] == '1')
  									{
  										$profile->gender = '男';
  									}
  									else if($userinfo['sex'] == '2')
  									{
  										$profile->gender = '女';
  									}
  									$profile->type = "wxuser";
  							        $profile->unionid = $unionid;
  									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);

 	 								$newcontent = XN_Content::create('wxopenids','',false,4);
 	 								$newcontent->my->profileid = $profileid;
 	 								$newcontent->my->wxopenid = $wxopenid;
 	 								$newcontent->my->unionid = $unionid;
									$newcontent->my->appid = self::$APPID;
 	 								$newcontent->save("profile_".$wxopenid);
 	 								$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
 	 							}
 	 					}
 	 					else
 	 					{
 	 						$application =  XN_Application::$CURRENT_URL;
 	 						$profile = XN_Profile::create ( $wxopenid, "123qwe" );
 	 						$profile->fullname = $wxopenid;
 	 						$profile->mobile = '';
 	 						$profile->status = 'True'; 

 	 						$nickname = str_replace("'","",$userinfo['nickname']);
 	 						$nickname = str_replace(" ","",$nickname);
 	 						$nickname = str_replace("\\","",$nickname);

 	 						$profile->givenname = $nickname;
 	 						$profile->country = $userinfo['country'];
 	 						$profile->province = $userinfo['province'];
 	 						$profile->city = $userinfo['city'];
 	 						$profile->link = $userinfo['headimgurl'];

 	 						if ($userinfo['sex'] == '1')
 	 						{
 	 							$profile->gender = '男';
 	 						}
 	 						else if($userinfo['sex'] == '2')
 	 						{
 	 							$profile->gender = '女';
 	 						}
 	 						else
 	 						{
 	 							//$profile->gender = '未知';
 	 						}
 	 						$profile->wxopenid = $wxopenid;
		 					//$usip=$_SERVER['REMOTE_ADDR'];
		 					//$profile->reg_ip = $usip;
		 					$profile->reg_ip = "";
 	 						$profile->type = "wxuser";
 	 				        $profile->unionid = '';
 	 				        if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
						    {
							    $profile->sourcer = $_SESSION['u'];
						    }
						    else
						    {
							    $profile->sourcer = '';
						    }
 	 						$profile->save("profile,profile_".$wxopenid);
 	 						$newcontent = XN_Content::create('wxopenids','',false,4);
 	 						$newcontent->my->profileid = $profile->profileid;
 	 						$newcontent->my->wxopenid = $wxopenid;
 	 						$newcontent->my->unionid = '';
							$newcontent->my->appid = self::$APPID;
 	 						$newcontent->save("profile_".$wxopenid);
 	 					}
 	 			}
 	 			else
 	 			{
  					if (isset($userinfo['unionid']) && $userinfo['unionid'] != '')
  					{
 						$unionid = $userinfo['unionid'];
 						$wxopenids = XN_Query::create ( 'MainContent' )->tag("profile_".$wxopenid)
 										->filter ( 'type', 'eic', 'wxopenids')
 										->filter ( 'my.unionid', '=', $unionid)
 										->end(-1)
 										->execute ();
 						if (count($wxopenids) == 0 )
 						{
 						    $profiles = XN_Query::create ( 'Profile' )->tag('profile_'.$wxopenid)
 									->filter( 'wxopenid', '=', $wxopenid)
 									->begin(0)->end(1)
 									->execute ();
 							if (count($profiles)  ==  0 )
 							{
 					    		$application =  XN_Application::$CURRENT_URL;
 					    		$profile = XN_Profile::create ( $wxopenid, "123qwe" );
 								$profile->fullname = $wxopenid;
 								$profile->mobile = '';
 								$profile->status = 'True'; 
 								$profile->givenname = '';
 								$profile->wxopenid = $wxopenid;
			 					//$usip=$_SERVER['REMOTE_ADDR'];
			 					//$profile->reg_ip = $usip;
			 					$profile->reg_ip = "";
 								$profile->unionid = $unionid;
 								$profile->type = "unsubscribe";
 								if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
							    {
								    $profile->sourcer = $_SESSION['u'];
							    }
							    else
							    {
								    $profile->sourcer = '';
							    }
 								$profile->save("profile,profile_".$wxopenid);
 								$newcontent = XN_Content::create('wxopenids','',false,4);
 								$newcontent->my->profileid = $profile->profileid;
 								$newcontent->my->wxopenid = $wxopenid;
 								$newcontent->my->unionid = $userinfo['unionid'];
								$newcontent->my->appid = self::$APPID;
 								$newcontent->save("profile_".$wxopenid);
 							}
 							else
 							{
 								$profile = $profiles[0];
 								if ($profile->unionid != $unionid)
 								{
 									$profile->unionid = $unionid;
 									$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
 								}
 								$newcontent = XN_Content::create('wxopenids','',false,4);
 								$newcontent->my->profileid = $profile->profileid;
 								$newcontent->my->wxopenid = $wxopenid;
 								$newcontent->my->unionid = $unionid;
								$newcontent->my->appid = self::$APPID;
 								$newcontent->save("profile_".$wxopenid);
 							}
 						}
 						else
 						{
 							$wxopenid_info = $wxopenids[0];
 							$profileid = $wxopenid_info->my->profileid;
 							$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
 							if ($profile->unionid != $unionid)
 							{
 								$profile->unionid = $unionid;
 								$profile->save("profile,profile_".$profile->profileid.",profile_".$profile->wxopenid);
 							}

 							$newcontent = XN_Content::create('wxopenids','',false,4);
 							$newcontent->my->profileid = $profileid;
 							$newcontent->my->wxopenid = $wxopenid;
 							$newcontent->my->unionid = $unionid;
							$newcontent->my->appid = self::$APPID;
 							$newcontent->save("profile_".$wxopenid);
 							$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
 						}
 					}
 					else
 					{
 					    $profiles = XN_Query::create ( 'Profile' )->tag('profile_'.$wxopenid)
 								->filter( 'wxopenid', '=', $wxopenid)
 								->begin(0)->end(1)
 								->execute ();
 						if (count($profiles)  ==  0 )
 						{
 				    		$application =  XN_Application::$CURRENT_URL;
 				    		$profile = XN_Profile::create ( $wxopenid, "123qwe" );
 							$profile->fullname = $wxopenid;
 							$profile->mobile = '';
 							$profile->status = 'True';
 							$profile->givenname = '';
 							$profile->wxopenid = $wxopenid;
		 					//$usip=$_SERVER['REMOTE_ADDR'];
		 					//$profile->reg_ip = $usip;
		 					$profile->reg_ip = "";
 							$profile->unionid = '';
 							$profile->type = "unsubscribe";
 							if (isset($_SESSION['u']) && $_SESSION['u'] != "" && $_SESSION['u'] != $profile->profileid)
						    {
							    $profile->sourcer = $_SESSION['u'];
						    }
						    else
						    {
							    $profile->sourcer = '';
						    }
 							$profile->save("profile,profile_".$wxopenid);
 							$newcontent = XN_Content::create('wxopenids','',false,4);
 							$newcontent->my->profileid = $profile->profileid;
 							$newcontent->my->wxopenid = $wxopenid;
 							$newcontent->my->unionid = '';
							$newcontent->my->appid = self::$APPID;
 							$newcontent->save("profile_".$wxopenid);
 						}
 						else
 						{
 							$profile = $profiles[0];
 							$newcontent = XN_Content::create('wxopenids','',false,4);
 							$newcontent->my->profileid = $profile->profileid;
 							$newcontent->my->wxopenid = $wxopenid;
 							$newcontent->my->unionid = '';
							$newcontent->my->appid = self::$APPID;
 							$newcontent->save("profile_".$wxopenid);
 						}
 					}
 	 			}
 	 		}
 	 		else
 	 		{
 	 			self::flushaccesstoken();
 				return "";
 	 		}
 			return $profile->profileid;
 	 	}
	 }

	 public static function geocoder($latitude,$longitude)
	 	{
	 	    $url = sprintf('http://api.map.baidu.com/geocoder/v2/?output=json&location=%s,%%20%s&ak=8tz6qyjPPVAwqq5FMy24sXrg',$latitude,$longitude);

	 		$response = self::get($url);

	 		$json = json_decode($response);

	 		$location = array();
	 		if ($json->status == "0")
	 		{
	 			$result = $json->result;
	 			$addressComponent = $result->addressComponent;
	 			$location['formatted_address'] = $result->formatted_address;
	 			$location['business'] = $result->business;
	 			$location['cityCode'] = $result->cityCode;
	 			$location['province'] = $addressComponent->province;
	 			$location['city'] = $addressComponent->city;
	 			$location['district'] = $addressComponent->district;
	 			$location['street'] = $addressComponent->street;
	 			$location['street_number'] = $addressComponent->street_number;
	 		}
	 		return $location;
	 	}


	public static function sendimagemessage($openid,$mediaid)
	{
		    self::initaccesstoken();
			if (self::$ACCESS_TOKEN != null)
			{
			    $url = sprintf('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s',self::$ACCESS_TOKEN);

				$body = sprintf('{"touser":"%s","msgtype":"image","image":{"media_id":"%s"}}',$openid,$mediaid);

				self::post($url,$body);
			}
	}

	public static function sendtextmessage($openid,$msgcontent)
	{
		    self::initaccesstoken();
			if (self::$ACCESS_TOKEN != null)
			{
			    $url = sprintf('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s',self::$ACCESS_TOKEN);

				$body = sprintf('{"touser":"%s","msgtype":"text","text":{"content":"%s"}}',$openid,$msgcontent);

				$response = self::post($url,$body);
				$json = json_decode($response);
				if ($json->errcode != "0")
				{
					 self::flushaccesstoken();
	 			     $url = sprintf('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s',self::$ACCESS_TOKEN);
				     self::post($url,$body);
				}
			}
	}

	public static function sendmessage($profileid,$msgcontent)
	{
		   $profile = XN_Profile::load($profileid,"id","profile_".$profileid);
		   $openid = $profile->wxopenid;
		   if ($openid != "")
		   {

			    global $WX_APPID;

			    require_once ($_SERVER['DOCUMENT_ROOT']."/ttwz/config.inc.php");

				$wxsettings = XN_Query::create('Content')
						->tag('wxsettings')
						->filter('type','eic','wxsettings')
						->filter('my.deleted','=','0')
						->filter('my.appid','=',$WX_APPID)
						->begin(0)
						->end(-1)
						->execute();
				if(count($wxsettings) > 0)
				{
					$wxsetting_info = $wxsettings[0];
			   		$appid = $wxsetting_info->my->appid;
			   		$secret = $wxsetting_info->my->secret;

					$WXID = $wxsetting_info->id;
			   		self::$APPID = $appid;
			   		self::$SECRET = $secret;

				    self::initaccesstoken();
					if (self::$ACCESS_TOKEN != null)
					{
					    $url = sprintf('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s',self::$ACCESS_TOKEN);

						$body = sprintf('{"touser":"%s","msgtype":"text","text":{"content":"%s"}}',$openid,$msgcontent);

						self::post($url,$body);

					 	$wxservices = XN_Query::create('Content')
								->tag('wxservices')
								->filter('type','eic','wxservices')
								->filter('my.deleted','=','0')
								->filter('my.wxid','=',$WXID)
								->filter('my.fromprofileid','=',$profileid)
								->order("published",XN_Order::DESC)
								->begin(0)
								->end(1)
								->execute();
						if (count($wxservices) == 0 )
						{
							$newcontent = XN_Content::create('wxservices','',false);
							$newcontent->my->wxid = $WXID;
							$newcontent->my->fromusername = $openid;
							$newcontent->my->fromprofileid = $profileid;
							$newcontent->my->tousername = "";
							$newcontent->my->wxmsgtype = "text";
							$newcontent->my->msgid = "";
							$newcontent->my->msgcontent = $msgcontent;
							$newcontent->my->picurl = "";
							$newcontent->my->mediaid = "";
							$newcontent->my->thumbmediaid = "";
							$newcontent->my->replycount = "1";
							$newcontent->my->customservice = "-";
							$newcontent->my->receivetime = strtotime("now");
							$newcontent->my->lastreplaytime = "-";
							$newcontent->my->deleted = "0";
							$newcontent->save('wxservices');

							$wxserviceid = $newcontent->id;
							$newcontent = XN_Content::create('wxreplys','',false);
						    $newcontent->my->record  = $wxserviceid;
							$newcontent->my->reply = $msgcontent;
							$newcontent->my->customservice = $profileid;
							$newcontent->save('wxreplys');
						}
						else
						{
							$wxservice_info = $wxservices[0];
							$wxservice_info->my->msgcontent = $msgcontent;
							$replycount = $wxservice_info->my->replycount;
							$wxservice_info->my->replycount = intval($replycount)+1;
							$wxservice_info->my->receivetime = strtotime("now");
							$wxservice_info->save('wxservices');

							$wxserviceid = $wxservice_info->id;
							$newcontent = XN_Content::create('wxreplys','',false);
						    $newcontent->my->record  = $wxserviceid;
							$newcontent->my->reply = $msgcontent;
							$newcontent->my->customservice = $profileid;
							$newcontent->save('wxreplys');
						}
					}
				}
		   }
	}


	public static function getoauthuserinfo($openid,$access_token)
	{
	    $url = sprintf('https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN',$access_token,$openid);

		$response = self::get($url);

		$response = str_replace("","",$response);
		$response = str_replace("\\n","",$response);

		$json = json_decode($response);

		if (!is_null($json->openid))
		{
			$userinfo = array();
			$userinfo['subscribe'] = $json->subscribe;
			$userinfo['nickname'] = $json->nickname;
			$userinfo['sex'] = $json->sex;
			$userinfo['language'] = $json->language;
			$userinfo['city'] = $json->city;
			$userinfo['province'] = $json->province;
			$userinfo['country'] = $json->country;
			$userinfo['headimgurl'] = $json->headimgurl;
			$userinfo['subscribe_time'] = $json->subscribe_time;
			$userinfo['unionid'] = $json->unionid;
			return  $userinfo;
		}
		return null;
	}
	public static function getuserinfo($openid)
	{
		    self::initaccesstoken();
			if (self::$ACCESS_TOKEN != null)
			{
			    $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',self::$ACCESS_TOKEN,$openid);

				$response = self::get($url);

				$response = str_replace("","",$response);
				$response = str_replace("\\n","",$response);

				$json = json_decode($response);

				if (!is_null($json->openid))
				{
					$userinfo = array();
					$userinfo['subscribe'] = $json->subscribe;
					$userinfo['nickname'] = $json->nickname;
					$userinfo['sex'] = $json->sex;
					$userinfo['language'] = $json->language;
					$userinfo['city'] = $json->city;
					$userinfo['province'] = $json->province;
					$userinfo['country'] = $json->country;
					$userinfo['headimgurl'] = $json->headimgurl;
					$userinfo['subscribe_time'] = $json->subscribe_time;
					$userinfo['unionid'] = $json->unionid;
					return  $userinfo;
				}
				else
				{
					self::flushaccesstoken();
					$url = sprintf('https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',self::$ACCESS_TOKEN,$openid);

					$response = self::get($url);

					$response = str_replace("","",$response);
					$response = str_replace("\\n","",$response);

					$json = json_decode($response);

					if (!is_null($json->openid))
					{
						$userinfo = array();
						$userinfo['subscribe'] = $json->subscribe;
						$userinfo['nickname'] = $json->nickname;
						$userinfo['sex'] = $json->sex;
						$userinfo['language'] = $json->language;
						$userinfo['city'] = $json->city;
						$userinfo['province'] = $json->province;
						$userinfo['country'] = $json->country;
						$userinfo['headimgurl'] = $json->headimgurl;
						$userinfo['subscribe_time'] = $json->subscribe_time;
						$userinfo['unionid'] = $json->unionid;
						return  $userinfo;
					}
					else
					{
			 			if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
						{
						    Header("Location: index.php");
							exit();
						}
						elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
						{
						     Header("Location: index.php");
							 exit();
						}
						else
						{
							throw new XN_Exception("微信接口异常，请与管理员联系!");
							return "";
						}
					}
				}
			}
			return null;
	}
	public static function qrcode($scene_id,$expire_seconds=null)
	{
		    self::initaccesstoken();
			if (self::$ACCESS_TOKEN != null)
			{
			    $url = sprintf('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s',self::$ACCESS_TOKEN);

				if ($expire_seconds==null)
				{
					$body = sprintf('{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": %s}}}',$scene_id);
				}
				else
				{
					$body = sprintf('{"expire_seconds": %s,"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": %s}}}',$expire_seconds,$scene_id);
				}

				$response = self::post($url,$body);

				$json = json_decode($response);

				if (!is_null($json->ticket))
				{
					$ticket = $json->ticket;
					return $json->ticket;
				}
				else
				{
					self::flushaccesstoken();
					$response = self::post($url,$body);

					$json = json_decode($response);

					if (!is_null($json->ticket))
					{
						$ticket = $json->ticket;
						return $json->ticket;
					}
				}

			}

			return null;
	}
	//调用微信接口创建标准菜单
	public static function synchromenus($menujson=null)
	{
		    $url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
			$body = self::get($url);
			$json = json_decode($body);
			if (!is_null($json->access_token))
			{
				self::$ACCESS_TOKEN = $json->access_token;
			}
			else
            {
                return $json->errcode.':'.$json->errmsg;
            }
			if (self::$ACCESS_TOKEN != null)
			{
				if ($menujson==null)
				{
					$url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s',self::$ACCESS_TOKEN);
				}
				else
				{
				    $url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s',self::$ACCESS_TOKEN);
				}
				$response = self::post($url,$menujson);

				$json = json_decode($response);
				if ($json->errcode == 0 && $json->errmsg == "ok")
				{
					return "ok";
				}
				else
				{
					return $json->errcode.':'.$json->errmsg;
				}
			}

			return null;
	}

	public static function header_to_array($response)
	{
	    $headers = array();
	    foreach (explode("\r\n", $response) as $i => $line)
	        if ($i != 0)
	        {
	            list ($key, $value) = explode(': ', $line);
	            $headers[$key] = $value;
	        }
	    return $headers;
	}
    public static function decideFilePath()
    {
        $filepath = 'storage';

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/'.XN_Application::$CURRENT_URL;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }

        $year  = date('Y');
        $month = date('F');
        $day  = date('md');
        $week   = '';

        $filepath .= '/'.$year;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/'.$month;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/'.$day;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/';

        return $filepath;
    }
    public static function decide_weixin_FilePath()
    {
        $filepath = 'storage';


        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }

        $filepath .= '/weixin';
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }

        $filepath .= '/'.XN_Application::$CURRENT_URL;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }

        $year  = date('Y');
        $month = date('F');
        $day  = date('md');
        $week   = '';

        $filepath .= '/'.$year;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/'.$month;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/'.$day;

        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath))
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$filepath);
        }
        $filepath .= '/';

        return $filepath;
    }

    public static function download_weixin_sound($mediaid)
	{
	    self::initaccesstoken();
		$upload_file_path = "";
		$savefile = "";
		if (self::$ACCESS_TOKEN != null)
		{
		    $url = sprintf('http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s',self::$ACCESS_TOKEN,$mediaid);
 			$res = self::fget($url);
			list($head, $body) = explode("\r\n\r\n", $res, 2);
			$headers = self::header_to_array($head);
			$content_type = $headers['Content-Type'];
			$filesize = $headers['Content-Length'];
			$Content_disposition = $headers['Content-disposition'];
			preg_match('/"(.*)"/i',$Content_disposition, $matches);

			if (count($matches) > 0)
			{
				    $disposition = $matches[1];

		            $upload_file_path =  "/". self::decide_weixin_FilePath();
		            $guid = date("YmdHis"). floor(microtime()*1000);
		            $savefile = $guid.".".end(explode('.', $disposition));

					$bin_filename = $_SERVER['DOCUMENT_ROOT'].$upload_file_path.$savefile;
					$fp = fopen($bin_filename,'w+');
				    fwrite($fp,$body);
				    fclose($fp);
			}
		}
		return $upload_file_path.$savefile;
	}

    public static function download_weixin_image($mediaid)
	{
	    self::initaccesstoken();
		$upload_file_path = "";
		$savefile = "";
		if (self::$ACCESS_TOKEN != null)
		{
		    $url = sprintf('http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s',self::$ACCESS_TOKEN,$mediaid);
 			$res = self::fget($url);
			list($head, $body) = explode("\r\n\r\n", $res, 2);
			$headers = self::header_to_array($head);
			$content_type = $headers['Content-Type'];
			$filesize = $headers['Content-Length'];
			$Content_disposition = $headers['Content-disposition'];
			preg_match('/"(.*)"/i',$Content_disposition, $matches);

			if (count($matches) > 0)
			{
				    $disposition = $matches[1];

		            $upload_file_path =  "/". self::decide_weixin_FilePath();
		            $guid = date("YmdHis"). floor(microtime()*1000);
		            $savefile = $guid.".".end(explode('.', $disposition));

					$bin_filename = $_SERVER['DOCUMENT_ROOT'].$upload_file_path.$savefile;
					$fp = fopen($bin_filename,'w+');
				    fwrite($fp,$body);
				    fclose($fp);
			}
		}
		return $upload_file_path.$savefile;
	}
	public static function downloadimage($mediaid,$record="",$module="")
	{
	    self::initaccesstoken();
		$upload_file_path = "";
		$savefile = "";
		if (self::$ACCESS_TOKEN != null)
		{
		    $url = sprintf('http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s',self::$ACCESS_TOKEN,$mediaid);
 			$res = self::fget($url);
			list($head, $body) = explode("\r\n\r\n", $res, 2);
			$headers = self::header_to_array($head);
			$content_type = $headers['Content-Type'];
			$filesize = $headers['Content-Length'];
			$Content_disposition = $headers['Content-disposition'];
			//$Content_disposition = 'attachment; filename="media_id.jpg"';
			preg_match('/"(.*)"/i',$Content_disposition, $matches);

			if (count($matches) > 0)
			{
				$disposition = $matches[1];
				$hashval = md5($body);
		        $attachments =XN_Query::create("MainContent")
			            ->filter("type","eic","attachments")
			            ->filter("my.hashval","=",$hashval)
			            ->end(1)
			            ->execute();
		        if(count($attachments)>0)
				{
		            $attach_info=$attachments[0];
		            $mime_type= $attach_info->my->type;
		            $filesize = $attach_info->my->filesize;
		            $savefile = $attach_info->my->savefile;
		            $upload_file_path=$attach_info->my->path;
		            $filename=$attach_info->my->name;
		            if(!file_exists($_SERVER['DOCUMENT_ROOT'].$upload_file_path.$savefile))
					{
		                if(!is_dir($_SERVER['DOCUMENT_ROOT'].$upload_file_path))
						{
		                    @mkdir($_SERVER['DOCUMENT_ROOT'].$upload_file_path, 0777, true);
		                }
						$bin_filename = $_SERVER['DOCUMENT_ROOT'].$upload_file_path.$savefile;
						$fp = fopen($bin_filename,'w+');
					    fwrite($fp,$body);
					    fclose($fp);
		            }
				}
				else
				{
		            $upload_file_path =  "/". self::decideFilePath();
		            $guid = date("YmdHis"). floor(microtime()*1000);
		            $savefile = $guid.".".end(explode('.', $disposition));

					$bin_filename = $_SERVER['DOCUMENT_ROOT'].$upload_file_path.$savefile;
					$fp = fopen($bin_filename,'w+');
				    fwrite($fp,$body);
				    fclose($fp);

		            XN_Content::create('attachments','',false)
		                ->my->add('name',$disposition)
		                ->my->add('module',$module)
		                ->my->add('record',$record)
		                ->my->add('filesize',$filesize)
		                ->my->add('type',$content_type)
		                ->my->add('path',$upload_file_path)
		                ->my->add('savefile',$savefile)
		                ->my->add('sequence','0')
		                ->my->add('hashval',$hashval)
		                ->my->add('deleted','0')
		                ->save('attachments');
				}
			}
		}
		return $upload_file_path.$savefile;
	}
	public static function uploadimage($image)
	{
		self::initaccesstoken();

		if (self::$ACCESS_TOKEN != null)
		{
			try
			{
				    $body = self::httpget($image,20);
					if (isset($body) && $body != "")
					{
						$hashval = md5($body);
						if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/storage/qrcode'))
						{
		                    @mkdir($_SERVER['DOCUMENT_ROOT'].'/storage/qrcode', 0777, true);
		                }

						$binfile = $_SERVER['DOCUMENT_ROOT'].'/storage/qrcode/'.$hashval.'.jpg';
						$fp = fopen($binfile,'w+');
					    fwrite($fp,$body);
					    fclose($fp);

						$url = sprintf('https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=image',self::$ACCESS_TOKEN);
			 			$response = self::fpost($url,$binfile);

			 			@unlink($binfile);

			 			$json = json_decode($response);

						if (!is_null($json->media_id))
						{
							return $json->media_id;
						}
					}
			}
			catch (XN_Exception $e)
			{

			}
 		}
 		return null;
	}

	 public static function fpost($url,$binfile)
	{
		 $curlObj = curl_init();
		 curl_setopt($curlObj, CURLOPT_URL, $url); // 设置访问的url
		 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array());
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);
		 curl_setopt($curlObj, CURLOPT_BINARYTRANSFER,true);
		 curl_setopt($curlObj, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

         curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'POST');

		 curl_setopt($curlObj, CURLOPT_POST, true);
		 $post_data ['media']  = '@'.$binfile;
         curl_setopt($curlObj, CURLOPT_POSTFIELDS, $post_data);
		 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');

		 $res = @curl_exec($curlObj);

		 curl_close($curlObj);

		 if ($res === false) {
             return null;
        }
		return $res;
	}

	public  static function  httpget($url, $timeout = 3)
	{
	     $curlObj = curl_init();
		 curl_setopt($curlObj, CURLOPT_URL, $url); // 设置访问的url
		 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array());
		 curl_setopt($curlObj, CURLOPT_TIMEOUT, $timeout);
         curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'GET');
         curl_setopt($curlObj, CURLOPT_HTTPGET, true);
		 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');
		 $res = @curl_exec($curlObj);

		 if ($res === false) {
            $errno = curl_errno($curlObj);
            if ($errno == CURLE_OPERATION_TIMEOUTED) {
                $msg = "Request Timeout: " . self::getRequestTimeout() . " seconds exceeded";
            } else {
                $msg = curl_error($curlObj);
            }
			 curl_close($curlObj);
            $e = new XN_TimeoutException($msg);
            throw $e;
        }
		 curl_close($curlObj);
         return $res;
	}
	public static function fget($url)
	{
		 $curlObj = curl_init();
		 curl_setopt($curlObj, CURLOPT_URL, $url); // 设置访问的url
		 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array());
	     curl_setopt($curlObj, CURLOPT_URL, $url);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($curlObj, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

         curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'GET');
         curl_setopt($curlObj, CURLOPT_HTTPGET, true);
		 curl_setopt($curlObj, CURLOPT_HEADER, TRUE);  //表示需要response header
		 curl_setopt($curlObj, CURLOPT_NOBODY, FALSE); //表示需要response body

		 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');
		 $res = @curl_exec($curlObj);

		 if ($res === false) {
	           $errno = curl_errno($curlObj);
	           if ($errno == CURLE_OPERATION_TIMEOUTED) {
	               $msg = "Request Timeout: " . self::getRequestTimeout() . " seconds exceeded";
	           } else {
	               $msg = curl_error($curlObj);
	           }
			    curl_close($curlObj);
	           $e = new XN_TimeoutException($msg);
	           throw $e;
	       }
		 curl_close($curlObj);
		 return $res;
	}
	 public static function get($url)
	{
		 $curlObj = curl_init();
		 curl_setopt($curlObj, CURLOPT_URL, $url); // 设置访问的url
		 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array());
         curl_setopt($curlObj, CURLOPT_URL, $url);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($curlObj, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

         curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'GET');
         curl_setopt($curlObj, CURLOPT_HTTPGET, true);

		 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');
		 $res = @curl_exec($curlObj);
		 if ($res === false) {
            $errno = curl_errno($curlObj);
            if ($errno == CURLE_OPERATION_TIMEOUTED) {
                $msg = "Request Timeout: " . self::getRequestTimeout() . " seconds exceeded";
            } else {
                $msg = curl_error($curlObj);
            }
			curl_close($curlObj);
            $e = new XN_TimeoutException($msg);
            throw $e;
        }
		 curl_close($curlObj);
		 return $res;
	}
	 public static function post($url,$body)
	{
		 $curlObj = curl_init();
		 curl_setopt($curlObj, CURLOPT_URL, $url); // 设置访问的url
		 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行
		 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array());
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);
		 curl_setopt($curlObj, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

         curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'POST');

		 curl_setopt($curlObj, CURLOPT_POST, true);
         curl_setopt($curlObj, CURLOPT_POSTFIELDS, $body);
		 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');

		 $res = @curl_exec($curlObj);

		 if ($res === false) {
            $errno = curl_errno($curlObj);
            if ($errno == CURLE_OPERATION_TIMEOUTED) {
                $msg = "Request Timeout: " . self::getRequestTimeout() . " seconds exceeded";
            } else {
                $msg = curl_error($curlObj);
            }
			curl_close($curlObj);
            $e = new XN_TimeoutException($msg);
            throw $e;
        }
		curl_close($curlObj);
		return $res;
	}
	//调用微信接口创建标准自定义菜单|lihongfei|2017-7-13
	public static function synchro_condition_menus($menujson=null)
	{
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
		$body = self::get($url);
		$json = json_decode($body);
		if (!is_null($json->access_token))
		{
			self::$ACCESS_TOKEN = $json->access_token;
		}
		if (self::$ACCESS_TOKEN != null)
		{
			if ($menujson==null)
			{
				$url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=%s',self::$ACCESS_TOKEN);
			}
			else
			{
				$url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=%s',self::$ACCESS_TOKEN);
			}
			$response = self::post($url,$menujson);

			$json = json_decode($response);
			if ($json->menuid > 0 )
			{
				return "ok";
			}
			else{
                return "创建个性化菜单失败";
            }
		}
		return null;
	}
	//调用微信接口创建用户分组标签|lihongfei|2017-7-14
	public static function create_user_tag($tagjson=null)
	{
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
		$body = self::get($url);
		$json = json_decode($body);
		if (!is_null($json->access_token))
		{
			self::$ACCESS_TOKEN = $json->access_token;
		}
		if (self::$ACCESS_TOKEN != null)
		{
			$url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/create?access_token=%s&appid=%s&secret=%s',self::$ACCESS_TOKEN,self::$APPID,self::$SECRET);
			$response = self::post($url,$tagjson);
			$json = json_decode($response);
			if($json->errcode==-1){
				return '系统繁忙';
			}
			elseif($json->errcode==45157){
				return '标签名非法，请注意不能和其他标签重名';
			}
			elseif($json->errcode==45158){
				return '标签名长度超过30个字节';
			}
			elseif($json->errcode==45056){
				return '创建的标签数过多，请注意不能超过100个';
			}
			else{
				return $json->tag->id;
			}
		}
		return null;
	}
    //调用微信接口根据标签名获取用户分组标签|lihongfei|2017-7-14
	public static function get_user_tag()
	{
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
		$body = self::get($url);
		$json = json_decode($body);
		if (!is_null($json->access_token))
		{
			self::$ACCESS_TOKEN = $json->access_token;
		}
		if (self::$ACCESS_TOKEN != null)
		{
			$url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/get?access_token=%s&appid=%s&secret=%s',self::$ACCESS_TOKEN,self::$APPID,self::$SECRET);
			$response = self::get($url);
			$json = json_decode($response,true);
            return $json;
		}
		return null;
	}
	//调用微信接口把会员加入用户分组标签|lihongfei|2017-7-14
	public static function add_userto_tag($taguserjson=null)
	{
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
		$body = self::get($url);
		$json = json_decode($body);
		if (!is_null($json->access_token))
		{
			self::$ACCESS_TOKEN = $json->access_token;
		}
		if (self::$ACCESS_TOKEN != null)
		{
			$url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=%s&appid=%s&secret=%s',self::$ACCESS_TOKEN,self::$APPID,self::$SECRET);
			$response = self::post($url,$taguserjson);
			$json = json_decode($response);
			if ($json->errcode == 0 && $json->errmsg == "ok")
			{
				return "ok";
			}
            elseif($json->errcode==-1){
                return $json->errcode.':系统繁忙';
            }
            elseif($json->errcode==40032){
                return $json->errcode.':每次传入的openid列表个数不能超过50个';
            }
            elseif($json->errcode==45159){
                return $json->errcode.':非法的标签';
            }
            elseif($json->errcode==40003){
                return $json->errcode.':传入非法的openid';
            }
            elseif($json->errcode==49003){
                return $json->errcode.':传入的openid不属于此公众号，请联系此用户重新关注以获取最新openid';
            }
			return null;
		}
	}
	//调用微信接口创建标准自定义菜单|lihongfei|2017-7-13
	public static function get_menuinfos()
	{
		$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',self::$APPID,self::$SECRET);
		$body = self::get($url);
		$json = json_decode($body);
		if (!is_null($json->access_token))
		{
			self::$ACCESS_TOKEN = $json->access_token;
		}
		if (self::$ACCESS_TOKEN != null)
		{
			$url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s',self::$ACCESS_TOKEN);
			$response = self::get($url);
			$json = json_decode($response);
			return $json;
		}
		return null;
	}
}
