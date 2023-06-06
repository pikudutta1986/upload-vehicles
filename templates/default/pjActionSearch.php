<?php
mt_srand();
$index = mt_rand(1, 9999);
?>
<div id="pjWrapper">
	<div class="container-fluid">
		<?php 
		include_once dirname(__FILE__) . '/elements/header.php';
		
		$mileage_from = pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], 0);
		$mileage_to = pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], 500000);
		
		$year_from = 1900;
		$year_to = date('Y');
		
		$power_from = pjUtil::showPower($tpl['option_arr']['o_power_in'], 0);
		$power_to = pjUtil::showPower($tpl['option_arr']['o_power_in'], 1000);
		
		$price_from = pjUtil::formatCurrencySign(0, $tpl['option_arr']['o_currency']);
		$price_to = pjUtil::formatCurrencySign(500000, $tpl['option_arr']['o_currency']);
		?>
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<?php echo __('front_label_search_title'); ?>
			</div><!-- /.panel-heading -->
			<?php
			$action =  $_SERVER['PHP_SELF'];
			if ($tpl['option_arr']['o_seo_url'] == 'Yes')
			{
				$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
				$path = $path == '/' ? '' : $path;
				$action = $path . '/index.html';
			}
			?>
			<div class="panel-body">
				<form id="frmCRLSearch" name="frmCRLSearch" action="<?php echo $action; ?>" method="get" class="form-horizontal" method="post">
					<?php
					if ($tpl['option_arr']['o_seo_url'] == 'No')
					{ 
						?>
						<input type="hidden" name="controller" value="pjListings" />
						<input type="hidden" name="action" value="pjActionCars" />
						<?php
					}
					?>
					<input type="hidden" name="listing_search" value="1" />
					
					<input id="pjAcSearchCarType" type="hidden" name="car_type"/>
					
					<input type="hidden" id="pjAcSearchYearFrom" name="year_from" />
					<input type="hidden" id="pjAcSearchYearTo" name="year_to" />
					
					<input type="hidden" id="pjAcSearchMileageFrom" name="mileage_from" />
					<input type="hidden" id="pjAcSearchMileageTo" name="mileage_to" />
					
					<input type="hidden" id="pjAcSearchPriceFrom" name="price_from" />
					<input type="hidden" id="pjAcSearchPriceTo" name="price_to" />
					
					<input type="hidden" id="pjAcSearchPowerFrom" name="power_from" />
					<input type="hidden" id="pjAcSearchPowerTo" name="power_to" />
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php __('front_label_car_type'); ?>:</label>
							
							<div class="col-sm-8">
								<div class="row">
									<div class="filter-col col-sm-5">
										<input type="checkbox" id="pjAcUsedCars" class="pjAcSearchCarType" name="crl_cartype_search" value="used"/>
										<label for="pjAcUsedCars"><?php __('front_label_used_cars'); ?></label>
									</div><!-- /.filter-col col-sm-5 -->
									
									<div class="filter-col col-sm-5">
										<input type="checkbox" id="pjAcNewCars" class="pjAcSearchCarType" name="crl_cartype_search" value="new"/>
										<label for="pjAcNewCars"><?php __('front_label_new_cars'); ?></label>
									</div><!-- /.filter-col col-sm-5 -->
								</div><!-- /.row -->
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->

						<div class="form-group">
							<label class="col-sm-4 control-label"><?php __('front_label_make'); ?></label>

							<div class="col-sm-8">
								<select id="pjAcSearchMake" name="make_id" class="form-control">
									<option value="">-- <?php __('front_label_all'); ?> --</option>
									<?php
									foreach($tpl['make_arr'] as $k => $v)
									{
										?><option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option><?php
									} 
									?>
								</select>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->

						<div class="form-group">
							<label class="col-sm-4 control-label"><?php __('front_label_model'); ?></label>

							<div id="pjAcSearchModel" class="col-sm-8">
								<select class="form-control">
									<option value="">-- <?php __('front_label_all'); ?> --</option>
								</select>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->
			
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php __('front_label_refid'); ?>:</label>

							<div class="col-sm-8">
								<input type="text" name="listing_refid" class="form-control">
							</div>
						</div><!-- /.form-group -->

						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo __('front_label_first_registration'); ?></label>
							
							<div class="col-sm-8">
								<div class="range clearfix">
									<div id="pjAcRegistrationSlider" style="margin-top: 10px;"></div>
									
									<p>
										<span id="pjAcRegistrationLabel"><?php echo $year_from . ' - ' . $year_to; ?></span>
									</p>
								</div><!-- /.range -->
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->
						
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo __('front_label_mileage'); ?>:</label>
							
							<div class="col-sm-8">
								<div class="range clearfix">
									<div id="pjAcMileageSlider" style="margin-top: 10px;"></div>
					
									<p>
										<span id="pjAcMileageLabel"><?php echo $mileage_from . ' - ' . $mileage_to; ?></span>
									</p>
								</div><!-- /.range -->
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->

						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo __('front_label_price'); ?>:</label>

							<div class="col-sm-8">
								<div class="range clearfix">
									<div id="pjAcPriceSlider" style="margin-top: 10px;"></div>
									
									<p>
										<span id="pjAcPriceLabel" data-currency="<?php echo $tpl['option_arr']['o_currency']; ?>"><?php echo $price_from . ' - ' . $price_to; ?></span>
									</p>
								</div><!-- /.range -->
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->

						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo __('front_label_power'); ?>:</label>

							<div class="col-sm-8">
								<div class="range clearfix">
									<div id="pjAcPowerSlider" style="margin-top: 10px;"></div>
									
									<p>
										<span id="pjAcPowerLabel" data-power="<?php echo $tpl['option_arr']['o_power_in']; ?>"><?php echo $power_from . ' - ' . $power_to; ?></span>
									</p>
								</div><!-- /.range -->
							</div><!-- /.col-sm-8 -->
						</div><!-- /.form-group -->

						<div class="form-group">
							<div class="col-sm-8 col-sm-offset-4">
								<button type="submit" class="btn btn-primary"><?php __('front_button_search');?></button>
							</div><!-- /.col-sm-8 col-sm-offset-4 -->
						</div><!-- /.form-group -->
					</div><!-- /.col-md-6 -->

					<div class="col-md-6">
						<br />
						<br />
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
								<div class="form-group">
									<label class="col-sm-4 control-label"><?php echo stripslashes($v); ?>:</label>
		
									<div class="col-sm-8">
										<select name="feature_<?php echo $k; ?>_id" class="form-control">
											<option value="">-- <?php __('front_label_all'); ?> --</option>
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
									</div><!-- /.col-sm-8 -->
								</div><!-- /.form-group -->
								<?php
							}
						} 
						?>
					</div><!-- /.col-md-6 -->
				</form>
			</div><!-- /.panel-body -->
		</div><!-- /.panel -->
	</div><!--  /.container-fluid  -->
</div><!-- /.pjWrapper -->

<?php include_once dirname(__FILE__) . '/elements/loadjs.php';?>