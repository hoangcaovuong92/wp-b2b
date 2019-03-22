<?php 
if (!class_exists('WD_Block_Social_Icons')) {
	class WD_Block_Social_Icons extends WD_Gutenberg_Template {
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

            $this->block_init();
        }
        
        public function set_block_info(){ 
            $this->block_name = 'social-icons';
            $this->block_title = __('WD - Social Icons', 'wd_package');
            $this->block_desc = __('Display social icon with many style...', 'wd_package');
            $this->block_icon = 'facebook'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = 'wd_social_icons_function';

            $style 			= array(
				'vertical' 		=> esc_html__('Style 1 (Vertical)', 'wd_package'),
				'horizontal' 	=> esc_html__('Style 2 (Horizontal)', 'wd_package'),
				'nav-user' 		=> esc_html__('Style 3 (Nav User)', 'wd_package')
			);
            $show_title 	= wd_get_list_tvgiao_boolean();
            $item_align 	= wd_get_list_flex_align_class();

            $this->get_select_block('style', $style, __( 'Style', 'wd_package' ), 'vertical');
            $this->get_select_block('show_title', $show_title, __( 'Show Title', 'wd_package' ), 1);
            $this->get_select_block('item_align', $item_align, __( 'Item Align', 'wd_package' ), 'wd-flex-justify-left');
            $this->get_text_block('rss_id', __('RSS ID', 'wd_package'), '', array('show_rss', '==', '1'));
            $this->get_text_block('twitter_id', __('Twitter ID', 'wd_package'), '', array('show_twitter', '==', '1'));
            $this->get_text_block('facebook_id', __('Facebook ID', 'wd_package'), '', array('show_facebook', '==', '1'));
            $this->get_text_block('google_id', __('Google Plus ID', 'wd_package'), '', array('show_google', '==', '1'));
            $this->get_text_block('pin_id', __('Pinterest ID', 'wd_package'), '', array('show_pin', '==', '1'));
            $this->get_text_block('youtube_id', __('Youtube ID', 'wd_package'), '', array('show_youtube', '==', '1'));
            $this->get_text_block('instagram_id', __('Instagram ID', 'wd_package'), '', array('show_instagram', '==', '1'));
            $this->get_text_block('class', __('Extra class name', 'wd_package'), '');
        }
	}
	WD_Block_Social_Icons::get_instance();
}