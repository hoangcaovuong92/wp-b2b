<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Wordpress
 * @since wpdance
 */
?>
<?php 
/**
 * package: 404
 * var: select_style			
 * var: bg_404_url			
 * var: bg_404_color 			
 * var: show_search_form		
 * var: show_back_to_home_btn	
 * var: back_to_home_btn_text	
 * var: back_to_home_btn_class
 * var: show_header_footer	
 */
extract(apply_filters('wd_filter_get_data_package', '404' )); ?>
<?php 
if ($show_header_footer){
	get_header(); 
} else {
 	get_header('none'); 
}
$class_style_select = ($select_style == 'bg_image') ? 'wd-bg-image-error' : 'wd-bg-color-error'; ?>

<div id="wd-main-content-wrap" class="wd-main-content-wrap wd-404-error <?php echo esc_attr($class_style_select); ?>">

	<?php 
	/**
	 * wd_hook_banner_404_before hook.
	 */
	do_action('wd_hook_banner_404_before'); ?>

	<section class="wd-error-404 wd-error-404-page-content">
		<div class="wd-page-header">
			<span class="wd-text-title"><?php esc_html_e( 'Sorry, Page Not Found!', 'feellio' ); ?></span>
			<h1 class="wd-page-title"><?php esc_html_e( '404', 'feellio' ); ?></h1>
		</div><!-- .page-header -->
		<div class="wd-page-content">
			<?php if ($show_search_form): ?>
				<?php 
				/**
				 * wd_hook_search_form hook.
				 *
				 * @hooked facebook_api - 5
				 */ 
				do_action('get_search_form'); ?>
			<?php endif ?>
			<?php if ($show_back_to_home_btn): ?>
			<a class="wd-back-to-home-button <?php echo esc_attr($back_to_home_btn_class); ?>" 
				href="<?php echo esc_url( home_url( '/' ) ); ?>" 
				title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
					<?php echo esc_html($back_to_home_btn_text); ?>
			</a>
		<?php endif ?>
		</div><!-- .page-content -->
	</section><!-- .error-404 -->

	<?php 
	/**
	 * wd_hook_banner_404_after hook.
	 */
	do_action('wd_hook_banner_404_after'); ?>

</div><!-- END CONTAINER  -->
<?php 
if ($show_header_footer){
	get_footer(); 
} else {
 	get_footer('none'); 
} ?>