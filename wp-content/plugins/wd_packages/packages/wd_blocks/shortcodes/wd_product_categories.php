<?php
if (!function_exists('wd_product_categories_function')) {
	function wd_product_categories_function($atts) {
		extract(shortcode_atts(array(
			'ids_category'		=> '',
			'style'				=> 'style-1',
			'sort'				=> 'DESC',
			'order_by'			=> 'term_id',
			'columns'			=> 3,
			'columns_tablet'	=> 2,
			'columns_mobile'	=> 1,
			'title'				=> '1',
			'thumbnail'			=> '1',
			'readmore'			=> '1',
			'meta'				=> '1',
			'class'				=> ''

		), $atts));
		if (!wd_is_woocommerce()) return;
		wp_reset_query();	

		$args = array(
		    'order'    		=> $sort,
		    'orderby'      	=> $order_by,
		    'hide_empty' 	=> false,
		);
		if ($ids_category) {
			$args['include'] = explode(',', $ids_category);
		}
		$columns_product 	= 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile;
		$product_categories = get_terms( 'product_cat', $args );
		$num_count 			= count($product_categories);
		$i 					= 0;
		$random_id 			= 'wd-shortcode-product-category-'.rand(0, 1000);	
		ob_start(); ?>

		<?php if( $num_count > 0 ) : ?>
			<div id="<?php echo esc_html($random_id); ?>" class="wd-shortcode-product-category woocommerce <?php echo esc_html($columns_product); ?> <?php echo esc_html($style); ?>">
				<ul class="wd-shortcode-product-category-content wd-columns-list-item" >
				<?php foreach( $product_categories as $cat ) { ?>
					<li class="item <?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>" >
						<?php
							$title_category 		= $cat->name;
							$id_category 			= $cat->term_id;
							$thumbnail_category 	= get_woocommerce_term_meta( $id_category , 'thumbnail_id', true ); 
							$image_url_category 	= wp_get_attachment_url( $thumbnail_category );
						?>
						<?php if($thumbnail && $image_url_category != '') : ?> 
							<div class="wd-product-category-thumbnail">
								<a href="<?php echo get_category_link($id_category); ?>">
									<img src="<?php echo $image_url_category; ?>" title="<?php echo $title_category; ?>" alt="<?php echo $title_category; ?>">
								</a>
							</div>
						<?php endif; ?>
						<div class="wd-product-category-info">
							<?php if($title ) : ?>
								<a href="<?php echo get_category_link($id_category); ?>">
									<h2 class="class='wd-product-category-title"><?php echo $title_category; ?></h2>
								</a>
							<?php endif; ?>
							<?php if($meta ) : ?>
								<span class='wd-product-category-meta'>(<?php printf(__('%d products','wd_package'), $cat->count); ?>)</span>
							<?php endif; ?>
							<?php if($readmore ) : ?>
								<a class='wd-product-category-readmore' href="<?php echo get_category_link($id_category); ?>"><?php echo esc_html('Read more','wd_package'); ?></a>
							<?php endif; ?>
				
						</div>
					</li>			
				<?php $i++; } // End While ?>
				</ul>
			</div>
		<?php endif; ?>
		<?php
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}
}

if (!function_exists('wd_product_categories_vc_map')) {
	function wd_product_categories_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			"name"				=> esc_html__("WD - Product Categories",'wd_package'),
			"base"				=> 'wd_product_categories',
			'description' 		=> esc_html__("Display product categories content...", 'wd_package'),
			"category"			=> esc_html__("WD - Product",'wd_package'),
			'icon'        		=> 'icon-wpb-woocommerce',
			"params"			=>array(	
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
					'heading' 		=> esc_html__( 'Style', 'wd_package' ),
					'param_name' 	=> 'style',
					'admin_label' 	=> true,
					'value' 		=> array(
							esc_html__( 'Style 1', 'wd_package' )	=> 'style-1',
							esc_html__( 'Style 2', 'wd_package' )	=> 'style-2'
						),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Sort By', 'wd_package' ),
					'param_name' 	=> 'sort',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_sort_by_values(),
					'std'			=> 'DESC',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Order By', 'wd_package' ),
					'param_name' 	=> 'order_by',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_order_by_values('term'),
					'std'			=> 'term_id',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					"type" 			=> "textfield",
					"class"			=> "",
					"heading" 		=> esc_html__("Columns", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "columns",
					"value" 		=> '3',
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show title', 'wd_package' ),
					'param_name' 	=> 'title',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Image Category', 'wd_package' ),
					'param_name'	=> 'thumbnail',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Readmore', 'wd_package' ),
					'param_name' 	=> 'readmore',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> esc_html__('', 'wd_package'),
					'edit_field_class' => 'vc_col-sm-3',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show meta', 'wd_package' ),
					'param_name' 	=> 'meta',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'description' 	=> esc_html__('', 'wd_package'),
					'edit_field_class' => 'vc_col-sm-3',
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