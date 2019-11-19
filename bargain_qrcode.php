<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lihongfei
 * Date: 15-7-16
 * Time: 上午10:08
 * To change this template use File | Settings | File Templates.
 */
session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if (isset($_SESSION['u']) && $_SESSION['u'] != "")
{
	$profileid = $_SESSION['u'];
} 

function curl_get_contents($url,$timeout=3) {
	$curlHandle = curl_init();
	curl_setopt( $curlHandle , CURLOPT_URL, $url );
	curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $curlHandle , CURLOPT_TIMEOUT, $timeout );
	$result = curl_exec( $curlHandle );
	curl_close( $curlHandle );
	return $result;
} 

function mb_string_chunk($string, $length){   
    $array = array();  
    $strlen = mb_strlen($string);  
    while($strlen){  
	    for($pos=1;$pos<=$length;$pos++)
		{
			$chunkstr = mb_substr($string, 0, $pos, "utf-8");
			preg_match_all("/([\x{4e00}-\x{9fa5}]){1}/u",$chunkstr,$arrCh);
			$hzcount = count($arrCh[0]); 
			if ($pos + $hzcount*3/5 >= $length)
			{
				 $array[] = $chunkstr;  
				 break;
			}
			else if ($pos >= $strlen)
			{
				 $array[] = $chunkstr;  
				 break;
			} 
		} 
        $string = mb_substr($string, $pos, $strlen, "utf-8"); 
        $strlen = mb_strlen($string);  
    }  
    return $array;  
}  
 

function imagettfsection($image,$imagewidth,$top,$left,$section) {
	
   $fontfile = $_SERVER['DOCUMENT_ROOT'].'/images/qrcode/YaHei.Consolas.1.12.ttf';
   $white =imagecolorallocate($image ,255,255,255);
   $gray = imagecolorallocate($im,128,128,128);
   $resulttop = $top;
   $splitstr = mb_string_chunk($section, 30);
   
   foreach($splitstr as $section_info)
   {
	    imagettftext($image, 20, 0, $left, $resulttop, $gray, $fontfile , $section_info);
   		imagettftext($image, 20, 0, $left-1, $resulttop-1, $white, $fontfile , $section_info);
		$resulttop += 40; 
   } 
   return $resulttop;
}


header("Pragma:no-cache\r\n");
header("Cache-Control:no-cache\r\n");
header("Expires:0\r\n"); 
header('Content-Type:image/jpeg');    //声明格式
 
try{
     
//生成二维码图片
$main = $_SERVER['DOCUMENT_ROOT'].'/images/qrcode/bargain.jpg'; 
$qrcode = $_SERVER['DOCUMENT_ROOT'].'/images/qrcode/tzsc.jpg'; 

global $wxsetting;
if (isset($wxsetting['qrcodeimage']) && $wxsetting['qrcodeimage'] != "")
{
	$qrcodeimage = $_SERVER['DOCUMENT_ROOT'].$wxsetting['qrcodeimage'];
	if(file_exists($qrcodeimage))
	{
		$qrcode = $qrcodeimage;
	}
}  
if(file_exists($main) && file_exists($qrcode))
{
	   $qrcodepng = file_get_contents($main); 
       $QR = imagecreatefromstring($qrcodepng);
       $QR_width = imagesx($QR);//背景图片宽度
       $QR_height = imagesy($QR);//背景图片高度  
	  
	   $supplier_info  =  get_supplier_info();  
   	   $suppliername = $supplier_info['suppliername']; 
   	   $address = $supplier_info['address'];
	   $description = $supplier_info['description']; 
	   $popularizemode = $supplier_info['popularizemode']; // 1 =>  1级推广', 2 =>  '2级推广',3 =>  '3级推广',  0 => '无推广' 
	   $popularizefund = $supplier_info['popularizefund']; // 推广金
	   $distributionmode = $supplier_info['distributionmode']; // 1 =>  1级分销', 2 =>  '2级分销',3 =>  '3级分销',  0 => '无分销' 
	   $sharefund = $supplier_info['sharefund']; // 分享金
	   $ranklimit = $supplier_info['ranklimit']; // 资格限制 
	   $supplierid= $supplier_info['supplierid']; 
	   
	   $profile_info = get_supplier_profile_info($profileid,$supplierid); 
	   $mobile = $profile_info['mobile']; 
	   $headimgurl = $profile_info['headimgurl']; 
	   $givenname = $profile_info['givenname'];   
	  
	   
	   $fontfile = $_SERVER['DOCUMENT_ROOT'].'/images/qrcode/YaHei.Consolas.1.12.ttf';
	   $white = imagecolorallocate($QR ,255,255,255);
	   $gray = imagecolorallocate($im,128,128,128);
	   $black = imagecolorallocate($im,0,0,0); 
	   
	   $left = $QR_width / 2 - 14 * strlen($suppliername) / 2 ; 
	   imagettftext($QR, 30, 0, $left, 80, $gray, $fontfile , $suppliername);
	   imagettftext($QR, 30, 0, $left-3, 80-3, $white, $fontfile , $suppliername);
	   
	   
	   $section = "您的好友".$givenname."，盛情邀请您关注【".$suppliername."】,参加砍价活动。";


	   $resulttop = imagettfsection($QR,$QR_width,180,50,"   ".$section);
	   
	   
       $logo = imagecreatefromstring(file_get_contents($qrcode)); 
       $logo_width = imagesx($logo);//logo图片宽度
       $logo_height = imagesy($logo);//logo图片高度
 
       imagecopyresampled($QR, $logo, 50, $resulttop, 0, 0, 260, 260, $logo_width, $logo_height);
       
	   
		
	   if (isset($headimgurl) && $headimgurl != "")
	   {
	       $headimg = imagecreatefromstring(curl_get_contents($headimgurl)); 
	       $headimg_width = imagesx($headimg);//logo图片宽度
	       $headimg_height = imagesy($headimg);//logo图片高度
	       //重新组合图片并调整大小
           imagecopyresampled($QR, $headimg, 330, $resulttop, 0, 0, 260, 260, $headimg_width, $headimg_height);
	   } 
	   $left = $QR_width / 2 - 220; 
	   $resulttop = imagettfsection($QR,$QR_width,$resulttop+300,$left,"(长按图中二维码，立即参加砍价活动)");
	   
	   
	   //输出图片
       ImageJpeg($QR);
	   @imagedestroy($QR);   
	   @imagedestroy($logo);  
	   @imagedestroy($headimg);         
    }  
     
    
}
catch (XN_Exception $e){
    echo $e->getMessage();
    die();
}
