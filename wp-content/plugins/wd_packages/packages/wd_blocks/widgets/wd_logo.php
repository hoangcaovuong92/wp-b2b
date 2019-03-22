<?php
if( !class_exists( 'wd_widget_logo' ) ) {
	class wd_widget_logo extends WD_Widgets_Fields{
		public function __construct() {
			$this->widget_init();
		}

		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'		=> '',
				'logo_id' 	=> '',
				'show_logo_title' 	=> '0',
				'width' 			=> '',
				'class' 			=> '',
			);
			$this->widget_name = esc_html__('WD - Site Logo','wd_package');
			$this->widget_desc = esc_html__('Display logo image...','wd_package');
			$this->widget_slug = 'wd_logo';
			$this->callback = 'wd_logo_function';
		}
		
	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

	        $show_logo_title_arr 	= wd_get_list_tvgiao_boolean();

			$this->text_field(
    			esc_html__( 'Widget title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title
			);
			
        	$this->image_field(	
        		esc_html__( 'Logo:', 'wd_package' ), 
        		$this->get_field_name( 'logo_id' ), 
        		$this->get_field_id( 'logo_id' ), 
        		$logo_id, 
        		'', 
        		esc_html__("If you do not want the default logo, you add another logo here", 'wd_package')
        	); 

    		$this->select_field(
    			esc_html__( 'Show Site Title:', 'wd_package' ), 
    			$this->get_field_name( 'show_logo_title' ), 
    			$this->get_field_id( 'show_logo_title' ), 
    			$show_logo_title_arr, 
    			$show_logo_title
    		); 

    		$this->text_field(
    			esc_html__( 'Width:', 'wd_package' ), 
    			$this->get_field_name( 'width' ), 
    			$this->get_field_id( 'width' ),
    			$width, 
    			esc_html__("Exam: 100px, 100%...", 'wd_package')
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
	function wd_widget_logo_register() {
		register_widget( 'wd_widget_logo' );
	}
	add_action( 'widgets_init', 'wd_widget_logo_register' );
}