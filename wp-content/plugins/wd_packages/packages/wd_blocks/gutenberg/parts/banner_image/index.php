<?php 
if (!class_exists('WD_Block_Banner_Image')) {
	class WD_Block_Banner_Image extends WD_Gutenberg_Template {
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
            $this->block_name = 'banner-image';
            $this->block_title = __('WD - Banner Image', 'wd_package');
            $this->block_desc = __('Simple banner image...', 'wd_package');
            $this->block_icon = 'format-image'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = 'wd_banner_image_function';

            $image_size     = wd_get_list_image_size();
            $hover_style 	= wd_get_list_style_class(3);
            $button_position = array(
                'center' => esc_html__("Center", 'wd_package'),
                'static' => esc_html__("Static", 'wd_package'),
                'custom' => esc_html__("Custom", 'wd_package'),
            );
            $target = wd_get_list_link_target();
            
            $this->get_image_block('image', esc_html__("Image", 'wd_package'));
            $this->get_select_block('image_size', $image_size, esc_html__("Image Size", 'wd_package'), 'full');
            $this->get_select_block('hover_style', $hover_style, esc_html__( 'Hover Style', 'wd_package' ), 'style-1');

            $this->set_group_name(esc_html__("Button Settings", 'wd_package'));

            $this->get_text_block('button_text', esc_html__("Button text", 'wd_package'), 'Shop Now');
            $this->get_text_block('link_url', esc_html__("Link Button", 'wd_package'), '#', array('button_text', '!==', ''));
            $this->get_select_block('target', $target, esc_html__( 'Link Target', 'wd_package' ), '_blank', array('button_text', '!==', ''));
            $this->get_select_block('button_position', $button_position, esc_html__( 'Button Position', 'wd_package' ), 'style-1', array('button_text', '!==', ''));
            $this->get_text_block('top', esc_html__("Top (ex: 5%, 5px...)", 'wd_package'), '', array('button_position', '==', 'custom'));
            $this->get_text_block('right', esc_html__("Right (ex: 5%, 5px...)", 'wd_package'), '', array('button_position', '==', 'custom'));
            $this->get_text_block('bottom', esc_html__("Bottom (ex: 5%, 5px...)", 'wd_package'), '', array('button_position', '==', 'custom'));
            $this->get_text_block('left', esc_html__("Left (ex: 5%, 5px...)", 'wd_package'), '', array('button_position', '==', 'custom'));

            $this->set_group_name(esc_html__("General Settings", 'wd_package'));

            $this->get_text_block('class', __('Extra class name', 'wd_package'), '');
        }
	}
	WD_Block_Banner_Image::get_instance();
}