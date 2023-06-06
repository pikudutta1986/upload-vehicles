<?php
mt_srand();
$index = mt_rand(1, 9999);
?>
<div id="pjWrapper">
	<div class="container-fluid">
		<?php include_once dirname(__FILE__) . '/elements/header.php';?>
		
		<div class="row">
			<div class="col-md-3">
				<?php include_once dirname(__FILE__) . '/elements/filter.php';?>
			</div><!-- /.col-md-3 -->
			
			<div class="col-md-9">
				<div class="clearfix">
					<?php include_once dirname(__FILE__) . '/elements/sortby.php';?>
				</div><!-- /.clearfix -->
				
				<br />
				<?php
				if(!empty($tpl['arr']))
				{
					foreach($tpl['arr'] as $v)
					{
						$title = stripslashes($v['make'] . " " . $v['model']);
							
						$image = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/215x160.jpg';
						if(!empty($v['image']))
						{
							if(is_file(PJ_INSTALL_PATH . $v['image']))
							{
								$image = PJ_INSTALL_URL . $v['image'];
							}
						}
						$price = '&nbsp;';
						if(!empty($v['listing_price']))
						{
							$price = pjUtil::formatCurrencySign($v['listing_price'], $tpl['option_arr']['o_currency'], ' ');
						}
							
						$listing_title = pjSanitize::html(stripslashes($v['listing_title'] . " / " . $v['listing_refid']));
						if ($tpl['option_arr']['o_seo_url'] == 'No')
						{
							$url = $_SERVER['SCRIPT_NAME'] . '?controller=pjListings&amp;action=pjActionView&amp;id=' . $v['id'] .(isset($_GET['iframe']) ? '&amp;iframe' : NULL);
						} else {
							$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
							$path = $path == '/' ? '' : $path;
							$url = $path .'/'. $controller->friendlyURL($v['listing_title']) . "-". $v['id'] . ".html";
						}
						?>
						<a href="<?php echo $url;?>" class="thumbnail clearfix product">
							<?php
							if($image != null)
							{ 
								?>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<div class="row">
										<div class="row">
											<img src="<?php echo $image;?>" alt="" class="col-xs-12">
										</div><!-- /.row -->
									</div><!-- /.row -->
								</div><!-- /.col-md-3 -->
								<?php
							} 
							?>
		
							<div class="<?php echo $image != null ? 'col-md-9 col-sm-9 col-xs-12' : 'col-md-12 col-sm-12 col-xs-12'?>">
								<div class="row">
									<div class="col-sm-5">
										<p>
											<span><?php echo $title;?></span>
										</p>
									</div><!-- /.col-xs-6 -->
		
									<div class="col-sm-4">
										<p>
											<span><?php echo $price;?></span>
										</p>
									</div><!-- /.col-xs-3 -->
		
									<div class="col-sm-3">
										<p>
											<span><?php echo !empty($v['listing_mileage']) ? pjUtil::showMileage($tpl['option_arr']['o_mileage_in'], $v['listing_mileage']) : '&nbsp;'; ?></span>
										</p>
									</div><!-- /.col-xs-3 -->
								</div><!-- /.row -->
								<?php
								if (!empty($v['extras']))
								{ 
									?>
									<span class="text-muted"><?php echo stripslashes($v['extras']); ?></span>
									<?php
								} 
								?>
							</div><!-- /.-->
		
							<div class="col-md-1 col-sm-1 hidden-xs">
								<br>
								<button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
							</div><!-- /.col-md-1 -->
						</a><!-- thumbnail -->
						<?php
					}
					include_once dirname(__FILE__) . '/elements/pagination.php';
				}else{
					__('front_label_not_found');
				} 
				?>
			</div><!-- /.content -->
		</div><!-- /.row -->
	</div><!--  /.container-fluid pjVpContainer  -->
</div><!-- /.pjWrapper -->

<?php include_once dirname(__FILE__) . '/elements/loadjs.php';?>