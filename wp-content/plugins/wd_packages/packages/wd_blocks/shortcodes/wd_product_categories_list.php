<?php
if ( ! function_exists( 'wd_product_categories_list_function' ) ) {
	function wd_product_categories_list_function( $atts ) {
		extract(shortcode_atts( array(
			'title'      			=> 'Categories',
			'ids_category'      	=> '',
			'text_align'      		=> 'text-center',
			'view_all'      		=> '1',
			'class'      			=> 'heading-1',
		), $atts ));
		if (!wd_is_woocommerce()) return;

		$shop_page_url = (get_permalink(wc_get_page_id('shop'))) ? get_permalink(wc_get_page_id('shop')) : '#';
		ob_start();
			?>
			<div class="wd-products-categories-list woocommerce <?php echo esc_html($class); ?>">
				<ul class="wd-products-categories-list-content <?php echo esc_html($text_align); ?>">
					<?php if ($title): ?>
						<li class="heading">
							<h2 class="wd-heading">
								<?php echo $title; ?>
							</h2><!-- .wd-heading -->
						</li><!-- .heading -->
					<?php endif ?>
					
					<?php if ($ids_category) {
						foreach ( wd_get_category_name_by_ids( explode( ',', $ids_category ) ) as $category ) {
							$name 	= $category['name'];
							$link 	= get_term_link( $category['id'], 'product_cat' );
							echo '<li class="wd-products-categories-list-item">';
							echo '<a href="'.esc_url($link).'">'.$name.'</a>';
							echo '</li>';
						}
					}else{
						$args = array(
							'number'     => '',
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => true,
						);
						$product_categories = get_terms( 'product_cat', $args );
						foreach ($product_categories as $category) {
							$name 	= $category->name;
							$link 	= get_term_link( $category->term_id, 'product_cat' );
							echo '<li class="wd-products-categories-list-item">';
							echo '<a href="'.esc_url($link).'">'.$name.'</a>';
							echo '</li>';
						}
					}
					?>

					<?php if ($view_all): ?>
						<li class="wd-products-categories-list-item">
							<a href="<?php echo esc_url($shop_page_url); ?>"><?php esc_html_e( 'View All', 'wd_package' ) ?></a>
						</li><!-- .heading -->
					<?php endif ?>
				</ul>
			</div><!-- .products-by-category-tabs -->
			<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}

if (!function_exists('wd_product_categories_list_vc_map')) {
	function wd_product_categories_list_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			'name'        => __( "WD - Products Categories List", 'wd_package' ),
			'description' => __( "Display product categories name with list style...", 'wd_package' ),
			'base'        => 'wd_product_categories_list',
			"category"    => esc_html__("WD - Product", 'wd_package'),
			'icon'        => 'icon-wpb-woocommerce',
			'params'      => array(
				array(
					'type'        	=> 'textfield',
					'class'       	=> '',
					'heading'     	=> __( "Title", 'wd_package' ),
					'description' 	=> __( "Leave blank to hide title", 'wd_package' ),
					'admin_label' 	=> true,
					'param_name'  	=> 'title',
					'value'       	=> __( 'Categories', 'wd_package' ),
				),
				array(
					'type' 			=> 'sorted_list',
					'heading' 		=> __( 'Categories', 'wd_package' ),
					'param_name' 	=> 'ids_category',
					'description' 	=> __( 'Select and sort product categories. Leave blank if you want to display all product category', 'wd_package' ),
					'value' 		=> '-1',
					'options' 		=> wd_vc_get_list_category('product_cat', false, 'sorted_list'),
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Text Align', 'wd_package' ),
					'param_name' 	=> 'text_align',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_text_align_bootstrap(),
					'std'			=> 'text-center',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'View All', 'wd_package' ),
					'description' 	=> __( 'Show item "View All" redirects to shop page.', 'wd_package' ),
					'param_name'  	=> 'view_all',
					'value'       	=> array(
						__( 'Show', 'wd_package' ) 	=> '1',
						__( 'Hide', 'wd_package' ) 	=> '0',
					),
					'save_always' 	=> true,
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'        	=> 'textfield',
					'heading'     	=> __( "Extra class name", 'wd_package' ),
					'description' 	=> __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package' ),
					'admin_label' 	=> true,
					'param_name'  	=> 'class',
					'value'       	=> '',
				),
			)
		);
	}
}