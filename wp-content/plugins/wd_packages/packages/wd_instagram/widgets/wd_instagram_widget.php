<?php
if( !class_exists( 'wd_widget_megamenu' ) ) {
	class wd_widget_megamenu extends WD_Widgets_Fields {
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'				=> 'INSTAGRAM',
				'insta_title'				=> '',
				'insta_desc'				=> '',
				'insta_follow'				=> '0',
				'insta_follow_text'			=> 'Follow Me',
				'insta_style'				=> "style-insta-1",
				'insta_hover_style'			=> '',
				'insta_columns'				=> 2,
				'columns_tablet'			=> 2,
				'columns_mobile'			=> 2,
				'insta_number'				=> 6,
				'insta_padding'				=> 'mini',
				'insta_size'				=> 'low_resolution',
				'insta_sortby'				=> 'none',
				'insta_action_click_item'	=> 'lightbox',
				'insta_open_win'			=> '_blank',
				'is_slider'					=> '0',
				'show_loadmore'				=> '0',
				'show_nav'					=> '1',
				'auto_play'					=> '1',
				'per_slide'					=> '1',
				'class' 					=> '',
			);
			$this->widget_name = esc_html__('WD - Instagram','wd_package');
			$this->widget_desc = esc_html__('Display instagram photos...','wd_package');
			$this->widget_slug = 'wd_instagram';
			$this->callback = 'wd_instagram_function';
		}

		public function form( $instance ) {
	       	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
			}
			
			$insta_style_arr = array(
				'style-insta-1' => esc_html__('Center + Inner Content', 'wd_package'),
				'style-insta-2' => esc_html__('Before Content', 'wd_package')
			);

			$insta_hover_style_arr = array(
				esc_html__('None', 'wd_package') 	=> '',
				esc_html__('Zoom', 'wd_package') 	=> 'wd-insta-hover-style-1',
				esc_html__('Overlay', 'wd_package') => 'wd-insta-hover-style-2'
			);

			$insta_columns_arr = wd_get_list_tvgiao_columns();
			$columns_tablet_arr = wd_get_list_columns_tablet();
			$columns_mobile_arr = wd_get_list_columns_mobile();

			$insta_padding_arr = wd_get_list_columns_padding();
			$insta_size_arr = wd_get_list_instagram_image_size();
			$insta_sortby_arr = wd_get_list_instagram_sort_by();
			$insta_action_click_item_arr = array(
				'lightbox' 	=> esc_html__("Lightbox", 'wd_package'),
				'link' 		=> esc_html__("Instagram Link", 'wd_package'),
			);
			$insta_open_win_arr = wd_get_list_link_target();

	        $this->text_field(
    			esc_html__( 'Title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
			);
			
			$this->text_field(
    			esc_html__( 'Title Instagram:', 'wd_package' ), 
    			$this->get_field_name( 'insta_title' ),
    			$this->get_field_id( 'insta_title' ),
    			$insta_title, 
    			esc_html__("", 'wd_package')
			);

			$this->textarea_field(
    			esc_html__( 'Description:', 'wd_package' ), 
    			$this->get_field_name( 'insta_desc' ),
    			$this->get_field_id( 'insta_desc' ),
    			$insta_desc, 
    			esc_html__("", 'wd_package')
			);

			$this->hidden_field(
                $this->get_field_name( 'insta_follow' ), 
                $this->get_field_id( 'insta_follow' ), 
                $insta_follow
			);

			$this->hidden_field(
                $this->get_field_name( 'insta_follow_text' ), 
                $this->get_field_id( 'insta_follow_text' ), 
                $insta_follow_text
			);

	        $this->select_field(
                esc_html__( 'Title & Desc Position:', 'wd_package' ), 
                $this->get_field_name( 'insta_style' ), 
                $this->get_field_id( 'insta_style' ), 
                $insta_style_arr, 
                $insta_style
			);

			$this->select_field(
                esc_html__( 'Hover Style:', 'wd_package' ), 
                $this->get_field_name( 'insta_hover_style' ), 
                $this->get_field_id( 'insta_hover_style' ), 
                $insta_hover_style_arr, 
                $insta_hover_style
			);

			$this->select_field(
                esc_html__( 'Columns:', 'wd_package' ), 
                $this->get_field_name( 'insta_columns' ), 
                $this->get_field_id( 'insta_columns' ), 
                $insta_columns_arr, 
                $insta_columns
			);

			$this->select_field(
                esc_html__( 'Columns On Tablet:', 'wd_package' ), 
                $this->get_field_name( 'columns_tablet' ), 
                $this->get_field_id( 'columns_tablet' ), 
                $columns_tablet_arr, 
                $columns_tablet
			);

			$this->select_field(
                esc_html__( 'Columns On Mobile:', 'wd_package' ), 
                $this->get_field_name( 'columns_mobile' ), 
                $this->get_field_id( 'columns_mobile' ), 
                $columns_mobile_arr, 
                $columns_mobile
			);

			$this->text_field(
    			esc_html__( 'Number of photos:', 'wd_package' ), 
    			$this->get_field_name( 'insta_number' ),
    			$this->get_field_id( 'insta_number' ),
    			$insta_number, 
    			esc_html__("", 'wd_package')
			);

			$this->select_field(
                esc_html__( 'Photo Size:', 'wd_package' ), 
                $this->get_field_name( 'insta_size' ), 
                $this->get_field_id( 'insta_size' ), 
                $insta_size_arr, 
                $insta_size
			);

			$this->select_field(
                esc_html__( 'Sort By:', 'wd_package' ), 
                $this->get_field_name( 'insta_sortby' ), 
                $this->get_field_id( 'insta_sortby' ), 
                $insta_sortby_arr, 
                $insta_sortby
			);

			$this->select_field(
                esc_html__( 'Action When Click Item:', 'wd_package' ), 
                $this->get_field_name( 'insta_action_click_item' ), 
                $this->get_field_id( 'insta_action_click_item' ), 
                $insta_action_click_item_arr, 
                $insta_action_click_item
			);

			$this->select_field(
                esc_html__( 'Open links in:', 'wd_package' ), 
                $this->get_field_name( 'insta_open_win' ), 
                $this->get_field_id( 'insta_open_win' ), 
                $insta_open_win_arr, 
                $insta_open_win
			);

			$this->hidden_field(
                $this->get_field_name( 'is_slider' ), 
                $this->get_field_id( 'is_slider' ), 
                $is_slider
			);
			$this->hidden_field(
                $this->get_field_name( 'show_loadmore' ), 
                $this->get_field_id( 'show_loadmore' ), 
                $show_loadmore
			);
			$this->hidden_field(	
                $this->get_field_name( 'show_nav' ), 	
                $this->get_field_id( 'show_nav' ), 	
                $show_nav	
            ); 	
            $this->hidden_field(	
                $this->get_field_name( 'auto_play' ),
                $this->get_field_id( 'auto_play' ),
                $auto_play	
			); 
			
			$this->hidden_field(	
                $this->get_field_name( 'per_slide' ),
                $this->get_field_id( 'per_slide' ),
                $per_slide	
            ); 

            $this->text_field(
    			esc_html__( 'Extra class name:', 'wd_package' ), 
    			$this->get_field_name( 'class' ),
    			$this->get_field_id( 'class' ),
    			$class, 
    			esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package')
    		);
	    }
	}
	function wp_widget_register_widget_megamenu() {
		register_widget( 'wd_widget_megamenu' );
	}
	add_action( 'widgets_init', 'wp_widget_register_widget_megamenu' );
}