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

	

	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;

	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);

	if($controller->isAdmin())

	{

		include_once PJ_VIEWS_PATH . 'pjAdminListings/elements/menu.php';

	}

	?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionCreate" method="post" id="frmCreateListing" class="form pj-form">

		<input type="hidden" name="listing_create" value="1" />

		<?php

		if($controller->isOwner())

		{ 

			pjUtil::printNotice(__('infoOwnerListingAddTitle', true), __('infoOwnerListingAddBody', true));

		}else{

			pjUtil::printNotice(__('lblListingAddTitle', true), __('lblListingAddDesc', true));

		} 

		?>

		<p>

			<label class="title"><?php __('lblListingRefid'); ?></label>

			<span class="inline_block">

				<input type="text" name="listing_refid" id="listing_refid" class="pj-form-field required" value="<?php echo pjUtil::uuid(); ?>" />

			</span>

		</p>

		<p style="overflow: visible; display: none;">

			<label class="title"><?php __('lblListingType'); ?></label>

			<span class="inline_block">

				<select name="car_type" id="car_type" class="pj-form-field w150 required">

					<option value="used">Used cars</option>

					<?php /*

					foreach (__('car_types', true) as $k => $v)

					{

						if($k == 'used') {

							?><option value="<?php echo $k; ?>" selected><?php echo stripslashes($v); ?></option><?php
						} else {
							?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php
						}

					}

				*/	?>

				</select>

			</span>

		</p>

		<p>
			<label class="title">Vin</label>
			<span class="inline_block">
				<input type="text" name="vin" id="vin" class="pj-form-field required" value="" />
			</span>
		</p>

		<p>
			<label class="title">Year</label>
			<span class="inline_block">
				<input type="text" name="year" id="year" class="pj-form-field required" value="" />
			</span>
		</p>

		<p>

			<label class="title"><?php __('lblListingMake'); ?></label>

			<span class="inline_block">

				<select name="make_id" id="make_id" class="pj-form-field w150 required">

					<option value="">-- <?php __('lblChoose'); ?> --</option>

					<?php

					foreach ($tpl['make_arr'] as $v)

					{

						?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php

					}

					?>

				</select>

			</span>

		</p>

		<p>

			<label class="title"><?php __('lblListingModel'); ?></label>

			<span id="model_container" class="inline_block">

				<select name="model_id" id="model_id" class="pj-form-field w150 required">

					<option value="">-- <?php __('lblChoose'); ?> --</option>

				</select>

			</span>

		</p>

		<?php

		if (!$controller->isOwner())

		{

			?>

			<p style="display: none;">

				<label class="title"><?php __('lblListingStatus'); ?></label>

				<span class="inline_block">

					<select name="status" id="status" class="pj-form-field w200 required">

						<option value="">-- <?php __('lblChoose'); ?> --</option>

						<?php

						foreach (__('publish_status', true) as $k => $v)

						{

							?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php

						}

						?>

					</select>

					<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblListingStatusTip'); ?>"></a>

				</span>

			</p>

			<p id="expiration_container" style="display: none;">

				<label class="title"><?php __('lblListingExpire'); ?></label>

				<span class="pj-form-field-custom pj-form-field-custom-after">

					<input type="text" name="expire" id="expire" class="pj-form-field pointer w80 datepick" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo date($tpl['option_arr']['o_date_format']); ?>" />

					<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>

					<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblListingExpireTip'); ?>"></a>

				</span>

			</p>

			<p style="overflow: visible; display: none;">

				<label class="title"><?php __('lblListingOwner'); ?></label>

				<span class="inline_block">

					<span class="block float_left r5">

						<select name="owner_id" id="owner_id" class="pj-form-field w200 required">

							<option value="">-- <?php __('lblChoose'); ?> --</option>

							<?php

							foreach ($tpl['user_arr'] as $v)

							{

								?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php

							}

							?>

						</select>

					</span>

					<a id="pjCssEditOwner" href="#" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionUpdate&id={ID}" class="pj-edit" style="display:none;"></a>

				</span>

			</p>

			<?php

		}

		?>

		<p style="display: none;">

			<label class="title"><?php __('lblOwnerShow'); ?></label>

			<span class="inline_block">

				<select name="owner_show" id="owner_show" class="pj-form-field w150">

					<?php

					foreach (__('_yesno', true) as $k => $v)

					{

						?><option value="<?php echo $k; ?>"<?php echo $k == 'T' ? 'selected="selected"' : NULL;?>><?php echo stripslashes($v); ?></option><?php

					}

					?>

				</select>

				<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblListingShowContactTip'); ?>"></a>

			</span>

		</p>

		<?php

		if ($controller->isAdmin() || $controller->isEditor())

		{ 

			?>

			<p class="pjFeaturedBox">

				<label class="title"><?php __('lblMakeFeatured'); ?></label>

				<span class="left">

					<?php

					foreach (__('_yesno', true) as $k => $v)

					{

						?>

						<label class="block float_left r5"><input type="radio" name="is_featured" value="<?php echo $k; ?>"<?php echo 'F' == $k ? ' checked="checked"' : NULL; ?> /> <?php echo $v; ?></label>

						<?php

					}

					?>

					<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php echo pjSanitize::clean(__('lblFeaturedTip', true)); ?>"></a>

				</span>

			</p>

			<?php

		} 

		?>

		<p>

			<label class="title">&nbsp;</label>

			<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />

			<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminListings&action=pjActionIndex';" />

		</p>

	</form>

	<?php

}

?>