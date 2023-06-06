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
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
	
	pjUtil::printNotice(__('infoEmailTitle', true), __('infoEmailBody', true));
	
	$locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : $controller->getLocaleId();
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form" id="frmEmails">
		<input type="hidden" name="options_update" value="1" />
		<?php
		if(!empty($tpl['arr']))
		{
			?>
			<input type="hidden" name="email_update" value="1" />
			<input type="hidden" name="id" value="<?php echo $tpl['arr']['id'];?>" />
			<?php
		}else{
			?><input type="hidden" name="email_create" value="1" /><?php
		} 
		?>
		<input type="hidden" name="next_action" value="pjActionEmails" />
		
		<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
		<div class="multilang b10"></div>
		<?php endif;?>
		<div class="clear_both">
			<?php
			if($controller->isAdmin() || $controller->isEditor())
			{
				?>
				<fieldset class="overflow b10">
					<legend><span><?php __('lblListingRegistrationNotifyEmail'); ?></span><a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblRegistrationEmailTip');?>"></a></legend>
					<?php
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblEmailSubject'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][new_account_subject]" class="pj-form-field w500<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['new_account_subject'])); ?>" />
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title">
								<?php __('lblEmailBody'); ?>
								<span class="block t10">
									<?php echo nl2br(__('lblListingRegisterNotifyTokens', true)); ?>
								</span>
							</label>
							<span class="inline_block">
								<textarea name="i18n[<?php echo $v['id']; ?>][new_account_message]" class="pj-form-field textarea h250 w500<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>"><?php echo stripslashes(@$tpl['arr']['i18n'][$v['id']]['new_account_message']); ?></textarea>
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					?>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
					</p>
				</fieldset>
				<fieldset class="overflow b10">
					<legend><span><?php __('lblPredefinedMessage'); ?></span><a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblPredefineTip');?>"></a></legend>
					<?php
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblEmailSubject'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][predefined_subject]" class="pj-form-field w500<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['predefined_subject'])); ?>" />
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title">
								<?php __('lblMessage'); ?>
								<span class="block t10">
									<?php echo nl2br(__('lblMessageTokens', true)); ?>
								</span>
							</label>
							<span class="inline_block">
								<textarea name="i18n[<?php echo $v['id']; ?>][predefined_message]" class="pj-form-field textarea h250 w500<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>"><?php echo stripslashes(@$tpl['arr']['i18n'][$v['id']]['predefined_message']); ?></textarea>
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					?>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
					</p>
				</fieldset>
				
				<fieldset class="overflow b10">
					<legend><span><?php __('lblVendorAccountConfirmation'); ?></span><a href="#" class="pj-form-langbar-tip listing-tip" title="<?php __('lblVendorAccountConfirmationTip');?>"></a></legend>
					<?php
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title"><?php __('lblEmailSubject'); ?></label>
							<span class="inline_block">
								<input type="text" name="i18n[<?php echo $v['id']; ?>][active_account_subject]" class="pj-form-field w500<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['active_account_subject'])); ?>" />
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					foreach ($tpl['lp_arr'] as $v)
					{
						?>
						<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
							<label class="title">
								<?php __('lblMessage'); ?>
								<span class="block t10">
									<?php echo nl2br(__('lblActiveAccountTokens', true)); ?>
								</span>
							</label>
							<span class="inline_block">
								<textarea name="i18n[<?php echo $v['id']; ?>][active_account_message]" class="pj-form-field textarea h250 w500<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>"><?php echo stripslashes(@$tpl['arr']['i18n'][$v['id']]['active_account_message']); ?></textarea>
								<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
								<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
								<?php endif;?>
							</span>
						</p>
						<?php
					}
					?>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
					</p>
				</fieldset>
				<?php
			}
			?>
		</div>
	</form>
	<?php 
}
?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
(function ($) {
	$(function() {
		$(".multilang").multilang({
			langs: <?php echo $tpl['locale_str']; ?>,
			flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
			select: function (event, ui) {
				
			}
		});
		$(".multilang").find("a[data-index='<?php echo $locale; ?>']").trigger("click");
	});
})(jQuery_1_8_2);
</script>