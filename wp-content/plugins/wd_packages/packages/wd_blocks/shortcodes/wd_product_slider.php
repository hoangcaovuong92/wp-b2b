<?php
if (!function_exists('wd_product_slider_function')) {
	function wd_product_slider_function($atts) {
		extract(shortcode_atts(array(
			'id_category'			=> '-1',
			'data_show'				=> 'recent_product',
			'number_products'		=> '12',
			'image_size'		    => 'shop_catalog',
			'sort'					=> 'DESC',
			'order_by'				=> 'date',
			'columns'				=> '1',
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'show_nav'				=> '1',
			'auto_play'				=> '1',
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

		$is_slider 		= 1;
		$products 		= new WP_Query( $args );
		$count 			= 0;
		$random_id 		= 'wd-simple-product-slider-'.mt_rand();	
		ob_start(); ?>
		<?php if ( $products->have_posts() ) : ?>
			<div class="wd-shortcode-product-simple-slider woocommerce <?php echo esc_html($class); ?>">
				<div id="<?php echo esc_attr( $random_id ); ?>" class='wd-shortcode-product-slider'>
					<ul class="products grid">
						<div class="wd-products-wrapper">				
							<?php while ( $products->have_posts() ) : $products->the_post(); global $post; ?>
								<?php wc_get_template( 'content-product.php', array(
									'image_size'    => $image_size,
								) ); ?>
								<?php //include( locate_template( 'woocommerce/content-product.php' ) ); ?>
							<?php endwhile; //End While ?>
						</div>
					</ul>
				</div>
			</div>
		<?php endif; // Have Product ?>	
		<?php
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}
}

if (!function_exists('wd_product_slider_vc_map')) {
	function wd_product_slider_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			"name"				=> esc_html__("WD - Products Slider",'wd_package'),
			"base"				=> 'wd_product_slider',
			'description' 		=> esc_html__("Simple Product Slider with dot style...", 'wd_package'),
			"category"			=> esc_html__("WD - Product",'wd_package'),
			'icon'        		=> 'icon-wpb-woocommerce',
			"params" 			=> array(
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
					'description' => ''
				),
				array(
					'type'			=> 'textfield',
					'heading' 		=> esc_html__( 'Number Of Products', 'wd_package' ),
					'param_name' 	=> 'number_products',
					'admin_label' 	=> true,
					'value' 		=> '12',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image Size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'description' 	=> '',
					'std'			=> 'shop_catalog',
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
					'value' 		=> wd_vc_get_order_by_values('product'),
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