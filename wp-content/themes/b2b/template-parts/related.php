<?php
/**
 * package: blog-related
 * var: columns 		
 */
extract(apply_filters('wd_filter_get_data_package', 'blog-related' ));

global $post;
$args = array(
	'post_type' 		=> $post->post_type,
	'posts_per_page'	=> 6,
	'post_status' 		=> 'publish',
	'post__not_in' 		=> array( $post->ID ),
	'orderby' 			=> 'rand',
);

if (count(get_post_taxonomies( $post ))) {
	$taxonomy_name 	= get_post_taxonomies( $post )[0];
	$terms  		= get_the_terms($post->ID, $taxonomy_name);
	if (is_array($terms) || is_object($terms)){
		$term_list 					= wp_list_pluck( $terms, 'slug' );
		$args['tax_query'] 	= array(
			array(
				'taxonomy' 	=> $taxonomy_name,
				'field' 	=> 'slug',
				'terms' 	=> $term_list
			)
		);
	}
}
wp_reset_postdata();
$related 	= new WP_Query($args); ?>
<?php if($related->have_posts()) : ?>
	<div class="wd-blog-wrapper wd-related-blog-wrap col-md-24 grid">
		<div class="wd-title">
			<h2 class="wd-title-heading"><?php esc_html_e('Lastest News', 'feellio'); ?></h2>
		</div>
		<?php 
		$slider_options = json_encode(array(
			'slider_type' => 'owl',
			'column_desktop' => esc_attr($columns),
		)); ?>
		<div class="wd-slider-wrap wd-slider-wrap--post-related" 
			data-slider-options='<?php echo $slider_options; ?>'>
			<?php if( $related->post_count > 1 ) {
				while($related->have_posts()) {
					$related->the_post(); global $post; 
					echo apply_filters('wd_filter_blog_content', array('thumbnail_size' => 'post-thumbnail', 'post_format' => get_post_format())); 
				}
			} //end while ?>
		</div>
	</div>
<?php endif; ?>