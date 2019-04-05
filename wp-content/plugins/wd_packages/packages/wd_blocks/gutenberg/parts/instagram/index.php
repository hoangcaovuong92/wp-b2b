<?php 
if (!class_exists('WD_Block_Instagram')) {
	class WD_Block_Instagram extends WD_Gutenberg_Template {
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
            $this->block_name = 'instagram';
            $this->block_title = __('WD - Instagram', 'wd_package');
            $this->block_desc = __('Display instagram photos...', 'wd_package');
            $this->block_icon = 'images-alt'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = 'wd_instagram_function';

            $insta_follow = wd_get_list_tvgiao_boolean();
            $insta_style = array(
                'style-insta-1' => esc_html__('Center + Inner Content', 'wd_package'),
                'style-insta-2' => esc_html__('Before Content', 'wd_package'),
            );
            $insta_hover_style = array(
                ''                       => esc_html__('None', 'wd_package'),
                'wd-insta-hover-style-1' => esc_html__('Zoom', 'wd_package'),
                'wd-insta-hover-style-2' => esc_html__('Overlay', 'wd_package')
            );
            $insta_columns = wd_get_list_tvgiao_columns();
            $columns_tablet = wd_get_list_columns_tablet();
            $columns_mobile = wd_get_list_columns_mobile();
            $insta_padding = wd_get_list_columns_padding();
            $insta_size = wd_get_list_instagram_image_size();
            $insta_sortby = wd_get_list_instagram_sort_by();
            $insta_action_click_item = array(
                'lightbox'  => esc_html__("Lightbox", 'wd_package'),
                'link'      => esc_html__("Instagram Link", 'wd_package'),
            );
            $insta_open_win = wd_get_list_link_target();
            $is_slider = wd_get_list_tvgiao_boolean();
            $show_loadmore = wd_get_list_tvgiao_boolean();
            $show_nav = wd_get_list_tvgiao_boolean();
            $auto_play = wd_get_list_tvgiao_boolean();

            $fullwidth_mode = wd_get_list_tvgiao_boolean();

            $this->set_group_name(esc_html__("General Settings", 'wd_package'));

            $this->get_text_block('insta_title', esc_html__("Title Instagram", 'wd_package'), '');
            $this->get_text_block('insta_desc', esc_html__("Description", 'wd_package'), '');
            $this->get_select_block('insta_follow', $insta_follow, esc_html__('Show Follow Me', 'wd_package' ), '1');
            $this->get_text_block('insta_follow_text', esc_html__("Follow Text", 'wd_package'), 'Follow Me', array('insta_follow', '==', '1'));
            $this->get_select_block('insta_style', $insta_style, __('Title & Desc Position', 'wd_package' ), 'style-insta-1');
            $this->get_select_block('insta_hover_style', $insta_hover_style, __('Hover Style', 'wd_package' ), '');

            //Instagram Setting
            $this->set_group_name(esc_html__("Instagram Settings", 'wd_package'));

            $this->get_select_block('insta_columns', $insta_columns, esc_html__( 'Columns', 'wd_package' ), '4');
            $this->get_select_block('columns_tablet', $columns_tablet, esc_html__( 'Columns On Tablet', 'wd_package' ), '2');
            $this->get_select_block('columns_mobile', $columns_mobile, esc_html__( 'Columns On Mobile', 'wd_package' ), '1');
            $this->get_text_block('insta_number', esc_html__("Number of photos", 'wd_package'), '4');
            $this->get_select_block('insta_padding', $insta_padding, esc_html__( 'Padding', 'wd_package' ), 'normal');
            $this->get_select_block('insta_size', $insta_size, esc_html__( 'Photo Size', 'wd_package' ), 'low_resolution');
            $this->get_select_block('insta_sortby', $insta_sortby, esc_html__( 'Sort By', 'wd_package' ), 'none');
            $this->get_select_block('insta_action_click_item', $insta_action_click_item, esc_html__( 'Action When Click Item', 'wd_package' ), 'lightbox');
            $this->get_select_block('insta_open_win', $insta_open_win, esc_html__( 'Open links in', 'wd_package' ), '_blank', array('insta_action_click_item', '==', 'link'));

            //Slider Setting
            $this->set_group_name(esc_html__("Slider Settings", 'wd_package'));

            $this->get_select_block('is_slider', $is_slider, esc_html__( 'Is Slider', 'wd_package' ), '0');
            $this->get_select_block('show_loadmore', $show_loadmore, esc_html__( 'Show Load More', 'wd_package' ), '0', array('is_slider', '==', '0'));
            $this->get_select_block('show_nav', $show_nav, esc_html__( 'Show Nav', 'wd_package' ), '1', array('is_slider', '==', '1'));
            $this->get_select_block('auto_play', $auto_play, esc_html__( 'Number Rows', 'wd_package' ), '1', array('is_slider', '==', '1'));
            $this->get_text_block('per_slide', esc_html__("Number Rows", 'wd_package'), '1', array('is_slider', '==', '1'));

            //Advance Setting
            $this->set_group_name(esc_html__("General Settings", 'wd_package'));

            $this->get_select_block('fullwidth_mode', $fullwidth_mode, __('Fullwidth Mode', 'wd_package'), 0);
            $this->get_text_block('class', __('Extra class name', 'wd_package'), '');
        }
	}
	WD_Block_Instagram::get_instance();
}