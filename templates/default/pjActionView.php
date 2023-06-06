<?php
mt_srand();
$index = mt_rand(1, 9999);
?>
<div id="pjWrapper">
	<div class="container-fluid">
		<?php 
		include_once dirname(__FILE__) . '/elements/header.php';
		
		if (isset($tpl['arr']))
		{
			$back = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['PHP_SELF'] .'?controller=pjListings&amp;action=pjActionCars'. (isset($_GET['iframe']) ? '&amp;iframe' : NULL);
			$price = '&nbsp;';
			if(!empty($tpl['arr']['listing_price']))
			{
				$price = pjUtil::formatCurrencySign($tpl['arr']['listing_price'], $tpl['option_arr']['o_currency'], ' ');
			}
			$car_type = __('car_types', true);
			?>
			<div class="container-fluid">
				<a href="<?php echo $back;?>" class="btn btn-default">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	
					<?php __('front_button_back'); ?>
				</a>
	
				<div class="row">
					<div class="col-md-8 col-sm-8 col-xs-12">
						<h1><?php echo stripslashes($tpl['arr']['listing_title']); ?></h1>	
					</div><!-- /.col-md-8 -->
	
					<div class="col-md-4 col-sm-4 col-xs-12">
						<h3 class="text-right text-primary"><?php echo $price; ?></h3>
					</div><!-- /.col-md-4 -->
				</div><!-- /.row -->
	
				<br>
			</div><!-- /.container-fluid -->
			<div class="container-fluid">
				<div class="row">
					<?php
					if (count($tpl['gallery_arr']) > 0)
					{ 
						?>
						<div class="col-md-6">
							<a href="<?php echo PJ_INSTALL_URL . $tpl['gallery_arr'][0]['large_path']; ?>" class="thumbnail pjYpFancybox" rel="pjYpGallery">
						    	<img src="<?php echo PJ_INSTALL_URL . $tpl['gallery_arr'][0]['large_path']; ?>" alt="">
						    </a>
							<?php
							unset($tpl['gallery_arr'][0]);
							if(!empty($tpl['gallery_arr']))
							{
								?>
								<div class="col-md-12">
									<div class="row">
										<?php
										foreach ($tpl['gallery_arr'] as $k => $v)
										{
											?>
											<div class="col-md-3 col-sm-3 col-xs-6">
										    	<a rel="pjYpGallery" href="<?php echo PJ_INSTALL_URL . $v['large_path']; ?>" class="thumbnail pjYpFancybox">
										    		<div class="row">
											    		<img src="<?php echo PJ_INSTALL_URL . $v['small_path']; ?>" class="col-xs-12" alt="">
											    	</div>
											    </a>
										    </div><!-- /.col-md-3 -->
											<?php
										} 
										?>
								    </div><!-- /.row -->
								</div><!-- /.col-md-12 -->
								<?php
							} 
							?>
						</div><!-- /.col-md-6 -->
						<?php
					} else {
						?>
						<div class="col-md-6">
							<a rel="lytebox[allphotos]" href="<?php echo PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/'?>842x582.jpg" class="thumbnail" data-apanel="0">
								<img src="<?php echo PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/'?>842x582.jpg" alt=""/>
							</a>
						</div>
						<?php
					}
					?>

					<div class="col-md-6">
						<ul class="list-group">
							<li class="list-group-item clearfix"><span class="pull-left"><?php echo __('front_label_type'); ?></span> <strong class="pull-right"><?php echo $car_type[$tpl['arr']['car_type']];?></strong></li>
							<li class="list-group-item clearfix"><span class="pull-left"><?php echo __('front_label_make'); ?></span> <strong class="pull-right"><?php echo stripslashes($tpl['arr']['make']); ?></strong></li>
							<li class="list-group-item clearfix"><span class="pull-left"><?php echo __('front_label_model'); ?></span> <strong class="pull-right"><?php echo stripslashes($tpl['arr']['model']); ?></strong></li>
							<?php
							if (!empty($tpl['arr']['listing_year']))
							{ 
								$_arr = array();
								if(!empty($tpl['arr']['listing_month']))
								{
									$_arr[] = str_pad($tpl['arr']['listing_month'], 2, '0', STR_PAD_LEFT); ;
								}
								$_arr[] = $tpl['arr']['listing_year'];
								?><li class="list-group-item clearfix"><span class="pull-left"><?php echo __('front_label_first_registration'); ?></span> <strong class="pull-right"><?php echo implode("/", $_arr);?></strong></li><?php
							}
							if (!empty($tpl['arr']['listing_mileage']))
							{ 
								?><li class="list-group-item clearfix"><span class="pull-left"><?php echo __('front_label_mileage'); ?></span> <strong class="pull-right"><?php echo pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], $tpl['arr']['listing_mileage']); ?></strong></li><?php
							}
							if (!empty($tpl['arr']['listing_power']))
							{ 
								?><li class="list-group-item clearfix"><span class="pull-left"><?php echo __('front_label_power'); ?></span> <strong class="pull-right"><?php echo pjUtil::showPower($tpl['option_arr']['o_power_in'], $tpl['arr']['listing_power']); ?></strong></li><?php
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
									foreach ($tpl['feature_arr'] as $feature)
									{
										if ($feature['type'] == $k)
										{
											if (isset($tpl['arr']['feature_'.$k.'_id']) && $tpl['arr']['feature_'.$k.'_id'] == $feature['id'])
											{
												?>
												<li class="list-group-item clearfix"><span class="pull-left"><?php echo stripslashes($v); ?></span> <strong class="pull-right"><?php echo stripslashes($feature['name']); ?></strong></li>
												<?php	
											} 
										}
									}
								}
							} 
							?>
							<li class="list-group-item clearfix"><span class="pull-left"><?php __('front_label_refid'); ?></span> <strong class="pull-right"><?php echo stripslashes($tpl['arr']['listing_refid']); ?></strong></li>
						</ul>
						
						<p>
							<a href="#" class="pjAcAddCompare" data-id="<?php echo $tpl['arr']['id'];?>" style="display:<?php echo isset($_SESSION[$controller->compareList]) ? (!in_array($tpl['arr']['id'], $_SESSION[$controller->compareList]) ? 'inline-block' : 'none') : 'inline-block'; ?>">
								<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
								<?php __('front_label_add_compare');?>
							</a>
							<a href="#" class="pjAcRemoveCompare" data-id="<?php echo $tpl['arr']['id'];?>" style="display:<?php echo isset($_SESSION[$controller->compareList]) ? (in_array($tpl['arr']['id'], $_SESSION[$controller->compareList]) ? 'inline-block' : 'none') : 'none'; ?>">
								<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
								<?php __('front_label_remove_compare');?>
							</a>
							&nbsp;
							&nbsp;

							<br class="visible-xs-block">
						
							<a href="#" class="contact-dealer pjAcContactDealer">
								<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>

								<?php echo __('front_label_contact_dealer'); ?>
							</a>
						</p>

						<div id="pjAcContactSection" class="panel panel-default fill-form pjAcContactSection" style="display:<?php echo $tpl['contact_message'] != null ? 'block' : 'none;'?>">
							<div class="panel-heading">
								<?php echo __('front_label_contact_title'); ?>
							</div><!-- /.panel-heading -->

							<div class="panel-body">
								<form id="frmAcContactDealer" name="frmAcContactDealer" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionContact" method="post" class="form-horizontal" role="form">
									<input type="hidden" id="crl_install_folder" name="install_folder" value="<?php echo PJ_INSTALL_FOLDER; ?>" />
									<input type="hidden" name="listing_contact" value="1" />
									<input type="hidden" name="listing_id" value="<?php echo $tpl['arr']['id'];?>" />									
									<?php
									if ($tpl['contact_message'] != null)
									{
										$status = __('contact_status', true);
										switch ($tpl['contact_message'])
										{
											case 9999:
												?><div class="alert alert-success"><?php echo $status[9999]; ?></div><?php
												break;
											default:
												?><div class="alert alert-danger"><?php echo @$status[$tpl['contact_message']]; ?></div><?php
										}
									}
									?>
									<div class="form-group">
										<label for="inputName" class="col-sm-4"><?php __('front_label_name'); ?></label>

										<div class="col-sm-8">
											<input type="text" name="contact_name" class="form-control required" data-msg-required="<?php __('front_field_required');?>">
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div>

									<div class="form-group">
										<label for="inputName" class="col-sm-4"><?php __('front_label_email'); ?></label>

										<div class="col-sm-8">
											<input type="text" name="contact_email" class="form-control required email" data-msg-required="<?php __('front_field_required');?>" data-msg-email="<?php __('front_email_invalid');?>">
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div>

									<div class="form-group">
										<label for="inputQuestion" class="col-sm-4"><?php echo __('front_label_your_question'); ?>:</label>
										
										<div class="col-sm-8">
											<textarea name="contact_message" cols="30" rows="10" class="form-control required" data-msg-required="<?php __('front_field_required');?>"><?php echo pjSanitize::html($tpl['message']);?></textarea>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-8 col-sm-offset-4">
											<div class="row">
												<div class="col-sm-4">
													<input type="text" name="captcha" class="form-control required" placeholder="<?php __('front_label_captcha'); ?>" maxlength="6" autocomplete="off" data-msg-required="<?php __('front_field_required');?>" data-msg-remote="<?php __('front_captcha_incorrect');?>">
												</div><!-- /.col-sm-4 -->

												<div class="col-sm-8">
													<img id="pjCrCaptchaImage" class="pjCrCaptchaImage crl-captcha" src="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFront&amp;action=pjActionCaptcha&amp;rand=<?php echo rand(1, 999999); ?>" alt="CAPTCHA" style="vertical-align: middle;" />
												</div><!-- /.col-sm-8 -->
											</div><!-- /.row -->
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-offset-4 col-sm-8">
											<button type="submit" class="btn btn-primary"><?php __('front_button_send');?></button>

											<button type="button" class="btn btn-primary cancel pjAcCancelContact"><?php __('front_button_cancel');?></button>
										</div>
									</div>
								</form>
							</div><!-- /.panel-body -->
						</div><!-- /.panel panel-default -->
					</div><!-- /.col-md-6 -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
			<?php
		} else{
			__('front_non_existing_car');
		}
		?>
		<div class="container-fluid">
			<?php
			if(!empty($tpl['arr']['extras']))
			{ 
				$extra_arr = explode(", ", $tpl['arr']['extras']);
				?>
				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading">
						<?php __('front_label_extras');?>
					</div>
	
					<div class="panel-body">
						<ul class="clearfix">
							<?php
							foreach($extra_arr as $extra)
							{
								?>
								<li class="col-md-3"><?php echo $extra;?></li>
								<?php
							} 
							?>
						</ul>
					</div>
				</div><!-- /. panel -->
				<?php
			}
			if(!empty($tpl['arr']['listing_description']))
			{ 
				?>
				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading">
						<?php __('front_label_description');?>
					</div>
	
					<div class="panel-body">
						<div class="container-fluid">
							<?php echo nl2br(stripslashes($tpl['arr']['listing_description']));?>
						</div><!-- /.container-fluid -->
					</div>
				</div><!-- /. panel -->
				<?php
			}
			ob_start();
			if (!empty($tpl['arr']['contact_title']) || !empty($tpl['arr']['name']))
			{
				$personal_titles = __('personal_titles', true, false);
				$name_arr = array();
				if(!empty($tpl['arr']['contact_title']))
				{
					$name_arr[] = $personal_titles[$tpl['arr']['contact_title']];
				}
				if(!empty($tpl['arr']['name']))
				{
					$name_arr[] = stripslashes($tpl['arr']['name']);
				}
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_name'); ?></span> 

						<br>

						<strong><?php echo join(" ", $name_arr); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['contact_phone']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_phone'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['contact_phone']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['contact_mobile']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_mobile'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['contact_mobile']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['contact_fax']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_fax'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['contact_fax']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['email']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_email'); ?></span> 

						<br>

						<strong><?php echo !preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i', $tpl['arr']['email']) ? $tpl['arr']['email'] : '<a href="mailto:'.$tpl['arr']['email'].'">'.$tpl['arr']['email'].'</a>'; ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['contact_url']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_website'); ?></span> 

						<br>

						<strong><?php echo preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $tpl['arr']['contact_url']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['address_postcode']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_zip'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['address_postcode']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['address_content']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_address'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['address_content']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['address_city']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_city'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['address_city']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['address_state']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_state'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['address_state']); ?></strong>
					</address>
				</li>
				<?php
			}
			if (!empty($tpl['arr']['country_title']))
			{
				?>
				<li class="col-md-6">
					<address>
						<span><?php echo __('front_label_country'); ?></span> 

						<br>

						<strong><?php echo stripslashes($tpl['arr']['country_title']); ?></strong>
					</address>
				</li>
				<?php
			}
			$ob_contact = ob_get_contents();
			ob_end_clean();
			if (!empty($ob_contact) && $tpl['arr']['owner_show'] == 'T')
			{
				?>
				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading">
						<?php echo __('front_label_contact_details'); ?>
					</div>
	
					<div class="panel-body">
						<ul class="list-unstyled">
							<?php echo $ob_contact;?>
						</ul>
					</div>
				</div><!-- /. panel -->
				<?php
			} 
			?>
		</div><!-- /.container-fluid -->
	</div><!--  /.container-fluid pjVpContainer  -->
</div><!-- /.pjWrapper -->

<?php include_once dirname(__FILE__) . '/elements/loadjs.php';?>