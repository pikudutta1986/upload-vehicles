<?php
$json_arr = array();
$items = array();
$feature_types = __('feature_types', true);
foreach ($feature_types as $k => $v)
{
	foreach ($tpl['feature_arr'] as $feature)
	{
		if ($feature['type'] == $k)
		{
			if (array_key_exists($k, $items))
			{
				$items[$k]++;
			} else {
				$items[$k] = 1;
			}
		}
	}
}
foreach ($feature_types as $k => $v)
{
	if (isset($items[$k]) && $items[$k] > 0)
	{
		$html_text = 	'<select name="feature_' . $k . '_id" id="feature_' . $k . '_id" class="pj-form-field w200">';
		$html_text .= 		'<option value="">-- ' . __('lblChoose', true) . ' --</option>';
		foreach ($tpl['feature_arr'] as $feature)
		{
			if ($feature['type'] == $k)
			{
				$html_text .= '<option value="' .$feature['id'] . '">' . stripslashes($feature['name']) . '</option>';
			}
		}
		$html_text .= '</select>';
		$json_arr['feature_' . $k . '_id'] = $html_text;
	}
}
pjAppController::jsonResponse($json_arr);
?>