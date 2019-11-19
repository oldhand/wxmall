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

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $supplierid = $_SESSION['supplierid'];
}
else
{ 
    die();
}
if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
{
    $profileid = $_SESSION['profileid'];
}
elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
     die();
}

if(isset($_REQUEST['productid']) && $_REQUEST['productid'] !='')
{
	$productid= $_REQUEST['productid'];  
}
else
{
	die(); 
} 


 
header("Pragma:no-cache\r\n");
header("Cache-Control:no-cache\r\n");
header("Expires:0\r\n"); 

require_once ('include/qrcode/phpqrcode.php');
 
try{ 
	

	$query_string = base64_encode("detail.php?productid=".$productid); 
	$url = 'http://' . $WX_DOMAIN . '/index.php?u=' . $profileid . '&sid=' . $supplierid . '&uri=' . $query_string;
    $errorCorrectionLevel = 'L';//容错级别
    $matrixPointSize = 6;//生成图片大小

//生成二维码图片
    
	   ob_start();
       QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize, 2); 
	   $qrcodepng = ob_get_contents();
	   ob_end_clean(); 
	   ImagePng($qrcodepng);
       $qrcodeimage = imagecreatefromstring($qrcodepng);
	   
	   $mainjpeg = imagecreate (320,320);
	   $background_color = imagecolorallocate($mainjpeg, 255, 255, 255);
		   
     //  $logo = imagecreatefromstring(file_get_contents($logo));
       $main_width = imagesx($mainjpeg);//二维码图片宽度
       $main_height = imagesy($mainjpeg);//二维码图片高度
       $qrcode_width = imagesx($qrcodeimage);//logo图片宽度
       $qrcode_height = imagesy($qrcodeimage);//logo图片高度
       $qrcode_qr_width = $main_width - 20;
       $scale = $qrcode_width/$qrcode_qr_width;
       $qrcode_qr_height = $qrcode_height/$scale;
       $from_width = ($main_width - $qrcode_qr_width) / 2;
       //重新组合图片并调整大小
       imagecopyresampled($mainjpeg, $qrcodeimage, $from_width, $from_width-10, 0, 0, $qrcode_qr_width,$qrcode_qr_height, $qrcode_width, $qrcode_height);
       
       $fontfile = $_SERVER['DOCUMENT_ROOT'] . '/images/qrcode/YaHei.Consolas.1.12.ttf';
       $white = imagecolorallocate($mainjpeg, 255, 255, 255);
       $gray = imagecolorallocate($mainjpeg, 80, 80, 80);
       $black = imagecolorallocate($mainjpeg, 0, 0, 0);

	   $msg = "打造优质低价,长按图中二维码进入商城";
       $left = 18;
       imagettftext($mainjpeg, 12, 0, $left, 305, $gray, $fontfile, $msg);
       imagettftext($mainjpeg, 12, 0, $left - 1, 305 - 1, $white, $fontfile, $msg);
	   
	   //输出图片
       ImagePng($mainjpeg);
       imagedestroy($mainjpeg);
	   imagedestroy($qrcodeimage);
	
     
     
    
}
catch (XN_Exception $e){
     die();
}
