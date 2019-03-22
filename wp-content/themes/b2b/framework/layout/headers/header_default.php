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
<div class="wd-header-container wd-header-default wd-header-content wd-sticky-menu">
	<div class="container">
		<div class="wd-header-top">
			<?php 
			/**
			 * wd_hook_banner_header_desktop_top hook.
			 */
			do_action('wd_hook_banner_header_desktop_top'); ?>
		</div>
	</div>
		
	<div class="wd-header-bottom">
		<div class="wd-header-main-container wd-header-main-container--row1">
			<div class="container">
				<?php 
				if ($show_social) {
					/**
					 * wd_hook_default_social_links_header hook.
					 *
					 * @hooked default_social_icons_header - 5
					 */
					do_action('wd_hook_default_social_links_header');
				}
				
				/**
				 * wd_hook_main_menu hook.
				 *
				 * @hooked display_main_menu - 5
				 */
				do_action('wd_hook_main_menu'); 

				if ($show_navuser) {
					/**
					 * wd_hook_nav_user_desktop hook.
					 *
					 * @hooked nav_user_desktop - 5
					 */
					do_action('wd_hook_nav_user_desktop');
				 } ?>
			</div>
		</div>

		<?php if ($show_logo) { ?>
			<div class="wd-header-main-container wd-header-main-container--row2 wd-hide-on-sticky-menu">
				<div class="container">
					<?php echo apply_filters('wd_filter_logo', array( 'logo_url' => $logo_url, 'logo_default' => $logo_default, 'show_logo_title' => $show_logo_title)); ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>