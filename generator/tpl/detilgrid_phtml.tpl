<div id="<!--__PANELNAME__-->">
	<div class="fgta-page-title">Daftar <!--__PAGETITLE__--></div>
	<div id="<!--__PANELNAME__-->-title" class="fgta-page-subtitle">XXXXXXX</div>

	<!--// Untuk keperluan search di detil
	<div id="<!--__PANELNAME__-->-pnl_head" style="display: flex; align-items: flex-end">
		<div class="list-search-wrap" style="width: calc(100% - 65px); display: flex;">
			<div class="list-search-item" style="width: 50px;">Cari</div>
			<div class="list-search-item">
				<input id="<!--__PANELNAME__-->-txt_search" class="easyui-textbox" style="width: 100%">
			</div>
			<div class="list-search-item" style="width: 45px">
				<a href="javascript:void(0)" id="<!--__PANELNAME__-->-btn_load" class="easyui-linkbutton c8 fgta-button-listload" style="width: 45px">Load</a>
			</div>
		</div>
	</div>	
	//-->


	<?php
	$customheadpanel = __DIR__.'/<!--__CUSTOMHEADPANELINC__-->';
	if (is_file($customheadpanel)) {
		include $customheadpanel;
	} 
	?>

	<div style="margin-top: 10px; margin-bottom: 15px">
		<table id="<!--__PANELNAME__-->-tbl_list" paging="true" cellspacing="0" width="100%" class="deftable">
			<?php
			$customview = __DIR__.'/<!--__CUSTOMVIEWINC__-->'; 
			if (is_file($customview)) {
				include $customview;
			} else { ?>
			<thead>

				<tr>
<!--__HEADERMAPCHK__-->					
<!--__HEADERMAP__-->
					<th mapping=""></th>
				</tr>
				<tr style="background-color: #cccccc; height: 30px">
<!--__HEADERROWCHK__-->
<!--__HEADERROW__-->
					<td class="fgtable-head" style="border-bottom: 1px solid #000000; text-align: center">&nbsp;</td>
				</tr>
			</thead>
			<?php } ?>
			
			<?php
			$tablefooter = __DIR__.'/<!--__TABLEFOOTERINC__-->';
			if (is_file($tablefooter)) {
				include $tablefooter;
			} 
			?>
			
		</table>
	</div>



	<div class="<!--__PANELNAME__-->-control" style="display: flex; justify-content: space-between; margin-bottom: 100px">
		<div>
			<a id="<!--__PANELNAME__-->-removechecked" href="javascript:void(0)" class="easyui-linkbutton c8 fgta-button-rowdel">Hapus Checked</a>
		</div>

		<div>
			<a id="<!--__PANELNAME__-->-addrow" href="javascript:void(0)" class="easyui-linkbutton c8 fgta-button-rowadd">Tambah Baris</a>
		</div>

		<div>
		</div>

	</div>



	<?php
	$customfootpanel = __DIR__.'/<!--__CUSTOMFOOTPANELINC__-->';
	if (is_file($customfootpanel)) {
		include $customfootpanel;
	} 
	?>

</div>	
