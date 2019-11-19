<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


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
	$profileid = "anonymous";
}

if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '<span class="mui-icon iconfont icon-tezan"></span>0';  
	die();  
}

if(isset($_REQUEST['productid']) && $_REQUEST['productid'] !='')
{
	$productid = $_REQUEST['productid']; 
}
else
{
	echo '<span class="mui-icon iconfont icon-tezan"></span>0';  	  
	die();
}	

	
try 
{  
	    $praises = XN_Query::create('Content')->tag('praises_'.$supplierid)
						->filter('type','eic','praises')
						->filter('my.productid','=',$productid) 
						->filter('my.profileid','=',$profileid) 
						->begin(0)->end(1) 
						->execute();
		if (count($praises) == 0)
		{ 							 		
				$newcontent = XN_Content::create('praises','',false);					  
				$newcontent->my->productid = $productid;
				$newcontent->my->profileid = $profileid; 
				$newcontent->my->supplierid = $supplierid;  
				$newcontent->my->type = 'o2o';								
				$newcontent->save('praises,praises_'.$supplierid);  
				
				$query = XN_Query::create('Content_Count') ->tag('praises_'.$supplierid)
						->filter('type','eic','praises') 
						->filter('my.productid','=',$productid)  			
						->rollup() 
						->begin(0)
						->end(-1);
				$query->execute();
				$count = $query->getTotalCount(); 
				$loadcontent = XN_Content::load($productid,"mall_products_".$supplierid);   
				$loadcontent->my->praise = $count;
				$loadcontent->save("mall_products,mall_products_".$supplierid);  
				XN_MemCache::put($count,"praises_".$productid);
				echo '<span class="mui-icon iconfont icon-tezan" style="color:#c30"></span>'.$count;  
				die();
		} 
		else
		{ 
		    try{
		        $count = XN_MemCache::get("praises_".$productid);  
  				echo '<span class="mui-icon iconfont icon-tezan" style="color:#c30"></span>'.$count;  
  				die();
		    }
		    catch(XN_Exception $e)
			{
				$query = XN_Query::create('Content_Count') ->tag('praises_'.$supplierid)
						->filter('type','eic','praises') 
						->filter('my.productid','=',$productid)  				
						->rollup() 
						->begin(0)
						->end(-1);
				$query->execute();
				$count = $query->getTotalCount();  
				XN_MemCache::put($count,"praises_".$productid);
  				echo '<span class="mui-icon iconfont icon-tezan" style="color:#c30"></span>'.$count;  
  				die();
			} 
		} 
				 
}
catch ( XN_Exception $e ) 
{  
	echo '<span class="mui-icon iconfont icon-tezan"></span>0';  	  
	die(); 
} 
 
?>