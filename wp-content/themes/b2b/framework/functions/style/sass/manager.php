<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 
require_once 'scssphp/scss.inc.php';
use Leafo\ScssPhp\Compiler;

if (!class_exists('WD_SASS')) {
	class WD_SASS{
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		// Ensure construct function is called only once
        private static $called = false;

        private $dir            = ''; //Sass files directory
        private $sass_file      = 'style.scss'; //Mail scss file
        private $setting_file   = 'settings/_setting.scss'; //Variables file
        private $option_key     = 'wd-main-style'; //All theme option key saved
        private $dismiss_key    = 'wd-dismiss-compile-sass-notice'; //If = 1, the notice will be dismiss and compiling style will be stop
        private $settings       = array(); //Array theme option variables
        private $dev_mode       = false; //Set true to compile CSS with tool offline. 
                                        //Set false to compile CSS automaticly after Theme Option Save and add inline CSS to header.
        private $dev_domain     = 'localhost'; //The site domain of test server. Dev mode is always true on this domain 

        private $theme_option_obj_name 	= 'wd_theme_options'; //Redux Framework object
        private $pre            = 'wd_'; //Prefix character of variables

        /**
		 * @var WD_SYNC_Single_Request
		 */
		protected $process_single;

		/**
		 * @var WD_SYNC_Multiple_Request
		 */
		protected $process_all;

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

            $this->do_action();
        }
       
        public function do_action(){
            if (WD_THEME_STYLE_MODE !== 'sass') return;
            
            $this->init_setting();
            $this->sync_libs(); //compile sass in background

            //Return CSS was compiled
            add_action('wp_enqueue_scripts',array($this,'enqueue_scripts'), 9999);
            add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts'));
            if (!$this->dev_mode) {
                //Use following filter to get compiled CSS
                //$style = apply_filters('wd_filter_compile_sass', $force_reload);
                add_filter( 'wd_filter_compile_sass', array($this, 'compile'), 10, 2);

                //Notice alert after Save Theme Option
                add_action('admin_notices', array($this, 'alert_screen'));

                //Compile sass with Ajax
                add_action('wp_ajax_nopriv_wd_ajax_compile_sass', array($this, 'ajax_compile_sass'));
                add_action('wp_ajax_wd_ajax_compile_sass', array($this, 'ajax_compile_sass'));
                
                //Dismiss notice
                add_action('wp_ajax_nopriv_wd_ajax_dismiss_compile_sass_notice', array($this, 'dismiss_compile_sass_notice'));
                add_action('wp_ajax_wd_ajax_dismiss_compile_sass_notice', array($this, 'dismiss_compile_sass_notice'));
                $this->update_setting_file(true);
            }else{
                //Update settings to variables SCSS file
                //There may be an error if the server does not support "allow_url_open"
                $this->update_setting_file();
            }

            //Action after save Theme Option
			add_action('redux/options/'.$this->theme_option_obj_name.'/saved', array($this, 'theme_option_after_save'));
        }

        public function init_setting(){
            $this->dev_mode = ($_SERVER['HTTP_HOST'] === $this->dev_domain) ? true : $this->dev_mode;
            $this->dir = WD_THEME_STYLE_MANAGER.'/sass/core/';
        }
       

        //****************************************************************//
        /*							    SYNC						      */
        //****************************************************************//
        public function sync_libs() {
			$template_file = array(
                'sync/classes/wp-async-request',
                'sync/classes/wp-background-process',
                'sync/class-logger', 
				'sync/single-request', 
                'sync/multiple-request',
			);
			foreach($template_file as $file){
				if(file_exists(WD_THEME_STYLE_MANAGER."/sass/{$file}.php")){
					require_once WD_THEME_STYLE_MANAGER."/sass/{$file}.php";
				}
            }
            $this->process_single = new WD_SYNC_Single_Request();
            $this->process_all    = new WD_SYNC_Multiple_Request();
            if (!$this->dev_mode) {
                //backgroud compile process
                add_action( 'init', array( $this, 'process_handler' ) );
            }
        }

        /**
		 * Process handler
		 */
		public function process_handler() {
			$this->handle_single();
		}

		/**
		 * Handle single
		 */
		protected function handle_single() {
			if(get_option($this->dismiss_key)) return;
            $this->compile(true);
			$current_user = wp_get_current_user();
            $name = isset($current_user->data->user_login) ? $current_user->data->user_login : 'anyone!!!';
			$this->process_single->data( array( 'name' => $name ) )->dispatch();
		}

		/**
		 * Handle all
		 */
		protected function handle_all() {
			$names = $this->get_names();
			foreach ( $names as $name ) {
				$this->process_all->push_to_queue( $name );
			}
			$this->process_all->save()->dispatch();
		}

		/**
		 * Get names
		 *
		 * @return array
		 */
		protected function get_names() {
			return array();
		}

        //****************************************************************//
        /*							    LIBRARIES						  */
        //****************************************************************//

        // Include library
        public function enqueue_scripts(){
            if (!$this->dev_mode) {
                wp_enqueue_style('wd-main-style-inline-css', WD_THEME_STYLE_MANAGER_URI.'/sass/css/inline_style.css');
                //Add CSS compiled automaticly to header
                wp_add_inline_style('wd-main-style-inline-css', get_option($this->option_key));
            }else{
                wp_enqueue_style('wd-test-main-css', WD_THEME_STYLE_MANAGER_URI.'/sass/css/style.css');
            }
        }

        // Include library
        public function admin_enqueue_scripts(){
            if (!$this->dev_mode) {
                global $wp_query;
                $ajax_object_vars = array(
                    'ajax_url' 		=> admin_url( 'admin-ajax.php' ),
                    'query_vars'	=> json_encode( $wp_query->query )
                );
                //Ajax do compile SASS admin
                wp_enqueue_script('wd-sass-ajax-js', WD_THEME_STYLE_MANAGER_URI.'/sass/js/ajax.js', array('jquery'), false, true);
                wp_localize_script('wd-sass-ajax-js', 'ajax_object', $ajax_object_vars);
            }
        }

        //****************************************************************//
        /*							    NOTICE						      */
        //****************************************************************//
        // Display notice message after save Theme Options
        public function alert_content(){
            $mess['class'] 		= 'updated settings-error notice is-dismissible wd-compile-sass-notice wd-notice--fixed';
            $mess['message'] 	= sprintf(__( 'You have saved the Theme Options recently. If you have changed the color or font settings, please compile the CSS by clicking the following link: %1$s | %2$s', 'feellio' ), '<a href="#" class="button button-primary wd-ajax-compile-sass">'.__('Compile CSS' , 'feellio').'</a>', '<a href="#" class="button wd-ajax-dismiss-compile-sass-notice">'.__('Dismiss this notice' , 'feellio').'</a>' );
            return sprintf( '<div class="%1$s"><p><strong><span class="dashicons dashicons-controls-volumeon"></span> %2$s</strong></p></div>', esc_attr( $mess['class'] ), $mess['message'] );
        }
        
        public function alert_screen(){
            if (get_option($this->dismiss_key) == 1 || !$this->check_conditions()) return;
            echo $this->alert_content();
        }

        public function ajax_compile_sass() {
            wp_send_json_success(array(
                'data' => $this->compile(true)
            ));
            die();	
        }

        public function dismiss_compile_sass_notice() {
            wp_send_json_success(array(
                'data' => update_option($this->dismiss_key, 1)
            ));
            die();	
        }

        public function theme_option_after_save() {
            //Display compile CSS notice
            update_option($this->dismiss_key, 0);
        }
        
        //****************************************************************//
        /*							MAIN FUNCTIONS						  */
        //****************************************************************//
         /* Get current option mode */
		public function use_theme_option_mode(){
			$mode = defined('WD_THEME_OPTION_MODE') ? WD_THEME_OPTION_MODE : class_exists( 'ReduxFramework' );
			return $mode;
        }

        //If pass all conditions, compile can do
        public function check_conditions(){
            return (!class_exists('ReduxFramework') || empty(get_option('wd-theme-options'))) ? false : true;
        }

        //Compile SASS -> CSS
        //$force_reload : if = true, always compile new CSS
        public function compile($force_reload = false){
            if (!$this->check_option_key() || $force_reload) {
                $style = '';
                $scss = new Compiler();
                $scss->addImportPath(function($file_name) {
                    if (!file_exists($this->dir.$file_name)) return null;
                    return $this->dir.$file_name;
                });
                
                if ($this->check_conditions()) {
                    try {
                        // Will import '/sass/style.scss'
                        $style = $scss->compile($this->variables().'@import "'.$this->sass_file.'";');
                        //Update new CSS to database
                        update_option($this->option_key, $this->minify_css($style));
                    } catch (Exception $e) {
                        error_log(printf(esc_html__( 'Caught exception: %s', 'feellio' ), $e->getMessage()));
                    }
                }

                //Dismiss notice
                update_option($this->dismiss_key, 1);
            }else{
                $style = get_option($this->option_key, '');
            }
            return $this->minify_css($style);
        }

        // Minify CSS
		public function minify_css( $content ) {
			$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
			$content = str_replace(["\r\n","\r","\n","\t",'  ','    ','     '], '', $content);
			$content = preg_replace(['(( )+{)','({( )+)'], '{', $content);
			$content = preg_replace(['(( )+})','(}( )+)','(;( )*})'], '}', $content);
			$content = preg_replace(['(;( )+)','(( )+;)'], ';', $content);
			return $content;
		}

        //If css was saved to database => return true
        public function check_option_key(){
            $style = get_option($this->option_key, '');
            return $style ? true : false;
        }

        //Overwrite settings to file variables CSS
        //There may be an error if the server does not support "allow_url_open"
        //$empty_file : set true to clear all content of file, set false to update settings from Theme Options
        public function update_setting_file($empty_file = false){
            if (file_exists($this->dir.$this->setting_file)){
                //Stop write content if content is exirst
                if (!$empty_file && filesize($this->dir.$this->setting_file)) return;
                $content = $empty_file ? '' : $this->variables();

                @file_put_contents($this->dir.$this->setting_file, $content);
            }
        }

        public function default_color_setting(){
			return array(
                'color-text' => array(
                    'title' => esc_html__( 'Text', 'feellio' ),
                    'desc'  => esc_html__( 'Customize text color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'color-textBase' => '#282828',
                        'color-textLink' => '#282828',
                        'color-textLink--hover' => '#b47828',

                        'color-text-secondary' => '#969696',
                        'color-textLink-secondary' => '#969696',
                        'color-textLink-secondary--hover' => '#282828',

                        'color-textHeading' => '#282828',
                        'color-textHeading--desc' => '#282828',

                        'text-color-light' => '#ffffff',
                        'text-color-dark' => '#282828',
                        'text-color-alert' => '#f00f00',
                        'text-color-success' => '#46b450',
                        'text-color-highlight' => '#0066c0',
                    )
                ),
                'color-background' => array(
                    'title' => esc_html__( 'Background', 'feellio' ),
                    'desc'  => esc_html__( 'Customize background color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'background-body-color' => '#ffffff',
                        'background-color-white' => '#ffffff',
                        'background-color-light' => '#e7e7e7',
                        'background-color-dark' => '#282828',
                        'background-color-alert' => '#f00f00',
                        'background-color-success' => '#46b450',
                    )
                ),
                'color-border' => array(
                    'title' => esc_html__( 'Border', 'feellio' ),
                    'desc'  => esc_html__( 'Customize border color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'border-color-white' => '#ffffff',
                        'border-color-light' => '#dbdbdb',
                        'border-color-dark' => '#282828',
                        'border-color-alert' => '#f00f00',
                        'border-color-success' => '#46b450',
                    )
                ),
                'color-table' => array(
                    'title' => esc_html__( 'Table', 'feellio' ),
                    'desc'  => esc_html__( 'Customize table color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'table-heading-color' => '#ffffff',
                        'table-heading-background-color' => '#282828',
                        'table-row-odd-color' => '#282828',
                        'table-row-odd-background-color' => '#ffffff',
                        'table-row-even-color' => '#282828',
                        'table-row-even-background-color' => '#f9f9f9',
                    )
                ),
                'color-tab' => array(
                    'title' => esc_html__( 'Tabs', 'feellio' ),
                    'desc'  => esc_html__( 'Customize tabs color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'tab-nav-color' => '#898989',
                        'tab-nav-colorHover' => '#ffffff',
                        'tab-nav-backgroundColor' => '#ffffff',
                        'tab-nav-backgroundColorHover' => '#898989',
                        'tab-nav-borderColor' => '#898989',
                        'tab-nav-borderColorHover' => '#898989',
                    )
                ),
                'color-button' => array(
                    'title' => esc_html__( 'Button', 'feellio' ),
                    'desc'  => esc_html__( 'Customize button color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'button--default-color' => '#ffffff',
                        'button--default-colorHover' => '#282828',
                        'button--default-backgroundColor' => '#282828',
                        'button--default-backgroundColorHover' => '#dddddd',
                        'button--default-borderColor' => '#dddddd',
                        'button--default-borderColorHover' => '#282828',

                        'button--secondary-color' => '#ffffff',
                        'button--secondary-colorHover' => '#282828',
                        'button--secondary-backgroundColor' => '#F9B143',
                        'button--secondary-backgroundColorHover' => '#dddddd',
                        'button--secondary-borderColor' => '#dddddd',
                        'button--secondary-borderColorHover' => '#F9B143',

                        'button--disabled-color' => '#ffffff',
                        'button--disabled-backgroundColor' => '#cccccc',
                        'button--disabled-borderColor' => '#cccccc',
                    )
                ),
                'color-nav-user' => array(
                    'title' => esc_html__( 'Nav User', 'feellio' ),
                    'desc'  => esc_html__( 'Customize nav user color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'navUser-color' => '#282828',
                        'navUser-color-hover' => '#b47828',
                    )
                ),
                'color-nav-pages' => array(
                    'title' => esc_html__( 'Menu', 'feellio' ),
                    'desc'  => esc_html__( 'Customize nav pages color (menu).', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'navPages-color' => '#282828',
                        'navPages-color-hover' => '#b47828',
                        'navPages-subMenu-background-Color' => '#ffffff',
                        'navPages-subMenu-background-Color-hover' => '#dddddd',
                        'navPages-subMenu-border-Color' => '#dbe2e5',
                        'navPages-flag-new-Color' => '#33a561',
                        'navPages-flag-sale-Color' => '#8224e3',
                        'navPages-flag-hot-Color' => '#ff3366',
                    )
                ),
                'color-pagination' => array(
                    'title' => esc_html__( 'Pagination', 'feellio' ),
                    'desc'  => esc_html__( 'Customize pagination color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'pagination-color' => '#969696',
                        'pagination-color-hover' => '#141414',
                        'pagination-background-color' => '#ffffff',
                        'pagination-background-color-hover' => '#ffffff',
                    )
                ),
                'color-blog' => array(
                    'title' => esc_html__( 'Blog', 'feellio' ),
                    'desc'  => esc_html__( 'Customize blog color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                    )
                ),
                'color-product' => array(
                    'title' => esc_html__( 'Product', 'feellio' ),
                    'desc'  => esc_html__( 'Customize woocommerce/product color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'product-icon-ratingEmpty' => '#999999',
                        'product-icon-ratingFull' => '#fabe00',
                        'product-title-color' => '#282828',
                        'product-title-color-hover' => '#282828',
                        'product-price-color' => '#282828',
                        'product-price-sale-color' => '#999999',
                    )
                ),
                'color-icon' => array(
                    'title' => esc_html__( 'Icons', 'feellio' ),
                    'desc'  => esc_html__( 'Customize icons color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'countPill-color' => '#ffffff',
                        'countPill-backgroundColor' => '#282828',
                    )
                ),
                'color-footer' => array(
                    'title' => esc_html__( 'Footer', 'feellio' ),
                    'desc'  => esc_html__( 'Customize default footer color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'footer-backgroundColor' => '#ffffff',
                        'footer-color' => '#282828',
                        'footer-link-color' => '#282828',
                        'footer-link-colorHover' => '#b47828',
                        'footer-heading-color' => '#ffffff',
                        'copyright-backgroundColor' => '#ffffff',
                        'copyright-color' => '#969696',
                        'copyright-linkColor' => '#969696',
                        'copyright-linkColor-hover' => '#b47828',
                    )
                ),
                'color-dropdown' => array(
                    'title' => esc_html__( 'Dropdown', 'feellio' ),
                    'desc'  => esc_html__( 'Customize dropdown color.', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'dropdown-backgroundColor' => '#ffffff',
                        'dropdown-textColor' => '#282828',
                        'dropdown-borderColor' => '#dbdbdb',
                    )
                ),
                'color-scrollbar' => array(
                    'title' => esc_html__( 'Scrollbar', 'feellio' ),
                    'desc'  => esc_html__( 'Customize scrollbar color (large screen only).', 'feellio' ),
                    'type'  => 'color', //color, typography, text
                    'output' => 'single',
                    'data'  => array(
                        'scrollbar-backgroundColor' => '#ffffff',
                        'scrollbar-buttonColor' => '#888888'
                    )
                ),
			);
        }

        public function default_font_setting(){
			return array(
                'font-family' => array(
                    'title' => esc_html__( 'Font Family', 'feellio' ),
                    'desc'  => esc_html__( 'Customize font family.', 'feellio' ),
                    'type'  => 'typography',
                    'output' => 'font_family',
                    'group_name' => 'fontFamily',
                    'data'  => array(
                        'body-font'         => 'Arimo',
                        'headings-font'     => 'Arimo',
                        'menu-font'         => 'Arimo',
                        'logo-font'         => 'Yesteryear',
                        'blog-title-font' => 'Unna',
                    )
                ),
                'font-size' => array(
                    'title' => esc_html__( 'Font Size', 'feellio' ),
                    'desc'  => esc_html__( 'Customize font size.', 'feellio' ),
                    'type'  => 'text',
                    'output' => 'group',
                    'group_name' => 'fontSize',
                    'data'  => array(
                        "root"    => '14px',
                        "base"    => '1rem',
                        "hero"    => '50px', 
                        "largest" => '36px',
                        "larger"  => '30px',
                        "large"   => '24px',
                        "small"   => '20px',
                        "smaller" => '16px',
                        "smallest"=> '12px',
                        "tiny"    => '12px',
                    )
                ),
                'responsive' => array(
                    'title' => esc_html__( 'Responsive', 'feellio' ),
                    'desc'  => esc_html__( 'Customize screen size on different devices (Unit: px).', 'feellio' ),
                    'type'  => 'text',
                    'output' => 'responsive',
                    'group_name' => 'breakpoints',
                    'data'  => array(
                        'smallest' => 320,
                        'small'    => 375,
                        'mobile'   => 480,
                        'tablet'   => 768,
                        'desktop'  => 992,
                        'large'    => 1024,
                        'largest'  => 1200
                    )
                ),
			);
        }

        public function variables(){
            $this->settings = get_option('wd-theme-options');
            ob_start();
                $this->print_variables($this->default_color_setting());
                $this->print_variables($this->default_font_setting());
            return ob_get_clean();
        }

        //Print variables from the Theme Options with the correct format
        public function print_variables($settings = array()){
            if (is_array($settings) && count($settings) > 0) {
                foreach ($settings as $key => $data) {
                    $output = $data['output'];
                    if ($data['output'] === 'single') {
                        foreach ($data['data'] as $id => $value) {
                            echo '$'.$id.': '.$this->settings[$this->pre.$key.'_'.$id].';'.PHP_EOL;
                        }
                    }else if ($data['output'] === 'group') {
                        $group_name = $data['group_name'];
                        echo '$'.$group_name.': ('.PHP_EOL;
                            foreach ($data['data'] as $id => $value) {
                                echo '"'.$id.'": '.$this->settings[$this->pre.$key.'_'.$id].','.PHP_EOL;
                            }
                        echo ');'.PHP_EOL;
                    }else if ($data['output'] === 'font_family') {
                        $default_font = '"Helvetica Neue", Helvetica, sans-serif';
                        $group_name = $data['group_name'];

                        foreach ($data['data'] as $id => $value) {
                            echo '$'.$id.': "'.$this->settings[$this->pre.$key.'_'.$id]['font-family'].'",'.$default_font.';'.PHP_EOL;
                        }
                        echo '$'.$group_name.': ('.PHP_EOL;
                            foreach ($data['data'] as $id => $value) {
                                echo '"'.$id.'": $'.$id.','.PHP_EOL;
                            }
                        echo ');'.PHP_EOL;
                    }else if ($data['output'] === 'responsive') {
                        $group_name = $data['group_name'];
                        echo '$'.$group_name.': ('.PHP_EOL;
                            foreach ($data['data'] as $id => $value) { //min-width
                                echo '"min-'.$id.'": (min-width: '.$this->settings[$this->pre.$key.'_'.$id].'px),'.PHP_EOL;
                            }
                            foreach ($data['data'] as $id => $value) { //max-width
                                echo '"max-'.$id.'": (max-width: '.($this->settings[$this->pre.$key.'_'.$id] - 1).'px),'.PHP_EOL;
                            }
                        echo ');'.PHP_EOL;
                    }
                    echo PHP_EOL;
                }
            }
        }
        
        public function string_to_name($str){
            $str = str_replace(array('-', '--'), ' ', $str);
            $str = str_replace('_', '/', $str);
            $str = ucwords($str);
            return $str;
        }

        /* Usage: display list theme color options field
		-----------
		$manager = WD_SASS::get_instance();
		$manager->options_color_fields($wp_customize);
		-----------
		File : theme_option\parts\options_color.php
        File : theme_customize\parts\wd_customize_color.php
        */
		public function options_color_fields(){
			if ($this->use_theme_option_mode()) {
                foreach ($this->default_color_setting() as $key => $data) {
                    $type = $data['type'];
                    $title = $data['title'];
                    $desc = $data['desc'] . '<br/>' . __( 'IMPORTANT: If you change the values in this section, compiling style will be run automatically or manually in the next page load.', 'feellio' );
                    $section_id = 'wd_font_setting_'.$key;
                    $font_field_array = array();

                    foreach ($data['data'] as $id => $value) {
                        $name = $this->string_to_name($id);
                        $slug = $this->pre.$key.'_'.$id;
                        $std  = $value;

                        switch ($type) {
                            case 'color':
                                $font_field_array[] = array(
                                    'id'            => $slug,
                                    'type'          => $type,
                                    'transparent'   => false,
                                    'title'         => $name,
                                    'subtitle'      => '',
                                    'default'       => $std,
                                );
                                break;

                            case 'typography':
                                $font_field_array[] = array(
                                    'id'       => $slug,
                                    'type'     => $type,
                                    'title'    => $name,
                                    'subtitle' => '',
                                    'google'   => true,
                                    'font-weight'   => false,
                                    'color'         => false,
                                    'text-align'    => false,
                                    'line-height'   => false,
                                    'font-style'    => false,
                                    'font-size'     => false,
                                    'subsets'       => false,
                                    'default'  => array(
                                        'color'       => '#dd9933',
                                        'font-size'   => '30px',
                                        'font-family' =>  $std,
                                        'font-weight' => 'Normal',
                                    ),
                                );
                                break;
                            
                            case 'text':
                                $font_field_array[] = array(
                                    'id'       => $slug,
                                    'type'     => $type,
                                    'title'    => $name,
                                    'subtitle' => '',
                                    'desc'     => '',
                                    'default'  => $std,
                                );
                                break;
                        }
                    }

                    Redux::setSection( $this->theme_option_obj_name, array(
                        'title'            => $title,
                        'id'               => $section_id,
                        'subsection'       => true,
                        'customizer_width' => '450px',
                        'desc'             => $desc,
                        'fields'           => $font_field_array
                    ) );
                }
			}
        }
        
        /* Usage: display list theme font options field
		-----------
		$manager = WD_SASS::get_instance();
		$manager->options_font_fields($wp_customize);
		-----------
		File : theme_option\parts\options_font.php
        File : theme_customize\parts\wd_customize_font.php
        */
		public function options_font_fields(){
			if ($this->use_theme_option_mode()) {
                foreach ($this->default_font_setting() as $key => $data) {
                    $type = $data['type'];
                    $title = $data['title'];
                    $desc = $data['desc'] . '<br/>' . __( 'IMPORTANT: If you change the values in this section, compiling style will be run automatically or manually in the next page load.', 'feellio' );
                    $section_id = 'wd_font_setting_'.$key;
                    $font_field_array = array();

                    foreach ($data['data'] as $id => $value) {
                        $name = str_replace(array('-', '--'), ' ', $id);
                        $name = str_replace('_', '/', $name);
                        $name = ucwords($name);
                        $slug = $this->pre.$key.'_'.$id;
                        $std  = $value;

                        switch ($type) {
                            case 'color':
                                $font_field_array[] = array(
                                    'id'            => $slug,
                                    'type'          => $type,
                                    'transparent'   => false,
                                    'title'         => $name,
                                    'subtitle'      => '',
                                    'default'       => $std,
                                );
                                break;

                            case 'typography':
                                $font_field_array[] = array(
                                    'id'       => $slug,
                                    'type'     => $type,
                                    'title'    => $name,
                                    'subtitle' => '',
                                    'google'   => true,
                                    'font-weight'   => false,
                                    'color'         => false,
                                    'text-align'    => false,
                                    'line-height'   => false,
                                    'font-style'    => false,
                                    'font-size'     => false,
                                    'subsets'       => false,
                                    'default'  => array(
                                        'color'       => '#dd9933',
                                        'font-size'   => '30px',
                                        'font-family' =>  $std,
                                        'font-weight' => 'Normal',
                                    ),
                                );
                                break;
                            
                            case 'text':
                                $font_field_array[] = array(
                                    'id'       => $slug,
                                    'type'     => $type,
                                    'title'    => $name,
                                    'subtitle' => '',
                                    'desc'     => '',
                                    'default'  => $std,
                                );
                                break;
                        }
                    }

                    Redux::setSection( $this->theme_option_obj_name, array(
                        'title'            => $title,
                        'id'               => $section_id,
                        'subsection'       => true,
                        'customizer_width' => '450px',
                        'desc'             => $desc,
                        'fields'           => $font_field_array
                    ) );
                }
			}
		}
	}
	WD_SASS::get_instance();  // Start an instance of the plugin class 
}