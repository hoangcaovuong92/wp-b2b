<?php
if( !class_exists( 'wd_widget_blog_special' ) ) {
	class wd_widget_blog_special extends WD_Widgets_Fields{
	    public function __construct() {
            $this->widget_init();
        }
        
        public function init_settings(){
			$this->list_widget_field_default = array(
                'widget_title'			=> 'Popular Posts',
                'layout'				=> 'category, title',
                'style'					=> 'grid',
                'id_category'			=> '-1',
                'data_show'				=> 'recent-post',
                'number_blogs'			=> 4,
                'sort'					=> 'DESC',
                'order_by'				=> 'date',
                'columns'				=> 1,
                'columns_tablet'		=> 1,
                'columns_mobile'		=> 1,
                'show_thumbnail'  		=> '1',
                'show_placeholder_image'  => '1',
                'image_size'  			=> 'post-thumbnail',
                'grid_hover_style'		=> 'normal',
                'number_excerpt'		=> '10',
                'is_slider'				=> '0',
                'show_nav'				=> '1',	
                'auto_play'				=> '1',
                'class'					=> ''
            );
			$this->widget_name = esc_html__('WD - Blog Special','wd_package');
			$this->widget_desc = esc_html__('Custom blog themes do not follow the default setting structure.','wd_package');
			$this->widget_slug = 'wd_blog_special';
			$this->callback = 'wd_blog_special_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

	    	$style_arr 		= array(
				'grid'		=> esc_html__('Grid', 'wd_package' ),
				'list'		=> esc_html__('List', 'wd_package' )
				 
			);
            $columns_arr                = wd_get_list_tvgiao_columns();
            $id_category_arr 			= wd_get_list_category('category');
            $data_show_arr 				= wd_get_list_special_blog_name();
            $sort_arr 					= wd_get_sort_by_values();
            $order_by_arr 				= wd_get_order_by_values();
            $show_thumbnail_arr 		= wd_get_list_tvgiao_boolean();
            $show_placeholder_image_arr = wd_get_list_tvgiao_boolean();
            $image_size_arr 			= wd_get_list_image_size();
            $show_date_arr 				= wd_get_list_tvgiao_boolean();
            $show_author_arr 			= wd_get_list_tvgiao_boolean();
            $show_category_arr 			= wd_get_list_tvgiao_boolean();
            $show_number_comments_arr 	= wd_get_list_tvgiao_boolean();
            $grid_hover_style_arr 		= array(
                'normal'        => esc_html__( 'Normal', 'wd_package' ),
                'grid-inner'    => esc_html__( 'Content Inner', 'wd_package' ),
            );
            $is_slider_arr 				= wd_get_list_tvgiao_boolean();
            $show_nav_arr 				= wd_get_list_tvgiao_boolean();
            $auto_play_arr 				= wd_get_list_tvgiao_boolean();

			$this->text_field(
    			esc_html__( 'Widget Title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title
    		);
	        $this->hidden_field(
    			$this->get_field_name( 'layout' ),
    			$this->get_field_id( 'layout' ),
    			$layout
    		);
            $this->hidden_field(
                $this->get_field_name( 'style' ), 
                $this->get_field_id( 'style' ), 
                $style
			);
			$this->select_field(
                esc_html__( 'Select Category:', 'wd_package' ), 
                $this->get_field_name( 'id_category' ), 
                $this->get_field_id( 'id_category' ), 
                $id_category_arr, 
                $id_category
            ); 
			$this->select_field(
                esc_html__( 'Data Show:', 'wd_package' ), 
                $this->get_field_name( 'data_show' ), 
                $this->get_field_id( 'data_show' ), 
                $data_show_arr, 
                $data_show
            ); 
    		$this->text_field(
    			esc_html__( 'Number of blogs:', 'wd_package' ), 
    			$this->get_field_name( 'number_blogs' ),
    			$this->get_field_id( 'number_blogs' ),
    			$number_blogs, 
    			esc_html__("", 'wd_package')
			);
			$this->select_field(
                esc_html__( 'Sort By:', 'wd_package' ), 
                $this->get_field_name( 'sort' ), 
                $this->get_field_id( 'sort' ), 
                $sort_arr, 
                $sort
			);
			$this->select_field(
                esc_html__( 'Order By:', 'wd_package' ), 
                $this->get_field_name( 'order_by' ), 
                $this->get_field_id( 'order_by' ), 
                $order_by_arr, 
                $order_by
            );
            $this->hidden_field(
                $this->get_field_name( 'columns' ), 
                $this->get_field_id( 'columns' ), 
                $columns
            ); 
            $this->hidden_field(
                $this->get_field_name( 'columns_tablet' ),
                $this->get_field_id( 'columns_tablet' ),
                $columns_tablet
            );

            $this->hidden_field(
                $this->get_field_name( 'columns_mobile' ),
                $this->get_field_id( 'columns_mobile' ),
                $columns_mobile
            );
            $this->hidden_field(
                $this->get_field_name( 'show_thumbnail' ), 
                $this->get_field_id( 'show_thumbnail' ), 
                $show_thumbnail
            ); 
            $this->hidden_field(
                $this->get_field_name( 'show_placeholder_image' ), 
                $this->get_field_id( 'show_placeholder_image' ), 
                $show_placeholder_image
            ); 
            $this->select_field(
                esc_html__( 'Thumbnail Size:', 'wd_package' ), 
                $this->get_field_name( 'image_size' ), 
                $this->get_field_id( 'image_size' ), 
                $image_size_arr, 
                $image_size
            ); 
            $this->select_field(
                esc_html__( 'Grid Hover Style:', 'wd_package' ), 
                $this->get_field_name( 'grid_hover_style' ), 
                $this->get_field_id( 'grid_hover_style' ), 
                $grid_hover_style_arr, 
                $grid_hover_style
            ); 
            $this->text_field(
    			esc_html__( 'Number Excerpt:', 'wd_package' ), 
    			$this->get_field_name( 'number_excerpt' ),
    			$this->get_field_id( 'number_excerpt' ),
    			$number_excerpt, 
    			esc_html__("", 'wd_package')
    		);
            $this->select_field(
                esc_html__( 'Is Slider:', 'wd_package' ), 
                $this->get_field_name( 'is_slider' ), 
                $this->get_field_id( 'is_slider' ), 
                $is_slider_arr, 
                $is_slider
			);
			$this->select_field(	
                esc_html__( 'Show Nav:', 'wd_package' ), 	
                $this->get_field_name( 'show_nav' ), 	
                $this->get_field_id( 'show_nav' ), 	
                $show_nav_arr, 	
                $show_nav	
            ); 	
            $this->select_field(	
                esc_html__( 'Auto Play:', 'wd_package' ),
                $this->get_field_name( 'auto_play' ),
                $this->get_field_id( 'auto_play' ),
                $auto_play_arr, 	
                $auto_play	
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
	function wd_widget_blog_special_register() {
		register_widget( 'wd_widget_blog_special' );
	}
	add_action( 'widgets_init', 'wd_widget_blog_special_register' );
}