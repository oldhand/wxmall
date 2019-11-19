<?php
/** Defines the XN Application PHP API.
 * @file
 * @ingroup XN
 */ 

if (! class_exists('XN_Application')) 
{ 
	class XN_Application 
	{
	    public static $CURRENT_URL; 
		public static $DESCRIPTION; 
	    private $_loadWasDelayed = false; 
	  
		private $_active;
		private $_trialtime;  
	    private $_published;
	    private $_updated;
	    private $_name;
	    private $_description;
	    private $_relativeUrl;  
		private $_owner;  
	    
	    private $_unsaved = null;
		public $_tag = null; 
		private $_needupdated = null;	 
    
	    public function debugString() 
		{
	        $str =  "XN_Application:<br>";
	        $str .= "\t\tpublished [".$this->published."]<br>";
	        $str .= "\t\tupdated [".$this->updated."]<br>";
	        $str .= "\t\tname [".$this->name."]<br>";
	        $str .= "\t\tdescription [".$this->description."]<br>";
	        $str .= "\t\trelativeUrl [".$this->relativeUrl."]<br>";
	        $str .= "\t\ttrialtime [".$this->trialtime."]<br>";
			$str .= "\t\tactive [".$this->active."]<br>"; 
	        $str .= "\t\towner [".$this->owner."]<br>";  
	        return $str;        
	    }      
	    public function __get($name) 
		{
	        return $this->_getProperty($name);
	    }
	    public function __set($name, $value) 
		{
	        $this->_lazyLoad($name);
	        $allowed_properties = array('trialtime','active','owner','description'); 
	        if (in_array($name, $allowed_properties)) 
			{ 
			    $this->{"_$name"}  = $value;
			    $this->_needupdated = true;
			}
	        else 
			{
	            throw new XN_IllegalArgumentException("Property $name is not settable");
	        } 
	    }    
	    public static function load($relativeUrl = null,$tag = null) 
		{
	      if (is_null($relativeUrl) || (strcasecmp($relativeUrl,self::$CURRENT_URL) == 0)) 
		  {
	            $app = XN_Cache::_get(self::$CURRENT_URL, 'XN_Application');
	            if (is_null($app)) 
				{ 
	                 $app = XN_Application::_createEmpty();
	                 $app->_relativeUrl = self::$CURRENT_URL;
	                 $app->_loadWasDelayed = true;
					 $app->_tag = $tag;
	                 XN_Cache::_put($app);
	            }
	            return $app;
	        } 
			else 
			{
				  if (is_array($relativeUrl)) 
				  {   
				         return self::loadMany($relativeUrl);
				  }
				  else 
				  {		 
			            $cachedApp = XN_Cache::_get($relativeUrl, 'XN_Application');
			            if (is_null($cachedApp)) 
						{  
							   $url = XN_REST::urlsprintf("/application(domain='%s')", $relativeUrl);
							   if ( $tag != null)
							   {
									 $headers = array('tag' => $tag);
									 $rsp = XN_REST::get($url,$headers);
							   }
							   else
							   {
									$rsp = XN_REST::get($url);
							   } 
						       return XN_AtomHelper::loadFromAtomFeed($rsp,'XN_Application');
					    } 
						else 
						{
					           return $cachedApp;
					    }
				  }
	        }
	    } 	
	    public static function create($subdomain, $props = null) 
		{
	        if (! is_array($props)) { $props = array(); }
	        $app = XN_Application::_createEmpty();
	        $app->_relativeUrl = $subdomain; 
	        $app->_name = isset($props['name']) ? $props['name'] : $subdomain;
	        $app->_description = isset($props['description']) ? $props['description'] : '';
	        $app->_unsaved = true;
	        return $app;
	    }  
	    public function save($tag = null) 
		{
			 if ($this->_needupdated === true)
			 {
		            try 
					{            	
		            	$entry = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">'. $this->_toAtomEntry(true).'</feed>';
            	
						$headers = array();
		            	if ($tag != null)
						{
							$headers['tag'] = $tag;
						}
				        $res = XN_REST::put(XN_AtomHelper::APP_REST_PREFIX('1.0').'/application?xn_out=xml',$entry,'application/atom+xml',$headers);
		            } 
					catch (Exception $e) 
					{ 
		                throw XN_Exception::reformat("Failed to save Application:<br>" . $this->debugString(), $e);
		            }
			}
	        else if ($this->_unsaved === true) 
			{
	            try { 
	            	$entry = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">'.$this->_toAtomEntry().'</feed>';
					$headers = array();
	            	if ($tag != null)
					{
						$headers['tag'] = $tag;
					}
	                $res = XN_REST::post(XN_AtomHelper::APP_REST_PREFIX('1.0').'/application?xn_out=xml', $entry, 'application/atom+xml',$headers);
	                $this->_unsaved = false;  
	                $d = new DomDocument();
					$d->loadXML($res);
					$x = XN_AtomHelper::XPath($d);
					$node = $x->query('/atom:feed/atom:entry')->item(0);
					if ($node == null) 
					{ 
						 $errormsg = "save application error.";		
					     throw new XN_Exception($errormsg); 
					} 
					$this->_fromAtomEntry($x, $node);
				
	            } 
				catch (Exception $e) 
				{
				
	                throw XN_Exception::reformat("Failed to save Application:<br>" , $e);
	            }
	        } 
			return true;
	    }  
	    function _getId()
		{
	        return $this->_relativeUrl;
	    }
	    static function _get($relativeUrl) 
		{
	        if ($relativeUrl == NULL) { return NULL; }
	        $application = XN_Cache::_get($relativeUrl, 'XN_Application');
	        return $application!=NULL ? $application : self::load($relativeUrl); 
	    }
	    private function __construct() 
		{
	    }  
	    public static function _createEmpty() 
		{
	        return new XN_Application();
	    } 
	    private function _getProperty($name)
		{
	        if (strpos($name, '_') === 0) 
			{
	            throw new XN_IllegalArgumentException("Invalid property name: '".$name."'");
	        }
	        return $this->_lazyLoad($name);
	    }      
	    private function _lazyLoad($prop) 
		{
	        if ($this->_loadWasDelayed && ($prop != 'relativeUrl')) 
			{
	            if ($this->_relativeUrl != self::$CURRENT_URL)
				{
	                throw new XN_Exception('Delayed loading only supported for current application.');
	            }
  	    	 
				if ($this->_tag != null)
				{
					$headers = array('tag' => $this->_tag);
					$rsp = XN_REST::get('/application',$headers);
				}
				else
				{
					$rsp = XN_REST::get('/application');
				}  
	            $app = XN_AtomHelper::loadFromAtomFeed($rsp,'XN_Application');
	            $this->_copy($app);
	            $this->_loadWasDelayed = false;
	        }

        
	        if (($this->_unsaved === true) && (! in_array($prop, array('name','description','owner','active','trialtime')))) 
			{
	            throw new XN_Exception("Only name, description, for unsaved apps  ($prop) ");
	        }
        
	        switch ($prop) 
			{
		        case "published":
		            return $this->_published;
		        case "updated":
		            return $this->_updated;
		        case "name":
		            return $this->_name;
		        case "description":
		            return $this->_description; 
			    case "relativeUrl":
			        return $this->_relativeUrl;
		        case "owner":
		            return $this->_owner; 
				case "active":
				 	return $this->_active;
				case "trialtime":
				 	return $this->_trialtime; 	 	
		        default:
		            throw new XN_IllegalArgumentException("Invalid property name: '".$prop."'");
	        }
	    }
	    public function _copy($obj)
		{
	        $this->_published = $obj->_published;
	        $this->_updated = $obj->_updated;
	        $this->_name = $obj->_name;
	        $this->_description = $obj->_description;
	        $this->_relativeUrl = $obj->_relativeUrl; 
	        $this->_owner = $obj->_owner; 
			$this->_tag = $obj->_tag; 
			$this->_active = $obj->_active;
			$this->_trialtime = $obj->_trialtime; 
	    } 
	    public function _fromAtomEntry(XN_XPathHelper $x, DomNode $node) 
		{
	         $this->_published = $x->textContent('atom:published', $node);
	         $this->_updated = $x->textContent('atom:updated', $node);
	         $this->_name = $x->textContent('atom:id', $node);
	         $this->_description = $x->textContent('atom:title', $node, true);
	         $this->_relativeUrl = $x->textContent('atom:id', $node); 
			 $this->_active = $x->textContent('xn:active', $node);
			 $this->_trialtime = $x->textContent('xn:trialtime', $node);  
	         $this->_owner = $x->textContent('atom:author/atom:name',$node, true); 
	         return $this;
	     }
		private function _toAtomEntry($put=false) 
		{
			if ($put)
			{
				$xml = XN_REST::xmlsprintf(trim('<entry xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://www.ning.com/atom/1.0">
					  <id>%s</id> 
			  		  <xn:id>%s</xn:id> 
					  <xn:application>%s</xn:application> 	  		  
					  <author>
					    <name>%s</name>
					  </author> 
					  <title>%s</title>  
					  <xn:description>%s</xn:description> 
					  '), $this->_name,$this->_name,$this->_name,$this->_owner,$this->_description,$this->_description);
			}
			else
			{ 
				$xml = XN_REST::xmlsprintf(trim('<entry xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://www.ning.com/atom/1.0">
					  <id>%s</id> 
			  		  <xn:id>%s</xn:id> 	
			  		  <xn:application>%s</xn:application> 			
					  <author>
					    <name>%s</name>
					  </author> 
					  <title>%s</title>  
					  <xn:description>%s</xn:description>  
					  <xn:trialtime>'.date( "Y-m-d 00:00:00",time()+24*60*60*30).'</xn:trialtime>  
					  <xn:active>true</xn:active> 
					  '), $this->_name,$this->_name,$this->_name,$this->_owner,$this->_description,$this->_description);
			}
	                
       		foreach (array('trialtime','active') as $prop) 
			{
                $p = $this->{"_$prop"};
                if (! is_null($p)) 
				{
                    $xml .= XN_REST::xmlsprintf("    <xn:$prop>%s</xn:$prop>\n", $p);
                }
			}
			$xml .= '</entry>';      
	        return $xml;
	    } 
	} 
}  

