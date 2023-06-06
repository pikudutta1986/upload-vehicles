<?php
mt_srand();
$index = mt_rand(1, 9999);
?>
<div id="pjWrapper">
	<div class="container-fluid">
		<?php 
		include_once dirname(__FILE__) . '/elements/header.php';
		?>
		
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo __('front_label_register_title'); ?>
					</div><!-- /.panel-heading -->

					<div class="panel-body">
						<form id="pjAcRegisterForm" name="pjAcRegisterForm" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionRegister" method="POST" class="crl-form" class="form-horizontal" role="form">
							<input type="hidden" name="listing_register" value="1" />
							
							<?php
							if (isset($_GET['err']))
							{
								$status = __('register_status', true);
								switch ($_GET['err'])
								{
									case 9999:
										?><div class="alert alert-success"><?php echo $status[9999]; ?></div><?php
										break;
									case 9998:
										?><div class="alert alert-success"><?php echo $status[9998]; ?></div><?php
										break;
									default:
										?><div class="alert alert-danger"><?php echo @$status[$_GET['err']]; ?></div><?php
								}
							}
							?>
							
							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_email'); ?></label>

								<div class="col-sm-8">
									<input type="email" name="register_email" value="<?php echo isset($_GET['register_email']) ? htmlspecialchars(stripslashes($_GET['register_email'])) : NULL; ?>" class="form-control required email" data-msg-required="<?php __('front_field_required');?>" data-msg-email="<?php __('front_email_invalid');?>">
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_password');?></label>

								<div class="col-sm-8">
									<input type="password" id="pjAcRegisterPassword" name="register_password" class="form-control required" data-msg-required="<?php __('front_field_required');?>">
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_retype_password'); ?></label>

								<div class="col-sm-8">
									<input type="password" name="register_password_repeat" class="form-control required" data-msg-required="<?php __('front_field_required');?>" data-msg-equalTo="<?php __('front_password_not_match');?>">
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_name'); ?></label>

								<div class="col-sm-8">
									<input type="text" name="name" class="form-control required" value="<?php echo isset($_GET['name']) ? htmlspecialchars(stripslashes($_GET['name'])) : NULL; ?>" data-msg-required="<?php __('front_field_required');?>">
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_captcha'); ?></label>
								<div class="col-sm-8">
									<div class="row">
										<div class="col-sm-4">
											<input type="text" name="captcha" class="form-control required" maxlength="6" autocomplete="off" data-msg-required="<?php __('front_field_required');?>" data-msg-remote="<?php __('front_captcha_incorrect');?>">
										</div><!-- /.col-sm-4 -->

										<div class="col-sm-8">
											<img id="pjCrCaptchaImage" class="pjCrCaptchaImage" class="crl-captcha" src="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFront&amp;action=pjActionCaptcha&amp;rand=<?php echo rand(1, 999999); ?>" alt="CAPTCHA" style="vertical-align: middle;" />
										</div><!-- /.col-sm-8 -->
									</div><!-- /.row -->
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-8">
									<button type="submit" class="btn btn-primary"><?php __('front_menu_register');?></button>
								</div>
							</div>
						</form>
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div><!-- /.col-md-6 -->

			<div class="content col-md-6">
				<div class="panel-body">
					<div class="hidden-xs">
						<br />
						<br />
						<br />
					</div><!-- /.hidden-xs -->
					
					<p><?php __('front_label_register_text');?></p>
				</div><!-- /.panel-body -->
			</div><!-- /.content -->
		</div><!-- /.row -->
	</div><!--  /.container-fluid  -->
</div><!-- /.pjWrapper -->

<?php include_once dirname(__FILE__) . '/elements/loadjs.php';?>