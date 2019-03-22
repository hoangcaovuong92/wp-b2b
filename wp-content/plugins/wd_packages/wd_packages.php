<?php 
/*
  Plugin Name: WD Packages (B2B Theme)
  Plugin URI: http://b2bnetworking.net/
  Description: Provide shortcodes, widgets, gutenberg blocks... for you to build a great website.
  Version: 1.0.0
  Author: Cao Vương
  Author URI: https://www.facebook.com/hoangcaovuong
  Text Domain: wd_package
 */
if (!class_exists('WD_Packages')) {
	class WD_Packages {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private $arr_settings 	= array();

		public function __construct(){
			if ($this->get_curent_theme_slug() != 'b2b') return;

			$this->constant();
			$this->get_packages_setting();
			$this->cpt(); //custom post type
			$this->package_function_include();
			$this->package_include();

			add_action('init', array($this, 'setting_page'));
			add_action('wp_enqueue_scripts', array($this, 'front_end_package_js'));
			add_action('admin_enqueue_scripts', array($this, 'back_end_package_js'));

			//Usage : $package_option = apply_filters('wd_filter_packages_option', $part);
			add_filter( 'wd_filter_packages_option', array($this, 'get_packages_option' ), 10, 2);

			//Usage : $package_setting = apply_filters('wd_filter_packages_setting', $part);
			add_filter( 'wd_filter_packages_setting', array($this, 'get_packages_setting' ), 10, 2);

			// Auto update
			$this->update_library_include();

			//Provides activation/deactivation hook for wordpress theme/plugins.
			$this->theme_actions();
			$this->plugins_actions();
		}

		protected function constant(){
			define('WD_PACKAGE'			,   plugin_dir_path( __FILE__ ) );
			define('WD_PACKAGE_URI'		,   plugins_url( '', __FILE__ ) );
			define('WD_PACKAGE_ASSETS' 	,   WD_PACKAGE_URI. '/assets');
			define('WD_PACKAGE_INCLUDE'	,   WD_PACKAGE. '/include' );
			define('WD_PACKAGE_CPT'		,   WD_PACKAGE. '/cpt' );
			define('WD_PACKAGE_JS' 		,   WD_PACKAGE_ASSETS. '/js');
		}

		//Plugin/theme update library
		public function update_library_include(){ 
			if(file_exists(WD_PACKAGE."/update/class.update.php")){
				require_once WD_PACKAGE.'/update/class.update.php';
				wd_update_checker_init(__FILE__);
			}
		} 

		//$part
		public function get_packages_option($part = 'all'){ 
			$options = array(
				'wd_blocks' 	=> array(
					'title'			=> __( 'Blocks + Shortcodes + Widgets', 'wd_package' ),
					'option_name' 	=> 'wd_package_shortcode',
					'default' 		=> true,
				),
				'wd_instagram' 	=> array(
					'title'			=> __( 'Instagram', 'wd_package' ),
					'default' 		=> false,
				),
				'wd_megamenu' => array(
					'title'			=> __( 'Megamenu', 'wd_package' ),
					'default' 		=> true,
				),
				'wd_facebook_chatbox' => array(
					'title'			=> __( 'Facebook Chatbox', 'wd_package' ),
					'default' 		=> true,
				),
			);
			return ($part !== 'all' && !empty($options[$part])) ? $options[$part] : $options;
		}

		//Check current settings data when active plugin and set default data if not exist
		public function set_settings_default(){
			if (!get_option('wd_packages')) {
				$packages_options = $this->get_packages_option();
				$default_settings = array();
				foreach ($packages_options as $key => $value) {
					$default_settings[$key] = $value['default'];
				}
				update_option('wd_packages', $default_settings);
			}
		}

		//$part
		public function get_packages_setting($part = 'all'){
			if ($parkages = get_option('wd_packages')) {
				foreach ($this->get_packages_option() as $key => $value) {
					$this->arr_settings[$key] = (!empty($parkages[$key])) ? $parkages[$key] : false;
				}
			}
			return ($part !== 'all' && !empty($this->arr_settings[$part])) ? $this->arr_settings[$part] : $this->arr_settings;
		}

		public function front_end_package_js(){
		}

		public function back_end_package_js(){
			wp_enqueue_style( 'wp-color-picker' );
		}

		public function package_function_include(){ 
			$functions = array(
				'functions',
				'functions_vc',
				'widgets',
			);
			foreach ($functions as $package) {
				if(file_exists(WD_PACKAGE_INCLUDE."/{$package}.php")){
					require_once WD_PACKAGE_INCLUDE."/{$package}.php";
				}
			}
		} 

		public function cpt(){
			if(file_exists(WD_PACKAGE_CPT."/manager.php")){
				require_once WD_PACKAGE_CPT."/manager.php";
			}
		}

		public function setting_page(){
			if(file_exists(WD_PACKAGE."/settings/manager.php")){
				require_once WD_PACKAGE."/settings/manager.php";
			}
		}

		public function package_include(){
			if (!empty($this->arr_settings)) {
				foreach ($this->arr_settings as $package => $display) {
					if(file_exists(WD_PACKAGE."/packages/{$package}/{$package}.php") && $display){
						require_once WD_PACKAGE."/packages/{$package}/{$package}.php";
					}
				}
			}
		}

		//**************************************************************//
		/*					THEME/PLUGINS ACTIVE/DEACTIVE HOOK			*/
		//**************************************************************//
		public function get_curent_theme_slug(){
			return wp_get_theme()->template;
		}

		public function plugins_actions(){
			register_activation_hook(__FILE__, array($this, 'plugin_activation_callback'));
			register_deactivation_hook(__FILE__, array($this, 'plugin_deactivation_callback'));
		}

		public function plugin_activation_callback() {
			$this->set_settings_default();
		}

		public function plugin_deactivation_callback() {
		}

		public function theme_actions(){
			//Provides activation/deactivation hook for wordpress theme.
			$this->theme_activation_hook($this->get_curent_theme_slug(), array($this, 'theme_activation_callback'));
			$this->theme_deactivation_hook($this->get_curent_theme_slug(), array($this, 'theme_deactivation_callback'));
		}

		public function theme_activation_callback() {
			$this->reset_activation();
		}
		
		public function theme_deactivation_callback() {
			$this->reset_activation();
		}
			
		public function reset_activation() {
			delete_option('wd_verify_purchase');
		    update_option('wd_check_update_remote_request', 0);
		    update_option('wd_customer_info_by_purchase_code', '');
		}

		public function theme_activation_hook($code, $function) {
		    $optionKey = "theme_is_activated_" . $code;
		    if(!get_option($optionKey)) {
		        call_user_func($function);
		        update_option($optionKey , 1);
		    }
		}

		public function theme_deactivation_hook($code, $function) {
		    // store function in code specific global
		    $GLOBALS["wp_register_theme_deactivation_hook_function" . $code]=$function;

		    // create a runtime function which will delete the option set while activation of this theme and will call deactivation function provided in $function
		    $fn = @create_function('$theme', ' call_user_func($GLOBALS["wp_register_theme_deactivation_hook_function' . $code . '"]); delete_option("theme_is_activated_' . $code. '");');

		    // add above created function to switch_theme action hook. This hook gets called when admin changes the theme.
		    // Due to wordpress core implementation this hook can only be received by currently active theme (which is going to be deactivated as admin has chosen another one.
		    // Your theme can perceive this hook as a deactivation hook.
		    add_action("switch_theme", $fn);
		}
	}
	WD_Packages::get_instance();
}