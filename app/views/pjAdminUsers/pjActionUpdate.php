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
	
	pjUtil::printNotice(__('infoUpdateUserTitle', true), __('infoUpdateUserBody', true)); 
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionUpdate" method="post" id="frmUpdateUser" class="form pj-form">
		<input type="hidden" name="user_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<p>
			<label class="title"><?php __('lblRole'); ?></label>
			<?php
			if ((int) $tpl['arr']['id'] !== 1)
			{
				?>
				<span class="inline_block">
					<select name="role_id" id="role_id" class="pj-form-field required">
						<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach ($tpl['role_arr'] as $v)
					{
						?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['role_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['role']); ?></option><?php
					}
					?>
					</select>
				</span>
				<?php
			} else {
				?>
				<span class="left">
				<?php
				foreach ($tpl['role_arr'] as $v)
				{
					if ($tpl['arr']['role_id'] == $v['id'])
					{
						echo stripslashes($v['role']);
						break;
					}
				}
				?>
				</span>
				<input type="hidden" name="role_id" value="1" />
				<?php
			}
			?>
		</p>
		<p>
			<label class="title"><?php __('email'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
				<input type="text" name="email" id="email" class="pj-form-field required email w200" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['email'])); ?>" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('pass'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-password"></abbr></span>
				<input type="text" name="password" id="password" class="pj-form-field required w200" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['password'])); ?>" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblName'); ?></label>
			<span class="inline_block">
				<input type="text" name="name" id="name" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['name'])); ?>" class="pj-form-field w250 required" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblStatus'); ?></label>
			<?php
			if ((int) $tpl['arr']['id'] !== 1)
			{
				?>
				<span class="inline_block">
					<select name="status" id="status" class="pj-form-field required">
						<option value="">-- <?php __('lblChoose'); ?>--</option>
						<?php
						foreach (__('u_statarr', true) as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['status'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</span>
				<?php
			} else {
				$status = __('u_statarr', true)
				?>
				<span class="left"><?php echo @$status[$tpl['arr']['status']]; ?></span>
				<input type="hidden" name="status" value="T" />
				<?php
			}
			?>
		</p>
		<p>
			<label class="title"><?php __('lblUserCreated'); ?></label>
			<span class="left"><?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['created'])); ?>, <?php echo date("H:i", strtotime($tpl['arr']['created'])); ?></span>
		</p>
		<p>
			<label class="title"><?php __('lblIp'); ?></label>
			<span class="left"><?php echo $tpl['arr']['ip']; ?></span>
		</p>
		
		<p class="user-contact">
			<label class="title"><?php __('lblContactTitle'); ?></label>
			<select name="contact_title" id="contact_title" class="pj-form-field">
				<option value="">-- <?php __('lblChoose'); ?> --</option>
				<?php
				foreach (__('personal_titles', true) as $k => $v)
				{
					?><option value="<?php echo $k; ?>" <?php echo $tpl['arr']['contact_title'] == $k ? 'selected="selected"' : null; ?>><?php echo stripslashes($v); ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="user-contact">
			<label class="title"><?php __('lblContactPhone'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
				<input type="text" name="contact_phone" id="contact_phone" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['contact_phone'])); ?>" class="pj-form-field w250" placeholder="(123) 456-7890" />
			</span>
		</p>
		<p class="user-contact">
			<label class="title"><?php __('lblContactMobile'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
				<input type="text" name="contact_mobile" id="contact_mobile" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['contact_mobile'])); ?>" class="pj-form-field w250" placeholder="(123) 456-7890" />
			</span>
		</p>
		<p class="user-contact">
			<label class="title"><?php __('lblContactFax'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
				<input type="text" name="contact_fax" id="contact_fax" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['contact_fax'])); ?>" class="pj-form-field w250" placeholder="(123) 456-7890" />
			</span>
		</p>
		<p class="user-contact">
			<label class="title"><?php __('lblContactWebsite'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-url"></abbr></span>
				<input type="text" name="contact_url" id="contact_url" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['contact_url'])); ?>" class="pj-form-field w300 validateUrl" placeholder="http://www.domain.com"  />
			</span>
		</p>
		<p>
			<label class="title"><?php __('lblAddress'); ?></label>
			<textarea name="address_content" id="address_content" class="pj-form-field w500 h80"><?php echo htmlspecialchars(stripslashes($tpl['arr']['address_content'])); ?></textarea>
		</p>
		<p>
			<label class="title"><?php __('lblAddressCity'); ?></label>
			<input type="text" name="address_city" id="address_city" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['address_city'])); ?>" class="pj-form-field w200" />
		</p>
		<p>
			<label class="title"><?php __('lblAddressState'); ?></label>
			<input type="text" name="address_state" id="address_state" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['address_state'])); ?>" class="pj-form-field w200" />
		</p>
		<p style="overflow: visible">
			<label class="title"><?php __('lblAddressCountry'); ?></label>
			<select name="address_country" id="address_country" class="pj-form-field w300">
				<option value="">-- <?php __('lblChoose'); ?> --</option>
				<?php
				foreach ($tpl['country_arr'] as $v)
				{
					?><option value="<?php echo $v['id']; ?>" <?php echo $tpl['arr']['address_country'] == $v['id'] ? 'selected="selected"' : null; ?>><?php echo stripslashes($v['name']); ?></option><?php					
				}
				?>
			</select>
		</p>
		<p>
			<label class="title"><?php __('lblAddressPostcode'); ?></label>
			<input type="text" name="address_postcode" id="address_postcode" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['address_postcode'])); ?>"  class="pj-form-field" />
		</p>
		
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
			<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminUsers&action=pjActionIndex';" />
		</p>
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.email_taken = "<?php __('cl_email_taken'); ?>";
	</script>
	<?php
}
?>