<?php
// echo '<pre>';
// print_r($tpl['make_arr']);
// $receivedMakeObjId = $_POST['makeObjId'];
// echo $receivedMakeObjId;
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
		<div id="tabs">
			<div class="tab-heading">Summary</div>
			<div id="tabs-1">
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
						<input type="text" name="vin" id="vin" class="pj-form-field required" value="" style="margin-right:10px"/>
					</span>
				</p>

				<p>
					<label class="title">Year</label>
					<span class="inline_block">
						<input type="text" name="year" id="year" class="pj-form-field required" value="" maxlength="4" minlength="4" style="margin-right: 10px;"/>
						<button class="fetch-button" onclick="getInputValue(event)">Fetch Data</button>
					</span>
				</p>

				<p>

					<label class="title"><?php __('lblListingMake'); ?></label>

					<span class="inline_block">

						<select name="make_id" id="make_id" class="pj-form-field w150">

							<option value="">-- <?php __('lblChoose'); ?> --</option>

							<?php

							foreach ($tpl['make_arr'] as $v)

							{
								// $selected = ($v['id'] === $receivedMakeObjId) ? 'selected' : '';

								?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php

							}

							?>

						</select>

					</span>

				</p>

				<p>

					<label class="title"><?php __('lblListingModel'); ?></label>

					<span id="model_container" class="inline_block">

						<select name="model_id" id="model_id" class="pj-form-field w150">

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
					<label class="title">Trim</label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
					<input type="text" id="trim" name="trim" class="pj-form-field w100" value="<?php echo $tpl['arr']['trim']; ?>" selected />
					</span>
					</p>
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
			</div>

			<hr class="mt-2">

			<p class="mt-2" style="text-align: right;">

				<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />

				<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminListings&action=pjActionIndex';" />

			</p>
		</div>
	</form>



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

<script src="https://code.jquery.com/jquery.js"></script>

<script>

var makeArray = JSON.parse('<?php echo json_encode($tpl['make_arr']); ?>');

function getInputValue(event) {
	event.preventDefault();
	var yearInput = document.getElementById('year');
	var year = yearInput.value;

	var vinInput = document.getElementById('vin');
	var vin = vinInput.value;

	if(year != '' && vin != '') {
		console.log("not blank");
		getData(vin, year);
	}
}

function getData(vin, year) {
	var url = 'https://vpic.nhtsa.dot.gov/api/vehicles/decodevinextended/' + vin + '?format=json&modelyear=' + year + '';
	$.ajax({
		url: (url),
		type: 'GET',
		dataType: 'json',
		success: function(res) {
			if (res.Count > 0) {
				console.log("res>>>>>", res);

				let resMakeObj = res.Results.find((x) => x.Variable == 'Make');

				if (resMakeObj)
				{
					let resMake = resMakeObj.Value;
					
					let makeObj = makeArray.find((x) => resMake.toLowerCase().indexOf(x.name.toLowerCase()) !== -1);

					if (makeObj)
					{
						let makeObjId = makeObj.id;
						console.log("makeObjId", makeObjId);
						$("#make_id").val(Number(makeObjId));

						var resModelObj = res.Results.find((y) => y.Variable == 'Model');
						console.log('VIN Model', resModelObj);
						var modelText = resModelObj.Value;
						

						$.get("https://www.uploadvehicles.com/index.php?controller=pjAdminListings&action=pjActionGetModels&id="+makeObjId, function(data, status){
							
							if (status == 'success')
							{
								var modelArray = [];
								var arr1 = data.split('value="');
								arr1.forEach((item) => {
									var arr2 = item.split('">');
									if (arr2[0] !='' && !isNaN(arr2[0])){
										if (arr2[1] != '')
										{
											var arr3 = arr2[1].split('</option>');
											modelArray.push({id: arr2[0], text: arr3[0]});

										}
									}
								});

								setTimeout(function (){
									$("#model_container").html(data);
									console.log('modelArray', modelArray);
									let modelObj = modelArray.find((x) => modelText.toLowerCase() == x.text.toLowerCase());

									if (modelObj)
									{
										$("#model_id").val(Number(modelObj.id));
									}
								}, 100);
								
							}
						});
					}
				}

				getAllFieldSelected(res.Results);
				
				let resTrimObj = res.Results.find((a) => a.Variable == 'Trim');
				let resTrim = resTrimObj ? resTrimObj.Value : null;

				let resDoorObj = res.Results.find((b) => b.Variable == 'Doors');
				let resDoor = resDoorObj ? resDoorObj.Value : null;

				let resFuelTypeObj = res.Results.find((c) => c.Variable == 'Fuel Type - Primary');
				let resFuelType = resFuelTypeObj ? resFuelTypeObj.Value : null;

				let resVehicleTypeObj = res.Results.find((d) => d.Variable == 'Vehicle Type');
				let resVehicleType = resVehicleTypeObj ? resVehicleTypeObj.Value : null;

				let resTransmissionObj = res.Results.find((e) => e.Variable == 'Transmission Style');
				let resTransmission = resTransmissionObj ? resTransmissionObj.Value : null;

				var paramObj = {trim: resTrim, door: resDoor, fueltype: resFuelType, vehicleType: resVehicleType, transmissison: resTransmission };
				console.log("paramObj", paramObj);

			}
		}
	});
}

function getAllFieldSelected(results) {
	console.log("results", results);

	// Set Number of cylinders........
	let resCylindersObj = results.find((a) => a.Variable == 'Engine Number of Cylinders');
	let resCylinder = resCylindersObj.Value;

	let cylinderElement = document.getElementById('feature_class_id');
	let cylindersArray = [];
	for (let i = 1; i < cylinderElement.options.length; i++) {
		let option = cylinderElement.options[i];
		let optionValue = option.value;
		let optionText = option.textContent;
		cylindersArray.push({ value: optionValue, name: optionText });
	}

	let cylinder = cylindersArray.find((b) => b.name.indexOf(resCylinder) != -1);
	if(cylinder) {
		$("#feature_class_id").val(Number(cylinder.value));
	}



	// Set Doors ............
	let resDoorsObj = results.find((c) => c.Variable == 'Doors');
	let resDoor = resDoorsObj.Value;

	let doorsElement = document.getElementById('feature_doors_id');
	let doorsArray = [];
	for (let j = 1; j < doorsElement.options.length; j++) {
		let option = doorsElement.options[j];
		let optionValue = option.value;
		let optionText = option.textContent;
		doorsArray.push({ value: optionValue, name: optionText });
	}

	let door = doorsArray.find((d) => d.name.indexOf(resDoor) != -1);
	if(door) {
		$("#feature_doors_id").val(Number(door.value));
	}


	// Set fuel type..........
	let resFuelObj = results.find((e) => e.Variable == 'Fuel Type - Primary');
	let resFuel = resFuelObj.Value.toLowerCase();

	let fuelElement = document.getElementById('feature_fuel_id');
	let fuelTypeArray = [];
	for (let k = 1; k < fuelElement.options.length; k++) {
		let option = fuelElement.options[k];
		let optionValue = option.value;
		let optionText = option.textContent;
		fuelTypeArray.push({ value: optionValue, name: optionText });
	}

	let fuelType = fuelTypeArray.filter((f) => resFuel.indexOf(f.name.toLowerCase()) != -1);
	if(fuelType) {
		$("#feature_fuel_id").val(Number(fuelType[0].value));
	}


	// Set Transmission..........
	let resTransObj = results.find((g) => g.Variable == 'Transmission Style');
	let resTrans = resTransObj.Value;

	let transElement = document.getElementById('feature_gearbox_id');
	let transArray = [];
	for (let l = 1; l < transElement.options.length; l++) {
		let option = transElement.options[l];
		let optionValue = option.value;
		let optionText = option.textContent;
		transArray.push({ value: optionValue, name: optionText });
	}

	let transmission = transArray.find((h) => h.name.indexOf(resTrans) != -1);
	if(transmission) {
		$("#feature_gearbox_id").val(Number(transmission.value));
	}


	// Set Vehicle type.........
	let resVehicleTypeObj = results.find((w) => w.Variable == 'Body Class');
	let resVehicleType = resVehicleTypeObj.Value;
	let resVehicleTypeArray = resVehicleType.split(/\s|\//); // Splitting by space or '/'
	let modifiedArray = resVehicleTypeArray.map(element => element.replace(/\(|\)/g, '')); // Replace brackates of the array elements...
	let modifiedresVehicleTypeArray = modifiedArray.map(element => element.toLowerCase()); // Convert each array elements to lower... 

	let typeElement = document.getElementById('feature_type_id');
	let typesArray = [];
	for (let m = 1; m < typeElement.options.length; m++) {
		let option = typeElement.options[m];
		let optionValue = option.value;
		let optionText = option.textContent;
		typesArray.push({ value: optionValue, name: optionText });
	}

	// Filter the array by response's body style...
	let vehicleTypeArray = typesArray.filter(obj => modifiedresVehicleTypeArray.some(str => obj.name.toLowerCase().includes(str)));
	if(vehicleTypeArray.length > 0) {
		$("#feature_type_id").val(Number(vehicleTypeArray[0].value));
	}
	

	// Set trim Value........... 
	let trimObj = results.find((m) => m.Variable == 'Trim');
	let trim = trimObj.Value;
	$('#trim').val(trim);


}

</script>

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
		margin-top: 7px !important;
	}

	.fetch-button {
		background: #115E9B;
		color: #fff;
		padding: 5px;
		cursor: pointer;
	}
</style>