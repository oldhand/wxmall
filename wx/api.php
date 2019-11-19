<?php
/**
 * wechat php test
 */
 
XN_Application::$CURRENT_URL = "admin";
 
 
ini_set('memory_limit','256M'); 

$default_timezone = 'PRC'; 


if(isset($default_timezone) && function_exists('date_default_timezone_set')) {
	@date_default_timezone_set($default_timezone);
}
//define your token
require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
require_once(dirname(__FILE__) . "/../config.common.php");
global $wxsetting;

if (isset($_GET['appid']) && $_GET['appid'] != '')
{
    $appid = $_GET['appid'];
    check_wxsetting_config($appid);
}
else
{
    echo 'no appid';
    die();
}


$wechatObj = new wechatCallbackapi();
$wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapi
{
    protected static $APPID = null;
    protected static $ORIGINALID = null;
    protected static $SECRET = null;
    protected static $WXNAME = null;
    protected static $WXID = null;
    protected static $DEFAULTREPLY = null;
    protected static $WELCOMEWORDS = null;

    protected static $description = null;
    protected static $replay = null;
    protected static $wxroletype = null;
    protected static $replytitle = null;
    protected static $image = null;
    protected static $replyid = null;
    protected static $supplierid = null;

    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if ($this->checkSignature())
        {
            if (isset($echoStr) && $echoStr != "")
            {
                echo $echoStr;
                exit;
            }
        }
    }


    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        self::log('responseMsg => ' . $postStr);
        //extract post data
        if (!empty($postStr))
        {

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->handleText($postObj);
                    break;
                case "event":
                    $resultStr = $this->handleEvent($postObj);
                    break;
                case "image":
                    $resultStr = $this->responseText($postObj, "您提交了一个图片。");
                    break;
                case "location":
                    $resultStr = $this->responseText($postObj, "您提交了一个地址。");
                    break;
                case "voice":
                    $resultStr = $this->responseText($postObj, "您提交了一个语音。");
                    break;
                case "video":
                    $resultStr = $this->responseText($postObj, "您提交了一个视频。");
                    break;
                case "link":
                    $resultStr = $this->responseText($postObj, "您提交了一个链接。");
                    break;
                default:
                    $resultStr = $this->responseText($postObj, "Unknow msg type: " . $RX_TYPE);
                    break;
            }
            echo $resultStr;

        }
        else
        {
            echo "";
            exit;
        }
    }

    public function log($info)
    {
        $fp = fopen('log.txt', 'a');
        fwrite($fp, $info . "\r\n\r\n");
        fclose($fp);
    }

    public function check_repeat_submit($key)
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
            XN_MemCache::put(strtotime("now"), $key, "20");
        }
        return false;
    }
	public function subscribe($openid,$subscribe = '0')
	{
		XN_Application::$CURRENT_URL = 'admin';
        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $openid)
            ->filter('type', 'eic', 'supplier_profile') 
            ->filter('my.deleted', '=', '0')
            ->filter('my.wxopenid', '=', $openid)
            ->end(1)
            ->execute();
        if (count($supplier_profile) > 0)
        {
			$supplier_profile_info = $supplier_profile[0];
			$supplier_profile_info->my->subscribe = $subscribe;
			$profileid = $supplier_profile_info->my->profileid;
			$supplierid = $supplier_profile_info->my->supplierid;
			$tag = "supplier_profile,supplier_profile_" . $openid . ",supplier_profile_" . $profileid. ",supplier_profile_" . $supplierid;
			$supplier_profile_info->save($tag);
		}
	}

    public function handleEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "VIEW":
            case "view":
                return "";
            case "subscribe":
                if ($object->Ticket != null && $object->Ticket != "" && $object->FromUserName != null && $object->FromUserName != "")
                {
                    $ticket = $object->Ticket;
                    $openid = $object->FromUserName;
                    
                    self::log('subscribe: $ticket =>' . $ticket);
                    self::profile_scan($object,$openid,$ticket);
					self::subscribe($openid,'0');
                     
                }
                elseif ($object->FromUserName != null && $object->FromUserName != "")
                {
                    $openid = $object->FromUserName;
					self::subscribe($openid,'0');
                }
                $contentStr = self::$WELCOMEWORDS;
                if ($object->FromUserName != null)
                {
                    $openid = $object->FromUserName;
					self::subscribe($openid,'0');
                    str_replace('$openid', $openid, $contentStr);
                }
                break; 
            case  "unsubscribe":
				$contentStr = "";
                $identifier = '';
				if ($object->FromUserName != null && $object->FromUserName != "")
                {
                    $openid = $object->FromUserName;
					self::subscribe($openid,'1');
                }
				return ""; 
			case "SCAN":
			    $contentStr = $this->handleScan($object);
                //$resultStr = "扫描场景 ".$object->EventKey;
                break;
            case "CLICK":
                $contentStr = $this->handleClick($object);
                //$resultStr = "点击菜单：".$object->EventKey; 
                break;
            case "LOCATION":
                $openid = $object->FromUserName;
                $originalid = $object->ToUserName;
                $latitude = $object->Latitude;
                $longitude = $object->Longitude;
				
				if (self::check_repeat_submit("location_" . $openid)) return "";
                //$contentStr = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                $this->checkWxApp($originalid);
			    $profiles = XN_Query::create('Profile')->tag('profile_' . $openid)
                    ->filter('wxopenid', '=', $openid)
                    ->begin(0)->end(1)
                    ->execute();
				if (count($profiles) > 0)
                {
                    $profile = $profiles[0];
                    //$profile = XN_Profile::load($openid,"wxopenid","profile");
                    $profileid = $profile->profileid;
                    $locations = XN_Query::create('MainContent')
                        ->tag('locations_' . $profileid)
                        ->filter('type', 'eic', 'locations')
                        ->filter('my.deleted', '=', '0')
                        ->filter('my.profileid', '=', $profileid)
                        ->begin(0)
                        ->end(1)
                        ->execute();
                    if (count($locations) > 0)
                    {
                        $location_info = $locations[0];
                        if ($location_info->my->latitude != $latitude && $location_info->my->longitude != $longitude)
                        {
                            $newlatitude = round($latitude * 1000000);
                            $newlongitude = round($longitude * 1000000);
                            $location_info->my->latitude = $newlatitude / 1000000;
                            $location_info->my->longitude = $newlongitude / 1000000;
                            $location_info->my->latitude1 = $newlatitude;
                            $location_info->my->longitude1 = $newlongitude;
                            $location_info->save('locations,locations_' . $profileid);
                        }
                    }
                    else
                    {
                        $newcontent = XN_Content::create('locations', '', false);
                        $newcontent->my->deleted = '0';
                        $newcontent->my->wxid = self::$WXID;
                        $newcontent->my->originalid = $originalid;
                        $newcontent->my->openid = $openid;
                        $newcontent->my->profileid = $profileid;
                        $newlatitude = round($latitude * 1000000);
                        $newlongitude = round($longitude * 1000000);
                        $newcontent->my->latitude = $newlatitude / 1000000;
                        $newcontent->my->longitude = $newlongitude / 1000000;
                        $newcontent->my->latitude1 = $newlatitude;
                        $newcontent->my->longitude1 = $newlongitude;
                        $newcontent->save('locations,locations_' . $profileid);
                    }
                }
 
				 return ""; 
            default :
                $contentStr = "Unknow Event: " . $object->Event;
                break;
        }

                if (self::$wxroletype == '2')
                {
                    $resultStr = $this->responseNews($object, $contentStr);
                }
                else
                {
                    $resultStr = $this->responseText($object, $contentStr);
                }

                return $resultStr;
        }


        public
        function responseImage($object, $mediaid)
        {
            $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType> 
                    <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                    </xml>";
            $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $mediaid);
            return $resultStr;
        }

        public
        function responseText($object, $content, $flag = 0)
        {
            if ($content != "")
            {
                $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
                $newcontent = str_replace("<p>", "", $content);
                $newcontent = str_replace("&nbsp;", " ", $newcontent);
                $newcontent = str_replace("</p>", "\n", $newcontent);
                $newcontent = strip_tags($newcontent, '<a>');
                $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $newcontent, $flag);
                return $resultStr;
            }
            else
            {
                /*
                    <MsgType><![CDATA[text]]></MsgType>
                    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                */
                $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime> 
                    <MsgType><![CDATA[transfer_customer_service]]></MsgType> 
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
                $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $flag);
                return $resultStr;
            }

        }

        public
        function responseNews($object, $content, $flag = 0)
        {
            $newsTpl = " <xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[%s]]></Title> 
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>
					</Articles>
					</xml>";
            self::log('responseNews: $WXID =>' . self::$WXID);
            self::log('responseNews: $replyid =>' . self::$replyid);
            self::log('responseNews: $flag =>' . $flag);
            self::log('responseNews: $content =>' . $content);
            if (self::$replyid == null)
            {
                $link = 'http://' . $_SERVER["HTTP_HOST"] . '/displayreply.php?type=o2o&wxid=' . self::$WXID;
                $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), self::$replytitle, strip_tags(self::$description), 'http://' . $_SERVER["HTTP_HOST"] . self::$image, $link);
            }
            else
            {
                $link = 'http://' . $_SERVER["HTTP_HOST"] . '/displayreply.php?type=o2o&appid=' . self::$WXID . '&id=' . self::$replyid;
                $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), self::$replytitle, strip_tags(self::$description), 'http://' . $_SERVER["HTTP_HOST"] . self::$image, $link);
            }
            self::log('responseNews =>' . $resultStr);
            return $resultStr;
        }
        
        public
        function profile_scan($postObj,$openid,$ticket)
        {
	        		$supplier_wxchannels = XN_Query::create('MainContent')->tag("supplier_wxchannels")
                        ->filter('type', 'eic', 'supplier_wxchannels')
                        ->filter('my.deleted', '=', '0')
                        ->filter('my.ticket', '=', $ticket)
                        ->end(1)
                        ->execute();
                    if (count($supplier_wxchannels) > 0)
                    {
                        $supplier_wxchannel_info = $supplier_wxchannels[0];
                        $profileid = $supplier_wxchannel_info->my->profileid; // 二维码所有人
                        $supplierid = $supplier_wxchannel_info->my->supplierid;
                        $wxchannelid = $supplier_wxchannel_info->id;
                        
                        self::log('subscribe: $profileid =>' . $profileid);
                        self::log('subscribe: $supplierid =>' . $supplierid);
                        
                        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $openid)
				            ->filter('type', 'eic', 'supplier_profile') 
				            ->filter('my.deleted', '=', '0')
				            ->filter('my.wxopenid', '=', $openid)
				            ->end(1)
				            ->execute();
				        if (count($supplier_profile) > 0)
				        {
							$supplier_profile_info = $supplier_profile[0];
							$hassourcer = $supplier_profile_info->my->hassourcer;
							if ($hassourcer != '0')
							{
						        echo $this->responseText($postObj,'感谢您持续关注我们的商城.');
	                            exit();
							} 
							$scan_profileid = $supplier_profile_info->my->profileid; ///扫码人
							$nickname = $supplier_profile_info->my->givenname; ///扫码人昵称
							if ($scan_profileid == $profileid)
							{
								 echo $this->responseText($postObj,'你扫了您自己的二维码.');
	                             exit();
							}
							
	                        $supplier_scans = XN_Query::create('MainContent')->tag("supplier_wxscans_" . $supplierid) 
	                            ->filter('type', 'eic', 'supplier_wxscans')
	                            ->filter('my.wxopenid', '=', $openid)
	                            ->filter('my.deleted', '=', '0')
	                            ->end(1)
	                            ->execute();
	                        if (count($supplier_scans) == 0)
	                        {
	                            $wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
	                                ->filter('type', 'eic', 'supplier_wxsettings')
	                                ->filter('my.deleted', '=', '0')
	                                ->filter('my.supplierid', '=', $supplierid)
	                                ->end(1)
	                                ->execute();
	                            if (count($wxsettings) > 0)
	                            {
	                                $sysconfig_info = $wxsettings[0];
	                                $appid = $sysconfig_info->my->appid;
									//已经关注，没有上下级关系，扫码确定关系
									$profile_info = get_profile_info($profileid);
								 
	                                $profile_info = get_profile_info($profileid);
	                                $givenname = $profile_info['givenname'];
                                
	                                self::log('subscribe: $givenname =>' . $givenname);

	                                $application = XN_Application::$CURRENT_URL;
	                                XN_Application::$CURRENT_URL = 'admin';
	                                $channel = XN_Content::create('supplier_wxscans', '', false, 4);
	                                $channel->my->wxopenid = $openid;
	                                $channel->my->scaner = $nickname;
	                                $channel->my->profileid = $profileid;
	                                $channel->my->supplierid = $supplierid;
	                                $channel->my->givenname = $givenname;
	                                $channel->my->appid = $appid;
	                                $channel->my->wxid = self::$WXID;
	                                $channel->my->wxchannelid = $wxchannelid;
	                                $channel->my->executestatus = '1';
	                                $channel->my->deleted = 0; 
	                                $channel->save('supplier_wxscans,supplier_wxscans_' . $supplierid);
	                                XN_Application::$CURRENT_URL = $application;
	                                $published = date("Y-m-d H:i");

	                                require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
	                                $message = '恭喜您,【' . $nickname . '】扫描了您的二维码,成为您的粉丝。时间:' . $published;
	                                XN_Message::sendmessage($profileid, $message, $appid);
									
									$profile_info = get_supplier_profile_info($profileid,$supplierid);
							        $onelevelsourcer = $profileid; 
							        $twolevelsourcer = $profile_info['onelevelsourcer'];
							        $threelevelsourcer = $profile_info['twolevelsourcer'];
									
						            $supplier_profile_info->my->onelevelsourcer = $onelevelsourcer;
						            $supplier_profile_info->my->twolevelsourcer = $twolevelsourcer;
						            $supplier_profile_info->my->threelevelsourcer = $threelevelsourcer;
									$supplier_profile_info->my->hassourcer = '1';

						            $wxopenid = $supplier_profile_info->my->wxopenid;
						            $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $scan_profileid . ",supplier_profile_" . $supplierid;
						            $tag .= ",supplier_profile_" . $onelevelsourcer;
						            $tag .= ",supplier_profile_" . $twolevelsourcer;
						            $tag .= ",supplier_profile_" . $threelevelsourcer;
									$tag .= ",supplier_profile_" . $profileid;
						            $supplier_profile_info->save($tag);
                                
	                                echo $this->responseText($postObj,'您扫描了【' . $givenname . '】的二维码.');
	                                exit();
								}
                                echo $this->responseText($postObj,'找不到公众号相关配置.');
                                exit();
							}
							else
							{
								//已经关注，没有上下级关系，原来已经扫码，但因为BUG，没有上下级关系
	                            $supplier_scan_info = $supplier_scans[0];
								
	                            $givenname = $supplier_scan_info->my->givenname;
	                            $published = date("Y-m-d H:i", strtotime($supplier_scan_info->published));
								$profileid = $supplier_scan_info->my->profileid;
								$executestatus = $supplier_scan_info->my->executestatus;
								
								if ($executestatus == "0")
								{
									$supplier_scan_info->my->executestatus = '1';
									$supplier_scan_info->save('supplier_wxscans,supplier_wxscans_' . $supplierid);
								}
                            
	                            self::log('subscribe: scan $givenname =>' . $givenname);
	                            self::log('subscribe: scan $published =>' . $published); 
								
								
								
								$profile_info = get_supplier_profile_info($profileid,$supplierid);
						        $onelevelsourcer = $profileid; 
						        $twolevelsourcer = $profile_info['onelevelsourcer'];
						        $threelevelsourcer = $profile_info['twolevelsourcer'];
								
					            $supplier_profile_info->my->onelevelsourcer = $onelevelsourcer;
					            $supplier_profile_info->my->twolevelsourcer = $twolevelsourcer;
					            $supplier_profile_info->my->threelevelsourcer = $threelevelsourcer;
								$supplier_profile_info->my->hassourcer = '1';

					            $wxopenid = $supplier_profile_info->my->wxopenid;
					            $tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $scan_profileid . ",supplier_profile_" . $supplierid;
					            $tag .= ",supplier_profile_" . $onelevelsourcer;
					            $tag .= ",supplier_profile_" . $twolevelsourcer;
					            $tag .= ",supplier_profile_" . $threelevelsourcer;
								$tag .= ",supplier_profile_" . $profileid;
					            $supplier_profile_info->save($tag);
								
								
	                            echo $this->responseText($postObj,'您在【' . $published . '】已经扫描过【' . $givenname . '】的二维码.');
	                            exit();
							}
								
					    }

                        $supplier_scans = XN_Query::create('MainContent')->tag("supplier_wxscans_" . $supplierid) 
                            ->filter('type', 'eic', 'supplier_wxscans')
                            ->filter('my.wxopenid', '=', $openid)
                            ->filter('my.deleted', '=', '0')
                            ->end(1)
                            ->execute();
                        if (count($supplier_scans) == 0)
                        {
                            $wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
                                ->filter('type', 'eic', 'supplier_wxsettings')
                                ->filter('my.deleted', '=', '0')
                                ->filter('my.supplierid', '=', $supplierid)
                                ->end(1)
                                ->execute();
                            if (count($wxsettings) > 0)
                            {
                                $sysconfig_info = $wxsettings[0];
                                $appid = $sysconfig_info->my->appid;
                                XN_WX::$APPID = $appid;
                                XN_WX::$SECRET = $sysconfig_info->my->secret;
                                
                                self::log('subscribe: $appid =>' . $appid);

                                $userinfo = XN_WX::getuserinfo($openid);

                                if (is_array($userinfo))
                                {
                                    if ($userinfo['subscribe'] == '1')
                                    {
                                        $nickname = str_replace("'", "", $userinfo['nickname']);
                                        $nickname = str_replace(" ", "", $nickname);
                                        $nickname = str_replace("\\", "", $nickname);
                                        
                                        self::log('subscribe: $nickname =>' . $nickname);

                                        $profile_info = get_profile_info($profileid);
                                        $givenname = $profile_info['givenname'];
                                        
                                        self::log('subscribe: $givenname =>' . $givenname);

                                        $application = XN_Application::$CURRENT_URL;
                                        XN_Application::$CURRENT_URL = 'admin';
                                        $channel = XN_Content::create('supplier_wxscans', '', false, 4);
                                        $channel->my->wxopenid = $openid;
                                        $channel->my->scaner = $nickname;
                                        $channel->my->profileid = $profileid;
                                        $channel->my->supplierid = $supplierid;
                                        $channel->my->givenname = $givenname;
                                        $channel->my->appid = $appid;
                                        $channel->my->wxid = self::$WXID;
                                        $channel->my->wxchannelid = $wxchannelid;
                                        $channel->my->executestatus = '0';
                                        $channel->my->deleted = 0; 
                                        $channel->save('supplier_wxscans,supplier_wxscans_' . $supplierid);
                                        XN_Application::$CURRENT_URL = $application;
                                        $published = date("Y-m-d H:i");

                                        require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
                                        $message = '恭喜您,【' . $nickname . '】扫描了您的二维码,成为您的粉丝。时间:' . $published;
                                        XN_Message::sendmessage($profileid, $message, $appid);

                                        
                                        echo $this->responseText($postObj,'您扫描了【' . $givenname . '】的二维码.');
                                        exit();
                                    }
                                }
                            }
                        }
                        else
                        {
	                        
                            $supplier_scan_info = $supplier_scans[0];
                            $givenname = $supplier_scan_info->my->givenname;
                            $published = date("Y-m-d H:i", strtotime($supplier_scan_info->published));
                            
                            self::log('subscribe: scan $givenname =>' . $givenname);
                            self::log('subscribe: scan $published =>' . $published); 
                            echo $this->responseText($postObj,'您在【' . $published . '】已经扫描过【' . $givenname . '】的二维码.');
                            exit();
                        }
                    }
        }

        public
        function handleScan($postObj)
        {
	        if ($postObj->Ticket != null && $postObj->Ticket != "" && $postObj->FromUserName != null && $postObj->FromUserName != "")
            {
                    $ticket = $postObj->Ticket;
                    $openid = $postObj->FromUserName;
                    
                    self::log('subscribe: $ticket =>' . $ticket);

                    self::profile_scan($postObj,$openid,$ticket); 
            }
            else
            {
	             echo $this->responseText($postObj,"欢迎您进入" . self::$WXNAME);
				 exit(); 
            } 
        }
        public  function popularizeqrcode($supplierid)
		{
			$popularizeqrcode  = '0'; 
		    $supplier_settings = XN_Query::create('MainContent')->tag("mall_settings_".$supplierid)
		        ->filter('type', 'eic', 'mall_settings')
		        ->filter('my.supplierid', '=', $supplierid)
		        ->filter('my.deleted', '=', '0')
		        ->end(1)
		        ->execute();
		    if (count($supplier_settings) > 0)
		    {
		        $supplier_setting_info = $supplier_settings[0]; 
				$popularizeqrcode  = $supplier_setting_info->my->popularizeqrcode; 
		    }
		    return $popularizeqrcode; 
		} 

        public
        function handleClick($postObj)
        {
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = $postObj->EventKey;

            self::log('checkSignature: keyword =>' . $keyword);

            if ($keyword == "qrcodecard")
            {

                $wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
                    ->filter('type', 'eic', 'supplier_wxsettings')
                    ->filter('my.deleted', '=', '0')
                    ->filter('my.appid', '=', self::$APPID)
                    ->end(1)
                    ->execute();
                if (count($wxsettings) > 0)
                {
                    $sysconfig_info = $wxsettings[0];
                    $supplierid = $sysconfig_info->my->supplierid;
					
                    $wxopenids = XN_Query::create('MainContent')->tag("wxopenids" )
                        ->filter('type', 'eic', 'wxopenids')
                        ->filter('my.wxopenid', '=', $fromUsername)
                        ->end(1)
                        ->execute();
                    if (count($wxopenids) > 0)
                    {
                        $wxopenid_info = $wxopenids[0];
                        $profileid = $wxopenid_info->my->profileid;

                        //$supplierid='352307';
                        //$profileid='hx5eyjjmlg6';

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
                            $ranklevel = $supplier_profile_info->my->ranklevel;
							$popularizeqrcode = $this->popularizeqrcode($supplierid);
							if ($popularizeqrcode == "1")
							{
								$ranklevel = "1";
							} 
                            if ($ranklevel == "1")
                            {
                                echo $this->responseText($postObj, '正在生成名片，请稍候...');
								$this->fast_finish_request();
                                
								
                                $supplier_wxchannels = XN_Query::create('MainContent')->tag("supplier_wxchannels_" . $profileid)
                                    ->filter('type', 'eic', 'supplier_wxchannels')
                                    ->filter('my.profileid', '=', $profileid)
                                    ->filter('my.supplierid', '=', $supplierid)
                                    ->filter('my.deleted', '=', '0')
                                    ->end(1)
                                    ->execute();
                                if (count($supplier_wxchannels) == 0)
                                {
                                    $qrid = 0;
                                    $claimrecords = XN_Query::create('MainContent')->tag('supplier_wxchannels')
                                        ->filter('type', 'eic', 'supplier_wxchannels')
                                        ->filter('my.deleted', '=', '0')
                                        ->filter('my.supplierid', '=', $supplierid)
                                        ->order('my.qrid', XN_Order::DESC_NUMBER)
                                        ->begin(0)->end(1)
                                        ->execute();
                                    if (count($claimrecords) > 0)
                                    {
                                        $claimrecord_info = $claimrecords[0];
                                        $qrid = $claimrecord_info->my->qrid;
                                        $qrid = intval($qrid) + 1;
                                    }
                                    XN_WX::$APPID = self::$APPID;
                                    XN_WX::$SECRET = self::$SECRET;
                                    $ticket = XN_WX::qrcode($qrid);
                                    if (isset($ticket) && $ticket != "")
                                    {
                                        $application = XN_Application::$CURRENT_URL;
                                        XN_Application::$CURRENT_URL = 'admin';
                                        $channel = XN_Content::create('supplier_wxchannels', '', false, 4);
                                        $channel->my->qrid = $qrid;
                                        $channel->my->profileid = $profileid;
                                        $channel->my->supplierid = $supplierid;
                                        $channel->my->wxid = self::$WXID;
                                        $channel->my->appid = self::$APPID;
                                        $channel->my->ticket = $ticket;
                                        $channel->my->deleted = 0;
                                        $channel->save('supplier_wxchannels,supplier_wxchannels_' . $profileid . ',supplier_wxchannels_' . $supplierid);
                                        XN_Application::$CURRENT_URL = $application;
                                    }
                                    else
                                    {
                                        $this->sendmessage($supplierid,$profileid,'生成名片失败,请稍候再试!');
                                        exit();
                                    }
                                }
                                $img = 'http://f2c.tezan.cn/qrcodecard_image.php?supplierid=' . $supplierid . '&profileid=' . $profileid;

                                XN_WX::$APPID = self::$APPID;
                                XN_WX::$SECRET = self::$SECRET;
                                $mediaid = XN_WX::uploadimage($img);
                                if (isset($mediaid) && $mediaid != "")
                                {
									$this->sendimagemessage($supplierid,$profileid,$mediaid); 
                                    //echo $this->responseImage($postObj, $mediaid);
                                    exit();
                                }
                                else
                                {
									$this->sendmessage($supplierid,$profileid,'生成名片失败,请稍候再试!');
                                    //echo $this->responseText($postObj, '生成名片失败,请稍候再试!');
                                    exit();
                                }

                            }
                            else
                            {
                                echo $this->responseText($postObj, '您还没有购买,暂不能生成名片,请购买后再获取名片!');
                                exit();
                            }
                        }
                        else
                        {
                            echo $this->responseText($postObj, '您还没有进入商城,暂不能生成名片,请进入商城，购买后再获取名片!');
                            exit();
                        }
                    }
                    else
                    {
                        echo $this->responseText($postObj, '您还没有进入商城,暂不能生成名片,请进入商城，购买后再获取名片!');
                        exit();
                    }
                }

            }

            $result = self::matchkey($keyword);
            if ($result == null)
            {
                return $contentStr = "点击菜单:" . $keyword . "\n";
            }
            return $result;

        }
		public function fast_finish_request()
		{
			ignore_user_abort(true); 
			fastcgi_finish_request(); 
		}
		
		public function sendimagemessage($supplierid,$profileid,$mediaid)
		{ 
			$supplier_wxsettings = XN_Query::create ( 'MainContent' ) ->tag('supplier_wxsettings_'.$supplierid)
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
	            XN_Message::sendimagemessage($profileid,$mediaid,$appid);   
			} 
		}
		public function sendmessage($supplierid,$profileid,$msg)
		{ 
			$supplier_wxsettings = XN_Query::create ( 'MainContent' ) ->tag('supplier_wxsettings_'.$supplierid)
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
	            XN_Message::sendmessage($profileid,$msg,$appid);   
			} 
		}

        public
        function matchkey($keyword)
        {

            global $wxsetting;
            $wxroles = $wxsetting['wxrolesettings'];
            foreach ($wxroles as $wxrole)
            {
                $triggerkey = $wxrole['triggerkey'];
                if ($triggerkey == $keyword)
                {
                    $replay = $wxrole['reply'];
                    $wxroletype = $wxrole['wxroletype'];
                    $replytitle = $wxrole['replytitle'];
                    $image = $wxrole['image'];
                    $wxroleid = $wxrole['roleid'];
                    $description = $wxrole['description'];
                    self::log('checkSignature: replay =>' . $replay);
                    self::$replay = $replay;
                    self::$wxroletype = $wxroletype;
                    self::$replytitle = $replytitle;
                    self::$image = $image;
                    self::$replyid = $wxroleid;
                    self::$description = $description;

                    return $replay;
                }
            }

            return null;
        }

        public
        function handleText($postObj)
        {
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);


            self::log('handleText: keyword =>' . $keyword);

            $contentStr = self::matchkey($keyword);

            if ($contentStr == null)
            {
                if (strlen($keyword) <= 5)
                {
                    if (self::$DEFAULTREPLY == "")
                    {
                        $contentStr = "请稍候，客服会回复您！";
                    }
                    else
                    {
                        $contentStr = self::$DEFAULTREPLY;
                    }
                }
                else
                {
                    $contentStr = "";
                }
                self::log('handleText: $fromUsername => ' . $fromUsername);
                if ($fromUsername != "")
                {
                    XN_Application::$CURRENT_URL = "admin";
                    $supplier_profile = XN_Query::create('Content')->tag("supplier_profile_" . $fromUsername)
                        ->filter('type', 'eic', 'supplier_profile')
                        ->filter('my.deleted', '=', '0')
                        ->filter('my.wxopenid', '=', $fromUsername)
                        ->end(1)
                        ->execute();
                    if (count($supplier_profile) > 0)
                    {
                        $supplier_profile_info = $supplier_profile[0];
                        $fromProfileid = $supplier_profile_info->my->profileid;
                        self::log('handleText: $fromProfileid => ' . $fromProfileid);
                        $wxservices = XN_Query::create('Content')
                            ->filter('type', 'eic', 'supplier_wxservices')
                            ->filter('my.deleted', '=', '0')
                            ->filter('my.wxid', '=', self::$WXID)
                            ->filter('my.fromprofileid', '=', $fromProfileid)
                            ->order("published", XN_Order::DESC)
                            ->begin(0)
                            ->end(1)
                            ->execute();
                        if (count($wxservices) == 0)
                        {
                            $newcontent = XN_Content::create('supplier_wxservices', '', false);
                            $newcontent->my->wxid = self::$WXID;
                            $newcontent->my->supplierid = self::$supplierid;
                            $newcontent->my->fromusername = $fromUsername;
                            $newcontent->my->fromprofileid = $fromProfileid;
                            $newcontent->my->tousername = $toUsername;
                            $newcontent->my->wxmsgtype = $postObj->MsgType;
                            $newcontent->my->msgid = $postObj->MsgId;
                            $newcontent->my->msgcontent = $postObj->Content;
                            $newcontent->my->picurl = $postObj->PicUrl;
                            $newcontent->my->mediaid = $postObj->MediaId;
                            $newcontent->my->thumbmediaid = $postObj->ThumbMediaId;
                            $newcontent->my->replycount = "1";
                            $newcontent->my->customservice = "-";
                            $newcontent->my->receivetime = strtotime("now");
                            $newcontent->my->lastreplaytime = "-";
                            $newcontent->my->deleted = "0";
                            $newcontent->save('supplier_wxservices');

                            $wxserviceid = $newcontent->id;
                            $newcontent = XN_Content::create('supplier_wxreplys', '', false);
                            $newcontent->my->record = $wxserviceid;
                            $newcontent->my->reply = $postObj->Content;
                            $newcontent->my->customservice = $fromProfileid;
                            $newcontent->save('supplier_wxreplys');
                        }
                        else
                        {
                            $wxservice_info = $wxservices[0];
                            $wxservice_info->my->msgcontent = $postObj->Content;
                            $replycount = $wxservice_info->my->replycount;
                            $wxservice_info->my->replycount = intval($replycount) + 1;
                            $wxservice_info->my->receivetime = strtotime("now");
                            $wxservice_info->my->deleted = "0";
                            $wxservice_info->save('supplier_wxservices');

                            $wxserviceid = $wxservice_info->id;
                            $newcontent = XN_Content::create('supplier_wxreplys', '', false);
                            $newcontent->my->record = $wxserviceid;
                            $newcontent->my->reply = $postObj->Content;
                            $newcontent->my->customservice = $fromProfileid;
                            $newcontent->save('supplier_wxreplys');
                        }
                    }
                }
            }


            if ($contentStr != null && $contentStr != "")
            {
                if (self::$wxroletype == '2')
                {
                    echo $this->responseNews($postObj, $contentStr);
                }
                else
                {
                    echo $this->responseText($postObj, $contentStr);
                }
            }
            else
            {
                echo "";
            }
        }

        private
        function checkSignature()
        {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];


            self::log('checkSignature: signature =>' . $signature);

            global $wxsetting;

            $token = $wxsetting['token'];

            self::log('checkSignature: token =>' . $token);

            self::$WXID = $wxsetting['wxid'];
            self::$APPID = $wxsetting['appid'];
            self::$ORIGINALID = $wxsetting['originalid'];
            self::$SECRET = $wxsetting['secret'];
            self::$WXNAME = $wxsetting['wxname'];
            self::$WELCOMEWORDS = $wxsetting['welcomewords'];
            self::$DEFAULTREPLY = $wxsetting['defaultreply'];

            self::$wxroletype = $wxsetting['wxtype'];
            self::$replytitle = $wxsetting['welcometitle'];
            self::$image = $wxsetting['image'];
            self::$description = $wxsetting['description'];
            self::$supplierid = $wxsetting['supplierid'];

            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);

            self::log('checkSignature: tmpStr =>' . $tmpStr);
            self::log('checkSignature: $WXID =>' . self::$WXID);

            if ($tmpStr == $signature)
            {
                self::log('checkSignature: appid =>' . $wxsetting['appid']);
                self::log('checkSignature: secret =>' . $wxsetting['secret']);
                return true;
            }
            return false;
        }

        private
        function checkWxApp($originalid)
        {
            global $wxsetting;

            if ($wxsetting['originalid'] == $originalid)
            {
                return true;
            }
            return false;
        }

        /**
         * 中英文截取
         * @param string    要截取的字符串
         * @param string    要截取的长度(超过总长度 按总长度计算)
         * @param [string]  (可选)开始位置(第一个为0)
         * @return string
         */
        private
        function mixSubstr($str, $length, $start = FALSE)
        {
            if (!$length)
            {
                return false;
            }

            $strlen = strlen($str);
            $content = '';
            $sing = 0;
            $count = 0;

            if ($length > $strlen)
            {
                $length = $strlen;
            }
            if ($start >= $strlen)
            {
                return false;
            }

            while ($length != ($count - $start))
            {
                if (ord($str[$sing]) > 0xa0)
                {
                    if (!$start || $start <= $count)
                    {
                        $content .= $str[$sing] . $str[$sing + 1] . $str[$sing + 2];
                    }
                    $sing += 3;
                    $count++;
                }
                else
                {
                    if (!$start || $start <= $count)
                    {
                        $content .= $str[$sing];
                    }
                    $sing++;
                    $count++;
                }
            }
            return $content;
        }
    }

?>