<?php
// echo '<pre>';
// print_r($tpl);
// print_r($tpl['feature_arr']);
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
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	if($controller->isAdmin())
	{
		include_once PJ_VIEWS_PATH . 'pjAdminListings/elements/menu.php';
	}
	?>
	<style type="text/css">
		.mceEditor > table{
			width: 570px !important;
		}
		.ui-menu{
			height: 230px;
			overflow-y: scroll;
		}
		.ui-tabs .ui-tabs-panel{
			overflow: visible;
		}
	</style>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionUpdate" method="post" id="frmUpdateListing" class="form pj-form" enctype="multipart/form-data">
		<input type="hidden" name="listing_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
		<?php $locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : $controller->getLocaleId(); ?>
		<input type="hidden" name="locale" value="<?php echo $locale; ?>" />

		<?php
		// $title_arr = array();
		// if(!empty($tpl['arr']['i18n'][$locale]['title']))
		// {
		// 	$title_arr[] = pjSanitize::html($tpl['arr']['i18n'][$locale]['title']); 
		// }
		// if(!empty($tpl['arr']['listing_refid']))
		// {
		// 	$title_arr[] = pjSanitize::html($tpl['arr']['listing_refid']);
		// }
		// if(!empty($title_arr))
		// {
		// 	?><div class="b10 bold"><?php echo implode("  / ", $title_arr);?></div><?php
		// }


		// title section starts here........

		$title = '';
		if (!empty($tpl['arr']['year'])) {
			$title .= $tpl['arr']['year'];
		}
		
		if (!empty($tpl['arr']['make_id'])) {
			$makeId = $tpl['arr']['make_id'];
			$makeName = '';
			
			foreach ($tpl['make_arr'] as $make) {
				if ($make['id'] == $makeId) {
					$makeName = $make['name'];
					break;
				}
			}
			
			if (!empty($makeName)) {
				$title .= ' ' . $makeName;
			}
		}
		
		if (!empty($tpl['arr']['model_id'])) {
			$modelId = $tpl['arr']['model_id'];
			$modelName = '';
			
			foreach ($tpl['model_arr'] as $model) {
				if ($model['id'] == $modelId) {
					$modelName = $model['name'];
					break;
				}
			}
			
			if (!empty($modelName)) {
				$title .= ' ' . $modelName;
			}
		}
		
		if (!empty($tpl['arr']['listing_refid'])) {
			$title .= ' / ' . $tpl['arr']['listing_refid'];
		}

		?>
			<h2 class="b10 bold"><?php echo $title ; ?></h2>

		<!-- title section ends here.... -->


		<div id="tabs">
		
			<!-- <ul>
				<li><a href="#tabs-1"><?php __('lblListingSummary'); ?></a></li>
				<li><a href="#tabs-2"><?php __('lblListingDetails'); ?></a></li>
				<li><a href="#tabs-3"><?php __('lblListingDescription'); ?></a></li>
				<li><a href="#tabs-4"><?php __('lblListingExtras'); ?></a></li>
				<li><a href="#tabs-5"><?php __('lblListingPhotos'); ?></a></li>
				<li><a href="#tabs-6"><?php __('lblListingSeo'); ?></a></li>
			</ul> -->
		
			<div class="tab-heading">Summary</div>
			<div id="tabs-1">
					
				<p><label class="title"><?php __('lblListingCreated'); ?></label><span class="left"><?php echo pjUtil::formatDate(date("Y-m-d", strtotime($tpl['arr']['created'])), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?> <?php echo pjUtil::formatTime(date("H:i:s", strtotime($tpl['arr']['created'])), 'H:i:s', $tpl['option_arr']['o_time_format']); ?></span></p>
				<p><label class="title"><?php __('lblListingModified'); ?></label><span class="left"><?php echo !empty($tpl['arr']['modified']) ? pjUtil::formatDate(date("Y-m-d", strtotime($tpl['arr']['modified'])), 'Y-m-d', $tpl['option_arr']['o_date_format']) . ' ' . pjUtil::formatTime(date("H:i:s", strtotime($tpl['arr']['modified'])), 'H:i:s', $tpl['option_arr']['o_time_format']) : __('lblNA'); ?></span></p>
				<p><label class="title"><?php __('lblListingViews'); ?></label><span class="left"><?php echo $tpl['arr']['views']; ?></span></p>
				<p>
					<label class="title"><?php __('lblListingRefid'); ?></label>
					<span class="inline_block">
						<input type="text" name="listing_refid" id="listing_refid" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['listing_refid'])); ?>" class="pj-form-field required" />
					</span>
				</p>
				<p style="overflow: visible; display: none;">
					<label class="title"><?php __('lblListingType'); ?></label>
					<span class="inline_block">
					<input type="text" name="car_type" id="car_type" value="used" class="pj-form-field required" readonly/>

						<!-- <select name="car_type" id="car_type" class="pj-form-field w150 required">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
							<?php
							foreach (__('car_types', true) as $k => $v)
							{
								?><option value="<?php echo $k; ?>" <?php echo $k == $tpl['arr']['car_type'] ? 'selected="selected"' : null; ?>><?php echo stripslashes($v); ?></option><?php
							}
							?>
						</select> -->
					</span>
				</p>
				<p>
					<label class="title">Vin</label>
					<span class="inline_block"><?php echo $tpl['arr']['vin']; ?></span>
				</p>
				<p>
					<label class="title">Year</label>
					<input type="number" name="year" id="year" value="<?php echo $tpl['arr']['year']; ?>" class="pj-form-field required" maxlength="4"  minlength="4" selected/>
				</p>
				<p>
					<label class="title"><?php __('lblListingMake'); ?></label>
					<span class="inline_block">
						<select name="make_id" id="make_id" class="pj-form-field w150 required">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
							<?php
							foreach ($tpl['make_arr'] as $v)
							{
								?><option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $tpl['arr']['make_id'] ? 'selected="selected"' : null; ?>><?php echo stripslashes($v['name']); ?></option><?php
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
							<?php
							foreach ($tpl['model_arr'] as $v)
							{
								?><option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $tpl['arr']['model_id'] ? 'selected="selected"' : null; ?>><?php echo stripslashes($v['name']); ?></option><?php
							}
							?>
						</select>
					</span>
				</p>
				<?php
				if (!$controller->isOwner())
				{
					?>
					<p><label class="title"><?php __('lblListingStatus'); ?></label>
						<span class="inline_block">
							<select name="status" id="status" class="pj-form-field required">
								<option value="">-- <?php __('lblChoose'); ?> --</option>
								<?php
								foreach (__('publish_status', true) as $k => $v)
								{
									if ($tpl['arr']['status'] == $k)
									{
										?><option value="<?php echo $k; ?>" selected="selected"><?php echo stripslashes($v); ?></option><?php
									} else {
										?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php
									}
								}
								?>
							</select>
							<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblListingStatusTip'); ?>"></a>
						</span>
					</p>
					<p id="expiration_container" style="display:<?php echo $tpl['arr']['status'] == 'E' ? 'block' : 'none'; ?>;">
						<label class="title"><?php __('lblListingExpire'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-after">
							<input type="text" name="expire" id="expire" class="pj-form-field pointer w80 datepick" value="<?php echo pjUtil::formatDate($tpl['arr']['expire'], "Y-m-d", $tpl['option_arr']['o_date_format']); ?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
							<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
						</span>
						<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblListingExpireTip'); ?>"></a>
					</p>
					<?php
				} else {
					?>
					<p style="display: none;">
						<label class="title"><?php __('lblListingExpire'); ?></label>
						<span class="left float_left"><?php echo pjUtil::formatDate(date("Y-m-d", strtotime($tpl['arr']['expire'])), 'Y-m-d', $tpl['option_arr']['o_date_format'] ); ?></span>
						<a class="pj-button float_left l10" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionPayment&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php __('lblListingExtend'); ?></a>
					</p>
					<?php
				}
				
				if ($controller->isAdmin())
				{
					?>
					<p style="overflow: visible">
						<label class="title"><?php __('lblListingOwner'); ?></label>
						<span class="inline_block">
							<span class="block float_left r5">
								<select name="owner_id" id="owner_id" class="pj-form-field required w200">
									<option value="">-- <?php __('lblChoose'); ?> --</option>
									<?php
									foreach ($tpl['user_arr'] as $val) {
										?>
										<option value="<?php echo $val['id'];?>" <?php echo $tpl['arr']['owner_id'] == $val['id'] ? 'selected="selected"' : '';?> ><?php echo $val['name'];?></option>
										<?php
									}
									?>
								</select>
							</span>
							<a id="pjCssEditOwner" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionUpdate&id=<?php echo $tpl['arr']['owner_id'];?>" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionUpdate&id={ID}" class="pj-edit" style="display:<?php echo !empty($tpl['arr']['owner_id']) ? 'block' : 'none'; ?>;"></a>
						</span>
					</p>
					<?php
				}
				?>
				<p style="display: none;">
					<label class="title"><?php __('lblOwnerShow'); ?></label>
					<span>
						<select name="owner_show" id="owner_show" class="pj-form-field w150">
							<?php
							foreach (__('_yesno', true) as $k => $v)
							{
								?><option value="<?php echo $k; ?>" <?php echo $tpl['arr']['owner_show'] == $k ? 'selected="selected"' : null;?>><?php echo stripslashes($v); ?></option><?php
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
							<label class="block float_left r5"><input type="radio" name="is_featured" value="<?php echo $k; ?>"<?php echo $tpl['arr']['is_featured'] == $k ? ' checked="checked"' : NULL; ?> /> <?php echo $v; ?></label>
							<?php
						}
						?>
						<a href="#" class="pj-form-langbar-tip listing-tip" title="<?php echo pjSanitize::clean(__('lblFeaturedTip', true)); ?>"></a>
						</span>
					</p>
					<?php
				} 
				?>
				<!-- <p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				</p> -->

			</div>
		
			<div class="tab-heading">Details</div>
			<div id="tabs-2">
				<?php
				if($controller->isOwner())
				{
					pjUtil::printNotice(__('infoOwnerCarDetailTitle', true), __('infoOwnerCarDetailBody', true));
				}else{
					pjUtil::printNotice(__('infoCarDetailTitle', true), __('infoCarDetailBody', true));
				} 
				?>
				<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
				<div class="multilang b10 first_multilang"></div>
				<?php endif;?>
				<div class="clear_both">
					<?php
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<!-- <p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblListingTitle'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][title]" class="pj-form-field w500<?php echo $controller->isOwner() ? ((int) $v['id'] === $locale ? ' required' : NULL) : ((int) $v['is_default'] === 0 ? NULL : ' required' ); ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['title']));?>"/>
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>  -->
						<?php
					}
					$items = array();
					$feature_types = __('feature_types', true);
					// print_r($feature_types);
					// print_r($tpl['feature_arr']);
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
							<p class="<?php echo ($k === 'seats') ? 'hidden-option' : ''; ?>">
								<label class="title"><?php echo stripslashes($v); ?></label>
								<span id="boxFeature<?php echo $k;?>" class="inline-block">
									<select name="feature_<?php echo $k; ?>_id" id="feature_<?php echo $k; ?>_id" class="pj-form-field w200">
										<option value="">-- <?php __('lblChoose'); ?> --</option>
										<?php
										foreach ($tpl['feature_arr'] as $feature)
										{
											if ($feature['type'] == $k)
											{
												if (isset($tpl['arr']['feature_'.$k.'_id']) && $tpl['arr']['feature_'.$k.'_id'] == $feature['id'])
												{
													?><option value="<?php echo $feature['id']; ?>" selected="selected"><?php echo stripslashes($feature['name']); ?></option><?php	
												} else {
													?><option value="<?php echo $feature['id']; ?>"><?php echo stripslashes($feature['name']); ?></option><?php
												}
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
					<p>
						<label class="title"><?php __('lblListingPrice'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
							<input type="text" id="listing_price" name="listing_price" class="pj-form-field w100 align_right" maxlength="18" value="<?php echo $tpl['arr']['listing_price']; ?>" />
						</span>
					</p>
					<p style="display: none;">
						<label class="title"><?php __('lblListingFirstRegistration'); ?></label>
						<span class="inline_block">
							<select name="listing_month" id="listing_month" class="pj-form-field">
								<option value="">-- <?php __('lblChoose'); ?> --</option>
								<?php
								for($i = 1; $i <= 12; $i++)
								{
									?>
									<option value="<?php echo $i;?>" <?php echo $tpl['arr']['listing_month'] == $i ? 'selected="selected"' : '';?> ><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ;?></option>
									<?php
								}
								?>
							</select>
							<select name="listing_year" id="listing_year" class="pj-form-field">
								<option value="">-- <?php __('lblChoose'); ?> --</option>
								<?php
								for($i = date('Y'); $i >= 1950; $i--)
								{
									?>
									<option value="<?php echo $i;?>" <?php echo $tpl['arr']['listing_year'] == $i ? 'selected="selected"' : '';?> ><?php echo $i;?></option>
									<?php
								}
								?>
							</select>
						</span>
					</p>
					<p>
						<label class="title"><?php __('lblListingMileage'); ?></label>
						<span class="inline_block">
							<input type="text" name="listing_mileage" id="listing_mileage" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['listing_mileage'])); ?>" maxlength="10" class="pj-form-field field-int w100 number" data-msg-required="<?php __('pj_number_validation');?>"/>
							<span><?php echo $tpl['option_arr']['o_mileage_in'];?></span>
						</span>
					</p>
					<p style="display: none;">
						<label class="title"><?php __('lblListingPower'); ?></label>
						<span class="inline_block">
							<input type="text" name="listing_power" id="listing_power" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['listing_power'])); ?>" maxlength="10" class="pj-form-field field-int w100 number" data-msg-required="<?php __('pj_number_validation');?>"/>
							<span><?php echo $tpl['option_arr']['o_power_in'];?></span>
						</span>
					</p>
					<!-- <p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
					</p> -->
				</div>
			</div>

			<div class="tab-heading">Description</div>
			<div id="tabs-3">
				<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
				<div class="multilang b10"></div>
				<?php endif;?>
				<div class="clear_both">
					<?php
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblListingDescription'); ?></label>
							<span class="inline_block">
								<textarea id="i18n_<?php echo $v['id']?>_description" data-index="<?php echo $v['id'];?>" name="i18n[<?php echo $v['id']; ?>][description]" class="mceEditor" style="width: 570px; height: 400px"><?php echo stripslashes(@$tpl['arr']['i18n'][$v['id']]['description']); ?></textarea>
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					?>
				</div>
				<!-- <p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				</p> -->
			</div>

			<div class="tab-heading">Extras</div>
			<div id="tabs-4">
				<?php
				if($controller->isOwner())
				{
					pjUtil::printNotice(__('infoOwnerCarExtraTitle', true), __('infoOwnerCarExtraBody', true));
				}else{
					pjUtil::printNotice(__('infoCarExtraTitle', true), __('infoCarExtraBody', true));
				} 
				
				$i = 1;
				foreach ($tpl['extra_arr'] as $v)
				{
					$is_open = true;
					?>
					<div class="float_left w200 b5 r25 pj-checkbox gradient<?php echo in_array($v['id'], $tpl['listing_extra_arr']) ? ' pj-checkbox-checked' : NULL; ?>">
						<input type="checkbox"  style="vertical-align: middle" name="extra[]" id="extra_<?php echo $v['id']; ?>" value="<?php echo $v['id']; ?>"<?php echo in_array($v['id'], $tpl['listing_extra_arr']) ? ' checked="checked"' : NULL; ?> />
						<label for="extra_<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></label>
					</div>
					<?php
					if ($i % 3 === 0)
					{
						$is_open = false;
						?><div class="clear_left"></div><?php
					}
					$i++;
					
				}
				if ($is_open) {
					?><div class="clear_left"></div><?php
				}
				?>
				
				<!-- <p>
					<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				</p> -->
			</div>
			
			<div class="tab-heading mt-2">Photos</div>
			<div id="tabs-5">
				<?php
				pjUtil::printNotice(@$titles['AL41'], @$bodies['AL41']);
				?>
				<div id="gallery"></div>
				<p style="text-align: right;">
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				</p>
			</div>
			
			<!-- <div id="tabs-6">
				<?php
				pjUtil::printNotice(__('infoCarSEOTitle', true), __('infoCarSEOBody', true));  
				?>
				<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
				<div class="multilang b10"></div>
				<?php endif;?>
				<div class="clear_both">
					<?php
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblListingMetaTitle'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][meta_title]" class="pj-form-field w500" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['meta_title'])); ?>" />
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblListingMetaKeywords'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][meta_keywords]" class="pj-form-field w500" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['meta_keywords'])); ?>" />
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblListingMetaDesc'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][meta_description]" class="pj-form-field w500" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['meta_description'])); ?>" />
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					?>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button pj-button-save" />
					</p>
				</div>
			</div> -->
			
		</div> <!-- #tabs -->
	</form>
	<div id="dialogLimit" style="display: none" title="<?php __('lblCharsLimitation');?>"><?php __('lblCharsLimitationDesc');?></div>
	<script type="text/javascript">
	var myGallery = myGallery || {};
	myGallery.foreign_id = "<?php echo $tpl['arr']['id']; ?>";
	myGallery.hash = "";
	var myLabel = myLabel || {};
	myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
	(function ($) {
		$(function() {
			$(".multilang").multilang({
				langs: <?php echo $tpl['locale_str']; ?>,
				flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
				select: function (event, ui) {
					$("input[name='locale']").val(ui.index);
					$.get("index.php?controller=pjAdminListings&action=pjActionGetLocale", {
						"locale" : ui.index
					}).done(function (data) {
						<?php
						foreach ($feature_types as $k => $v)
						{
							if (isset($items[$k]) && $items[$k] > 0)
							{
								?>
								selected_id = $('#feature_<?php echo $k;?>_id').find("option:selected").val();
								$("#boxFeature<?php echo $k;?>").html(data.feature_<?php echo $k; ?>_id);
								$("#feature_<?php echo $k;?>_id").find("option[value='"+selected_id+"']").prop("selected", true);
								<?php
							}
						} 
						?>
					});
				}
			});
			$(".first_multilang").find("a[data-index='<?php echo $locale; ?>']").trigger("click");
			$(".multilang").find("a[data-index='<?php echo $locale; ?>']").addClass("pj-form-langbar-item-active");
		});
	})(jQuery_1_8_2);
	</script>
	
	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{
		$tab_id = explode("-", $_GET['tab_id']);
		$tab_id = (int) $tab_id[1] - 1;
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "active", <?php echo $tab_id; ?>);
			});
		})(jQuery_1_8_2);
		</script>
		<?php
	}
}
?>

<style>
	.hidden-option {
		display: none;
	}

	.tab-heading {
		background: #115E9B;
		color: #fff;
		padding: 10px;
		font-size: 15px;
		margin-bottom: 10px;
	}

	.mt-2 {
		margin-top: 7px;
	}
</style>