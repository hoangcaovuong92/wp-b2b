<?php
if( !class_exists( 'wd_widget_my_account_form' ) ) {
	class wd_widget_my_account_form extends WD_Widgets_Fields{
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'	=> '',
				'form' 			=> 'login',
				'style' 	  	=> 'style-1',
				'class' 		=> '',
			);
			$this->widget_name = esc_html__('WD - My Account (Form)','wd_package');
			$this->widget_desc = esc_html__('Custom Login/Register/Forgot Password Form...)','wd_package');
			$this->widget_slug = 'wd_my_account_form';
			$this->callback = 'wd_myaccount_form_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

	        $form_arr 		= array(
					'login' 			=> esc_html__( 'Login', 'wd_package' ),
					'register' 			=> esc_html__( 'Register', 'wd_package' ),
					'forgot-password' 	=> esc_html__( 'Forgot Password', 'wd_package' ),
				);

			$style_arr 		= wd_get_list_style_class(4);
			
			$this->text_field(
    			esc_html__( 'Widget title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
            );

    		$this->select_field(
    			esc_html__( 'Form:', 'wd_package' ), 
    			$this->get_field_name( 'form' ), 
    			$this->get_field_id( 'form' ), 
    			$form_arr, 
    			$form
    		); 
    		$this->select_field(
    			esc_html__( 'Style:', 'wd_package' ), 
    			$this->get_field_name( 'style' ), 
    			$this->get_field_id( 'style' ), 
    			$style_arr, 
    			$style
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
	function wd_widget_my_account_form_register() {
		register_widget( 'wd_widget_my_account_form' );
	}
	add_action( 'widgets_init', 'wd_widget_my_account_form_register' );
}