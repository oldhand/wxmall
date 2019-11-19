<?php
$configs = array(
	array( 
		'rankname'=> "新会员",
		'minrank'=> "0", 
		'sequence'=> "1", 
		'csskey' => "xinhuiyuan",
	),
	array( 
		'rankname'=> "小花",
		'minrank'=> "1", 
		'sequence'=> "2", 
		'csskey' => "xiaohua",
	),
	array( 
		'rankname'=> "星星",
		'minrank'=> "500", 
		'sequence'=> "3", 
		'csskey' => "yueliang",
	),
	array( 
		'rankname'=> "月亮",
		'minrank'=> "2500", 
		'sequence'=> "4", 
		'csskey' => "taiyang",
	),
	array( 
		'rankname'=> "太阳",
		'minrank'=> "12500", 
		'sequence'=> "5", 
		'csskey' => "taiyang",
	),
	array( 
		'rankname'=> "盾牌",
		'minrank'=> "62500", 
		'sequence'=> "6", 
		'csskey' => "dunpai",
	),
	array( 
		'rankname'=> "钻石",
		'minrank'=> "312500", 
		'sequence'=> "7", 
		'csskey' => "zuanshi",
	),
	array( 
		'rankname'=> "皇冠",
		'minrank'=> "1562500", 
		'sequence'=> "8", 
		'csskey' => "huangguan",
	),
	array( 
		'rankname'=> "奖牌",
		'minrank'=> "7812500", 
		'sequence'=> "9", 
		'csskey' => "jiangpai",
	),
	array( 
		'rankname'=> "奖杯",
		'minrank'=> "39062500", 
		'sequence'=> "10", 
		'csskey' => "jiangbei",
	), 
);

$applicaion = XN_Application::$CURRENT_URL;
XN_Application::$CURRENT_URL = 'admin';
$check_profileranks = XN_Query::create ( 'MainContent' )
	->tag ( 'profilerank' )
	->filter ( 'type', 'eic', 'profilerank' )
	->filter ( 'my.rankname', '=', '新会员' )
	->filter ( 'my.deleted', '=', '0' )
	->execute ();
if (count($check_profileranks) == 0)
{
	$profileranks = XN_Query::create ( 'MainContent' )
		->tag ( 'profilerank' )
		->filter ( 'type', 'eic', 'profilerank' )
		->filter ( 'my.deleted', '=', '0' )
		->execute ();
	XN_Content::delete($profileranks,'profilerank');
	foreach($configs as $profilerank_info)
	{
		$newcontent = XN_Content::create('profilerank','',false);
		$newcontent->my->deleted = '0';
		$newcontent->my->rankname = $profilerank_info['rankname'];
		$newcontent->my->minrank = $profilerank_info['minrank'];
		$newcontent->my->sequence = $profilerank_info['sequence'];
		$newcontent->my->csskey = $profilerank_info['csskey'];
		$newcontent->save('profilerank');
	}
}
XN_Application::$CURRENT_URL = $applicaion;
	
?>