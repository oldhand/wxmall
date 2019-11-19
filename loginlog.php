<?php

session_start();

require_once (dirname(__FILE__) . "/config.inc.php"); 
 

//获取浏览器信息
function matchbrowser( $Agent, $Patten )
{
    if( preg_match( $Patten, $Agent, $Tmp ) )
    {
        return $Tmp[1];
    }
    else
    {
        return false;
    }
}
function getsharebrowser()
{
    $useragent = $_SERVER["HTTP_USER_AGENT"];
    if( $Browser = matchbrowser( $useragent, "|(myie[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Netscape[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Opera[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(NetCaptor[^;^^()]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(TencentTraveler)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Firefox[0-9/\.^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(MSN[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Lynx[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Konqueror[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(WebTV[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(msie[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Maxthon[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(MicroMessenger[^;^)^(]*)|i" ) );
    else if( $Browser = matchbrowser( $useragent, "|(Scrapy[^;^)^(]*)|i" ) );
    else
    {
        $Browser = '其它';
    }
    return trim( $Browser );
}


//获取操作系统版本

function getsharesystem()
{
    $useragent = $_SERVER["HTTP_USER_AGENT"];
    if( $System = matchbrowser( $useragent, "|(Windows NT[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(Windows Phone[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(Windows[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(iPhone OS[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(Mac[^;^)]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(unix)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(Android[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(Linux[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(SunOS[\ 0-9\.]*)|i" ) );
    else if( $System = matchbrowser( $useragent, "|(BSD[\ 0-9\.]*)|i" ) );
    else
    {
        $System = '其它';
    }
    return trim( $System );
}

if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
    try{
		XN_Application::$CURRENT_URL = "admin";
        $profileid = $_SESSION['profileid'];
        $profile_info=XN_Profile::load($profileid,"id","profile"); 
		
        $lastlogins=XN_Query::Create("Content")
            ->tag("lastloginlog_".$profileid)
            ->filter("type","eic","lastloginlog")
            ->filter("my.profileid","=",$profileid)
            ->filter("my.deleted","=",'0')
            ->end(1)
            ->execute();
        if(count($lastlogins))
		{
            $lastloginlogContent=$lastlogins[0];
            if($lastloginlogContent->my->reg_mobile!=$profile_info->mobile){
                $lastloginlogContent->my->reg_mobile=$profile_info->mobile;
            }
            if($lastloginlogContent->my->gender!=$profile_info->gender){
                if($profile_info->gender!=""){
                    $lastloginlogContent->my->gender=$profile_info->gender;
                }else{
                    $lastloginlogContent->my->gender="";
                }
            }
            if($lastloginlogContent->my->birthdate!=$profile_info->birthdate){
                $lastloginlogContent->my->birthdate=$profile_info->birthdate;
                $lastloginlogContent->my->monthday=date("m-d",strtotime($profile_info->birthdate));
            }
            if($lastloginlogContent->my->province!=$profile_info->province){
                $lastloginlogContent->my->province=$profile_info->province;
            }
            if($lastloginlogContent->my->city!=$profile_info->city){
                $lastloginlogContent->my->city=$profile_info->city;
            }
            $lastloginlogContent->my->logindate=date("Y-m-d H:i:s");
            $lastloginlogContent->my->loginip=$_SERVER['REMOTE_ADDR'];
			$lastloginlogContent->my->appid=$WX_APPID;
            $lastloginlogContent->my->browser = getsharebrowser();
            $lastloginlogContent->my->system = getsharesystem();
            $lastloginlogContent->save("lastloginlog,lastloginlog_".$profileid);
        }
		else
		{
            $lastloginlogContent=XN_Content::create("lastloginlog","",false);
            $lastloginlogContent->my->deleted='0';
            $lastloginlogContent->my->reg_mobile=$profile_info->mobile;
            $lastloginlogContent->my->gender=$profile_info->gender;
            $lastloginlogContent->my->birthdate=$profile_info->birthdate;
            $lastloginlogContent->my->monthday=date("m-d",strtotime($profile_info->birthdate));
            $lastloginlogContent->my->province=$profile_info->province;
            $lastloginlogContent->my->city=$profile_info->city;
            $lastloginlogContent->my->profileid=$profileid;
            $lastloginlogContent->my->logindate=date("Y-m-d H:i:s");
            $lastloginlogContent->my->loginip=$_SERVER['REMOTE_ADDR']; 
			$lastloginlogContent->my->appid=$WX_APPID;
            $lastloginlogContent->my->browser = getsharebrowser();
            $lastloginlogContent->my->system = getsharesystem();
            $lastloginlogContent->save("lastloginlog,lastloginlog_".$profileid);
        }
		
       /* $content = XN_Content::create('loginlogs',"",false)
            ->my->add('profileid',$profileid)
            ->my->add('logindate',date("Y-m-d H:i:s"))
            ->my->add('loginip',$_SERVER['REMOTE_ADDR'])
            ->my->add("browser",getsharebrowser())
            ->my->add("system",getsharesystem())
            ->save("loginlogs"); */
    }
    catch(XN_Exception $e){

    }

}