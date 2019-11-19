{if $supplier_info.themecolor neq ''}
	<style>
	<!-- 
	   header.mui-bar {ldelim}background-color: {$supplier_info.themecolor}; {rdelim}
	   header .mui-title, header a {ldelim} color: {$supplier_info.textcolor}; {rdelim} 
	   .mui-grid-view.mui-grid-9 .mui-media {ldelim} color: {$supplier_info.themecolor}; {rdelim}
	   .menuicon {ldelim} color: {$supplier_info.themecolor}; {rdelim}
	   .mui-searchbar {ldelim} background-color: {$supplier_info.themecolor}; {rdelim} 
	   .tishi {ldelim} color: {$supplier_info.themecolor}; {rdelim}
	   h5.show-content {ldelim} background: {$supplier_info.themecolor}; color: {$supplier_info.textcolor};{rdelim}
	   .mui-segmented-control-negative.mui-segmented-control-inverted .mui-control-item.mui-active {ldelim} color: {$supplier_info.themecolor};  border-bottom: 2px solid {$supplier_info.themecolor};{rdelim}
	   .button-color {ldelim}  color: {$supplier_info.buttoncolor};  {rdelim} 
	   .mui-table-view input[type='radio']:checked:before,.mui-radio input[type='radio']:checked:before, .mui-checkbox input[type='checkbox']:checked:before {ldelim}  color: {$supplier_info.buttoncolor};  {rdelim} 
	    
	    .mui-table-view.mui-grid-view .singlerow.mui-table-view-cell {ldelim} background-color:{$supplier_info.productbackgroundcolor};  {rdelim}
		.mui-table-view.mui-grid-view .doublerow.mui-table-view-cell {ldelim} background-color:{$supplier_info.productbackgroundcolor};  {rdelim}
		.mui-table-view.mui-grid-view .singlerow.mui-table-view-cell .cp_miaoshu {ldelim} color:{$supplier_info.textcolor};  {rdelim}
		#list.mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {ldelim} color: {$supplier_info.textcolor}; {rdelim}
		.cnt ul .wg1 {ldelim} border-right: 1px solid {$supplier_info.textcolor}; {rdelim}
	    .cnt ul .wg2 {ldelim} border-right: 1px solid {$supplier_info.textcolor}; {rdelim}
		.cnt {ldelim} background-color: {$supplier_info.productbackgroundcolor}; {rdelim}
		{if $supplier_info.themecolor eq $supplier_info.buttoncolor }
		 	.special-button-color {ldelim}  color: #fff;  {rdelim} 
		{else}
		 	.special-button-color {ldelim}  color: {$supplier_info.buttoncolor};  {rdelim} 
		{/if} 
		.singlerow .price2, .doublerow .price2 {ldelim}  color: {$supplier_info.productpricecolor};  {rdelim}  
		{if $supplier_info.footerstyle eq '1'}   
			.mui-bar-tab .mui-tab-item.mui-active {ldelim} color: {$supplier_info.textcolor}; background-color: {$supplier_info.selectnavbarcolor}; {rdelim}
			.mui-bar-tab .mui-tab-item.confirmpayment {ldelim} color: {$supplier_info.textcolor}; background-color: {$supplier_info.selectnavbarcolor}; {rdelim}
			.mui-bar-tab .mui-tab-item  {ldelim} color: {$supplier_info.navbarcolor}; {rdelim}  
		{else} 
			.mui-bar-tab .mui-tab-item.mui-active {ldelim} color: {$supplier_info.selectnavbarcolor}; {rdelim}
			.mui-bar-tab .mui-tab-item.confirmpayment {ldelim} color: {$supplier_info.selectnavbarcolor}; {rdelim}
			.mui-bar-tab .mui-tab-item  {ldelim} color: {$supplier_info.navbarcolor}; {rdelim}  
		{/if}
	-->
	</style>
	
{/if}