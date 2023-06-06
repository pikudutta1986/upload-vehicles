<?php
$make = '<select name="make_id" id="make_id" class="pj-form-field w200 required">';
$make .= sprintf('<option value="">-- %s --</option>', __('lblChoose', true));
foreach ($tpl['make_arr'] as $v)
{
	$make .= sprintf('<option value="%u">%s</option>', $v['id'], stripslashes($v['name']));
}
$make .= '</select>';

pjAppController::jsonResponse(compact('make'));
?>