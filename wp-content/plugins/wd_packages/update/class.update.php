<?php
/**
 * Author: Hoang Cao Vuong
 * Contact: hoangcaovuong92@gmail.com
 * Usage: 
 * 	1/ Copy the following function to plugin index file:
 * 		public function update_library_include(){ 
 * 			if(file_exists(WD_PACKAGE."/update/class.update.php")){
 * 				require_once WD_PACKAGE.'/update/class.update.php';
 * 				wd_update_checker_init(__FILE__);
 * 			}
 * 		} 
 * 
 *  2/ Copy the following lines to the __construct() function of plugin index file. 
 * 		$this->update_library_include();		
 */

if (!class_exists('WD_Packages_Update_Checker')) {
	class WD_Packages_Update_Checker {
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

		private $theme_slug 			= 'b2b';
		private $plugin_slug 			= 'wd_packages';
		private $plugin_index_file_path = __FILE__;
		private $server_url 			= 'http://ifindsystem.com/wp-update-server/';

		private $purchase_code_option_name 			= 'wd_verify_purchase'; //option field name save to database
		private $remote_request_status_option_name 	= 'wd_check_update_remote_request'; //If = 0, remote request to server
		private $customer_info_option_name 			= 'wd_customer_info_by_purchase_code'; //Save customer information

		public function __construct($plugin_index_file_path = '', $server_url = ''){
			$this->constant();
			$this->plugin_index_file_path 	= ($plugin_index_file_path == '') ? $this->plugin_index_file_path : $plugin_index_file_path;
			$this->server_url 				= ($server_url) ? $server_url : $this->server_url;

			//Ajax check update server status
			add_action('wp_ajax_nopriv_wd_update_checker_server_status', array($this, 'ajax_wd_update_checker_server_status'));
			add_action('wp_ajax_wd_update_checker_server_status', array($this, 'ajax_wd_update_checker_server_status'));

			//Ajax check activation purchase code
			add_action('wp_ajax_nopriv_wd_check_activation_purchase_code', array($this, 'ajax_wd_check_activation_purchase_code'));
			add_action('wp_ajax_wd_check_activation_purchase_code', array($this, 'ajax_wd_check_activation_purchase_code'));

			//Notice alert
			add_action('admin_notices', array($this,'show_msg'));

			//Change update plugin notice
			$hook = "in_plugin_update_message-{$this->plugin_slug}/{$this->plugin_slug}.php";
			add_action( $hook, array($this, 'custom_update_message'), 10, 2 ); 
			//$this->include_library();
		}

		protected function constant(){
			if (!defined('WD_PACKAGE_UPDATE')) {
				define('WD_PACKAGE_UPDATE'			,   plugin_dir_path( __FILE__ ) );
				define('WD_PACKAGE_UPDATE_URI'		,   plugins_url( '', __FILE__ ) );
			}
		}

		static function include_library() {
			// if(file_exists(WD_PACKAGE_UPDATE."/class.envato.api.php")){
			// 	require_once WD_PACKAGE_UPDATE.'/class.envato.api.php';
			// 	$verify = new WD_Envato_Verify('API KEY');
			// 	var_dump($verify->verify_purchase('PURCHASE CODE'));
			// }
		}

		/**
		 * If the theme is not active with the themeforest purchase code, display the message at the admin page 
		 */
		public function custom_update_message( $plugin_data, $r ){
			if (!$this->get_purchase_code()) {
				$mess['class'] 		= 'wd-message wd-message-warning';
				$mess['message'] 	= '<strong>'.sprintf(__( 'Theme has not been activated. Click on the following link to activate the theme and enable the update function: %s', 'wd_package' ), '<a href="admin.php?page=wd-active-theme">'.__('Active Theme' ,'wd_package').'</a></strong>' );
				printf( '<div class="%1$s"><p>%2$s</p><span class="wd-message-closebtn">&times;</span></div>', esc_attr( $mess['class'] ), $mess['message'] ); 
		    }
		}

		/**
		 * Show suggested actions at admin page 
		 */
		public function show_msg(){
			$list_msg 	= array();
			$screen 	= get_current_screen();
			//Active Theme Alert
			if (!$this->get_purchase_code() && (!isset($screen->id) || $screen->id != 'wd-settings_page_wd-active-theme')) {
				$list_msg['active_mess']['class'] 	= 'notice update-nag is-dismissible';
				$list_msg['active_mess']['header'] 	= __( '', 'wd_package' );
				$list_msg['active_mess']['message'] = '<strong><span class="dashicons dashicons-admin-network"></span> '.sprintf(__( 'Theme has not been activated. Click on the following link to activate the theme and enable the update function: %s', 'wd_package' ), '<a href="admin.php?page=wd-active-theme">'.__('Click here!' ,'wd_package').'</a></strong>' );
			}

			// //Package Setting Alert
			// $package_mess = '<strong><a href="themes.php?page=wd-package-setting">' . __('Packages Manager' ,'wd_package') . '</a></strong>';
			// $package_mess .=  ' | ' . '<strong><a href="themes.php?page=install_theme_guide">'.__('Install Theme By Step' ,'wd_package').'</a></strong>';
			// $package_mess .=  (class_exists('WD_Shop_Currency_Converter')) ? ' | ' . '<strong><a href="admin.php?page=wc-settings&tab=wd_currency_switcher_setting">'.__('Set Default Currency Switcher' ,'wd_package').'</a></strong>' : '';

			// $list_msg['package_setting']['class'] 	= 'notice notice-success is-dismissible';
			// $list_msg['package_setting']['header'] 	= '<span class="dashicons dashicons-admin-generic"></span> '.__( 'All the things you might need when using the WPDance Theme:', 'wd_package' );
			// $list_msg['package_setting']['message'] = $package_mess;

			if (count($list_msg)) {
				foreach ($list_msg as $key => $mess) {
					printf( '<div class="%1$s"><strong>%2$s</strong><p>%3$s</p></div>', esc_attr( $mess['class'] ),$mess['header'], $mess['message'] );
				}
			}
		}	

		/**
		 * Not used at current version 
		 */
		public function addSecretKey($queryArgs){
		    return $queryArgs;
		}

		/**
		 * Get the activation code that the user entered at Appearance => Theme Active 
		 */
		public function get_purchase_code(){
			$verify_purchase = get_option('wd_verify_purchase');
			$verify_purchase = ( !empty($verify_purchase) ) ? $verify_purchase['purchase_code'] : false;
		    return $verify_purchase;
		}

		/**
		 * The path of the update server 
		 */
		public function get_server_url(){
			$verify_purchase = get_option($this->purchase_code_option_name);
			$server_url =  (!empty($verify_purchase) && $verify_purchase['custom_server'] != '') ? $verify_purchase['custom_server'] : $this->server_url;
			return esc_url($server_url);
		}

		//Check server Status
		public function get_curl($url){
			$status = false;
			if ($url) {
				// Open cURL channel
				$ch = curl_init();
				// Set cURL options
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				//Set the user agent
				$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
				curl_setopt($ch, CURLOPT_USERAGENT, $agent);	

				// Decode returned JSON
				$result = json_decode(curl_exec($ch), true);
				// Close Channel
				curl_close($ch);
			}
			return $result;
		}

		public function check_link_404($url){
			$result = false;
			$handle = curl_init($url);
			curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($handle,   CURLOPT_FOLLOWLOCATION, TRUE);

			/* Get the HTML or whatever is linked in $url. */
			$response = curl_exec($handle);

			/* Check for 404 (file not found). */
			$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			if($httpCode == 404) {
				$result = true;
			}
			curl_close($handle);
			return $result;
		}

		/**
		 * Using CURL For Remote Requests and do sonmethings on serverside
		 */
		public function curl_remote_request($url){
			$remote_request = get_option($this->remote_request_status_option_name, 0);
			if (!$remote_request) {
				$result = $this->get_curl($url);
				if ($result) {
					update_option($this->remote_request_status_option_name, 1);
				}
			}
		}

		//Check server Status
		public function check_server_status(){
			$url = $this->get_server_url();
			$result = $this->get_curl($url);
			return (!empty($result) && $result['connected']) ? true : false;
		}

		//Ajax check update server status
		public function ajax_wd_update_checker_server_status() {
			$result['connected'] = $this->check_server_status();
			wp_send_json_success($result);
			die(); //stop "0" from being output
		}

		public function reset_activation() {
			delete_option($this->purchase_code_option_name);
		    update_option($this->remote_request_status_option_name, 0);
		    update_option($this->customer_info_option_name, '');
		}

		//Ajax check username and purchase code
		public function ajax_wd_check_activation_purchase_code(){
			$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$purchase_code = isset($_REQUEST['purchase_code']) ? $_REQUEST['purchase_code'] : '';
			$result = array();
			if (empty($purchase_code) || empty($username)) {
				$this->reset_activation();
				$result['customer_info'] = '';
			}else{
				$customer_info = $this->check_purchase_code_from_server($username, $purchase_code);
				$result['customer_info'] = !empty($customer_info) ? $customer_info : '';
			}
			
			wp_send_json_success($result);
			die();	
		}

		public function check_purchase_code_from_server($username, $purchase_code){
			//Get customer's info from theme options
			$customer_info 	= get_option($this->customer_info_option_name);
			
			//If customer's info is not exist
			if (empty($customer_info)) {
				//Check from server
				$url = $this->server_url.'api.php?verify_purchase='.$purchase_code;
				$customer_info = $this->get_curl($url);

				//if customer's info is correct
				if (!empty($customer_info) && $customer_info['verify-purchase']['buyer'] === trim($username)) {
					//update customer's info
					update_option($this->customer_info_option_name, $customer_info);
				}else{
					if (empty($customer_info['error'])) {
						$customer_info = '';
					}
					$this->reset_activation();
				}
			}
			return $customer_info;
		}
		
		/**
		 * Get full query url 
		 */
		public function get_update_query_url($plugin_slug = '', $theme_slug = '', $type = 'plugin'){
			if (!$this->get_purchase_code()) return;

			$license_args 	= array(
				'purchase_code' => $this->get_purchase_code(), 
				'url' 			=> $_SERVER['HTTP_HOST'], 
				'server_url' 	=> esc_url($this->get_server_url()),
				'theme_slug' 	=> $theme_slug,
			);
			$license = base64_encode(serialize($license_args));
			$link 	= esc_url($this->get_server_url()).'packages/'.$theme_slug;
			$link 	= add_query_arg( 'action', 'get_metadata', $link );
			$link 	= add_query_arg( 'slug', ($type == 'theme') ? $theme_slug : $plugin_slug, $link );
			$link 	= add_query_arg( 'type', $type, $link );
			$link 	= add_query_arg( 'theme_slug', $theme_slug, $link );
			$link 	= add_query_arg( 'wd_data', $license, $link );
			
			//var_dump($link);
			//$this->curl_remote_request($link);
			return $link;
		}
		
		/**
		 * Check plugin update 
		 */
		public function plugin_update_checker($plugin_slug = '', $theme_slug = ''){
			if (!$this->get_purchase_code()) return;

			if ($plugin_slug == '') $plugin_slug = $this->plugin_slug;
			if ($theme_slug == '') $theme_slug = $this->theme_slug;

			$link = $this->get_update_query_url($plugin_slug, $theme_slug);
			//var_dump($link);
			if (!$this->check_link_404($link)) {
				if(file_exists(WD_PACKAGE_UPDATE.'/plugin-updates/plugin-update-checker.php')){
					require WD_PACKAGE_UPDATE.'/plugin-updates/plugin-update-checker.php';
					$Update_Checker = PucFactory::buildUpdateChecker($link, $this->plugin_index_file_path, 'wd_package');
					$Update_Checker->addQueryArgFilter(array($this,'addSecretKey'));
				}
			}
		}

		/**
		 * Check theme update 
		 */
		public function theme_update_checker($plugin_slug = '', $theme_slug = '') {
			if (!$this->get_purchase_code()) return;

			if ($plugin_slug == '') $plugin_slug = $this->plugin_slug;
			if ($theme_slug == '') $theme_slug = $this->theme_slug;
			$link = $this->get_update_query_url($plugin_slug, $theme_slug, 'theme');
			//var_dump($link);
			if (!$this->check_link_404($link)) {
				if(file_exists(WD_PACKAGE_UPDATE.'/theme-updates/theme-update-checker.php')){
					require WD_PACKAGE_UPDATE.'/theme-updates/theme-update-checker.php';
					$example_update_checker = new ThemeUpdateChecker($this->theme_slug,$link);
				}
			}
		}
	}
	//WD_Packages_Update_Checker::get_instance();  // Start an instance of the plugin class 
}

if( !function_exists('wd_update_checker_init') ){
	function wd_update_checker_init($plugin_slug = '', $theme_slug = '', $plugin_index_file_path = '', $server_url = '') {
	    $check_update = new WD_Packages_Update_Checker($plugin_slug, $theme_slug, $plugin_index_file_path, $server_url);
		$check_update->plugin_update_checker();
		$check_update->theme_update_checker();
		unset($check_update);
	}
}