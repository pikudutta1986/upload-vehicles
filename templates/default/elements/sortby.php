<?php
$sorting_url = pjUtil::currPageURL($_GET);
$sorting_url = pjUtil::getSortingUrl($sorting_url, $tpl['option_arr']['o_seo_url']); 
?>
<span><?php __('front_label_order_by');?>:</span>

&nbsp;
&nbsp;

<div class="btn-group">
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php __('front_label_price');?> <span class="caret"></span></button>

		<ul class="dropdown-menu" role="menu">
			<li><a href="<?php echo $sorting_url .  ($tpl['option_arr']['o_seo_url'] == 'No' ? '&amp;sortby=listing_price&direction=desc' : '?sortby=listing_price&direction=desc'); ?>"><?php __('front_highest_to_lowest');?></a></li>
			<li><a href="<?php echo $sorting_url . ($tpl['option_arr']['o_seo_url'] == 'No' ? '&amp;sortby=listing_price&direction=asc' : '?sortby=listing_price&direction=asc'); ?>"><?php __('front_lowest_to_highest');?></a></li>
		</ul>
	</div>

	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php __('front_label_first_registration');?> <span class="caret"></span></button>

		<ul class="dropdown-menu" role="menu">
			<li><a href="<?php echo $sorting_url . ($tpl['option_arr']['o_seo_url'] == 'No' ? '&amp;sortby=listing_year&direction=desc' : '?sortby=listing_year&direction=desc');?>"><?php __('front_highest_to_lowest');?></a></li>
			<li><a href="<?php echo $sorting_url . ($tpl['option_arr']['o_seo_url'] == 'No' ? '&amp;sortby=listing_year&direction=asc' : '?sortby=listing_year&direction=asc');?>"><?php __('front_lowest_to_highest');?></a></li>
		</ul>
	</div>

	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php __('front_label_mileage');?> <span class="caret"></span></button>

		<ul class="dropdown-menu" role="menu">
			<li><a href="<?php echo $sorting_url . ($tpl['option_arr']['o_seo_url'] == 'No' ? '&amp;sortby=listing_mileage&direction=desc' : '?sortby=listing_mileage&direction=desc');?>"><?php __('front_highest_to_lowest');?></a></li>
			<li><a href="<?php echo $sorting_url . ($tpl['option_arr']['o_seo_url'] == 'No' ? '&amp;sortby=listing_mileage&direction=asc' : '?sortby=listing_mileage&direction=asc');?>"><?php __('front_lowest_to_highest');?></a></li>
		</ul>
	</div>
</div><!-- /.btn-group -->