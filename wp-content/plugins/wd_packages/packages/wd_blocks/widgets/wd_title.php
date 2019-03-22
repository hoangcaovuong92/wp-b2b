<?php
if( !class_exists( 'wd_widget_title' ) ) {
	class wd_widget_title extends WD_Widgets_Fields{
        public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
                'widget_title'	    => '',
                'title'             => '',
                'title_highlight'   => '',
                'description'       => '',
                'heading_type'      => 'wd-title-section-style-1',
                'title_color'       => '',
                'desc_color'        => '',
                'heading_element'   => 'h2',
                'text_align'        => '',
                'display_button'    => '0',
                'button_text'       => 'View All',
                'button_url'        => '#',
                'class'             => ''
            );
			$this->widget_name = esc_html__('WD - Title/Heading','wd_package');
			$this->widget_desc = esc_html__('Custom title for everywhere','wd_package');
			$this->widget_slug = 'wd_title';
			$this->callback = 'wd_title_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}


            $heading_type_arr     = wd_get_list_style_class(1, 'wd-title-section-style-');
	    	$heading_element_arr  = wd_get_list_heading_tag();
            $text_align_arr 	  = wd_get_list_text_align_bootstrap();
            $display_button_arr   = wd_get_list_tvgiao_boolean();

            $this->text_field(
    			esc_html__( 'Widget title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
            );
            
	        $this->text_field(
                esc_html__( 'Title:', 'wd_package' ), 
                $this->get_field_name( 'title' ),
                $this->get_field_id( 'title' ),
                $title, 
                esc_html__("", 'wd_package')
            );

            $this->text_field(
                esc_html__( 'Title HighLight:', 'wd_package' ), 
                $this->get_field_name( 'title_highlight' ),
                $this->get_field_id( 'title_highlight' ),
                $title_highlight, 
                esc_html__("Enter the keyword of the title, where you want to highlight.", 'wd_package')
            );

            $this->textarea_field(
                esc_html__( 'Description:', 'wd_package' ), 
                $this->get_field_name( 'description' ),
                $this->get_field_id( 'description' ),
                $description, 
                esc_html__("", 'wd_package')
            );

            $this->select_field(
                esc_html__( 'Heading Style:', 'wd_package' ), 
                $this->get_field_name( 'heading_type' ), 
                $this->get_field_id( 'heading_type' ), 
                $heading_type_arr, 
                $heading_type
            );
            $this->color_field(
                esc_html__( 'Custom Title Color:', 'wd_package' ), 
                $this->get_field_name( 'title_color' ),
                $this->get_field_id( 'title_color' ),
                $title_color, 
                esc_html__("", 'wd_package')
            );
            $this->color_field(
                esc_html__( 'Custom Description Color:', 'wd_package' ), 
                $this->get_field_name( 'desc_color' ),
                $this->get_field_id( 'desc_color' ),
                $desc_color, 
                esc_html__("", 'wd_package')
            );

            $this->select_field(
                esc_html__( 'Heading Element:', 'wd_package' ), 
                $this->get_field_name( 'heading_element' ), 
                $this->get_field_id( 'heading_element' ), 
                $heading_element_arr,
                $heading_element
            ); 
            $this->select_field(
                esc_html__( 'Text Align:', 'wd_package' ), 
                $this->get_field_name( 'text_align' ), 
                $this->get_field_id( 'text_align' ), 
                $text_align_arr,
                $text_align
            ); 
            $this->select_field(
                esc_html__( 'Display Button:', 'wd_package' ), 
                $this->get_field_name( 'display_button' ), 
                $this->get_field_id( 'display_button' ), 
                $display_button_arr,
                $display_button
            );
            $this->text_field(
                esc_html__( 'Button Text:', 'wd_package' ), 
                $this->get_field_name( 'button_text' ),
                $this->get_field_id( 'button_text' ),
                $button_text, 
                esc_html__("", 'wd_package')
            );
            $this->text_field(
                esc_html__( 'Button URL:', 'wd_package' ), 
                $this->get_field_name( 'button_url' ),
                $this->get_field_id( 'button_url' ),
                $button_url, 
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
	function wd_widget_title_register() {
		register_widget( 'wd_widget_title' );
	}
	add_action( 'widgets_init', 'wd_widget_title_register' );
}