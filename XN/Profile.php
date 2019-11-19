<?php 
if (! class_exists('XN_Profile')) { 
	class XN_Profile 
	{
	    public static $VIEWER; 
	    private static $_currentProfile = null;
	    private $_lazyLoaded = array('uploadEmailAddress' => false); 
	    private $_notSettable = array('identifier','published');  
	    private $_changed = array();
		
	    protected $_data = array( 
					    'profileid' => null, //用户ID 唯一 36进制ID
					    'identifier' => null, //用户自增ID
					    'fullname' => null,  //用户名称
						'givenname' => null, //昵称
						'realname' => null, //实际姓名
						'mobile' => null, //手机
						'regioncode' => null, //国际区位
						'email' => null, //邮箱
				        'type' => null, //类型
						'link' => null, //头像
						'online' => null, //是否在线
				        'status' => null, //状态 可用与禁用
					    'gender' => null, //姓名
					    'age' => null,  //年龄 不能赋值,取决于生日 
					    'country' => null, //国家
						'province' => null, //省份
						'city' => null,   //城市
					    'birthdate' => null, //生日
					    'password' => null, //登录密码
						'system' => null,  //注册时所用系统
						'browser' => null, //注册时所用浏览器
						'reg_ip' => null, //注册时IP地址
						'published' => null, //注册日期  
						'identitycard' => null, //身份证号码
				    );
    
	   

    
	    public function debugString() 
		{
	        $str =  "XN_Profile:<br>";
			foreach (array_keys($this->_data) as $property) 
			{ 
			     $str .= "  $property [" . $this->$property."]<br>";
			} 
	        return $str;
	    }
	    public function __get($name) 
		{
				if (array_key_exists($name, $this->_data)) 
				{ 
				    if (isset($this->_lazyLoaded[$name]) && ($this->_lazyLoaded[$name] === false))
					{
						$this->_lazyLoad($name);
				    }
				    return $this->_data[$name];
				}
				else 
				{
				    throw new XN_Exception("Invalid property name: '$name'");
				}
	    }      
	    public function __set($name, $value) 
		{
			if (array_key_exists($name, $this->_data) && (! in_array($name, $this->_notSettable))) 
			{
			    $this->_data[$name] = $value;
			    $this->_changed[$name] = true;
			    if ($name == 'birthdate') 
				{
					$this->_setAgeFromBirthdate();
			    }
			}
			else 
			{
			    throw new XN_Exception("Invalid property name: '$name'");
			}
	    } 
	    public static function load($profileid=null,$keyname=null,$tag = null) 
		{
	        if (! isset($profileid)) 
			{
	            if (! isset(self::$VIEWER)) 
				{
	                return new XN_Profile();
	            } 
				else 
				{
	                $profileid = self::$VIEWER;
	            }
	        }
			if (is_array($profileid)) 
			{
			    return self::loadMany($profileid,$keyname,$tag);
			} 
			else 
			{
				if ($keyname==null)
				{
					$keyname = 'id';
				}
		   
	            try {
					 if ( $tag != null)
					 {
							 $headers = array('tag' => $tag);
							 $rsp = XN_REST::get(XN_REST::urlsprintf(XN_AtomHelper::APP_REST_PREFIX() . "/profile($keyname='%s')?xn_out=xml",XN_REST::singleQuote($profileid)),$headers); 
					 }
					 else
					 {
							  $rsp = XN_REST::get(XN_REST::urlsprintf(XN_AtomHelper::APP_REST_PREFIX() . "/profile($keyname='%s')?xn_out=xml",XN_REST::singleQuote($profileid))); 
					 }						 
				 
	                 $p = XN_AtomHelper::loadFromAtomFeed($rsp, 'XN_Profile', true, true); } catch (Exception $e) {
	                 if ($e->getCode() == 404) 
					 {
	                    $p = null;
	                 }
	            }
				catch(XN_Exception $e)
				{
					throw $e; 
				} 
	            if (is_null($p) && ($profileid == self::$VIEWER)) 
				{
	                throw new XN_Exception("Unable to load profile for current user ($profileid) key=$keyname");
		   	 	}
	            else if (!$p) 
				{
	                throw new XN_Exception("No profile found for user ($profileid) key=$keyname");
	            }
		    	return $p;
	    	}
	    } 
	    public static function loadMany($profileids,$keyname=null,$tag = null) 
		{
			if ($keyname==null)
			{
				$keyname = 'id';
			}
			$url = XN_AtomHelper::APP_REST_PREFIX().'/profile('.rawurlencode("$keyname in ['" . implode("','", $profileids) . "']") . ')?xn_out=xml';
		    if ( $tag != null)
			{
				 $headers = array('tag' => $tag);
				 $xml = XN_REST::get($url,$headers);
			}
			else
			{
				 $xml = XN_REST::get($url);
			}
			$profiles = XN_AtomHelper::loadFromAtomFeed($xml, 'XN_Profile', false, true, '2.0');
			return $profiles;
	    }     
	    public static function create($email, $password) 
		{
			$profile = new XN_Profile();
			$profile->email = $email;
			$profile->password = $password;
			return $profile;
	    }    
	    public function save($tag = null,$auth = null) 
		{
			$entry = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">'.$this->_toAtomEntry().'</feed>';
			if (mb_strlen($this->profileid)) 
			{
			    $url = XN_REST::urlsprintf(XN_AtomHelper::APP_REST_PREFIX() . "/profile(id='%s')?xn_out=xml", $this->profileid);
	            try {
				    if ( $tag != null)
					{
						 $headers = array('tag' => $tag);
						 $rsp = XN_REST::put($url, $entry,'text/xml',$headers);
					}
					else
					{
						$rsp = XN_REST::put($url, $entry);
					}
	            } 
				catch (Exception $e) 
				{
	                return XN_REST::parseErrorsFromException($e);
	            }
			}
			else 
			{
			    $headers = array();
			    $url = XN_AtomHelper::APP_REST_PREFIX() . "/profile?xn_out=xml";
			    try {
					if ( $tag != null)
					{
						$headers['tag'] = $tag;
					}
					$rsp = XN_REST::post($url, $entry, 'text/xml', $headers);

			    } 
				catch (Exception $e) 
				{
		            return XN_REST::parseErrorsFromException($e);
		        }
			} 
			$d = new DomDocument();
			$d->loadXML($rsp);
			$x = XN_AtomHelper::XPath($d);
			$node = $x->query('/atom:feed/atom:entry')->item(0);
			if ($node == null) 
			{
				 $errormsg = $d->getElementsByTagName("error")->item(0)->nodeValue;		
			     throw new XN_Exception($errormsg); 
			} 
			$this->_fromAtomEntry($x, $node);
			return true;
	    }
	    public static function signIn($profileidOrEmail, $password, $options = array()) 
		{
			if (! isset($options['max-age'])) 
			{
			    $options['max-age'] = -1;
			}
			if (! isset($options['set-cookies'])) 
			{
			    $options['set-cookies'] = true;
			} 
			$url = XN_REST::urlsprintf(XN_AtomHelper::APP_REST_PREFIX() . "/profile(id='%s')/signin?xn_out=xml&max-age=%d", XN_REST::singleQuote($profileidOrEmail), $options['max-age']);
			$host = preg_replace('@:\d+$@','',XN_AtomHelper::HOST_APP(XN_Application::$CURRENT_URL));
			$headers = array('Authorization' => 'Basic ' . base64_encode($profileidOrEmail.':' . $password),'Host' => $host);
	        try {
	            $rsp = XN_REST::post($url, '', 'text/plain', $headers);
	            if ($rsp != 'ok') throw new XN_Exception("INVALID_PASSWORD");
	            if ($options['set-cookies']) 
				{
	                XN_REST::setLastResponseCookies();
	            }
	            return true;
	        } 
			catch (Exception $e) 
			{
		     	return false;
	        }
	    }	 
	    public static function signOut() 
		{
	        try {
		  	  	XN_REST::post(XN_AtomHelper::APP_REST_PREFIX() . '/profile/signout?xn_out=xml','','text/plain',array('Host' => $_SERVER['HTTP_HOST']));
	            XN_REST::setLastResponseCookies();
	            return true;
	        } 
			catch (Exception $e) 
			{
	            return XN_REST::parseErrorsFromException($e);
	        }
	    }     
	    public static function current() 
		{
	        if (is_null(self::$_currentProfile)) 
			{
	            if (isset(self::$VIEWER)) 
				{
	                self::$_currentProfile = XN_Profile::load(self::$VIEWER);
	            } 
				else 
				{
	                self::$_currentProfile = new XN_Profile();
	            }
	        }
	        return self::$_currentProfile;
	    }     
	    public function isLoggedIn() 
		{
	        return ((! is_null($this->profileid)) && ($this->profileid == self::$VIEWER));
	    }    
	    public function isOwner() 
		{
	        return ((! is_null($this->profileid)) && (strtolower($this->profileid) ==  strtolower(XN_Application::load()->owner)));
	    }  
	    function _getId()
		{
	        return $this->profileid;
	    } 
	    static function _get($profileid) 
		{
	        if ($profileid == NULL) { return NULL; }
	        $profile = XN_Cache::_get($profileid, 'XN_Profile');
	        return $profile!=NULL ? $profile : self::load($profileid); 
	    } 
	    private function __construct(){}   
	    public static function _createEmpty() 
		{
	        return new XN_Profile();
	    }
	    public function _copy($obj)
		{
			$this->_data = $obj->_data;
	    }    
	    private function _lazyLoad($prop) 
		{
	         if (! isset($this->_lazyLoaded[$prop])) 
			 {
	             throw new XN_IllegalArgumentException("Unknown property: $prop");
	         }
	         if ($this->_lazyLoaded[$prop]) 
			 {
	             return;
	         }
	    }     
	    public function _fromAtomEntry(XN_XPathHelper $x, DomNode $node) 
		{
	        $this->_data['profileid'] = $x->textContent('xn:id', $node);
		    $this->_data['profileid'] = $x->textContent('xn:id', $node);
	        $this->_data['fullname'] = $x->textContent('atom:title', $node);  

			$fields = array('type','gender','givenname','link','country','email','birthdate','mobile','regioncode','status','password',
						'province','city','online','system','browser','realname','identitycard','identifier','reg_ip',);

			foreach ($fields as $prop) 
			{
			    $this->_data[$prop] = $x->textContent("xn:$prop", $node, true);
			}
	
			$this->_data['published'] = $x->textContent("atom:published", $node, true); 
	
			$this->_setAgeFromBirthdate();
	        return $this;
	    }
	    protected function _setAgeFromBirthdate() 
		{
			if (mb_strlen($this->_data['birthdate']) && preg_match('/^\d{4}-\d\d-\d\d$/',$this->_data['birthdate'])) 
			{ 
				list($y,$m,$d) = explode('-',gmdate('Y-m-d')); 
				list($by,$bm,$bd) = explode('-', $this->_data['birthdate']); 
				$this->_data['age'] = $y - $by; 
				if (($m < $bm) || (($m == $bm) && ($d < $bd))) 
				{
					$this->_data['age']--;
				}
			}
			else 
			{
				$this->_data['age'] = null;
			}
	    }    
	    protected function _toAtomEntry() 
		{
			$xml = XN_REST::xmlsprintf('<entry xmlns="%s" xmlns:xn="%s"><id>%s</id><title type="text">%s</title>',
								XN_AtomHelper::NS_ATOM, XN_AtomHelper::NS_XN,$this->_toAtomId(), $this->fullname);
			if (mb_strlen($this->profileid)) 
			{
			    $xml .= XN_REST::xmlsprintf("  <xn:id>%s</xn:id>\n", $this->profileid);
			}

			$fields = array('type','gender','givenname','link','country','email','birthdate','mobile','regioncode','status',
						'province','city','online','system','browser','realname','identitycard','identifier','reg_ip',);
						
			foreach ($fields as $prop) 
			{
			    if (mb_strlen($this->$prop)) 
				{
					$xml .= XN_REST::xmlsprintf("  <xn:$prop>%s</xn:$prop>\n", $this->$prop);
			    }
				else
				{
					if (isset($this->_changed[$prop]) && in_array($prop,array("mobile"))) 
					{
					    $xml .= XN_REST::xmlsprintf("  <xn:$prop>%s</xn:$prop>\n", $this->$prop);
					} 
				}
			}
	 
			if (isset($this->_changed['profileid'])) {
			  $xml .= XN_REST::xmlsprintf("  <xn:profileid>%s</xn:profileid>\n", $this->profileid);
			}
			if (isset($this->_changed['password'])) {
			    $xml .= XN_REST::xmlsprintf("  <xn:password>%s</xn:password>\n", $this->password);
			} 
			else {
			    $xml .= "  <xn:password ignore='true' />\n";
			}
			$xml .= '</entry>';
			return $xml;
	    }
	    protected function _toAtomId() 
		{
			return 'http://www.ning.com/profile/' . $this->profileid;
	    }    
	    private static function _roleToAtom($roleCode) 
		{
	        return XN_REST::xmlsprintf('<xn:role xmlns:xn="'.XN_AtomHelper::NS_XN.'" name="%s"/>',$roleCode);
	    } 
	}
} 
