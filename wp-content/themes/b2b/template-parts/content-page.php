<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Wordpress
 * @since wpdance
**/

$post_ID		= apply_filters('wd_filter_global_post_id', '');
/*PAGE CONFIG*/
$_page_config 	= apply_filters('wd_filter_post_layout_config', $post_ID); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content <?php echo esc_attr( $_page_config['custom_class'] ); ?>" <?php echo $_page_config['custom_id'] ? 'id="'.esc_attr( $_page_config['custom_id'] ).'"' : ''; ?>>
		<?php the_content();
		//echo apply_filters('the_content', get_post_field('post_content', $post_ID)); 
		
		/**
		 * wd_hook_page_link hook.
		 *
		 * @hooked display_blog_page_link - 5
		 */
		do_action('wd_hook_page_link'); ?>
	</div><!-- .entry-content -->
</div><!-- #post-## -->