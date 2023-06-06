<?php
$type = '<select name="type" id="type" class="pj-form-field w200 required">';
$type .= sprintf('<option value="">-- %s --</option>', __('lblChoose', true));
foreach (__('feature_types', true) as $k => $v)
{
	$type .= sprintf('<option value="%s">%s</option>', $k, stripslashes($v));
}
$type .= '</select>';

pjAppController::jsonResponse(compact('type'));
?>