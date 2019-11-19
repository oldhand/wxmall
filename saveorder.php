<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");
require_once (dirname(__FILE__) . "/payment.func.php");

if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
	$profileid = $_SESSION['profileid']; 
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
	$profileid = $_SESSION['accessprofileid']; 
}
else
{
	echo '{"code":201,"msg":"用户ID!"}';
	die();
}
if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
}

if(isset($_REQUEST['orderid']) && $_REQUEST['orderid'] !='')
{
	$orderid = $_REQUEST['orderid']; 
} 
else
{
	echo '{"code":201,"msg":"没有订单ID!"}';
	die();  
}
 
if(isset($_REQUEST['paymentway']) && $_REQUEST['paymentway'] !='')
{
	$paymentway = $_REQUEST['paymentway']; 
} 
if(isset($_REQUEST['usemoney']) && $_REQUEST['usemoney'] !='')
{
	$usemoney = $_REQUEST['usemoney']; 
}
if(isset($_REQUEST['needpayable']) && $_REQUEST['needpayable'] !='')
{
	$needpayable = $_REQUEST['needpayable']; 
}    
if(isset($_REQUEST['vipcardusageid']) && $_REQUEST['vipcardusageid'] !='')
{
	$vipcardusageid = $_REQUEST['vipcardusageid']; 
} 
else
{
	$vipcardusageid = "";
}
if(isset($_REQUEST['vipcardusageamount']) && $_REQUEST['vipcardusageamount'] !='')
{
	$vipcardusageamount = $_REQUEST['vipcardusageamount']; 
} 

try{   
	    $order_info = XN_Content::load($orderid,"mall_orders_".$profileid,7);  
	 
		if ($paymentway == "rankpayment")
		{
			if (finished_rank_trade($order_info))
			{
				ticheng($order_info);  
				echo '{"code":200,"paymentway":"tzb"}';
				die();
			} 
			else
			{
				echo '{"code":201,"msg":"支付失败!"}';
				die();  
			}
		}
		else
		{
			if ($usemoney == '1')
			{  
				//使用余额的情况
				if (floatval($needpayable) == 0)
				{
					//使用余额足够的情况  
					if (!check_frozenlist($profileid))
					{
						if (finished_yue_trade($order_info,$vipcardusageid,$vipcardusageamount))
						{
							ticheng($order_info);  
							echo '{"code":200,"paymentway":"tzb"}';
							die();
						} 
						else
						{
							echo '{"code":201,"msg":"支付失败!"}';
							die();  
						}
					}
					else
					{
						echo '{"code":201,"msg":"您的余额已经冻结!"}';
						die();
					} 
				}
				else
				{
					//使用余额+第三方支付的情况
					$orderid = $order_info->id; 
					$supplierid = $order_info->my->supplierid; 
					$ordername = $order_info->my->ordername;
					$order_no = $order_info->my->mall_orders_no;
					$sumorderstotal = $order_info->my->sumorderstotal; 
				    $userank = $order_info->my->userank;
					$rankmoney = $order_info->my->rankmoney;
					$rankcostrate = $order_info->my->rankcostrate;
					$vipdeductionmoney = $order_info->my->vipdeductionmoney;
				
				
					$total_discount = 0;
					global $vipcardid;	
					if (isset($vipcardusageid) && $vipcardusageid != "" &&
						isset($vipcardusageamount) && $vipcardusageamount != "")
					{ 
						 $total_discount = discount($sumorderstotal,$vipcardusageid); 
						 $discount_sumorderstotal = $sumorderstotal - $total_discount;
	 					 $mall_usage_info = XN_Content::load($vipcardusageid,'mall_usages_'.$profileid,7); 
	 					 if ($mall_usage_info->my->timelimit == '0')
	 					 {  
	 		   			    $mall_usage_info->my->orderid = $orderid; 
	 		   			    $mall_usage_info->my->presubmittime = date("Y-m-d H:i:s");
	 					 } 
	 					 $mall_usage_info->save("mall_usages,mall_usages_".$profileid.",mall_usages_".$supplierid);  
					
					} 
					else
					{
						 $discount_sumorderstotal = $sumorderstotal;
						 $vipcardid = "";
					}
				
					$profile_info = get_supplier_profile_info($profileid,$supplierid);
				
					if (count($profile_info) > 0)
					{
						$money = $profile_info['money'];
						$supplier_info = get_supplier_info();
						$moneypaymentrate = $supplier_info['moneypaymentrate']; 
						
						if ($userank == "1")
						{
							$paymentamount = floatval($sumorderstotal) - $total_discount - floatval($rankmoney) - floatval($vipdeductionmoney);
						}
						else
						{
							$paymentamount = floatval($sumorderstotal) - $total_discount - floatval($vipdeductionmoney);
						}
						
						
						if (isset($moneypaymentrate) && $moneypaymentrate != '')
						{
							$maxpayment = $paymentamount - $paymentamount * (100 - intval($moneypaymentrate)) / 100;
						}
						else
						{
							$maxpayment = $paymentamount;
						}

						$money = floatval($profile_info['money']);	
 
						if (round($money,2) < round($maxpayment,2)) 
						{
							$availablenumber = $money;
						}
						else
						{
							$availablenumber = $maxpayment;
						}  
						$mathneedpayable = $paymentamount - $availablenumber;  
						if (round(floatval($needpayable),2) == round(floatval($mathneedpayable),2))  
						{ 
							$amount = round(floatval($needpayable) * 100); 
						 	$order_info->my->paymentamount = number_format($needpayable,2,".","");
						 	$order_info->my->usemoney = number_format($availablenumber,2,".",""); 
							$order_info->my->paymentway = "weixin+tzb";  
							$order_info->my->payment = "余额+微信支付";
							$order_info->my->paymentmode = '1'; 
							$order_info->my->discount = number_format($total_discount,2,".",""); 
							$order_info->my->vipcardid = $vipcardid; 
							$order_info->my->usageid = $vipcardusageid;
						 	$order_info->save('mall_orders,mall_orders_'.$profileid.',mall_orders_'.$supplierid); 
						
						
							if ($supplierid == "71352" || $supplierid == "12434") // 特赞测试账号
							{
								$jsApiParameters = weixin_jsapi($profileid,$ordername,'1',$order_no,$orderid);
							}
							else
							{
								$jsApiParameters = weixin_jsapi($profileid,$ordername,$amount,$order_no,$orderid);
							} 
						 
							if (floatval($needpayable) > 0)
							{
			                	$consumelogs = XN_Query::create ( 'YearContent' )->tag('mall_consumelogs')
									->filter ( 'type', 'eic', 'mall_consumelogs') 
									->filter ( 'my.deleted', '=', '0')   
									->filter ( 'my.orderid', '=', $orderid)  
									->end(-1)
									->execute (); 
								if (count($consumelogs) > 0)
								{
									$consumelog_info = $consumelogs[0]; 
									$consumelog_info->my->amount = number_format($money,2,".","");
									$consumelog_info->my->remain = '';
									$consumelog_info->my->sumorderstotal = $sumorderstotal;
									$consumelog_info->save('mall_consumelogs,mall_consumelogs_'.$profileid.',mall_consumelogs_'.$supplierid);
								} 
								else
								{ 
									$newcontent = XN_Content::create('mall_consumelogs','',false,7);					  
									$newcontent->my->deleted = '0';  
									$newcontent->my->profileid = $profileid;    
									$newcontent->my->supplierid = $supplierid;  
									$newcontent->my->orderid = $orderid;   
									$newcontent->my->paymentdatetime = '';
									$newcontent->my->amount = number_format($money,2,".","");
									$newcontent->my->remain = '';
									$newcontent->my->sumorderstotal = $sumorderstotal;
									$newcontent->my->consumelogsstatus = '混合待支付';
									$newcontent->my->tradestatus = "notrade";
									$newcontent->save('mall_consumelogs,mall_consumelogs_'.$profileid.',mall_consumelogs_'.$supplierid);
								}  
							}  
							echo '{"code":200,"paymentway":"weixin","json":',$jsApiParameters,'}'; 
							die(); 
						}
						else
						{
							echo '{"code":201,"msg":"余额+优惠金额+应付金额+积分抵扣+折扣优惠!=订单总额？"}';
							die(); 
						} 
					}
					else
					{
						echo '{"code":201,"msg":"没有用户余额数据!"}';
						die(); 
					} 
				}
			}
			else
			{
				$total_discount = 0;
				global $vipcardid;	
				if (isset($vipcardusageid) && $vipcardusageid != "" &&
					isset($vipcardusageamount) && $vipcardusageamount != "")
				{ 
					 $total_discount = discount($sumorderstotal,$vipcardusageid); 
					 $mall_usage_info = XN_Content::load($vipcardusageid,'mall_usages_'.$profileid,7); 
					 if ($mall_usage_info->my->timelimit == '0')
					 {  
		   			    $mall_usage_info->my->orderid = $orderid; 
		   			    $mall_usage_info->my->presubmittime = date("Y-m-d H:i:s");
					 } 
					 $mall_usage_info->save("mall_usages,mall_usages_".$profileid.",mall_usages_".$supplierid); 
				} 
				else
				{
					 $vipcardid = "";
				} 
				$orderid = $order_info->id; 
				$supplierid = $order_info->my->supplierid; 
				$ordername = $order_info->my->ordername;
				$order_no = $order_info->my->mall_orders_no;
				$sumorderstotal = $order_info->my->sumorderstotal;  
			    $userank = $order_info->my->userank;
				$rankmoney = $order_info->my->rankmoney;
				$rankcostrate = $order_info->my->rankcostrate;
				$vipdeductionmoney = $order_info->my->vipdeductionmoney;
				
				if ($userank == "1")
				{
					$paymentamount = floatval($sumorderstotal) - $total_discount - floatval($rankmoney) - floatval($vipdeductionmoney);
				}
				else
				{
					$paymentamount = floatval($sumorderstotal) - $total_discount - floatval($vipdeductionmoney);
				}
			
				if (round(floatval($needpayable),2) == round($paymentamount,2))  
				{   
					if ($paymentway == "weixin")
					{
						$amount = round($paymentamount * 100);  
						$order_info->my->paymentamount = $paymentamount;  
					 	$order_info->my->usemoney = '0.00';  
						$order_info->my->paymentway = "weixin"; 
						$order_info->my->payment = "微信支付"; 
						$order_info->my->paymentmode = '1'; 
						$order_info->my->vipcardid = $vipcardid; 
						$order_info->my->usageid = $vipcardusageid;
					 	$order_info->save('mall_orders,mall_orders_'.$profileid.',mall_orders_'.$supplierid); 
						if ($supplierid == "71352" || $supplierid == "12434") // 特赞测试账号
						{
							$jsApiParameters = weixin_jsapi($profileid,$ordername,'1',$order_no,$orderid);
						}
						else
						{
							$jsApiParameters = weixin_jsapi($profileid,$ordername,$amount,$order_no,$orderid);
						}
			
						echo '{"code":200,"paymentway":"weixin","json":',$jsApiParameters,'}';
						die(); 
					} 
					else
					{
						echo '{"code":201,"msg":"不存在的支付方式！"}';
						die(); 
					}
				
				}
				else
				{
					echo '{"code":201,"msg":"优惠金额+应付金额!=订单总额？"}';
					die(); 
				} 
				//完全不使用余额的情况
			}
		} 
 
	echo 'success';
	die();
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	echo '{"code":202,"msg":"'.$msg.'"}'; 
	die(); 
} 

 
 
 
?>