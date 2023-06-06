<?php
if (pjObject::getPlugin('pjOneAdmin') !== NULL)
{
	$controller->requestAction(array('controller' => 'pjOneAdmin', 'action' => 'pjActionMenu'));
}
?>

<div class="leftmenu-top"></div>
<div class="leftmenu-middle">
	<ul class="menu">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionIndex' ? 'menu-focus' : NULL; ?>"><span class="menu-dashboard">&nbsp;</span><?php __('menuDashboard'); ?></a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminListings' && !in_array($_GET['action'], array('pjActionUpdate')) ? 'menu-focus' : NULL; ?>"><span class="menu-cars">&nbsp;</span><?php __('menuCars'); ?></a></li>
		<?php
		if ($controller->isAdmin() || $controller->isEditor())
		{
			if($controller->isAdmin())
			{
				?>
				<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionIndex" class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && in_array($_GET['action'], array('pjActionIndex', 'pjActionSubmissions', 'pjActionEmails'))) || in_array($_GET['controller'], array('pjAdminLocales', 'pjBackup', 'pjLocale', 'pjAdminCarModels', 'pjAdminMakes', 'pjAdminExtras', 'pjAdminFeatures', 'pjAdminCountries')) ? 'menu-focus' : NULL; ?>"><span class="menu-options">&nbsp;</span><?php __('menuOptions'); ?></a></li>
				<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminUsers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuUsers'); ?></a></li>
				<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionPreview" class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'pjActionPreview' ? 'menu-focus' : NULL; ?>"><span class="menu-preview">&nbsp;</span><?php __('menuPreview'); ?></a></li>
				<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall" class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'pjActionInstall' ? 'menu-focus' : NULL; ?>"><span class="menu-install">&nbsp;</span><?php __('menuInstall'); ?></a></li>
				<?php 
			}else{
				?>
				<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMakes&amp;action=pjActionIndex" class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && in_array($_GET['action'], array('pjActionIndex', 'pjActionSubmissions', 'pjActionEmails', 'pjActionInstall'))) || in_array($_GET['controller'], array('pjAdminCarModels', 'pjAdminMakes', 'pjAdminExtras', 'pjAdminFeatures', 'pjAdminCountries')) ? 'menu-focus' : NULL; ?>"><span class="menu-options">&nbsp;</span><?php __('menuOptions'); ?></a></li>
				<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex" class="<?php echo $_GET['controller'] == 'pjAdminUsers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuUsers'); ?></a></li>
				<li><a href="preview.php" target="_blank"><span class="menu-preview">&nbsp;</span><?php __('menuPreview'); ?></a></li>
				<?php
			}
		}
		if ($controller->isOwner() || $controller->isEditor())
		{
			?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile" class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionProfile' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuProfile'); ?></a></li><?php
		}
		?>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogout"><span class="menu-logout">&nbsp;</span><?php __('menuLogout'); ?></a></li>
	</ul>
</div>
<div class="leftmenu-bottom"></div>
<?php
if (isset($tpl['menu_locale_arr']) && !empty($tpl['menu_locale_arr']) && count($tpl['menu_locale_arr']) > 1)
{
	?>
	<div class="locale-nav dropdown-nav">
		<a href="#" class="dropdown-toggle dropdown-closed"><img src="<?php echo PJ_INSTALL_URL; ?>core/framework/libs/pj/img/flags/<?php echo @$tpl['menu_locale_arr'][$controller->getLocaleId()]['file']; ?>" alt="<?php echo pjSanitize::html(@$tpl['menu_locale_arr'][$controller->getLocaleId()]['title']); ?>" /> <?php echo pjSanitize::html(@$tpl['menu_locale_arr'][$controller->getLocaleId()]['title']); ?></a>
		<ul><?php
		foreach ($tpl['menu_locale_arr'] as $locale)
		{
			?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionSetLocale&amp;id=<?php echo $locale['id']; ?>"<?php echo $locale['id'] != $controller->getLocaleId() ? NULL : ' class="active"'; ?>><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file']; ?>" alt="<?php echo pjSanitize::html($locale['title']); ?>" /> <?php echo pjSanitize::html($locale['title']); ?></a></li><?php
		}
		?></ul>
	</div>
	<?php
}
?>