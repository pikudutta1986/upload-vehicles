<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<button class="navbar-toggle collapsed" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" type="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div><!-- /.Navbar-header -->

	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<?php
			$show_compare_menu = false;
			if(isset($_SESSION[$controller->compareList])) 
			{
				if(count($_SESSION[$controller->compareList]) > 0)
				{
					$show_compare_menu = true;
				}
			}
			if ($tpl['option_arr']['o_seo_url'] == 'No')
			{
				?>
				<li<?php echo $_GET['action'] == 'pjActionCars' ? ' class="active"' : NULL;?>>
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionCars"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
				</li>
				<?php
				if($tpl['option_arr']['o_allow_adding_car'] == 'Yes')
				{ 
					?>
					<li<?php echo $_GET['action'] == 'pjActionLogin' ? ' class="active"' : NULL;?>>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionLogin"><?php __('front_menu_login'); ?></a>
					</li>
		
					<li<?php echo $_GET['action'] == 'pjActionRegister' ? ' class="active"' : NULL;?>>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionRegister"><?php __('front_menu_register'); ?></a>
					</li>
					<?php
				} 
				?>
				<li<?php echo $_GET['action'] == 'pjActionSearch' ? ' class="active"' : NULL;?>>
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionSearch"><?php __('front_menu_search'); ?></a>
				</li>
				<li id="pjAcCompareMenu"<?php echo $_GET['action'] == 'pjActionCompare' ? ' class="active"' : NULL;?> style="display:<?php echo $show_compare_menu == true ? 'block' : 'none'; ?>;">
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjListings&amp;action=pjActionCompare"><?php __('front_menu_compare'); ?> <span id="pjAcCompareBadge" class="badge"><?php echo $show_compare_menu == true ? count($_SESSION[$controller->compareList]) : 0; ?></span></a>
				</li>
				<?php
			}else{
				$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
				$path = $path == '/' ? '' : $path;
				?>
				<li<?php echo $_GET['action'] == 'pjActionCars' ? ' class="active"' : NULL;?>>
					<a href="<?php echo $path . '/index.html'; ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
				</li>
				<?php
				if($tpl['option_arr']['o_allow_adding_car'] == 'Yes')
				{ 
					?>
					<li<?php echo $_GET['action'] == 'pjActionLogin' ? ' class="active"' : NULL;?>>
						<a href="<?php echo $path . '/login.html'; ?>"><?php __('front_menu_login'); ?></a>
					</li>
		
					<li<?php echo $_GET['action'] == 'pjActionRegister' ? ' class="active"' : NULL;?>>
						<a href="<?php echo $path . '/register.html'; ?>"><?php __('front_menu_register'); ?></a>
					</li>
					<?php
				} 
				?>
				<li<?php echo $_GET['action'] == 'pjActionSearch' ? ' class="active"' : NULL;?>>
					<a href="<?php echo $path . '/search.html'; ?>"><?php __('front_menu_search'); ?></a>
				</li>
				<li id="pjAcCompareMenu"<?php echo $_GET['action'] == 'pjActionCompare' ? ' class="active"' : NULL;?> style="display:<?php echo $show_compare_menu == true ? 'block' : 'none'; ?>;">
					<a href="<?php echo $path . '/compare.html'; ?>"><?php __('front_menu_compare'); ?> <span id="pjAcCompareBadge" class="badge"><?php echo $show_compare_menu == true ? count($_SESSION[$controller->compareList]) : 0; ?></span></a>
				</li>
				<?php
			} 
			?>
		</ul>
		<?php
		if (isset($tpl['locale_arr']) && count($tpl['locale_arr']) > 1)
		{
			$selected_title = '';
			foreach($tpl['locale_arr'] as $k => $locale)
			{
				if($controller->getLocaleId() == $locale['id'])
				{
					$selected_title = pjSanitize::html($locale['title']);
				}
			}
			?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" aria-expanded="false" role="button" data-toggle="dropdown" href="#">
						<?php echo $selected_title;?>
						<span class="caret"></span>
					</a>
	
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						<?php
						foreach($tpl['locale_arr'] as $k => $locale)
						{
							?>
							<li role="presentation" class="text-right">
								<a role="menuitem" href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?controller=pjFront&amp;action=pjActionSetLocale&amp;locale=<?php echo $locale['id']; ?><?php echo isset($_GET['iframe']) ? '&amp;iframe' : NULL; ?>"><?php echo pjSanitize::html($locale['title']);?></a>
							</li>
							<?php
						} 
						?>
					</ul>
				</li><!-- /.dropdown -->
			</ul>
			<?php 
		}
		?>
	</div><!-- /.navbar-collapse -->
</nav><!-- /.nav -->