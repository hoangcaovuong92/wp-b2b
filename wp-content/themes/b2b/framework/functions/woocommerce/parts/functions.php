<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 
if (!class_exists('WD_Woocommerce_Functions')) {
	class WD_Woocommerce_Functions {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		// Ensure construct function is called only once
		private static $called = false;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;

			add_action('after_setup_theme', array($this, 'hooks'), 250);
			add_action('after_setup_theme', array($this, 'archive_posts_per_page'), 200);
		}

		public function hooks(){
			/**
			 * package: woo_hook
			 * var: display_buttons
			 * var: wishlist_default
			 * var: compare_default
			 * var: show_recently_product
			 * var: show_upsell_product
			 * var: show_title
			 * var: show_description
			 * var: show_rating
			 * var: show_price
			 * var: show_price_decimal
			 * var: show_meta
			 * var: product_summary_layout 
			 * var: hover_thumbnail
			 */
			extract(apply_filters('wd_filter_get_data_package', 'woo_hook'));

			// Remove "first", "last" class on product loop / avoid clear both row error
			add_filter('post_class', array($this, 'remove_product_loop_class'), 21);
			// Disable Ajax Call from WooCommerce on front page and posts
			add_action('wp_enqueue_scripts', array($this, 'dequeue_woocommerce_cart_fragments'), 11);
			//remove image srcset
			add_filter('wp_calculate_image_srcset_meta', '__return_null');

			//**************************************************************//
			/*					CUSTOM CHECKOUT FORM FIELS					*/
			//**************************************************************//
			// WooCommerce Checkout Fields Hook
			add_filter('woocommerce_checkout_fields', array($this, 'custom_wc_checkout_fields'));
			// WooCommerce Billing Fields Hook
			add_filter('woocommerce_billing_fields', array($this, 'custom_wc_billing_fields'));
			// WooCommerce Shipping Fields Hook
			add_filter('woocommerce_shipping_fields', array($this, 'custom_wc_shipping_fields'));

			//**************************************************************//
			/*					CUSTOM CART REDIRECT LINK					*/
			//**************************************************************//
			//Set a custom add to cart URL to redirect to
			add_filter('woocommerce_add_to_cart_redirect', array($this, 'custom_add_to_cart_redirect'));
	
			// Trim zeros in price decimals
			if (!$show_price_decimal) {
				add_filter('woocommerce_price_trim_zeros', '__return_true');
			}
			
			//**************************************************************//
			/*					WD FILTER/ACTION							*/
			//**************************************************************//
			
			//PRODUCT LOOP
			//echo apply_filters('wd_filter_product_image_html', $image_size); //Display product thumbnail
			add_filter('wd_filter_product_image_html', array($this, 'get_product_image_html'));

			//Add Class to product thumbnail (change image when hover)
			if ($hover_thumbnail) {
				add_filter('post_class', array($this, 'product_loop_second_thumbnail_class'));
				//echo apply_filters('wd_filter_product_secondary_thumbnail', $image_size); //Display product secondary thumbnail
				add_filter('wd_filter_product_secondary_thumbnail', array($this, 'get_product_secondary_thumbnail'));
			}
	
			//add link open/link close
			/* do_action('wd_hook_shop_loop_link_open'); */
			add_action('wd_hook_shop_loop_link_open', 'woocommerce_template_loop_product_link_open', 5); 
			/* do_action('wd_hook_shop_loop_link_close'); */
			add_action('wd_hook_shop_loop_link_close', 'woocommerce_template_loop_product_link_close', 5); 
			
			/* do_action('wd_hook_after_shop_loop_price'); */
			add_action('wd_hook_after_shop_loop_price', 'woocommerce_template_loop_rating', 10);

			//add product attribute color (another hook: woocommerce_before_shop_loop_item_title)
			/* do_action('wd_hook_after_shop_loop_price'); */
			add_action('wd_hook_after_shop_loop_price', array($this, 'product_loop_attribute_color'), 15); 

			//Sale Date Countdown
			/* do_action('wd_hook_after_shop_loop_price'); */
			//add_action('wd_hook_after_shop_loop_price',array($this, 'product_countdown_offer'), 20); //Sale schedule countdown

			/* do_action('wd_hook_sale_featured_flash'); */
			add_action('wd_hook_sale_featured_flash', 'woocommerce_show_product_loop_sale_flash', 5);
			/* do_action('wd_hook_product_flash'); */
			add_action('wd_hook_product_flash', array($this, 'product_flash_sale'), 5); //add feature flash after sale flash
			add_action('wd_hook_product_flash', array($this, 'product_flash_featured'), 10); //add feature flash after sale flash

			// SINGLE PRODUCT
			//echo apply_filters('wd_filter_single_product_variable_options', $attributes); //Display single product variable options
			add_filter('wd_filter_single_product_variable_options', array($this, 'get_single_product_variable_options'));

			/* do_action('wd_hook_single_product_image_html'); */
			add_action('wd_hook_single_product_image_html', array($this, 'get_single_product_image_html'), 5); //Display single product image 

			/* do_action('wd_hook_single_product_thumbnails_html'); */
			add_action('wd_hook_single_product_thumbnails_html', array($this, 'get_single_product_thumbnails_html'), 5); //Display single product thumbnails 

			/* do_action('wd_hook_single_product_after_image'); */
			add_action('wd_hook_single_product_after_image', 'woocommerce_show_product_sale_flash', 5); //add sale & feature flash
	
			/* do_action('wd_hook_product_share'); */
			add_action('wd_hook_product_share', array($this, 'product_share_button'), 5); //Share 

			/* do_action('wd_hook_after_single_product_price'); */
			add_action('wd_hook_after_single_product_price', array($this, 'product_countdown_offer'), 5); //Sale schedule countdown


			//**************************************************************//
			/*					WOOCOMMERCE BREADCRUMB STRUCTURE			*/
			//**************************************************************//
			//Change Woocommerce Breadcrumb Structure
			add_filter('woocommerce_breadcrumb_defaults', array($this, 'woocommerce_breadcrumbs_structure'));
			//Remove woocommerce breadcrumb
			remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
			
			//**************************************************************//
			/*							SHOP LOOP				  			*/
			//**************************************************************//
			//remove link close default on shop loop
			remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10); 
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
	
			//remove add to cart button, rating, price default
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10); 
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);	
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	
			//remove default title
			remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
	
			//add add to cart button, rating, price with new position
			add_action('woocommerce_shop_loop_item_title', array($this, 'product_loop_title'), 10); 
			add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 5);
	
			//add desicription product
			add_action('woocommerce_after_shop_loop_item', array($this, 'product_loop_short_description'), 15);
			//add Sale date countdown
			//add_action('woocommerce_before_shop_loop_item_title', array($this, 'product_countdown_offer'), 20); //Sale schedule countdown
	
			if(!$show_title){
				remove_action('woocommerce_shop_loop_item_title', array($this, 'product_loop_title'), 10);
			}
			if(!$show_description){
				//remove_action('woocommerce_after_shop_loop_item', array($this, 'product_loop_short_description'), 15);
			}
			if(!$show_rating){
				remove_action('wd_hook_after_shop_loop_price', 'woocommerce_template_loop_rating', 10);
			}
			if(!$show_price){
				remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 5);
				remove_action('wd_hook_after_shop_loop_price', array($this, 'product_loop_attribute_color'), 15); //remove attribute color
			}
			if(!$show_meta){
				//remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash',10);
			}
	
			//**************************************************************//
			/*							SHOP / ARCHIVE			  			*/
			//**************************************************************//
			add_action('woocommerce_archive_description', array($this, 'product_category_image'), 2);
	
			//**************************************************************//
			/*							SINGLE PRODUCT				  		*/
			//**************************************************************//
			/* Hook: woocommerce_before_single_product_summary */
			remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10); //remove sale flash default
	
			/* Hook: woocommerce_single_product_summary */
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10); 
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
	
			//Reorder the product summary layout
			$i = 5;
			$product_summary_default = array(
				'single_product_summary_price'          => __( 'Price', 'feellio' ),
				'single_product_summary_review'        	=> __( 'Review', 'feellio' ),
				'single_product_summary_sku'           	=> __( 'Sku', 'feellio' ),
				'single_product_summary_availability'  	=> __( 'Availability', 'feellio' ),
				'single_product_summary_excerpt'		=> __( 'Excerpt', 'feellio' ),
				'single_product_summary_add_to_cart'    => __( 'Add To Cart', 'feellio' ),
				'single_product_summary_categories'     => __( 'Categories', 'feellio' ),
			);
			$product_summary_layout = is_array($product_summary_layout) ? $product_summary_layout : $product_summary_default;
			if ($product_summary_layout) {
				foreach ($product_summary_layout as $action_function => $value) {
					if ($value) {
						add_action('woocommerce_single_product_summary', array($this, $action_function), $i);
						$i += 5;
					}
				}
			}
	
			//Single product advance tabs
			add_filter('woocommerce_product_tabs', array($this, 'single_product_advance_tabs') );
			
	
			/* Hook: woocommerce_after_single_product_summary */
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 ); //remove upsell default
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20); //remove related default
			
	
			if ($show_recently_product) { //Show/hide recent product
				add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
			}
			if ($show_upsell_product) { //Show/hide upsell product
				add_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 25);
			}

			//**************************************************************//
			/*						PRODUCT PRICE FILTER					*/
			//**************************************************************//
			if ($this->get_price_multiplier() > 0 && $this->get_price_multiplier() !== 1) {
				// Simple, grouped and external products
				add_filter('woocommerce_product_get_price', array($this, 'custom_price'), 99, 2 );
				add_filter('woocommerce_product_get_regular_price', array($this, 'custom_price'), 99, 2 );
				// Variations
				add_filter('woocommerce_product_variation_get_regular_price', array($this, 'custom_price'), 99, 2 );
				add_filter('woocommerce_product_variation_get_price', array($this, 'custom_price'), 99, 2 );

				// Variable (price range)
				add_filter('woocommerce_variation_prices_price', array($this, 'custom_variable_price'), 99, 3 );
				add_filter('woocommerce_variation_prices_regular_price', array($this, 'custom_variable_price'), 99, 3 );
				// Handling price caching (see explanations at the end)
				add_filter('woocommerce_get_variation_prices_hash', array($this, 'add_price_multiplier_to_variation_prices_hash'), 99, 1);
			}
	
			//**************************************************************//
			/*								BUTTON				 	 		*/
			//**************************************************************//
			//Gris/List Toggle
			//remove default hook
			remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
			remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
			/* do_action('wd_hook_product_archive_toggle_button'); */
			add_action('wd_hook_product_archive_toggle_button', array($this, 'woocommerce_result_count'), 5);
			add_action('wd_hook_product_archive_toggle_button', array($this, 'woocommerce_catalog_ordering'), 10);
			add_action('wd_hook_product_archive_toggle_button', array($this, 'product_archive_toggle_button'), 15);

			//Add to cart button
			/* do_action('wd_hook_shop_loop_button'); */
			add_action('wd_hook_shop_loop_button', array($this, 'add_to_cart_button'), 5);

			//Quick Shop Button
			add_action('wd_hook_shop_loop_button', array($this, 'quickshop_button'), 10);

			//Compare Button
			add_action('wd_hook_shop_loop_button', array($this, 'compare_button'), 15);
			add_action('woocommerce_after_add_to_cart_button', array($this, 'compare_button'), 30); //single product

			if (!$compare_default) { //Remove compare button default
				update_option('yith_woocompare_compare_button_in_product_page', 'no'); 
			}

			//Wishlist Button
			add_action('wd_hook_shop_loop_button', array($this, 'wishlist_button'), 20);
			add_action('woocommerce_after_add_to_cart_button' , array($this, 'wishlist_button'), 50);

			if (!$wishlist_default) { //Remove wishlist button default
				update_option( 'yith_wcwl_button_position', 'shortcode' );
			}

			//Display Product buttons (Add to cart/wishlist/compare)
			if(!$display_buttons){
				remove_action('wd_hook_shop_loop_button', 'woocommerce_template_loop_add_to_cart', 10);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 60);
			}

			//**************************************************************//
			/*							PRODUCT EFFECT						*/
			//**************************************************************//
			add_action('wd_hook_footer_init_action', array($this, 'product_effect'), 25) ;
		}

		//**************************************************************//
        /*						 	FUNCTIONS							*/
		//**************************************************************//

		//**************************************************************//
        /*					 SINGLE PRODUCT CONTENT						*/
		//**************************************************************//
		//Get product attachment IDs array
		public function get_product_attachment_ids( $product, $include_post_thumbnail = true ) {
			if (!is_object($product)) return;
			$attachment_ids = $product->get_gallery_image_ids();
			$attachment_ids = ( is_array($attachment_ids) ) ? $attachment_ids : array();
			if (has_post_thumbnail() && $include_post_thumbnail) {
				array_unshift($attachment_ids, get_post_thumbnail_id());
			}
			return $attachment_ids;
		}

		//Display single product image HTML
		public function get_single_product_image_html() {
			global $woocommerce, $product, $post;
			$product_id 		= $product->get_id();
			$attachment_ids 	= $this->get_product_attachment_ids( $product );
			$product_image_class = $attachment_ids ? 'wd-single-product-with-thumbnail' : 'wd-single-product-without-thumbnail'; ?>
			<div id="wd-single-product-image" class="<?php echo esc_attr($product_image_class); ?>">
				<?php 
				/**
				 * wd_hook_single_product_before_image hook
				 */
				do_action('wd_hook_single_product_before_image'); ?>
				<?php
					if ( $attachment_ids ) {
						$img_attachment_id 	= has_post_thumbnail() ? get_post_thumbnail_id() : $attachment_ids[0];
						$image_title 		= esc_attr( get_the_title( $img_attachment_id ) );
						$image_link  		= wp_get_attachment_url( $img_attachment_id );

						if (has_post_thumbnail()) {
							$image = get_the_post_thumbnail($product_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), 
															array( 'title' 	=> $image_title, 'itemprop'	=> "image", ));
						}else{
							$image = wp_get_attachment_image($img_attachment_id, 'shop_single', false, array( 'title' 	=> $image_title, 'itemprop'	=> "image", ));
						}

						$product_image_html_desktop = sprintf( '<a href="%s" itemprop="image" class="wd-single-product-image-link cloud-zoom zoom" title="%s"  id=\'zoom1\' rel="position:\'right\',showTitle:1,titleOpacity:0.5,lensOpacity:0.5,fixWidth:362,fixThumbWidth:72,fixThumbHeight:72, adjustY:-4">%s</a>', $image_link, $image_title, $image );

						$product_image_html_mobile = sprintf( '<a data-fancybox-group="wd-single-product-thumbnails" href="%s" itemprop="image" class="wd-single-product-image-link wd-fancybox-image-gallery" title="%s">%s</a>', $image_link, $image_title, $image ); ?>

						<div class="wd-desktop-screen">
							<?php echo apply_filters('woocommerce_single_product_image_html', $product_image_html_desktop ,$product_id); ?>
						</div>

						<div class="wd-mobile-screen">
							<?php echo apply_filters('woocommerce_single_product_image_html', $product_image_html_mobile ,$product_id); ?>
						</div>
					<?php } else {
						echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $product_id );
					}
				?>
				<?php 
				/**
				 * wd_hook_single_product_after_image hook
				 *
				 * @hooked woocommerce_show_product_sale_flash - 5
				 */
				do_action('wd_hook_single_product_after_image'); ?>
			</div>
			<?php
		}

		//Display single product thumbnails HTML
		public function get_single_product_thumbnails_html() { 
			global $product; 
			/**
			 * package: single-product-thumbnail
			 * var: thumbnail_number 
			 * var: position_additional
			 */
			extract(apply_filters('wd_filter_get_data_package', 'single-product-thumbnail' ));
			$product_id 	= $product->get_id();
			$attachment_ids = $this->get_product_attachment_ids( $product ); 
			if ($attachment_ids): 
				$vertical 	= ($position_additional == "left") ? true : false;
				$thumbnail_enough_class = count($attachment_ids) > $thumbnail_number ? 'wd-product-thumbnails-enough' : 'wd-product-thumbnails-fail';
				$slider_options_mobile = json_encode(array(
					'slider_type' => 'slick',
					'column_desktop' => 3,
					'column_tablet' => 3,
					'column_mobile' => 3,
				));
				$slider_options_desktop = json_encode(array(
					'slider_type' => 'slick',
					'column_desktop' => $thumbnail_number,
					'column_tablet' => $thumbnail_number,
					'column_mobile' => $thumbnail_number,
					'vertical'	=> $vertical,
				)); ?> 
				<div class="wd-single-product-thumbnails-wrap">
					<ul class="wd-slider-wrap wd-slider-wrap--product-thumbnails wd-mobile-screen <?php echo esc_attr($thumbnail_enough_class); ?>"
						data-slider-options='<?php echo $slider_options_mobile; ?>'>
						<?php
							$loop = 0;
							foreach ( $attachment_ids as $attachment_id ) {
								$classes = array(  );
								$image_link = wp_get_attachment_url( $attachment_id );

								if ( !$image_link || $attachment_id === get_post_thumbnail_id()) continue;	
									
								$image_link_class = esc_attr( implode( ' ', $classes ) );
								$image_link_class .= ' wd-single-product-thumbnail-link wd-fancybox-image-gallery';
								$image_link_class .= ( $loop === 0 ) ? ' first active' : '';

								$image_title = esc_attr( $product->get_title() );
								$_thumb_size =  apply_filters( 'single_product_large_thumbnail_size', 'shop_single' );
								$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ),array( 'alt' => $image_title, 'title' => $image_title ) );
								$image_src   = wp_get_attachment_image_src( $attachment_id, $_thumb_size );
						
								$product_image_html = sprintf( '<li><a data-fancybox-group="wd-single-product-thumbnails" href="%1$s" class="%2$s" title="%3$s">%4$s</a></li>', $image_link, $image_link_class, $image_title, $image ); 
								
								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $product_image_html, $attachment_id, $product_id , $image_link_class ); 
								$loop++;
							}
						?>
					</ul>
					<ul class="wd-slider-wrap wd-slider-wrap--product-thumbnails wd-desktop-screen <?php echo esc_attr($thumbnail_enough_class); ?>"
						data-slider-options='<?php echo $slider_options_desktop; ?>'>
						<?php
							$loop = 0;
							foreach ( $attachment_ids as $attachment_id ) {
								$classes = array(  );
								$image_link = wp_get_attachment_url( $attachment_id );

								if ( ! $image_link ) 
									continue;	
									
								$image_link_class = esc_attr( implode( ' ', $classes ) );
								$image_link_class .= ' wd-single-product-thumbnail-link';
								$image_link_class .= ( $loop === 0 ) ? ' first active' : '';
								$image_link_class .= ' pop_cloud_zoom cloud-zoom-gallery';


								$image_title = esc_attr( $product->get_title() );
								$_thumb_size =  apply_filters( 'single_product_large_thumbnail_size', 'shop_single' );
								$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ),array( 'alt' => $image_title, 'title' => $image_title ) );
								$image_src   = wp_get_attachment_image_src( $attachment_id, $_thumb_size );

								$product_image_html = sprintf( '<li><a href="%1$s" class="%2$s" title="%3$s"  rel="useZoom: \'zoom1\', smallImage: \'%4$s\'">%5$s</a></li>', $image_link, $image_link_class, $image_title, $image_src[0], $image );

								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $product_image_html, $attachment_id, $product_id , $image_link_class ); 
								$loop++;
							}
						?>
					</ul>
				</div>
			<?php endif ?>
			<?php
		}

		//Single Product variable options
		public function get_single_product_variable_options_item($name, $options){ 
			$orderby = wc_attribute_orderby( $name );
			switch ( $orderby ) {
				case 'name' :
					$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
					break;
				case 'id' :
					$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false, 'hide_empty' => false );
					break;
				case 'menu_order' :
					$args = array( 'menu_order' => 'ASC', 'hide_empty' => false );
					break;
			}
			$args['taxonomy'] = $name;
			$terms = get_terms($args);
			$select_opt = '';
			$select_opt .= '<div class="wd-color-image-swap">';
			foreach ( $terms as $term ) {
				
				if ( ! in_array( $term->slug, $options ) )
					continue;
				$datas = get_term_meta($term->term_id, "wd_pc_color_config", true );
				if( strlen($datas) > 0 ){
					$datas = unserialize($datas);	
				}else{
					$datas = array(
						'wd_pc_color_color' 	=> "#fff",
						'wd_pc_color_image' 	=> 0
					);
				}
				
				$attr_color 	= ($name == 'pa_color') ? $datas['wd_pc_color_color'] : '#fff';
				$select_style 	= 'min-width: 30px; height:30px; text-align:center; background-color: ' . $attr_color;
				$select_opt 	.= '<div style="'. $select_style .'" class="wd-select-option" data-value="'.esc_attr($term->slug).'" data-attr_name="'.esc_attr($name).'" >';

				if( absint($datas['wd_pc_color_image']) > 0 ){
					$_img = wp_get_attachment_image_src( absint($datas['wd_pc_color_image']), 'wd_small_thumbnail', true ); 
					$_img = $_img[0];
					$select_opt .= '<img alt="'.$attr_color.'" src="'.esc_url( $_img ).'" class="wd_pc_preview_image" />';
					
				} else {
					if ($name == 'pa_color') {
						$select_opt .= '<a href="#" style="'.$select_style.'"></a>';
					}else{
						$select_opt .= '<a href="#" style="'.$select_style.'">'.esc_attr($term->name).'</a>';
					}
					
				}
				$select_opt 	.= "</div>";
				
			}
			$select_opt .= "</div>";
			
			return $select_opt;
		}

		//use on template woocommerce/single-product/add-to-cart/variable.php
		public function get_single_product_variable_options($attributes = array()) { 
			if (is_array($attributes) && count($attributes) > 0) {
				global $product;
				$product_id = $product->get_id();
				$loop = 0; 
				foreach ( $attributes as $name => $options ) : 
					$loop++; ?>
					<tr>
						<td class="label"><label class="bold-upper" for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?> <abbr class="required" title="required">*</abbr></label></td>
						<td class="value">
							<?php 
							$hide_select = "";
							if(wc_sanitize_taxonomy_name( 'color' ) && class_exists('WD_Shopbycolor')){
								if ( is_array( $options ) ) {
									if ( taxonomy_exists( $name ) ) {
										echo $this->get_single_product_variable_options_item($name, $options);
									}
								}
								$hide_select = "style=\"display:none;\"";
							} ?>
							
							<select <?php echo wp_kses_post($hide_select);?> id="<?php echo esc_attr( sanitize_title($name) ); ?>" name="attribute_<?php echo sanitize_title($name); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>">
								<option value=""><?php echo esc_html__( 'Choose an option', 'feellio' ) ?>&hellip;</option>
								<?php
									if ( is_array( $options ) ) {

										if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
											$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
										} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
											$selected_value = $selected_attributes[ sanitize_title( $name ) ];
										} else {
											$selected_value = '';
										}

										// Get terms if this is a taxonomy - ordered
										if ( taxonomy_exists( $name ) ) {
											
											$terms = wc_get_product_terms( $product_id , $name, array( 'fields' => 'all' ) );

											foreach ( $terms as $term ) {
												if ( ! in_array( $term->slug, $options ) ) {
													continue;
												}
												echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
											}
											
										} else {

											foreach ( $options as $option ) {
												echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
											}

										}
									}
								?>
							</select> 
						
							<?php 
							if ( sizeof( $attributes ) === $loop ) {
								echo '<p class="wd_reset_variations"><a class="reset_variations" href="#reset">' . esc_html__( 'Clear selection', 'feellio' ) . '</a></p>';
							} ?>
						</td>
					</tr>
				<?php endforeach;
			}
		}
		
		//Advance tabs
		public function facebook_comment_form_callback(){ ?>
			<div class="wd-comment-form-wrap">
				<?php
				/**
				* package: comment
				* var: layout_style
				* var: comment_status
				* var: comment_mode
				* var: num_comment
				*/
				extract(apply_filters('wd_filter_get_data_package', 'comment'));
				$content = '';
				ob_start(); 
				?>
				<div class="wd-facebook-comment-form">
					<?php if ($comment_mode): ?>
						<div class="fb-comments" xid="<?php the_ID(); ?>" data-numposts="<?php echo esc_attr($num_comment); ?>" data-colorscheme="light" data-width="100%" data-version="v2.3"></div>
					<?php else: ?>
						<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-numposts="<?php echo esc_attr($num_comment); ?>" width="100%" data-colorscheme="light" data-width="100%" data-version="v2.3"></div>
					<?php endif ?>
				</div>
			</div>
			<?php
		}

		public function single_product_tags_tab_callback(){
			echo '<div class="wd_product_tags">';
				global $product, $post;
				$_terms = wp_get_post_terms( $product->get_id(), 'product_tag');
				$tags_label = esc_html__("", 'feellio');
				echo '<div class="tagcloud">';
				
				$_include_tags = '';
				if( count($_terms) > 0 ){
					echo '<span class="tag_heading">'.$tags_label.'</span>';
					foreach( $_terms as $index => $_term ){
						$_include_tags .= ( $index == 0 ? "{$_term->term_id}" : ",{$_term->term_id}" ) ;
					}
					wp_tag_cloud( array('taxonomy' => 'product_tag', 'include' => $_include_tags, 'separator'=>'' ) );
				}
				
				echo "</div>\n";
			echo '</div>';
		}
		
		public function single_product_advance_tabs( $tabs ) {
			global $product, $wd_theme_options;
			$display 	 = $wd_theme_options['wd_comment_facebook_display_on_single_product']; 
			//Facebook comment tab
			if ($display){
				$tabs['wd_facebook_comment_tab'] = array(
					'title' 	=> __( 'Comments', 'feellio' ),
					'priority' 	=> 60,
					'callback' 	=> array($this, 'facebook_comment_form_callback')
				);
			}
			//Tag tab
			if (count($product->get_tag_ids())){
				$tabs['wd_tag_tab'] = array(
					'title' 	=> __( 'Product Tags', 'feellio' ),
					'priority' 	=> 30,
					'callback' 	=> array($this, 'single_product_tags_tab_callback')
				);
			}
			return $tabs;
		}

		//**************************************************************//
        /*			 	SINGLE PRODUCT SUMMARY PARTS					*/
		//**************************************************************//
		public function single_product_summary_price(){
			if( function_exists('woocommerce_template_single_price') ){
				woocommerce_template_single_price();
			}
		}

		public function single_product_summary_excerpt(){
			if( function_exists('woocommerce_template_single_excerpt') ){
				woocommerce_template_single_excerpt();
			}
		}

		public function single_product_summary_add_to_cart(){
			if( function_exists('woocommerce_template_single_add_to_cart') ){
				woocommerce_template_single_add_to_cart();
			}	
		}

		public function single_product_summary_review(){
			global $product;
			if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' ) return;	
			
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();

			if ( $rating_count > 0 ) : ?>

				<div class="woocommerce-product-rating">
					<?php echo wc_get_rating_html( $average, $rating_count ); ?>
					<?php if ( comments_open() ) : ?><a href="#reviews" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'feellio' ), esc_html( $review_count ) ); ?></a><?php endif ?>
				</div>

			<?php endif; ?>
			<?php
		}
	
		//Get availability product
		public function get_product_availability($product) {
			if ( !wd_is_woocommerce() ) {
				return;
			}	
			$availability = $class = "";
	
			if ( $product->managing_stock() ) {
				if ( $product->is_in_stock() ) {
	
					if ( $product->get_stock_quantity() > 0 ) {
	
						$format_option = get_option( 'woocommerce_stock_format' );
	
						switch ( $format_option ) {
							case 'no_amount' :
								$format = esc_html__( 'In stock', 'feellio' );
							break;
							case 'low_amount' :
								$low_amount = get_option( 'woocommerce_notify_low_stock_amount' );
	
								$format = ( $product->get_stock_quantity() <= $low_amount ) ? esc_html__( 'Only %s left in stock', 'feellio' ) : esc_html__( 'In stock', 'feellio' );
							break;
							default :
								$format = esc_html__( '%s in stock', 'feellio' );
							break;
						}
	
						$availability = sprintf( $format, $product->get_stock_quantity() );
						$class = 'in-stock';
	
						if ( $product->backorders_allowed() && $product->backorders_require_notification() )
							$availability .= ' ' . esc_html__( '(backorders allowed)', 'feellio' );
	
					} else {
	
						if ( $product->backorders_allowed() ) {
							if ( $product->backorders_require_notification() ) {
								$availability = esc_html__( 'Available on backorder', 'feellio' );
								$class        = 'available-on-backorder';
							} else {
								$availability = esc_html__( 'In stock', 'feellio' );
							}
						} else {
							$availability = esc_html__( 'Out of stock', 'feellio' );
							$class        = 'out-of-stock';
						}
	
					}
	
				} elseif ( $product->backorders_allowed() ) {
					$availability = esc_html__( 'Available on backorder', 'feellio' );
					$class        = 'available-on-backorder';
				} else {
					$availability = esc_html__( 'Out of stock', 'feellio' );
					$class        = 'out-of-stock';
				}
			} elseif ( ! $product->is_in_stock() ) {
				$availability = esc_html__( 'Out of stock', 'feellio' );
				$class        = 'out-of-stock';
			} elseif ( $product->is_in_stock() ){
				$availability = esc_html__( 'In stock', 'feellio' );
				$class        = 'in-stock';		
			}
	
			return apply_filters( 'woocommerce_get_availability', array( 'availability' => $availability, 'class' => $class ), $product );
		}
	
		public function single_product_summary_availability(){
			global $product;
			$_product_stock = $this->get_product_availability($product); ?>
			<p class="availability stock <?php echo esc_attr($_product_stock['class']);?>"><?php esc_html_e('Availability', 'feellio');?>: <span><?php echo esc_attr($_product_stock['availability']);?></span></p><?php		
		}
	
		public function single_product_summary_sku(){
			global $product, $post;
			if (trim($product->get_sku())) {
				$sku_label = esc_html__("Sku:", 'feellio');
				echo "<p class='wd_product_sku product_meta'>" . $sku_label . " <span class=\"product_sku sku\" itemprop=\"mpn\">" . esc_attr($product->get_sku()) . "</span></p>";
			}
		}
	
		public function single_product_summary_categories(){
			global $product;
			$rs = '<div class="wd-product-categories"><span>'.esc_html__("Categories: ", 'feellio').'</span>';
			$product_categories = wp_get_post_terms(get_the_ID($product),'product_cat');
			$count = count($product_categories);
			if ( $count > 0 ){
				foreach ( $product_categories as $term ) {
				$rs.= '<a href="'.get_term_link($term->slug,$term->taxonomy).'">'.$term->name . "</a>, ";
	
				}
				$rs = substr($rs,0,-2);
			}
			$rs .= '</div>';
			echo wp_kses_post( $rs );
		}

		//**************************************************************//
        /*						 SHOP LOOP CONTENT						*/
		//**************************************************************//
		//Get product attachment HTML
		public function get_product_image_html( $image_size = 'shop_catalog' ) {
			global $product;
			echo '<div class="wd-thumbnail-product-img">';
			if (woocommerce_get_product_thumbnail( $image_size ) != '' && has_post_thumbnail()) {
				$props = wc_get_product_attachment_props( get_post_thumbnail_id(), $product ); 
				echo get_the_post_thumbnail( $product->get_id(), $image_size, array( 
						'title' => $props['title'],  
						'alt' => $props['alt'], 
						'class' => 'wp-post-image'
					)
				);
			}else{
				echo wc_placeholder_img( $image_size );
			}
			echo apply_filters('wd_filter_product_secondary_thumbnail', $image_size);
			echo '</div>';
		}

		public function product_loop_attribute_color(){
			if(!class_exists('WD_Shopbycolor')) return;
			global $product;
			if ( $product->is_type( 'variable' ) ) {
				$attr_name = 'pa_color';
				$attributes = $product->get_variation_attributes();
				if ($attributes && !is_wp_error( $attributes )) {
					foreach ($attributes as $attr => $options) {
						if ($attr == $attr_name) {
							if ( taxonomy_exists( $attr_name ) ) {
								echo $this->get_single_product_variable_options_item($attr_name, $options);
							}
						}
					}
				}
			}
		}

		function product_loop_title() { 
			/**
			 * package: product-loop-title-word
			 * var: title_word  
			 */
			extract(apply_filters('wd_filter_get_data_package', 'product-loop-title-word' ));
			$title = (!$title_word || $title_word == '-1') ? get_the_title() : wp_trim_words(get_the_title(), $title_word, '...') ;
			?>
			<h2 class="woocommerce-loop-product__title wd-product-shop-loop-title"><?php echo esc_html($title); ?></h2> 
			<?php 
		}

		public function product_loop_short_description() { 
			/**
			 * package: product-description
			 * var: show_description  
			 * var: number_word  
			 */
			extract(apply_filters('wd_filter_get_data_package', 'product-description' )); ?>
			<div itemprop="description" class="wp_description_product <?php echo (!$show_description) ? 'wd_hidden_desc_product' : 'wd_show_desc_product'; ?>">
				 <?php echo apply_filters('wd_filter_excerpt_limit_word_length', array('word_limit' => $number_word)); ?>
			 </div> 
			<?php 
		}

		//**************************************************************//
        /*						 	PRODUCT BUTTON						*/
		//**************************************************************//
		public function add_to_cart_button(){
			echo '<div class="wd-shop-button wd-shop-button--add-to-cart">';
			woocommerce_template_loop_add_to_cart();
			echo '</div>';
		}

		public function quickshop_button(){
			if( class_exists( 'WD_Quickshop' )){
				$qs = new WD_Quickshop();
				echo '<div class="wd-shop-button wd-shop-button--quickshop">';
				$qs->display_quickshop_button();
				echo '</div>';
			}
		}

		public function compare_button(){
			if( class_exists( 'YITH_Woocompare_Frontend' ) && class_exists( 'YITH_Woocompare' ) ) {
				global $yith_woocompare;
				$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
				if( $yith_woocompare->is_frontend() || $is_ajax ) {
					if( $is_ajax ){
						$yith_woocompare->obj = new YITH_Woocompare_Frontend();
					}
					echo '<div class="wd-shop-button wd-shop-button--compare">';
					$yith_woocompare->obj->add_compare_link();
					echo '</div>';
				}
			}
		}
		
		public function wishlist_button(){
			if( shortcode_exists('yith_wcwl_add_to_wishlist') ){
				echo '<div class="wd-shop-button wd-shop-button--wishlist">';
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
				echo '</div>';
			}
		}

		//**************************************************************//
        /*						 	SHARE BUTTON						*/
		//**************************************************************//
		//Share button
		public function product_share_button(){
			/**
			 * package: social_share
			 * var: display_social
			 * var: title_display
			 * var: pubid
			 * var: button_class
			 */
			extract(apply_filters('wd_filter_get_data_package', 'social_share' ));
			if ($display_social) { ?>
				<div class="wd-product-share">
					<div class="wd-social-share">
						<?php if ($title_display) { ?>
							<span><?php esc_html_e('Share ', 'feellio'); ?></span>
						<?php } ?>
						<div class="<?php echo $button_class; ?>"></div>
					</div>
				</div>
			<?php
			}
		}
		
		//**************************************************************//
        /*						 SALE/FEATURED FLASH					*/
		//**************************************************************//
		//Get Price Sale Percent
		public function get_product_price_sale_percent(){
			global $product;
			if ($product->is_on_sale()){ 
				if( $product->get_regular_price() > 0 ){
					return round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
				}
			}
		}
		
		//Get Price Sale Text
		public function product_flash_sale(){ 
			/**
			 * package: product-sale-flash
			 * var: text  
			 * var: show_percent  
			 */
			extract(apply_filters('wd_filter_get_data_package', 'product-sale-flash' ));
			global $post, $product;
			if ( $product->is_on_sale() ){
				if ($show_percent) {
					$percent = $this->get_product_price_sale_percent();
					if ($percent && $percent < 100) {
						$text .= $percent.'%';
					}else{
						$text = __( 'Sale!', 'feellio' );
					}
				}
				if ($text) {
					echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html($text) . '</span>', $post, $product ); 
				}
			}
		}

		public function product_flash_featured(){
			global $product;
			if ( $product->is_featured() ) { ?>
				<span class="featured"><?php esc_html_e('Hot', 'feellio');?></span>
			<?php } 
		}

		//**************************************************************//
        /*						 COUNTDOWN OFFER						*/
		//**************************************************************//
		public function product_countdown_offer() {
			global $product;
			$date_from = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ); //Return timestamp
			$date_to = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ); //Return timestamp
			if (empty($date_to)) return;

			$now_timestamp = time(); //Return current timestamp
			$now_time_string = date("Y-m-d H:i:s", $now_timestamp); //Return times string
			$enable_countdown = ((!empty($date_from) && $date_from <= $now_timestamp && $now_timestamp <= $date_to)
								|| (empty($date_from) && $now_timestamp <= $date_to)) 
								? true : false;
			if ($enable_countdown) {
				$date_to_object = ( $date_to ) ? new DateTime( date_i18n( 'Y-m-d', $date_to ) ) : ''; //Return Object
				$now_time_object  = new DateTime($now_time_string);
				$offer_end = date_diff( $date_to_object, $now_time_object );
				ob_start(); ?>
				<div class="wd-offer-shop-wrap">
					<div class="wd-offer-shop-title"><?php _e( 'Hurry Up! Offer ends in:', 'feellio' ) ?></div>
					<!-- .wd-offer-shop -->
					<div class="wd-offer-shop-date" data-offer="<?php echo date_i18n( 'Y/m/d', $date_to ) ?>">
						<ul class="offer-end list-inline countdown">
							<li class="date <?php echo $offer_end->y == 0 ? 'hidden' : '' ?>">
								<span class="year"><?php echo $offer_end->y ?></span>
								<span><?php _e( 'Year', 'feellio' ) ?></span>
							</li>
							<li class="date <?php echo ( $offer_end->y == 0 && $offer_end->m == 0 ) ? 'hidden' : '' ?>">
								<span class="month"><?php echo $offer_end->m ?></span>
								<span><?php _e( 'Month', 'feellio' ) ?></span>
							</li>
							<li class="date <?php echo ( $offer_end->y == 0 && $offer_end->m == 0 && $offer_end->h == 0 ) ? 'hidden' : '' ?>">
								<span class="day"><?php echo $offer_end->d ?></span>
								<span><?php _e( 'Day', 'feellio' ) ?></span>
							</li>
	
							<li class="date">
								<span class="hour"><?php echo $offer_end->h ?></span>
								<span><?php _e( 'Hours', 'feellio' ) ?></span>
							</li>
							<li class="date">
								<span class="minute"><?php echo $offer_end->m ?></span>
								<span><?php _e( 'Mins', 'feellio' ) ?></span>
							</li>
							<li class="date">
								<span class="second"><?php echo $offer_end->s ?></span>
								<span><?php _e( 'Secs', 'feellio' ) ?></span>
							</li>
						</ul>
					</div><!-- .wd-offer-shop-date -->
				</div><!-- .wd-offer-shop-wrap -->
				<?php
				echo ob_get_clean();
			}
		}

		//**************************************************************//
        /*						 THUMBNAIL HOVER						*/
		//**************************************************************//
		//Get product gallery image ID
		public function get_product_gallery_image_ids( $product ) {
			if ( ! is_a( $product, 'WC_Product' ) ) {
				return;
			}

			$attachment_ids = array();
			if ( is_callable( 'WC_Product::get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids();
			} else {
				$attachment_ids = $product->get_gallery_attachment_ids();
			}
			if (count($attachment_ids) > 0) {
				foreach ($attachment_ids as $key => $id) {
					if (!wp_get_attachment_image($id)) {
						unset($attachment_ids[$key]);
					}
				}
			}
			return $attachment_ids;
		}

		//Add Class to product thumbnail (change image when hover)
		public function product_loop_second_thumbnail_class( $classes ) {
			global $product;
			if ( get_post_type(get_the_ID()) == 'product' ) {
				$attachment_ids = $this->get_product_gallery_image_ids( $product );
				if ( (has_post_thumbnail() && get_the_post_thumbnail( $product->get_id()) ) || $attachment_ids ) {
					$classes[] = 'wd-product-hover-thumbnail-effect';
				}
			}
			return $classes;
		}

		//Get HTML secondary thumbnail (change image when hover)
		public function get_product_secondary_thumbnail($image_size = 'shop_catalog') {
			global $product, $woocommerce;

			$attachment_ids = $this->get_product_gallery_image_ids( $product );
			if ( $attachment_ids || (has_post_thumbnail() && get_the_post_thumbnail( $product->get_id()) ) ) {
				$attachment_ids     = ($attachment_ids) ? array_values( $attachment_ids ) : array(get_post_thumbnail_id( $product->get_id() ));
				$secondary_image_id = $attachment_ids['0'];

				$secondary_image_alt = get_post_meta( $secondary_image_id, '_wp_attachment_image_alt', true ) 
										? get_post_meta( $secondary_image_id, '_wp_attachment_image_alt', true ) : get_the_title($secondary_image_id);
				$secondary_image_title = get_the_title($secondary_image_id);

				$image_html = wp_get_attachment_image( $secondary_image_id, $image_size, '', array(
						'class' => 'secondary-image attachment-shop-catalog wp-post-image wp-post-image--secondary',
						'alt' => $secondary_image_alt,
						'title' => $secondary_image_title
					)
				);
			}else if (has_post_thumbnail() && get_the_post_thumbnail( $product->get_id()) ) {
				$image_html = get_the_post_thumbnail( $product->get_id(), $image_size, array(
						'class' => 'secondary-image attachment-shop-catalog wp-post-image wp-post-image--secondary',
						'alt' => get_the_title($product->get_id()),
						'title' => get_the_title($product->get_id())
					) 
				);
			}
			echo $image_html;
		}

		//**************************************************************//
        /*					CUSTOM CHECKOUT FORM FIELS					*/
		//**************************************************************//
		// Change order comments placeholder and label
		public function custom_wc_checkout_fields( $fields ) {
			/* Billing form */
			$fields['billing']['billing_first_name']['placeholder'] 	= esc_html__( 'First name', 'feellio' );
			$fields['billing']['billing_first_name']['label'] 			= '';

			$fields['billing']['billing_last_name']['placeholder'] 		= esc_html__( 'Last name', 'feellio' );
			$fields['billing']['billing_last_name']['label'] 			= '';

			$fields['billing']['billing_company']['placeholder'] 		= esc_html__( 'Company name', 'feellio' );
			$fields['billing']['billing_company']['label'] 				= '';

			$fields['billing']['billing_country']['label'] 				= '';

			$fields['billing']['billing_address_1']['placeholder'] 		= esc_html__( 'House number and street name', 'feellio' );
			$fields['billing']['billing_address_1']['label'] 			= '';

			$fields['billing']['billing_address_2']['placeholder'] 		= esc_html__( 'Apartment, suite, unit etc. (optional)', 'feellio' );
			$fields['billing']['billing_address_2']['label'] 			= '';

			$fields['billing']['billing_city']['placeholder'] 			= esc_html__( 'Town / City', 'feellio' );
			$fields['billing']['billing_city']['label'] 				= '';

			$fields['billing']['billing_state']['placeholder'] 			= esc_html__( 'State / County', 'feellio' );
			$fields['billing']['billing_state']['label'] 				= '';

			$fields['billing']['billing_postcode']['placeholder'] 		= esc_html__( 'Postcode / ZIP', 'feellio' );
			$fields['billing']['billing_postcode']['label'] 			= '';

			$fields['billing']['billing_phone']['placeholder'] 			= esc_html__( 'Phone', 'feellio' );
			$fields['billing']['billing_phone']['label'] 				= '';

			$fields['billing']['billing_email']['placeholder'] 			= esc_html__( 'Email address', 'feellio' );
			$fields['billing']['billing_email']['label'] 				= '';

			/* Shipping form */
			$fields['shipping']['shipping_first_name']['placeholder'] 	= esc_html__( 'First name', 'feellio' );
			$fields['shipping']['shipping_first_name']['label'] 		= '';

			$fields['shipping']['shipping_last_name']['placeholder'] 	= esc_html__( 'Last name', 'feellio' );
			$fields['shipping']['shipping_last_name']['label'] 			= '';

			$fields['shipping']['shipping_company']['placeholder'] 		= esc_html__( 'Company name', 'feellio' );
			$fields['shipping']['shipping_company']['label'] 			= '';

			$fields['shipping']['shipping_country']['label'] 			= '';

			$fields['shipping']['shipping_address_1']['placeholder'] 	= esc_html__( 'House number and street name', 'feellio' );
			$fields['shipping']['shipping_address_1']['label'] 			= '';

			$fields['shipping']['shipping_address_2']['placeholder'] 	= esc_html__( 'Apartment, suite, unit etc. (optional)', 'feellio' );
			$fields['shipping']['shipping_address_2']['label'] 			= '';

			$fields['shipping']['shipping_city']['placeholder'] 		= esc_html__( 'Town / City', 'feellio' );
			$fields['shipping']['shipping_city']['label'] 				= '';

			$fields['shipping']['shipping_state']['placeholder'] 		= esc_html__( 'State / County', 'feellio' );
			$fields['shipping']['shipping_state']['label'] 				= '';

			$fields['shipping']['shipping_postcode']['placeholder'] 	= esc_html__( 'Postcode / ZIP', 'feellio' );
			$fields['shipping']['shipping_postcode']['label'] 			= '';

			/* Order comment form */
			$fields['order']['order_comments']['placeholder'] 			= esc_html__( 'Notes about your order, e.g. special notes for delivery.', 'feellio' );
			$fields['order']['order_comments']['label'] 				= '';
			return $fields;
		}

		public function custom_wc_billing_fields( $fields ) {
			/* Billing form */
			$fields['billing_first_name']['placeholder'] 	= esc_html__( 'First name', 'feellio' );
			$fields['billing_first_name']['label'] 			= '';

			$fields['billing_last_name']['placeholder'] 	= esc_html__( 'Last name', 'feellio' );
			$fields['billing_last_name']['label'] 			= '';

			$fields['billing_company']['placeholder'] 		= esc_html__( 'Company name', 'feellio' );
			$fields['billing_company']['label'] 			= '';

			$fields['billing_country']['label'] 			= '';

			$fields['billing_address_1']['placeholder'] 	= esc_html__( 'House number and street name', 'feellio' );
			$fields['billing_address_1']['label'] 			= '';

			$fields['billing_address_2']['placeholder'] 	= esc_html__( 'Apartment, suite, unit etc. (optional)', 'feellio' );
			$fields['billing_address_2']['label'] 			= '';

			$fields['billing_city']['placeholder'] 			= esc_html__( 'Town / City', 'feellio' );
			$fields['billing_city']['label'] 				= '';

			$fields['billing_state']['placeholder'] 		= esc_html__( 'State / County', 'feellio' );
			$fields['billing_state']['label'] 				= '';

			$fields['billing_postcode']['placeholder'] 		= esc_html__( 'Postcode / ZIP', 'feellio' );
			$fields['billing_postcode']['label'] 			= '';

			$fields['billing_phone']['placeholder'] 		= esc_html__( 'Phone', 'feellio' );
			$fields['billing_phone']['label'] 				= '';

			$fields['billing_email']['placeholder'] 		= esc_html__( 'Email address', 'feellio' );
			$fields['billing_email']['label'] 				= '';
			return $fields;
		}

		public function custom_wc_shipping_fields( $fields ) {
			$fields['shipping_first_name']['placeholder'] 	= esc_html__( 'First name', 'feellio' );
			$fields['shipping_first_name']['label'] 		= '';

			$fields['shipping_last_name']['placeholder'] 	= esc_html__( 'Last name', 'feellio' );
			$fields['shipping_last_name']['label'] 			= '';

			$fields['shipping_company']['placeholder'] 		= esc_html__( 'Company name', 'feellio' );
			$fields['shipping_company']['label'] 			= '';

			$fields['shipping_country']['label'] 			= '';

			$fields['shipping_address_1']['placeholder'] 	= esc_html__( 'House number and street name', 'feellio' );
			$fields['shipping_address_1']['label'] 			= '';

			$fields['shipping_address_2']['placeholder'] 	= esc_html__( 'Apartment, suite, unit etc. (optional)', 'feellio' );
			$fields['shipping_address_2']['label'] 			= '';

			$fields['shipping_city']['placeholder'] 		= esc_html__( 'Town / City', 'feellio' );
			$fields['shipping_city']['label'] 				= '';

			$fields['shipping_state']['placeholder'] 		= esc_html__( 'State / County', 'feellio' );
			$fields['shipping_state']['label'] 				= '';

			$fields['shipping_postcode']['placeholder'] 	= esc_html__( 'Postcode / ZIP', 'feellio' );
			$fields['shipping_postcode']['label'] 			= '';
			return $fields;
		}

		//**************************************************************//
        /*					CUSTOM CART REDIRECT LINK					*/
		//**************************************************************//
		public function custom_add_to_cart_redirect() { 
			return esc_url( wc_get_cart_url() ); 
		}

		//**************************************************************//
        /*					DEQUEUE WOOCOMMERCE LIBRARY					*/
		//**************************************************************//
		// Disable Ajax Call from WooCommerce on front page and posts
		public function dequeue_woocommerce_cart_fragments() {
			if ( wd_is_woocommerce() ) {
				wp_dequeue_script('wc-cart-fragments');
				if (( get_option('woocommerce_myaccount_page_id') != get_the_ID() 
					&& !is_woocommerce() && !is_cart() && !is_checkout() 
					&& !is_home() && !is_front_page() 
					&& !is_page() ) || is_page_template( 'page-templates/template-woocomerce-disabled.php' )) {
					# Styles
					wp_dequeue_style( 'woocommerce-general' );
					wp_dequeue_style( 'woocommerce-layout' );
					wp_dequeue_style( 'woocommerce-smallscreen' );
					wp_dequeue_style( 'woocommerce_frontend_styles' );
					wp_dequeue_style( 'woocommerce_fancybox_styles' );
					wp_dequeue_style( 'woocommerce_chosen_styles' );
					wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
					# Scripts
					wp_dequeue_script( 'wc_price_slider' );
					wp_dequeue_script( 'wc-single-product' );
					wp_dequeue_script( 'wc-add-to-cart' );
					wp_dequeue_script( 'wc-checkout' );
					wp_dequeue_script( 'wc-add-to-cart-variation' );
					wp_dequeue_script( 'wc-single-product' );
					wp_dequeue_script( 'wc-cart' );
					wp_dequeue_script( 'wc-chosen' );
					wp_dequeue_script( 'woocommerce' );
					wp_dequeue_script( 'prettyPhoto' );
					wp_dequeue_script( 'prettyPhoto-init' );
					wp_dequeue_script( 'jquery-blockui' );
					wp_dequeue_script( 'jquery-placeholder' );
					wp_dequeue_script( 'fancybox' );
					wp_dequeue_script( 'jqueryui' );
				}
			}
		}

		//**************************************************************//
        /*						REMOVE PRODUCT CLASS					*/
        //**************************************************************//
		//Remove "first", "last" class on product loop
		public function remove_product_loop_class( $classes ) {
			global $product;
			if ( get_post_type(get_the_ID()) == 'product' ) {
				$classes = array_diff( $classes, array( 'first', 'last' ) );
			}
			return $classes;
		}

		//**************************************************************//
        /*					WOOCOMMERCE BREADCRUMB STRUCTURE			*/
        //**************************************************************//
		//Change Woocommerce Breadcrumb Structure
		public function woocommerce_breadcrumbs_structure() {
			$delimiter = '<span class="wd-breadcrumb-delimiter"><i class="lnr lnr-chevron-right wd-icon"></i></span> ';
			$front_id = get_option( 'page_on_front' );
			if ( !empty( $front_id ) ) {
				$home = get_the_title( $front_id );
			} else {
				$home = esc_html__( 'Home', 'feellio' );
			}
			return array(
					'delimiter'   => $delimiter,
					'wrap_before' => '<div class="wd-breadcrumb-slug-content">',
					'wrap_after'  => '</div>',
					'before'      => '',
					'after'       => '',
					'home'        => $home,
				);
		}

		//**************************************************************//
        /*							PRODUCT EFFECT						*/
        //**************************************************************//
		public function product_effect(){
			/**
			 * package: product-effect
			 * var: popup_cart
			 */
			extract(apply_filters('wd_filter_get_data_package', 'product-effect' )); ?>
			<div id="wd-popup-after-add-to-cart" data-active="<?php echo esc_attr($popup_cart); ?>" style="display: none;">
				<div class="wd-popup-title"><?php esc_html_e('Your shopping cart', 'feellio'); ?></div>
				<div class="wd-popup-content"></div>
			</div>
			<?php
		}

		//**************************************************************//
        /*						PRODUCT PRICE FILTER					*/
		//**************************************************************//
		//https://stackoverflow.com/questions/45806249/change-product-prices-via-a-hook-in-woocommerce-3/45807054#45807054
		// Utility function to change the prices with a multiplier (number)
		public function get_price_multiplier() {
			return 1; // set 2 to x2 price
		}

		// Simple, grouped and external products, Variations
		public function custom_price( $price, $product ) {
			return (int)$price * $this->get_price_multiplier();
		}

		// Variable (price range)
		public function custom_variable_price( $price, $variation, $product ) {
			// Delete product cached price  (if needed)
			// wc_delete_product_transients($variation->get_id());

			return (int)$price * $this->get_price_multiplier();
		}

		// Handling price caching (see explanations at the end)
		public function add_price_multiplier_to_variation_prices_hash( $hash ) {
			$hash[] = (int)$this->get_price_multiplier();
			return $hash;
		}
		//**************************************************************//
        /*						 PRODUCT ARCHIVE 						*/
		//**************************************************************//
		public function woocommerce_result_count(){ 
			woocommerce_result_count();
		}

		public function woocommerce_catalog_ordering(){ 
			woocommerce_catalog_ordering();
		}

		public function product_archive_toggle_button(){ ?>
			<div class="wd-layout-toggle-wrap wd-layout-toggle-product wd-desktop-screen">
				<ul class="option-set">
					<?php 
					$list_columns = array(
						'2'	=> esc_html__( '2 Columns', 'feellio' ),
						'3'	=> esc_html__( '3 Columns', 'feellio' ),
						'4'	=> esc_html__( '4 Columns', 'feellio' ),
					);  
					foreach ($list_columns as $column => $title){ ?>
						<li data-option-value="<?php echo $column; ?>" id="wd-columns-toggle-<?php echo $column; ?>" class="wd-columns-toggle-action" data-toggle="tooltip" title="<?php echo $title; ?>">
							<?php echo $column; ?>
						</li>
					<?php } ?>
					<li data-layout="grid" class="wd-grid-list-toggle-action" data-toggle="tooltip" title="<?php _e('Grid view', 'feellio'); ?>">
						<i class="lnr lnr-menu wd-icon"></i>
					</li>
					<li data-layout="list" class="wd-grid-list-toggle-action" data-toggle="tooltip" title="<?php _e('List view', 'feellio'); ?>">
						<i class="lnr lnr-list wd-icon"></i>
					</li>
				</ul>
			</div>		
		<?php 
		}

		public function product_ordering() {  ?>
			<form action="shop" class="woocommerce-ordering" method="get">
				<select name="orderby" class="orderby">
					<option value="menu_order" selected="selected"><?php _e('Default sorting', 'feellio'); ?></option>
					<option value="popularity"><?php _e('Sort by popularity', 'feellio'); ?></option>
					<option value="rating"><?php _e('Sort by average rating', 'feellio'); ?></option>
					<option value="date"><?php _e('Sort by newnes', 'feellio'); ?></option>
					<option value="price"><?php _e('Sort by price: low to high', 'feellio'); ?></option>
					<option value="price-desc"><?php _e('Sort by price: high to low', 'feellio'); ?></option>
				</select>
			</form>
		<?php
		}

		public function get_archive_posts_per_page(){ 
			/**
			 * package: product-archive-posts-per-page
			 * var: posts_per_page  
			 */
			extract(apply_filters('wd_filter_get_data_package', 'product-archive-posts-per-page' ));
			return $posts_per_page;
		}
	
		public function archive_posts_per_page(){ 
			add_filter('loop_shop_per_page', array($this, 'get_archive_posts_per_page'), 20 );
		}

		/* woocommerce product category image */
		public function product_category_image() {
			if ( is_product_category() ){
				global $wp_query;
				$cat = $wp_query->get_queried_object();
				$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				if ( $image ) {
						echo "<div class='wd-cat-thumb-archive-product'>";
						echo '<img src="' . $image . '" alt="'.get_bloginfo('name').'" />';
						echo "</div>";
					}
			}
		}
	}
	WD_Woocommerce_Functions::get_instance();  // Start an instance of the plugin class 
}