<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

// Usage: Add following lines to functions.php:
// if(file_exists(get_template_directory()."/framework/abstract.php")){
//     require_once get_template_directory()."/framework/abstract.php";
// }

if (!class_exists('WD_Theme_Abstract')) {
	class WD_Theme_Abstract{
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

		//Variable
		protected $theme_name		= 'feellio';
		protected $theme_slug		= 'feellio';

		protected $arr_functions 	= array();
		
		//Constructor
		public function __construct(){
			$this->constant();
			$this->init_arr_functions();
			$this->init_functions();
			$this->init_theme_options();
			add_action('after_setup_theme', array($this, 'after_setup_theme'));
		}

		// Constant
		protected function constant(){			
			// Default
			if (!defined('WD_DS')) define('WD_DS', 										DIRECTORY_SEPARATOR);	
			if (!defined('WD_THEME_NAME')) define('WD_THEME_NAME', 						$this->theme_name );
			if (!defined('WD_THEME_DIR')) define('WD_THEME_DIR', 						get_template_directory());
			if (!defined('WD_THEME_URI')) define('WD_THEME_URI'	, 						get_template_directory_uri());
			if (!defined('WD_THEME_ASSET_URI')) define('WD_THEME_ASSET_URI', 			WD_THEME_URI.'/assets');

			// Style-Script-Image
			if (!defined('WD_THEME_IMAGES')) define('WD_THEME_IMAGES', 					WD_THEME_ASSET_URI.'/images');
			if (!defined('WD_THEME_CSS')) define('WD_THEME_CSS', 						WD_THEME_ASSET_URI.'/css');
			if (!defined('WD_THEME_JS')) define('WD_THEME_JS', 							WD_THEME_ASSET_URI.'/js');
			if (!defined('WD_THEME_EXTEND_LIBS')) define('WD_THEME_EXTEND_LIBS', 		WD_THEME_ASSET_URI.'/libs');
			
			//Framework Theme
			if (!defined('WD_THEME_FRAMEWORK')) define('WD_THEME_FRAMEWORK', 			WD_THEME_DIR.'/framework');
			if (!defined('WD_THEME_FRAMEWORK_URI')) define('WD_THEME_FRAMEWORK_URI', 	WD_THEME_URI.'/framework');
			if (!defined('WD_THEME_PLUGINS')) define('WD_THEME_PLUGINS', 				WD_THEME_FRAMEWORK_URI.'/plugins');
			if (!defined('WD_THEME_LAYOUT')) define('WD_THEME_LAYOUT', 					WD_THEME_FRAMEWORK.'/layout');
			if (!defined('WD_THEME_LAYOUT_URI')) define('WD_THEME_LAYOUT_URI', 			WD_THEME_FRAMEWORK_URI.'/layout');

			//Theme functions
			if (!defined('WD_THEME_FUNCTIONS')) define('WD_THEME_FUNCTIONS', 			WD_THEME_FRAMEWORK.'/functions');
			if (!defined('WD_THEME_FUNCTIONS_URI')) define('WD_THEME_FUNCTIONS_URI', 	WD_THEME_FRAMEWORK_URI.'/functions');

			if (!defined('WD_THEME_STYLE_MANAGER')) define('WD_THEME_STYLE_MANAGER', 	WD_THEME_FUNCTIONS.'/style');
			if (!defined('WD_THEME_STYLE_MANAGER_URI')) define('WD_THEME_STYLE_MANAGER_URI', WD_THEME_FUNCTIONS_URI.'/style');

			if (!defined('WD_THEME_OPTIONS')) define('WD_THEME_OPTIONS', 				WD_THEME_FUNCTIONS.'/theme_options');;

			if (!defined('WD_THEME_METABOX')) define('WD_THEME_METABOX', 				WD_THEME_FUNCTIONS.'/metabox');
			if (!defined('WD_THEME_METABOX_URI')) define('WD_THEME_METABOX_URI', 		WD_THEME_FUNCTIONS_URI.'/metabox');

			if (!defined('WD_THEME_STYLE_MODE')) define('WD_THEME_STYLE_MODE', 			'sass'); //sass / xml

			if (!defined('WD_THEME_OPTION_MODE')) define('WD_THEME_OPTION_MODE', 	class_exists( 'ReduxFramework' ));
		}

		//Include Function
		protected function init_arr_functions(){
			$this->arr_functions = array(
				'theme_options/set_default',
				'global_functions',
				'style/manager',
				'required/plugins',
				'required/version',
				'set_demo',
				'timezone',
				'query',
				'main',
				'html_block',
				'set_font_list',
				'theme_options/get_data',
				'accessibility',
				'ajax',
				'breadcrumbs',
				'comments',
				'counter_views',
				'excerpt',
				'navuser',
				'myaccount',
				'menu',
				'pushmenu',
				'pagination',
				'search',
				'sidebar',
				'transient',
				'blog/manager',
				'slider',
				'metabox/manager',
			);
		}
		
		// Load File
		protected function init_functions(){
			foreach($this->arr_functions as $function){
				if(file_exists(WD_THEME_FUNCTIONS."/{$function}.php")){
					require_once WD_THEME_FUNCTIONS."/{$function}.php";
				}	
			}
		}

		protected function init_theme_options(){
			if ( ! class_exists( 'ReduxFramework' ) ) return;
			if(file_exists(WD_THEME_OPTIONS. "/manager.php")){
				require_once WD_THEME_OPTIONS. "/manager.php";
			}
		}

		// Function Setup Theme
		public function after_setup_theme(){
			//After setup theme
			global $content_width;
		    if ( !isset($content_width) ) {
		        $content_width = 1170;
		    }
			//Make theme available for translation
			//Translations can be filed in the /languages/ directory
   			load_theme_textdomain('feellio', get_template_directory() . '/languages');
   			//Import Theme Support
   			$this->theme_support();
   			//Import Script / Style
			add_action('wp_enqueue_scripts', array($this,'enqueue_scripts'));
			add_action('admin_enqueue_scripts', array($this,'admin_enqueue_scripts'));
		}

		//Theme Support
		public function theme_support(){
			// Enable support for Post Formats.
    		add_theme_support('post-formats', array('gallery', 'video', 'audio', 'quote'));
			add_theme_support('title-tag');
			add_theme_support('automatic-feed-links');
			add_theme_support('woocommerce');
			add_theme_support('post-thumbnails');
			add_theme_support('editor-styles');
			add_theme_support('wp-block-styles');
			
			//Add Image Size
			set_post_thumbnail_size( 640, 440, true );
			add_image_size('wd_image_size_thumbnail'		, 150, 90,	true);
			add_image_size('wd_image_size_medium'			, 420, 250,	true);
			add_image_size('wd_image_size_large'			, 780, 465,	true);
			add_image_size('wd_image_size_cart_dropdown' 	, 150, 150, true);
			add_image_size('wd_image_size_square_small' 	, 300, 300, true);
			add_image_size('wd_image_size_square_medium' 	, 500, 500, true);
			add_image_size('wd_image_size_square_large' 	, 700, 700, true);

			/* Update woocommerce image size */
			//Catalog Image
			update_option( 'shop_catalog_image_size', array('width'=>'450', 'height' => '577', 'crop' => 1 ));
			//Single Image
			update_option( 'shop_single_image_size', array('width'=>'560', 'height' => '716', 'crop' => 1 ));
			//Thumbnail Image
			update_option( 'shop_thumbnail_image_size', array('width'=>'94', 'height' => '94', 'crop' => 1 ));
		}

		//Enqueue Style And Script
		public function enqueue_scripts(){
			global $wp_query, $wd_theme_options;
			$ajax_object_vars = array(
				'ajax_url' 		=> admin_url( 'admin-ajax.php' ),
				'query_vars'	=> json_encode( $wp_query->query )
			);

			$responsive = array(
				'smallest' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_smallest'] : 320,
				'small' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_small'] : 375,
				'mobile' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_mobile'] : 480,
				'tablet' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_tablet'] : 768,
				'desktop' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_desktop'] : 992,
				'large' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_large'] : 1024,
				'largest' 		=> (WD_THEME_OPTION_MODE) ? $wd_theme_options['wd_responsive_largest'] : 1200,
			); 
			
			/*----------------- Style ---------------------*/
			// Wordpress Libs
			//wp_enqueue_style('jquery-ui-autocomplete');
			//wp_enqueue_style('thickbox');
			wp_enqueue_style('jquery-ui-core');

			// LIB
			wp_enqueue_style('bootstrap-core', 			WD_THEME_EXTEND_LIBS.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('font-awesome', 			WD_THEME_EXTEND_LIBS.'/font-awesome/css/font-awesome.min.css');
			wp_enqueue_style('elusive-icons', 			WD_THEME_EXTEND_LIBS.'/elusive-icons/css/elusive-icons.min.css');
			wp_enqueue_style('linearicons', 			WD_THEME_EXTEND_LIBS.'/linearicons/icon-font.css');
			wp_enqueue_style('owl-carousel-core', 		WD_THEME_EXTEND_LIBS.'/owl-carousel/css/owl.carousel.min.css');
			wp_enqueue_style('cloud-zoom-core', 		WD_THEME_EXTEND_LIBS.'/cloud-zoom/css/cloud-zoom.css');
			wp_enqueue_style('slick-core', 				WD_THEME_EXTEND_LIBS.'/slick/slick.css');
			wp_enqueue_style('slick-theme-css', 		WD_THEME_EXTEND_LIBS.'/slick/slick-theme.css');
			wp_enqueue_style('fancybox-css', 			WD_THEME_EXTEND_LIBS.'/fancybox/jquery.fancybox.css');
			wp_enqueue_style('select2-core',			WD_THEME_EXTEND_LIBS.'/select2/css/select2.min.css'); 
			wp_enqueue_style('jquery-timepicker', 		WD_THEME_EXTEND_LIBS.'/jquery-timepicker/jquery.timepicker.min.css');

			// CSS OF THEME
			wp_enqueue_style('wd-theme-main-css', 		WD_THEME_URI.'/style.css');
			wp_enqueue_style('wd-theme-base-css', 		WD_THEME_CSS.'/base.css');
			wp_enqueue_style('wd-custom-style-inline-css', WD_THEME_CSS.'/wd_print_inline_style.css');
			wp_enqueue_style('wd-update-css', 			WD_THEME_CSS.'/wd_update.css');

			/*----------------- Script ---------------------*/
			// Wordpress Libs
			wp_enqueue_script('jquery');
			//wp_enqueue_script('jquery-ui-autocomplete');
			wp_enqueue_script('jquery-ui-core');
			//wp_enqueue_script('thickbox');

			// LIBS
			wp_enqueue_script('bootstrap-core', 		WD_THEME_EXTEND_LIBS.'/bootstrap/js/bootstrap.min.js', array('jquery'), false, true);

			wp_enqueue_script('isotope-pkgd',			WD_THEME_EXTEND_LIBS.'/isotope-pkgd/js/isotope.pkgd.min.js', array('jquery'), false, true);
			//wp_enqueue_script('masonry-pkgd',			WD_THEME_EXTEND_LIBS.'/masonry-pkgd/masonry.pkgd.min.js', array('jquery'), false, true);
			//wp_enqueue_script('imagesloaded-pkgd',		WD_THEME_EXTEND_LIBS.'/masonry-pkgd/imagesloaded.pkgd.min.js', array('jquery'), false, true);

			wp_enqueue_script('owl-carousel-core', 		WD_THEME_EXTEND_LIBS.'/owl-carousel/js/owl.carousel.min.js', array('jquery'), false, true);
			wp_enqueue_script('slick-core', 			WD_THEME_EXTEND_LIBS.'/slick/slick.min.js', array('jquery'), false, true);

			wp_enqueue_script('fancybox-js', 			WD_THEME_EXTEND_LIBS.'/fancybox/jquery.fancybox.pack.js', array('jquery'), false, true);
			wp_enqueue_script('select2-core', 			WD_THEME_EXTEND_LIBS.'/select2/js/select2.min.js',false,false,true);

			wp_enqueue_script('cloud-zoom-core', 		WD_THEME_EXTEND_LIBS.'/cloud-zoom/js/cloud-zoom.1.0.2.js', array('jquery'), false, true);
			wp_enqueue_script('jquery-validate-core', 	WD_THEME_EXTEND_LIBS.'/jquery-validate/js/jquery.validate.min.js', array('jquery'), false, true);
			wp_enqueue_script('jquery-timepicker', 		WD_THEME_EXTEND_LIBS.'/jquery-timepicker/jquery.timepicker.min.js', array('jquery'), false, true);
		
			// JS OF THEME
		    wp_enqueue_script('wd-validate-form-js'	, 	WD_THEME_JS.'/wd_validate_form.js', array('jquery', 'jquery-validate-core'), false, true);
			wp_enqueue_script('wd-menu-js', 			WD_THEME_JS.'/wd_menu.js', array('jquery'), false, true);
			wp_localize_script('wd-menu-js', 			'responsive', $responsive);

			wp_enqueue_script('wd-main-js', 			WD_THEME_JS.'/wd_main.js', array('jquery'), false, true);
			wp_localize_script('wd-main-js', 			'responsive', $responsive);

			wp_enqueue_script('wd-ajax-js', 			WD_THEME_JS.'/wd_ajax.js', array('jquery'), false, true);
			wp_localize_script('wd-ajax-js', 			'ajax_object', $ajax_object_vars);

			wp_enqueue_script('wd-slider-js', 			WD_THEME_JS.'/wd_slider.js', array('jquery'), false, true);
			wp_localize_script('wd-slider-js', 			'responsive', $responsive);

			wp_enqueue_script('wd-custom-script-inline-js', WD_THEME_JS.'/wd_print_inline_script.js', array('jquery'), false, true);
			wp_enqueue_script('wd-update-js', 			WD_THEME_JS.'/wd_update.js', array('jquery'), false, true);
			
		    if (is_singular() && comments_open()) { 
		    	wp_enqueue_script('comment-reply'); 
		    }
		}

		public function admin_enqueue_scripts(){
			wp_enqueue_media();
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style('wd-main-admin-css', 		WD_THEME_CSS.'/wd_admin.css');
			wp_enqueue_style('jquery-timepicker', 		WD_THEME_EXTEND_LIBS.'/jquery-timepicker/jquery.timepicker.min.css');

			wp_enqueue_script('jquery-timepicker', 		WD_THEME_EXTEND_LIBS.'/jquery-timepicker/jquery.timepicker.min.js', array('jquery'), false, true);
		}
	}
	WD_Theme_Abstract::get_instance();
}