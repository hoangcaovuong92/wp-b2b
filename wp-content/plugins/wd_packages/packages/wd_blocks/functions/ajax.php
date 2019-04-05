<?php
//Ajax loadmore product. 
//Template: wd_product_best_selling.php / wd_product_grid_list.php / wd_product_special.php 
add_action( 'wp_ajax_load_more_product', 'wd_load_more_product_function' );
add_action( 'wp_ajax_nopriv_load_more_product', 'wd_load_more_product_function' );
if(!function_exists ('wd_load_more_product_function')){
	function wd_load_more_product_function() {
		$layout_data = isset($_REQUEST['layout_data']) ? $_REQUEST['layout_data'] : '';
		$post__not_in = isset($_REQUEST['post__not_in']) ? $_REQUEST['post__not_in'] : '';
		$query_data	= isset($_REQUEST['query_data']) ? $_REQUEST['query_data'] : '';

		$result = array(
			'post_count' => 0,
			'posts_per_page' => 0,
			'hide_button' => false,
			'query_data' => $query_data,
			'post__not_in' => $post__not_in,
			'data' => '',
		);

		ob_start();
		if($query_data){
			$query_data['post__not_in'] = $post__not_in;
			$products = new WP_Query( $query_data );
			if( $products->have_posts() ) {
				while( $products->have_posts() ) {
					$products->the_post(); global $post;
					wc_get_template_part( 'content', 'product' ); 
					$post__not_in[] = $post->ID;
				}
			}
			
			$result['post_count'] = (int)$products->post_count;
			$result['posts_per_page'] = (int)$query_data['posts_per_page'];
			$result['hide_button'] = $result['post_count'] < $result['posts_per_page'] ? true : false;
			$result['post__not_in'] = json_encode($post__not_in);
			$result['query_data'] = $query_data;
		}
		wp_reset_postdata();
		$result['data'] = ob_get_clean();

		wp_send_json_success($result);
		die();	
	}
}

//Ajax loadmore blog. Template: wd_blog_grid_list.php
add_action( 'wp_ajax_load_more_blog', 'wd_load_more_blog_function' );
add_action( 'wp_ajax_nopriv_load_more_blog', 'wd_load_more_blog_function' );
if(!function_exists ('wd_load_more_blog_function')){
	function wd_load_more_blog_function() {
		$layout_data = isset($_REQUEST['layout_data']) ? $_REQUEST['layout_data'] : '';
		$post__not_in = isset($_REQUEST['post__not_in']) ? $_REQUEST['post__not_in'] : '';
		$query_data	= isset($_REQUEST['query_data']) ? $_REQUEST['query_data'] : '';

		$result = array(
			'post_count' => 0,
			'posts_per_page' => 0,
			'hide_button' => false,
			'query_data' => $query_data,
			'post__not_in' => $post__not_in,
			'data' => '',
		);

		ob_start();
		if($query_data){
			/**
			 * package: layout_data
			 * var: image_size
			 * var: item_class
			 */
			extract($layout_data);
			$query_data['post__not_in'] = $post__not_in;
			$query_data['ignore_sticky_posts'] = true;
			$special_blogs = new WP_Query( $query_data );
			if( $special_blogs->have_posts() ) {
				while( $special_blogs->have_posts() ) {
					$special_blogs->the_post(); global $post;
					echo apply_filters('wd_filter_blog_content', array('thumbnail_size' => $image_size, 'custom_class' => $item_class )); 
					$post__not_in[] = $post->ID;
				}
			}
			
			$result['post_count'] = (int)$special_blogs->post_count;
			$result['posts_per_page'] = (int)$query_data['posts_per_page'];
			$result['hide_button'] = $result['post_count'] < $result['posts_per_page'] ? true : false;
			$result['post__not_in'] = json_encode($post__not_in);
			$result['query_data'] = $query_data;
		}
		wp_reset_postdata();
		$result['data'] = ob_get_clean();

		wp_send_json_success($result);
	    die();	
	}
}

//Ajax load product tab. Template: wd_product_by_category_tabs.php
add_action( 'wp_ajax_nopriv_product_by_category_tabs', 'wd_product_by_category_tabs' );
add_action( 'wp_ajax_product_by_category_tabs', 'wd_product_by_category_tabs' );
if ( ! function_exists( 'wd_product_by_category_tabs' ) ) {
	function wd_product_by_category_tabs() {
		$type    				= $_POST['type'];
		$slug    				= $_POST['slug'];
		$id      				= $_POST['id'];
		$sort   				= $_POST['sort'];
		$order_by 				= $_POST['order_by'];
		$columns 				= $_POST['columns'];
		$columns_tablet 		= $_POST['columns_tablet'];
		$columns_mobile 		= $_POST['columns_mobile'];
		$posts_per_page 		= $_POST['posts_per_page'];
		$is_slider 				= $_POST['is_slider'];
		$mansory_layout 		= $_POST['mansory_layout'];
		$mansory_image_size 	= $_POST['mansory_image_size'];
		$show_category_thumb 	= $_POST['show_category_thumb'];
		$show_nav 				= $_POST['show_nav'];
		$auto_play 				= $_POST['auto_play'];
		$per_slide 				= $_POST['per_slide'];

		$columns_product 		= ($is_slider == '0') ? 'wd-columns-'.$columns.' wd-tablet-columns-'.$columns_tablet.' wd-mobile-columns-'.$columns_mobile : '';
		$query_args = array(
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

		if ( $type == 'event' ) {
			switch ( $slug ) {
				case 'featured':
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'id',
						'terms'    => 'featured',
						'operator' => 'IN',
					);
					break;
				case 'top_reviewed':
					$query_args['meta_key'] = '_wc_average_rating';
					$query_args['orderby']  = 'meta_value_num';
					break;
			}
		}

		$query_args['tax_query'][] = array(
			'terms'    => $id,
			'taxonomy' => 'product_cat',
		);

		$products = new WP_Query( $query_args );

		ob_start();

		if ( $products->have_posts() ): ?>
			<?php 
			$num_post =  $products->post_count;
			if( $num_post < 2 || $num_post <= ($per_slide * $columns) ){
				$is_slider = 0;
			}
			if( $num_post <= $posts_per_page ){
				$posts_per_page = $num_post;
			} 

			$mansory_image_size = $mansory_image_size != '' ? explode(',', $mansory_image_size) : '';
			$masonry_enable 	= $is_slider == 0 && $mansory_layout == '1' && $mansory_image_size != '' ? true : false;
			$random_id 			= 'wd-product-by-category-tab-'.mt_rand();
			$class_masonry_wrap = ($masonry_enable) ? 'wd-product-mansonry-wrap' : '';
			$class_masonry_item = ($masonry_enable) ? 'wd-product-mansonry-item' : '';
			?>
			<div id="<?php echo esc_attr( $random_id ); ?>" class="<?php echo esc_attr( $class_masonry_wrap ); ?>">
				<div class="wd-products-wrapper <?php echo esc_html($columns_product); ?> wd-products-by-category-tabs-products">

					<?php if ($is_slider == '1') : ?>
						<div class="products grid">
					<?php else: ?>
						<ul class="products grid">
					<?php endif ?>
 
						<?php if ($is_slider == 0 && $show_category_thumb): ?>
							<?php echo '<li class="category-image">' . wp_get_attachment_image( get_term_meta( $id, 'thumbnail_id', true ), 'full' ) . '</li>'; //category thumbnail ?> 
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

						<?php if ($masonry_enable): ?>
							<script type="text/javascript">jQuery(document).ready(function($){wd_masonry_product('#<?php echo esc_html($random_id); ?>');})</script>
						<?php endif ?>

					<?php if ($is_slider == '1') : ?>
						</div>
					<?php else: ?>
						</ul>
					<?php endif ?>

				</div>
				
				<?php if( $show_nav && $is_slider ){ ?>
					<div class="slider_control">
						<a href="#" class="prev">&lt;</a>
						<a href="#" class="next">&gt;</a>
					</div>
				<?php } ?>
				
				<?php if ( $is_slider == '1') : ?>
					<script type="text/javascript">
						jQuery(document).ready(function(){
							"use strict";						
							var $_this = jQuery('#<?php echo esc_attr( $random_id ); ?>');
							var _auto_play = <?php echo esc_attr( $auto_play ); ?>;
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
	                                    items : <?php echo $columns_mobile; ?>
	                                },
	                                426:{
	                                    items : <?php echo $columns_tablet; ?>
	                                },
	                                768:{
	                                    items : <?php echo $columns ?>
	                                }
	                            },
								onInitialized: function(){
								}
							});
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
			<?php
			wp_reset_postdata();
		endif;

		echo ob_get_clean();

		wp_die();
	}
}