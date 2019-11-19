<?php
 
if (! class_exists('XN_Message')) 
{ 
	class XN_Message
	{    
		public static function get_wx_secret($appid) 
		{
			$wx_info = array();
			 
			$wxsettings = XN_Query::create('MainContent')
					->tag('supplier_wxsettings')
					->filter('type','eic','supplier_wxsettings')
					->filter('my.deleted','=','0')
					->filter('my.appid','=',$appid)  
					->begin(0)
					->end(1)
					->execute();
			if(count($wxsettings) > 0)
			{
				$wxsetting_info = $wxsettings[0]; 
		   		$appid = $wxsetting_info->my->appid;
				$wxid = $wxsetting_info->id;
		   		$secret = $wxsetting_info->my->secret;
				$supplierid = $wxsetting_info->my->supplierid;
				$wx_info['appid'] = $appid;
				$wx_info['wxid'] = $wxid;
				$wx_info['secret'] = $secret; 
				$wx_info['supplierid'] = $supplierid; 
			}  
			return $wx_info;
		}
		public static function sendmessage($profileid,$msg,$appid=null,$wxopenid=null) 
		{
			if ($wxopenid == null && $appid == null)
			{
	  		      $lastlogins=XN_Query::Create("MainContent")
	  		            ->tag("lastloginlog_".$profileid)
	  		            ->filter("type","eic","lastloginlog")
	  		            ->filter("my.profileid","=",$profileid)
	  		            ->filter("my.deleted","=",'0')
	  		            ->end(1)
	  		            ->execute();
	  			  if (count($lastlogins) > 0)
	  			  {
	  			   		$lastlogin_info = $lastlogins[0];
	  					$appid = $lastlogin_info->my->appid; 
  				        if (isset($appid) && $appid != '')
						{
						 	$wxopenids = XN_Query::create ( 'MainContent' )->tag("wxopenids_".$profileid)
						 						->filter ( 'type', 'eic', 'wxopenids')  
						 						->filter ( 'my.profileid', '=', $profileid)
												->filter ( 'my.appid', '=', $appid)
						 						->end(1)
						 						->execute ();
						 	if (count($wxopenids) > 0 )	
						 	{
								$wxopenid_info = $wxopenids[0];
								$wxopenid = $wxopenid_info->my->wxopenid;
							}  
						} 
				}
		   }
		   if  ($appid != null && $wxopenid == null)
		   {
			   $wxopenids = XN_Query::create ( 'MainContent' )->tag("wxopenids_".$profileid)
						 						->filter ( 'type', 'eic', 'wxopenids')  
						 						->filter ( 'my.profileid', '=', $profileid)
												->filter ( 'my.appid', '=', $appid)
						 						->end(1)
						 						->execute ();
			 	if (count($wxopenids) > 0 )	
			 	{
					$wxopenid_info = $wxopenids[0];
					$wxopenid = $wxopenid_info->my->wxopenid;
				}  
		   }
		   if ($wxopenid != null && $appid != null)
		   {
			   	   $wxsettings = self::get_wx_secret($appid);
				   if (count($wxsettings))
				   {
				  	   require_once (XN_INCLUDE_PREFIX."/XN/Wx.php");
					   $wxid = $wxsettings['wxid'];
					   $supplierid = $wxsettings['supplierid']; 			   		   
		 			   $tag = "messages,messages_".$profileid;
		 			   try{ 
		 			  	  XN_Content::create('message','wxmessage',false,6)			
		 			  			  ->my->add('deleted','0')  
								  ->my->add('supplierid',$supplierid)	  
		 			  			  ->my->add('profileid',$profileid)
		 			  			  ->my->add('sendid',XN_Profile::$VIEWER) 
		 			  			  ->my->add('status','0')
								  ->my->add('viewtime','')	 
		 			  			  ->my->add('message',$msg)
		 			  			  ->save($tag);   
		 			  }
		 			  catch(XN_Exception $e){
		 			      throw $e;
		 			  } 
				   } 
			  }
		    
		}  
	} 
}  
