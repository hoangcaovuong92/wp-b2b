<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 

if (!class_exists('WD_Demo_Data')) {
	class WD_Demo_Data {
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

			// $demo_image_id = apply_filters('wd_filter_demo_image', true);
			add_filter( 'wd_filter_demo_image', array($this, 'get_demo_image' ), 10, 2);

			// do_action('wd_hook_default_social_links_header');
			add_action( 'wd_hook_default_social_links_header', array($this, 'default_social_icons_header' ), 5);

			// do_action('wd_hook_default_social_links_footer');
			add_action( 'wd_hook_default_social_links_footer', array($this, 'default_social_icons_footer' ), 5);

			// do_action('wd_hook_default_instagram');
			add_action( 'wd_hook_default_instagram', array($this, 'default_instagram' ), 5);
		}

		//****************************************************************//
		/*							IMGAE DEMO							  */
		//****************************************************************//
		//Return attachment id
		public function get_demo_image(){
			$attachment_id = $this->check_img_demo_exist() ? $this->check_img_demo_exist() : $this->add_new_demo_image();
			update_option('wd-image-demo', $attachment_id);
			return $attachment_id;
		}

		public function add_new_demo_image(){
			$file = WD_THEME_IMAGES.'/demo.jpg';
			$filename = basename($file);
			$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
			if (!$upload_file['error']) {
				$wp_filetype = wp_check_filetype($filename, null );
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attachment_id = wp_insert_attachment( $attachment, $upload_file['file']);
				if (!is_wp_error($attachment_id)) {
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					wp_update_attachment_metadata( $attachment_id,  $attachment_data );
				}
			}

			return $attachment_id;
		}

		//Return attachment ID if true
		public function check_img_demo_exist(){
			$attachment_id = get_option('wd-image-demo', '') ? get_option('wd-image-demo', '') : false;
			$result = (wp_attachment_is_image($attachment_id)) ? $attachment_id : false;
			return $result;
		}

		//****************************************************************//
		/*						SHORTCODE DEMO							  */
		//****************************************************************//
		public function default_social_icons_header(){
			if (function_exists('wd_social_icons_function')) {
				/**
				 * package: social-link
				 * var: rss_id
				 * var: twitter_id
				 * var: facebook_id
				 * var: google_id
				 * var: pin_id
				 * var: youtube_id
				 * var: instagram_id
				 */
				$atts = apply_filters('wd_filter_get_data_package', 'social-link');
				$atts['style'] = 'nav-user';
				$atts['show_title'] = false;
				echo wd_social_icons_function($atts);
			}
		}

		public function default_social_icons_footer(){
			if (function_exists('wd_social_icons_function')) {
				/**
				 * package: social-link
				 * var: rss_id
				 * var: twitter_id
				 * var: facebook_id
				 * var: google_id
				 * var: pin_id
				 * var: youtube_id
				 * var: instagram_id
				 */
				$atts = apply_filters('wd_filter_get_data_package', 'social-link');
				$atts['style'] = 'horizontal';
				$atts['show_title'] = true;
				$atts['item_align'] = 'wd-flex-justify-center';
				echo wd_social_icons_function($atts);
			}
		}

		public function default_instagram(){
			if (function_exists('wd_instagram_function')) {
				/**
				 * package: instagram
				 * var: insta_user
				 * var: insta_client_id
				 * var: insta_access_token
				 */
				$atts = apply_filters('wd_filter_get_data_package', 'instagram');
				$atts['insta_title'] = esc_html__( 'FOLLOW @ INSTAGRAM' , 'feellio');
				$atts['insta_style'] = 'style-insta-2';
				$atts['insta_hover_style'] = 'wd-insta-hover-style-2';
				$atts['insta_follow'] = 0;
				$atts['insta_columns'] = 6;
				$atts['insta_number'] = 6;
				$atts['insta_padding'] = 'none';
				$atts['insta_size'] = 'standard_resolution';
				echo wd_instagram_function($atts);
			}
		}
	}
	WD_Demo_Data::get_instance();  // Start an instance of the plugin class 
}