<?php
if (!function_exists('wd_product_special_function')) {
	function wd_product_special_function($atts) {
		extract(shortcode_atts(array(
			'id_category'			=> '-1',
			'data_show'				=> 'recent_product',
			'number_products'		=> '12',
			'product_style'			=> 'grid',
			'image_size'			=> 'shop_catalog',
			'sort'					=> 'DESC',
			'order_by'				=> 'date',
			'columns'				=> '1',
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'is_slider'				=> '1',
			'center_mode'			=> '0',
			'show_nav'				=> '1',
			'auto_play'				=> '1',
			'fullwidth_mode' 		=> false,
			'class'					=> ''
		), $atts));
		if (!wd_is_woocommerce()) return;
		wp_reset_query();	

		$args = array(
			'posts_per_page' 	=> (int)$number_products,
			'order' 			=> $sort,
		);
		$settings = array(
			'category' 	=> array(
				'product_cat'	=> (int)$id_category
			),
			'order_by' 	=> $order_by,
			'data_show' => $data_show
		);
		$args = apply_filters('wd_filter_get_product_query', $args, $settings);

		if ($is_slider) {
			$wrap_class = '';
			$list_class	= 'wd-slider-wrap wd-slider-wrap--product-special';
			$slider_options = json_encode(array(
				'slider_type' => ($center_mode) ? 'slick' : 'owl',
				'column_desktop' => esc_attr($columns),
				'column_tablet' => esc_attr($columns_tablet),
				'column_mobile' => esc_attr($columns_mobile),
				'arrows' => $show_nav ? true : false,
				'autoplay' => $auto_play ? true : false,
				'centerMode' => $center_mode ? true : false,
			));
		}else{
			$wrap_class = ($is_slider == '0') ? 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile : '';
			$list_class	= 'wd-columns-list-item';
			$slider_options	= '';
		}

		$products 			= new WP_Query( $args );

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
		ob_start(); ?>
		<?php if ( $products->have_posts() ) : ?>
			<div class="wd-products-wrapper wd-shortcode-product-special woocommerce <?php echo esc_attr( $wrap_class ); ?> <?php echo esc_attr($class); ?>">
				<ul class="products <?php echo esc_attr( $product_style ); ?> <?php echo esc_attr( $list_class ); ?>" 
					data-slider-options='<?php echo esc_attr( $slider_options ); ?>'>
					<!-- Begin while -->
					<?php while ( $products->have_posts() ) : 
						$products->the_post();  
						global $product; 
						wc_get_template( 'content-product.php', array(
							'image_size'    => $image_size,
						) ); 
					endwhile; ?>
					<!-- End While -->
				</ul>
			</div>
		<?php endif; // Have Product?>	
		<?php
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}
}

if (!function_exists('wd_product_special_vc_map')) {
	function wd_product_special_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			"name"				=> esc_html__("WD - Products Special", 'wd_package'),
			"base"				=> 'wd_product_special',
			'description' 		=> esc_html__("Display product with custom thumbnail image size...", 'wd_package'),
			"category"			=> esc_html__("WD - Product", 'wd_package'),
			'icon'       	 	=> 'icon-wpb-woocommerce',
			"params"			=>array(
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category(),
					'description' 	=> ''
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Data Show', 'wd_package' ),
					'param_name' 	=> 'data_show',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_special_product_name(),
					'description' 	=> '',
				),
				array(
					'type'			=> 'textfield',
					'heading' 		=> esc_html__( 'Number Of Products', 'wd_package' ),
					'param_name' 	=> 'number_products',
					'admin_label' 	=> true,
					'value' 		=> '12',
					'edit_field_class' => 'vc_col-sm-4',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Sort By', 'wd_package' ),
					'param_name' 	=> 'sort',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_sort_by_values(),
					'std'			=> 'DESC',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-4',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Order By', 'wd_package' ),
					'param_name' 	=> 'order_by',
					'admin_label' 	=> true ,
					'value' 		=> wd_vc_get_order_by_values('product'),
					'std'			=> 'date',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-4',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Product Style', 'wd_package' ),
					'param_name' 	=> 'product_style',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Grid', 'wd_package' )		=> 'grid',
						esc_html__( 'List', 'wd_package' )		=> 'list'
					),
					'description' 	=> '',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Image Padding", 'wd_package'),
					'description' 	=> esc_html__("Padding between images. Only fill in whole numbers or real numbers. Example: 2.5 (Unit: pixels)", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'image_padding',
					'value' 		=> '0',
					'dependency'  	=> array('element' => "product_style", 'value' => array('image_thumb_only'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Thumbnail Size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'description' 	=> '',
					'std'			=> 'shop_catalog',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Columns', 'wd_package' ),
					'param_name' 	=> 'columns',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_columns(),
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
				/*-----------------------------------------------------------------------------------
					SLIDER SETTING
				-------------------------------------------------------------------------------------*/
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
					'edit_field_class' => 'vc_col-sm-6',
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
					'edit_field_class' => 'vc_col-sm-6',
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