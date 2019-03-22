<?php 
if (!class_exists('WD_Blocks')) {
	class WD_Blocks {
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

		public function __construct(){
			$this->constant();

			$this->functions();

			$this->widgets();
			$this->shortcodes();

			$this->gutenberg();
			//add_action('after_setup_theme', array($this, 'gutenberg'));

			if($this->checkPluginVC()){
				if ( ! defined( 'ABSPATH' ) ) { exit; }
				add_action("vc_after_init",array($this,'vc_map'));
			}
			// Register Script
			add_action('wp_enqueue_scripts', array($this, 'init_script'));
		}

		protected function constant(){
			define('WD_BLOCKS_BASE'			,   plugin_dir_path( __FILE__ ) );
			define('WD_BLOCKS_BASE_URI'		,   plugins_url( '', __FILE__ ) );
		}

		public function functions(){
			if( file_exists(WD_BLOCKS_BASE.'/functions/ajax.php') ){
				require_once WD_BLOCKS_BASE.'/functions/ajax.php';
			}
			if( file_exists(WD_BLOCKS_BASE.'/classes/tweetphp/TweetPHP.php') ){
				require_once WD_BLOCKS_BASE.'/classes/tweetphp/TweetPHP.php';
			}
			if( file_exists(WD_BLOCKS_BASE.'/classes/class-rest.php') ){
				require_once WD_BLOCKS_BASE.'/classes/class-rest.php';
			}
		}

        public function gutenberg(){
			if( file_exists(WD_BLOCKS_BASE.'/gutenberg/manager.php') ){
				require_once WD_BLOCKS_BASE.'/gutenberg/manager.php';
			}	
		}

		protected function initArrShortcodes(){
			return array(
				'wd_title',
				'wd_logo',
				'wd_profile',
				'wd_copyright',
				'wd_do_shortcode',
				'wd_myaccount_form',
				'wd_social_icons',
				'wd_social_fanpage_likebox',

				'wd_buttons',
				'wd_banner_image',
				'wd_banner_slider',
				'wd_text_slider',
				'wd_twitter_slider',

				'wd_blog_search',
				'wd_blog_layout',
				'wd_blog_special',
				'wd_blog_slider',

				//Nav user
				'wd_nav_user_icons',
				'wd_myaccount_icon',
				'wd_search_icon',
				'wd_minicart_icon',
				'wd_wishlist_icon',

				'wd_product_search',
				'wd_product_layout',
				'wd_product_special',
				'wd_product_slider',
				'wd_product_single_category',
				'wd_product_categories',
				'wd_product_category_tabs',
				'wd_product_categories_group',
				'wd_product_categories_accordion',
				'wd_product_categories_list',
				
				'wd_gtranslate',
				'wd_count_icon',
				'wd_payment_icon',
				// 'wd_feedburner_subscription',
				'wd_pages_list',
				'wd_accordion',
				'wd_tabs',
				'wd_process_bar',
	 		);
		}

		protected function widgets(){
			foreach($this->initArrShortcodes() as $widget){
				if( file_exists(WD_BLOCKS_BASE."/widgets/{$widget}.php") ){
					require_once WD_BLOCKS_BASE."/widgets/{$widget}.php";
				}
			}	
		}

		protected function shortcodes(){
			foreach($this->initArrShortcodes() as $shortcode){
				if( file_exists(WD_BLOCKS_BASE."/shortcodes/{$shortcode}.php") ){
					require_once WD_BLOCKS_BASE."/shortcodes/{$shortcode}.php";
				}
				if (function_exists($shortcode.'_function')) {
					add_shortcode($shortcode, $shortcode.'_function');
				}
			}
		}

		public function vc_map(){ 
			foreach ($this->initArrShortcodes() as $shortcode) {
				if (function_exists($shortcode.'_vc_map')) {
					$args = call_user_func($shortcode.'_vc_map');
					if (!empty($args)) {
						vc_map(call_user_func($shortcode.'_vc_map'));
					}
				}
			}
		}

		public function init_script(){
			global $wp_query;
			//LIBS
			wp_enqueue_style('timecircles-core', 				WD_BLOCKS_BASE_URI.'/assets/libs/timecircles/css/timecircles.css');
			wp_enqueue_script('jquery-countdown-core', 			WD_BLOCKS_BASE_URI.'/assets/libs/jquery-countdown/js/jquery.countdown.min.js',array('jquery'),false,true);
			wp_enqueue_script('timecircles-core', 				WD_BLOCKS_BASE_URI.'/assets/libs/timecircles/js/timecircles.js',false,false,true);
			wp_enqueue_script('jquery-cookie-core', 			WD_BLOCKS_BASE_URI.'/assets/libs/jquery-dcjqaccordion/js/js.cookie.min.js',false,false,true);
			wp_enqueue_script('jquery-dcjqaccordion-core', 		WD_BLOCKS_BASE_URI.'/assets/libs/jquery-dcjqaccordion/js/jquery.dcjqaccordion.2.7.min.js',false,false,true);
			//wp_enqueue_script( 'jquery-animation-counter',		WD_BLOCKS_BASE_URI.'/assets/libs/jquery-animation-counter/animationCounter.min.js', array( 'jquery' ), false, true);
			wp_enqueue_script( 'jquery-smooth-circle-chart',	WD_BLOCKS_BASE_URI.'/assets/libs/jquery-smooth-circle-chart/jquery.circlechart.js', array( 'jquery' ), false, true);

			wp_enqueue_script('wd-shortcode-custom-script',		WD_BLOCKS_BASE_URI.'/assets/js/wd_shortcode.js',false,false,true);
			wp_enqueue_script('wd-shortcode-ajax-script', 		WD_BLOCKS_BASE_URI.'/assets/js/wd_ajax.js',false,false,true);
			wp_localize_script('wd-shortcode-ajax-script', 'ajax_object', array(
				'ajax_url' 			=> admin_url('admin-ajax.php'),
				'query_vars'		=> json_encode($wp_query->query)
			));
		}
		public function admin_init_script($hook){
			if ($hook != 'toplevel_page_WPDanceLaParis') {
				//wp_enqueue_style('jquery-ui-core');
				//wp_enqueue_script('jquery-ui-core');
			}
			// wp_enqueue_script( 'jquery-ui-datepicker');
			// wp_enqueue_style('datepicker-css', 					SC_CSS.'/datepicker.css');
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

	function wd_block_init() {
		return WD_Blocks::get_instance();
	}
	add_action('plugins_loaded', 'wd_block_init');
}