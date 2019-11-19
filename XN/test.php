<?php 

ini_set('memory_limit','2048M');
set_time_limit(0);
header('Content-Type: text/html; charset=utf-8');

XN_Application::$CURRENT_URL = "admin";
$app = XN_Application::load();
echo $app->debugString();
 

// echo '<br><br><br>';
// test_profile();
// echo '<br><br><br>';
// test_sql();
// echo '<br><br><br>';
// test_index_content();
// echo '<br><br><br>';
// test_base_content();
// echo '<br><br><br>';
// test_yearcontent();
// echo '<br><br><br>';
// test_yearmonthcontent();
// echo '<br><br><br>';
// test_bigcontent();
// echo '<br><br><br>';
// test_mq();
// echo '<br><br><br>';
test_message();
echo '<br><br><br>';


function test_profile()
{
	$email = date("YmdHis")."@tezan.cn";
	$password = "123qwe";
	$mobile = "159".date("dHis");
	$username = "admin_".date("Y_m_d_H_i_s"); 	
	try 
	{
  		$profile = XN_Profile::create ( strtolower(trim($email)), $password );
  		$profile->fullname = strtolower($username);
  		$profile->mobile = trim($mobile);
  		$profile->status = 'True';	 
  		$profile->type = "register";
  		$profile->save ("profile");
  		echo  '测试创建用户['.$username.']成功<br>';
	}
  	catch ( XN_Exception $e ) 
  	{
  		echo  '测试创建用户['.$username.']失败['.$e->getMessage().']!<br>';
  		die();
  	} 
	$profileid = $profile->profileid;
    try 
    {
  		$profile_info = XN_Profile::load ($profileid,'id',"profile");	
  		echo "测试获取用户[".$profileid."]成功!<br>";
    }
	catch(XN_Exception $e){
	    echo "测试获取用户[".$profileid."]失败[".$e->getMessage()."]!<br>";
	} 
    try 
    {
		$profile->password = date("YmdHis");	  
		$profile->givenname = "admin";       
		$profile->save();	
  		echo "测试保存用户[".$profileid."]成功!<br>";
    }
	catch(XN_Exception $e){
	    echo "测试保存用户[".$profileid."]失败[".$e->getMessage()."]!<br>";
	} 
    
	try{
		$profiles = XN_Query::create ( 'Profile' ) ->tag("profile")
						 	 ->filter ( 'published', '>=', '2018-01-01 00:00:00' )
							 ->filter ( 'published', '<=', date("Y-m-d").' 23:59:59' )
							 ->order("published",XN_Order::DESC)
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($profiles) > 0)
		 {
			 echo '通过查询,测试获取用户成功<br>'; 
			 foreach($profiles as $profile_info )
			 {
				 echo '通过查询,测试获取用户数据ID:'.$profile_info->profileid . ' =>  '.$profile_info->givenname.'<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "通过查询,测试获取用户失败 : ".$e->getMessage() . '<br>'; 
	} 
	

	 

	try{ 
		$query = XN_Query::create ( 'Profile_Count' ) ->tag("profile")
						 	 ->filter ( 'published', '>=', '2018-01-01 00:00:00' )
							 ->filter ( 'published', '<=', date("Y-m-d").' 23:59:59' ) 
					   	     ->end(-1);
		$query->execute(); 
		echo "统计用户记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计用户记录个数失败 :".$e->getMessage() . '<br>'; 
	}  
	

	try{ 
			$query = XN_Query::create('Profile_Count')->tag('profile') 
				 	 ->filter ( 'published', '>=', '2015-01-01 00:00:00' )
					 ->filter ( 'published', '<=', date("Y-m-d").' 23:59:59' ) 
				     ->filter('type','!=','')
				     ->rollup()
				     ->group('type')
				     ->order("count",XN_Order::DESC_NUMBER) 
			 		 ->order("type",XN_Order::ASC);    
			$query->begin(0);
			$query->end(10);
		    $statistics = $query->execute(); 
		 echo '分组统计用户记录个数成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组统计用户记录个数获得数据ID:'.$statistic_info->my->type . ' =>  ' . $statistic_info->my->count . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计用户记录个数失败 :".$e->getMessage() . '<br>'; 
	}  
	
}


function test_message()
{
	 try{ 
		  $profileid = 'm0ju5li2541';
		  $tag = "messages,messages_".$profileid; 
	  	  XN_Content::create('message','',false,6)
   	  			  ->my->add('deleted','0')
   	  			  ->my->add('status','0')
  				  ->my->add('supplierid','41461')
   	  			  ->my->add('profileid',"hx5eyjjmlg6")
   	  			  ->my->add('sendid',"hx5eyjjmlg6")
   	  			  ->my->add('viewtime','')
   	  			  ->my->add('message',"abcdefabcdefabcdefabcdef")
   	  			  ->save($tag);
	   
	 	 $newcontent = XN_Content::create('push','',false,6)			
 			  ->my->add('deleted','0')
 			  ->my->add('status','0') 
			  ->my->add('supplierid','41461')
 			  ->my->add('profileid',"hx5eyjjmlg6")
 			  ->my->add('sendid',"hx5eyjjmlg6")
 			  ->my->add('viewtime','') 
 			  ->my->add('message',"中华人民共和国")
				  ->save($tag);
		 
	}
	catch(XN_Exception $e){
	   echo "测试发送消息失败 :".$e->getMessage() . '<br>'; 
	} 
	

	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'message',6);
		echo "获取消息记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取消息记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'message',6); 
		$info->my->status = '1'; 
	    $info->my->message = '更新消息记录成功'; 
		$info->save('message');
		echo "更新消息记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新消息记录失败 :".$e->getMessage() . '<br>'; 
	} 



	try{
		$shoppingcarts = XN_Query::create ( 'Message' )->tag('message' ) 
							 ->filter ( 'type', 'eic', 'message' ) 
							 ->filter (  'my.deleted', '=','0') 
							 ->order("published",XN_Order::DESC)
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute(); 
		 if (count($shoppingcarts) > 0)
		 {
			 echo '获取消息记录成功<br>'; 
			 foreach($shoppingcarts as $mq_info )
			 {
				 echo '消息记录获得数据ID:'.$mq_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取消息记录失败 :".$e->getMessage() . '<br>'; 
	} 



	try{ 
		$query = XN_Query::create ( 'Message_count' )->tag('message') 
				->filter (  'my.deleted', '=','0') 
				->end(-1); 
		$query->execute(); 
		echo "统计消息记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计消息记录个数失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'Message_count' )->tag('message') 
				//->filter (  'my.deleted', '=','0') 
		   		->rollup()
		        ->group('my.profileid')
				->end(-1);  
		 $statistics = $query->execute(); 
		 echo '分组获取消息记录成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组消息记录获得数据ID:'.$statistic_info->my->toprofileid . ' =>  ' . $statistic_info->my->count . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计消息记录失败 :".$e->getMessage() . '<br>'; 
	}   
	

	try{ 
 	   $newcontent = XN_Content::create('push','',false,6)			
			  ->my->add('deleted','0')
			  ->my->add('status','0') 
			  ->my->add('profileid',"hx5eyjjmlg6")
			  ->my->add('sendid',"hx5eyjjmlg6")
			  ->my->add('viewtime','') 
			  ->my->add('message',"中华人民共和国")
			  ->save($tag);
	   
	   XN_Content::delete($newcontent->id,$tag,6);
	   echo '删除消息记录成功<br>'; 
	}
	catch(XN_Exception $e){
	  echo "删除消息记录失败 :".$e->getMessage() . '<br>'; 
	}   
}

/***************************************************************************************************************/ 
function test_sql()
{
	try{
		 $sql = "update main_mall_products set shop_price='300'  where xn_id = 141356;";
		 $results = XN_Content::create('sql', '',false,5)
		     ->my->add('sql',base64_encode($sql))  
		     ->my->add('method','post')  //get 查询数据进行标签保存   post 进行标签清理 
		     ->my->add('type','mall_products') //sql中无法指明查询表的类型, 使用这个字段指明
			 ->my->add('transaction','true')  //transaction 事务开关 'true'时,开启事务模式,其他值时关闭
		     ->save("mall_products"); 
		 echo '测试SQL: ' . $sql . '成功<br>'; 
	 }
	 catch(XN_Exception $e){
	   echo "测试SQL失败 :".$e->getMessage() . '<br>'; 
	 }	 
 
 
	 try{
		 $sql = "select * from main_mall_products where xn_id = 141356 limit 2;"; 
		 $results = XN_Content::create('sql', '',false,5)
		     ->my->add('sql',base64_encode($sql))  
		     ->my->add('method','post')  //get 查询数据进行标签保存   post 进行标签清理 
		     ->my->add('type','mall_products') //sql中无法指明查询表的类型, 使用这个字段指明
			 ->my->add('transaction','true')  //transaction 事务开关 'true'时,开启事务模式,其他值时关闭
		     ->save("mall_products"); 
		 echo '测试SQL: ' . $sql . '成功<br>';
		 foreach($results as $content_info )
		 {
			 echo '获得数据ID:'.$content_info->id . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "测试SQL失败 :".$e->getMessage() . '<br>'; 
	} 
}
  
/***************************************************************************************************************/ 

function test_index_content()
{
	try{ 
		$profileid = '1234';
		$newcontent = XN_Content::create('profile2fields','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->profileid = $profileid;
		$newcontent->my->fieldid = '10000'; 
		$newcontent->my->fieldname = 'test';
		$newcontent->my->visible = '0'; 
	    $newcontent->my->readonly = '1';  
		$newcontent->save('profile2fields');  
		echo "向全索引表(基础表)插入记录成功 : ".$newcontent->id . '<br>';  
	}
	catch(XN_Exception $e){
	  echo "向插入全索引表(基础表)记录失败 :".$e->getMessage() . '<br>'; 
	}
	try{   
		$newcontent = XN_Content::create('profile2fields','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->profileid = $profileid;
		$newcontent->my->fieldid = array('10000','10001','10002','10003'); 
		$newcontent->my->fieldname = 'test';
		$newcontent->my->visible = '0'; 
	    $newcontent->my->readonly = '1';  
		$newcontent->save('profile2fields');  
		echo "向全索引表(基础表)插入数组记录成功 : ".$newcontent->id . '<br>'; 
		
		$newcontent->my->fieldid = array('1','2','3','4'); 
		$newcontent->save('profile2fields'); 
		echo "向全索引表(基础表)更新数组记录成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "向插入全索引表(基础表)数组记录失败 :".$e->getMessage() . '<br>'; 
	}
	 
	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'profile2fields');
		echo "获取全索引表(基础表)记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取全索引表(基础表)记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'profile2fields'); 
		$info->my->visible = '111'; 
	    $info->my->readonly = '222'; 
		$info->save('profile2fields');
		echo "更新全索引表(基础表)记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新全索引表(基础表)记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$shoppingcarts = XN_Query::create ( 'Content' )->tag('profile2fields' )
							 ->filter ( 'type', 'eic', 'profile2fields' ) 
							 ->filter (  'my.profileid', '=',$profileid) 
							 ->filter (  'my.fieldid', '=','10001') 
							 ->order("published",XN_Order::DESC)
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($shoppingcarts) > 0)
		 {
			 echo '通过数组查询,获取全索引表(基础表)记录成功<br>'; 
			 foreach($shoppingcarts as $shoppingcart_info )
			 {
				 echo '通过数组查询,全索引表(基础表)记录获得数据ID:'.$shoppingcart_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取全索引表(基础表)记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$shoppingcarts = XN_Query::create ( 'Content' )->tag('profile2fields' )
							 ->filter ( 'type', 'eic', 'profile2fields' ) 
							 ->filter (  'my.profileid', '=',$profileid) 
							 ->order("published",XN_Order::DESC)
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($shoppingcarts) > 0)
		 {
			 echo '获取全索引表(基础表)记录成功<br>'; 
			 foreach($shoppingcarts as $shoppingcart_info )
			 {
				 echo '全索引表(基础表)记录获得数据ID:'.$shoppingcart_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取全索引表(基础表)记录失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'Content_count' )->tag('profile2fields')
				->filter ( 'type', 'eic', 'profile2fields')  
				->filter ( 'my.profileid', '=',$profileid) 
				->end(-1); 
		$query->execute(); 
		echo "统计全索引表(基础表)记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计全索引表(基础表)记录个数失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'Content_count' )->tag('profile2fields')
				->filter ( 'type', 'eic', 'profile2fields')  
		   		->rollup()
		        ->group('my.profileid')
				->end(-1);  
		 $statistics = $query->execute(); 
		 echo '分组获取全索引表(基础表)记录成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组全索引表(基础表)记录获得数据ID:'.$statistic_info->my->profileid . ' =>  ' . $statistic_info->my->count . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计全索引表(基础表)记录失败 :".$e->getMessage() . '<br>'; 
	} 
	try{ 
		$newcontent = XN_Content::create('profile2fields','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->profileid = $profileid;
		$newcontent->my->fieldid = array('10000','10001','10002','10003'); 
		$newcontent->my->fieldname = 'test';
		$newcontent->my->visible = '0'; 
	    $newcontent->my->readonly = '1';  
		$newcontent->save('profile2fields');  
	   
	   XN_Content::delete($newcontent->id,"profile2fields");
	   echo '删除全索引表(基础表)记成功<br>'; 
	}
	catch(XN_Exception $e){
	  echo "删除全索引表(基础表)记失败 :".$e->getMessage() . '<br>'; 
	}   
}
  
/***************************************************************************************************************/ 
function test_base_content()
{
	try{ 
		$profileid = '1234';
		$newcontent = XN_Content::create('friends','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->profileid = $profileid;
		$newcontent->my->fieldid = '10000'; 
		$newcontent->my->fieldname = 'test';
		$newcontent->my->visible = '0'; 
	    $newcontent->my->readonly = '1';  
		$newcontent->save('friends');  
		echo "向普通表插入记录成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "向普通表插入记录失败 :".$e->getMessage() . '<br>'; 
	}




	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'friends');
		echo "获取普通表记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取普通表记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'friends'); 
		$info->my->visible = '111'; 
	    $info->my->readonly = '222'; 
		$info->save('friends');
		echo "更新普通表记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新普通表记录失败 :".$e->getMessage() . '<br>'; 
	} 



	try{
		$shoppingcarts = XN_Query::create ( 'Content' )->tag('friends' )
							 ->filter ( 'type', 'eic', 'friends' ) 
							 ->filter (  'my.profileid', '=',$profileid) 
							 ->order("published",XN_Order::DESC)
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute(); 
		 if (count($shoppingcarts) > 0)
		 {
			 echo '获取普通表记录成功<br>'; 
			 foreach($shoppingcarts as $mq_info )
			 {
				 echo '普通表记录获得数据ID:'.$mq_info->id . '<br>'; 
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取普通表记录失败 :".$e->getMessage() . '<br>'; 
	} 



	try{ 
		$query = XN_Query::create ( 'Content_count' )->tag('friends')
				->filter ( 'type', 'eic', 'friends')  
				->filter ( 'my.profileid', '=',$profileid) 
				->end(-1); 
		$query->execute(); 
		echo "统计普通表记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计普通表记录个数失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'Content_count' )->tag('friends')
				->filter ( 'type', 'eic', 'friends')  
		   		->rollup()
		        ->group('my.profileid')
				->end(-1);  
		 $statistics = $query->execute(); 
		 echo '分组获取普通表记录成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组普通表记录获得数据ID:'.$statistic_info->my->profileid . ' =>  ' . $statistic_info->my->count . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计普通表记录失败 :".$e->getMessage() . '<br>'; 
	}   
  
  
	try{ 
		$profileid = '1234';
		$newcontent = XN_Content::create('approvalcenters','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->finished = $profileid;
		$newcontent->my->approver = array('10000','10001','10002','10003'); 
		$newcontent->my->record = 'test';
		$newcontent->my->guid = '0'; 
	    $newcontent->my->flowid = '1';  
		$newcontent->save('approvalcenters');   
		echo "向普通表插入数组记录成功 : ".$newcontent->id . '<br>';  
		
		$newcontent = XN_Content::create('approvalcenters','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->finished = $profileid;
		$newcontent->my->approver = array('10000'); 
		$newcontent->my->record = 'test';
		$newcontent->my->guid = '0'; 
	    $newcontent->my->flowid = '1';  
		$newcontent->save('approvalcenters'); 
		$newcontent->my->approver = array('1','2');  
		$newcontent->save('approvalcenters');    
		echo "向普通表更新数组记录成功 : ".$newcontent->id . '<br>';  
		
		
		$newcontent = XN_Content::create('approvalcenters','',false,4);
		$newcontent->my->tabid = '10000';
		$newcontent->my->finished = $profileid;
		$newcontent->my->approver = array('10000'); 
		$newcontent->my->record = 'test';
		$newcontent->my->guid = '0'; 
	    $newcontent->my->flowid = '1';  
		$newcontent->save('approvalcenters'); 
		$newcontent->my->approver = array('1','2');  
		$newcontent->save('approvalcenters');    
		echo "向普通表更新数组记录成功 : ".$newcontent->id . '<br>';  
	}
	catch(XN_Exception $e){
	  echo "向普通表插入数组记录失败 :".$e->getMessage() . '<br>'; 
	} 

	try{
		$shoppingcarts = XN_Query::create ( 'Content' )->tag('approvalcenters' )
							 ->filter ( 'type', 'eic', 'approvalcenters' ) 
							 ->filter (  'my.finished', '=',$profileid) 
							 ->filter (  'my.approver', '=','10001') 
							 ->order("published",XN_Order::DESC)
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($shoppingcarts) > 0)
		 {
			 echo '通过数组查询,获取普通表记录成功<br>'; 
			 foreach($shoppingcarts as $shoppingcart_info )
			 {
				 echo '通过数组查询,普通表获得数据ID:'.$shoppingcart_info->id . '<br>';
			 }
		 }
	 
	 	$shoppingcarts = XN_Query::create ( 'Content' )->tag('approvalcenters' )
	 						 ->filter ( 'type', 'eic', 'approvalcenters' ) 
	 						 ->filter (  'my.finished', '=',$profileid) 
	 						 ->filter (  'my.approver', 'in', array('10001','10002')) 
	 						 ->order("published",XN_Order::DESC)
	 				   	     ->begin(0)
	 				   	     ->end(5)
	 						 ->execute();
	}
	catch(XN_Exception $e){
	  echo "通过数组查询,获取普通表失败 :".$e->getMessage() . '<br>'; 
	} 
	try{ 
		$profileid = '1234';
		$newcontent = XN_Content::create('approvalcenters','',false);
		$newcontent->my->tabid = '10000';
		$newcontent->my->finished = $profileid;
		$newcontent->my->approver = array('10000','10001','10002','10003'); 
		$newcontent->my->record = 'test';
		$newcontent->my->guid = '0'; 
	    $newcontent->my->flowid = '1';  
		$newcontent->save('approvalcenters');
	   
	   XN_Content::delete($newcontent->id,"approvalcenters");
	   echo '删除普通表记录成功<br>'; 
	}
	catch(XN_Exception $e){
	  echo "删除普通表记录失败 :".$e->getMessage() . '<br>'; 
	}   
} 

/***************************************************************************************************************/ 
function test_yearcontent()
{
	try{
		//如果购物车里面没有这种商品的话，新增 
	
		$productid = '1213';
		$newcontent = XN_Content::create('mall_shoppingcarts','',false,7);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $loginprofileid;
		$newcontent->my->sessionid = session_id(); 
		$newcontent->my->productid = $productid;
		$newcontent->my->propertydesc = ''; 
	    $newcontent->my->quantity = '2'; 
	    $newcontent->my->shop_price = '11.00';
	    $newcontent->my->total_price = '22';
		$newcontent->save('mall_shoppingcarts'); 
	
		$productid = '1212';
		$newcontent = XN_Content::create('mall_shoppingcarts','',false,7);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $loginprofileid;
		$newcontent->my->sessionid = session_id(); 
		$newcontent->my->productid = $productid;
		$newcontent->my->propertydesc = ''; 
	    $newcontent->my->quantity = '2'; 
	    $newcontent->my->shop_price = '22.00';
	    $newcontent->my->total_price = '44';
		$newcontent->save('mall_shoppingcarts'); 
		echo "插入按年存储记录成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "插入按年存储记录失败 :".$e->getMessage() . '<br>'; 
	}


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'mall_shoppingcarts',7);
		echo "获取按年存储记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取按年存储记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'mall_shoppingcarts',7); 
		$info->my->propertydesc  = 'okokok';
		$info->save('mall_shoppingcarts');
		echo "更新按年存储记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新按年存储记录失败 :".$e->getMessage() . '<br>'; 
	} 
	try{
		$shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts' )
							 ->filter ( 'type', 'eic', 'mall_shoppingcarts' ) 
							 ->filter (  'my.productid', '=',$productid)
							 ->filter (  'my.deleted', '=','0')
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($shoppingcarts) > 0)
		 {
			 echo '获取按年存储记录成功<br>'; 
			 foreach($mqs as $mq_info )
			 {
				 echo '按年存储记录获得数据ID:'.$mq_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取按年存储记录失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'YearContent_count' )->tag('mall_shoppingcarts')
				->filter ( 'type', 'eic', 'mall_shoppingcarts') 
				->filter ( 'my.deleted', '=', '0') 
				->filter (  'my.productid', '=',$productid) 
				->end(-1); 
		$query->execute(); 
		echo "统计按年存储记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计按年存储记录个数失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'YearContent_count' )->tag('mall_shoppingcarts')
				->filter ( 'type', 'eic', 'mall_shoppingcarts') 
				->filter ( 'my.deleted', '=', '0')  
		   		->rollup('my.shop_price')
		        ->group('my.productid')
			    ->order("my.shop_price",XN_Order::DESC_NUMBER) 
		 		->order("my.productid",XN_Order::ASC)
				->end(-1);  
		 $statistics = $query->execute(); 
		 echo '分组获取按年存储记录成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组按年存储记录获得数据ID:'.$statistic_info->my->productid . ' =>  ' . $statistic_info->my->shop_price . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计按年存储记录失败 :".$e->getMessage() . '<br>'; 
	}  
	try{ 
		$productid = '1213';
		$newcontent = XN_Content::create('mall_shoppingcarts','',false,7);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $loginprofileid;
		$newcontent->my->sessionid = session_id(); 
		$newcontent->my->productid = $productid;
		$newcontent->my->propertydesc = ''; 
	    $newcontent->my->quantity = '2'; 
	    $newcontent->my->shop_price = '11.00';
	    $newcontent->my->total_price = '22';
		$newcontent->save('mall_shoppingcarts'); 
	   
	   XN_Content::delete($newcontent->id,"mall_shoppingcarts");
	   echo '删除按年存储记录成功<br>'; 
	}
	catch(XN_Exception $e){
	  echo "删除按年存储记录失败 :".$e->getMessage() . '<br>'; 
	}   
}

/***************************************************************************************************************/ 
function test_yearmonthcontent()
{
	try{	
		$productid = '1213';
		$newcontent = XN_Content::create('supplier_messages','',false,9);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $loginprofileid;
		$newcontent->my->sessionid = session_id(); 
		$newcontent->my->productid = $productid;
		$newcontent->my->propertydesc = ''; 
	    $newcontent->my->quantity = '2'; 
	    $newcontent->my->shop_price = '11.00';
	    $newcontent->my->total_price = '22';
		$newcontent->save('supplier_messages'); 
	
		$productid = '1212';
		$newcontent = XN_Content::create('supplier_messages','',false,9);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $loginprofileid;
		$newcontent->my->sessionid = session_id(); 
		$newcontent->my->productid = $productid;
		$newcontent->my->propertydesc = ''; 
	    $newcontent->my->quantity = '2'; 
	    $newcontent->my->shop_price = '22.00';
	    $newcontent->my->total_price = '44';
		$newcontent->save('supplier_messages'); 
		echo "插入按月存储记录成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "插入按月存储记录失败 :".$e->getMessage() . '<br>'; 
	}


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'supplier_messages',9);
		echo "获取按月存储记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取按月存储记录失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'supplier_messages',9); 
		$info->my->propertydesc  = 'okokok';
		$info->save('supplier_messages');
		echo "更新按月存储记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新按月存储记录失败 :".$e->getMessage() . '<br>'; 
	} 
	try{
		$shoppingcarts = XN_Query::create ( 'YearMonthContent' )->tag('supplier_messages' )
							 ->filter ( 'type', 'eic', 'supplier_messages' ) 
							 ->filter (  'my.productid', '=',$productid)
							 ->filter (  'my.deleted', '=','0')
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($shoppingcarts) > 0)
		 {
			 echo '获取按月存储记录成功<br>'; 
			 foreach($mqs as $mq_info )
			 {
				 echo '按月存储记录获得数据ID:'.$mq_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取按月存储记录失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'YearMonthContent_count' )->tag('supplier_messages')
				->filter ( 'type', 'eic', 'supplier_messages') 
				->filter ( 'my.deleted', '=', '0') 
				->filter (  'my.productid', '=',$productid) 
				->end(-1); 
		$query->execute(); 
		echo "统计按月存储记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计按月存储记录个数失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'YearMonthContent_count' )->tag('supplier_messages')
				->filter ( 'type', 'eic', 'supplier_messages') 
				->filter ( 'my.deleted', '=', '0')  
		   		->rollup('my.shop_price')
		        ->group('my.productid')
				->end(-1);  
		 $statistics = $query->execute(); 
		 echo '分组获取按月存储记录成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组按月存储记录获得数据ID:'.$statistic_info->my->productid . ' =>  ' . $statistic_info->my->shop_price . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计按月存储记录失败 :".$e->getMessage() . '<br>'; 
	} 
	try{ 
		$productid = '1212';
		$newcontent = XN_Content::create('supplier_messages','',false,9);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $loginprofileid;
		$newcontent->my->sessionid = session_id(); 
		$newcontent->my->productid = $productid;
		$newcontent->my->propertydesc = ''; 
	    $newcontent->my->quantity = '2'; 
	    $newcontent->my->shop_price = '22.00';
	    $newcontent->my->total_price = '44';
		$newcontent->save('supplier_messages'); 
	   
	   XN_Content::delete($newcontent->id,"supplier_messages",9);
	   echo '删除按月存储记录成功<br>'; 
	}
	catch(XN_Exception $e){
	  echo "删除按月存储记录失败 :".$e->getMessage() . '<br>'; 
	}   
}

/***************************************************************************************************************/ 
function test_bigcontent()
{
	try{ 
		$profileid = 'm0ju5li2541';
		$newcontent = XN_Content::create('loginhistorys','',false,1);
		$newcontent->my->deleted = '0';
		$newcontent->my->profileid = $profileid;
		$newcontent->my->user_ip = '127.0.0.1'; 
		$newcontent->my->user_name = 'admin';
		$newcontent->my->logout_time = ''; 
	    $newcontent->my->login_time = date("Y-m-d H:i:s"); 
	    $newcontent->my->status = 'Signed in'; 
		$newcontent->save('loginhistorys');  
		echo "插入按天存储记录成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "插入按天存储记录失败 :".$e->getMessage() . '<br>'; 
	}


	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'loginhistorys',1);
		echo "获取按天存储记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取按天存储记录失败 :".$e->getMessage() . '<br>'; 
	} 

	/*
	try{
		$contentid = $newcontent->id; 
		$info = XN_Content::load($contentid,'loginhistorys',1); 
		$info->my->logout_time  = date("Y-m-d H:i:s"); 
		$info->save('loginhistorys');
		echo "更新按天存储记录成功 : ".$info->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新按天存储记录失败 :".$e->getMessage() . '<br>'; 
	} 
	*/


	try{
		$shoppingcarts = XN_Query::create ( 'BigContent' )->tag('loginhistorys' )
							 ->filter ( 'type', 'eic', 'loginhistorys' ) 
							 ->filter (  'my.profileid', '=',$profileid)
							 ->filter (  'my.deleted', '=','0')
					   	     ->begin(0)
					   	     ->end(5)
							 ->execute();

		 if (count($shoppingcarts) > 0)
		 {
			 echo '获取按天存储记录成功<br>'; 
			 foreach($mqs as $mq_info )
			 {
				 echo '按天存储记录获得数据ID:'.$mq_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取按天存储记录失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'BigContent_count' )->tag('loginhistorys')
				->filter ( 'type', 'eic', 'loginhistorys') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.profileid', '=',$profileid) 
				->end(-1); 
		$query->execute(); 
		echo "统计按天存储记录个数成功 : ".$query->getTotalCount(). '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "统计按天存储记录个数失败 :".$e->getMessage() . '<br>'; 
	} 

	try{ 
		$query = XN_Query::create ( 'BigContent_count' )->tag('loginhistorys')
				->filter ( 'type', 'eic', 'loginhistorys') 
				->filter ( 'my.deleted', '=', '0') 
		   		->rollup()
		        ->group('my.profileid')
				->end(-1);  
		 $statistics = $query->execute(); 
		 echo '分组获取按天存储记录成功<br>'; 
		 foreach($statistics as $statistic_info )
		 {
			 echo '分组按天存储记录获得数据ID:'.$statistic_info->my->profileid . ' =>  ' . $statistic_info->my->count . '<br>';
		 } 
	}
	catch(XN_Exception $e){
	  echo "分组统计按天存储记录失败 :".$e->getMessage() . '<br>'; 
	} 
}


/***************************************************************************************************************/  
function test_mq()
{
	try{
		$record = '12121212';
		$newcontent = XN_Content::create('test', '',false,2)   
		     ->my->add('productid','11111') 
		     ->my->add('profileid','333333')
		     ->my->add('module','mall_products')
		     ->my->add('action','test')
		     ->my->add('record',$record)
		     ->save("test");
	
		echo "插入消息队列成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "插入消息失败 :".$e->getMessage() . '<br>'; 
	}

	try{
		$mqid = $newcontent->id; 
		$mq_info = XN_Content::load($mqid,'test',2);
		echo "获取消息队列成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "获取消息失败 :".$e->getMessage() . '<br>'; 
	} 


	try{
		$mqid = $newcontent->id; 
		$mq_info = XN_Content::load($mqid,'test',2);
		$mq_info->my->ack = '1';
		$mq_info->my->result  = 'okokok';
		$mq_info->save('test');
		echo "更新消息队列成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "更新消息队列失败 :".$e->getMessage() . '<br>'; 
	} 
	/*
	  对消息队列进行查询操作,可以查询出历史消息是否已经处理完毕
	*/
	try{
		$mqs = XN_Query::create('Mq')->tag("test")
		      ->filter('type','eic','test')
		      ->filter('my.module','=','mall_products')
		      ->filter('my.action','=','test')
		      ->filter('my.record','=',$record)
		      //->filter('my.ack','=','0')
		      ->begin(0)
		      ->end(5)
		      ->execute(); 
		 if (count($mqs) > 0)
		 {
			 echo '获取消息队列成功<br>'; 
			 foreach($mqs as $mq_info )
			 {
				 echo '消息队列获得数据ID:'.$mq_info->id . '<br>';
			 }
		 }
	}
	catch(XN_Exception $e){
	  echo "获取消息队列失败 :".$e->getMessage() . '<br>'; 
	} 
	try{
		$mobile = '15974160308';
		$checkcode = '123456';
		
		$access_key_id = 'LTAIv69hqCMYf6Yv';
		$access_key_secret = 'EtIrTchbzkZ4PSd9IO42uoOy93AjKe'; 
		$signname = '农夫帮';
		//$templatecode = 'SMS_127810013';
		$templatecode = 'SMS_127152802';
		
		
		if (isset($access_key_id) && $access_key_id !="" &&
			isset($access_key_secret) && $access_key_secret !="" &&
			isset($signname) && $signname !="" &&
			isset($templatecode) && $templatecode !="" )
        {  
		   //lock => 1 表示消息队列锁定, 所有的任务全是单一队列排队, 为其他值时(并发类型),表示为多个队列同时排队
		   //async => 1 表示同步队列，只有后台处理完毕后，才会返回结果，为其他值时为异步队列
		   XN_Content::create('sendmobile', '', false, 2)
			            ->my->add('status', 'waiting') 
			            ->my->add('to_mobile', $mobile)
			            ->my->add('verifycode', $checkcode)
				        ->my->add('access_key_id', $access_key_id)
			            ->my->add('access_key_secret', $access_key_secret)
			            ->my->add('signname', urlencode($signname))
			            ->my->add('codename', 'code')
			            ->my->add('templatecode', $templatecode)	
					    ->my->add('lock', '1')	
			            ->save("sendmobile");
		 	            
            echo "发送短信成功 : ".$newcontent->id . '<br>'; 
			die();
        }
        else
        {
	        echo '系统没有配置短信网关参数!<br>"}';
            die(); 
        } 
		echo "插入消息队列成功 : ".$newcontent->id . '<br>'; 
	}
	catch(XN_Exception $e){
	  echo "发送短信失败 :".$e->getMessage() . '<br>'; 
	}
}





