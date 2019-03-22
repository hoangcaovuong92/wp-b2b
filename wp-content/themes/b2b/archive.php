<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @package Wordpress
 * @since wpdance
 */
?>
<?php get_header(); ?>
<?php
/**
 * package: archive-blog
 * var: layout
 * var: sidebar_left
 * var: sidebar_righ
 * var: toggle_layout
 * var: layout_style
 * var: columns
 */
$setting_data = apply_filters('wd_filter_get_data_package', 'archive-blog' );
$custom_data = apply_filters('wd_filter_taxonomy_layout_config', '');
extract(wp_parse_args($custom_data, $setting_data));

$content_class = apply_filters('wd_filter_content_class_by_layout', $layout); 

$layout_style = $toggle_layout ? 'grid' : $layout_style;

$class_masonry_wrap = ($layout_style !== 'list') ? 'wd-masonry-wrap' : '';
$class_masonry_item = ($layout_style !== 'list') ? 'wd-masonry-item' : '';

$layout_class = $layout_style.' '.$class_masonry_wrap;
$layout_class .= ' wd-columns-'.$columns;

$image_size =  ($columns == 1 || $layout_style !== 'list') ? 'full' : 'post-thumbnail';

/**
 * wd_hook_before_main_content hook.
 *
 * @hooked before_main_content_wrapper
 */
do_action('wd_hook_before_main_content'); ?>

	<?php 
	/**
	 * wd_hook_banner_archive_post_before hook.
	 */
	do_action('wd_hook_banner_archive_post_before'); ?>

	<div class="wd-content-page wd-archive-blog-page wd-main-content">
		<!-- Left Content -->
		<?php echo apply_filters('wd_filter_display_left_sidebar', $sidebar_left, $layout); ?>
		
		<!-- Content Index -->
		<div class="wd-default-blog-archive <?php echo esc_attr($content_class); ?>">
			<?php if ( have_posts() ) : ?>
				<?php 
				if ($toggle_layout) {
					/**
					 * wd_hook_blog_archive_toggle_button hook.
					 *
					 * @hooked before_main_content_wrapper
					 */
					do_action('wd_hook_blog_archive_toggle_button');
				} ?>
				<div class="wd-blog-wrapper wd-blog-switchable-layout <?php echo esc_attr($layout_class); ?>">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php echo apply_filters('wd_filter_blog_content', array('thumbnail_size' => $image_size, 'custom_class' => $class_masonry_item )); ?>
					<?php endwhile; ?>
					</div>
				<div class="wd-pagination">
					<?php echo apply_filters('wd_filter_display_pagination', 3); ?>
				</div>
			<?php else: ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; // End If Have Post ?>
		</div>
	
		<!-- Right Content -->
		<?php echo apply_filters('wd_filter_display_right_sidebar', $sidebar_right, $layout); ?>
	</div>

	<?php 
	/**
	 * wd_hook_banner_archive_post_after hook.
	 */
	do_action('wd_hook_banner_archive_post_after'); ?>

<?php 
/**
 * wd_hook_after_main_content hook.
 *
 * @hooked after_main_content_wrapper
 */
do_action('wd_hook_after_main_content'); ?>

<?php get_footer(); ?>