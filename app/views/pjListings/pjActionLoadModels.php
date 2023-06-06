<select name="model_id" class="form-control">
	<option value="">-- <?php __('front_label_all'); ?> --</option>
	<?php
	foreach ($tpl['model_arr'] as $v)
	{
		?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?></option><?php
	}
	?>
</select>