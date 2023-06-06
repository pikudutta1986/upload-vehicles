<?php
if (isset($_GET['query_string']))
{
	parse_str($_GET['query_string'], $output);
}
$size = 'large';
if(isset($_GET['size']) && in_array($_GET['size'], array('large', 'medium', 'small')))
{
	$size = $_GET['size'];
}

$rec_width = $tpl['arr']['source_width'];
$rec_height = $tpl['arr']['source_height'];
switch ($size) {
	case 'large':
		pjUtil::printNotice(__('plugin_gallery_resize_large_title', true), __('plugin_gallery_resize_large_body', true));
		break;
	case 'small':
		$rec_width = $tpl['gallery_set_arr']['small_width'];
		$rec_height = $tpl['gallery_set_arr']['small_height'];
		
		$title = __('plugin_gallery_resize_small_title', true);
		$body = __('plugin_gallery_resize_small_body', true);
		
		if(isset($_GET['origin']))
		{
			$title = __('plugin_gallery_resize_thumb_title', true);
			$body = __('plugin_gallery_resize_thumb_body', true);
		}
		
		$title = str_replace("{SIZE}", $rec_width . ' x ' . $rec_height, $title);
		$body = str_replace("{STAG}", '<a href="'.$_SERVER['PHP_SELF'].'?controller=pjGallery&action=pjActionCrop&id='.$tpl['arr']['id'].'&size=small&origin=1">', $body);
		$body = str_replace("{ETAG}", '</a>', $body);
		pjUtil::printNotice($title, $body, false);
		break;

	case 'medium':
		$rec_width = $tpl['gallery_set_arr']['medium_width'];
		$rec_height = $tpl['gallery_set_arr']['medium_height'];
		
		$title = __('plugin_gallery_resize_medium_title', true);
		$body = __('plugin_gallery_resize_medium_body', true);
		
		if(isset($_GET['origin']))
		{
			$title = __('plugin_gallery_resize_preview_title', true);
			$body = __('plugin_gallery_resize_preview_body', true);
		}
		
		$title = str_replace("{SIZE}", $rec_width . ' x ' . $rec_height, $title);
		$body = str_replace("{STAG}", '<a href="'.$_SERVER['PHP_SELF'].'?controller=pjGallery&action=pjActionCrop&id='.$tpl['arr']['id'].'&size=medium&origin=1">', $body);
		$body = str_replace("{ETAG}", '</a>', $body);
		pjUtil::printNotice($title, $body, false);
		
		break;
}
?>
<div class="plugin_gallery_crop_nav">
	<label><?php __('plugin_gallery_image_version');?>:</label>
	<span class="plugin_gallery_button_size">
		<span>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjGallery&amp;action=pjActionCrop&amp;id=<?php echo $tpl['arr']['id'];?>&amp;size=large<?php echo isset($output) && !empty($output) ? '&amp;query_string=' . urlencode($_GET['query_string']) : NULL;?>" class="pj-button<?php echo $size == 'large' ? ' pj-button-active' : NULL;?>"><?php __('plugin_gallery_original'); ?></a>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjGallery&amp;action=pjActionCrop&amp;id=<?php echo $tpl['arr']['id'];?>&amp;size=small<?php echo isset($output) && !empty($output) ? '&amp;query_string=' . urlencode($_GET['query_string']) : NULL;?>" class="pj-button<?php echo $size == 'small' ? ' pj-button-active' : NULL;?>"><?php __('plugin_gallery_thumb'); ?></a>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjGallery&amp;action=pjActionCrop&amp;id=<?php echo $tpl['arr']['id'];?>&amp;size=medium<?php echo isset($output) && !empty($output) ? '&amp;query_string=' . urlencode($_GET['query_string']) : NULL;?>" class="pj-button<?php echo $size == 'medium' ? ' pj-button-active' : NULL;?>"><?php __('plugin_gallery_preview'); ?></a>
		</span>
	</span>
</div>
<?php

if (isset($tpl['arr'], $tpl['arr'][$size . '_path']) && !empty($tpl['arr'][$size . '_path']) && is_file(PJ_INSTALL_PATH . $tpl['arr'][$size . '_path']))
{
	$back_url = sprintf('%s?controller=pjGallerySet&amp;action=pjActionUpdate&amp;id=%u', $_SERVER['PHP_SELF'], $tpl['arr']['foreign_id']);
	if (isset($output) && !empty($output))
	{
		$back_url = sprintf('%s?%s', $_SERVER['PHP_SELF'], http_build_query($output));
	}	
	?>
	<div class="plugin_gallery_crop_nav">
		<a href="<?php echo $back_url; ?>" class="pj-button plugin_gallery_btn"><?php __('plugin_gallery_crop_btn_back'); ?></a>
		
		<?php
		if($size == 'medium' || $size == 'small')
		{ 
			?>
			<div class="plugin_gallery_crop_actions">
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-rotate-left" title="<?php __('plugin_gallery_crop_rotate_left', false, true); ?>"><i class="plugin_gallery_icon_rotate_left"></i></button>
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-zoom-out" title="<?php __('plugin_gallery_crop_zoom_out', false, true); ?>"><i class="plugin_gallery_icon_zoom_out"></i></button>
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-fit" title="<?php __('plugin_gallery_crop_fit_image', false, true); ?>"><i class="plugin_gallery_icon_fit"></i></button>
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-zoom-in" title="<?php __('plugin_gallery_crop_zoom_in', false, true); ?>"><i class="plugin_gallery_icon_zoom_in"></i></button>
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-rotate-right" title="<?php __('plugin_gallery_crop_rotate_right', false, true); ?>"><i class="plugin_gallery_icon_rotate_right"></i></button>
			</div>
			<?php
		}else{
			?>
			<div class="plugin_gallery_rotate_actions">
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-rotate-left" title="<?php __('plugin_gallery_crop_rotate_left', false, true); ?>"><i class="plugin_gallery_icon_rotate_left"></i></button>
				<button type="button" class="pj-button plugin_gallery_btn plugin_gallery_btn_icon btn-cropper-rotate-right" title="<?php __('plugin_gallery_crop_rotate_right', false, true); ?>"><i class="plugin_gallery_icon_rotate_right"></i></button>
			</div>
			<?php
		} 
		?>
		
		<form action="" method="post" class="plugin_gallery_crop_form" id="frmGalleryCrop" data-size="<?php echo $size;?>" data-rw="<?php echo $rec_width; ?>" data-rh="<?php echo $rec_height; ?>">
			<input type="hidden" name="do_crop" value="1" />
			<?php 
			if(isset($_GET['origin']))
			{
				?><input type="hidden" name="<?php echo $size == 'small' ? 'create_thumb': 'create_preview';?>" value="1" /><?php
			}
			?>
			<input type="hidden" name="size" value="<?php echo $size;?>" />
			<input type="hidden" name="id" value="<?php echo pjSanitize::html($tpl['arr']['id']); ?>" />
			<input type="hidden" name="foreign_id" value="<?php echo pjSanitize::html($tpl['arr']['foreign_id']); ?>" />
			<input type="hidden" name="x" value="" />
			<input type="hidden" name="y" value="" />
			<input type="hidden" name="width" value="" />
			<input type="hidden" name="height" value="" />
			<input type="hidden" name="rotate" value="" />
			<button type="submit" class="pj-button plugin_gallery_btn plugin_gallery_btn_primary"><?php $size == 'medium' || $size == 'small' ? __('plugin_gallery_crop_btn_crop') : __('plugin_gallery_crop_btn_save'); ?></button>
		</form>
	</div>
	<?php
	$source_path = PJ_INSTALL_FOLDER . $tpl['arr'][$size . '_path'];
	if(isset($_GET['origin']))
	{
		$source_path = PJ_INSTALL_FOLDER . $tpl['arr']['source_path'];
	} 
	?>
	<div style="height: 500px" data-src="<?php echo pjSanitize::html($source_path); ?>?r=<?php echo rand(1,9999); ?>" id="plugin_gallery_img_wrap"></div>
	<?php
}
?>
