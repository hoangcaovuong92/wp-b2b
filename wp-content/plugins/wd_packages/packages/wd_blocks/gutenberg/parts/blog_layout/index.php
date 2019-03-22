<?php 
if (!class_exists('WD_Block_Blog_Grid_List')) {
	class WD_Block_Blog_Grid_List extends WD_Gutenberg_Template {
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
            $this->block_name = 'blog-grid-list';
            $this->block_title = __('WD - Blog Grid/List', 'wd_package');
            $this->block_desc = __('Display blog with Grid/List layout...', 'wd_package');
            $this->block_icon = 'welcome-write-blog'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = 'wd_blog_layout_function';

            $data_show = wd_get_list_special_blog_name();
            $sort = wd_get_sort_by_values();
            $order_by = wd_get_order_by_values();
            $columns = wd_get_list_tvgiao_columns();
            $columns_tablet = wd_get_list_columns_tablet();
            $columns_mobile = wd_get_list_columns_mobile();
            $grid_list_button = wd_get_list_tvgiao_boolean();

            $grid_list_layout = array(
                'grid'      => __('Grid', 'wd_package'),
                'list'      => __('List', 'wd_package'),
            );

            $grid_hover_style = array(
                'normal'    => __('Normal', 'wd_package'),
                'grid-inner' => __('Content Inner', 'wd_package'),
            );

            $pagination_loadmore = array(
                'pagination' => __('Pagination', 'wd_package'),
                'loadmore'  => __('Infinite Scroll', 'wd_package'),
                '0'         => __('No Show', 'wd_package'),
            );

            //$this->get_toggle_block('test', 'Toggle', true);
            $this->get_api_block('id_category', 'post_categories', __('Select Category', 'wd_package'));
            $this->get_select_block('data_show', $data_show, __('Data Show', 'wd_package'), 'recent_blog');
            $this->get_text_block('number_blogs', __('Number of blogs', 'wd_package'), 12);
            $this->get_select_block('sort', $sort, __('Sort By', 'wd_package'), 'DESC');
            $this->get_select_block('order_by', $order_by, __('Order By', 'wd_package'), 'date');
            $this->get_select_block('columns', $columns, __('Columns', 'wd_package'), 3);
            $this->get_select_block('columns_tablet', $columns_tablet, __('Columns On Tablet', 'wd_package'), 2);
            $this->get_select_block('columns_mobile', $columns_mobile, __('Columns On Mobile', 'wd_package'), 1);
            $this->get_select_block('grid_list_button', $grid_list_button, __('Show Layout Switch Button', 'wd_package'), 1);
            $this->get_select_block('grid_list_layout', $grid_list_layout, __('Default Layout', 'wd_package'), 'grid', array('grid_list_button', '==', '0'));
            $this->get_select_block('grid_hover_style', $grid_hover_style, __('Grid Hover Style', 'wd_package'), 'normal');
            $this->get_text_block('excerpt_words', __('Number of excerpt words', 'wd_package'), 20);
            $this->get_select_block('pagination_loadmore', $pagination_loadmore, __('Show Pagination/Load More', 'wd_package'), 'pagination');
            $this->get_text_block('class', __('Extra class name', 'wd_package'), '');
        }
	}
	WD_Block_Blog_Grid_List::get_instance();
}