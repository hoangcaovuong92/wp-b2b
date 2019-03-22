<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Transient')) {
	class WD_Transient {
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
			
			//Clear transient menu after menu save
			//add_action( 'wp_update_nav_menu', array($this, 'update_menus_transient'));
		}

		function do_transient_menu( $args = array() ) {
			$defaults = array(
				'menu' 				=> '',
				'theme_location' 	=> '',
				'echo' 				=> true,
			);
		
			$args 					= wp_parse_args( $args, $defaults );
			$transient_name 		= 'wd_menu-' . $args['menu'] . '-' . $args['theme_location'];
			$menu 					= get_transient( $transient_name );
		
			if ( false === $menu ) {
				$menu_args 			= $args;
				$menu_args['echo'] 	= false;
				$menu 				= wp_nav_menu( $menu_args );
				set_transient($transient_name, $menu, 0);
		
				$transient_keys 	= get_option( 'wd_menu_transient' );
				$transient_keys[] 	= $transient_name;
				update_option('wd_menu_transient', $transient_keys);
			}
		
			if( false === $args['echo'] ) {
				return $menu;
			}
		
				echo $menu;
		
			}
		
		//Clear transient menu after menu save
		function update_menus_transient() {
		
			//Delete nav menu transient
			$menus 		= get_option( 'wd_menu_transient' );
			if (is_array($menus) && sizeof($menus) > 0) {
				foreach( $menus as $menu ) {
					delete_transient( $menu );
				}
			}
			update_option('wd_menu_transient', array());
		
			//Delete push menu mobile transient
			delete_transient( 'wd_pushmenu_mobile' );
		}
		
		//Transient for WP_Query
		function query_transient($transient_key, $args) {
			$content = get_transient( $transient_key );
			if ( false === $content ) {
				$content = new WP_Query( $args );
				set_transient( $transient_key, $content, DAY_IN_SECONDS );
			}
		}
	}
	WD_Transient::get_instance();  // Start an instance of the plugin class 
}