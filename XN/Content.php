<?php

if (! class_exists('XN_Content')) 
{
class XN_Content {
    protected $_node;
    protected $_x; 
    public $my;
    protected static $_systemAttributes = 
        array(
              'id' => array('callback' => '_lazyLoadCallback'),
			  'published' => array('xpath' => 'atom:published'), 
			  'updated' => array('xpath' => 'atom:updated'), 
              'type' => array('xpath' => 'xn:type'),
              'title' => array('xpath' => 'atom:title',
                               'write' => true), 
	          'author' => array('xpath' => 'atom:author/atom:name',
                                         'convert' => 'anonymous'),
              'application' => array('xpath' => 'xn:application'),					 
              'datatype' => array('xpath' => 'atom:datatype'), 
              );
    protected static $_xpathForSystemAttributes = '';  
    protected static $_defaultIsPrivate = false;
    protected $_serializationData;
    protected $_lazyLoadedData = array();
    public $_valuesAtLoad = array();
    protected $_bc_unsavedTypes = array();
    protected $_datatype = 0; 
	 
    public function node()
    {
    	return $this->_node;
    } 
    public function datatype()
    {
		if ($this->_datatype == 0 )
		{
			$datatype = $this->_x->textContent('atom:datatype', $this->_node, true);
			if (isset($datatype))
			{
				$this->_datatype = $datatype; 
			}
		} 
    	return $this->_datatype;
    }    
    public function __construct($typeOrNode, $titleOrXpath = null, $anonymous = true,$datatype=0) 
	{
        $this->_datatype=$datatype;        
        if ($typeOrNode instanceof DOMNode) 
		{
            $this->_node = $typeOrNode;
            $this->_x = $titleOrXpath;
            $this->my = new XN_AttributeContainer($this);
            $ns = $this->_node->getAttribute('xmlns:my');
            if ($ns == '') 
			{
                $ns = XN_AtomHelper::NS_APP(XN_Application::load()->relativeUrl);
                $this->_node->setAttribute('xmlns:my', $ns);
            }
            $this->_registerMyNamespace($ns);
            XN_Cache::_put($this);
        }
        else 
		{
            if (! is_null($titleOrXpath)) 
			{
                $titleXml = XN_REST::xmlsprintf('<title type="text">%s</title>', $titleOrXpath);
            } 
			else 
			{ $titleXml = ''; } 
		    if ($anonymous) 
			{
			    $author = XN_AtomHelper::XN_ANONYMOUS;
		    } 
			else 
			{		    
			    $author = XN_Profile::$VIEWER != null ? XN_Profile::$VIEWER : XN_AtomHelper::XN_ANONYMOUS;
		    }
	    	$authorXml = XN_REST::xmlsprintf('<author><name>%s</name></author>', $author);
	    
            $this->_buildFromXml(XN_REST::xmlsprintf(trim('<entry xmlns="%s" xmlns:xn="%s" xmlns:my="%s"><xn:type>%s</xn:type>'.$titleXml.''.$authorXml.'<xn:application>%s</xn:application></entry>'), 
						XN_AtomHelper::NS_ATOM, XN_AtomHelper::NS_XN,XN_AtomHelper::NS_APP(XN_Application::load()->relativeUrl),$typeOrNode,XN_Application::load()->relativeUrl));
            
        }
    } 
    protected function _xquery($expr) 
	{
        return $this->_x->query($expr, $this->_node);
    } 
    protected function _buildFromXml($xml, $root = '/atom:entry') 
	{
        $doc = new DOMDocument();
        $doc->loadXml($xml);
        $this->_x = XN_AtomHelper::XPath($doc);
        $this->_node = $this->_x->query($root)->item(0);
        $this->_registerMyNamespace();
        $this->my = new XN_AttributeContainer($this);
    }
    /**     
     * @param $type string the content type 
     * @param $title string an optional title
     * @param $anonymous Whether to save it as anonymous or not
     * @param $datatype 0 => content, 1 => bigcontent, 2 => mq,4 => maincontent,5 => schedule 
	 * @param $datatype 6 => message,7 => yearcontent,9 => yearmonthcontent
     */
    public static function create($typeOrNode, $titleOrXpath = null, $anonymous = true,$datatype = 0) 
	{
        if (is_string($titleOrXpath))
    		$titleOrXpath = str_replace("%", "%%", $titleOrXpath);
        if ($typeOrNode instanceof DOMNode) 
		{
	    	$id = self::_idFromAtomEntry($titleOrXpath, $typeOrNode);
        }
        return new XN_Content($typeOrNode, $titleOrXpath, $anonymous,$datatype);
    }
    public static function createMany(XN_XPathHelper $x,$datatype=0) 
	{
        $objs = array();
        foreach ($x->query('/atom:feed/atom:entry') as $node) 
		{
            $objs[] = self::create($node, $x,false,$datatype);
        }
        return $objs;
    }
    /**     
     * @param $content string|XN_Content|array a string content ID, XN_Content object
     * @param $datatype 0 => content, 1 => bigcontent, 2 => mq,4 => maincontent,5 => schedule 
	 * @param $datatype 6 => message,7 => yearcontent,9 => yearmonthcontent
     */
    public static function load($content,$tag = null,$datatype=0) 
	{
        if (is_array($content))
		{
            return self::loadMany($content,$tag,$datatype);
        }
        $id = is_object($content) ? $content->id : $content;
        try {
        	if ($datatype == 0)
        	{
				if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/content(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/content(id=%s)', $id),$headers);
				}
        	}
        	else if($datatype == 1)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/bigcontent(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/bigcontent(id=%s)', $id),$headers);
				}
        	}
        	else if($datatype == 2)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/mq(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/mq(id=%s)', $id),$headers);
				}
        	}
			else if($datatype == 4)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/maincontent(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/maincontent(id=%s)', $id),$headers);
				}
        	}
			else if($datatype == 5)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/schedule(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/schedule(id=%s)', $id),$headers);
				}
        	}
			else if($datatype == 6)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/message(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/message(id=%s)', $id),$headers);
				}
        	}
			else if($datatype == 7)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/yearcontent(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/yearcontent(id=%s)', $id),$headers);
				}
        	}
			else if($datatype == 9)
        	{
        		if ( $tag == null)
				{
					 $rsp = XN_REST::get(XN_REST::urlsprintf('/yearmonthcontent(id=%s)', $id));
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::get(XN_REST::urlsprintf('/yearmonthcontent(id=%s)', $id),$headers);
				}
        	}
        	else 
        	{
        		throw new XN_Exception("Failed to load content object");
        	}
            $x = XN_AtomHelper::XPath($rsp);
            $node = $x->query('/atom:feed/atom:entry')->item(0);
            if ($node == null)  throw new XN_Exception("error content id.");             
            $obj = self::create($node, $x,false,$datatype);
	    	return $obj;
        } 
		catch (XN_Exception $e) 
		{
            if ($e->getCode() == 404) 
			{
                throw new XN_Exception("'Failed to load content object: ApplicationException: Cannot find content with ID '$id'", 404);
            } 
			else 
			{
                throw $e;
            }
        }
    }	
    public static function loadMany($contents,$tag = null,$datatype=0) 
	{
        if ($contents== null || count($contents) == 0 ){
            return array();
        }
		else if (count($contents) > 50)
		{
			$subcontents = 	array_chunk($contents,50);
			$result = array();
			foreach($subcontents as $subcontent_info)
			{
				$subresult = XN_Content::load_Many($subcontent_info,$tag,$datatype);
				$result = array_merge($result,$subresult);
			}
			return $result;
		}
		else
		{
			$result = XN_Content::load_Many($contents,$tag,$datatype);
			return $result;
		}
    }   
    public static function load_Many($contents,$tag = null,$datatype=0) 
	{
        $ids = array();
        foreach ($contents as $content) 
		{
            $id = is_object($content) ? $content->_id : $content;
            $ids[] = $id;
        }
        if ($datatype == 0)
        {
			if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/content(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/content(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
        else if ($datatype == 1)
        {
        	if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/bigcontent(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/bigcontent(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
    	else if ($datatype == 2)
        {
        	if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/mq(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/mq(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
		else if ($datatype == 4)
        {
        	if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/maincontent(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/maincontent(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
		else if ($datatype == 6)
        {
        	if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/message(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/message(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
		else if ($datatype == 7)
        {
        	if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/yearcontent(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/yearcontent(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
		else if ($datatype == 9)
        {
        	if ( $tag == null)
			{
				 $rsp = XN_REST::get(XN_REST::urlsprintf('/yearmonthcontent(%s)','id in ['.implode(',',$ids).']'));
			}
			else 
			{
				$headers = array('tag' => $tag);
				$rsp = XN_REST::get(XN_REST::urlsprintf('/yearmonthcontent(%s)','id in ['.implode(',',$ids).']'),$headers);
			}
        }
        else 
        {
			throw new XN_Exception("Failed to load content object");
        }
        return self::createMany(XN_AtomHelper::XPath($rsp),$datatype);
    }
    public static function batchsave($objs,$tag = null) 
    {
		$datatype = -1;
		if (count($objs) ==0) return array();
		$update = false;
		foreach($objs as $obj)
		{
		    if (get_class($obj) != 'XN_Content') throw new XN_Exception("obj classname is not XN_Content");
			if ($datatype == -1) $datatype = $obj->datatype();
			if ($datatype != $obj->datatype()) throw new XN_Exception("datatype not the same.");
			if ($obj->id) 	$update = true;
		}
        try {
				if ($datatype == 0 ) 
					$url = '/content';
				else if ($datatype == 1 ) 
					$url = '/bigcontent';
				else if ($datatype == 2 ) 
					$url = '/mq';
				else if ($datatype == 4 ) 
					$url = '/maincontent';
				else if ($datatype == 7 ) 
					$url = '/yearcontent';
				else if ($datatype == 9 ) 
					$url = '/yearmonthcontent';
				else
					 throw new XN_Exception("Can't save object : datatype");
					 
				$subobjs = 	array_chunk($objs,100);
		        $result = array();
    			foreach($subobjs as $subobj_info)
    			{
    				$xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">';

					foreach($subobj_info as $obj)
					{
						$xml .= $obj->node()->ownerDocument->saveXml($obj->node());	
					}
	
					 $xml .= '</feed>';
							
					if ($update)
					{
						if ( $tag == '')
						{
							 $rsp = XN_REST::put($url, $xml,'text/xml');
						}
						else 
						{
							$headers = array('tag' => $tag);
							$rsp = XN_REST::put($url, $xml,'text/xml',$headers);
						}    
					}
					else
					{
						if ( $tag == '')
						{
							 $rsp = XN_REST::post($url, $xml,'text/xml');
						}
						else 
						{
							$headers = array('tag' => $tag);
							$rsp = XN_REST::post($url, $xml,'text/xml',$headers);
						}    
					}           
					$contents = XN_AtomHelper::loadFromAtomFeed($rsp, 'XN_Content', false);
    				$result = array_merge($result,$contents);
    			}				
				return $result;
        } 
		catch (Exception $e) 
		{
            throw XN_Exception::reformat("Failed to save content:\n" , $e);
        }
    }
	public function setPublished($value) 
	{
    	$name = "published";
    	$xpath = self::$_systemAttributes[$name]['xpath'];
    	$nodeList = $this->_xquery($xpath);
    	if ($nodeList->length == 0)
    	{
    	 	$node = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_ATOM,$xpath, self::_valueToAtom($value));
    	 	$this->_node->appendChild($node);
    	}
    	else
    	{
    	 	$node = $nodeList->item(0);
    	}
     	foreach ($node->childNodes as $child) {
            $node->removeChild($child);
        }
    	$node->appendChild($node->ownerDocument->createTextNode($value));
    	return $this; 
    }
	public function setUpdated($value) 
    {
    	$name = "updated";
    	$xpath = self::$_systemAttributes[$name]['xpath'];
    	$nodeList = $this->_xquery($xpath);
    	if ($nodeList->length == 0)
    	{
    	 	$node = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_ATOM,$xpath, self::_valueToAtom($value));
    	 	$this->_node->appendChild($node);
    	}
    	else
    	{
    	 	$node = $nodeList->item(0);
    	}
     	foreach ($node->childNodes as $child) {
            $node->removeChild($child);
        }
    	$node->appendChild($node->ownerDocument->createTextNode($value));
    	return $this;
    }  
    public function save($tag = null,$datatype=null) 
	{
		if ($datatype!=null)
		{
			$this->_datatype=$datatype;
		}
		return $this->_saveProper(false, false,$tag); 
	}    
    public function saveAnonymous($tag = null) { return $this->_saveProper(true, false,$tag); }
    protected function _saveProper($anonymous = false, $cascade = false,$tag = null) 
	{
        try {
        	if ($this->_datatype == 0 ) 
        		$url = '/content';
        	else if ($this->_datatype == 1 ) 
        		$url = '/bigcontent';
        	else if ($this->_datatype == 2 ) 
        		$url = '/mq';
			else if ($this->_datatype == 4 ) 
        		$url = '/maincontent';
			else if ($this->_datatype == 5 ) 
        		$url = '/schedule';
			else if ($this->_datatype == 6 ) 
        		$url = '/message';
			else if ($this->_datatype == 7 ) 
        		$url = '/yearcontent';
			else if ($this->_datatype == 9 ) 
        		$url = '/yearmonthcontent';
        	else
            	 throw new XN_Exception("Can't save object : datatype");
            if ($anonymous) 
			{
                $authorNodeList = $this->_xquery('atom:author');
                if ($authorNodeList->length == 1) 
				{
                    $authorNode = $authorNodeList->item(0);
                    foreach ($authorNode->childNodes as $child) 
					{ 
                        $authorNode->removeChild($child);
                    }
                    $authorNode->appendChild($this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_ATOM, 'atom:name', XN_AtomHelper::XN_ANONYMOUS));
                }
                elseif ($authorNodeList->length == 0)
				{
                    $this->_node->appendChild($authorNode = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_ATOM, 'atom:author'));
                    $authorNode->appendChild($this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_ATOM, 'atom:name', XN_AtomHelper::XN_ANONYMOUS));
                } 
				else 
				{
                    throw new XN_Exception("Can't save object as anonymous: unexpected XML");
                }
            }
            $xml = '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:xn="http://localhost/atom/1.0">'.$this->_node->ownerDocument->saveXml($this->_node).'</feed>';
            if ($this->id) 
			{
            	if ($this->_datatype == 1)
            	{
            		throw new XN_Exception("Failed to save content: bigcontent can not save!(".$this->_datatype.")\n");
            	}
                $url .= '(id=' . $this->id . ')';                	       
	            if ( $tag == null)
				{
					 $rsp = XN_REST::put($url, $xml,'text/xml');
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::put($url, $xml,'text/xml',$headers);
				}
                $this->_x = XN_AtomHelper::XPath($rsp);
                $this->_node = $this->_x->query('/atom:feed/atom:entry')->item(0);
                $ns = $this->_node->getAttribute('xmlns:my');
                if ($ns == '') 
				{
                    $ns = XN_AtomHelper::NS_APP(XN_Application::load()->relativeUrl);
                    $this->_node->setAttribute('xmlns:my', $ns);
                }
                $this->_registerMyNamespace($ns);
                $this->_lazyLoadedData = array();
            } 
			else 
			{
                if ( $tag == '')
				{
					 $rsp = XN_REST::post($url, $xml,'text/xml');
 					 if ($this->_datatype == 5 ) 
 					 {  
 		                $x = XN_AtomHelper::XPath($rsp); 
 		                $node = $x->query('/atom:feed/atom:entry')->item(0); 
 						if (!$node)
 						{
 					        $doc = new DomDocument();
 					        $doc->loadXML($rsp);
 					        $error = $doc->getElementsByTagName('error');
 							$errormsg = $error->item(0)->textContent;  
 							if (isset($errormsg) && $errormsg != "")
 							{ 
 								throw new XN_Exception($errormsg); 
 							} 
 							return array();
 						}  
 						return XN_Content::createMany($x,5); 
 					}
				}
				else 
				{
		        	$headers = array('tag' => $tag);
		            $rsp = XN_REST::post($url, $xml,'text/xml',$headers);
					 
					if ($this->_datatype == 5 ) 
					{  
		                $x = XN_AtomHelper::XPath($rsp); 
		                $node = $x->query('/atom:feed/atom:entry')->item(0); 
						if (!$node)
						{
					        $doc = new DomDocument();
					        $doc->loadXML($rsp);
					        $error = $doc->getElementsByTagName('error');
							$errormsg = $error->item(0)->textContent;  
							if (isset($errormsg) && $errormsg != "")
							{ 
								throw new XN_Exception($errormsg); 
							} 
							return array();
						}  
						return XN_Content::createMany($x,5); 
					}
				}               
                $this->_x = XN_AtomHelper::XPath($rsp);
                $this->_node = $this->_x->query('/atom:feed/atom:entry')->item(0);
				if (!$this->_node)
				{
			        $doc = new DomDocument();
			        $doc->loadXML($rsp);
			        $error = $doc->getElementsByTagName('error');
					$errormsg = $error->item(0)->textContent; 
					throw new XN_Exception($errormsg); 
				}
                $ns = $this->_node->getAttribute('xmlns:my');
                if ($ns == '') 
				{
                    $ns = XN_AtomHelper::NS_APP(XN_Application::load()->relativeUrl);
                    $this->_node->setAttribute('xmlns:my', $ns);
                }
                $this->_registerMyNamespace($ns);
                $this->_lazyLoadedData = array();
            }
            XN_Cache::_put($this); 
            return $this;
        } 
		catch (Exception $e) 
		{
            throw XN_Exception::reformat("Failed to save content:\n" , $e);
        }
    }     
    public static function delete($content,$tag = null,$datatype=0) 
	{
    	if (is_array($content) && count($content) > 100) 
    	{
			$subcontents = 	array_chunk($content,100);    			
			foreach($subcontents as $subcontent_info)
			{
				self::_deleteProper($subcontent_info,false,$tag,$datatype);
			}
    	}
    	else 
    	{
    		self::_deleteProper($content, false,$tag,$datatype); 
    	}
    } 	
    protected static function _deleteProper($content, $cascade = false,$tag = null,$datatype=0) 
	{
        if (is_null($content)) { return; } 
        $ids = array();
        if (is_array($content) && count($content) == 0) return;
        if (! is_array($content)) { $content = array($content); } 
         
        foreach ($content as $c) 
		{
            if (is_object($c)) 
			{
                $ids[] = $c->id;
				if ($c->datatype() != 0 )
				{
					$datatype = $c->datatype();
				}
            } 
			else 
			{
                $ids[] = $c;
            }
        }
		if (is_object($content))
		{
			if ($content->datatype() != 0 )
			{
				$datatype = $content->datatype();
			}
		}
		 
        $qs = $cascade ? '?cascade=true' : '';
        try {
        	 if ($datatype == 0)
        	 {
				 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/content(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/content(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
        	 }
             else if ($datatype == 1)
             {
             	 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/bigcontent(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/bigcontent(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
             }
        	 else if ($datatype == 2)
             {
             	 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/mq(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/mq(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
             }
			 else if ($datatype == 4)
             {
             	 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/maincontent(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/maincontent(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
             }
			 else if ($datatype == 6)
             {
             	 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/message(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/message(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
             }
			 else if ($datatype == 7)
             {
             	 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/yearcontent(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/yearcontent(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
             }			
			 else if ($datatype == 9)
             {
             	 if ( $tag == '')
					{
						XN_REST::delete(XN_REST::urlsprintf('/yearmonthcontent(%s)','id in ['.implode(',',$ids).']').$qs);
					}
					else 
					{
			        	$headers = array('tag' => $tag);
			            XN_REST::delete(XN_REST::urlsprintf('/yearmonthcontent(%s)','id in ['.implode(',',$ids).']').$qs,$headers);
					} 
             }
        	 else {
				 throw XN_Exception::reformat("Failed to delete content: id = '".implode(',',$ids)."'");
        	 }		  
        } 
		catch (Exception $e) 
		{
            throw XN_Exception::reformat("Failed to delete content: id = '".implode(',',$ids)."'", $e);
        } 
    }    
    public function debugString() 
	{
        $str =  "<br>XN_Content:<br>";
        $str .= "\t\tid [".$this->id."]<br>";
        $str .= "\t\tpublished [".$this->published."]<br>";
        $str .= "\t\tupdated [".$this->updated."]<br>";
        $str .= "\t\ttype [".$this->type."]<br>";
		$str .= "\t\tauthor [".$this->author."]<br>"; 
        $str .= "\t\ttitle [".$this->title."]<br>"; 
	    foreach ($this->my->attribute() as $na) 
		{
            if (is_array($na))
			{
                foreach ($na as $na1) 
				{
                    if (isset($na1))
					{
                        $str .= "\t\t\t\t\t\t".$na1->name." => ".$na1->value."<br>";
                    }
                }
            }
            else if (isset($na))
			{
                $str .= "\t\t\t\t".$na->name." => ".$na->value."<br>";
            }
        } 
        return $str;        
    }      
    public function __get($prop) 
	{
        if (strpos($prop, 'my:') === 0) 
		{
            if (($prop != 'my:*') && XN_Attribute::_isClownString(substr($prop,3))) 
			{
                throw new XN_Exception("Illegal attribute name: my." . substr($prop,3));
            }
            $nodes = $this->_xquery($prop);
            if ($nodes->length == 0) 
			{
                return null;
            }
            $allValues = array();
            foreach ($nodes as $node) 
			{
                $nodeType = $node->getAttributeNS(XN_AtomHelper::NS_XN, 'type');
                $multivalues = $this->_x->query('xn:value', $node);
                if ($multivalues->length == 0) 
				{
                    $value = $node->textContent;
                    if (isset($this->_bc_unsavedTypes[$prop])) 
					{
                        settype($value, $this->_bc_unsavedTypes[$prop]);
                    }
                    else 
					{
                        $value = self::_valueFromAtom($value, $nodeType);
                    }
                    $allValues[$node->localName] = ($nodes->length == 1) ? $value : array($value);
                }                
                elseif ($multivalues->length == 1) 
				{
                    $value = $multivalues->item(0)->textContent;
                    if (isset($this->_bc_unsavedTypes[$prop])) 
					{
                        settype($value, $this->_bc_unsavedTypes[$prop]);
                    }
                    else 
					{
                        $value = self::_valueFromAtom($value, $nodeType);
                    }
                    $allValues[$node->localName] = ($nodes->length == 1) ? $value :  array($value);
                }
                else 
				{
                    $values = array();
                    foreach ($multivalues as $multivalue) 
					{
                        $value = $multivalue->textContent;
                        if (isset($this->_bc_unsavedTypes[$prop])) 
						{
                            settype($value, $this->_bc_unsavedTypes[$prop]);
						}
                        else 
						{
                            $value = self::_valueFromAtom($value, $nodeType);
                        }
                        $values[] = $value;
                    }
                    $allValues[$node->localName] = $values;
                }
            }
            return ($nodes->length == 1) ? $allValues[$nodes->item(0)->localName] : $allValues;
	    } 
        else 
		{
            if (isset(self::$_systemAttributes[$prop])) 
			{				
				if (isset(self::$_systemAttributes[$prop]['callback'])) 
				{
				  if ((! isset(self::$_systemAttributes[$prop]['read'])) || (self::$_systemAttributes[$prop]['read'] === true)) 
				  {
				    	$value = call_user_func(array($this,self::$_systemAttributes[$prop]['callback']), 'read', $prop);
				  } 
				  else 
				  {
				    	$value = null;
				  }
				}
				elseif (isset(self::$_systemAttributes[$prop]['xpath'])) 
				{
				    $value = $this->_x->textContent(self::$_systemAttributes[$prop]['xpath'], $this->_node, true);
				}
				elseif (isset(self::$_systemAttributes[$prop]['constant'])) 
				{
				    $value = self::$_systemAttributes[$prop]['constant'];
				}        
				if (isset(self::$_systemAttributes[$prop]['convert'])) 
				{
				    switch (self::$_systemAttributes[$prop]['convert']) 
					{
					    case 'boolean':
								$value = ($value == 'true') ? true : false;
					    		break;
		                case 'anonymous':
		                        $value = ($value == XN_AtomHelper::XN_ANONYMOUS) ? null : $value;
				    	default:
							    break;
				    }
				}
				return $value;
            } 
			else 
			{
                throw new XN_IllegalArgumentException("Unknown property name: '".$prop."'");
            }
        }
    }    
    public function __set($prop, $value) 
	{
		$_bc_array_of_numbers_check = false;
		if (strpos($prop, 'my:') === 0) 
		{
		    if (is_array($value)) 
			{
				$type = 'string';
				$_bc_array_of_numbers_check = true;
		    } 
			else 
			{
				$type = (is_float($value) || is_int($value)) ? 'number' : 'string';
                if (is_bool($value)) 
				{
                    $_bc_array_of_numbers_check = true;
                }
		    }
		}
	    else 
		{
		    $type ='string';
		}
		return $this->_setWithType($prop, $value, $type, true, $_bc_array_of_numbers_check);
    }     
    protected function _setWithType($name, $value, $type, $overwrite = false, $_bc_array_of_numbers_check = false) 
	{
        $nameToCheck = (strncmp('my:', $name, 3) == 0) ? substr($name, 3) : $name;
        if (XN_Attribute::_isClownString($nameToCheck)) 
		{
            throw new XN_Exception("Invalid attribute name: $nameToCheck. Only letters, digits, and _ allowed.");
        } 
		if ($type == XN_Attribute::CONTENT) 
		{
		    $type = XN_Attribute::REFERENCE;
		}
        else if ($type == XN_Attribute::URL) 
		{
            $type = XN_Attribute::STRING;
        } 
		if (! array_key_exists($name, $this->_valuesAtLoad)) 
		{
			  $x = $this->$name;
			  $this->_valuesAtLoad[$name] = $x;
		}
  
        if (strpos($name, 'my:') === 0) 
		{
            if (is_null($value)) 
			{
                return $this->remove($name);
            }
		    if ($overwrite) 
			{
				$nodes = $this->_xquery($name);
				foreach ($nodes as $node) 
				{
				    $node->parentNode->removeChild($node);
				}
				$nodeToUse = null;
		    }
		    else 
			{
				$nodes = $this->_xquery($name);
				if ($nodes->length == 1) 
				{
				    $valueNodes = $this->_xquery("$name/xn:value");
				    if ($valueNodes->length == 0) 
					{
						$existingValue = $nodes->item(0)->textContent;
						foreach ($nodes->item(0)->childNodes as $childNode) 
						{
						    $nodes->item(0)->removeChild($childNode);
						}
						$nodes->item(0)->appendChild($this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_XN,'xn:value', self::_valueToAtom($existingValue, $type)));
				    }
				}
				$nodeToUse = $nodes->item(0);
				if (! is_array($value)) 
				{
				    $value = array($value);
				}
		    }
            
	        if ($overwrite && is_array($value)) 
			{
	            if (count($value) == 1) 
				{
	                $value = reset($value);
	            }
	            else if (count($value) == 0) 
				{
	                $value = null;
	            }
	        }

	        if (is_array($value)) 
			{
				if (($type == 'number') || $_bc_array_of_numbers_check) 
				{
						    $this->_bc_unsavedTypes[$name] = gettype($value[0]);
				}
				if (! $nodeToUse) 
				{
				    $nodeToUse = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_APP(XN_Application::load()->relativeUrl),$name);
				    $nodeToUse->setAttributeNS(XN_AtomHelper::NS_XN, 'xn:type', $type);				    
				    $this->_node->appendChild($nodeToUse);
				}
				foreach ($value as $v) 
				{
				    $valueNode = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_XN, 'xn:value', self::_valueToAtom($v, $type));
				    $nodeToUse->appendChild($valueNode);
				}
	       } 
		   else 
		   {
	            if ($type == 'number' || $_bc_array_of_numbers_check) 
				{
	                $this->_bc_unsavedTypes[$name] = gettype($value);
	            } 
	            $valueToSet = self::_valueToAtom($value, $type);
				$node = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_APP(XN_Application::load()->relativeUrl),$name, $valueToSet);
			
				$node->setAttributeNS(XN_AtomHelper::NS_XN, 'xn:type', $type);
				
		     	foreach ($this->_node->childNodes as $child) 
				{
		             if ($child->nodeName == $node->nodeName)
					 {
					 	 $this->_node->removeChild($child);
					 } 
		        }
	            $this->_node->appendChild($node);
	        }
        }
        else 
		{
            if (isset(self::$_systemAttributes[$name])) 
			{
                if (isset(self::$_systemAttributes[$name]['write']) && self::$_systemAttributes[$name]['write']) 
				{
		   		 	$createdNode = false;
				    if (isset(self::$_systemAttributes[$name]['callback'])) 
					{
						$node = call_user_func(array($this,self::$_systemAttributes[$name]['callback']), 'write', $name, $value, $type, $overwrite);
				    } 
					else 
					{
						$nodeList = $this->_xquery(self::$_systemAttributes[$name]['xpath']);
						if (isset(self::$_systemAttributes[$name]['convert'])) 
						{
						    switch (self::$_systemAttributes[$name]['convert']) 
							{
							    case 'boolean':
				                      $type = XN_Attribute::BOOLEAN;
								break;
							    default:
								break;
						    }
						}
						if ($nodeList->length == 0) 
						{ 
                            if (! is_null($value)) 
							{
                                if ($name == 'title') 
								{
                                    $node = $this->_node->ownerDocument->createElementNS(XN_AtomHelper::NS_ATOM,'atom:title', self::_valueToAtom($value));
                                } 
								else 
								{
                                    throw new XN_IllegalArgumentException("Unknown missing system attribute: $name");
                                }
                                $this->_node->appendChild($node);
                            }
						    $createdNode = true;
						} 
						else 
						{
						    $node = $nodeList->item(0);
						}
						if (! $createdNode) 
						{
                            self::_setNodeValue($node, $value, $type);
                        }
		    		}
                } 
				else 
				{
                    throw new XN_IllegalArgumentException("Attempted to set read-only system property '".$name."'");
                }
            } 
			else 
			{
                throw new XN_IllegalArgumentException("Attempted to set unknown system property '".$name."'");
            }
        }
		return $this;
    }  
    public function attribute($name = null, $returnArray = false) 
	{
        if (is_null($name)||($name == 'my:')) { $returnArray = true; } 
        return $this->_transformNode($name, array($this, '_attributeTransformer'), $returnArray);
    }
    protected function _attributeTransformer($nodes, $returnArray) 
	{
		$ar = array();
		foreach ($nodes as $i => $node) 
		{
		    $attr =  XN_Attribute::createFromNode($this, $node);
	            if ((! $returnArray) && is_array($attr) && (count($attr) == 1)) 
				{
	                $ar[$attr[0]->name] = $attr[0];
	            } 
				else 
				{
	                $ar[$attr[0]->name] = $attr;
	            }
		}
		return $ar;
    }
    protected function _transformNode($name, $callback, $returnArray) 
	{
        if ((! is_callable($callback, false, $callableName)) && (! is_null($callback))) 
		{
            throw new XN_IllegalArgumentException("The callback function '$callableName' isn't callable.");
        } 
		if ($name == 'my:') { $name = 'my:*'; }
	    else if (is_null($name)) { $name = self::_getXpathForSystemAttributes(); }        
		$nodes = $this->_xquery($name);
		if (! is_null($callback)) 
		{
		    $nodes = call_user_func($callback, $nodes, $returnArray);
		}
	    if (is_array($nodes)) 
	    {
	        $ar = $nodes;
	    } 
	    else 
	    {
            $ar = array();
            foreach ($nodes as $node) 
			{
                $ar[] = $node;
            }
	    }
		if ($returnArray) 
		{
		    return $ar;
		} 
		else 
		{
            if (count($ar) > 1) 
			{
                return $ar;
            } 
			else 
			{
                $first = reset($ar);
                return $first;
            }
		}
    }	
    protected static function _getXpathForSystemAttributes() 
	{
        if (! strlen(self::$_xpathForSystemAttributes)) 
		{
            $qnames = array();
            foreach (self::$_systemAttributes as $attr => $info) 
			{
                if (isset($info['xpath']) && ((! isset($info['read'])) || ($info['read'] === true))) 
				{
                    $qnames[] = $info['xpath'];
                }
            }
            self::$_xpathForSystemAttributes = implode('|', $qnames);
        }
        return self::$_xpathForSystemAttributes;
    }
    public function add($name, $value, $type = XN_Attribute::STRING) 
	{
		if (! is_null($value)) 
		{ 
		    $value = is_array($value) ? $value : array($value);
		    foreach ($value as $v) 
			{
				$this->_setWithType($name, $v, $type, false);
		    }
		}
		return $this;
    }   
    public function set($name, $value = null, $type = XN_Attribute::STRING) 
	{
		if (($type == XN_Attribute::CONTENT) && is_object($value)) 
		{
		    $this->setContent($name, $value);
		} 
		else 
		{
		    $this->_setWithType($name, $value, $type, true);
		}
		return $this;
    }
    public function remove($name) 
	{
        if (strncmp($name,'my:', 3) != 0) 
		{
            throw new XN_IllegalArgumentException("Can't remove attribute $name");
        }
        if (XN_Attribute::_isClownString(substr($name, 3))) 
		{
            throw new XN_IllegalArgumentException("Illegal attribute nme for remove: $name");
        }
		if ($name == 'my:') 
		{
		    $expr = 'my:*';
		} 
		else 
		{
		    $expr = $name;
		}
		foreach ($this->_xquery($expr) as $node) 
		{
		    $node->parentNode->removeChild($node);
		}
		return $this;
    }
    public function _getId() { return $this->id; }
    public function _loadCached($id) 
	{
		$c = XN_Cache::_get($id, 'XN_Content');
		if (! $c) 
		{
		    $c = self::load($id);
		}
		return $c;
    }
    protected static function _idFromAtomEntry(XN_XPathHelper $x, DomNode $node) 
	{
		$xn_id = $x->textContent('xn:id', $node, true);
        if (strlen($xn_id)) 
		{
            return $xn_id;
        }
        $id = $x->textContent('atom:id', $node, true);
        if (strlen($id)) 
		{
            return ((integer) $id);
        }
        return null;
    } 
    protected function _lazyLoadCallback($op, $name) 
	{
		if ($op != 'read') 
		{
		    throw new XN_Exception("_lazyLoadCallback must only be called for reads");
		}
		if (array_key_exists($name, $this->_lazyLoadedData)) 
		{
		    return $this->_lazyLoadedData[$name];
		}
		switch ($name) 
		{
	        case 'id':
	            $this->_lazyLoadedData[$name] = self::_idFromAtomEntry($this->_x,$this->_node);
	            break;
			case 'contributor':
			    $this->_lazyLoadedData[$name] = XN_Profile::_get($this->author);
			    break;
			case 'owner':
			    $this->_lazyLoadedData[$name] = XN_Application::load($this->ownerUrl);
			    break;
			case 'ownerName':
			    $this->_lazyLoadedData[$name] = $this->owner->name;
			    break;
			default:
			    throw new XN_IllegalArgumentException("Unknown lazy-load property: $name");
		}
		return $this->_lazyLoadedData[$name];
    } 
    protected static function _setNodeValue($node, $value, $type) 
	{
        foreach ($node->childNodes as $child) 
		{
            $node->removeChild($child);
        } 
        $node->appendChild($node->ownerDocument->createTextNode(self::_valueToAtom($value, $type, false)));
        return $node;
    }
    protected function _registerMyNamespace($ns = null) 
	{
        if (! $this->_x->namespacePrefixIsRegistered('my')) 
		{
            if (is_null($ns)) 
			{
                $ns = $this->_node->getAttribute('xmlns:my');
                if ($ns == '') 
				{
                    $app = $this->_x->textContent('xn:application',$this->_node, true);
                    if ($app == '') 
					{
                        $app = XN_Application::load()->relativeUrl;
                    }
                    $ns = XN_AtomHelper::NS_APP($app);
                }
            }
            $this->_x->registerNamespace('my', $ns);
        }
    }	
    protected static function _valueToAtom($value, $type = XN_Attribute::STRING, $encode = true) 
	{
        if ($type == XN_Attribute::STRING) 
		{
            $value = (string) $value;
        }
        if (($type == XN_Attribute::BOOLEAN) && is_bool($value))
		{
            $value = $value ? 'true' : 'false';
        }
        if ($encode) 
		{
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }
    protected static function _valueFromAtom($value, $type) 
	{
        if ($type == XN_Attribute::BOOLEAN) 
		{
            switch ($value) 
			{
            case 'true':
                $value = true;
                break;
            case 'false':
                $value = false;
                break;
            }
        }
        return $value;
    }

}

 
class XN_AttributeContainer {
    protected $___h;
    protected static $___map;
    protected static $___methods = array('attribute' => true,'add' => true, 'set' => true, 'remove' => true,);
    public function __construct($content) 
	{ 
        $h = spl_object_hash($content);
        self::$___map[$h] = $content;
        $this->___h = $h;
    }
    public function __get($p) { return self::$___map[$this->___h]->__get("my:$p"); }
    public function __set($p, $v) { return self::$___map[$this->___h]->__set("my:$p", $v); }
    public function __call($method, $args) 
	{
        if (isset(self::$___methods[strtolower($method)])) 
		{
            $args[0] = 'my:' . $args[0];
            return call_user_func_array(array(self::$___map[$this->___h], $method), $args);
        }
        else
		{
	    	 throw new Exception("@todo: disallowed my-> methods ($method)");
        }
    } 
    public function ___removeFromMap() { unset(self::$___map[$this->___h]); }
}

 
class XN_Attribute {
    const STRING = "string";
    const NUMBER = "number";
    const URL = "url";
    const DATE = "date";
    const FILEIMAGE = "file.image";
    const CONTENT = "xn_content";
    const UPLOADEDFILE = 'xn_uploadedfile';
    const BINARY = 'xn_binary';
    const BOOLEAN = 'boolean';
    const REFERENCE = 'reference';
   
    protected $_id = -1;
    public $name;
    public $value;
    public $type;
    
    protected static $_nodeNameToSystemAttributeMap = 
        array('xn:id' => array('type' => XN_Attribute::STRING,
                               'name' => 'id'),
              'xn:type' => array('type' => XN_Attribute::STRING,
                                 'name' => 'type'),
              'atom:published' => array('type' => XN_Attribute::DATE,
                                        'name' => 'published'),
              'atom:updated' => array('type' => XN_Attribute::DATE,
                                      'name' => 'updated'),
              'atom:title' => array('name' => 'title',
                                    'type' => XN_Attribute::STRING),
                          'atom:name' => array('name' => 'author',
                                   'type' => XN_Attribute::STRING),
              'xn:application' => array('name' => 'ownerUrl',
                                        'type' => XN_Attribute::STRING),
              );

   
    public static function createFromNode($content, DOMNode $node) 
	{
        // If there's more than 1 xn:value child, then this is a multivalued attribute
        $attrs = array();
        $values = array();
        $valueChildren = $node->getElementsByTagNameNS(XN_AtomHelper::NS_XN, 'value');
        if ($valueChildren->length == 0) 
		{
            $values[] = $node->textContent;
        } 
		else 
		{
            foreach ($valueChildren as $valueChild) 
			{
                $values[] = $valueChild->textContent;
            }
        }
        
        if ($node->prefix == 'my') 
		{
            $type = $node->getAttributeNS(XN_AtomHelper::NS_XN, 'type');
            if ($type == XN_Attribute::REFERENCE)
			{
                $type = XN_Attribute::CONTENT;
            }            
            $name = $node->localName;
        }
        else 
		{
            $pfx = $node->prefix ?$node->prefix : 'atom';
            $qname = $pfx. ':' . $node->localName;
            if (isset(self::$_nodeNameToSystemAttributeMap[$qname])) 
			{
                $type = self::$_nodeNameToSystemAttributeMap[$qname]['type'];
                $name = self::$_nodeNameToSystemAttributeMap[$qname]['name'];
            } 
            else 
			{
                throw new XN_Exception("Unknown attribute qname: $qname");
            }

            if ($type == XN_Attribute::BOOLEAN)
			{
                $values[0] = ($values[0] === 'true') ? true : false;
            }
        }

        
        foreach ($values as $value) 
		{
            $attr = new XN_Attribute($name,$value,$type);
            if ($content->id) 
			{
                $attr->_calculateID($content);
            }
            $attrs[] = $attr;
        }
		return $attrs;
    }     
    public function debugString() 
	{
        return "[".$this->_id."] ".$this->name . " : " . $this->value . " : " . $this->type;
    }      
    public function __get($name) 
	{
        if ($name != 'id') 
            throw new XN_IllegalArgumentException("Unknown property [".$name."]");
        return intval($this->_id);
    }
    function __set($name, $value) 
	{
        if ($name == 'id') 
            throw new XN_IllegalArgumentException("Cannot set attribute id");
        else
            throw new XN_IllegalArgumentException(
                "Unknown property [".$name."]");        
    }
    protected function __construct($name, $value, $type = self::STRING, $prop = false)
	{
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        if ($prop) $this->_id = null;
    }    
    function _calculateID(XN_Content $c) 
	{
		static $seq = 0;
		$seq++;
	        $id = $c->id . '-' . $this->name;
		$id .= '-'.$seq;
	        $this->_id = $id;
    } 
    public static function _isClownString($name) 
	{ 
        if (preg_match('@^[a-zA-Z_][a-zA-Z0-9_]+$@u',$name)) { return false; }
        if (preg_match('@[ !\@#\$%\^&\*\(\)[]{};\':"<>,\./\?\\\-=\+]@u',$name)) { return true; }
        return ! preg_match(self::clownRegex, $name);
    }
    const clownRegex = '@^(?:[\x{0041}-\x{005A}\x{0061}-\x{007A}\x{00C0}-\x{00D6}\x{00D8}-\x{00F6}\x{00F8}-\x{00FF}\x{0100}-\x{0131}\x{0134}-\x{013E}\x{0141}-\x{0148}\x{014A}-\x{017E}\x{0180}-\x{01C3}\x{01CD}-\x{01F0}\x{01F4}-\x{01F5}\x{01FA}-\x{0217}\x{0250}-\x{02A8}\x{02BB}-\x{02C1}\x{0386}\x{0388}-\x{038A}\x{038C}\x{038E}-\x{03A1}\x{03A3}-\x{03CE}\x{03D0}-\x{03D6}\x{03DA}\x{03DC}\x{03DE}\x{03E0}\x{03E2}-\x{03F3}\x{0401}-\x{040C}\x{040E}-\x{044F}\x{0451}-\x{045C}\x{045E}-\x{0481}\x{0490}-\x{04C4}\x{04C7}-\x{04C8}\x{04CB}-\x{04CC}\x{04D0}-\x{04EB}\x{04EE}-\x{04F5}\x{04F8}-\x{04F9}\x{0531}-\x{0556}\x{0559}\x{0561}-\x{0586}\x{05D0}-\x{05EA}\x{05F0}-\x{05F2}\x{0621}-\x{063A}\x{0641}-\x{064A}\x{0671}-\x{06B7}\x{06BA}-\x{06BE}\x{06C0}-\x{06CE}\x{06D0}-\x{06D3}\x{06D5}\x{06E5}-\x{06E6}\x{0905}-\x{0939}\x{093D}\x{0958}-\x{0961}\x{0985}-\x{098C}\x{098F}-\x{0990}\x{0993}-\x{09A8}\x{09AA}-\x{09B0}\x{09B2}\x{09B6}-\x{09B9}\x{09DC}-\x{09DD}\x{09DF}-\x{09E1}\x{09F0}-\x{09F1}\x{0A05}-\x{0A0A}\x{0A0F}-\x{0A10}\x{0A13}-\x{0A28}\x{0A2A}-\x{0A30}\x{0A32}-\x{0A33}\x{0A35}-\x{0A36}\x{0A38}-\x{0A39}\x{0A59}-\x{0A5C}\x{0A5E}\x{0A72}-\x{0A74}\x{0A85}-\x{0A8B}\x{0A8D}\x{0A8F}-\x{0A91}\x{0A93}-\x{0AA8}\x{0AAA}-\x{0AB0}\x{0AB2}-\x{0AB3}\x{0AB5}-\x{0AB9}\x{0ABD}\x{0AE0}\x{0B05}-\x{0B0C}\x{0B0F}-\x{0B10}\x{0B13}-\x{0B28}\x{0B2A}-\x{0B30}\x{0B32}-\x{0B33}\x{0B36}-\x{0B39}\x{0B3D}\x{0B5C}-\x{0B5D}\x{0B5F}-\x{0B61}\x{0B85}-\x{0B8A}\x{0B8E}-\x{0B90}\x{0B92}-\x{0B95}\x{0B99}-\x{0B9A}\x{0B9C}\x{0B9E}-\x{0B9F}\x{0BA3}-\x{0BA4}\x{0BA8}-\x{0BAA}\x{0BAE}-\x{0BB5}\x{0BB7}-\x{0BB9}\x{0C05}-\x{0C0C}\x{0C0E}-\x{0C10}\x{0C12}-\x{0C28}\x{0C2A}-\x{0C33}\x{0C35}-\x{0C39}\x{0C60}-\x{0C61}\x{0C85}-\x{0C8C}\x{0C8E}-\x{0C90}\x{0C92}-\x{0CA8}\x{0CAA}-\x{0CB3}\x{0CB5}-\x{0CB9}\x{0CDE}\x{0CE0}-\x{0CE1}\x{0D05}-\x{0D0C}\x{0D0E}-\x{0D10}\x{0D12}-\x{0D28}\x{0D2A}-\x{0D39}\x{0D60}-\x{0D61}\x{0E01}-\x{0E2E}\x{0E30}\x{0E32}-\x{0E33}\x{0E40}-\x{0E45}\x{0E81}-\x{0E82}\x{0E84}\x{0E87}-\x{0E88}\x{0E8A}\x{0E8D}\x{0E94}-\x{0E97}\x{0E99}-\x{0E9F}\x{0EA1}-\x{0EA3}\x{0EA5}\x{0EA7}\x{0EAA}-\x{0EAB}\x{0EAD}-\x{0EAE}\x{0EB0}\x{0EB2}-\x{0EB3}\x{0EBD}\x{0EC0}-\x{0EC4}\x{0F40}-\x{0F47}\x{0F49}-\x{0F69}\x{10A0}-\x{10C5}\x{10D0}-\x{10F6}\x{1100}\x{1102}-\x{1103}\x{1105}-\x{1107}\x{1109}\x{110B}-\x{110C}\x{110E}-\x{1112}\x{113C}\x{113E}\x{1140}\x{114C}\x{114E}\x{1150}\x{1154}-\x{1155}\x{1159}\x{115F}-\x{1161}\x{1163}\x{1165}\x{1167}\x{1169}\x{116D}-\x{116E}\x{1172}-\x{1173}\x{1175}\x{119E}\x{11A8}\x{11AB}\x{11AE}-\x{11AF}\x{11B7}-\x{11B8}\x{11BA}\x{11BC}-\x{11C2}\x{11EB}\x{11F0}\x{11F9}\x{1E00}-\x{1E9B}\x{1EA0}-\x{1EF9}\x{1F00}-\x{1F15}\x{1F18}-\x{1F1D}\x{1F20}-\x{1F45}\x{1F48}-\x{1F4D}\x{1F50}-\x{1F57}\x{1F59}\x{1F5B}\x{1F5D}\x{1F5F}-\x{1F7D}\x{1F80}-\x{1FB4}\x{1FB6}-\x{1FBC}\x{1FBE}\x{1FC2}-\x{1FC4}\x{1FC6}-\x{1FCC}\x{1FD0}-\x{1FD3}\x{1FD6}-\x{1FDB}\x{1FE0}-\x{1FEC}\x{1FF2}-\x{1FF4}\x{1FF6}-\x{1FFC}\x{2126}\x{212A}-\x{212B}\x{212E}\x{2180}-\x{2182}\x{3041}-\x{3094}\x{30A1}-\x{30FA}\x{3105}-\x{312C}\x{AC00}-\x{D7A3}]|[\x{4E00}-\x{9FA5}\x{3007}\x{3021}-\x{3029}]|_)(?:[\x{0041}-\x{005A}\x{0061}-\x{007A}\x{00C0}-\x{00D6}\x{00D8}-\x{00F6}\x{00F8}-\x{00FF}\x{0100}-\x{0131}\x{0134}-\x{013E}\x{0141}-\x{0148}\x{014A}-\x{017E}\x{0180}-\x{01C3}\x{01CD}-\x{01F0}\x{01F4}-\x{01F5}\x{01FA}-\x{0217}\x{0250}-\x{02A8}\x{02BB}-\x{02C1}\x{0386}\x{0388}-\x{038A}\x{038C}\x{038E}-\x{03A1}\x{03A3}-\x{03CE}\x{03D0}-\x{03D6}\x{03DA}\x{03DC}\x{03DE}\x{03E0}\x{03E2}-\x{03F3}\x{0401}-\x{040C}\x{040E}-\x{044F}\x{0451}-\x{045C}\x{045E}-\x{0481}\x{0490}-\x{04C4}\x{04C7}-\x{04C8}\x{04CB}-\x{04CC}\x{04D0}-\x{04EB}\x{04EE}-\x{04F5}\x{04F8}-\x{04F9}\x{0531}-\x{0556}\x{0559}\x{0561}-\x{0586}\x{05D0}-\x{05EA}\x{05F0}-\x{05F2}\x{0621}-\x{063A}\x{0641}-\x{064A}\x{0671}-\x{06B7}\x{06BA}-\x{06BE}\x{06C0}-\x{06CE}\x{06D0}-\x{06D3}\x{06D5}\x{06E5}-\x{06E6}\x{0905}-\x{0939}\x{093D}\x{0958}-\x{0961}\x{0985}-\x{098C}\x{098F}-\x{0990}\x{0993}-\x{09A8}\x{09AA}-\x{09B0}\x{09B2}\x{09B6}-\x{09B9}\x{09DC}-\x{09DD}\x{09DF}-\x{09E1}\x{09F0}-\x{09F1}\x{0A05}-\x{0A0A}\x{0A0F}-\x{0A10}\x{0A13}-\x{0A28}\x{0A2A}-\x{0A30}\x{0A32}-\x{0A33}\x{0A35}-\x{0A36}\x{0A38}-\x{0A39}\x{0A59}-\x{0A5C}\x{0A5E}\x{0A72}-\x{0A74}\x{0A85}-\x{0A8B}\x{0A8D}\x{0A8F}-\x{0A91}\x{0A93}-\x{0AA8}\x{0AAA}-\x{0AB0}\x{0AB2}-\x{0AB3}\x{0AB5}-\x{0AB9}\x{0ABD}\x{0AE0}\x{0B05}-\x{0B0C}\x{0B0F}-\x{0B10}\x{0B13}-\x{0B28}\x{0B2A}-\x{0B30}\x{0B32}-\x{0B33}\x{0B36}-\x{0B39}\x{0B3D}\x{0B5C}-\x{0B5D}\x{0B5F}-\x{0B61}\x{0B85}-\x{0B8A}\x{0B8E}-\x{0B90}\x{0B92}-\x{0B95}\x{0B99}-\x{0B9A}\x{0B9C}\x{0B9E}-\x{0B9F}\x{0BA3}-\x{0BA4}\x{0BA8}-\x{0BAA}\x{0BAE}-\x{0BB5}\x{0BB7}-\x{0BB9}\x{0C05}-\x{0C0C}\x{0C0E}-\x{0C10}\x{0C12}-\x{0C28}\x{0C2A}-\x{0C33}\x{0C35}-\x{0C39}\x{0C60}-\x{0C61}\x{0C85}-\x{0C8C}\x{0C8E}-\x{0C90}\x{0C92}-\x{0CA8}\x{0CAA}-\x{0CB3}\x{0CB5}-\x{0CB9}\x{0CDE}\x{0CE0}-\x{0CE1}\x{0D05}-\x{0D0C}\x{0D0E}-\x{0D10}\x{0D12}-\x{0D28}\x{0D2A}-\x{0D39}\x{0D60}-\x{0D61}\x{0E01}-\x{0E2E}\x{0E30}\x{0E32}-\x{0E33}\x{0E40}-\x{0E45}\x{0E81}-\x{0E82}\x{0E84}\x{0E87}-\x{0E88}\x{0E8A}\x{0E8D}\x{0E94}-\x{0E97}\x{0E99}-\x{0E9F}\x{0EA1}-\x{0EA3}\x{0EA5}\x{0EA7}\x{0EAA}-\x{0EAB}\x{0EAD}-\x{0EAE}\x{0EB0}\x{0EB2}-\x{0EB3}\x{0EBD}\x{0EC0}-\x{0EC4}\x{0F40}-\x{0F47}\x{0F49}-\x{0F69}\x{10A0}-\x{10C5}\x{10D0}-\x{10F6}\x{1100}\x{1102}-\x{1103}\x{1105}-\x{1107}\x{1109}\x{110B}-\x{110C}\x{110E}-\x{1112}\x{113C}\x{113E}\x{1140}\x{114C}\x{114E}\x{1150}\x{1154}-\x{1155}\x{1159}\x{115F}-\x{1161}\x{1163}\x{1165}\x{1167}\x{1169}\x{116D}-\x{116E}\x{1172}-\x{1173}\x{1175}\x{119E}\x{11A8}\x{11AB}\x{11AE}-\x{11AF}\x{11B7}-\x{11B8}\x{11BA}\x{11BC}-\x{11C2}\x{11EB}\x{11F0}\x{11F9}\x{1E00}-\x{1E9B}\x{1EA0}-\x{1EF9}\x{1F00}-\x{1F15}\x{1F18}-\x{1F1D}\x{1F20}-\x{1F45}\x{1F48}-\x{1F4D}\x{1F50}-\x{1F57}\x{1F59}\x{1F5B}\x{1F5D}\x{1F5F}-\x{1F7D}\x{1F80}-\x{1FB4}\x{1FB6}-\x{1FBC}\x{1FBE}\x{1FC2}-\x{1FC4}\x{1FC6}-\x{1FCC}\x{1FD0}-\x{1FD3}\x{1FD6}-\x{1FDB}\x{1FE0}-\x{1FEC}\x{1FF2}-\x{1FF4}\x{1FF6}-\x{1FFC}\x{2126}\x{212A}-\x{212B}\x{212E}\x{2180}-\x{2182}\x{3041}-\x{3094}\x{30A1}-\x{30FA}\x{3105}-\x{312C}\x{AC00}-\x{D7A3}]|[\x{4E00}-\x{9FA5}\x{3007}\x{3021}-\x{3029}]|[\x{0030}-\x{0039}\x{0660}-\x{0669}\x{06F0}-\x{06F9}\x{0966}-\x{096F}\x{09E6}-\x{09EF}\x{0A66}-\x{0A6F}\x{0AE6}-\x{0AEF}\x{0B66}-\x{0B6F}\x{0BE7}-\x{0BEF}\x{0C66}-\x{0C6F}\x{0CE6}-\x{0CEF}\x{0D66}-\x{0D6F}\x{0E50}-\x{0E59}\x{0ED0}-\x{0ED9}\x{0F20}-\x{0F29}]|[\x{0300}-\x{0345}\x{0360}-\x{0361}\x{0483}-\x{0486}\x{0591}-\x{05A1}\x{05A3}-\x{05B9}\x{05BB}-\x{05BD}\x{05BF}\x{05C1}-\x{05C2}\x{05C4}\x{064B}-\x{0652}\x{0670}\x{06D6}-\x{06DC}\x{06DD}-\x{06DF}\x{06E0}-\x{06E4}\x{06E7}-\x{06E8}\x{06EA}-\x{06ED}\x{0901}-\x{0903}\x{093C}\x{093E}-\x{094C}\x{094D}\x{0951}-\x{0954}\x{0962}-\x{0963}\x{0981}-\x{0983}\x{09BC}\x{09BE}\x{09BF}\x{09C0}-\x{09C4}\x{09C7}-\x{09C8}\x{09CB}-\x{09CD}\x{09D7}\x{09E2}-\x{09E3}\x{0A02}\x{0A3C}\x{0A3E}\x{0A3F}\x{0A40}-\x{0A42}\x{0A47}-\x{0A48}\x{0A4B}-\x{0A4D}\x{0A70}-\x{0A71}\x{0A81}-\x{0A83}\x{0ABC}\x{0ABE}-\x{0AC5}\x{0AC7}-\x{0AC9}\x{0ACB}-\x{0ACD}\x{0B01}-\x{0B03}\x{0B3C}\x{0B3E}-\x{0B43}\x{0B47}-\x{0B48}\x{0B4B}-\x{0B4D}\x{0B56}-\x{0B57}\x{0B82}-\x{0B83}\x{0BBE}-\x{0BC2}\x{0BC6}-\x{0BC8}\x{0BCA}-\x{0BCD}\x{0BD7}\x{0C01}-\x{0C03}\x{0C3E}-\x{0C44}\x{0C46}-\x{0C48}\x{0C4A}-\x{0C4D}\x{0C55}-\x{0C56}\x{0C82}-\x{0C83}\x{0CBE}-\x{0CC4}\x{0CC6}-\x{0CC8}\x{0CCA}-\x{0CCD}\x{0CD5}-\x{0CD6}\x{0D02}-\x{0D03}\x{0D3E}-\x{0D43}\x{0D46}-\x{0D48}\x{0D4A}-\x{0D4D}\x{0D57}\x{0E31}\x{0E34}-\x{0E3A}\x{0E47}-\x{0E4E}\x{0EB1}\x{0EB4}-\x{0EB9}\x{0EBB}-\x{0EBC}\x{0EC8}-\x{0ECD}\x{0F18}-\x{0F19}\x{0F35}|\x{0F37}\x{0F39}\x{0F3E}\x{0F3F}\x{0F71}-\x{0F84}\x{0F86}-\x{0F8B}\x{0F90}-\x{0F95}\x{0F97}\x{0F99}-\x{0FAD}\x{0FB1}-\x{0FB7}\x{0FB9}\x{20D0}-\x{20DC}\x{20E1}\x{302A}-\x{302F}\x{3099}\x{309A}]|[\x{00B7}\x{02D0}\x{02D1}\x{0387}\x{0640}\x{0E46}\x{0EC6}\x{3005}\x{3031}-\x{3035}\x{309D}-\x{309E}\x{30FC}-\x{30FE}]|_)*$@u';
    
}

} /* class-exists */