<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Wordpress
 */

get_header(); 	
$post_ID		= apply_filters('wd_filter_global_post_id', '');

//Post Config
$_post_config 	= apply_filters('wd_filter_post_layout_config', $post_ID);

/**
 * package: single-blog-layout
 * var: layout 		
 * var: sidebar_left 	
 * var: sidebar_right 	
 * var: show_recent_blog 
 */
extract(apply_filters('wd_filter_get_data_package', 'single-blog-layout' )); 

$layout = ($_post_config['layout'] != '0') ? $_post_config['layout'] : $layout;

if ($_post_config['layout'] != '0' && $_post_config['layout'] != '0-0-0') {
	$sidebar_left 	= $_post_config['left_sidebar'];
	$sidebar_right 	= $_post_config['right_sidebar'];
}

$wrap_content_class 		= '';
if( ($layout == '1-0-0') || ($layout == '0-0-1') ){
	$content_col_class 		= "col-md-18 col-sm-24";
	if (($layout == '1-0-0')) {
		$wrap_content_class = "wd-blog-left-sidebar";
	}elseif($layout == '0-0-1'){
		$wrap_content_class = "wd-blog-right-sidebar";
	}
}elseif($layout == '1-0-1'){
	$content_col_class 		= "col-md-12 col-sm-24";
	$wrap_content_class 	= "wd-blog-left-right-sidebar";
}else{
	$content_col_class 		= "col-md-24";
	$wrap_content_class 	= "wd-blog-full-width";
} 

/**
 * wd_hook_before_main_content hook.
 *
 * @hooked before_main_content_wrapper
 */
do_action('wd_hook_before_main_content'); ?>

	<?php 
	/**
	 * wd_hook_banner_single_post_before hook.
	 */
	do_action('wd_hook_banner_single_post_before'); ?>

	<div class="wd-single-post-wrap wd-main-content">
		<!-- Left Content -->
		<?php echo apply_filters('wd_filter_display_left_sidebar', $sidebar_left, $layout); ?>
		
		<?php while ( have_posts() ) : the_post();  ?>
			<!-- Content Single Post -->
			<div class="wd-single-post-content <?php echo esc_attr($content_col_class); ?> <?php echo esc_attr($wrap_content_class); ?>">
				<div class="wd-content-single">
					<?php echo apply_filters('wd_filter_blog_single', true); ?>
				</div>
				<?php 
				/**
				 * wd_hook_display_comment_form hook.
				 * 
				 * @hooked display_comment_form - 5
				 */
				do_action('wd_hook_display_comment_form'); ?>
			</div>
		<?php endwhile; // End of the loop. ?>
		
		<!-- Right Content -->
		<?php echo apply_filters('wd_filter_display_right_sidebar', $sidebar_right, $layout); ?>
	</div>
	<?php if ($show_recent_blog && !post_password_required()): ?>
		<?php while ( have_posts() ) : the_post();  ?>
			<div class="wd-related-post-wrap">
				<?php get_template_part( 'template-parts/related'); ?>	
			</div>
		<?php endwhile; // End of the loop. ?>
	<?php endif ?>
	
	<?php 
	/**
	 * wd_hook_banner_single_post_after hook.
	 */
	do_action('wd_hook_banner_single_post_after'); ?>

<?php 
/**
 * wd_hook_after_main_content hook.
 *
 * @hooked after_main_content_wrapper
 */
do_action('wd_hook_after_main_content'); ?>

<?php get_footer(); ?>