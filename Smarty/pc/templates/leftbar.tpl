  <!--左侧悬浮导航-->
  <div class="btn-group-left">
    <img id="menu-start" src="/public/pc/images/festive1.png" width="72" />
    <ul>
	 {foreach name="categorys" item=category_info from=$supplier_info.categorys} 
	     <li>
	       <div class="btn-grout-icon">
	         <div class="grout-inner">
	          <span class="grout-t">{$category_info.name}</span>
	           <span class="grout-b">{$category_info.name}</span>
	         </div>
	       </div>
	       <div class="leftsubmenu leftnavmenu">
	         <h4>
	           <span class="m-r5">
	              <span class="iconfont icon-chanpinfenlei" style="display:inline-block;margin-top:0px;font-size: 2.0em;"></span>
	           	  {$category_info.name}
		   		  </span>
	         </h4>
			 {assign var="childcategorys" value=$category_info.childs}
	         <div class="menu-list">
	           <ul>
				{if $childcategorys|@count gt 0}
		             <li>
		               |
		               <a href="search.php?categoryid={$category_info.id}" class="fw">全部</a>
		             </li>
				    {foreach name="childcategorys" item=childcategory_info from=$childcategorys} 
	                   <li>
	                     |
	                     <a href="search.php?categoryid={$childcategory_info.id}">{$childcategory_info.name}</a>
	                   </li> 
					{/foreach}
				{else}
		             <li>
		               |
		               <a href="search.php?categoryid={$category_info.id}" class="fw">{$category_info.name}</a>
		             </li>
				{/if} 
	           </ul>
	         </div>
	       </div>
	     </li>
	 {/foreach}  
    </ul>
  </div>
