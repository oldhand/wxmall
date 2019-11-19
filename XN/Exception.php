<?php 
if (!class_exists('XN_Exception')) 
{ 
	class XN_Exception extends Exception {
	
	    public $brief = false;
	    public $central = false;
	    protected $logData = array();
	    public function __construct($message, $code = 0){
	        parent::__construct($message, $code);
	    }

	    public static function reformat($prefix, Exception $e, $class = null) {
	        $class = is_null($class) ? 'XN_Exception' : $class;
			if ($e instanceof XN_TimeoutException) {
		            return $e;
			}
	        $msg = "$prefix\n" . $e->getMessage();
	        if (($e instanceof XN_Exception) && (! $e->brief)) {
	            $msg .= "\n" .$e->getTraceAsString(); 
	        }
	        $e2 = new $class($msg);
	        if (($e instanceof XN_Exception) && ($e2 instanceof XN_Exception)) {
	            $e2->brief = $e->brief;
	            $e2->central = $e->central;
	            $e2->setLogData($e->getLogData());
	        }
	        return $e2;
	    }
	    public function setLogData($data) {
	        $this->logData = $data;
	    }
	    public function getLogData() {
	        return $this->logData;
	    }
	}
	class XN_ProgrammingException extends XN_Exception {
	    public function __construct($message){
	        parent::__construct($message);
	    }
	}
	class XN_IllegalStateException extends XN_ProgrammingException {
	    public function __construct($message){
	        parent::__construct($message);
	    }
	}
	class XN_IllegalArgumentException extends XN_ProgrammingException {
	    public function __construct($message){
	        parent::__construct($message);
	    }
	}
	class XN_UnsupportedOperationException extends XN_ProgrammingException {
	    public function __construct($message){
	        parent::__construct($message);
	    }
	}
	class XN_TimeoutException extends XN_Exception {
	    public $brief = false;
	    public $central = true;
	}
	class XN_SerializationException extends XN_Exception {}
	function XN_ExceptionHandler(Exception $ex){
	    $class = new ReflectionClass($ex);
	    print "<pre>Uncaught ".$class->getName().": ".$ex->getMessage()."\n".
	          $ex->getTraceAsString()."</pre>"; 
	}
}
