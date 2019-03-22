<?php
/*
Template Name: None Header/Footer
*/
get_header('none'); 
$post_ID		= apply_filters('wd_filter_global_post_id', '');
/*PAGE CONFIG*/
$_page_config 	= apply_filters('wd_filter_post_layout_config', $post_ID);

/**
 * package: default-page
 * var: layout 	
 * var: sidebar_left 
 * var: sidebar_right 
 */
extract(apply_filters('wd_filter_get_data_package', 'default-page' )); 

$layout 		= ($_page_config['layout'] != '0') ? $_page_config['layout'] : $layout;

if ($_page_config['layout'] != '0' && $_page_config['layout'] != '0-0-0') {
	$sidebar_left 	= $_page_config['left_sidebar'];
	$sidebar_right 	= $_page_config['right_sidebar'];
}

$content_class = apply_filters('wd_filter_content_class_by_layout', $layout); 

/**
 * wd_hook_before_main_content hook.
 *
 * @hooked before_main_content_wrapper
 */
do_action('wd_hook_before_main_content'); ?>

	<!-- Content Index -->
	<div class="wd-content-page wd-main-content">

		<!-- Left Content -->
		<?php echo apply_filters('wd_filter_display_left_sidebar', $sidebar_left, $layout); ?>

		<!-- Content Index -->
		<div class="<?php echo esc_attr($content_class); ?>">
			<div id="primary" class="content-area">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'page' ); ?>
				<?php endwhile; ?>
			</div><!-- #primary -->
		</div>

		<!-- Right Content -->
		<?php echo apply_filters('wd_filter_display_right_sidebar', $sidebar_right, $layout); ?>
			
	</div>

<?php 
/**
 * wd_hook_after_main_content hook.
 *
 * @hooked after_main_content_wrapper
 */
do_action('wd_hook_after_main_content'); ?>

<?php get_footer('none'); ?>