<?php
$url = pjUtil::currPageURL($_GET);
if(!isset($_GET['listing_search']) || empty($_GET['listing_search']))
{
	if ($tpl['option_arr']['o_seo_url'] == 'No')
	{
		$url .= "&listing_search=1";
	}else{
		$url .= "?listing_search=1";
	}
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<?php __('front_label_filters');?>
	</div><!-- /.panel-header -->

	<div class="panel-body">
		<form action="?" method="post">
			<?php
			$filter_url = $url;
			$type_filter_arr = array();
			if(isset($_GET['car_type']) && in_array($_GET['car_type'], array('new', 'used')))
			{
				$type_filter_arr = explode(",", $_GET['car_type']);
				$filter_url = str_replace('&car_type=' . $_GET['car_type'], '', $filter_url);
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_car_type'); ?>:</label>
				<br>
				
				<div class="row">
					<div class="col-md-6">
						<input type="checkbox" class="pjAcFilterType" id="used_cars"<?php echo in_array('used', $type_filter_arr) ? ' checked="checked"' : null;?> value="used" data-url="<?php echo $filter_url; ?>"/>
						<label for="used_cars" class="pjFilterLabel"><?php __('front_label_used_cars'); ?></label>
					</div><!-- /.col-md-5 -->
					
					<div class="col-md-6">
						<input type="checkbox" class="pjAcFilterType" id="new_cars"<?php echo in_array('new', $type_filter_arr) ? ' checked="checked"' : null;?> value="new" data-url="<?php echo $filter_url; ?>"/>
						<label for="new_cars" class="pjFilterLabel"><?php __('front_label_new_cars'); ?></label>
					</div><!-- /.col-md-5 -->
				</div><!-- /.row -->
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			if(isset($_GET['make_id']) && (int) $_GET['make_id'] > 0 )
			{
				$filter_url = str_replace('&make_id=' . $_GET['make_id'], '', $filter_url);
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_make'); ?>:</label>

				<select name="make_id" class="form-control pjAcSelectorFilter" data-url="<?php echo $filter_url;?>">
					<option value="">-- <?php __('front_label_select');?> --</option>
					<?php
					foreach($tpl['make_arr'] as $k => $v)
					{
						?><option value="<?php echo $v['id'];?>"<?php echo (isset($_GET['make_id']) && $_GET['make_id'] == $v['id']) ? ' selected="selected"': NULL;?>><?php echo stripslashes($v['name']);?></option><?php
					} 
					?>
				</select>
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			if(isset($_GET['model_id']) && (int) $_GET['model_id'] > 0)
			{
				$filter_url = str_replace('&model_id=' . $_GET['model_id'], '', $filter_url);
			} 
			?>
			<div class="form-group">
				<label for="model"><?php __('front_label_model'); ?>:</label>

				<select name="model_id" class="form-control pjAcSelectorFilter" data-url="<?php echo $filter_url;?>">
					<option value="">-- <?php __('front_label_select');?> --</option>
					<?php
					foreach ($tpl['model_arr'] as $v)
					{
						?><option value="<?php echo $v['id']; ?>"<?php echo (isset($_GET['model_id']) && $_GET['model_id'] == $v['id']) ? ' selected="selected"' : NULL;?>><?php echo stripslashes($v['name']); ?></option><?php
					}
					?>
				</select>
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			$year_from = 1900;
			$year_to = date('Y');
			if(isset($_GET['year_from']) && (int) $_GET['year_from'] > 0 && isset($_GET['year_to']) && (int) $_GET['year_to'] > 0)
			{
				$filter_url = str_replace('&year_from=' . $_GET['year_from'], '', $filter_url);
				$filter_url = str_replace('&year_to=' . $_GET['year_to'], '', $filter_url);
				$year_from = $_GET['year_from'];
				$year_to = $_GET['year_to'];
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_first_registration'); ?>:</label>
				
				<div class="range clearfix">
					<div id="pjAcRegistrationSlider" data-url="<?php echo $filter_url?>"></div>
					
					<p>
						<span id="pjAcRegistrationLabel"><?php echo $year_from . ' - ' . $year_to; ?></span>
					</p>
				</div><!-- /.range -->
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			$mileage_from = pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], 0);
			$mileage_to = pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], 500000);
			if(isset($_GET['mileage_from']) && (int) $_GET['mileage_from'] >= 0 && isset($_GET['mileage_to']) && (int) $_GET['mileage_to'] > 0)
			{
				$filter_url = str_replace('&mileage_from=' . $_GET['mileage_from'], '', $filter_url);
				$filter_url = str_replace('&mileage_to=' . $_GET['mileage_to'], '', $filter_url);
				$mileage_from = pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], $_GET['mileage_from']);
				$mileage_to = pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], $_GET['mileage_to']);
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_mileage');?>:</label>
				
				<div class="range clearfix">
					<div id="pjAcMileageSlider" data-url="<?php echo $filter_url?>"></div>
					
					<p>
						<span id="pjAcMileageLabel" data-mileage="<?php echo $tpl['option_arr']['o_mileage_in']; ?>"><?php echo $mileage_from . ' - ' . $mileage_to; ?></span>
					</p>
				</div><!-- /.range -->
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			$price_from = pjUtil::formatCurrencySign(0, $tpl['option_arr']['o_currency']);
			$price_to = pjUtil::formatCurrencySign(500000, $tpl['option_arr']['o_currency']);
			if(isset($_GET['price_from']) && (int) $_GET['price_from'] >= 0 && isset($_GET['price_to']) && (int) $_GET['price_to'] > 0)
			{
				$filter_url = str_replace('&price_from=' . $_GET['price_from'], '', $filter_url);
				$filter_url = str_replace('&price_to=' . $_GET['price_to'], '', $filter_url);
				$price_from = pjUtil::formatCurrencySign($_GET['price_from'], $tpl['option_arr']['o_currency']);
				$price_to = pjUtil::formatCurrencySign($_GET['price_to'], $tpl['option_arr']['o_currency']);
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_price');?>:</label>

				<div class="range clearfix">
					<div id="pjAcPriceSlider" data-url="<?php echo $filter_url?>"></div>
					
					<p>
						<span id="pjAcPriceLabel" data-currency="<?php echo $tpl['option_arr']['o_currency']; ?>"><?php echo $price_from . ' - ' . $price_to; ?></span>
					</p>
				</div><!-- /.range -->
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			if(isset($_GET['feature_colors_id']) && (int) $_GET['feature_colors_id'] > 0)
			{
				$filter_url = str_replace('&feature_colors_id=' . $_GET['feature_colors_id'], '', $filter_url);
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_color');?>:</label>
				<select name="feature_colors_id" class="form-control pjAcSelectorFilter" data-url="<?php echo $filter_url; ?>">
					<option value="">-- <?php __('front_label_select');?> --</option>
					<?php
					foreach ($tpl['color_arr'] as $v)
					{
						if(isset($_GET['feature_colors_id']) && $_GET['feature_colors_id'] == $v['id'])
						{
							?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo stripslashes($v['name']); ?></option><?php
						}else{
							?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
						}
					}
					?>
				</select>
			</div><!-- /.form-group -->
			<?php
			$filter_url = $url;
			if(isset($_GET['feature_fuel_id']) && (int) $_GET['feature_fuel_id'] > 0)
			{
				$filter_url = str_replace('&feature_fuel_id=' . $_GET['feature_fuel_id'], '', $filter_url);
			} 
			?>
			<div class="form-group">
				<label><?php __('front_label_fuel_type');?>:</label>

				<select name="feature_fuel_id" class="form-control pjAcSelectorFilter" data-url="<?php echo $filter_url; ?>">
					<option value="">-- <?php __('front_label_select');?> --</option>
					<?php
					foreach ($tpl['fuel_arr'] as $v)
					{
						if(isset($_GET['feature_fuel_id']) && $_GET['feature_fuel_id'] == $v['id'])
						{
							?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo stripslashes($v['name']); ?></option><?php
						}else{
							?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
						}
					}
					?>
				</select>
			</div><!-- /.form-group -->
			<?php
			if(isset($_GET['listing_search']))
			{ 
				?>
				<div class="buttons">
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionCars" class="clear-filters"><?php __('front_label_clear_filters');?></a>
				</div>
				<?php
			} 
			?>
		</form>
	</div><!-- /.panel-body -->
</div><!-- /.panel panel-default -->