<?php
if ( ! function_exists( 'wd_product_category_tabs_function' ) ) {
	function wd_product_category_tabs_function( $atts ) {
		extract(shortcode_atts( array(
			'title'      			=> 'Categories',
			'type'       			=> 'special',
			'id_event'   			=> '',
			'id_single'  			=> '',
			'ids'     	   			=> '',
			'style_tab'				=> 'tab-on-top-content',
			'view_all_tab'  		=> '1',
			'show_event' 			=> 'bestselling',
			'columns' 				=> '4',
			'columns_tablet'		=> 2,
			'columns_mobile'		=> 1,
			'posts_per_page'		=> '8',
			'sort'      			=> 'DESC',
			'order_by'    			=> 'date',
			'is_slider'				=> '0',
			'mansory_layout'		=> '0',
			'mansory_image_size'	=> '1:1, 2:2, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 2:2, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 2:2',
			'show_category_thumb'	=> '0',
			'show_nav'				=> '1',
			'auto_play'				=> '1',
			'per_slide'				=> '1',
			'class'      			=> 'heading-1',
		), $atts ));	
		if (!wd_is_woocommerce()) return;

		$tab_style_class 	= ($type == 'categories') ? 'wd-tab-style-'.$style_tab : '';
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $posts_per_page,
			'order'               => $sort,
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
		);

		switch ( $order_by ) {
			case 'price':
				$args['meta_key'] 	= '_price';
				$args['orderby']  	= 'meta_value_num';
				break;
			case 'sales':
				$args['meta_key'] 	= 'total_sales';
				$args['orderby']  	= 'meta_value_num';
				break;
			default:
				$args['orderby'] 	= $order_by;
				break;
		}

		$tab = array();
		switch ( $type ) {
			case 'special':
				$tab['id']  = $id_event;
				$show_event = array_filter( explode( ',', $show_event ) );
				foreach ( $show_event as $key => $show ) {
					switch ( $show ) {
						case 'bestselling':
							if ( 0 == $key ) {
								$args['meta_key'] = 'total_sales';
								$args['orderby']  = 'meta_value_num';
							}

							$item_name = __( 'Bestselling', 'wd_package' );
							break;
						case 'featured':
							if ( 0 == $key ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'product_visibility',
									'field'    => 'id',
									'terms'    => 'featured',
									'operator' => 'IN',
								);
							}
							$item_name = __( 'Featured', 'wd_package' );
							break;
						case 'new_arrivals':
							if ( 0 == $key ) {
							}
							$item_name = __( 'New Arrivals', 'wd_package' );
							break;
						case 'top_reviewed':
							if ( 0 == $key ) {
								$args['meta_key'] = '_wc_average_rating';
								$args['orderby']  = 'meta_value_num';
							}
							$item_name = __( 'Top Reviewed', 'wd_package' );
							break;
					}
					/** @var string $item_name */
					$tab['items'][] = array(
						'name' => $item_name,
						'id'   => $tab['id'],
						'slug' => str_replace( '_', '-', $show ),
						'link' => 'products-by-category-tabs-' . str_replace( '_', '-', $show ) . '-' . $tab['id'],
					);
				}
				break;
			case 'single_category':
				$tab['id'] = $id_single;
				// Get list id subcategory + name subcategory => array
				$subcategory = wd_get_subcategory( $tab['id'] );
				if ( isset( $subcategory[0]['id'] ) ) {
					$tab['id'] = $subcategory[0]['id'];
				}
				foreach ( $subcategory as $category ) {
					$tab['items'][] = array(
						'name' => $category['name'],
						'id'   => $category['id'],
						'slug' => $category['slug'],
						'link' => 'products-by-category-tabs-' . $category['slug'] . '-' . rand(),
					);
				}
				break;
			case 'categories':
				foreach ( wd_get_category_name_by_ids( explode( ',', $ids ) ) as $category ) {
					$tab['items'][] = array(
						'name' => $category['name'],
						'id'   => $category['id'],
						'slug' => $category['slug'],
						'link' => 'products-by-category-tabs-' . $category['slug'],
					);
				}
				$tab['id'] = $tab['items'][0]['id'];
				break;
		}

		$args['tax_query'][] = array(
			'terms'    => $tab['id'],
			'taxonomy' => 'product_cat', 
		);

		if ( $type === 'categories' ) {
			$tab['heading'] = '<span>' . $title . '</span>';
			$tab['link']    = '<span><a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '" role="tab">' . __( 'View All Products', 'wd_package' ) . '</a></span>';
		} else {
			$term           = get_term_by( 'id', $tab['id'], 'product_cat' );
			$tab['heading'] = '<a href="' . get_term_link( $term->slug, $term->taxonomy ) . '">' . $term->name . '</a>';
			$tab['link']    = '<span><a href="' . get_term_link( $term->slug, $term->taxonomy ) . '" role="tab">' . __( 'View All Products', 'wd_package' ) . '</a></span>';
		}

		$tab['img'] = wp_get_attachment_image( get_term_meta( $tab['id'], 'thumbnail_id', true ), 'full' );

		$products = new WP_Query( $args );

		$num_post =  $products->found_posts;
		if( $num_post < 2 || $num_post <= ($per_slide * $columns) ){
			$is_slider = '0';
		}
		if( $num_post <= $posts_per_page ){
			$posts_per_page = $num_post;
		}
		$columns_product 	= ($is_slider == '0') ? 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile : '';

		ob_start();
		if ( $products->have_posts() ) : ?>
			<div class="products-by-category-tabs woocommerce <?php echo esc_html($tab_style_class); ?> <?php echo esc_attr( $class ) ?>">
				<div class="tab-control">
					<ul class="tabs" role="tablist">
						<?php if ($title): ?>
							<li class="heading">
								<h2 class="wd-heading">
									<?php echo $tab['heading'] ?>
								</h2><!-- .wd-heading -->
							</li><!-- .heading -->
						<?php endif ?>
						
						<?php if (isset($tab['items']) && count($tab['items']) > 0) : ?>
							<?php foreach ( $tab['items'] as $key => $item ) : ?>
								<li class="tab <?php echo ( 0 == $key ) ? 'active' : '' ?>" role="presentation">
									<a href="#<?php echo $item['link']; ?>"
									   role="tab" data-toggle="tab"
									   data-type				= "<?php echo $type; ?>"
									   data-slug				= "<?php echo $item['slug']; ?>"
									   data-sort				= "<?php echo $sort; ?>"
									   data-order_by			= "<?php echo $order_by; ?>"
									   data-id					= "<?php echo $item['id']; ?>"
									   data-columns				= "<?php echo $columns; ?>"
									   data-columns_tablet		= "<?php echo $columns_tablet; ?>"
									   data-columns_mobile		= "<?php echo $columns_mobile; ?>"
									   data-posts_per_page		= "<?php echo $posts_per_page; ?>"
									   data-is_slider			= "<?php echo $is_slider; ?>"
									   data-mansory_layout		= "<?php echo $mansory_layout; ?>"
									   data-mansory_image_size	= "<?php echo $mansory_image_size; ?>"
									   data-show_category_thumb	= "<?php echo $show_category_thumb; ?>"
									   data-show_nav			= "<?php echo $show_nav; ?>"
									   data-auto_play			= "<?php echo $auto_play; ?>"
									   data-per_slide			= "<?php echo $per_slide; ?>"
									   ><?php echo $item['name'] ?></a>
								</li>
							<?php endforeach; ?>
						<?php endif ?>

						<?php if ($type !== 'categories' || ($type === 'categories' && $view_all_tab)): ?>
							<li class="tab tab-link" role="presentation"><?php echo $tab['link'] ?></li>
						<?php endif ?>
						
					</ul>
				</div><!-- .tabs-control -->
				<div class="tab-content">
					<?php 
					$mansory_image_size = $mansory_image_size != '' ? explode(',', $mansory_image_size) : '';
					$masonry_enable 	= $is_slider == 0 && $mansory_layout == '1' && $mansory_image_size != '' ? true : false;
					$class_masonry_wrap = ($masonry_enable) ? 'wd-product-mansonry-wrap' : '';
					$class_masonry_item = ($masonry_enable) ? 'wd-product-mansonry-item' : '';
					?>
					<?php if (isset($tab['items']) && count($tab['items']) > 0) : ?>
						<?php foreach ( $tab['items'] as $key => $item ) : ?>
							<div 	role="tabpanel" class="tab-pane <?php echo ( 0 == $key ) ? 'active' : '' ?>"
							        id="<?php echo $item['link'] ?>"
							        data-load="<?php echo ( 0 == $key ) ? 'loaded' : 'loading' ?>">
								
								<?php $random_id 			= 'wd-product-by-category-tab-'.mt_rand(); ?>
								<div id="<?php echo esc_attr( $random_id ); ?>" class="<?php echo esc_attr( $class_masonry_wrap ); ?>">
									<div class="wd-products-wrapper <?php echo esc_html($columns_product); ?> <?php echo ( 0 == $key ) ? 'products-by-category-tabs-products' : 'products-by-category-tabs-loading' ?>">
										<?php if ( 0 == $key ): ?>
											
											<?php if ($is_slider == '1') : ?>
												<div class="products grid">
											<?php else: ?>
												<ul class="products grid">
											<?php endif ?>

												<?php if ($is_slider == 0 && $show_category_thumb): ?>
													<?php echo '<li class="wd-product-by-category-thumbnail">' . $tab['img'] . '</li>'; //category thumbnail ?> 
												<?php endif ?>

												<?php 
												$count 				= 0; 
												$size_count 		= 0;
												?>

												<?php while ( $products->have_posts() ) : $products->the_post(); ?> 
													<?php if (($count == 0 || $count % $per_slide == 0) && $is_slider == '1') : ?>
														<ul class="widget_per_slide">
													<?php endif; // Endif ?>

														<?php 
														$image_size 		= 'shop_catalog';
														$custom_width_class = '';
														if ($masonry_enable){
															$size_count = ($size_count >= count($mansory_image_size)) ? 0 : $size_count;
															$image_size = trim($mansory_image_size[$size_count]);
															if ($image_size == '1:1'){
																$image_size 		= 'wd_image_size_square_small';
															}elseif ($image_size == '2:2'){
																$image_size 		= 'wd_image_size_square_large';
																$custom_width_class = 'wd-columns-double-width';
															} 
														} ?>

														<?php wc_get_template( 'content-product.php', array(
													        'image_size'    		=> $image_size,
													        'custom_width_class'	=> $custom_width_class.' '.$class_masonry_item,
													        'mansory_hover_layout'	=> $masonry_enable,
													    ) ); ?>
														<?php //include( locate_template( 'woocommerce/content-product.php' ) ); ?>

													<?php $count++; $size_count++; ?>

													<?php if( ($count % $per_slide == 0 || $count == $num_post) && $is_slider == '1' ): ?>
														</ul>
													<?php endif; // Endif ?> 
												<?php endwhile; ?> 

											<?php if ($is_slider == '1') : ?>
												</div>
											<?php else: ?>
												</ul>
											<?php endif ?>

										<?php else: ?>
											<?php $number_loading_icon = ($is_slider == 0) ? $posts_per_page : ($columns * $per_slide); ?>
											<div class="<?php echo 'wd-columns-'.$columns; ?>">
												<ul class="text-center">
													<?php for ( $i = 0; $i < $number_loading_icon; $i ++ ): ?>
														<li><img alt="<?php __( 'loading', 'wd_package' ) ?>" src="<?php echo WD_BLOCKS_BASE_URI.'/assets/images/loading.gif';?>"></li>
													<?php endfor; ?>
												</ul>
											</div>
										<?php endif; ?>
									</div>
									<?php if( $show_nav && $is_slider ){ ?>
										<?php wd_slider_control(); ?>
									<?php } ?>
									
									<?php if ( $is_slider == '1') : ?>
										<script type="text/javascript">
											jQuery(document).ready(function(){
												"use strict";						
												var $_this = jQuery('#<?php echo esc_attr( $random_id ); ?>');
												var _auto_play = <?php echo esc_attr( $auto_play ); ?> == 1;
												var owl = $_this.find('.wd-products-wrapper .products').owlCarousel({
													loop : true,
													items : <?php echo $columns; ?>,
													nav : false,
													dots : false,
													navSpeed : 1000,
													slideBy: 1,
													rtl:jQuery('body').hasClass('rtl'),
													navRewind: false,
													autoplay: _auto_play,
													autoplayTimeout: 5000,
													autoplayHoverPause: true,
													autoplaySpeed: false,
													mouseDrag: true,
													touchDrag: false,
													responsiveBaseElement: $_this,
													responsiveRefreshRate: 1000,
													responsive:{
														0:{
															items : 2
														},
														480:{
															items : 3
														},
														768:{
															items : 3
														},
														992:{
															items : <?php echo $columns; ?>
														},
													},
													onInitialized: function(){
													}
												});
												
												setTimeout(function(){ 
													owl.trigger('refresh.owl.carousel');
											 	}, 1500);
												
												$_this.on('click', '.next', function(e){
													e.preventDefault();
													owl.trigger('next.owl.carousel');
												});

												$_this.on('click', '.prev', function(e){
													e.preventDefault();
													owl.trigger('prev.owl.carousel');
												});
											});
										</script>
									<?php endif; // Endif Slider?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif ?>

				</div><!-- .tabs-contents -->
			</div><!-- .products-by-category-tabs -->
			<?php

			wp_reset_postdata();
		endif;

		return ob_get_clean();
	}
}

if (!function_exists('wd_product_category_tabs_vc_map')) {
	function wd_product_category_tabs_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			'name'        => __( "WD - Products by Category Tabs", 'wd_package' ),
			'description' => __( "Products by Category Tabs", 'wd_package' ),
			'base'        => 'wd_product_category_tabs',
			"category"    => esc_html__("WD - Product", 'wd_package'),
			'icon'        => 'icon-wpb-woocommerce',
			'params'      => array(
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Select Type', 'wd_package' ),
					'param_name'  => 'type',
					'admin_label' => true,
					'value'       => array(
						__( 'Special Products', 'wd_package' )      => 'special',
						__( 'Single Category', 'wd_package' ) 		=> 'single_category',
						__( 'Categories', 'wd_package' )      		=> 'categories',
					),
					'description' => __( 'Select Type and customize attributes in tab Category Settings', 'wd_package' ),
				),
				/*-----------------------------------------------------------------------------------
					Special Products
				-------------------------------------------------------------------------------------*/
				// Select a Category
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Category', 'wd_package' ),
					'description' => __( '', 'wd_package' ),
					'param_name'  => 'id_event',
					'admin_label' => true,
					'value'       => wd_vc_get_list_category('product_cat', false, 'sorted_list'),
					'dependency'  => array('element' => 'type', 'value'   => array( 'special' )),
				),
				// Show Event
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show Event', 'wd_package' ),
					'param_name' => 'show_event',
					'value'      => array(
						__( 'Bestselling', 'wd_package' )  => 'bestselling',
						__( 'Featured', 'wd_package' )     => 'featured',
						__( 'New Arrivals', 'wd_package' ) => 'new_arrivals',
						__( 'Top Reviewed', 'wd_package' ) => 'top_reviewed',
					),
					'std'        => 'bestselling',
					'dependency' => array('element' => 'type','value'   => array( 'special' )),
				),
				/*-----------------------------------------------------------------------------------
					Single Category
				-------------------------------------------------------------------------------------*/
				// Select a Category
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Category', 'wd_package' ),
					'description' => __( 'Select a category', 'wd_package' ),
					'param_name'  => 'id_single',
					'admin_label' => true,
					'value'       => wd_vc_get_list_category('product_cat', false, 'sorted_list'),
					'dependency'  => array('element' => 'type', 'value'   => array( 'single_category' )),
				),
				/*-----------------------------------------------------------------------------------
					Categories
				-------------------------------------------------------------------------------------*/
				array(
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => __( "Title", 'wd_package' ),
					'description' => __( "Leave blank to hide title", 'wd_package' ),
					'admin_label' => true,
					'param_name'  => 'title',
					'value'       => __( 'Categories', 'wd_package' ),
					'dependency'  => array('element' => 'type', 'value'   => array( 'categories' )),
				),
				array(
					'type' 			=> 'sorted_list',
					'heading' 		=> __( 'Categories', 'wd_package' ),
					'param_name' 	=> 'ids',
					'description' 	=> __( 'Select and sort product categories. Leave blank if you want to display all product category', 'wd_package' ),
					'value' 		=> '-1',
					'options' 		=> wd_vc_get_list_category('product_cat', false, 'sorted_list'),
					'dependency'  	=> array('element' => 'type', 'value'   => array( 'categories' )),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Style Tabs', 'wd_package' ),
					'description' => __( 'Style of product categories tabs', 'wd_package' ),
					'param_name'  => 'style_tab',
					'value'       => array(
						__( 'Tabs Menu On Top Content', 'wd_package' ) 	=> 'tab-on-top-content',
						__( 'Tabs Menu On Left Content', 'wd_package' ) => 'tab-on-left-content',
					),
					'save_always' => true,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  => array('element' => 'type', 'value'   => array( 'categories' )),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'View All Products Tab', 'wd_package' ),
					'description' => __( 'Enable this option to display the "View All Products" tab redirecting to the shop page.', 'wd_package' ),
					'param_name'  => 'view_all_tab',
					'value'       => array(
						__( 'Show', 'wd_package' ) 	=> '1',
						__( 'Hide', 'wd_package' ) 	=> '0',
					),
					'save_always' => true,
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  => array('element' => 'type', 'value'   => array( 'categories' )),
				),
				/*-----------------------------------------------------------------------------------
					Global
				-------------------------------------------------------------------------------------*/
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Columns', 'wd_package' ),
					'param_name' 	=> 'columns',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_columns(),
					'std' 			=> 4,
					'save_always' 	=> true,
					'description' 	=> esc_html__( '', 'wd_package' ),
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
					'type'        => 'textfield',
					'class'       => '',
					'heading'     => __( "Posts Per Page", 'wd_package' ),
					'description' => __( "Number products on one tab...", 'wd_package' ),
					'admin_label' => true,
					'param_name'  => 'posts_per_page',
					'value'       => 8,
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
					'admin_label' 	=> true ,
					'value' 		=> wd_vc_get_order_by_values('product'),
					'std'			=> 'date',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
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
					"heading" 		=> esc_html__("Mansory Layout", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "mansory_layout",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					"description" 	=> "",
					'dependency'  	=> array('element' => "is_slider", 'value' => array('0'))
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Mansory Image Size", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'mansory_image_size',
					'value' 		=> '1:1, 2:2, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 2:2, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 1:1, 2:2',
					'dependency'  	=> array('element' => "mansory_layout", 'value' => array('1'))
				),

				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Show Category Thumbnail", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "show_category_thumb",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",
					'dependency'  	=> array('element' => "is_slider", 'value' => array('0'))
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
					'heading' 		=> esc_html__("Number Rows", 'wd_package'),
					'description' 	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'per_slide',
					'value' 		=> '1',
					'edit_field_class' => 'vc_col-sm-4',
					'dependency'  	=> array('element' => "is_slider", 'value' => array('1'))
				),

				array(
					'type'        => 'textfield',
					'heading'     => __( "Extra class name", 'wd_package' ),
					'description' => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package' ),
					'admin_label' => true,
					'param_name'  => 'class',
					'value'       => '',
				),
			)
		);
	}
}