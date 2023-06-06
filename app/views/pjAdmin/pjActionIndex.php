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
}else{
	?>
	<div class="dashboard">
		<div class="dashboard_header">
			<div class="left"></div>
			<div class="middle">
				<div class="total-cars"><div class="header-content"><span><?php echo $tpl['cnt_cars'];?></span><label><?php echo $tpl['cnt_cars'] != 1 ? strtolower(__('lblCars', true)) : strtolower(__('lblCar', true)); ?></label></div></div>
				<div class="total-cars"><div class="header-content"><span><?php echo $tpl['cnt_expire_arr'];?></span><label><?php echo $tpl['cnt_expire_arr'] != 1 ? strtolower(__('lblCars', true)) : strtolower(__('lblCar', true)); ?>&nbsp;<?php __('lblExpireToday');?></label></div></div>
				<?php 
				if(!$controller->isOwner())
				{
					?><div class="users"><div class="header-content"><span><?php echo $tpl['cnt_users'];?></span><label><?php echo $tpl['cnt_users'] != 1 ? strtolower(__('lblUsers', true)) : strtolower(__('lblUser', true)); ?></label></div></div><?php
				}else{
					?><div class="users"><div class="header-content"><span>&nbsp;</span><label>&nbsp;</label></div></div><?php
				} 
				?>
			</div>
			<div class="right"></div>
		</div>
		
		<div class="dashboard_box most-pupular-box">
			<div class="header">
				<div class="left"></div>
				<div class="middle"><span><?php __('lblMostPopular');?></span></div>
				<div class="right"></div>
			</div>
			<div class="content">
				<div class="dashboard_list">
					<?php
					if(!empty($tpl['listing_arr']))
					{
						$row_count = count($tpl['listing_arr']);
						foreach($tpl['listing_arr'] as $k => $v)
						{
							$image = PJ_INSTALL_URL . PJ_IMG_PATH . 'backend/no_img.png';
							if(!empty($v['pic']))
							{
								if(is_file(PJ_INSTALL_PATH . $v['pic']))
								{
									$image = PJ_INSTALL_URL . $v['pic'];
								}
							}
							?>
							<div class="dashboard_row popular-car-row<?php echo $k + 1 == $row_count ? ' dashboard_last_row' : null; ?>">
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionUpdate&id=<?php echo $v['id'];?>"><img src="<?php echo $image;?>"  /></a>
								<div class="listing-views"><label><?php echo $v['views'];?></label><span><?php echo $v['views'] != 1 ? strtolower(__('lblViews', true)) : strtolower(__('lblView', true)); ?></span></div>
							</div>
							<?php							
						}
					} else {
						?><div class="not-found"><?php __('lblNoCar');?></div><?php
					}
					?>
				</div>
			</div>
			<div class="footer">
				<div class="left"></div>
				<div class="middle"></div>
				<div class="right"></div>
			</div>
		</div>
		<div class="dashboard_box most-pupular-box">
			<div class="header">
				<div class="left"></div>
				<div class="middle"><span><?php __('lblLatestAddedCars');?></span></div>
				<div class="right"></div>
			</div>
			<div class="content">
				<div class="dashboard_list">
					<?php
					if(!empty($tpl['latest_listing_arr']))
					{
						$row_count = count($tpl['latest_listing_arr']);
						foreach($tpl['latest_listing_arr'] as $k => $v)
						{
							$image = PJ_INSTALL_URL . PJ_IMG_PATH . 'backend/no_img.png';
							if(!empty($v['pic']))
							{
								if(is_file(PJ_INSTALL_PATH . $v['pic']))
								{
									$image = PJ_INSTALL_URL . $v['pic'];
								}
							}
							?>
							<div class="dashboard_row popular-car-row<?php echo $k + 1 == $row_count ? ' dashboard_last_row' : null; ?>">
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionUpdate&id=<?php echo $v['id'];?>"><img src="<?php echo $image;?>"  /></a>
								<div class="listing-details">
									<label><?php echo pjSanitize::html($v['make']);?></label>
									<label><?php echo pjSanitize::html($v['model']);?></label>
									<?php
									if(!empty($v['listing_year']))
									{
										?><label><?php echo pjSanitize::html($v['listing_year']);?></label><?php
									} 
									if(!empty($v['listing_price']))
									{
										?><label><?php echo pjUtil::formatCurrencySign($v['listing_price'], $tpl['option_arr']['o_currency']);?></label><?php
									} 
									?>
								</div>
							</div>
							<?php							
						}
					} else {
						?><div class="not-found"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionCreate"><?php __('lblAddFirstCar');?></a></div><?php
					}
					?>
				</div>
			</div>
			<div class="footer">
				<div class="left"></div>
				<div class="middle"></div>
				<div class="right"></div>
			</div>
		</div>
		<?php
		if(!$controller->isOwner())
		{ 
			?>
			<div class="dashboard_box top-users-box">
				<div class="header">
					<div class="left"></div>
					<div class="middle"><span><?php echo __('lblLatestVendors');?></span></div>
					<div class="right"></div>
				</div>
				<div class="content">
					<div class="dashboard_list">
						<?php
						if(!empty($tpl['user_arr']))
						{
							$row_count = count($tpl['user_arr']);
							foreach($tpl['user_arr'] as $k => $v)
							{
								?>
								<div class="dashboard_row top-users-row<?php echo $k + 1 == $row_count ? ' dashboard_last_row' : null; ?>">
									<label class="name"><?php echo stripslashes($v['name']);?></label>
									<a class="email" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionUpdate&id=<?php echo $v['id'];?>"><?php echo $v['email']; ?></a>
									<label class="last-login"><?php echo strtolower(__('lblDashLastLogin', true));?>: <?php echo pjUtil::formatDate(date('Y-m-d', strtotime($v['last_login'])), 'Y-m-d', $tpl['option_arr']['o_date_format']) . ' ' . pjUtil::formatTime(date('H:i:s', strtotime($v['last_login'])), 'H:i:s', $tpl['option_arr']['o_time_format']); ?></label>
									<div class="listings"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminListings&amp;action=pjActionIndex&user_id=<?php echo $v['id'];?>"><?php echo $v['cnt_listings'];?></a> <?php echo $v['cnt_listings'] != 1 ? strtolower(__('lblCars')) : strtolower(__('lblCar')); ?></div>
								</div>
								<?php							
							}
						} else {
							?><div class="not-found"><?php __('lblNoUser');?></div><?php
						}
						?>
					</div>
				</div>
				<div class="footer">
					<div class="left"></div>
					<div class="middle"></div>
					<div class="right"></div>
				</div>
			</div>
			<?php
		}else{
			?>
			<div class="dashboard_box top-users-box">
				<div class="header">
					<div class="left"></div>
					<div class="middle"><span>&nbsp;</span></div>
					<div class="right"></div>
				</div>
				<div class="content">
					<div class="dashboard_list">
						<div class="not-found">&nbsp;</div>
					</div>
				</div>
				<div class="footer">
					<div class="left"></div>
					<div class="middle"></div>
					<div class="right"></div>
				</div>
			</div>
			<?php
		} 
		?>
		
		<div class="clear_left t20 overflow">
			<div class="float_left black t30"><span class="gray"><?php echo ucfirst(__('lblDashLastLogin', true)); ?>:</span> <?php echo date("F d, Y H:i", strtotime($_SESSION[$controller->defaultUser]['last_login'])); ?></div>
			<div class="float_right overflow">
			<?php
			list($hour, $day, $other) = explode("_", date("H:i_l_F d, Y"));
			?>
				<div class="dashboard_date">
					<abbr><?php echo $day; ?></abbr>
					<?php echo $other; ?>
				</div>
				<div class="dashboard_hour"><?php echo $hour; ?></div>
			</div>
		</div>
	</div>
	<?php
}
?>