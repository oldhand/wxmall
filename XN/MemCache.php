<?php
 
if (! class_exists('XN_MemCache')) 
{ 
	class XN_MemCache 
	{
		public static $caches;
		public static function xmlentities($string) 
	    {
		   return str_replace ( array ( '&', '"', "'", '<', '>', '' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), $string );
		}
		public static function html_entity_decode($string) 
	    {
		   return str_replace (array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), array ( '&', '"', "'", '<', '>', '' ),  $string );
		}
		public static function put($value,$key=null,$expire=null) 
		{
			if (is_array($value))
			{
				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
		 	    if ($key)
				{
					$xml .= '<key>'.$key.'</key>';
				}
				else
				{
					$xml .= '<key></key>';
				} 
		 	    if ($expire)
				{
					$xml .= '<expire>'.$expire.'</expire>';
				}
				else
				{
					$xml .= '<expire>0</expire>';
				} 
				$xml .= '<type>serialize</type>';
				$xml .= '<value>'.base64_encode(serialize($value)).'</value>';
				$xml .= '</feed>';
			}
			else
			{
				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
		 	    if ($key)
				{
					$xml .= '<key>'.$key.'</key>';
				}
				else
				{
					$xml .= '<key></key>';
				} 
		 	    if ($expire)
				{
					$xml .= '<expire>'.$expire.'</expire>';
				}
				else
				{
					$xml .= '<expire>0</expire>';
				} 
				$xml .= '<type>kv</type>';
				$xml .= '<value>'.self::xmlentities($value).'</value>';
				$xml .= '</feed>';
			}
			$url = '/memcache'; 
			$rsp = XN_REST::put($url, $xml,'text/xml'); 
	   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA); 
			
			
			if (isset(XN_MemCache::$caches[$key]))
			{
				unset(XN_MemCache::$caches[$key]);
			} 
			if ($xmlObj)
			{
				if ($xmlObj->getName() == "error")
				{
					 throw new XN_Exception(trim($xmlObj));
				}
				else
				{
			   	    $key =  trim($xmlObj->key); 
			   	    /*$type =  trim($xmlObj->type);  
					if ($type == "serialize") 
					{
					    $value = trim($xmlObj->value);  
						$value = unserialize($value);
					}
					else
					{
					    $value = trim($xmlObj->value);  
					}*/
				    return $key;
				} 
			}
			else
			{
				throw new XN_Exception("Wrong XML format.");
			}
		}
		public static function getmany($keys)  
		{
			$lists = array();
			foreach ( array_chunk($keys,20,true) as $chunk_keys)
			{
				$lists = array_merge($lists,self::get($chunk_keys));
			}
			return $lists;
		}
		public static function get($key) 
		{
			if (is_array($key))
			{
				$rsp = XN_REST::get(XN_REST::urlsprintf('/memcache(%s)','id in ['.implode(',',$key).']'));
		   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA); 
				if ($xmlObj)
				{
					if ($xmlObj->getName() == "error")
					{
						 throw new XN_Exception(trim($xmlObj));
					}
					else
					{
						$list = array();
						$entrylist = $xmlObj->entry; 
						foreach ( $entrylist as  $node )
						{
					   	    $key =  trim($node->key); 
					   	    $type =  trim($node->type);  
							if ($type == "serialize") 
							{
							    $value = trim($node->value);  
								$value = unserialize(base64_decode($value));
							}
							else
							{
							    $value = self::html_entity_decode(trim($node->value));  
							}
							$list[$key] = $value;
						}
					    return $list;
					} 
				}
				else
				{
					throw new XN_Exception("Wrong XML format.");
				}
			}
			else
			{ 
				if (isset(XN_MemCache::$caches[$key]))
				{
					return XN_MemCache::$caches[$key];
				}
				else
			    {
					$rsp = XN_REST::get(XN_REST::urlsprintf('/memcache(%s)',"id='".$key."'"));
				 
			   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA);  
				 
					if ($xmlObj)
					{
						if ($xmlObj->getName() == "error")
						{
							 throw new XN_Exception(trim($xmlObj));
						}
						else
						{
					   	    $key =  trim($xmlObj->key); 
					   	    $type =  trim($xmlObj->type);  
							if ($type == "serialize") 
							{
							    $value = trim($xmlObj->value);   
								$value = unserialize(base64_decode($value));
							}
							else
							{
							    $value = self::html_entity_decode(trim($xmlObj->value)); 
							}
							XN_MemCache::$caches[$key]=$value;
						    return $value;
						} 
					}
					else
					{
						throw new XN_Exception("Wrong XML format.");
					}
				} 
				
			}
	   	    
		}
		public static function delete($key) 
		{
			if (is_array($key))
			{
				 XN_REST::delete(XN_REST::urlsprintf('/memcache(%s)','id in ['.implode(',',$key).']')); 
			}
			else
			{
				 if (isset(XN_MemCache::$caches[$key]))
				 {
					unset(XN_MemCache::$caches[$key]);
				 } 
				 XN_REST::delete(XN_REST::urlsprintf('/memcache(%s)',"id='".$key."'")); 
			}
		}
		public static function list_put($value,$key=null) 
		{
			if (is_array($value))
			{
				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
		 	    if ($key)
				{
					$xml .= '<key>'.$key.'</key>';
				}
				else
				{
					$xml .= '<key></key>';
				} 
				$xml .= '<type>serialize</type>';
				$xml .= '<value>'.serialize($value).'</value>';
				$xml .= '</feed>';
			}
			else
			{
				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
		 	    if ($key)
				{
					$xml .= '<key>'.$key.'</key>';
				}
				else
				{
					$xml .= '<key></key>';
				} 
				$xml .= '<type>kv</type>';
				$xml .= '<value>'.$value.'</value>';
				$xml .= '</feed>';
			}
			$url = '/memcachelist';
			$rsp = XN_REST::put($url, $xml,'text/xml'); 

	   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA); 
			if ($xmlObj->getName() == "error")
			{
				 throw new XN_Exception(trim($xmlObj));
			}
			else
			{
				$key =  trim($xmlObj->key); 
				$list = array();
				$entrylist = $xmlObj->entry; 
				foreach ( $entrylist as  $node )
				{ 
			   	    $type =  trim($node->type);  
					$childkey =  trim($node->key);
					if ($type == "serialize") 
					{
					    $value = trim($node->value);  
						$value = unserialize($value);
					}
					else
					{
					    $value = trim($node->value);  
					}
					$list[$childkey] = $value;
				}
			    return array($key=>$list);
			} 
		} 
		public static function list_get($key) 
		{
			$rsp = XN_REST::get(XN_REST::urlsprintf('/memcachelist(%s)',"id='".$key."'"));
			 
	   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA); 
			if ($xmlObj->getName() == "error")
			{
				 throw new XN_Exception(trim($xmlObj));
			}
			else
			{
				$key =  trim($xmlObj->key); 
				$list = array();
				$entrylist = $xmlObj->entry; 
				foreach ( $entrylist as  $node )
				{ 
			   	    $type =  trim($node->type);  
					$childkey =  trim($node->key);
					if ($type == "serialize") 
					{
					    $value = trim($node->value);  
						$value = unserialize($value);
					}
					else
					{
					    $value = trim($node->value);  
					}
					$list[$childkey] = $value;
				}
			    return array($key=>$list);
			} 
		}
		public static function list_delete($key,$value=null) 
		{
			if ($value != null)
			{   
				if (is_array($value))
				{
					$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
			 	    $xml .= '<key>'.$key.'</key>';
					$xml .= '<type>serialize</type>';
					$xml .= '<value>'.serialize($value).'</value>';
					$xml .= '</feed>';
				}
				else
				{
					$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';
			 	    $xml .= '<key>'.$key.'</key>';
					$xml .= '<type>kv</type>';
					$xml .= '<value>'.$value.'</value>';
					$xml .= '</feed>';
				} 
				XN_REST::post(XN_REST::urlsprintf('/memcachelist(%s)',"id='".$key."'"),$xml,'text/xml'); 
			}
			else
			{
				XN_REST::delete(XN_REST::urlsprintf('/memcachelist(%s)',"id='".$key."'")); 
			}
		}  
	}

 

}  
