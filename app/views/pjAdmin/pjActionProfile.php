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

	if (isset($_GET['err']))

	{

		$titles = __('error_titles', true);

		$bodies = __('error_bodies', true);

		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);

	}

	?>

	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">

		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">

			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile"><?php __('menuProfile'); ?></a></li>

		</ul>

	</div>

	

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile" method="post" id="frmUpdateProfile" class="form pj-form">

		<input type="hidden" name="profile_update" value="1" />

		<p>

			<label class="title"><?php __('email'); ?>:</label>

			<span class="pj-form-field-custom pj-form-field-custom-before">

				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>

				<input type="text" name="email" id="email" class="pj-form-field required email w200" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['email'])); ?>" />

			</span>

		</p>

		<p>

			<label class="title"><?php __('pass'); ?>:</label>

			<span class="pj-form-field-custom pj-form-field-custom-before">

				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-password"></abbr></span>

				<input type="text" name="password" id="password" class="pj-form-field required w200" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['password'])); ?>" autocomplete="off" />

			</span>

		</p>

		<p>

			<label class="title"><?php __('lblName'); ?></label>

			<span class="inline_block">

				<input type="text" name="name" id="name" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['name'])); ?>" class="pj-form-field w250 required" />

			</span>

		</p>


		<p>

			<label class="title">Show items in home page</label>

			<select name="homepage_items" id="homepage_items" class="pj-form-field">

				<option value="">-- <?php __('lblChoose'); ?> --</option>

				<?php

				for ($i = 3; $i < 21; $i+=3)

				{

					?><option value="<?php echo $i; ?>" <?php echo $tpl['arr']["homepage_items"] == $i ? 'selected' : '' ; ?> ><?php echo $i; ?></option><?php 

				}

				?>

			</select>

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

		</p>

	</form>

	<?php

}

?>