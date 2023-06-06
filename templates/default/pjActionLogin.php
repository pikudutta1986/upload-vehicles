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
					<div class="panel-heading clearfix">
						<?php echo __('front_label_login_title'); ?>
					</div><!-- /.panel-heading -->

					<div class="panel-body">
						<form id="pjAcLoginForm" name="pjAcLoginForm" action="<?php echo PJ_INSTALL_URL;?>index.php?controller=pjAdmin&amp;action=pjActionLogin" method="post" target="_blank" class="form-horizontal" role="form">
							<input type="hidden" name="login_user" value="1" />
							
							<div class="form-group">
								
							</div><!-- /.form-group -->

							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_email'); ?></label>

								<div class="col-sm-8">
									<input type="email" name="login_email" class="form-control required email" data-msg-required="<?php __('front_field_required');?>" data-msg-email="<?php __('front_email_invalid');?>">
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label"><?php __('front_label_password'); ?></label>
								
								<div class="col-sm-8">
									<input type="password" name="login_password" class="form-control required" autocomplete="off" data-msg-required="<?php __('front_field_required');?>" />
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-8">
									
									<button type="submit" class="btn btn-primary"><?php __('front_menu_login');?></button>
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
					
					<p><?php __('front_label_login_text');?></p>
				</div><!-- /.panel-body -->
			</div><!-- /.content -->
		</div><!-- /.row -->
	</div><!--  /.container-fluid  -->
</div><!-- /.pjWrapper -->

<?php include_once dirname(__FILE__) . '/elements/loadjs.php';?>