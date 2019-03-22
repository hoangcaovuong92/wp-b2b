<?php
if( !class_exists( 'wd_widget_wishlist' ) ) {
	class wd_widget_wishlist extends WD_Widgets_Fields{
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'	=> '',
				'show_icon'     => '1',
				'show_text'     => '0',
				'class'         => ''
			);
			$this->widget_name = esc_html__('WD - Wishlist','wd_package');
			$this->widget_desc = esc_html__('Display wishlist icon.','wd_package');
			$this->widget_slug = 'wd_wishlist';
			$this->callback = 'wd_wishlist_icon_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

            $show_icon_arr       = wd_get_list_tvgiao_boolean();
            $show_text_arr       = wd_get_list_tvgiao_boolean();
		   
	        $this->text_field(
    			esc_html__( 'Widget title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
    		);
    		$this->select_field(
    			esc_html__( 'Show icon:', 'wd_package' ), 
    			$this->get_field_name( 'show_icon' ), 
    			$this->get_field_id( 'show_icon' ), 
    			$show_icon_arr, 
    			$show_icon
			); 
			$this->select_field(
    			esc_html__( 'Show text:', 'wd_package' ), 
    			$this->get_field_name( 'show_text' ), 
    			$this->get_field_id( 'show_text' ), 
    			$show_text_arr, 
    			$show_text
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
	function wd_widget_wishlist_register() {
		register_widget( 'wd_widget_wishlist' );
	}
	add_action( 'widgets_init', 'wd_widget_wishlist_register' );
}