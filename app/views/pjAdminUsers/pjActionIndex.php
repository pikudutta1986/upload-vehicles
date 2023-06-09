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
	$u_statarr = __('u_statarr', true);
	pjUtil::printNotice(__('infoUsersTitle', true, false), __('infoUsersDesc', true, false));
	?>
	<div class="b10">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
			<input type="hidden" name="controller" value="pjAdminUsers" />
			<input type="hidden" name="action" value="pjActionCreate" />
			<input type="submit" class="pj-button" value="<?php __('btnAddUser'); ?>" />
		</form>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
		</form>
		<?php
		$filter = __('filter', true);
		?>
		<div class="float_right t5">
			<a href="#" class="pj-button btn-all">All</a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="T"><?php echo $filter['active']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="F"><?php echo $filter['inactive']; ?></a>
		</div>
		<br class="clear_both" />
	</div>

	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.currentUserId = <?php echo (int) $_SESSION[$controller->defaultUser]['id']; ?>;
	pjGrid.isEditor = <?php echo $controller->isEditor() ? 'true' : 'false'; ?>;
	var myLabel = myLabel || {};
	myLabel.name = "<?php __('lblName'); ?>";
	myLabel.email = "<?php __('email'); ?>";
	myLabel.created = "<?php __('lblUserCreated'); ?>";
	myLabel.role = "<?php __('lblRole'); ?>";
	myLabel.confirmed = "<?php __('lblIsActive'); ?>";
	myLabel.revert_status = "<?php __('revert_status'); ?>";
	myLabel.exported = "<?php __('lblExport'); ?>";
	myLabel.active = "<?php echo $u_statarr['T']; ?>";
	myLabel.inactive = "<?php echo $u_statarr['F']; ?>";
	myLabel.delete_confirmation = "<?php __('lblDeleteConfirmation'); ?>";
	myLabel.delete_selected = "<?php __('lblDeleteSelected'); ?>";
	myLabel.status = "<?php __('lblStatus'); ?>";
	</script>
	<?php
}
?>