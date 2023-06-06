<?php
mt_srand();
$index = mt_rand(1, 9999);
?>
<div id="pjWrapper">
	<div class="container-fluid">
		<?php include_once dirname(__FILE__) . '/elements/header.php';?>
		
		<p>
			<a href="<?php echo !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['PHP_SELF'] .'?controller=pjListings&amp;action=pjActionCars'. (isset($_GET['iframe']) ? '&amp;iframe' : NULL);?>" class="btn btn-default">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>

				<?php __('front_button_back'); ?>
			</a>
			&nbsp;
			<a href="<?php echo $_SERVER['PHP_SELF'] .'?controller=pjListings&amp;action=pjActionClearCompare'. (isset($_GET['iframe']) ? '&amp;iframe' : NULL);?>">
				<?php __('front_clear_compare_list'); ?>
			</a>
		</p>

		<br>
		<?php
		if(!empty($tpl['arr']))
		{
			?>
			<div class="pjPcs-body">
				<div class="table-responsive pjPcs-table-responsive">
					<table class="pjPcs-table pjPcs-table-main">
						<tbody>
							
							<tr>
								<td>&nbsp;</td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									$listing_title = pjSanitize::html(stripslashes($v['listing_title']));
									if ($tpl['option_arr']['o_seo_url'] == 'No')
									{
										$detail_url = $_SERVER['SCRIPT_NAME'] . '?controller=pjListings&amp;action=pjActionView&amp;id=' . $v['id'] .(isset($_GET['iframe']) ? '&amp;iframe' : NULL);
									} else {
										$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
										$path = $path == '/' ? '' : $path;
										$detail_url = $path .'/'. $controller->friendlyURL($v['listing_title']) . "-". $v['id'] . ".html";
									}
									?><td class="text-primary"><a href="<?php echo $detail_url; ?>"><?php echo $listing_title?></a></td><?php
								} 
								?>
							</tr>
	
							<tr>
								<td><strong><?php __('front_label_make'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo $v['make']?></td><?php
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_model'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo $v['model']?></td><?php
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_price'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									$price = '&nbsp;';
									if(!empty($v['listing_price'])){
										$price = pjUtil::formatCurrencySign($v['listing_price'], $tpl['option_arr']['o_currency'], ' ');
									}
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo $price;?></td><?php
								} 
								?>
							</tr>
							<tr>
							<td><strong><?php __('front_label_first_registration'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									if(!empty($v['listing_year']))
									{
										$_arr = array();
										if(!empty($v['listing_month']))
										{
											$_arr[] = str_pad($v['listing_month'], 2, '0', STR_PAD_LEFT); ;
										}
										$_arr[] = $v['listing_year'];
										?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo join("/", $_arr);?></td><?php
									}else{
										?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>">&nbsp;</td><?php
									}
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_mileage'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], $v['listing_mileage']); ?></td><?php
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_power'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo pjUtil::showPower($tpl['option_arr']['o_power_in'], $v['listing_power']); ?></td><?php
								} 
								?>
							</tr>
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
									<tr>
										<td><strong><?php echo stripslashes($v); ?></strong></td>
										<?php
										foreach($tpl['arr'] as $arr)
										{ 
											foreach ($tpl['feature_arr'] as $feature)
											{
												if ($feature['type'] == $k)
												{
													if (isset($arr['feature_'.$k.'_id']) && $arr['feature_'.$k.'_id'] == $feature['id'])
													{
														?><td data-th="<?php echo pjSanitize::html(stripslashes($arr['listing_title']));?>"><?php echo stripslashes($feature['name']); ?></td><?php
													}
												}
											}
										} 
										?>
									</tr>		
									<?php
								}
							} 
							?>
							<tr>
								<td><strong><?php __('front_label_refid'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo stripslashes($v['listing_refid']); ?></td><?php
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_views'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo stripslashes($v['views']); ?></td><?php
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_extras'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>"><?php echo stripslashes($v['extras']); ?></td><?php
								} 
								?>
							</tr>
							<tr>
								<td><strong><?php __('front_label_description'); ?></strong></td>
								<?php
								foreach($tpl['arr'] as $v)
								{
									?><td data-th="<?php echo pjSanitize::html(stripslashes($v['listing_title']));?>" valign="top"><?php echo nl2br(stripslashes($v['listing_description']));?></td><?php
								} 
								?>
							</tr>
						</tbody>
					</table>
				</div><!-- /.table-responsive -->
			</div>
			<?php
		} 
		?>
	</div><!--  /.container-fluid pjVpContainer  -->
</div><!-- /.pjWrapper -->

<?php include_once dirname(__FILE__) . '/elements/loadjs.php';?>