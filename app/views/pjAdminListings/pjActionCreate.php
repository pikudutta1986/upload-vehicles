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
				<input type="text" name="vin" id="vin" class="pj-form-field" value="" onblur="getInputValue()"/>
			</span>
		</p>

		<p>
			<label class="title">Year</label>
			<span class="inline_block">
				<input type="text" name="year" id="year" class="pj-form-field required" value="" maxlength="4" minlength="4" onblur="getInputValue()"/>
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

<script src="https://code.jquery.com/jquery.js"></script>

<script>

var makeArray = JSON.parse('<?php echo json_encode($tpl['make_arr']); ?>');

function getInputValue() {
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
</script>