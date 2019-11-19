<?php

class XN_REST {
    protected static $instance;
    protected static $memMultipliers = array('k' => 1024, 'm' => 1048576, 'g' => 1073741824);
    protected static $responseSizeMemoryRatio = 0.9;
    protected $maxResponseSize = null;
    const STATUS = 'xn:status'; 
    const USE_REQUEST_TIMEOUT = 'xn-request-timeout';

    protected $body;
    protected $headers;
    protected $responseCode;
    protected $url;
    protected $bodyTooBig = false;

    const DEBUG = false;
    protected static $RESPONSE_FROM_HEADER = '';
    public static $SECURITY_TOKEN = null;
    public static $LOCAL_API_HOST_PORT = null;
    public static $APPCORE_IP = null;
    public static $TRACE = null;
    protected static $GLOBAL_API_HOST_PORT = null; 
    protected static $requestTimeout = 90;
	
    public static function setRequestTimeout($t) 
	{
        self::$requestTimeout = $t;
        $instance = self::getInstance();
        foreach ($instance->curl as $k => $curl) 
		{
            if (! is_null($curl)) 
			{
                curl_setopt($instance->curl[$k], CURLOPT_TIMEOUT, $t);
            }
        }
    } 
    public static function getRequestTimeout() { return self::$requestTimeout; }
    protected $curl = array();
    protected static function getInstance() 
	{
        if (is_null(self::$instance)) { self::$instance = new XN_REST; }
        return self::$instance;
    }
    protected static function setInstance($instance) 
	{
        self::$instance = $instance;
    }
    public static function urlsprintf() 
	{
          $args = func_get_args();
          return self::mapsprintf('urlsprintf','rawurlencode',$args);
    }
    public static function xmlsprintf() 
	{
          $args = func_get_args();
          return self::mapsprintf('xmlsprintf',array('XN_REST','utf8specialchars'),$args);
    }
    protected static function mapsprintf($caller, $callback, $args) 
	{
        if (count($args) >= 2) {
            $format = array_shift($args);
            return vsprintf($format, array_map($callback,$args));
        } else if (count($args) == 1) {
            return $args[0];
        } else {
            throw new XN_Exception("$caller expects at least 1 argument");
        }
    }
    public static function utf8specialchars($s) { return htmlspecialchars($s, ENT_COMPAT, 'UTF-8'); }
    public static function singleQuote($s) 
	{
        $s = str_replace('\\','\\\\',$s);
        $s = str_replace("'","\\'",$s);
        return $s;
    }
    protected function __construct() 
	{
        if (strlen($t = trim(ini_get('memory_limit')))) {
            if (isset(self::$memMultipliers[$c=strtolower(substr($t,-1))])) { $t *= self::$memMultipliers[$c]; }
            $this->maxResponseSize = $t * self::$responseSizeMemoryRatio;
        }
    }
    protected function _initializeCurl($slot) 
	{
        $this->curl[$slot] = curl_init();
        curl_setopt($this->curl[$slot], CURLOPT_HEADERFUNCTION, array($this,'parseHeader'));
        curl_setopt($this->curl[$slot], CURLOPT_WRITEFUNCTION, array($this, 'parseBody'));
        curl_setopt($this->curl[$slot], CURLOPT_USERAGENT, 'XN-REST 0.2');
        curl_setopt($this->curl[$slot], CURLOPT_VERBOSE, self::DEBUG);
        curl_setopt($this->curl[$slot], CURLOPT_TIMEOUT, self::getRequestTimeout());
    } 
    protected static function determineSlot() 
	{
        return self::$LOCAL_API_HOST_PORT; 
    }
    public static function get($url, $headers = null) 
	{
        $ref = null;
        return self::getInstance()->doRequest('GET', $url, $ref, null, $headers);
    }
    public static function post($url, $postData = null, $contentType = 'text/xml', $headers = null) 
	{
        if (is_array($postData)) 
		{
            $tmp = array();
            foreach ($postData as $k => $v) {
                $tmp[] = urlencode($k).'='.urlencode($v);
            }
            $postBody = implode('&',$tmp);
            $contentType = 'application/x-www-form-urlencoded';
        } 
		else 
		{
            $postBody = $postData;
        }
        return self::getInstance()->doRequest('POST', $url, $postBody, $contentType, $headers);
    }
    public static function put($url, $body, $contentType = 'text/xml', $headers = null) 
	{
        return self::getInstance()->doRequest('PUT',$url,$body,$contentType,$headers);
    }
    public static function delete($url, $headers = null) 
	{
        $ref = null;
        return self::getInstance()->doRequest('DELETE',$url, $ref, null, $headers);
    }
    public static function head($url, $headers = null) 
	{
        try {
            $ref = null;
            $instance = self::getInstance();
            $instance->doRequest('HEAD', $url, $ref, null, $headers);
            return $instance->responseCode;
        } catch (XN_Exception $e) {
            return $instance->responseCode;
        }
    }
    protected function parseHeader($curl, $data) 
	{
        if (self::DEBUG) { print self::mem_info(); print "<code>[h] ".xnhtmlentities($data)."</code><br/>\n"; }
        if (is_null($this->responseCode) && preg_match('@^HTTP/1\.\d (\d\d\d)@',$data, $matches)) 
		{
            if ($matches[1] != '100') { 
                $this->responseCode = $matches[1];
            }
        }
        list($header, $value) = explode(': ', $data, 2);
        if (strlen($header = trim($header))) 
		{
            if (($header == 'Content-Length') && (! is_null($this->maxResponseSize)) && ($value > $this->maxResponseSize)) 
			{
                $this->bodyTooBig = true;
            }
            if (is_array($this->headers[$header])) 
			{
                $this->headers[$header][] = trim($value);
            } 
			else if (isset($this->headers[$header])) 
			{
                $this->headers[$header] =array($this->headers[$header], trim($value));
            } 
			else 
			{
                $this->headers[$header] = trim($value);
            }
        }
        return strlen($data);
    }
    protected function parseBody($curl, $data) 
	{
        if (self::DEBUG) { print self::mem_info(); print "<code>[b] (".strlen($data).') '.xnhtmlentities($data)."</code><br/>\n"; }
        if ($this->bodyTooBig) { return -1; }
        $this->body .= $data;
        return strlen($data);
    }
    protected static function mem_info() 
	{
        if (is_callable('memory_get_usage')) {
            return '<code>[m] '.memory_get_usage()."</code><br/>\n";
        }
    }   
    protected static function prepareUrl($url) 
	{
        $host = null;
        $slot = null;
        if (($isRelative = ($url[0] == '/')) || (strncasecmp($thisAppBaseUrl = 'http://'.XN_AtomHelper::HOST_APP(XN_Application::$CURRENT_URL), $url, $thisAppBaseUrlLen = strlen($thisAppBaseUrl)) == 0)) 
		{            
            if ($isRelative) 
			{
                if (strpos($url,'/profile') === 0 &&
                    !preg_match("@^/profile[^/]*/role@",$url)) {
                    $url = XN_AtomHelper::APP_REST_PREFIX() . $url;
                }
                else if (substr($url,0,4) !== '/xn/') 
				{
                    $url = XN_AtomHelper::APP_ATOM_PREFIX . $url.'?xn_out=xml';
                }
                $slot = self::determineSlot();
                $url = 'http://' . $slot . $url;
            }
            else 
			{
                $slot = self::determineSlot();
                $url = 'http://'. $slot . substr($url, $thisAppBaseUrlLen);
            }
            $host = preg_replace('@:\d+$@','',XN_AtomHelper::HOST_APP(XN_Application::$CURRENT_URL));
        } 
        else {
            $slot = '__other__';
        }
        $info = array('url' => $url, 'slot' => $slot);
        if (! is_null($host)) 
		{
            $info['host'] = $host;
        }
        return $info;
    }
    protected function doRequest($method, $url, &$body = null,$contentType=null, $additionalHeaders = null) 
    {
        $requestHeaders = array();   
       
        $urlInfo = self::prepareUrl($url);
         
        $slot = $urlInfo['slot'];
        $url  = $urlInfo['url'];
        
        if (isset($urlInfo['host'])) 
		{
            $requestHeaders['Host'] = $urlInfo['host'];
        } 
		
        $this->_initializeCurl($slot);

        
        $this->headers = array();
        $this->body = '';
        $this->responseCode = null;
        $this->bodyTooBig = false;

        curl_setopt($this->curl[$slot], CURLOPT_HTTPHEADER, array());
        curl_setopt($this->curl[$slot], CURLOPT_URL, $url);

        if ($method == 'POST') 
		{
            curl_setopt($this->curl[$slot], CURLOPT_CUSTOMREQUEST, 'POST');
        } 
		elseif ($method == 'GET') 
		{
            curl_setopt($this->curl[$slot], CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($this->curl[$slot], CURLOPT_HTTPGET, true);
        } 
		else 
		{
            curl_setopt($this->curl[$slot], CURLOPT_HTTPGET, true);
            curl_setopt($this->curl[$slot], CURLOPT_CUSTOMREQUEST, $method);
            if ($method == 'HEAD') 
			{
                $requestHeaders['Connection'] = 'close';
            }
        }
        if (is_array($body) || strlen($body)) 
		{
            curl_setopt($this->curl[$slot], CURLOPT_POST, true);
            curl_setopt($this->curl[$slot], CURLOPT_POSTFIELDS, $body);
        }
        if (strlen($contentType)) 
		{
            if (preg_match("@\+xml$@",$contentType) || (! preg_match("@^(image|audio|video)/@", $contentType))) 
			{
                $requestHeaders['Content-Type'] = "$contentType;charset=UTF-8";
            }
            else 
			{
                $requestHeaders['Content-Type'] = $contentType;
            }
        }

        if (is_array($additionalHeaders)) 
		{
            foreach ($additionalHeaders as $header => $value) 
			{  
                if (is_null($value)) 
				{
                    if (isset($requestHeaders[$header])) 
					{
                        unset($requestHeaders[$header]);
                    }
                } 
				else 
				{
                    $requestHeaders[$header] = $value;
                }
            }

        }
		if (!is_null(XN_Profile::$VIEWER))
	    {
			$requestHeaders['profile'] = XN_Profile::$VIEWER;
	    }
        $combinedRequestHeaders = array();
        foreach ($requestHeaders as $header => $value) 
		{
            $combinedRequestHeaders[] = "$header: $value";
        }
        curl_setopt($this->curl[$slot], CURLOPT_HTTPHEADER, $combinedRequestHeaders);
        curl_setopt($this->curl[$slot], CURLOPT_ENCODING, 'gzip');
        $this->url = $url;         
        if (isset($additionalHeaders[self::USE_REQUEST_TIMEOUT])) 
		{
            curl_setopt($this->curl[$slot], CURLOPT_TIMEOUT, $additionalHeaders[self::USE_REQUEST_TIMEOUT]);
        }         
        $res = @curl_exec($this->curl[$slot]); 
        curl_close($this->curl[$slot]);
        if (isset($additionalHeaders[self::USE_REQUEST_TIMEOUT])) 
		{
            curl_setopt($this->curl[$slot], CURLOPT_TIMEOUT, self::$requestTimeout);
        }      
        if ($res === false) 
		{
            $errno = curl_errno($this->curl[$slot]);
            if ($errno == CURLE_OPERATION_TIMEOUTED) 
			{
                $msg = "Request Timeout: " . self::getRequestTimeout() . " seconds exceeded";
            } 
			else 
			{
                $msg = curl_error($this->curl[$slot]);
            }
            $e = new XN_TimeoutException($msg);
            $apiUrlParts = parse_url($url);
            $e->setLogData(array('api-method' => $method,
                                 'api-host' => isset($apiUrlParts['host']) ? $apiUrlParts['host'] : '-',
                                 'api-url' => $url,
                                 'host' => isset($requestHeaders['Host']) ? $requestHeaders['Host'] : '-',
                                 'pg' => $_SERVER['SERVER_ADDR'],
                                 'method' => $_SERVER['REQUEST_METHOD'],
                                 'url' => $_SERVER['HTTP_X_NING_REQUEST_URI'],
                                 'trace' => self::$TRACE
                                 )
                           );
			$errormsg = "<pre>Uncaught : ".$e->getMessage()."\n".$e->getTraceAsString()."</pre>";
	        header('Content-Type:text/html;charset=utf-8');
			if ($_SERVER["SERVER_ADDR"]== "117.41.237.48" || $_SERVER["SERVER_ADDR"] == "117.41.237.35"  ) 
			{
				$this->errorprint('警告','服务器连接失败!<br><br><div style="text-align: left;padding-left: 30px;"> 江西医流通医疗器械有限责任公司<br>Copyright © 2010-2016 qixieyun.com <br>紧急联系手机：13979158400(王瑞恒)<br></div>');
				
			}
			else 
			{
				$this->errorprint('警告','服务器连接失败!<br><br><div style="text-align: left;padding-left: 30px;"> 湖南赛明威科技有限公司<br>Copyright © 2010-2016 tezan.cn <br>紧急联系手机：15974160308(王真明)<br></div>');
 			} 	    	
			die();			   
            throw $e;
        }
        if (($slot != '__other__') && (($this->responseCode == 400) || ($this->responseCode == 500))) 
		{
			 $errormsg = "Uncaught : responseCode => ".$this->responseCode."";
	         throw new XN_Exception($errormsg);	
        }        
        if (! self::responseCodeIsOk($this->responseCode)) 
		{
            list($errorCode, $errorMessage) = XN_AtomHelper::parseError($this->body);
            throw new XN_Exception($errorMessage, $this->responseCode);
        }        
        if ($this->bodyTooBig) 
		{
            throw new XN_Exception("Response body too big: ".$this->headers['Content-Length']." bytes. Max is ".$this->maxResponseSize);
        }  
        return $this->body;
    }	 
	public static function errorprint($title,$msg)
	{
		   $html = '<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>'.$title.'</title>
		<style type="text/css">
		<!--
		.t {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				color: #CC0000;
		}
		.c {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 12px;
				font-weight: normal;
				color: #000000;
				line-height: 18px;
				text-align: center;
				border: 1px solid #CCCCCC;
				background-color: #FFFFEC;
		}
		body {
				background-color: #FFFFFF;
				margin-top: 100px;
		}
		-->
		</style>
		</head>
		<body>
		<div align="center">
		  <h2><span class="t">'.$title.'</span></h2>
		  <table border="0" cellpadding="8" cellspacing="0" width="460">
			<tbody>
			  <tr>
				<td class="c">'.$msg.'.</td>
			  </tr>
			</tbody>
		  </table>
		</div>
		</body>
		</html>';
		  echo $html;
		   die();
	}
    public static function getLastResponseCode() 
	{
        return self::getInstance()->responseCode;
    }
    public static function getLastResponseHeaders() 
	{
    	return self::getInstance()->headers;
    }
    public static function setLastResponseCookies() 
	{
	    $headers = self::getLastResponseHeaders();
	    if (isset($headers['Set-Cookie'])) 
		{
	        if (is_array($headers['Set-Cookie']))
			{
		        foreach ($headers['Set-Cookie'] as $cookieHeader) 
				{
		            header('Set-Cookie: '. $cookieHeader, false);
		        }
	        }
	        else 
			{
	       	 	header('Set-Cookie: ' . $headers['Set-Cookie']);
	        }
	    }
    }
    protected static function responseCodeisOk($code) 
	{
        return (($code >= 200) && ($code < 300));
    }
    public static function getLastResponseBody() 
	{
   	 	return self::getInstance()->body;
    } 
    public static function parseErrorsFromException(Exception $e) 
	{
        $errorXml = @simplexml_load_string($e->getMessage());
        $errors = array();
        if ($errorXml instanceof SimpleXmlElement) 
		{
            foreach ($errorXml->error as $error) 
			{
                $errors[ (string) $error['code'] ] = (string) $error;
            }
        }
	    if (count($errors) == 0) 
		{
	      	$errors[-1] = 'Unknown Error';
        }
        $errors[XN_REST::STATUS] = $e->getCode();
        return $errors;
    } 
}


