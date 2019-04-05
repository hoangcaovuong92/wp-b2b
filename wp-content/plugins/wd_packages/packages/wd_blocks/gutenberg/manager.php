<?php 
if (!class_exists('WD_Gutenberg')) {
	class WD_Gutenberg {
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

            if ( ! function_exists( 'register_block_type' ) ) {
                // Gutenberg is not active.
                return;
            }
			add_filter('block_categories', array($this, 'register_block_category'), 10, 2);

			//General block style
			add_action('enqueue_block_editor_assets', array($this, 'enqueue_blocks_editor'));
			add_action('enqueue_block_assets', array($this, 'enqueue_styles_frontend'));

			$this->include_template();
			$this->include_blocks();
		}
		
		public function include_template(){ 
			if (file_exists(WD_BLOCKS_BASE.'/gutenberg/template/template.php')) {
				require_once WD_BLOCKS_BASE.'/gutenberg/template/template.php';
			}
		}

        public function include_blocks(){ 
			$blocks = array(
				'blog_layout',
				'product_layout',
				'social_icons',
				'banner_image',
				'profile',
				'title'
			);
			if (class_exists('WD_Instagram')) {
				$blocks[] = 'instagram';
			}
			foreach ($blocks as $block) {
                if (file_exists(WD_BLOCKS_BASE.'/gutenberg/parts/'.$block.'/index.php')) {
                    require_once WD_BLOCKS_BASE.'/gutenberg/parts/'.$block.'/index.php';
                }
			}
		} 
		
		//Register new block category
		public function register_block_category($categories, $post){ 
			return array_merge(
				array(
					array(
						'slug'  => 'wd-block',
						'title' => __('WD Blocks', 'wd_package'),
					),
				),
				$categories
			);
		} 
        
        public function folder_exist($folder){
            // Get canonicalized absolute pathname
            $path = realpath($folder);
            // If it exist, check if it's a directory
            return ($path !== false AND is_dir($path)) ? $path : false;
		}
		
		public function enqueue_blocks_editor($hook) {
			wp_enqueue_style('wd-general-block-editor-style', WD_BLOCKS_BASE_URI.'/gutenberg/template/editor.css');
			//wp_enqueue_script('wd-general-block-editor-script', 	WD_BLOCKS_BASE_URI.'/gutenberg/template/test.js', array('wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'wp-date', 'wp-api-fetch'), false, true);
		}
		
		public function enqueue_styles_frontend() {
			wp_enqueue_style('wd-general-block-frontend-style', WD_BLOCKS_BASE_URI.'/gutenberg/template/style.css');
		}
	}
	WD_Gutenberg::get_instance();
}