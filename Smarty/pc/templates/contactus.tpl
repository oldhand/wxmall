<!DOCTYPE html>
<html lang='zh-cn' class='m-company m-company-index'>
<head>
    <meta charset="utf-8">
    <title>{$supplier_info.suppliername}</title>
    <meta name="keywords" content="{$supplier_info.suppliername}">
    <meta name="description" content="{$supplier_info.suppliername}"> 
      <link href="/public/pc/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/pc/css/public.css" rel="stylesheet">
  <link href="/public/pc/css/index.css" rel="stylesheet">
  <link href="/public/pc/css/iconfont.css" rel="stylesheet">
	<script>
	    {literal}
		var config={"webRoot":"\/","cookieLife":30,"requestType":"PATH_INFO","requestFix":"-","moduleVar":"m","methodVar":"f","viewVar":"t","defaultView":"html","themeRoot":"\/theme\/","currentModule":"company","currentMethod":"index","clientLang":"zh-cn","requiredFields":"","save":"\u4fdd\u5b58","router":"\/index.php","runMode":"front","langCode":""}
		if(typeof(v) != "object") v = {};v.theme = {"template":"default","theme":"wide","device":"desktop"};
	    {/literal}
	</script>
	{include file='header.tpl'}
	<style>
	 {literal}
	.article-content{padding: 0}
	 {/literal}
	</style>  
</head>
<body>	
<div class='container'>  
   
  <div class='page-wrapper'>
    <div class='page-content'>
      <div class='blocks' data-region='all-banner'></div>
      <ul class="breadcrumb"><li><span class='breadcrumb-title'>当前位置：</span><a href='/' >首页</a>
      </li><li>关于我们</li></ul>
      <div class='row blocks' data-region='company_index-topBanner'></div>
      <div class='row' id='columns' data-page='company_index'>
      <div class="col-md-8 col-main">
        <div class='row blocks' data-region='company_index-top'></div>
        <div style="width:697px;height:400px;border:#ccc solid 1px;margin-bottom: 100px;" id="dituContent"></div>
        <div class='row blocks' data-region='company_index-bottom'></div>
      </div>
      <div class='col-md-4 col-side'>
      <side class='page-side blocks' data-region='company_index-side'>
      <div id="block10" class='panel-block-contact panel panel-block block-system-contact'>
      <div class='panel-heading'>
        <h2><i class='icon panel-icon icon-phone'></i> 联系我们</h2>
      </div>
      <div class='panel-body'>
      <div id='companyContact10' data-ve='companyContact'>
        <table class='table table-data'>
          <tr>
            <th>企业名称：</th>
            <td>{$supplier_info.suppliername}</td>
          </tr>
          <tr>
            <th>企业地址：</th>
            <td>{$supplier_info.address}</td>
          </tr>
          <tr>
            <th>Email：</th>
            <td>alan@ttzan.com</td>
          </tr>
          <tr>
            <th>QQ：</th>
            <td><a href='http://wpa.qq.com/msgrd?v=3&uin=2169742861&site=测试&menu=yes' target='_blank'>2169742861</a>
            </td>
          </tr>
          <tr>
            <th>微信：</th>
            <td>行云特赞公众号</td>
          </tr>
          <tr>
            <th>微博：</th>
            <td><a href='http://weibo.com/u/5865910392?from=myfollow_all&is_all=1#_loginLayer_1482818811250' target='_blank'>@行云无锡</a>
          </td>
          </tr>
          <tr>
            <th>地址：</th>
            <td>江苏省无锡市新区国家软件园金牛座</td>
          </tr>
          <tr>
            <th>联系方式：</th>
            <td>0510-85381010</td>
          </tr>
        </table>
        <a href="potenitalsuppliers.php"><button type="button" class="btn btn-danger" style="width: 100%;height: 45px;margin: 0 auto;">商城合作</button></a>
      </div>
    </div>
  </div>
  </side></div>
    </div>
  <div class='row blocks' data-region='company_index-bottomBanner'></div>
    <div class='blocks all-bottom' data-region='all-bottom'></div>
  </div>
</div>
</div>  
<script>
{literal}
	v.lang = {"confirmDelete":"\u60a8\u786e\u5b9a\u8981\u6267\u884c\u5220\u9664\u64cd\u4f5c\u5417\uff1f","deleteing":"\u5220\u9664\u4e2d","doing":"\u5904\u7406\u4e2d","loading":"\u52a0\u8f7d\u4e2d","updating":"\u66f4\u65b0\u4e2d...","timeout":"\u7f51\u7edc\u8d85\u65f6,\u8bf7\u91cd\u8bd5","errorThrown":"<h4>\u6267\u884c\u51fa\u9519\uff1a<\/h4>","continueShopping":"\u7ee7\u7eed\u8d2d\u7269","required":"\u5fc5\u586b","back":"\u8fd4\u56de","continue":"\u7ee7\u7eed"};;
{/literal} 
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>
<script type="text/javascript">
var x = {$supplier_info.longitude},y = {$supplier_info.latitude};
{literal}
    //创建和初始化地图函数：
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
    }
    
    //创建地图函数：
    function createMap(){
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        var point = new BMap.Point(x,y);//定义一个中心点坐标
        map.centerAndZoom(point,9);//设定地图的中心点和坐标并将地图显示在地图容器中
        window.map = map;//将map变量存储在全局
    }
    
    //地图事件设置函数：
    function setMapEvent(){
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
        map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard();//启用键盘上下左右键移动地图
    }
    
    //地图控件添加函数：
    function addMapControl(){
        //向地图中添加缩放控件
  var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
  map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
  var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
  map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
  var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
  map.addControl(ctrl_sca);
    }
    
    
    initMap();//创建和初始化地图
  {/literal}
</script>
{include file='footer.tpl'} 

</body>
</html>
 