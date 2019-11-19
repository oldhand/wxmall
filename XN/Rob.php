<?php
 
if (! class_exists('XN_Rob')) 
{
 
	class XN_Rob 
	{  
		public static function set($robid,$amount,$stime,$etime) 
		{ 
				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
		 	    $xml .= '<robid>'.$robid.'</robid>'; 
				$xml .= '<amount>'.$amount.'</amount>'; 
				$xml .= '<stime>'.$stime.'</stime>'; 
				$xml .= '<etime>'.$etime.'</etime>';  
				$xml .= '</feed>'; 
				$url = '/rob';
				$rsp = XN_REST::post($url, $xml,'text/xml'); 
				
		   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA); 
				if ($xmlObj->getName() == "error")
				{
					 throw new XN_Exception(trim($xmlObj));
				}
				elseif ($xmlObj->getName() == "ok")
				{
					 return trim($xmlObj);
				}
				else
				{
			   	     return trim($xmlObj);
				} 
		}
		
		public static function get($robid) 
		{
			$rsp = XN_REST::get(XN_REST::urlsprintf('/rob(%s)',"id='".$robid."'"));
			 
	   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA);  
 
			if ($xmlObj->getName() == "error")
			{
				 throw new XN_Exception(trim($xmlObj));
			}
			elseif ($xmlObj->getName() == "feed")
			{
				$orderinfo = array();
		   	    $orderinfo["robid"] =  trim($xmlObj->robid); 
		   	    $orderinfo["amount"] =  trim($xmlObj->amount);  
				$orderinfo["stime"] =  trim($xmlObj->stime);  
				$orderinfo["etime"] =  trim($xmlObj->etime);  
				$orderinfo["countdown"] =  trim($xmlObj->countdown);  
				$orderinfo["remainingtime"] =  trim($xmlObj->remainingtime);  
				 
				
				$entrylist = $xmlObj->entry; 
				$list = array();
				foreach ( $entrylist as  $node )
				{
			   	    $profileid =  trim($node->profileid); 
					$sn =  trim($node->sn); 
					$datetime=  trim($node->datetime);  
					$list[$sn] = array('profileid'=>$profileid,'datetime'=>$datetime,);
				}
				
				$orderinfo["grab"] = $list;
				
				return $orderinfo;
			}
			else
			{
		   	 	throw new XN_Exception("Unrecognized Result");
			} 
		}
		 
		public static function rob($robid,$profileid) 
		{
				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
		 	    $xml .= '<profileid>'.$profileid.'</profileid>';  
				$xml .= '<robid>'.$robid.'</robid>';
				$xml .= '</feed>'; 
				$url = '/rob';
				$rsp = XN_REST::put($url, $xml,'text/xml');  
			
		   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA);  
				 
				if ($xmlObj->getName() == "error")
				{
					 throw new XN_Exception(trim($xmlObj));
				}
				elseif ($xmlObj->getName() == "feed")
				{
			   	    $profileid =  trim($xmlObj->profileid); 
					$sn =  trim($xmlObj->sn); 
					$datetime=  trim($xmlObj->datetime);  
					return array('profileid' => $profileid,'sn' => $sn, 'datetime' => $datetime,);
				}
				else
				{
			   	 	throw new XN_Exception("Unrecognized Result");
				} 
		} 
	}
}  
