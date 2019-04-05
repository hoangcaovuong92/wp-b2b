<?php
if(!function_exists('wd_blog_special_function')){
	function wd_blog_special_function($atts){
		extract(shortcode_atts(array(
			'layout'				=> 'category, title, meta, excerpt, readmore',
			'style'					=> 'grid',
			'id_category'			=> '-1',
			'data_show'				=> 'recent-post',
			'number_blogs'			=> 6,
			'sort'					=> 'DESC',
			'order_by'				=> 'date',
			'columns'				=> 3,
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'padding'				=> 'normal',
			'show_thumbnail'  		=> '1',
			'show_placeholder_image'  => '0',
			'image_size'  			=> 'post-thumbnail',
			'grid_hover_style'		=> 'normal',
			'number_excerpt'		=> '10',
			'is_slider'				=> '1',
			'show_nav'				=> '1',	
			'auto_play'				=> '1',
			'fullwidth_mode'		=> false,
			'class'					=> ''
		),$atts));
		
		$args = array(
			'posts_per_page' 	=> (int)$number_blogs,
			'order' 			=> $sort,
			'orderby' 			=> $order_by,
			'ignore_sticky_posts' => true
		);
		$settings = array(
				'category' 	=> array(
					'category'	=> (int)$id_category
				),
				'data_show' => $data_show
		);
		$args = apply_filters('wd_filter_get_blog_query', $args, $settings);

		if ($is_slider) {
			$wrap_class = '';
			$list_class	= 'wd-slider-wrap wd-slider-wrap--blog-special';
			$slider_options = json_encode(array(
				'slider_type' => 'owl',
				'column_desktop' => esc_attr($columns),
				'column_tablet' => esc_attr($columns_tablet),
				'column_mobile' => esc_attr($columns_mobile),
				'arrows' => $show_nav ? true : false,
				'autoplay' => $auto_play ? true : false,
			));
		}else{
			$wrap_class = ($is_slider == '0') ? 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile.' wd-columns-padding-'.$padding : '';
			$list_class	= 'wd-columns-list-item';
			$slider_options	= '';
		}

		$wrap_class	.= ($grid_hover_style === 'grid-inner') ? ' wd-masonry-blog-content-inner' : '';

		$layout = ($layout) ? explode(',', $layout) : array();

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		wp_reset_query();
		$recent_posts = new WP_Query($args);
		ob_start();

		if ( $recent_posts->have_posts() ) { ?>
			<div class="wd-shortcode wd-shortcode-blog-special <?php echo esc_attr($class); ?>">
				<div class="wd-blog-wrapper <?php echo esc_attr( $style ); ?> <?php echo esc_attr( $wrap_class ); ?>">
					<div class="wd-blog-special-list <?php echo esc_attr( $list_class ); ?>" 
						data-slider-options='<?php echo esc_attr( $slider_options ); ?>'>
						<?php
						$count = 0;	
						while( $recent_posts->have_posts() ) {
							$recent_posts->the_post();
							global $post; ?>
							<article itemscope itemtype="http://schema.org/Article" id="post-<?php the_ID(); ?>" <?php post_class( 'wd-blog-item' ); ?>>
								<div class="wd-content-post-format wd-content-post-format--none wd-post-has-thumbnail">
									<?php 
									echo apply_filters('wd_filter_blog_thumbnail_html', array(
											'thumbnail_size' => $image_size, 
											'show_thumbnail' => $show_thumbnail, 
											'num' => 1, 
											'placeholder' => $show_placeholder_image, 
											'custom_class' => ''
										)); ?>
									<div class="wd-blog-content-wrap">
										<?php foreach ($layout as $layout_part){
											$layout_part = trim($layout_part);
											if ($layout_part == 'category') {
												echo apply_filters('wd_filter_blog_category', true);
											}elseif ($layout_part == 'title') {
												echo apply_filters('wd_filter_blog_title', true);
											}elseif ($layout_part == 'meta') {
												echo apply_filters('wd_filter_blog_meta', 'loop');
											}elseif ($layout_part == 'excerpt') {
												echo apply_filters('wd_filter_blog_excerpt', true, $number_excerpt);
											}elseif ($layout_part == 'readmore') {
												echo apply_filters('wd_filter_blog_readmore', true);
											}
										} ?>
									</div>
								</div>
							</article>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php }
		$output = ob_get_clean();
		wp_reset_query();
		return $output;
	}
}

if (!function_exists('wd_blog_special_vc_map')) {
	function wd_blog_special_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Blog Special", 'wd_package'),
			'base' 				=> 'wd_blog_special',
			'description' 		=> esc_html__("Custom blog themes do not follow the default setting structure.", 'wd_package'),
			'category' 			=> esc_html__("WD - Blog", 'wd_package'),
			'icon'        		=> 'icon-wpb-toggle-small-expand',
			'params' => array(
				//Layout Setting
				array(
					'type' 			=> 'sorted_list',
					'heading' 		=> __( 'Layout', 'wd_package' ),
					'param_name' 	=> 'layout',
					'description' 	=> __( 'Select and sort blog layout. Leave blank if you want to display all properties blog', 'wd_package' ),
					'value' 		=> 'category, title, meta, excerpt, readmore',
					'options' 		=> wd_vc_get_list_blog_special_layout(),
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Style', 'wd_package' ),
					'param_name' 		=> 'style',
					'admin_label' 		=> true,
					'value' => array(
							esc_html__( 'Grid', 'wd_package' )		=> 'grid',
							esc_html__( 'List', 'wd_package' )		=> 'list'
						),
					'description' 		=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category('category'),
					'description' 	=> '',
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Data Show", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "data_show",
					"value" 		=> wd_vc_get_list_special_blog_name(),
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Number of blogs", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'number_blogs',
					'value' 		=> '6',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Sort By', 'wd_package' ),
					'param_name' 	=> 'sort',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_sort_by_values(),
					'std'			=> 'DESC',
					'description'	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Order By', 'wd_package' ),
					'param_name' 	=> 'order_by',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_order_by_values(),
					'std'			=> 'date',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Columns', 'wd_package' ),
					'param_name' 	=> 'columns',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_columns(),
					"std"			=> 3,
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Columns On Tablet', 'wd_package' ),
					'param_name' 		=> 'columns_tablet',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_columns_tablet(),
					'std'				=> 2,
					'description' 		=> esc_html__( '', 'wd_package' ),
					"group"				=> esc_html__('Responsive', 'wd_package'),
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Columns On Mobile', 'wd_package' ),
					'param_name' 		=> 'columns_mobile',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_columns_mobile(),
					'std'				=> 1,
					'description' 		=> esc_html__( '', 'wd_package' ),
					"group"				=> esc_html__('Responsive', 'wd_package'),
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Padding", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "padding",
					"value" 		=> wd_vc_get_list_columns_padding(),
					"description" 	=> esc_html__('Padding between items.', 'wd_package'),
					"std"			=> 'normal',
					'edit_field_class' => 'vc_col-sm-6',
				),
				//Image Setting
				array(
					"type" 			=> "dropdown",
					"class" 		=> "", 
					"heading" 		=> esc_html__("Show Thumbnail", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "show_thumbnail",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '1',
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Placeholder Image', 'wd_package' ),
					'param_name' 	=> 'show_placeholder_image',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					'description' 	=> esc_html__( 'Show Placeholder Image if post no thumbnail', 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_thumbnail", 'value' => array('1'))
				),
		
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Thumbnail Size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'description' 	=> '',
					'std'			=> 'post-thumbnail',
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "show_thumbnail", 'value' => array('1'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Grid Hover Style', 'wd_package' ),
					'param_name' 	=> 'grid_hover_style',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Normal', 'wd_package' )			=> 'normal',
						esc_html__( 'Content Inner', 'wd_package' ) 	=> 'grid-inner',
					),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Number Excerpt", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'number_excerpt',
					'value' 		=> '10',
					'edit_field_class' => 'vc_col-sm-6',
				),
				//Slider
				array(
					"type" 			=> "dropdown", 
					"class" 		=> "",
					"heading" 		=> esc_html__("Is Slider", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "is_slider",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",
				),
				array(	
					"type" 			=> "dropdown",	
					"class" 		=> "",	
					"heading" 		=> esc_html__("Show Nav", 'wd_package'),	
					"admin_label" 	=> true,	
					"param_name" 	=> "show_nav",	
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),	
					"description" 	=> "",	
					'edit_field_class' => 'vc_col-sm-4',	
					'dependency'  	=> array('element' => "is_slider", 'value' => array('1'))	
				),	
				array(	
					"type" 			=> "dropdown",	
					"class" 		=> "",	
					"heading" 		=> esc_html__("Auto Play", 'wd_package'),	
					"admin_label" 	=> true,	
					"param_name" 	=> "auto_play",	
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",	
					'edit_field_class' => 'vc_col-sm-4',	
					'dependency'  	=> array('element' => "is_slider", 'value' => array('1'))	
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> ''
				)
			)
		);
	}
}