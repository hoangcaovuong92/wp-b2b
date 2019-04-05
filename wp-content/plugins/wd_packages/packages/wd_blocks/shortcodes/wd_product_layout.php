<?php
if (!function_exists('wd_product_layout_function')) {
	function wd_product_layout_function($atts) {
		extract(shortcode_atts(array(
			'id_category'			=> '-1',
			'data_show'				=> 'recent_product',
			'number_products'		=> '12',
			'sort'					=> 'DESC',
			'order_by'				=> 'date',
			'columns'				=> '4',
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'result_count'			=> '1',
			'grid_list_button'		=> '1',
			'grid_list_layout'		=> 'grid',
			'pagination_loadmore'	=> 'pagination',
			'fullwidth_mode' 		=> false,
			'class'					=> ''
		), $atts));
		if (!wd_is_woocommerce()) return;

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

		//array send to ajax loadmore
		//List product ID
		$list_post_id = array();
		$products 			= new WP_Query( $args );
		$count_products 	= $products->found_posts;
		$columns_product 	= 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile;
		$random_id 			= 'wd-product-grid-list-'.rand(0,1000).time();
		
		$class .= ($pagination_loadmore === "loadmore") ? ' wd-infinite-scroll-wrap' : '';

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		wp_reset_query();
		ob_start(); ?>
		<div id="<?php echo esc_attr($random_id); ?>" class="wd-shortcode wd-shortcode-product-grid-list woocommerce <?php echo esc_attr($class); ?>">
			<?php if ( $products->have_posts() ) : ?>
				<?php if ($grid_list_button == 1) { ?>
					<div class="wd-product-toggle-layout-wrapper">
						<?php
							/**
							 * wd_hook_product_archive_toggle_button hook.
							 *
							 * @hooked woocommerce_result_count - 5
							 * @hooked woocommerce_catalog_ordering - 10
							 * @hooked product_archive_toggle_button - 15
							 */
							do_action('wd_hook_product_archive_toggle_button');
						?>
					</div>
				<?php } ?>
				
				<div class="wd-products-wrapper wd-product-switchable-layout <?php echo esc_html($columns_product); ?>" 
					data-columns="<?php echo esc_attr($columns); ?>" 
					data-default-layout="<?php echo esc_attr($grid_list_layout); ?>">
					
					<?php if ($grid_list_button == 1) { 
						woocommerce_product_loop_start(); 
					}else{ ?>
						<ul class="products <?php echo esc_attr($grid_list_layout); ?>">
					<?php } ?>

					<?php while ( $products->have_posts() ) : 
						$products->the_post();
						global $product; 
						wc_get_template_part( 'content', 'product' );
						$list_post_id[] = $product->get_id(); ?>
					<?php endwhile;	?>

					<?php woocommerce_product_loop_end(); ?>

				</div>

				<?php if($pagination_loadmore == 'pagination') : ?> 
					<div class="wd-pagination">
						<?php echo apply_filters('wd_filter_display_pagination', 3, $products); ?>
					</div>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
				<?php if($pagination_loadmore == 'loadmore') : ?> 
					<div class="wd-loadmore">
						<div class="wd-icon-loading" id="<?php echo esc_html($random_id); ?>-icon-loading">
							<img src="<?php echo WD_BLOCKS_BASE_URI.'/assets/images/ajax-loader_image.gif';?>" alt="HTML5 Icon" style="height:15px;">
						</div>

						<div classs="wd-loadmore-btn-wrap">
							<a 	data-random_id="<?php echo esc_html($random_id); ?>"
								data-post__not_in='<?php echo json_encode($list_post_id); ?>'
								data-query_data='<?php echo json_encode($args); ?>'
								href="#" class="button wd-loadmore-btn wd-loadmore-btn--product"><?php _e('LOAD MORE','wd_package') ?></a>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; // Have Product?>	
		</div>
		<?php
		$content = ob_get_clean();
		wp_reset_query();
		return $content;
	}
}

if (!function_exists('wd_product_layout_vc_map')) {
	function wd_product_layout_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			"name"				=> esc_html__("WD - Product Grid/List",'wd_package'),
			"base"				=> 'wd_product_layout',
			'description' 		=> esc_html__("Display Products with Grid/List layout...", 'wd_package'),
			"category"			=> esc_html__("WD - Product",'wd_package'),
			'icon'        		=> 'icon-wpb-woocommerce',
			"params"			=>array(	
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category(),
					'description' 	=> '',
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
					'heading' 		=> esc_html__( 'Number of products', 'wd_package' ),
					'param_name' 	=> 'number_products',
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
					'std'			=> '4',
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
					'heading' 		=> esc_html__( 'Show Layout Switch Button', 'wd_package' ),
					'param_name' 	=> 'grid_list_button',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Default Layout', 'wd_package' ),
					'param_name' 	=> 'grid_list_layout',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Grid', 'wd_package' )		=> 'grid',
						esc_html__( 'List', 'wd_package' )		=> 'list'
					),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "grid_list_button", 'value' => array('0'))
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Pagination/Load More', 'wd_package' ),
					'param_name' 	=> 'pagination_loadmore',
					'admin_label' 	=> true,
					'value' 	=> array(
						esc_html__( 'Pagination', 'wd_package' )		=> 'pagination',
						esc_html__( 'Infinite Scroll', 'wd_package' )	=> 'loadmore',
						esc_html__( 'No Show', 'wd_package' )			=> '0'
					),
					'description' 	=> '',
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