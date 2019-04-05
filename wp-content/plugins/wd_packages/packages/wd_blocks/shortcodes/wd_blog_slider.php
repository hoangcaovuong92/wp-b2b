<?php
if(!function_exists('wd_blog_slider_function')){
	function wd_blog_slider_function($atts){
		extract(shortcode_atts(array(
			'id_category'			=> '-1',
			'data_show'				=> 'recent_blog',
			'number_blogs'			=> '12',
			'sort'					=> 'DESC',
			'order_by'				=> 'date',
			'columns'				=> 3,
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'image_size'			=> 'full',
			'grid_hover_style'		=> 'normal',
			'show_nav'				=> '1',
			'auto_play'				=> '1',
			'fullwidth_mode'		=> false,
			'class'					=> '',
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

		$slider_options = json_encode(array(
			'slider_type' => 'owl',
			'column_desktop' => esc_attr($columns),
			'column_tablet' => esc_attr($columns_tablet),
			'column_mobile' => esc_attr($columns_mobile),
			'arrows' => $show_nav ? true : false,
			'autoplay' => $auto_play ? true : false,
		));

		$class	.= ($grid_hover_style === 'grid-inner') ? ' wd-masonry-blog-content-inner' : '';
		$class	.= ($columns == '1') ? ' wd-masonry-blog-slider-single-column' : '';

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
		wp_reset_query();
		$recent_posts 		= new WP_Query( $args );
		ob_start();
		if ( $recent_posts->have_posts() ) { ?>
			<div class="wd-shortcode wd-shortcode-blog-slider <?php echo esc_attr($class); ?>">
				<div class="wd-blog-wrapper wd-slider-wrap wd-slider-wrap--blog-slider"
					data-slider-options='<?php echo esc_attr( $slider_options ); ?>'>
					<?php while( $recent_posts->have_posts() ) { 
						$recent_posts->the_post();	global $post; ?>
						<?php echo apply_filters('wd_filter_blog_content', array('thumbnail_size' => $image_size)); ?>
					<?php } // End While ?>
				</div>
			</div>
		<?php }
		$output = ob_get_clean();
		wp_reset_query();
		return $output;
	}
}

if (!function_exists('wd_blog_slider_vc_map')) {
	function wd_blog_slider_vc_map() {
		return array(
			"name"				=> esc_html__("WD - Blog Slider",'wd_package'),
			"base"				=> 'wd_blog_slider',
			'description' 		=> esc_html__("Display Blog with slider...", 'wd_package'),
			"category"			=> esc_html__("WD - Blog",'wd_package'),
			'icon'        		=> 'icon-wpb-toggle-small-expand',
			"params"=>array(	
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category('category'),
					'description' 	=> '',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Data Show', 'wd_package' ),
					'param_name' 	=> 'data_show',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_special_blog_name(),
					'description' => '',
				),
				array(
					'type'			=> 'textfield',
					'heading' 		=> esc_html__( 'Number of blogs', 'wd_package' ),
					'param_name' 	=> 'number_blogs',
					'admin_label' 	=> true,
					'value' 		=> '12',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Sort By', 'wd_package' ),
					'param_name' 	=> 'sort',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_sort_by_values(),
					'std'			=> 'DESC',
					'description' 	=> '',
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
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'description'  => '',
					'edit_field_class' => 'vc_col-sm-6',
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
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Show Nav", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "show_nav",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Auto Play", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "auto_play",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
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