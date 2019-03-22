<?php
/**
 * The template for displaying search results pages
 *
 * @package Wordpress
 * @since wpdance
 */
?>
<?php get_header(); ?>
<?php
/**
 * package: search-layout
 * var: layout 	
 * var: sidebar_left 
 * var: sidebar_right 
 * var: type 
 * var: columns 
 */
extract(apply_filters('wd_filter_get_data_package', 'search-layout' )); 

$content_class = apply_filters('wd_filter_content_class_by_layout', $layout); 

$layout_class 	= 'wd-columns-'.$columns.' wd-tablet-columns-2 wd-mobile-columns-1';

$class_masonry_wrap = (isset($_GET['post_type']) && $_GET['post_type'] == 'post' && have_posts() ) ? 'wd-masonry-wrap' : '';
$class_masonry_item = (isset($_GET['post_type']) && $_GET['post_type'] == 'post' && have_posts() ) ? 'wd-masonry-item' : '';
?>
<div id="wd-main-content-wrap" class="wd-main-content-wrap wd-search-result-page">
	<div class="container">
		<div class="row">

			<?php 
			/**
			 * wd_hook_banner_search_result_before hook.
			 */
			do_action('wd_hook_banner_search_result_before'); ?>
			
			<div class="wd-content-page wd-archive-blog-page wd-main-content">
				<!-- Left Content -->
				<?php echo apply_filters('wd_filter_display_left_sidebar', $sidebar_left, $layout); ?>
				
				<!-- Content Index -->
				<div class="wd-default-blog-archive <?php echo esc_attr($content_class); ?>">
					<?php if ( have_posts() ) : ?>
						<div class="wd-blog-wrapper <?php echo esc_attr($class_masonry_wrap); ?> <?php echo esc_attr($layout_class); ?>">
							<!-- Start the Loop --> 
							<?php while ( have_posts() ) : the_post(); ?>
								<?php echo apply_filters('wd_filter_blog_content', array('custom_class' => $class_masonry_item )); ?>
							<?php endwhile; ?>
							<!-- End the Loop -->
						</div>
					<?php else: ?>
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					<?php endif; // End If Have Post ?>
				</div>
				<div class="wd-pagination">
					<?php echo apply_filters('wd_filter_display_pagination', 3); ?>
				</div>
				
				<!-- Right Content -->
				<?php echo apply_filters('wd_filter_display_right_sidebar', $sidebar_right, $layout); ?>
			</div>

			<?php 
			/**
			 * wd_hook_banner_search_result_after hook.
			 */
			do_action('wd_hook_banner_search_result_after'); ?>

		</div>
	</div>
</div><!-- END CONTAINER  -->
<?php get_footer(); ?>
