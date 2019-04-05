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
extract(apply_filters('wd_filter_get_data_package', 'header-default' ));  ?>	
<div class="wd-header-mobile-content">
	<div class="container">
		<div class="wd-header-top">
			<?php 
			/**
			 * wd_hook_banner_header_mobile_top hook.
			 */
			do_action('wd_hook_banner_header_mobile_top'); ?>
		</div>
		
		<div class="wd-header-bottom">
			<div class="wd-header-mobile-content-left">
				<?php
				/**
				 * wd_hook_pushmenu_mobile_toggle hook.
				 *
				 * @hooked pushmenu_mobile_toggle - 5
				 */
				do_action('wd_hook_pushmenu_mobile_toggle'); ?>
			</div>
			<div class="wd-header-mobile-content-center">
				<?php echo apply_filters('wd_filter_logo', array('logo_url' => $logo_url, 'logo_default' => $logo_default, 'show_logo_title' => $show_logo_title)); ?>
			</div>
			<div class="wd-header-mobile-content-right">
				<?php 
				/**
				 * wd_hook_nav_user_mobile hook.
				 *
				 * @hooked nav_user_mobile - 5
				 */
				do_action('wd_hook_nav_user_mobile'); ?>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * wd_hook_popup_search_form hook.
 *
 * @hooked popup_search_form - 5
 */ 
do_action('wd_hook_popup_search_form'); ?>