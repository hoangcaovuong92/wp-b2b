<?php
if (!class_exists('WD_Instagram')) {
	class WD_Instagram {
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

		protected $taxonomy 			= '';
		protected $arrShortcodes 		= array('instagram', 'instagram_masonry', 'instagram_snapppt');
		protected $number_widget_area 	= 7;

		public function __construct(){
			$this->constant();
			
			require_once( WDINS_BASE . '/wd_functions.php' ); 

			add_action('admin_enqueue_scripts',array($this,'init_admin_script'));

			//Include Lybrary
			add_action('template_redirect', array($this,'template_redirect') );
			
			$this->initWidgets();

			//Visual Composer
			$this->initShortcodes();
			if($this->checkPluginVC()){
				if ( ! defined( 'ABSPATH' ) ) { exit; }
				add_action("vc_after_init",array($this,'initVisualComposer'));
			}
		}
		
		protected function constant(){
			define('WDINS_BASE'		,   plugin_dir_path( __FILE__ )		);
			define('WDINS_BASE_URI'	,   plugins_url( '', __FILE__ )		);
			define('WDINS_JS'		, 	WDINS_BASE_URI . '/assets/js'	);
			define('WDINS_CSS'		, 	WDINS_BASE_URI . '/assets/css'	);
			define('WDINS_ADMIN_JS'	, 	WDINS_BASE_URI . '/admin/js'	);
			define('WDINS_ADMIN_CSS', 	WDINS_BASE_URI . '/admin/css'	);
			define('WDINS_ADMIN_LIB', 	WDINS_BASE_URI . '/admin/libs'	);
			define('WDINS_IMAGE'	, 	WDINS_BASE_URI . '/images'		);
			define('WDINS_TEMPLATE' , 	WDINS_BASE . '/templates'		);
			define('WDINS_WIDGET' 	, 	WDINS_BASE . '/widgets'			);
		}

		
		/******************************** testimonials POST TYPE INIT START ***********************************/
		public function template_redirect(){
			global $wp_query,$post,$page_datas,$data;
			//if( $wp_query->is_page() || $wp_query->is_single() ){
				//if ( has_shortcode( $post->post_content, 'wd_megamenu' ) ) { 
					add_action('wp_enqueue_scripts',array($this,'init_script'));
				//}
			//}
		}
	
		protected function initShortcodes(){
			foreach($this->arrShortcodes as $shortcode){
				if( file_exists(WDINS_TEMPLATE."/wd_{$shortcode}.php") ){
					require_once WDINS_TEMPLATE."/wd_{$shortcode}.php";
				}	
			}
		}

		public function initVisualComposer(){ 
			foreach ($this->arrShortcodes as $visual) {
				if( file_exists(WDINS_TEMPLATE."/wd_vc_{$visual}.php") ){
					require_once WDINS_TEMPLATE."/wd_vc_{$visual}.php";
				}
			}
	    } 

	    protected function initWidgets(){
			foreach($this->arrShortcodes as $widget){
				if( file_exists(WDINS_WIDGET."/wd_{$widget}_widget.php") ){
					require_once WDINS_WIDGET."/wd_{$widget}_widget.php";
				}	
			}
		}
		
		public function init_admin_script($hook) {
			$screen = get_current_screen();
			//if ($hook = 'nav-menus.php' || $this->post_type == $screen->post_type) {
				wp_enqueue_style('wd-instagram-admin-custom-css'	 	, 	WDINS_ADMIN_CSS.'/wd_admin.css');
				wp_enqueue_script('wd-instagram-admin-custom-scripts'	,	WDINS_ADMIN_JS.'/wd_script.js',false,false,true);
			//}
		}
		
		public function init_script(){
			wp_enqueue_style('wd-instagram-custom-css'		, WDINS_CSS.'/wd_style.css');	
			//wp_enqueue_style('magnific--css'				, WDINS_CSS.'/magnific.css');	
			wp_enqueue_script('instafeed-scripts'			, WDINS_JS.'/instafeed.min.js',false,false,true);
			//wp_enqueue_script('magnific-scripts'			, WDINS_JS.'/magnific.min.js',false,false,true);
			wp_enqueue_script('wd-instagram-custom-scripts'	, WDINS_JS.'/wd_script.js',false,false,true);
		}

		/******************************** Check Visual Composer active ***********************************/
		protected function checkPluginVC(){
			$_active_vc = apply_filters('active_plugins',get_option('active_plugins'));
			if(in_array('js_composer/js_composer.php',$_active_vc)){
				return true;
			}else{
				return false;
			}
		}

	}
	WD_Instagram::get_instance();  // Start an instance of the plugin class 
}