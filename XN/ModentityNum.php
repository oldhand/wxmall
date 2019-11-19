<?php
 
if (! class_exists('XN_ModentityNum')) 
{ 
	class XN_ModentityNum 
	{    
		public static function get($module) 
		{
			 
					$rsp = XN_REST::get(XN_REST::urlsprintf('/modentitynum(%s)',"module='".$module."'"));
				 
			   	    $xmlObj = simplexml_load_string($rsp, 'SimpleXMLElement', LIBXML_NOCDATA);  
				 
					if ($xmlObj->getName() == "error")
					{
						 throw new XN_Exception(trim($xmlObj));
					}
					else
					{
				   	    $application =  trim($xmlObj->application); 
				   	    $modentitynum =  trim($xmlObj->modentitynum);  
						 
					    return $modentitynum;
					}  
		} 
	} 
}  
