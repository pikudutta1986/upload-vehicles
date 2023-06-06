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
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php __('lblInstallListing'); ?></a></li>
			<li><a href="#tabs-2"><?php __('lblInstallFeatured'); ?></a></li>
			<li><a href="#tabs-3"><?php __('lblInstallOptional'); ?></a></li>
		</ul>
		<div id="tabs-1">
			<?php pjUtil::printNotice(NULL, __('lblInstallPhp1Title', true), false, false); ?>
			
			<form id="frmListingPage" action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form b20">
				<input type="hidden" name="options_update" value="1" />
				<input type="hidden" name="next_action" value="pjActionInstall" />
				
			</form>
			
			<p style="margin: 0 0 10px; font-weight: bold"><?php __('lblInstallPhp1_1'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:50px">
&lt;?php
ob_start();
?&gt;</textarea>
			<p style="margin: 20px 0 10px; font-weight: bold"><?php __('lblInstallPhp1_2'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:30px">{CRL_LISTINGS}</textarea>
			<p style="margin: 20px 0 10px; font-weight: bold"><?php __('lblInstallPhp1_2a'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:30px">{CRL_META}</textarea>
			<p style="margin: 20px 0 10px; font-weight: bold"><?php __('lblInstallPhp1_3'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:30px">
&lt;?php include '<?php echo dirname($_SERVER['SCRIPT_FILENAME']); ?>/app/views/pjLayouts/pjActionListings.php'; ?&gt;</textarea>
		</div>
		<div id="tabs-2">
			<?php pjUtil::printNotice(NULL, __('lblInstallPhp3Title', true), false, false); ?>
			<p style="margin: 12px 0 10px; font-weight: bold"><?php __('lblInstallFeat_1'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:50px">
&lt;?php
ob_start();
?&gt;</textarea>
			<p style="margin: 20px 0 10px; font-weight: bold"><?php __('lblInstallFeat_2'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:30px">{CRL_FEATURED}</textarea>
			<p style="margin: 20px 0 10px; font-weight: bold"><?php __('lblInstallFeat_3'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:30px">
&lt;?php include '<?php echo dirname($_SERVER['SCRIPT_FILENAME']); ?>/app/views/pjLayouts/pjActionFeatured.php'; ?&gt;</textarea>
		</div>
		<div id="tabs-3">
			<?php pjUtil::printNotice(NULL, __('lblInstallPhp4Title', true), false, false); ?>
			
			<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" class="form pj-form b20">
				<input type="hidden" name="options_update" value="1" />
				<input type="hidden" name="next_action" value="pjActionInstall" />
				<input type="hidden" name="seo_update" value="1" />
				<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
				<input type="hidden" name="seo_tab_id" value="<?php echo isset($_GET['seo_tab_id']) && !empty($_GET['seo_tab_id']) ? $_GET['seo_tab_id'] : 'seo_tabs-1'; ?>" />
				
				<?php
				$listing_page = NULL;
				foreach ($tpl['o_arr'] as $item)
				{
					if ($item['key'] == 'o_listing_page')
					{
						$listing_page = $item['value'];
						?>
						<p>
							<label class="float_left w300 pt5"><?php __('opt_' . $item['key']); ?></label>
							<span class="block overflow float_left">
								<span class="pj-form-field-custom pj-form-field-custom-before">
									<span class="pj-form-field-before"><abbr class="pj-form-field-icon-url"></abbr></span>
									<input type="text" name="value-<?php echo $item['type']; ?>-<?php echo $item['key']; ?>" class="pj-form-field w250 required" value="<?php echo htmlspecialchars(stripslashes($item['value'])); ?>" />
								</span>
							</span>
							<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button float_left l5 align_middle" />
						</p>
						<?php
						break;
					}
				}
				?>
				<?php
				foreach ($tpl['o_arr'] as $item)
				{
					if ($item['key'] == 'o_seo_url')
					{
						?>
						<p>
							<label class="float_left w150 pt5"><?php __('opt_' . $item['key']); ?></label>
							<select name="value-<?php echo $item['type']; ?>-<?php echo $item['key']; ?>" class="pj-form-field float_left">
							<?php
							$default = explode("::", $item['value']);
							$enum = explode("|", $default[0]);
							
							$enumLabels = array();
							if (!empty($item['label']) && strpos($item['label'], "|") !== false)
							{
								$enumLabels = explode("|", $item['label']);
							}
							
							foreach ($enum as $k => $el)
							{
								if ($default[1] == $el)
								{
									?><option value="<?php echo $default[0].'::'.$el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
								} else {
									?><option value="<?php echo $default[0].'::'.$el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
								}
							}
							?>
							</select>
							<input type="submit" value="<?php __('btnSave'); ?>" class="pj-button float_left l5 align_middle" />
						</p>
						<?php
						break;
					}
				}
				?>
			
			<?php
			$parts = parse_url($listing_page);
			$prefix = NULL;
			if (substr($parts['path'], -1) !== "/")
			{
				$prefix = basename($parts['path']);
			}
			if (isset($parts['query']) && !empty($parts['query']))
			{
				$prefix .= "?" . $parts['query'];
			}
			$prefix .= (strpos($prefix, "?") === false) ? "?" : "&";
			?>
			<p style="margin: 0 0 10px; font-weight: bold"><?php __('lblInstallPhp1_4'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:130px;">
RewriteEngine On
RewriteRule ^(.*)-(\d+).html$ <?php echo $prefix; ?>controller=pjListings&action=pjActionView&id=$2
RewriteRule index.html$ <?php echo $prefix; ?>controller=pjListings&action=pjActionCars [L,NC,QSA]
RewriteRule login.html$ <?php echo $prefix; ?>controller=pjListings&action=pjActionLogin [L,NC,QSA]
RewriteRule register.html$ <?php echo $prefix; ?>controller=pjListings&action=pjActionRegister [L,NC,QSA]
RewriteRule search.html$ <?php echo $prefix; ?>controller=pjListings&action=pjActionSearch [L,NC,QSA]
RewriteRule compare.html$ <?php echo $prefix; ?>controller=pjListings&action=pjActionCompare [L,NC,QSA]</textarea>
			<p style="margin: 20px 0 10px; font-weight: bold"><?php __('lblInstallPhp1_5'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:35px">
&lt;base href="<?php echo $listing_page; ?>" /&gt;</textarea>
			
				<br/><br/>
				<?php
				pjUtil::printNotice(NULL, __('infoInstallSEODesc', true),false, false);  
				?>
				<div id="seo_tabs">
					<ul>
						<li><a href="#seo_tabs-1"><?php __('lblHomePage'); ?></a></li>
						<li><a href="#seo_tabs-2"><?php __('lblLoginPage'); ?></a></li>
						<li><a href="#seo_tabs-3"><?php __('lblRegisterPage'); ?></a></li>
						<li><a href="#seo_tabs-4"><?php __('lblSearchPage'); ?></a></li>
						<li><a href="#seo_tabs-5"><?php __('lblComparePage'); ?></a></li>
					</ul>
					<div id="seo_tabs-1">
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<div class="multilang b10"></div>
						<?php endif;?>
						<?php include_once PJ_VIEWS_PATH . 'pjAdminOptions/elements/home.php';?>
					</div>
					<div id="seo_tabs-2">
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<div class="multilang b10"></div>
						<?php endif;?>
						<?php include_once PJ_VIEWS_PATH . 'pjAdminOptions/elements/login.php';?>
					</div>
					<div id="seo_tabs-3">
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<div class="multilang b10"></div>
						<?php endif;?>
						<?php include_once PJ_VIEWS_PATH . 'pjAdminOptions/elements/register.php';?>
					</div>
					<div id="seo_tabs-4">
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<div class="multilang b10"></div>
						<?php endif;?>
						<?php include_once PJ_VIEWS_PATH . 'pjAdminOptions/elements/search.php';?>
					</div>
					<div id="seo_tabs-5">
						<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && count($tpl['lp_arr']) > 1) : ?>
						<div class="multilang b10"></div>
						<?php endif;?>
						<?php include_once PJ_VIEWS_PATH . 'pjAdminOptions/elements/compare.php';?>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		(function ($) {
			$(function() {
				$(".multilang").multilang({
					langs: <?php echo $tpl['locale_str']; ?>,
					flagPath: "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/",
					select: function (event, ui) {
						$("input[name='locale']").val(ui.index);
					}
				});
			});
		})(jQuery_1_8_2);
	</script>
	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{
		$tab_id = explode("-", $_GET['tab_id']);
		$tab_id = (int) $tab_id[1] - 1;
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "active", <?php echo $tab_id; ?>);
			});
		})(jQuery_1_8_2);
		</script>
		<?php
	}
	if (isset($_GET['seo_tab_id']) && !empty($_GET['seo_tab_id']))
	{
		$tab_id = explode("-", $_GET['seo_tab_id']);
		$tab_id = (int) $tab_id[1] - 1;
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#seo_tabs").tabs("option", "active", <?php echo $tab_id; ?>);
			});
		})(jQuery_1_8_2);
		</script>
		<?php
	}
}
?>