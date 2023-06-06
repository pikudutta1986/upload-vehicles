<select name="model_id" id="model_id" class="pj-form-field w150 required">
	<option value="">-- <?php __('lblChoose'); ?> --</option>
	<?php
	foreach ($tpl['model_arr'] as $v)
	{
		?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
	}
	?>
</select>