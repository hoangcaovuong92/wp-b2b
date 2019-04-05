<?php 
if (!class_exists('WD_Block_Profile')) {
	class WD_Block_Profile extends WD_Gutenberg_Template {
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
            $this->block_name = 'profile';
            $this->block_title = __('WD - Profile', 'wd_package');
            $this->block_desc = __('Display user profile...', 'wd_package');
            $this->block_icon = 'money'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = 'wd_profile_function';

            $image_size = wd_get_list_image_size();
            $target = wd_get_list_link_target();
            $display_logo = wd_get_list_tvgiao_boolean();
            $text_align = wd_get_list_text_align_bootstrap();
            $display_sign = wd_get_list_tvgiao_boolean();

            $fullwidth_mode = wd_get_list_tvgiao_boolean();
            
            $this->get_image_block('image', esc_html__("Image", 'wd_package'));
            $this->get_select_block('image_size', $image_size, __('Image Size', 'wd_package'), 'full');

            $this->get_text_block('website', __('Website', 'wd_package'), '#');
            $this->get_select_block('target', $target, __('Link Target', 'wd_package'), 'full');
            $this->get_text_block('job', __('Occupation', 'wd_package'), 'BLOGER / PHOTOGRAPHER / DESIGNER');
            $this->get_text_block('title', __('Title', 'wd_package'), 'Hello everybody, my name is Lara Croft');
            $this->get_text_block('desc', __('Description', 'wd_package'), 'Great design is making something memorable and meaningful');
            $this->get_select_block('display_logo', $display_logo, __('Display Site Logo', 'wd_package'), 0);
            $this->get_select_block('text_align', $text_align, __('About Text Align', 'wd_package'), 'text-left');
            $this->get_textarea_block('about', __('About Text', 'wd_package'), '[vc_row][vc_column_inner width="1/2"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.[/vc_column_text][/vc_column_inner][/vc_row]');
            $this->get_select_block('display_sign', $display_sign, __('Display Signature Image', 'wd_package'), 'text-left');
            $this->get_image_block('sign_image', esc_html__("Sign Image", 'wd_package'), '', array('display_sign', '==', 1));

            $this->get_select_block('fullwidth_mode', $fullwidth_mode, __('Fullwidth Mode', 'wd_package'), 0);
            $this->get_text_block('class', __('Extra class name', 'wd_package'), '');
        }
	}
	WD_Block_Profile::get_instance();
}