<div class="clear_both">
	<?php
	foreach ($tpl['lp_arr'] as $v)
	{
		?>
		<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
			<label class="title"><?php __('lblListingMetaTitle'); ?></label>
			<span class="inline_block">
				<input type="text" name="i18n[<?php echo $v['id']; ?>][register_meta_title]" class="pj-form-field w500" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['register_meta_title'])); ?>" />
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
			<label class="title"><?php __('lblListingMetaKeywords'); ?></label>
			<span class="inline_block">
				<input type="text" name="i18n[<?php echo $v['id']; ?>][register_meta_keywords]" class="pj-form-field w500" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['register_meta_keywords'])); ?>" />
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
			<label class="title"><?php __('lblListingMetaDesc'); ?></label>
			<span class="inline_block">
				<input type="text" name="i18n[<?php echo $v['id']; ?>][register_meta_description]" class="pj-form-field w500" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['register_meta_description'])); ?>" />
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
		<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button pj-button-save" />
	</p>
</div>