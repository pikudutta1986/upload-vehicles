<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	<?php include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php'; ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		<input type="hidden" name="controller" value="pjAdminExtras" />
		<input type="hidden" name="action" value="pjActionCreate" />
		<input type="submit" class="pj-button" value="<?php __('btnAdd'); ?>" />
		<p>&nbsp;</p>
	</form>
	
	<div id="grid"></div>
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.extra = "<?php __('lblExtra'); ?>";
	myLabel.status = "<?php __('lblStatus'); ?>";
	myLabel.active = "<?php __('lblActive'); ?>";
	myLabel.inactive = "<?php __('lblInactive'); ?>";
	myLabel.delete_confirmation = "<?php __('lblDeleteConfirmation'); ?>";
	myLabel.delete_selected = "<?php __('lblDeleteSelected'); ?>";
	</script>
	<?php
}
?>