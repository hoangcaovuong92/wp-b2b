<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Required_WP_Version')) {
	class WD_Required_WP_Version {
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

		private $wpversion = '5.0';
		private $phpversion = '5.4';

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;
			if (!$this->check_wp_version() || !$this->check_php_version()) {
				add_action( 'after_switch_theme', array($this, 'required_version_on_switch_theme' ));
				add_action( 'template_redirect', array($this, 'required_version_on_preview' ));
				add_action( 'load-customize.php', array($this, 'required_version_on_customize' ));
			}
		}

		
		public function check_wp_version() {
			return version_compare($GLOBALS['wp_version'], $this->wpversion, '>') ? true : false;
		}

		public function check_php_version() {
			return version_compare(PHP_VERSION, $this->phpversion, '>') ? true : false;
		}

		public function required_version_on_switch_theme() {
			//switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			unset( $_GET['activated'] );
			add_action( 'admin_notices', array($this, 'required_version_on_upgrade_notice' ));
		}
		
		/**
		* Adds a message for unsuccessful theme switch.
		*
		* Prints an update nag after an unsuccessful attempt to switch to
		* feellio on WordPress versions prior to 4.8.
		*
		* @since feellio 1.0
		*
		* @global string $wp_version WordPress version.
		*/
		public function required_version_on_upgrade_notice() {
			$message = sprintf( esc_html__( '%s requires at least WordPress version %s. You are running WordPress version %s and PHP version %s. Please upgrade and try again.', 'feellio' ), WD_THEME_NAME, $this->wpversion, $this->phpversion, $GLOBALS['wp_version'], PHP_VERSION );
			printf( '<div class="error"><p>%s</p></div>', $message );
		}

		/**
		* Prevents the Customizer from being loaded on WordPress versions prior to 4.8.
		*
		* @since feellio 1.0
		*
		* @global string $wp_version WordPress version.
		*/
		public function required_version_on_customize() {
			wp_die( sprintf( esc_html__( '%s requires at least WordPress version %s and PHP version %s. You are running WordPress version %s and PHP version %s. Please upgrade and try again.', 'feellio' ), WD_THEME_NAME, $this->wpversion, $this->phpversion, $GLOBALS['wp_version'], PHP_VERSION ), '', array(
				'back_link' => true,
			) );
		}
		

		/**
		* Prevents the Theme Preview from being loaded on WordPress versions prior to 4.8.
		*
		* @since feellio 1.0
		*
		* @global string $wp_version WordPress version.
		*/
		public function required_version_on_preview() {
			if ( isset( $_GET['preview'] ) ) {
				wp_die( sprintf( esc_html__( '%s requires at least WordPress version %s and PHP version %s. You are running WordPress version %s and PHP version %s. Please upgrade and try again.', 'feellio' ), WD_THEME_NAME, $this->wpversion, $this->phpversion, $GLOBALS['wp_version'], PHP_VERSION ) );
			}
		}
	}
	WD_Required_WP_Version::get_instance();  // Start an instance of the plugin class 
}