<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

 if (!class_exists('WD_Load_Custom_Style')) {
	class WD_Load_Custom_Style {
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

			add_action( 'wp_enqueue_scripts', array($this, 'load_custom_style') , 10000);
		}

		public function load_custom_style(){
			/* Breadcrumb */
			$custom_style = apply_filters('wd_filter_breadcrumb_style', true);
			/* 404 page */
			$custom_style .= $this->page_404_style();
			/* Search page */
			$custom_style .= apply_filters('wd_filter_search_page_style', true);
			
			/* Back To Top Button */
			$custom_style .= apply_filters('wd_filter_back_to_top_style', true);
			/* Background Css */
			$custom_style .= $this->background_style();

			if (WD_THEME_STYLE_MODE === 'xml') {
				$custom_style .= apply_filters('wd_filter_xml_style', true);
			}

			/* Style from HTML Block */
			$custom_style .= apply_filters('wd_filter_html_block_style', true);;

			/* Custom Css from Theme Option */
			//$custom_style .= $this->theme_option_custom_styles();
			
			$custom_script = 'jQuery(window).ready(function($) {';
			
			/* Script from Theme Option */
			$custom_script .= $this->theme_option_custom_scripts();
			$custom_script .= '})';
		
			
			/******************ENQUEUE STYLE*******************/
			wp_add_inline_style( 'wd-custom-style-inline-css', $this->minify_css($custom_style) );
		
			/******************ENQUEUE SCRIPT*******************/
			wp_add_inline_script( 'wd-custom-script-inline-js', $this->minify_js($custom_script) );
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
		
		// Minify JS
		public function minify_js( $content ) {
			$content = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $content);
			$content = str_replace(["\r\n","\r","\t","\n",'  ','    ','     '], '', $content);
			$content = preg_replace(['(( )+\))','(\)( )+)'], ')', $content);
			return $content;
		}
		
		/** Get custom background css */
		public function background_style() {
			/**
			 * package: background
			 * var: bg_display
			 * var: bg_image
			 */
			extract(apply_filters('wd_filter_get_data_package', 'background' ));
			$custom_css 	= '';
			if ($bg_display && isset($bg_config) && !empty($bg_config['background-image'])) {
				$custom_css 	.= 'html body {';
				$custom_css 	.= 'background-image: url("'.esc_url($bg_config['background-image']).'");';
				$custom_css 	.= 'background-repeat: '.esc_attr($bg_config['background-repeat']).';';
				$custom_css 	.= 'background-attachment: '.esc_attr($bg_config['background-attachment']).';';
				$custom_css 	.= 'background-position: '.esc_attr($bg_config['background-position']).';';
				$custom_css 	.= 'background-size: 100%;';
				$custom_css 	.= '}';
			}
			return $custom_css;
		}
		
		/* Get custom css 404 Page */
		public function page_404_style(){
			/**
			 * package: 404
			 * var: select_style
			 * var: bg_404_url
			 * var: bg_404_color
			 * var: show_search_form
			 * var: show_back_to_home_btn
			 * var: back_to_home_btn_text
			 * var: back_to_home_btn_class
			 * var: show_header_footer
			 */
			extract(apply_filters('wd_filter_get_data_package', '404' ));
			$custom_style_404_page = '';
			if($select_style == 'bg_image'){
				$default_url_404 		= WD_THEME_IMAGES.'/bg_404.jpg';
				if ($bg_404_url != $default_url_404 && strpos($bg_404_url, home_url()) === false) {
					$bg_404_url 	= $default_url_404;
				}
				$custom_style_404_page 	.= '.wd-404-error { 
						background-image: url("'.esc_url($bg_404_url).'"); 
						background-attachment: fixed; 
						background-position: center;
					}';
				$custom_style_404_page 	.= '.wd-error-404-page-content .wd-page-title { 
						background: url("'.esc_url($bg_404_url).'"); 
						-webkit-background-clip: text; 
						background-clip: text; 
						color: transparent !important; 
						height: 100%;
						width: 100%; 
						background-repeat: no-repeat;
						background-size: cover;
					}';
			}else{
				$custom_style_404_page 	.= '.wd-404-error { background-color: '.esc_url($bg_404_color).'; }';
			}
			return $custom_style_404_page;
		}
		
		
		
		/** Get custom css from theme option */
		public function theme_option_custom_styles() {
			$custom_css = '';
			if (WD_THEME_OPTION_MODE) {
				global $wd_theme_options;
				if( $wd_theme_options['wd_custom_css'] ) {
					$custom_css .= $wd_theme_options['wd_custom_css'];
				}
			}
			return $custom_css;
		}
		
		/** Get custom script from theme option */
		public function theme_option_custom_scripts() {
			$custom_script = '';
			if (WD_THEME_OPTION_MODE) {
				global $wd_theme_options;
				if( $wd_theme_options['wd_custom_script'] ) {
					$custom_script .= $wd_theme_options['wd_custom_script'];
				}
			}
			return $custom_script;
		}
	}
	WD_Load_Custom_Style::get_instance();  // Start an instance of the plugin class 
}