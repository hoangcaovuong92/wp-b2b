<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Cart')) {
	class WD_Cart {
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

			//Display mini cart icon
			/* echo apply_filters('wd_filter_minicart', array('show_icon' => 1, 'show_text' => 0, 'show_text' => 0, 'dropdown_position' => 'left', 'screen' => 'all', 'class' => '')); */
			add_filter('wd_filter_minicart', array($this, 'minicart_content'), 10, 2);
			
			//Update cart ( single product add to cart via Ajax )
			add_action('wp_ajax_update_tini_cart_single_product', array($this, 'update_tini_cart_single_product'));
			add_action('wp_ajax_nopriv_update_tini_cart_single_product', array($this, 'update_tini_cart_single_product'));

			add_action('init', array($this, 'tiny_cart_add_filter'), 1);

			// Ensure cart contents update when products are added to the cart via AJAX
			add_filter('woocommerce_add_to_cart_fragments', array($this, 'update_tini_cart'));
		}

		public function minicart_content($setting = array()){
			if ( !wd_is_woocommerce() )  return;
			$default = array(
				'show_icon' => 1, //icon
				'show_text' => 0, 
				'show_total' => 0, 
				'dropdown_position' => 'left', //left/right 
				'screen' => 'all', //desktop/mobile/all
				'class' => ''
			);
			extract(wp_parse_args($setting, $default));
			$random_id 	= 'wd-cart-popup-content-'.mt_rand();
			ob_start(); ?>
			<div class="wd-navUser-action-wrap wd-dropdown-wrap wd-cart-flagments">
				<?php if ($screen == 'desktop' || $screen == 'all') { ?>
					<div class="wd-desktop-screen">
						<div class="wd-tini-cart-wrapper <?php echo esc_attr($class) ?>">
							<?php $this->cart_icon('desktop', '', $show_icon, $show_text, $show_total); ?>
							<div class="wd-dropdown-container wd-dropdown-container--<?php echo $dropdown_position; ?> wd-dropdown--minicart">
								<?php $this->minicart_desktop_dropdown_content(); ?>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<?php if ($screen == 'mobile' || $screen == 'all') { ?>
					<div class="wd-mobile-screen">
						<div class="wd-tini-cart-wrapper <?php echo esc_attr($class) ?>">
							<?php $this->cart_icon('mobile', $random_id, $show_icon, $show_text, $show_total); ?>
							<div id="<?php echo esc_attr($random_id); ?>" style="display:none;">
								<div class="wd-popup-title"><?php esc_html_e('Your shopping cart', 'feellio'); ?></div>
								<div class="wd-popup-content">
									<div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php 
			return ob_get_clean();
		}
		
		public function cart_icon($device = 'desktop', $random_id = '', $show_icon = 'icon', $show_text = 0, $show_total = 0) {
			$random_id 		= ($random_id != '') ? $random_id : 'wd-cart-popup-content-'.mt_rand() ;
			$icon_html 	= ($show_icon) ? '<span class="fa fa-cart-arrow-down wd-icon"></span>' : '';
			$title = ($show_text) ? '<span class="wd-navUser-action-text">'.esc_html__('Cart', 'feellio').'</span>' : '';
			$total = ($show_total) ? '<span class="wd-mini-cart-total">'.WC()->cart->get_cart_subtotal().'</span>' : '';
			//Num item
			$number = '<span class="wd-count-pill wd-count-pill--cart">';
			$number .= apply_filters('wd_filter_number_fix_length', array('number' => WC()->cart->cart_contents_count, 'length' => '2', 'character' => '0'));
			$number .= '</span>';
			
			$cart_action_class = (WC()->cart->cart_contents_count) ? 'wd-minicart-has-items' : 'wd-minicart-none-items'; ?>
			
			<a data-content_id="<?php echo esc_attr($random_id); ?>" class="wd-navUser-action wd-navUser-action--minicart <?php echo esc_attr($cart_action_class); ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html_e('View your cart', 'feellio');?>">
				<?php echo $icon_html.$title.$number.$total; ?>
			</a>
		<?php
		}
		
		public function minicart_desktop_dropdown_content() {
			global $woocommerce;
			$is_cart_empty 	= sizeof( $woocommerce->cart->get_cart() ) > 0 ? false : true ; 
			//Num item
			$number 		= WC()->cart->cart_contents_count;
			$number 		= ( $number < 10 && $number != 0 )  ? '0'.esc_attr($number) : esc_attr($number); ?>
		
			<!-- Cart content -->
			<?php if ( !$is_cart_empty ) : ?>
				<div class="wd-dropdown-body">
					<!-- <h5 class="wd-cart-item-info">
						<?php //printf(_n('<strong>%s</strong> Item On Your Cart', '<strong>%s</strong> Items On Your Cart', $number, 'feellio'), $number); ?>
					</h5> -->
					<ul class="cart_list product_list_widget">
						<?php
							do_action( 'woocommerce_before_mini_cart_contents' );
		
							foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
								$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
								$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		
								if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
									$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
									$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
									$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
									?>
									<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
										
										<?php if ( ! $_product->is_visible() ) : ?>
											<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
										<?php else : ?>
											<a href="<?php echo esc_url( $product_permalink ); ?>">
												<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
											</a>
										<?php endif; ?>
										<div class="cart_item_wrapper">	
											<?php if ( ! $_product->is_visible() ) : ?>
												<?php echo $product_name; ?>
											<?php else : ?>
												<a class="wd-minicart-item-title" href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $product_name; ?></a>
											<?php endif; ?>
		
											<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
		
											<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity link_color">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
											<?php
		
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												__( 'Remove this item', 'feellio' ),
												esc_attr( $product_id ),
												esc_attr( $cart_item_key ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
											?>
										</div>
									</li>
									<?php
								}
							}
		
							do_action( 'woocommerce_mini_cart_contents' );
						?>
					</ul>
				</div>
				<div class="wd-empty-cart-btn">
					<?php $url_clear_cart = add_query_arg('empty_cart', 1, wd_get_current_url()); ?>
					<a href="<?php echo esc_url($url_clear_cart); ?>" class="cart wd-clear-cart-item" title="<?php esc_html_e( 'Empty Cart', 'feellio' ) ?>" data-mess="<?php esc_html_e( 'Are you sure you want to empty shopping cart?', 'feellio' ) ?>"><span class="fa fa-trash wd-icon"></span> <?php esc_html_e( 'Empty Cart', 'feellio' ) ?>
					</a>
					<div class="wd-loading wd-loading--empty-minicart hidden">
						<img src="<?php echo WD_THEME_IMAGES.'/loading.gif'; ?>" alt="<?php echo esc_html__( 'Loading Icon' , 'feellio'); ?>">
					</div>
				</div>
				<div class="wd-dropdown-footer">
					<div class="wd-dropdown-footer-button">
						<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
						<div class="buttons">
							<a class="cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html_e( 'View Cart', 'feellio' ) ?>"><?php esc_html_e('View cart', 'feellio');?></a>
							<a class="checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" title="<?php esc_html_e( 'Checkout Page', 'feellio' ) ?>"><span class="fa fa-arrow-right wd-icon"></span> <?php esc_html_e( 'Checkout', 'feellio' ); ?></a>
							
						</div>
					</div>
					<div class="total">
						<span class="total-label"><?php esc_html_e( 'Total:', 'feellio' ); ?></span>
						<span class="total-price"><?php echo wp_kses_post( $woocommerce->cart->get_cart_subtotal()); ?></span>
					</div>
				</div>
				<div class="wd-minicart-notice">
					<?php esc_html_e( '* Shipping & taxes calculated at checkout', 'feellio' ); ?>
				</div>
			<?php else: ?>
				<div class="wd-dropdown-body wd-cart-empty">
					<?php esc_html_e('No products in the cart', 'feellio'); ?>
				</div>
				<div class="wd-dropdown-footer">
					<div class="wd-dropdown-footer-button">
						<div class="buttons">
							<a class="cart" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php esc_html_e('Go Shopping', 'feellio');?></a>
						</div>
					</div>
					<div class="total">
						<span class="total-label"><?php esc_html_e( 'Total:', 'feellio' ); ?></span>
						<span class="total-price"><?php echo wp_kses_post( $woocommerce->cart->get_cart_subtotal()); ?></span>
					</div>
				</div>
			<?php endif; ?>
		<?php 
		}
		
		// Ensure cart contents update when products are added to the cart via AJAX
		public function update_tini_cart( $fragments ) {
			$fragments['.wd-cart-flagments'] = $this->minicart_content();
			return $fragments;
		}
		
		//Update cart ( single product add to cart via Ajax )
		public function update_tini_cart_single_product() {
			$product_id 	= $_POST['product_id'];
			$variation_id 	= $_POST['variation_id'];
			$quantity 		= $_POST['quantity'];
			$product_type 	= $_POST['product_type'];
		
			if ($product_type == 'variation') {
				WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
			} elseif ($product_type == 'simple') {
				WC()->cart->add_to_cart( $product_id, $quantity);
			}
		
			echo $this->minicart_content();
			die();
		}
		
		
		/* Support WooCommerce Multilingual */
		public function tiny_cart_add_ajax_action($actions){
			$actions[] = 'update_tini_cart';
			return $actions;
		}
		
		public function tiny_cart_add_filter(){
			add_filter( 'wcml_multi_currency_is_ajax', array($this, 'tiny_cart_add_ajax_action'));
		}
	}
	WD_Cart::get_instance();  // Start an instance of the plugin class 
}