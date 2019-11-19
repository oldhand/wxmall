<?php
 
if (! class_exists('XN_Cache')) 
{ 
	class XN_Cache { 
	    private static $cache = array(); 
	    static function _put($object){
	        self::$cache[self::_keyFromObject($object)] = $object;
	    } 
	    static function _remove($object){
	        unset(self::$cache[self::_keyFromObject($object)]);
	    } 
	    static function _get($id, $classname){
	        $key = self::_keyFromIdAndClassName($id, $classname);
	        return array_key_exists($key, self::$cache) ?
	            self::$cache[$key] : null;
	    }     
	    private static function _keyFromObject($object){
	        return self::_keyFromIdAndClassName($object->_getId(),self::_toClassName($object));
	    }    
	    private static function _keyFromIdAndClassName($id, $classname){
	        return $classname.$id;
	    }    
	    private static function _toClassName($object){
	        if (!is_object($object))
	            throw new Exception("$object is not an object");
	        else if ($object instanceof XN_Profile)
	            return "XN_Profile";
	        else if ($object instanceof XN_Content)
	            return "XN_Content";
	        else if ($object instanceof XN_Application)
	            return "XN_Application";
	        else if ($object instanceof XN_Tag)
	            return "XN_Tag";
	        else if ($object instanceof XN_Shape)
	            return "XN_Shape";
		      else if ($object instanceof XN_Backup)
	            return "XN_Backup";
	        else
	            throw new Exception("Unsupported class: $object");
	    }  
	} 
}  
