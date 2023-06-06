<?php
$active = " ui-tabs-active ui-state-active";
?>
<style type="text/css">
.ui-widget-content{
	/*border: medium none;*/
}
</style>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminListings' && in_array($_GET['action'], array('pjActionIndex', 'pjActionCreate')) ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionIndex"><?php __('menuCars'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminListings' && in_array($_GET['action'], array('pjActionExport')) ? $active : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionExport"><?php __('lblExport'); ?></a></li>
	</ul>
</div>