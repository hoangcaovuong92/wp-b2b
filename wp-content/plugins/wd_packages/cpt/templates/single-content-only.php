<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Wordpress
 */
?>
<?php get_header('none'); ?>
<?php
$_post_config 	= apply_filters('wd_filter_post_layout_config', get_the_ID()); ?>
<div id="wd-main-content-wrap" class="wd-main-content-wrap">
	<div class="container">
		<div class="wd-single-post-wrap wd-main-content">
			<?php while ( have_posts() ) : the_post();  ?>
				<div class="wd-content-header row <?php echo esc_attr( $_post_config['custom_class'] ); ?>" id="<?php echo esc_attr( $_post_config['custom_id'] ); ?>">
					<?php the_content(); ?>
				</div>
			<?php endwhile; // End of the loop. ?>
		</div>
	</div>
</div><!-- END CONTAINER  -->
<?php get_footer('none'); ?>