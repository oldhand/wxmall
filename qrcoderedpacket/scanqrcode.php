<?php
	/**
	 * Created by PhpStorm.
	 * User: clubs
	 * Date: 2018/8/1
	 * Time: 上午9:25
	 */

	if (!isset($_REQUEST["token"]) || $_REQUEST["token"] == "")
	{
		MessageBox('错误', '二维码已失效，感谢您的支持！', true);
	}
	$token      = $_REQUEST["token"];
	$qrcode     = base64_decode($token);
	$qrcodes    = explode("_", $qrcode);
	$supplierid = $qrcodes[0];

	$supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
								   ->filter('type', 'eic', 'supplier_wxsettings')
								   ->filter('my.deleted', '=', '0')
								   ->filter('my.supplierid', '=', $supplierid)
								   ->end(1)
								   ->execute();
	if (count($supplier_wxsettings) > 0)
	{
		$supplier_wxsetting_info = $supplier_wxsettings[0];
		$appid                   = $supplier_wxsetting_info->my->appid;
		$secret                  = $supplier_wxsetting_info->my->secret;
		require_once 'wxoauth2.php';
		WxOauth2::$APPID     = $appid;
		WxOauth2::$APPSECRET = $secret;
		$wxopenid            = WxOauth2::GetOpenid();
		if (!isset($wxopenid) || $wxopenid == '')
		{
			MessageBox('错误', '微信登录失败！', true);
		}
	}
	else
	{
		MessageBox('错误', '商家信息不全！', true);
	}

	$query = XN_Query::create("MainContent")->tag("mall_qrcode")
					 ->filter("type", "eic", "mall_qrcode")
					 ->filter("my.deleted", "=", "0")
					 ->filter("my.qrcode", "=", $token)
					 ->filter("my.status", "=", "0")
					 ->filter("my.supplierid", "=", $supplierid)
					 ->end(1)->execute();
	if (count($query) > 0)
	{
		$qrcodeHint = $query[0]->my->notmemberhint;
		if (!isset($qrcodeHint) || $qrcodeHint == "")
		{
			$qrcodeHint = "长沙新恒隆感谢您的关注！";
		}
	}
	else
	{
		$qrcodeHint = "长沙新恒隆感谢您的关注！";
	}

	$query = XN_Query::create("MainContent")->tag('supplier_profile')
					 ->filter("type", "eic", "supplier_profile")
					 ->filter("my.wxopenid", "=", $wxopenid)
					 ->filter("my.supplierid", "=", $supplierid)
					 ->filter("my.subscribe", "=", "0")
					 ->filter("my.deleted", "=", "0")
					 ->end(1)->execute();
	if (count($query) <= 0)
	{
		followMessage("关注", $qrcodeHint, $supplier_wxsetting_info->my->qrcodeimage, true);
	}
	else
	{
		$supplierprofile = $query[0];
		$profileid       = $supplierprofile->my->profileid;
		$mobile          = $supplierprofile->my->mobile;
		if (isset($mobile) && $mobile != "")
		{
			$query = XN_Query::create("MainContent")->tag("mall_qrcoderedpacket")
							 ->filter("type", "eic", "mall_qrcoderedpacket")
							 ->filter("my.deleted", "=", "0")
							 ->filter("my.profileid", "=", "")
							 ->filter("my.supplierid", "=", $supplierid)
							 ->filter("my.mobile", "=", $mobile)
							 ->filter("my.status", "=", "0")
							 ->execute();
			foreach ($query as $item)
			{
				$item->my->profileid = $profileid;
				$item->my->member    = $supplierprofile->id;
				if ($supplierprofile->my->gender == "男")
					$item->my->gender = "1";
				elseif ($supplierprofile->my->gender == "女")
					$item->my->gender = "2";
				else
					$item->my->gender = "3";
				$item->my->province = $supplierprofile->my->province;
				$item->my->city     = $supplierprofile->my->city;
				$item->save('mall_qrcoderedpacket');
			}
		}
	}

	$query = XN_Query::create("MainContent")->tag("mall_qrcoderedpacket")
					 ->filter("type", "eic", "mall_qrcoderedpacket")
					 ->filter("my.deleted", "=", "0")
					 ->filter("my.profileid", "=", $profileid)
					 ->filter("my.supplierid", "=", $supplierid)
					 ->filter("my.status", "=", "0")
					 ->end(1)->execute();
	if (count($query) <= 0)
	{
		MessageBox('欢迎', $qrcodeHint, true);
	}
	else
	{
		$qrcoderedpacket = $query[0];
	}

	$query = XN_Query::create("MainContent")->tag("mall_qrcode")
					 ->filter("type", "eic", "mall_qrcode")
					 ->filter("my.deleted", "=", "0")
					 ->filter("my.qrcode", "=", $token)
					 ->filter("my.status", "=", "0")
					 ->filter("my.supplierid", "=", $supplierid)
					 ->execute();
	if (count($query) <= 0)
	{
		MessageBox('提示', '二维码已失效，感谢您的支持！', true);
	}

	if (intval($query[0]->my->usedcount) <= 0)
	{
		$query[0]->my->scannumber = intval($query[0]->my->scannumber) + 1;
		$query[0]->save("mall_qrcode");
		MessageBox('提示', '此二维码已全部领完，感谢您的支持！', true);
	}
	else
	{
		$qrcodeConn = $query[0];
	}

	$query = XN_Query::create("MainContent")->tag("mall_qrcode_usedetails")
					 ->filter("type", "eic", "mall_qrcode_usedetails")
					 ->filter("my.profileid", "=", $profileid)
					 ->filter("my.qrcode", "=", $token)
					 ->filter("my.deleted", "=", "0")
					 ->filter("my.supplierid", "=", $supplierid)
					 ->execute();
	if (count($query) > 0)
	{
		MessageBox('欢迎', '您已经领取过此二维码，感谢您的支持！', true);
	}

	//region 处理领红包事务
	if (isset($qrcodeConn))
	{
		try
		{
			$qrmoney = floatval($qrcodeConn->my->qrmoney) * 100;

			$qrcodeConn->my->scannumber = intval($qrcodeConn->my->scannumber) + 1;
			$usedcount                  = intval($qrcodeConn->my->usedcount) - 1;
			if ($usedcount < 0)
				$usedcount = 0;
			$qrcodeConn->my->usedcount = $usedcount;
			$qrcodeConn->save("mall_qrcode");

			$usedetailConn                = XN_Content::create("mall_qrcode_usedetails", "", true);
			$usedetailConn->my->profileid = $profileid;
			$usedetailConn->my->qrcode    = $token;
			$usedetailConn->my->deleted   = '0';
			$usedetailConn->my->usedtime  = date("Y-m-d H:i:s");
			$usedetailConn->my->usedmoney = $qrcodeConn->my->qrmoney;
			if (isset($qrcoderedpacket))
			{
				$usedetailConn->my->member = $qrcoderedpacket->my->member;
				$overman    = $qrcoderedpacket->my->overman;
				$percentage = $qrcoderedpacket->my->percentage;
				if (isset($overman) && $overman != "")
				{
					$usedetailConn->my->overman    = $overman;
				}
				if (isset($percentage) && floatval($percentage) > 0){
					$rank = intval($percentage * $qrmoney / 10000);
					$usedetailConn->my->percentage = $percentage;
					$usedetailConn->my->tage       = $rank;
					try{
						//师傅添加积分
						$overmanConn = XN_Content::load($overman,'supplier_profile',4);
						$overmanConn->my->rank = intval($overmanConn->my->rank) + $rank;
						$overmanConn->my->accumulatedrank = intval($overmanConn->my->accumulatedrank) + $rank;
						$overmanConn->save('supplier_profile');
					}catch(XN_Exception $e){

					}
				}
				//扫码加积分
//				try{
//					$memberConn = XN_Content::load($qrcoderedpacket->my->member,'supplier_profile',4);
//					$memberConn->my->rank = intval($memberConn->my->rank) + $qrmoney / 100;
//					$memberConn->my->accumulatedrank = intval($memberConn->my->accumulatedrank) + $qrmoney / 100;
//					$memberConn->save('supplier_profile');
//				}catch (XN_Exception $e){
//				}
			}
			$usedetailConn->my->supplierid = $supplierid;
			$usedetailConn = $usedetailConn->save("mall_qrcode_usedetails");

			//发送红包
			if (isset($supplier_wxsetting_info))
			{
				require_once '../plugins/wxpayapi/lib/WxWallet.Api.php';
				require_once '../plugins/wxpayapi/lib/WxPay.Config.php';
				WxPayConfig::$APPID        = $supplier_wxsetting_info->my->appid;
				WxPayConfig::$MCHID        = $supplier_wxsetting_info->my->mchid;
				WxPayConfig::$KEY          = $supplier_wxsetting_info->my->mchkey;
				WxPayConfig::$APPSECRET    = $supplier_wxsetting_info->my->secret;
				WxPayConfig::$SSLCERT_PATH = $_SERVER["DOCUMENT_ROOT"].$supplier_wxsetting_info->my->sslcert;
				WxPayConfig::$SSLKEY_PATH  = $_SERVER["DOCUMENT_ROOT"].$supplier_wxsetting_info->my->sslkey;
				WxPayConfig::$OAUTH_HOST   = "|f2c.tezan.cn|124.232.147.61|";
				$supplierinfo              = XN_Content::load($supplierid, "suppliers", 4);
				if (Wallet::SendRedPacket($supplierinfo->my->mallname,
										  $usedetailConn->id,
										  $wxopenid,
										  $qrcodeConn->id,
										  $qrmoney, $qrmoney, $qrmoney, 1,
										  "生意兴隆", "生意兴隆", "生意兴隆"))
				{
					MessageBox('提示', "红包已发放，请注意查收！", true);
				}
				else
				{
					MessageBox('提示', "发放红包失败！", true);
				}
			}
//			MessageBox('提示', "积分已发放！", true);
		}
		catch (Exception $e)
		{
			MessageBox('提示', $e->getMessage(), true);
		}
	}
	else
	{
		MessageBox("提示", "领取失败！", true);
	}
	//endregion

	//region 自定义函数
	function followMessage($title, $msg, $url, $isBreak = false)
	{
		header('Content-Type:text/html;charset=utf-8');
		$html = '
			<html>
			<head> 
			<title>'.$title.'</title>
			<meta http-equiv=content-type content="text/html; charset=utf8"> 
			  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
			<style type=text/css>
			body { font-size: 12px; font-family: tahoma }
			td { font-size: 12px; font-family: tahoma }
			a:link { text-decoration: none }
			a:visited { text-decoration: none }
			a:hover { text-decoration: underline }
			body { background-color: #cccccc }
			.maintable {
				overflow:hidden;
				border:1px solid #d3d3d3;
				background:#fefefe;
				width:100%;
				margin:5% auto 0;
				-moz-border-radius:15px; /* FF1+ */
				-webkit-border-radius:15px; /* Saf3-4 */
				border-radius:15px;
				-moz-box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
				-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
			}
			</style>
			<script src="public/js/baidu.js?_=20140821" type="text/javascript"></script>
			</head>
			<body style="table-layout: fixed; word-break: break-all" topmargin="10" marginwidth="10" marginheight="10"> 
			<table height="95%" cellspacing=0 cellpadding=0 width="100%" align=center border=0>
			  <tbody>
			  <tr valign=center align=middle>
				<td>
				  <table class="maintable" cellspacing=0 cellpadding=0 width="96%" bgcolor=#ffffff border=0>
					<tbody>
					<tr>
					  <td width=20  height=20></td>
					  <td width=108 background="/images/rbox/rbox_2.gif"  height=20></td>
					  <td width=56><img height=20 src="/images/rbox/rbox_ring.gif" width=56></td>
					  <td width=100 background="/images/rbox/rbox_2.gif"></td>
					  <td width=56><img height=20 src="/images/rbox/rbox_ring.gif" width=56></td>
					  <td width=108 background="/images/rbox/rbox_2.gif"></td>
					  <td width=20  height=20></td>
					</tr>
					<tr>
					  <td align=left rowspan=2></td>
					  <td align=middle bgcolor=#eeeeee colspan=5 height=80>
						<p><strong>'.$msg.'<br><br></strong></p></td>
					  <td align=left rowspan=2></td>
					</tr>
					<tr>
					  <td align=left colspan=5 height=80>
					  	<p align=center><br><p id=lid2 style="text-align: center;">长按关注二维码,识别二维码</p>
						<div align=center><img style="height: 150px;" src="'.$url.'"/></div></td>
					</tr>
					<tr>
					  <td align=left height=20></td>
					  <td align=left colspan=5 height=20></td>
					  <td align=left height=20></td></tr>
				  </tbody></table>
			  </td></tr>
			</tbody>
			</table> 
			</body> 
			</html>
		';
		echo $html;
		if ($isBreak)
			die();
	}

	function MessageBox($title, $msg, $isBreak = false)
	{
		header('Content-Type:text/html;charset=utf-8');
		$html = '
			<html>
			<head> 
			<title>'.$title.'</title>
			<meta http-equiv=content-type content="text/html; charset=utf8"> 
			  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
			<style type=text/css>
			body { font-size: 12px; font-family: tahoma }
			td { font-size: 12px; font-family: tahoma }
			a:link { text-decoration: none }
			a:visited { text-decoration: none }
			a:hover { text-decoration: underline }
			body { background-color: #cccccc }
			.maintable {
				overflow:hidden;
				border:1px solid #d3d3d3;
				background:#fefefe;
				width:100%;
				margin:5% auto 0;
				-moz-border-radius:15px; /* FF1+ */
				-webkit-border-radius:15px; /* Saf3-4 */
				border-radius:15px;
				-moz-box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
				-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
			}
			</style>
			<script src="public/js/baidu.js?_=20140821" type="text/javascript"></script>
			</head>
			<body style="table-layout: fixed; word-break: break-all" topmargin="10" marginwidth="10" marginheight="10"> 
			<table height="95%" cellspacing=0 cellpadding=0 width="100%" align=center border=0>
			  <tbody>
			  <tr valign=center align=middle>
				<td>
				  <table class="maintable" cellspacing=0 cellpadding=0 width="96%" bgcolor=#ffffff border=0>
					<tbody>
					<tr>
					  <td width=20  height=20></td>
					  <td width=108 background="/images/rbox/rbox_2.gif"  height=20></td>
					  <td width=56><img height=20 src="/images/rbox/rbox_ring.gif" width=56></td>
					  <td width=100 background="/images/rbox/rbox_2.gif"></td>
					  <td width=56><img height=20 src="/images/rbox/rbox_ring.gif" width=56></td>
					  <td width=108 background="/images/rbox/rbox_2.gif"></td>
					  <td width=20  height=20></td>
					</tr>
					<tr>
					  <td align=left rowspan=2></td>
					  <td align=middle bgcolor=#eeeeee colspan=5 height=80>
						<p><strong>'.$msg.'<br><br></strong></p></td>
					  <td align=left rowspan=2></td>
					</tr>
					<tr>
					  <td align=left height=20></td>
					  <td align=left colspan=5 height=20></td>
					  <td align=left height=20></td></tr>
				  </tbody></table>
			  </td></tr>
			</tbody>
			</table> 
			</body> 
			</html>
		';
		echo $html;
		if ($isBreak)
			die();
	}
	//endregion