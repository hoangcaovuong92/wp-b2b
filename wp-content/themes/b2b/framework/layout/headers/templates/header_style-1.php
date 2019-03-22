<?php
/**
 * package: header-default
 * var: show_logo_title
 * var: logo_default
 * var: logo_url
 * var: show_logo
 * var: show_social
 * var: show_navuser
 */
extract(apply_filters('wd_filter_get_data_package', 'header-default' )); ?>
<div class="wd-header-container wd-header-content wd-sticky-menu">
	<div class="container">
		<div class="wd-header-top">
			<?php 
			/**
			 * wd_hook_banner_header_desktop_top hook.
			 */
			do_action('wd_hook_banner_header_desktop_top'); ?>
		</div>
		
		<div class="wd-header-bottom">
			<div class="wd-header-main-container wd-header-main-container--row1">
				<?php 
				/**
				 * wd_hook_nav_user_desktop hook.
				 *
				 * @hooked nav_user_desktop - 5
				 */
				do_action('wd_hook_nav_user_desktop'); ?>
			</div>

			<div class="wd-header-main-container wd-header-main-container--row2">
				<?php echo apply_filters('wd_filter_logo', array( 'logo_url' => $logo_url, 'logo_default' => $logo_default, 'show_logo_title' => $show_logo_title)); ?>
				<?php 
				/**
				 * wd_hook_main_menu hook.
				 *
				 * @hooked display_main_menu - 5
				 */
				do_action('wd_hook_main_menu'); ?>
			</div>
		</div>
	</div>
</div>