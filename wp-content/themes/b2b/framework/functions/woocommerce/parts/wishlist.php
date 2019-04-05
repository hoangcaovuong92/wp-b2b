<?php

/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Wishlist')) {
	class WD_Wishlist {
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
			
			//Auto update wishlist count
			if( defined( 'YITH_WCWL' )){
				add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', array($this, 'wishlist_ajax_update_count' ));
				add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', array($this, 'wishlist_ajax_update_count' ));
			}

			//Display wishlist icon
 			/* echo apply_filters('wd_filter_wishlist_icon', array('show_icon' => 1, 'show_text' => 0, 'class' => ''); */
			add_filter( 'wd_filter_wishlist_icon', array($this, 'get_wishlist_icon' ));
		}

		public function get_wishlist_icon($setting = array()){
			$default = array(
				'show_icon' => 1, //icon
				'show_text' => 0, 
				'class' => ''
			);
			extract(wp_parse_args($setting, $default));

			if (!wd_is_woocommerce()) return;
			if (!defined('YITH_WCWL')) return '';
	
			global $wpdb;
			$has_table = $wpdb->query("SHOW TABLES LIKE '".YITH_WCWL_TABLE."'" );

			if(!$has_table) return;
			
			ob_start();
			
			if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) ) {
				$user_id = $_GET['user_id'];
			} elseif( is_user_logged_in() ) {
				$user_id = get_current_user_id();
			}
			$wishlist_page = esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) );
			
			$icon_html 	= ($show_icon) ? '<span class="fa fa-heartbeat wd-icon"></span>' : '';
			$title = ($show_text) ? '<span class="wd-navUser-action-text">'.esc_html__('Wishlist', 'feellio').'</span>' : ''; ?>
			<div class="wd-navUser-action-wrap wd-wishlist-icon <?php echo esc_attr($class) ?>">
				<a class="wd-navUser-action wd-navUser-action--wishlist" href="<?php echo esc_url($wishlist_page); ?>">
					<?php echo $icon_html.$title; ?>
					<span class="wd-count-pill wd-count-pill--wishlist">
						<?php echo apply_filters('wd_filter_number_fix_length', array('number' => yith_wcwl_count_all_products(), 'length' => '2', 'character' => '0')); ?>
					</span>
				</a>
			</div>
			<?php
			$tini_wishlist = ob_get_clean();
			return $tini_wishlist;
		}
		
		public function wishlist_ajax_update_count(){
			wp_send_json( array(
				'count' => apply_filters('wd_filter_number_fix_length', array('number' => yith_wcwl_count_all_products(), 'length' => '2', 'character' => '0'))
			) );
		}
	}
	WD_Wishlist::get_instance();  // Start an instance of the plugin class 
}