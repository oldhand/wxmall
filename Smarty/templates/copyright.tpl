<style>
<!--  
   #copyright {ldelim}  margin: 3px 3px; margin-bottom:10px;{rdelim}
   #copyright .mui-table-view {ldelim} background-color: #efeff4; {rdelim}
   #copyright .mui-table-view-cell {ldelim} width:310px;margin:0 auto {rdelim}
   #copyright .mui-table-view .mui-media-object.mui-pull-left {ldelim}  margin-right: 0px; margin-top: 0px; {rdelim} 
   #copyright .icon-logo {ldelim} font-size: 3.0em;padding-right: 5px;color: {$supplier_info.buttoncolor};; {rdelim} 
   #copyright .mui-ellipsis {ldelim} color:#000; {rdelim} 
   #copyright .tezan {ldelim}font-size:9px;font-family:Arial Narrow,Arial; {rdelim} 
   #copyright .tezan a {ldelim}font-size:9px;font-family:Arial Narrow,Arial; {rdelim} 
-->
</style>
<div id="copyright" class="mui-card">
    <ul class="mui-table-view">
        <li class="mui-table-view-cell mui-media">   
			{assign var="copyrights" value=$supplier_info.copyrights} 
					 <div class="mui-media-object mui-pull-left"><span class="mui-icon iconfont {$copyrights.logo} menuicon" style="font-size: 3.0em;"></span></div>
                    <div class="mui-media-body"> 
	                    <p class='mui-ellipsis' style="padding-left:5px">{$copyrights.trademark}提供技术支持</p>
                           <p class='mui-ellipsis tezan' style="padding-left:5px">Copyright © 2010-2018  <a href="http://www.{$copyrights.site}">{$copyrights.site}</a> All Rights Reserved.</span></p> 
                    
                    </div> 
        </li>
		
    </ul>
</div> 

 