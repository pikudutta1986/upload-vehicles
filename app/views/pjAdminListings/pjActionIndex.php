<?php
// echo '<pre>';
// print_r($tpl);
// print_r($tpl["session"]["admin_user"]["id"]);
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
	if($controller->isAdmin())
	{
		include_once PJ_VIEWS_PATH . 'pjAdminListings/elements/menu.php';
	}
	pjUtil::printNotice(__('infoCarsTitle', true, false), __('infoCarsDesc', true, false));
	?>
	<div class="b10">
		<?php
		if(!$controller->isOwner() || ($controller->isOwner() && $tpl['option_arr']['o_allow_adding_car'] == 'Yes'))
		{
			?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="float_left pj-form r10">
				<input type="hidden" name="controller" value="pjAdminListings" />
				<input type="hidden" name="action" value="pjActionCreate" />
				<input type="submit" class="pj-button" value="<?php __('btnAddCar'); ?>" />
			</form>
			<?php
		} 
		?>
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch'); ?>" />
			<button type="button" class="pj-button pj-button-detailed"><span class="pj-button-detailed-arrow"></span></button>
		</form>
		
		<?php
		$filter = __('filter', true);
		?>
		<?php
			$dralerName = $tpl["session"]["admin_user"]["name"];
		?>
		<div class="float_right t5">
			<a href="https://www.uploadvehicles.com/inventory/<?php echo $dralerName; ?>.txt" target="_blank" class="pj-button">Get Data</a>
			<a href="#" class="pj-button btn-all"><?php __('lblAll');?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="T"><?php echo $filter['active']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="status" data-value="F"><?php echo $filter['inactive']; ?></a>
		</div>
		<br class="clear_both" />
	</div>
	
	<div class="pj-form-filter-advanced" style="display: none">
		<span class="pj-menu-list-arrow"></span>
		<form action="" method="get" class="form pj-form pj-form-search frm-filter-advanced">
			<div class="float_left w350">
				<p>
					<label class="title title100"><?php __('lblListingRefid'); ?></label>
					<input type="text" name="listing_refid" id="listing_refid" class="pj-form-field w150" />
				</p>
				<p>
					<label class="title title100"><?php __('lblMake'); ?></label>
					<select name="make_id" id="make_id" class="pj-form-field w150">
						<option value="">-- <?php __('lblChoose'); ?> --</option>
						<?php
						foreach ($tpl['make_arr'] as $v)
						{
							?><option value="<?php echo $v['id']; ?>"<?php echo isset($_GET['make_id']) && (int) $_GET['make_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['name']); ?></option><?php
						}
						?>
					</select>
				</p>
				<p>
					<label class="title title100"><?php __('lblModel'); ?></label>
					<span id="model_container" class="inline_block">
						<select name="model_id" id="model_id" class="pj-form-field w150">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
						</select>
					</span>
				</p>
				<p>
					<label class="title title100"><?php __('lblYear'); ?></label>
					<select name="year_from" id="year_from" class="pj-form-field w94">
						<option value="">--</option>
						<?php
						$y = date("Y");
						foreach (range($y, 1900) as $v)
						{
							?><option value="<?php echo $v; ?>"><?php echo $v; ?></option><?php
						}
						?>
					</select>
					&nbsp;<?php __('lblTo');?>&nbsp;
					<select name="year_to" id="year_to" class="pj-form-field w94">
						<option value="">--</option>
						<?php
						$y = date("Y");
						foreach (range($y, 1900) as $v)
						{
							?><option value="<?php echo $v; ?>"><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</p>
				<p>
					<label class="title title100"><?php __('lblPower'); ?></label>
					<input type="text" name="power_from" id="power_from" class="pj-form-field w80" />
					&nbsp;<?php __('lblTo');?>&nbsp;
					<input type="text" name="power_to" id="power_to" class="pj-form-field w80" />
				</p>
				<p>
					<label class="title title100"><?php __('lblListingMileage'); ?></label>
					<select name="mileage_from" id="mileage_from" class="pj-form-field w94">
						<option value="">--</option>
						<?php
						$range = array(0, 10000, 20000, 50000, 100000, 250000, 500000);
						foreach ($range as $v)
						{
							?><option value="<?php echo $v; ?>"><?php echo number_format($v); ?></option><?php
						}
						?>
					</select>
					&nbsp;<?php __('lblTo');?>&nbsp;
					<select name="mileage_to" id="mileage_to" class="pj-form-field w94">
						<option value="">--</option>
						<?php
						$y = date("Y");
						foreach ($range as $v)
						{
							?><option value="<?php echo $v; ?>"><?php echo number_format($v); ?></option><?php
						}
						?>
					</select>
				</p>
				<p>
					<label class="title title100">&nbsp;</label>
					<input type="submit" value="<?php __('btnSearch'); ?>" class="pj-button" />
					<input type="reset" value="<?php __('btnCancel'); ?>" class="pj-button" />
				</p>
			</div>
			<div class="float_right w350">
				<?php
				$items = array();
				$feature_types = __('feature_types', true);
				foreach ($feature_types as $k => $v)
				{
					foreach ($tpl['feature_arr'] as $feature)
					{
						if ($feature['type'] == $k)
						{
							if (array_key_exists($k, $items))
							{
								$items[$k]++;
							} else {
								$items[$k] = 1;
							}
						}
					}
				}
				foreach ($feature_types as $k => $v)
				{
					if (isset($items[$k]) && $items[$k] > 0)
					{
						?>
						<p>
							<label class="title"><?php echo stripslashes($v); ?></label>
							<span id="boxFeature<?php echo $k;?>" class="inline-block">
								<select name="feature_<?php echo $k; ?>_id" id="feature_<?php echo $k; ?>_id" class="pj-form-field w150">
									<option value="">-- <?php __('lblChoose'); ?> --</option>
									<?php
									foreach ($tpl['feature_arr'] as $feature)
									{
										if ($feature['type'] == $k)
										{
											
											?><option value="<?php echo $feature['id']; ?>"><?php echo stripslashes($feature['name']); ?></option><?php
											
										}
									}
									?>
								</select>
							</span>
						</p>					
						<?php
					}
				} 
				?>
			</div>			
			<br class="clear_both" />
		</form>
		<span id="model_clone" class="inline_block" style="display:none;">
			<select name="model_id" id="{model_clone_id}" class="pj-form-field w150">
				<option value="">-- <?php __('lblChoose'); ?> --</option>
			</select>
		</span>
	</div>
	
	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jqDateFormat = "<?php echo pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	pjGrid.queryString = "";
	pjGrid.isOwner = <?php echo $controller->isOwner() ? 'true' : 'false'; ?>;
	<?php
	if (isset($_GET['user_id']) && (int) $_GET['user_id'] > 0)
	{
		?>pjGrid.queryString += "&user_id=<?php echo (int) $_GET['user_id']; ?>";<?php
	}
	?>
	var myLabel = myLabel || {};
	myLabel.exp_date_plus_30 = "<?php __('cl_exp_date_plus_30'); ?>";
	myLabel.image = "<?php __('cl_image'); ?>";
	myLabel.ref_id = "<?php __('lblListingRefid'); ?>";
	myLabel.details = "<?php __('lblDetails'); ?>";
	myLabel.owner = "<?php __('cl_owner'); ?>";
	myLabel.expire = "<?php __('cl_expire'); ?>";
	myLabel.publish = "<?php __('cl_publish'); ?>";
	myLabel.active = "<?php __('lblActive'); ?>";
	myLabel.inactive = "<?php __('lblInactive'); ?>";
	myLabel.exp_date = "<?php __('cl_exp_date'); ?>";
	myLabel.delete_selected = "<?php __('lblDeleteSelected'); ?>";
	myLabel.published = "<?php __('cl_published'); ?>";
	myLabel.not_published = "<?php __('cl_not_published'); ?>";
	myLabel.extend_exp_date = "<?php __('cl_extend_exp_date'); ?>";
	myLabel.delete_confirm = "<?php __('lblDeleteConfirmation'); ?>";
	</script>
	<?php
}
?>