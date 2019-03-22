<?php
if (!class_exists('WD_Packages_Admin_Page')) {
	class WD_Packages_Admin_Page {
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

		protected $parkage_name = 'admin_page';

		public function __construct(){
			if ($this->get_current_user_role() !== 'administrator') return;
			$this->constant();
			
			$this->init_templates();
			$this->do_optimize_settings();
				
			add_action('admin_menu', array($this, 'admin_page_register'));
			add_action('admin_enqueue_scripts', array( $this, 'admin_init_script' ));
		}
		
		protected function constant(){
			define('WDADMIN_BASE'		,   plugin_dir_path( __FILE__ ));
			define('WDADMIN_BASE_URI'	,   plugins_url( '', __FILE__ ));
			
			define('WDADMIN_JS'			, 	WDADMIN_BASE_URI . '/js'		);
			define('WDADMIN_CSS'		, 	WDADMIN_BASE_URI . '/css'		);
			define('WDADMIN_IMAGE'		, 	WDADMIN_BASE_URI . '/images'	);
			define('WDADMIN_TEMPLATE'	, 	WDADMIN_BASE . '/templates'	);
			define('WDADMIN_TEMPLATE_URI'	, 	WDADMIN_BASE_URI . '/templates'	);
		}
		/******************************** INIT START ***********************************/
		public function do_optimize_settings() {
			if (file_exists(WDADMIN_BASE.'/optimize.php')) {
				require_once WDADMIN_BASE.'/optimize.php';
			}
		}

		protected function init_templates(){
			$template_file = array(
				'packages', 
				'activation', 
				'plugins', 
				'pages', 
				'sidebar',
				'templates',
			);
			foreach($template_file as $file){
				if(file_exists(WDADMIN_TEMPLATE. "/{$file}.php")){
					require_once WDADMIN_TEMPLATE. "/{$file}.php";
				}
			}
		}

		public function get_current_user_role( $user = null ) {
			$user = $user ? new WP_User( $user ) : wp_get_current_user();
			return $user->roles ? $user->roles[0] : false;
		}
		
		public function admin_page_register(){
			add_menu_page( //or add_theme_page
				esc_html__('WD Templates', 'wd_package'),     // page title
				esc_html__('WD Templates', 'wd_package'),     // menu title
				'manage_options',   // capability
				'wd-post-type-panel',     // menu slug
				'', // callback function
				WDADMIN_IMAGE.'/icon.png', //icon (dashicons-universal-access-alt)
				23 //position
			);
		}

		public static function setting_page_tabs(){
			$list_tabs = array();
			if (class_exists('WD_Package_Settings')) {
				$list_tabs[] = array(
					'slug' => 'wd-package-setting',
					'url' => admin_url('admin.php?page=wd-package-setting'),
					'title' => esc_html__("Package Settings", 'wd_package')
				);
			}

			if (class_exists('WD_Activation')) {
				$list_tabs[] = array(
					'slug' => 'wd-active-theme',
					'url' => admin_url('admin.php?page=wd-active-theme'),
					'title' => esc_html__("Theme Activation", 'wd_package')
				);
			}

			if (class_exists('WD_Plugin_Installation')) {
				$list_tabs[] = array(
					'slug' => 'wd-plugin-setting',
					'url' => admin_url('admin.php?page=wd-plugin-setting'),
					'title' => esc_html__("Required Plugins", 'wd_package')
				);
			}

			if (class_exists('WD_Page_Template')) {
				$list_tabs[] = array(
					'slug' => 'wd-page-setting',
					'url' => admin_url('admin.php?page=wd-page-setting'),
					'title' => esc_html__("Page Templates", 'wd_package')
				);
			}

			if (class_exists('WD_Sidebar_Installation')) {
				$list_tabs[] = array(
					'slug' => 'wd-sidebar-setting',
					'url' => admin_url('admin.php?page=wd-sidebar-setting'),
					'title' => esc_html__("Widget Templates", 'wd_package')
				);
			}

			if (class_exists('WD_Template_Parts')) {
				$list_tabs[] = array(
					'slug' => 'wd-template-part-setting',
					'url' => admin_url('admin.php?page=wd-template-part-setting'),
					'title' => esc_html__("Other Templates", 'wd_package')
				);
			}

			$active_tab = isset($_GET['page']) ? $_GET['page'] : 'wd-package-setting';
			if (count($list_tabs)) {
				echo '<h2 class="nav-tab-wrapper">';
				foreach ($list_tabs as $tab) {
					$active_class = $active_tab == $tab['slug'] ? 'nav-tab-active' : '';
					echo '<a href="'.$tab['url'].'" class="nav-tab '.$active_class.'">'.$tab['title'].'</a>';
				}
				echo '</h2>';
			} 
		}

		//****************************************************************//
		/*							STYLE/SCRIPTS						  */
		//****************************************************************//
		public function admin_init_script($hook){
			if ($hook == 'toplevel_page_wd-package-setting' || $hook == 'wd-settings_page_wd-page-setting' || $hook = 'wd-settings_page_wd-sidebar-setting'|| $hook = 'wd-settings_page_wd-plugin-setting') {
				global $wp_query;
				$ajax_object_vars = array(
					'ajax_url' 		=> admin_url( 'admin-ajax.php' ),
					'query_vars'	=> json_encode( $wp_query->query )
				);
				wp_enqueue_style('wd-package-admin-page-css', 	WDADMIN_CSS.'/style.css');
				wp_enqueue_script('wd-package-admin-page-ajax-js', 	WDADMIN_JS.'/ajax.js', array('jquery'), false, true);
				wp_localize_script('wd-package-admin-page-ajax-js', 	'ajax_object', $ajax_object_vars);
			}
		}
	}
	WD_Packages_Admin_Page::get_instance();  // Start an instance of the plugin class 
}

