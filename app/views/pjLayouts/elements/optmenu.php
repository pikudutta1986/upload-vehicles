<?php
$active = " ui-tabs-active ui-state-active";
?>
<style type="text/css">
.ui-widget-content{
	/*border: medium none;*/
}
</style>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<?php
		if($controller->isAdmin() || $controller->isEditor())
		{ 
			if($controller->isAdmin())
			{
				?>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminOptions' || $_GET['action'] != 'pjActionIndex' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionIndex"><?php __('menuOptions'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminOptions' || $_GET['action'] != 'pjActionSubmissions' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionSubmissions"><?php __('menuSubmissions'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminOptions' || $_GET['action'] != 'pjActionEmails' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionEmails"><?php __('menuEmails'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminMakes' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMakes&amp;action=pjActionIndex"><?php __('menuMakes'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminCarModels' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCarModels&amp;action=pjActionIndex"><?php __('menuModels'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminExtras' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionIndex"><?php __('menuExtras'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminFeatures' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminFeatures&amp;action=pjActionIndex"><?php __('menuFeatures'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminCountries' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCountries&amp;action=pjActionIndex"><?php __('menuCountries'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjLocale' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjLocale&amp;action=pjActionIndex"><?php __('menuLocales'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjBackup' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjBackup&amp;action=pjActionIndex"><?php __('menuBackup'); ?></a></li>
				<?php
			}else{
				?>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminMakes' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMakes&amp;action=pjActionIndex"><?php __('menuMakes'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminCarModels' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCarModels&amp;action=pjActionIndex"><?php __('menuModels'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminExtras' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionIndex"><?php __('menuExtras'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminFeatures' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminFeatures&amp;action=pjActionIndex"><?php __('menuFeatures'); ?></a></li>
				<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminCountries' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCountries&amp;action=pjActionIndex"><?php __('menuCountries'); ?></a></li>
				<?php
			}
		}
		?>
	</ul>
</div>