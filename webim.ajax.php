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
	echo '{"code":200,"data":[]}';
	die(); 
}  
 
if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '{"code":200,"data":[]}';
	die();  
}  
 
if(isset($_REQUEST['type']) && $_REQUEST['type'] !='')
{
	$type = $_REQUEST['type'];  
}
else
{
	echo '{"code":200,"data":[]}';
	die(); 
}

XN_Application::$CURRENT_URL = "admin"; 
 
try{
	if ($type == "newmessage")
	{
		if(isset($_REQUEST['maxmessageid']) && $_REQUEST['maxmessageid'] !='')
		{
			global $maxmessageid,$minmessageid;
			$maxmessageid = $_REQUEST['maxmessageid'];  
			if ($maxmessageid == '0')
			{
				$query = XN_Query::create ( 'YearmonthContent_Count' )->tag('supplier_messages_'.$profileid)
						->filter ( 'type', 'eic', 'supplier_messages') 
						->filter ( 'yearmonth', '=', date("Y-m")) 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.supplierid', '=', $supplierid)  
						->filter ( 'my.profileid', '=', $profileid)  					 
						->rollup()
						->end(-1);
				$query->execute ();
				if ($query->getTotalCount() == 0 )
				{
					$newcontent = XN_Content::create('supplier_messages','',false,9);  
			        $newcontent->my->deleted = '0';
					$newcontent->my->profileid = $profileid; 
			        $newcontent->my->supplierid = $supplierid; 
					$newcontent->my->businesseid = '';
					$newcontent->my->msgtype = '1'; 
					$newcontent->my->source = '1'; 
					$newcontent->my->fromprofileid = 'SYSTEM'; 
					$newcontent->my->message = '您好！有需要帮助吗？';
			        $newcontent->save('supplier_messages,supplier_messages_'.$supplierid.',supplier_messages_'.$profileid);
				} 
				$minmessageid = "";
				$chats = get_chats($supplierid,$profileid);
				 
				if ($count > 10)
				{
					echo '{"code":200,"maxmessageid":'.$maxmessageid.',"minmessageid":'.$minmessageid.',"data":'.json_encode($chats,true).'}';
					die();
				}
				else
				{  
					$time = strtotime('-1 month',date("Y-m"));  
					if ( $time > 1448899200 )
					{
						echo '{"code":200,"maxmessageid":'.$maxmessageid.',"minmessageid":'.$minmessageid.',"yearmonth":"'.date("Y-m",$time).'","data":'.json_encode($chats,true).'}';
					}
					else
					{
						echo '{"code":200,"maxmessageid":'.$maxmessageid.',"minmessageid":'.$minmessageid.',"yearmonth":"2015-12","data":'.json_encode($chats,true).'}';
					}
					
					die();
				} 
			}
			else
			{
				$chats = get_chats($supplierid,$profileid);
				if (count($chats) > 0)
				{
					echo '{"code":200,"maxmessageid":'.$maxmessageid.',"data":'.json_encode($chats,true).'}';
					die();
				}
			}
			
		}
	}
	else if ($type == "addmsgtext")
	{
		if(isset($_REQUEST['msgtext']) && $_REQUEST['msgtext'] !='')
		{
			$msgtext = $_REQUEST['msgtext'];  
			$supplier_chats = XN_Query::create ( 'Content' )->tag('supplier_chats_'.$profileid)
						->filter ( 'type', 'eic', 'supplier_chats') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.supplierid', '=', $supplierid)  
						->filter ( 'my.profileid', '=', $profileid) 
						->end(1)
						->execute (); 
			if (count($supplier_chats) == 0)
			{ 
		        $newcontent = XN_Content::create('supplier_chats','',false);  
		        $newcontent->my->deleted = '0';
				$newcontent->my->profileid = $profileid; 
		        $newcontent->my->supplierid = $supplierid; 
				$newcontent->my->businesseid = '';
				$newcontent->my->chatstatus = '0';
				$newcontent->my->lastsubmittime = date("Y-m-d H:i");
				$newcontent->my->lastreplytime = '';
				$newcontent->my->lastmessage = $msgtext;
		        $newcontent->save('supplier_chats,supplier_chats_'.$supplierid.',supplier_chats_'.$profileid);
			}
			else
			{
				$supplier_chat_info = $supplier_chats[0];
				$supplier_chat_info->my->chatstatus = '0';
				$supplier_chat_info->my->lastsubmittime = date("Y-m-d H:i"); 
				$supplier_chat_info->my->lastmessage = $msgtext;
		        $supplier_chat_info->save('supplier_chats,supplier_chats_'.$supplierid.',supplier_chats_'.$profileid);
			}
	        $newcontent = XN_Content::create('supplier_messages','',false,9);  
	        $newcontent->my->deleted = '0';
			$newcontent->my->profileid = $profileid; 
	        $newcontent->my->supplierid = $supplierid; 
			$newcontent->my->businesseid = '';
			$newcontent->my->msgtype = '1'; 
			$newcontent->my->source = '0'; 
			$newcontent->my->fromprofileid = ''; 
			$newcontent->my->message = $msgtext;
	        $newcontent->save('supplier_messages,supplier_messages_'.$supplierid.',supplier_messages_'.$profileid);
		}
	}
	else if ($type == "history")
	{
		if(isset($_REQUEST['minmessageid']) && $_REQUEST['minmessageid'] !='' &&
		   isset($_REQUEST['yearmonth']) && $_REQUEST['yearmonth'] !='')
		{
			global $minmessageid;
			$minmessageid = intval($_REQUEST['minmessageid']);  
			$yearmonth = $_REQUEST['yearmonth'];  
			
			$query = XN_Query::create ( 'YearmonthContent' )->tag('supplier_messages_'.$profileid)
						->filter ( 'type', 'eic', 'supplier_messages') 
						->filter ( 'yearmonth', '=', $yearmonth) 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.supplierid', '=', $supplierid)  
						->filter ( 'my.profileid', '=', $profileid)  
						->filter ( 'id', '<', $minmessageid) 
						->order("published",XN_Order::DESC)
						->end(10);
			$supplier_chats	= $query->execute ();  			
			$count = $query->getTotalCount();
			$chats = array();
			$key = 1;
			foreach($supplier_chats as $chat_info)
			{
				$message = $chat_info->my->message;
				$fromprofileid = $chat_info->my->fromprofileid;
				$profileid = $chat_info->my->profileid;
				$source = $chat_info->my->source;
				$published = $chat_info->published;
				$msgtype = intval($chat_info->my->msgtype);
				
				$chats[$key]['messageid'] = $chat_info->id;
				$chats[$key]['message'] = $message;
				$chats[$key]['fromprofileid'] = $fromprofileid;
				$chats[$key]['profileid'] = $profileid;
				$chats[$key]['source'] = intval($source);
				if ($msgtype == '1')
				{
					$chats[$key]['msgtype'] = 'text'; 
				} 
				else if ($msgtype == '2')
				{
					$chats[$key]['msgtype'] = 'image';  				
					$diff_time = date_diff(date_create($published),date_create("now"));
					if (intval($diff_time->format("%h")) > 1)
					{
						global $APISERVERADDRESS;
						if (isset($message) && $message != "")
						{
							$width = 320;
							$message = $APISERVERADDRESS.$message."?width=".$width; 
							$chats[$key]['message'] = $message;
						}
					}
					else
					{
						$chats[$key]['message'] = $message;
					} 
				} 
				else if ($msgtype == '3')
				{
					$chats[$key]['msgtype'] = 'sound'; 
				} 
				else 
				{
					$chats[$key]['msgtype'] = 'text'; 
				} 
				$key ++;
				if ($chat_info->id < $minmessageid)
					$minmessageid = $chat_info->id;
			} 
			
			 
			if ($count > 10)
			{
				echo '{"code":200,"minmessageid":'.$minmessageid.',"yearmonth":"'.$yearmonth.'","data":'.json_encode($chats,true).'}';
				die();
			}
			else
			{ 
				 
				$time = strtotime('-1 month',strtotime($yearmonth));  
				if ( $time > 1448899200 )
				{
					echo '{"code":200,"minmessageid":'.$minmessageid.',"yearmonth":"'.date("Y-m",$time).'","data":'.json_encode($chats,true).'}';
				}
				else
				{
					echo '{"code":200,"minmessageid":'.$minmessageid.',"yearmonth":"2015-12","data":'.json_encode($chats,true).'}';
				}
				
				die();
			} 
		}
	}
	else if ($type == "addimage")
	{
		if(isset($_REQUEST['image']) && $_REQUEST['image'] !='')
		{
			$mediaid_info = $_REQUEST['image'];
			require_once (XN_INCLUDE_PREFIX."/XN/Wx.php"); 
			global $wxsetting;
			XN_WX::$APPID = $wxsetting['appid'];
			XN_WX::$SECRET = $wxsetting['secret'];
	 
			$image = XN_WX::download_weixin_image($mediaid_info); 		
				 
	        $newcontent = XN_Content::create('supplier_messages','',false,9);  
	        $newcontent->my->deleted = '0';
			$newcontent->my->profileid = $profileid; 
	        $newcontent->my->supplierid = $supplierid; 
			$newcontent->my->businesseid = '';
			$newcontent->my->msgtype = '2'; 
			$newcontent->my->source = '0'; 
			$newcontent->my->fromprofileid = ''; 
			$newcontent->my->message = $image;
	        $newcontent->save('supplier_messages,supplier_messages_'.$supplierid.',supplier_messages_'.$profileid);
		}
	}
	else if ($type == "addsound")
	{
		if(isset($_REQUEST['sound']) && $_REQUEST['sound'] !='')
		{
			$mediaid_info = $_REQUEST['sound'];
			require_once (XN_INCLUDE_PREFIX."/XN/Wx.php"); 
			global $wxsetting;
			XN_WX::$APPID = $wxsetting['appid'];
			XN_WX::$SECRET = $wxsetting['secret'];
	 
			$sound = XN_WX::download_weixin_sound($mediaid_info); 	
			
			$newsound = str_replace("amr","mp3",$sound);
			exec('ffmpeg -i '.$_SERVER['DOCUMENT_ROOT'].$sound.' '.$_SERVER['DOCUMENT_ROOT'].$newsound, $out);	 				 
	        $newcontent = XN_Content::create('supplier_messages','',false,9);  
	        $newcontent->my->deleted = '0';
			$newcontent->my->profileid = $profileid; 
	        $newcontent->my->supplierid = $supplierid; 
			$newcontent->my->businesseid = '';
			$newcontent->my->msgtype = '3'; 
			$newcontent->my->source = '0'; 
			$newcontent->my->fromprofileid = ''; 
			$newcontent->my->message = $newsound;
	        $newcontent->save('supplier_messages,supplier_messages_'.$supplierid.',supplier_messages_'.$profileid);
		}
	}

	
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":200,"data":[]}'; 
die();  
  
  
function  get_chats($supplierid,$profileid) 
{
	global $maxmessageid,$minmessageid;
    global $APISERVERADDRESS;
	if ($maxmessageid == '0')
	{
		$supplier_chats = XN_Query::create ( 'YearmonthContent' )->tag('supplier_messages_'.$supplierid)
					->filter ( 'type', 'eic', 'supplier_messages') 
					->filter ( 'yearmonth', '=', date("Y-m")) 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)  
					->filter ( 'my.profileid', '=', $profileid) 
					->order("published",XN_Order::DESC) 
					->end(10)
					->execute (); 
		$chats = array();
		$key = 100;
		
		foreach($supplier_chats as $chat_info)
		{
			$message = $chat_info->my->message;
			$fromprofileid = $chat_info->my->fromprofileid;
			$profileid = $chat_info->my->profileid;
			$source = $chat_info->my->source;
			$published = $chat_info->published;
			$msgtype = intval($chat_info->my->msgtype);
		
			$chats[$key]['messageid'] = $chat_info->id;
			$chats[$key]['message'] = $message;
			$chats[$key]['fromprofileid'] = $fromprofileid;
			$chats[$key]['profileid'] = $profileid;
			$chats[$key]['source'] = intval($source);
			if ($msgtype == '1')
			{
				$chats[$key]['msgtype'] = 'text'; 
			} 
			else if ($msgtype == '2')
			{
				$chats[$key]['msgtype'] = 'image';  
				$diff_time = date_diff(date_create($published),date_create("now"));
				if (intval($diff_time->format("%h")) > 1)
				{
					global $APISERVERADDRESS;
					if (isset($message) && $message != "")
					{
						$width = 320;
						$message = $APISERVERADDRESS.$message."?width=".$width; 
						$chats[$key]['message'] = $message;
					}
				}
				else
				{
					$chats[$key]['message'] = $message;
				} 
			} 
			else if ($msgtype == '3')
			{
				$chats[$key]['msgtype'] = 'sound'; 
			} 
			else 
			{
				$chats[$key]['msgtype'] = 'text'; 
			} 
			$key --;
			if ($minmessageid == "")
			{
				$minmessageid = $chat_info->id;
			}
			else
			{
				if ($chat_info->id < $minmessageid)
					$minmessageid = $chat_info->id;
			}
			if ($chat_info->id > $maxmessageid)
				$maxmessageid = $chat_info->id;
		}  
	}
	else
	{
		$supplier_chats = XN_Query::create ( 'YearmonthContent' )->tag('supplier_messages_'.$supplierid)
					->filter ( 'type', 'eic', 'supplier_messages') 
					->filter ( 'yearmonth', '=', date("Y-m")) 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)  
					->filter ( 'my.profileid', '=', $profileid)  
					->filter ( 'id', '>', $maxmessageid) 
					->order("published",XN_Order::ASC)
					->end(10)
					->execute (); 
		$chats = array();
		$key = 1;
		foreach($supplier_chats as $chat_info)
		{
			$message = $chat_info->my->message;
			$fromprofileid = $chat_info->my->fromprofileid;
			$profileid = $chat_info->my->profileid;
			$source = $chat_info->my->source;
			$published = $chat_info->published;
			$msgtype = intval($chat_info->my->msgtype);
		
			$chats[$key]['messageid'] = $chat_info->id;
			$chats[$key]['message'] = $message;
			$chats[$key]['fromprofileid'] = $fromprofileid;
			$chats[$key]['profileid'] = $profileid;
			$chats[$key]['source'] = intval($source);
			if ($msgtype == '1')
			{
				$chats[$key]['msgtype'] = 'text'; 
			} 
			else if ($msgtype == '2')
			{
				$chats[$key]['msgtype'] = 'image'; 
				global $APISERVERADDRESS;
				if (isset($message) && $message != "")
				{
					$diff_time = date_diff(date_create($published),date_create("now"));
					if (intval($diff_time->format("%h")) > 1)
					{
						global $APISERVERADDRESS;
						if (isset($message) && $message != "")
						{
							$width = 320;
							$message = $APISERVERADDRESS.$message."?width=".$width; 
							$chats[$key]['message'] = $message;
						}
					}
					else
					{
						$chats[$key]['message'] = $message;
					} 
					
				}
			} 
			else if ($msgtype == '3')
			{
				$chats[$key]['msgtype'] = 'sound'; 
			} 
			else 
			{
				$chats[$key]['msgtype'] = 'text'; 
			} 
			$key ++;
			$maxmessageid = $chat_info->id;
		} 
	}
	
	return $chats; 
}
 
?>