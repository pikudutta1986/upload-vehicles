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
	?>
	<?php include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php'; ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminFeatures&amp;action=pjActionCreate" method="post" id="frmCreateFeature" class="pj-form form">
		<input type="hidden" name="feature_create" value="1" />
		<?php $locale = isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : $controller->getLocaleId(); ?>
		<input type="hidden" name="locale" value="<?php echo $locale; ?>" />
		<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
		<div class="multilang"></div>
		<?php endif;?>
		<div class="clear_both">
			<?php
			foreach ($tpl['lp_arr'] as $v)
			{
			?>
				<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
					<label class="title"><?php __('lblFeature'); ?>:</label>
					<span class="inline_block">
						<input type="text" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w300<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" />
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
						<?php endif;?>
					</span>
				</p>
				<?php
			}
			?>
			<p>
				<label class="title"><?php __('lblType'); ?></label>
				<span class="inline_block" id="boxType">
					<select name="type" id="type" class="pj-form-field w200 required">
						<option value="">-- <?php __('lblChoose');?> --</option>
						<?php
						foreach (__('feature_types', true) as $k => $v)
						{
							?><option value="<?php echo $k;?>"><?php echo stripslashes($v);?></option><?php
						} 
						?>
					</select>
				</span>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button" />
				<input type="button" value="<?php __('btnCancel'); ?>" class="pj-button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminFeatures&action=pjActionIndex';" />
			</p>
		</div>
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.field_required = "<?php __('cl_field_required'); ?>";
	myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
	(function ($) {
		$(function() {
			$(".multilang").multilang({
				langs: <?php echo $tpl['locale_str']; ?>,
				flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
				tooltip: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sit amet faucibus enim.",
				select: function (event, ui) {
					$("input[name='locale']").val(ui.index);
					$.get("index.php?controller=pjAdminFeatures&action=pjActionGetLocale", {
						"locale" : ui.index
					}).done(function (data) {
						type = $("#type").find("option:selected").val();
						$("#boxType").html(data.type);
						$("#type").find("option[value='"+type+"']").prop("selected", true);
						$("#type").chosen();
					});
				}
			});
			$(".multilang").find("a[data-index='<?php echo $locale; ?>']").trigger("click");
		});
	})(jQuery_1_8_2);
	</script>
	<?php
}
?>