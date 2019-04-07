<?php 
if (!class_exists('WD_Block_Title')) {
	class WD_Block_Title extends WD_Gutenberg_Template {
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
            $this->block_name = 'title';
            $this->block_title = __('WD - Title/Heading', 'wd_package');
            $this->block_desc = __('Custom title for everywhere', 'wd_package');
            $this->block_icon = 'editor-textcolor'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = 'wd_title_function';

            $heading_element = wd_get_list_heading_tag();
            $text_align = wd_get_list_text_align_bootstrap();
            $display_button = wd_get_list_tvgiao_boolean();
            $fullwidth_mode = wd_get_list_tvgiao_boolean();

            $this->get_text_block('title', __('Title', 'wd_package'), '');
            $this->get_text_block('title_highlight', __('Title HighLight', 'wd_package'), '', array('title', '!=', ''));
            $this->get_textarea_block('description', __('Description', 'wd_package'), '');
            $this->get_text_block('title_size', __('Title Font Size', 'wd_package'), '', array('title', '!=', ''));
            $this->get_text_block('highlight_size', __('Highlight Font Size', 'wd_package'), '', array('title_highlight', '!=', ''));
            $this->get_text_block('desc_size', __('Description Font Size', 'wd_package'), '', array('description', '!=', ''));
            $this->get_select_block('heading_element', $heading_element, __('Heading Element', 'wd_package'), 'h2');
            $this->get_select_block('text_align', $text_align, __('Text Align', 'wd_package'), '');
            $this->get_select_block('display_button', $display_button, __('Display Button', 'wd_package'), 0);
            $this->get_text_block('button_text', __('Button Text', 'wd_package'), 'View All', array('display_button', '==', 1));
            $this->get_text_block('button_url', __('Button URL', 'wd_package'), '#', array('display_button', '==', 1));
            
            $this->get_select_block('fullwidth_mode', $fullwidth_mode, __('Fullwidth Mode', 'wd_package'), 0);
            $this->get_text_block('class', __('Extra class name', 'wd_package'), '');
        }
	}
	WD_Block_Title::get_instance();
}