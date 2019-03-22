<?php
if( !class_exists( 'wd_widget_pages_list' ) ) {
	class wd_widget_pages_list extends WD_Widgets_Fields{
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'			=> '',
				'ids'                   => '-1',
				'style'                 => 'footer-copyright-links-list',
				'copyright'             => '1',
				'copyright_text'        => sprintf(__( '© 2019 by %s. All rights reserved.', 'wd_package' ), esc_html( get_bloginfo('name'))),
				'class'                 => '',
			);
			$this->widget_name = esc_html__('WD - Pages List','wd_package');
			$this->widget_desc = esc_html__('Display pages link with list style...','wd_package');
			$this->widget_slug = 'wd_pages_list';
			$this->callback = 'wd_pages_list_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

            $ids_desc     = sprintf(esc_html__("List page ID: %s", 'wd_package'), implode(', ', wd_get_list_pages()) );

	    	$style_arr 		= array(
                'wd-pages-list-horizontal'  => esc_html__('Horizontal', 'wd_package' ),
                'wd-pages-list-vertical'    => esc_html__('Vertical', 'wd_package' ),
				 
			);
			$copyright_arr 	= wd_get_list_tvgiao_boolean();
			
	        $this->text_field(
    			esc_html__( 'Title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
			);
			
	        $this->text_field(
    			esc_html__( 'List Page ID (Separated by commas):', 'wd_package' ), 
    			$this->get_field_name( 'ids' ),
    			$this->get_field_id( 'ids' ),
    			$ids, 
    			$ids_desc
    		);
            $this->select_field(
                esc_html__( 'Style:', 'wd_package' ), 
                $this->get_field_name( 'style' ), 
                $this->get_field_id( 'style' ), 
                $style_arr, 
                $style
            );
            $this->select_field(
                esc_html__( 'Display Copyright?:', 'wd_package' ), 
                $this->get_field_name( 'copyright' ), 
                $this->get_field_id( 'copyright' ), 
                $copyright_arr,
                $copyright
            ); 
            $this->text_field(
                esc_html__( 'Copyright Text:', 'wd_package' ), 
                $this->get_field_name( 'copyright_text' ),
                $this->get_field_id( 'copyright_text' ),
                $copyright_text, 
                esc_html__("", 'wd_package')
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
	function wd_widget_pages_list_register() {
		register_widget( 'wd_widget_pages_list' );
	}
	add_action( 'widgets_init', 'wd_widget_pages_list_register' );
}