<?php
 
if (! class_exists('XN_Backup')) 
{
class XN_Backup { 
    const screenName = 'screenName';
    const fullName = 'fullName';
    const uploadEmailAddress = 'uploadEmailAddress';

    protected $_data =
      array( 
        'id' => null,
        'path' => null,
        'status' => null,
		'style' => null,
		'application' => null,
		'type' => null,
        'published' => null,
	    );
    
	private $_lazyLoaded = array();
    private $_notSettable = array( 'path', 'published');
    private $_changed = array();
	
    public function debugString() 
	{
        $str =  "XN_Backup:\n";
		foreach (array_keys($this->_data) as $property) 
		{
		  $str .= "  $property [" . $this->$property."]\n";
		}
        return $str;
    }
    function _getId()
	{
        return $this->_data['id'];
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
		}
		else {
		    throw new XN_Exception("Invalid property name: '$name'");
		}
    }
    public static function restore($id) 
	{
        $entry = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0"></feed>';
	    $url = XN_AtomHelper::APP_REST_PREFIX().'/backup(id='.$id.')?xn_out=xml';
            try 
			{
                $rsp = XN_REST::put($url, $entry);
            } 
			catch (Exception $e) 
			{
                return XN_REST::parseErrorsFromException($e);
            }
    }
    public static function delete($id) 
	{
        try {
            $url = XN_AtomHelper::APP_REST_PREFIX().'/backup(id='.$id.')?xn_out=xml';
		    XN_REST::delete($url);
            return true;
        } 
		catch (Exception $e) 
		{
            return XN_REST::parseErrorsFromException($e);
        }
    }
    public static function load($id) 
	{
        $url = XN_AtomHelper::APP_REST_PREFIX().'/backup(id='.$id.')?xn_out=xml';
		$xml = XN_REST::get($url);
		$backup = XN_AtomHelper::loadFromAtomFeed($xml, 'XN_Backup', true, true);
		return $backup;
    }
    public static function loadMany() 
	{
		$url = XN_AtomHelper::APP_REST_PREFIX().'/backup?xn_out=xml';
		$xml = XN_REST::get($url);
		$backups = XN_AtomHelper::loadFromAtomFeed($xml, 'XN_Backup', false, true, '2.0');
		return $backups;
    }
    public static function create() 
	{
		$backup = new XN_Backup();
		return $backup;
    }
    public function save() 
	{
		$entry = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">'.$this->_toAtomEntry().'</feed>';	
		$headers = array();
		$url = XN_AtomHelper::APP_REST_PREFIX() . "/backup?xn_out=xml";
		try 
		{
			$rsp = XN_REST::post($url, $entry, 'text/xml', $headers);
		} 
		catch (Exception $e) 
		{
				return XN_REST::parseErrorsFromException($e);
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
		return $this;
    }
    private function __construct() { }  
    public static function _createEmpty() 
	{
        return new XN_Backup();
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
         if ($this->_lazyLoaded[$prop]) {  return; }         
    }
    public function _fromAtomEntry(XN_XPathHelper $x, DomNode $node) 
	{
        $this->_data['id'] = $x->textContent('xn:id', $node);  
        $this->_data['published'] = $x->textContent('atom:published', $node);        
		foreach (array( 'path','status','appliction','type','style') as $prop) 
		{
		    $this->_data[$prop] = $x->textContent("xn:$prop", $node, true);
		}
        return $this;
    }
	protected function _toAtomEntry() 
	{
		$xml = XN_REST::xmlsprintf('<entry xmlns="%s" xmlns:xn="%s"><id></id> <title type="text">backup</title> <summary type="text">backup</summary>',
				XN_AtomHelper::NS_ATOM, XN_AtomHelper::NS_XN);	
		foreach (array( 'path','status') as $prop) 
		{
		    if (mb_strlen($this->$prop)) 
			{
				$xml .= XN_REST::xmlsprintf("  <xn:$prop>%s</xn:$prop>\n", $this->$prop);
		    }
		}
		$xml .= '</entry>';
		return $xml;
    }
}

} 
