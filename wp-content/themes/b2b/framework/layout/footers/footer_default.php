<?php
/**
 * package: footer-default
 * var: logo_default
 * var: logo_url
 * var: instagram
 * var: social
 * var: copyright
 */
extract(apply_filters('wd_filter_get_data_package', 'footer-default' )); ?>
<div class="wd-footer wd-footer-default wd-footer-content">
	<?php 
	if ($instagram) {
		/**
		 * wd_hook_social_links hook.
		 *
		 * @hooked default_instagram - 5
		 */
		do_action('wd_hook_default_instagram');
	}

	if ($social) {
		/**
		 * wd_hook_default_social_links_footer hook.
		 *
		 * @hooked default_social_icons_footer - 5
		 */
		do_action('wd_hook_default_social_links_footer');
	}

	/**
	 * wd_hook_banner_footer hook.
	 */
	do_action('wd_hook_banner_footer'); 
	if ($copyright) {
		echo apply_filters('wd_filter_copyright', array('custom_class' => ''));
	} ?>
</div>
