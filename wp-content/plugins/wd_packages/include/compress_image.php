<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 

if (!class_exists('WD_Compress_Images')) {
	class WD_Compress_Images {
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

		protected $allow_image_resize = true, //Automatically resizes uploaded images (JPEG, GIF, and PNG)
				$image_re_compression = true, //Force JPEG re-compression
				$compression_level = 30, //(=90%)
				$max_width  = '', //leave blank to ignore or enter number (Unit: px. Recommended values: 1000)
				$max_height = '', //leave blank to ignore or enter number (Unit: px. Recommended values: 1000)
				$image_upload_limit_size = ''; //Limit Image Size in WordPress Media Library. Ex: 2000 = 2MB

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;

			$this->add_actions();
		}

		public function add_actions() {
			add_action('upload_mimes', array($this, 'custom_upload_mimes'));
			add_filter('wp_handle_upload_prefilter', array($this, 'validate_image_limit'));
			add_action('wp_handle_upload', array($this, 'upload_resize'));
		}

		public function custom_upload_mimes($mimes = array()) {
			// Add a key and value for the CSV file type
			$mimes['csv'] = "text/csv";
			$mimes['webp'] = "image/webp";
			return $mimes;
		}

		public function validate_image_limit( $file ) {
			if ($this->image_upload_limit_size){
				$image_size = $file['size'] / 1024;
				$limit = esc_textarea($this->image_upload_limit_size);
				$is_image = strpos($file['type'], 'image');
				if ( ( $image_size > $limit ) && ($is_image !== false) ) {
					$file['error'] = __( 'Your picture is too large. It has to be smaller than ', 'vnex' ) . ''. $limit .'KB';
				}
			}
			return $file;
		}
		
		function upload_resize ($image_data){
			error_log("**-start--resize-image-upload");
			if($this->image_re_compression) {
				$fatal_error_reported = false;
				$valid_types = array('image/gif','image/png','image/jpeg','image/jpg');
				if(empty($image_data['file']) || empty($image_data['type'])) {
					error_log("--non-data-in-file");
					$fatal_error_reported = true;
				} else if(!in_array($image_data['type'], $valid_types)) {
					error_log("--non-image-type-uploaded-( ".$image_data['type']." )");
					$fatal_error_reported = true;
				}
				error_log("--filename-( ".$image_data['file']." )");
				$image_editor = wp_get_image_editor($image_data['file']);
				$image_type = $image_data['type'];

				if($fatal_error_reported || is_wp_error($image_editor)) {
					error_log("--wp-error-reported");
				} else {
					$to_save = false;
					$resized = false;
					if($this->allow_image_resize) {
						error_log("--resizing-enabled");
						$sizes = $image_editor->get_size();
						if((isset($sizes['width']) && $sizes['width'] > $this->max_width) || (isset($sizes['height']) && $sizes['height'] > $this->max_height)) {
							$image_editor->resize($this->max_width, $this->max_height, false);
							$resized = true;
							$to_save = true;
							$sizes = $image_editor->get_size();
							error_log("--new-size--".$sizes['width']."x".$sizes['height']);
						}
						else {
							error_log("--no-resizing-needed");
						}
					} else {
						error_log("--no-resizing-requested");
					}

					if($this->image_re_compression && ($image_type=='image/jpg' || $image_type=='image/jpeg')) {
						$to_save = true;
						error_log("--compression-level--q-".$this->compression_level);
					} elseif(!$resized) {
						error_log("--no-forced-recompression");
					}

					if($to_save) {
						$image_editor->set_quality($this->compression_level);
						$saved_image = $image_editor->save($image_data['file']);
						error_log("--image-saved");
					} else {
						error_log("--no-changes-to-save");
					}
				}
			} else {
				error_log("--no-action-required");
			}
			error_log("**-end--resize-image-upload\n");
			return $image_data;
		}
	}
	WD_Compress_Images::get_instance();  // Start an instance of the plugin class 
}