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
	include_once PJ_VIEWS_PATH . 'pjAdminListings/elements/menu.php';
	pjUtil::printNotice(__('infoExportCarsTitle', true), __('infoExportCarsDesc', true));
	
	$export_formats = __('export_formats', true, false);
	$export_types = __('export_types', true, false);
	?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionExport" method="post" id="frmExportCars" class="form pj-form">
		<input type="hidden" name="cars_export" value="1" />
		<p>
			<label class="title"><?php __('lblFormat'); ?></label>
			<span class="inline_block">
				<select name="format" id="format" class="pj-form-field w100">
					<?php
					foreach ($export_formats as $k => $v)
					{
						?><option value="<?php echo $k; ?>"<?php echo isset($_POST['format']) && $_POST['format'] == $k ? ' selected="selected"' : null; ?>><?php echo pjSanitize::html($v); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblType'); ?></label>
			<span class="inline_block t5">
				<span class="block float_left r10">
					<input type="radio" name="type" id="file" value="file"<?php echo isset($_POST['type']) ? ($_POST['type'] == 'file' ? ' checked="checked"' : null) : ' checked="checked"'; ?> class="float_left r3"/>
					<span class="inline_block">
						<label for="file"><?php echo $export_types['file'];?></label>
					</span>
				</span>
				<span class="block float_left">
					<input type="radio" name="type" id="feed" value="feed"<?php echo isset($_POST['type']) ? ($_POST['type'] == 'feed' ? ' checked="checked"' : null) : null; ?> class="float_left r3"/>
					<span class="inline_block">
						<label for="feed"><?php echo $export_types['feed'];?></label>
					</span>
				</span>
			</span>
		</p>
		<p class="tsPassowrdContainer" style="display:<?php echo isset($_POST['type']) ? ($_POST['type'] == 'file' ? ' none' : ' block' ) : ' none'; ?>">
			<label class="title"><?php __('lblEnterPassword');?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-password"></abbr></span>
				<input type="text" id="feed_password" name="password" class="pj-form-field w200" value="<?php echo isset($_POST['password']) ? $_POST['password'] : null; ?>" data-msg-rquired="<?php __('lblFieldRequired');?>"/>
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblCarCreatedModified'); ?></label>
			<span class="inline_block">
				<select name="made_period" id="made_period" class="pj-form-field w150">
					<option value="0">-- <?php __('lblAll');?> --</option>
					<?php
					foreach(__('made_arr', true) as $k => $v)
					{
						?><option value="<?php echo $k;?>"<?php echo isset($_POST['made_period']) ? ($_POST['made_period'] == $k ? ' selected="selected"' : null) : null; ?>><?php echo $v;?></option><?php 
					} 
					?>
				</select>
			</span>
		</p>
		
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" id="tsSubmitButton" value="<?php isset($_POST['type']) ? ($_POST['type'] == 'file' ? __('lblExport') : __('btnGetFeedURL') ) :  __('lblExport'); ?>" class="pj-button" />
		</p>
		<?php
		if(isset($_POST['type']) && $_POST['type'] == 'feed') 
		{
			?>
			<div class="tsFeedContainer">
				<br/>
				<?php pjUtil::printNotice(__('infoCarsFeedTitle', true), __('infoCarsFeedDesc', true)); ?>
				<span class="inline_block">
					<textarea id="listing_feed" name="listing_feed" class="pj-form-field h80" style="width: 726px;"><?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminListings&amp;action=pjActionExportFeed&amp;format=<?php echo$_POST['format']; ?>&amp;period=<?php echo $_POST['made_period']; ?>&amp;p=<?php echo isset($tpl['password']) ? $tpl['password'] : null;?></textarea>
				</span>
			</div>
			<?php
		} 
		?>
	</form>
	
	<br/>
	<div id="export_grid"></div>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.btn_export = "<?php __('lblExport'); ?>";
	myLabel.btn_get_url = "<?php __('btnGetFeedURL'); ?>";
	myLabel.format = "<?php echo pjSanitize::clean(__('lblFormat', true)); ?>";
	myLabel.car_created = "<?php echo pjSanitize::clean(__('lblCarCreatedModified', true)); ?>";
	myLabel.delete_confirm = "<?php __('lblDeleteConfirmation'); ?>";
	myLabel.delete_selected = "<?php __('lblDeleteSelected'); ?>";
	</script>
	<?php
}
?>