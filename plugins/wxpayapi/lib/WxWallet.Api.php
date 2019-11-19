<?php
	/**
	 * Created by PhpStorm.
	 * User: clubs
	 * Date: 2017/5/14
	 * Time: 下午8:43
	 */
	require_once "WxPay.Config.php";
	require_once "WxPay.Exception.php";

	class WxWalletApi
	{
		/**
		 * 获取用户 的ip
		 * @return ip
		 */
		public static function getRealIp() {
			$ip = "Unknown";

			if (isset($_SERVER["HTTP_X_REAL_IP"]) && !empty($_SERVER["HTTP_X_REAL_IP"])) {
				$ip = $_SERVER["HTTP_X_REAL_IP"];
			}
			elseif (isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) && !empty($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])) {
				$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
			}
			elseif (isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) && !empty($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])) {
				$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
			}
			elseif (isset($HTTP_SERVER_VARS["REMOTE_ADDR"]) && !empty($HTTP_SERVER_VARS["REMOTE_ADDR"])) {
				$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
			}
			elseif (getenv("HTTP_X_FORWARDED_FOR")) {
				$ip = getenv("HTTP_X_FORWARDED_FOR");
			}
			elseif (getenv("HTTP_CLIENT_IP")) {
				$ip = getenv("HTTP_CLIENT_IP");
			}
			elseif (getenv("REMOTE_ADDR")) {
				$ip = getenv("REMOTE_ADDR");
			}

			if( $ip == 'Unknown'){
				throw new WxPayException("获取不到ip地址");
			}
			return $ip;
		}

		/**
		 * 产生随机字符串，不长于32位
		 * @param int $length
		 * @return 产生的随机字符串
		 */
		public static function getNonceStr($length = 32)
		{
			$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
			$str ="";
			for ( $i = 0; $i < $length; $i++ )  {
				$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
			}
			return $str;
		}

		/**
		 * 带证书请求xml 接口
		 * @param   $url     请求的接口
		 * @param   $vars    xml数据
		 * @param   $second  最大超时时间 s
		 * @param   $aHeader 请求头
		 * @return 产生的随机字符串
		 */
		public static function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
		{
			$ch = curl_init();
			//超时时间
			curl_setopt($ch,CURLOPT_TIMEOUT,$second);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
			//如果有配置代理这里就设置代理
			if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
			   && WxPayConfig::CURL_PROXY_PORT != 0){
				curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
				curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
			}
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

			//以下两种方式需选择一种

			//第一种方法，cert 与 key 分别属于两个.pem文件
			//默认格式为PEM，可以注释
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::$SSLCERT_PATH );
			//默认格式为PEM，可以注释
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::$SSLKEY_PATH );

			curl_setopt($ch,CURLOPT_CAINFO, WxPayConfig::$SSLROOTCA_PATH );

			if( count($aHeader) >= 1 ){
				curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
			}

			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
			$data = curl_exec($ch);
			if($data){
				curl_close($ch);
				return $data;

			}
			else {
				$error = curl_errno($ch);
				curl_close($ch);
				throw new WxPayException("curl error,code:$error");
			}
		}

		/**
		 * +---------------------------+
		 * +       生成支付签名        +
		 * +---------------------------+
		 * @param  $xmlData  xml数据
		 * @return 带上签名的xmlData
		 */
		public static function _getSign($xmlData){
			$res = WxWalletApi::FromXml($xmlData);
			unset($res['sign']);
			ksort($res);
			$stringA = "";
			foreach ($res as $key => $value) {
				if (!empty($value)){
					$stringA .= "{$key}=$value&";
				}
			}

			$stringSignTemp="{$stringA}key=". WxPayConfig::$KEY;
			$sign = md5($stringSignTemp);

			$xmlData = str_replace("{sign}", $sign, $xmlData);
			return $xmlData;
		}

		//签名检查算法  signature、timestamp、nonce

		/**
		 * +---------------------------+
		 * +       生成验证签名        +
		 * +---------------------------+
		 * @param  $signature   生成的签名
		 * @param  $timestamp   生成的签名
		 * @param  $nonce       生成的签名
		 * @return bool  true or false
		 */
		public static function checkSignature($signature,$timestamp,$nonce) {
			if( empty($signature) ){return false;}

			$time = time();
			if ($time - $timestamp > 10) {
				return false;
			}

			$tmpArr = array($timestamp, $nonce);
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );

			if( $tmpStr == $signature ){
				return true;
			}else{
				return false;
			}
		}

		/**
		 * curl请求
		 * @param $url        请求的api
		 * @param $post       是否开户post
		 * @param $postFields post的数据
		 * @return 接口返回的数据
		 */
		public static function curlGet($url, $post = false, $postFields = array(), $timeout = 2) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

			if ($post && !empty($postFields)) {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}

			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		/**
		 * 将xml转为array
		 * @param string $xml
		 * @throws WxPayException
		 */
		public static function FromXml($xml)
		{
			if(!$xml){
				throw new WxPayException("xml数据异常！");
			}
			//将XML转为array
			//禁止引用外部xml实体
			libxml_disable_entity_loader(true);
			return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		}
	}

	class RedpackData extends WxWalletApi
	{
		protected $values = array();

		function __construct(){
		}

		public  function set_mch_billno( $mch_billno ){
			$this->values['mch_billno'] = $mch_billno;
		}
		public  function get_mch_billno(){
			try{
				return $this->values['mch_billno'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_mch_id( $mch_id ){
			$this->values['mch_id'] = $mch_id;
		}
		public  function get_mch_id(){
			try{
				return isset($this->values['mch_id']) ? $this->values['mch_id'] : WxPayConfig::$MCHID;
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_wxappid( $wxappid ){
			$this->values['wxappid'] = $wxappid;
		}
		public  function get_wxappid(){
			try{
				return isset($this->values['wxappid']) ? $this->values['wxappid'] : WxPayConfig::$APPID;
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_nick_name( $nick_name ){
			$this->values['nick_name'] = $nick_name;
		}
		public  function get_nick_name(){
			try{
				return $this->values['nick_name'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_send_name( $send_name ){
			$this->values['send_name'] = $send_name;
		}
		public  function get_send_name(){
			try{
				return $this->values['send_name'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_re_openid( $re_openid ){
			$this->values['re_openid'] = $re_openid;
		}
		public  function get_re_openid(){
			try{
				return $this->values['re_openid'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_total_amount( $total_amount ){
			$this->values['total_amount'] = $total_amount;
		}
		public  function get_total_amount(){
			try{
				return $this->values['total_amount'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_amt_type( $amt_type ){
			$this->values['amt_type'] = $amt_type;
		}
		public  function get_amt_type(){
			try{
				return $this->values['amt_type'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_amt_list( $amt_list ){
			$this->values['amt_list'] = $amt_list;
		}
		public  function get_amt_list(){
			try{
				return $this->values['amt_list'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_watermark_imgurl( $watermark_imgurl ){
			$this->values['watermark_imgurl'] = $watermark_imgurl;
		}
		public  function get_watermark_imgurl(){
			try{
				return $this->values['watermark_imgurl'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_banner_imgurl( $banner_imgurl ){
			$this->values['banner_imgurl'] = $banner_imgurl;
		}
		public  function get_banner_imgurl(){
			try{
				return $this->values['banner_imgurl'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}


		public  function set_min_value( $min_value ){
			$this->values['min_value'] = $min_value;
		}
		public  function get_min_value(){
			try{
				return $this->values['min_value'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_max_value( $max_value ){
			$this->values['max_value'] = $max_value;
		}
		public  function get_max_value(){
			try{
				return $this->values['max_value'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_total_num( $total_num ){
			$this->values['total_num'] = $total_num;
		}
		public  function get_total_num(){
			try{
				return $this->values['total_num'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_wishing( $wishing ){
			$this->values['wishing'] = $wishing;
		}
		public  function get_wishing(){
			try{
				return $this->values['wishing'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_client_ip( $client_ip ){
			$this->values['client_ip'] = $client_ip;
		}
		public  function get_client_ip(){
			try{
				return $this->values['client_ip'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_act_name( $act_name ){
			$this->values['act_name'] = $act_name;
		}
		public  function get_act_name(){
			try{
				return $this->values['act_name'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_act_id( $act_id ){
			$this->values['act_id'] = $act_id;
		}
		public  function get_act_id(){
			try{
				return $this->values['act_id'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}


		public  function set_remark( $remark ){
			$this->values['remark'] = $remark;
		}
		public  function get_remark(){
			try{
				return $this->values['remark'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_logo_imgurl( $logo_imgurl ){
			$this->values['logo_imgurl'] = $logo_imgurl;
		}
		public  function get_logo_imgurl(){
			try{
				return $this->values['logo_imgurl'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}


		public  function set_share_content( $share_content ){
			$this->values['share_content'] = $share_content;
		}
		public  function get_share_content(){
			try{
				return $this->values['share_content'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}


		public  function set_share_url( $share_url ){
			$this->values['share_url'] = $share_url;
		}
		public  function get_share_url(){
			try{
				return $this->values['share_url'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_share_imgurl( $share_imgurl ){
			$this->values['share_imgurl'] = $share_imgurl;
		}
		public  function get_share_imgurl(){
			try{
				return $this->values['share_imgurl'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}

		public  function set_nonce_str( $nonce_str ){
			$this->values['nonce_str'] = $nonce_str;
		}
		public  function get_nonce_str(){
			try{
				return $this->values['nonce_str'];
			}catch(Exception $e){
				throw new WxPayException($e->getMessage());
			}
		}
	}

	class Wallet extends RedpackData
	{
		function __construct(){
			$arrAuthHost = explode('|', WxPayConfig::$OAUTH_HOST);
			if( !in_array($_SERVER['HTTP_HOST'], $arrAuthHost)){
				throw new WxPayException($_SERVER['HTTP_HOST'].' host非法请求');
			}

		}

		/**
		 * 发送红包的xml数据 包
		 * @param  inputObj  传入数据
		 * @return 带签名的完整 xml 数据
		 */
		public  function getSendRedpackXml($inputObj){
//			$xml = <<<eof
//            <xml>
//                <sign>{sign}</sign>
//                <mch_billno>{$inputObj->get_mch_billno()}</mch_billno>
//                <mch_id>{$inputObj->get_mch_id()}</mch_id>
//                <wxappid>{$inputObj->get_wxappid()}</wxappid>
//                <nick_name>{$inputObj->get_nick_name()}</nick_name>
//                <send_name>{$inputObj->get_send_name()}</send_name>
//                <re_openid>{$inputObj->get_re_openid()}</re_openid>
//                <total_amount>{$inputObj->get_total_amount()}</total_amount>
//                <min_value>{$inputObj->get_min_value()}</min_value>
//                <max_value>{$inputObj->get_max_value()}</max_value>
//                <total_num>{$inputObj->get_total_num()}</total_num>
//                <wishing>{$inputObj->get_wishing()}</wishing>
//                <client_ip>{$inputObj->get_client_ip()}</client_ip>
//                <act_name>{$inputObj->get_act_name()}</act_name>
//                <act_id>{$inputObj->get_act_id()}</act_id>
//                <remark>{$inputObj->get_remark()}</remark>
//                <logo_imgurl>{$inputObj->get_logo_imgurl()}</logo_imgurl>
//                <share_content>{$inputObj->get_share_content()}</share_content>
//                <share_url>{$inputObj->get_share_url()}</share_url>
//                <share_imgurl>{$inputObj->get_share_imgurl()}</share_imgurl>
//                <nonce_str>{$inputObj->get_nonce_str()}</nonce_str>
//            </xml>
//eof;
			$xml = <<<eof
				<xml>
				<sign><![CDATA[{sign}]]></sign>
				<mch_billno><![CDATA[{$inputObj->get_mch_billno()}]]></mch_billno>
				<mch_id><![CDATA[{$inputObj->get_mch_id()}]]></mch_id>
				<wxappid><![CDATA[{$inputObj->get_wxappid()}]]></wxappid>
				<send_name><![CDATA[{$inputObj->get_send_name()}]]></send_name>
				<re_openid><![CDATA[{$inputObj->get_re_openid()}]]></re_openid>
				<total_amount><![CDATA[{$inputObj->get_total_amount()}]]></total_amount>
				<total_num><![CDATA[{$inputObj->get_total_num()}]]></total_num>
				<wishing><![CDATA[{$inputObj->get_wishing()}]]></wishing>
				<client_ip><![CDATA[{$inputObj->get_client_ip()}]]></client_ip>
				<act_name><![CDATA[{$inputObj->get_act_name()}]]></act_name>
				<remark><![CDATA[{$inputObj->get_remark()}]]></remark>
				<scene_id><![CDATA[PRODUCT_5]]></scene_id>
				<nonce_str><![CDATA[{$inputObj->get_nonce_str()}]]></nonce_str>
				</xml> 
eof;

			$newXmlData = WxWalletApi:: _getSign($xml);
			$data['api_url']  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
			$data['xml_data'] = $newXmlData;
			return $data;
		}

		/**
		 * 发送裂变红包的xml数据 包
		 * @param  inputObj  传入数据
		 * @return 带签名的完整 xml 数据 add param => amt_type, amt_list, watermark_imgurl, banner_imgurl
		 */
		public  function getSendgroupredpackXml($inputObj){
			$xml = <<<eof
            <xml>
                <sign>{sign}</sign>
                <mch_billno>{$inputObj->get_mch_billno()}</mch_billno>
                <mch_id>{$inputObj->get_mch_id()}</mch_id>
                <wxappid>{$inputObj->get_wxappid()}</wxappid>
                <send_name>{$inputObj->get_send_name()}</send_name>
                <re_openid>{$inputObj->get_re_openid()}</re_openid>
                <total_amount>{$inputObj->get_total_amount()}</total_amount>
                <amt_type>{$inputObj->get_amt_type()}</amt_type>
                <amt_list>{$inputObj->get_amt_list()}</amt_list>
                <total_num>{$inputObj->get_total_num()}</total_num>
                <wishing>{$inputObj->get_wishing()}</wishing>
                <act_name>{$inputObj->get_act_name()}</act_name>
                <remark>{$inputObj->get_remark()}</remark>
                <logo_imgurl>{$inputObj->get_logo_imgurl()}</logo_imgurl>
                <share_content>{$inputObj->get_share_content()}</share_content>
                <share_url>{$inputObj->get_share_url()}</share_url>
                <nonce_str>{$inputObj->get_nonce_str()}</nonce_str>
            </xml>
eof;

			$newXmlData = WxWalletApi:: _getSign($xml);
			$data['api_url']  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
			$data['xml_data'] = $newXmlData;
			return $data;
		}

		/**
		 * 企业付款 xml 数据包
		 * @param  inputObj  传入数据
		 * @return 带签名的完整 xml 数据
		 */
		public  function getSendTransfersXml($inputObj){
			$xml = <<<eof
            <xml>
                <mch_appid>{$inputObj->get_mch_appid()}</mch_appid>
                <mchid>{$inputObj->get_mchid()}</mchid>
                <nonce_str>{$inputObj->get_nonce_str()}</nonce_str>
                <partner_trade_no>{$inputObj->get_partner_trade_no()}</partner_trade_no>
                <openid>{$inputObj->get_openid()}</openid>
                <check_name>{$inputObj->get_check_name()}</check_name>
                <re_user_name>{$inputObj->get_re_user_name()}</re_user_name>
                <amount>{$inputObj->get_amount()}</amount>
                <desc>{$inputObj->get_desc()}!</desc>
                <spbill_create_ip>{$inputObj->get_spbill_create_ip()}</spbill_create_ip>
                <sign>{sign}</sign>
            </xml>
eof;
			$newXmlData = WxWalletApi:: _getSign($xml);
			$data['api_url']  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
			$data['xml_data'] = $newXmlData;
			return $data;
		}

		/**
		 * 自定义发送红包函数
		 * @param string $mchname		提供方名称
		 * @param string $billno		唯一订单号
		 * @param string $wxopenid		接受红包的用户 用户在wxappid下的openid
		 * @param int	 $actid			活动ID
		 * @param int 	 $amount		付款金额，单位分
		 * @param int 	 $minamount		最小红包金额，单位分
		 * @param int	 $maxamount		最大红包金额，单位分（ 最小金额等于最大金额： $minamount = $maxamount = $amount）
		 * @param int	 $sendnumber	红包发放总人数
		 * @param string $wishing		红包祝福语
		 * @param string $actname		活动名称
		 * @param string $remark		备注信息
		 * @throws WxPayException
		 */
		public static function SendRedPacket($mchname,$billno,$wxopenid,$actid,$amount,$minamount,$maxamount,$sendnumber = 1,$wishing,$actname,$remark){
			try
			{
				require_once '../plugins/wxpayapi/log.php';
//初始化日志
				$logHandler= new CLogFileHandler("../plugins/wxpayapi/logs/".date('Y-m-d').'.log');
				$log = Log::Init($logHandler, 15);
				Log::DEBUG("");
				Log::DEBUG("=================================================");
				$SendRedpack = new Wallet();
				$SendRedpack->set_mch_billno($billno);
				$SendRedpack->set_nick_name($mchname);
				$SendRedpack->set_send_name($mchname);
				$SendRedpack->set_re_openid($wxopenid);

				$SendRedpack->set_total_amount($amount);
				$SendRedpack->set_min_value($minamount);
				$SendRedpack->set_max_value($maxamount);
				$SendRedpack->set_total_num($sendnumber);
				$SendRedpack->set_wishing($wishing);
				$SendRedpack->set_client_ip(WxWalletApi::getRealIp()); //调用接口的机器Ip地址

				$SendRedpack->set_act_name($actname);
				$SendRedpack->set_act_id($actid);
				$SendRedpack->set_remark($remark);
				$SendRedpack->set_logo_imgurl('');                 // 商户logo的url
				$SendRedpack->set_share_content('');             // 分享文案
				$SendRedpack->set_share_url('');                 // 分享链接
				$SendRedpack->set_share_imgurl('');                 // 分享的图片url
				$SendRedpack->set_nonce_str(WxWalletApi::getNonceStr()); // 随机字符串
				// 得到签名和其它设置的 xml 数据
				$getNewData = $SendRedpack->getSendRedpackXml($SendRedpack);

				Log::DEBUG("Send SendRedPacket Sign Data:".$getNewData['xml_data']);
				Log::DEBUG("");
				$data       = WxWalletApi::curl_post_ssl($getNewData['api_url'], $getNewData['xml_data']);
				$res        = WxWalletApi::FromXml($data);
				Log::DEBUG("Send Redpace Call Back:".json_encode($res));
				if (!empty($res) && $res["return_code"] == "SUCCESS" && $res["result_code"] == "SUCCESS")
				{
					return true;
				}
				else
				{
					Log::DEBUG("");
					Log::DEBUG("Send SendRedPacket Error Back:".$res["return_msg"]);
					return false;
				}
			}catch (WxPayException $e){
				throw $e;
			}
		}
	}
