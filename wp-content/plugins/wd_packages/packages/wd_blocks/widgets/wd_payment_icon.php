<?php
if( !class_exists( 'wd_widget_icon_payment' ) ) {
	class wd_widget_icon_payment extends WD_Widgets_Fields{
		public function __construct() {
			$this->widget_init();
		}
		
		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'		=> '',
				'list_icon_payment' => 'fa-cc-amex, fa-cc-discover, fa-cc-mastercard, fa-cc-paypal, fa-cc-visa',
				'size'              => 'fa-2x',
				'text_align'        => 'text-left',
				'class'             => ''
			);
			$this->widget_name = esc_html__('WD - Payment Icon','wd_package');
			$this->widget_desc = esc_html__('Display Payment Icon...','wd_package');
			$this->widget_slug = 'wd_icon_payment';
			$this->callback = 'wd_payment_icon_function';
		}

	    public function form( $instance ) {
	    	foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
	    	}

            $size_arr       = wd_get_list_awesome_font_size();
			$text_align_arr = wd_get_list_text_align_bootstrap();
			
			$this->text_field(
    			esc_html__( 'Widget title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			esc_html__("", 'wd_package')
            );

	        $this->text_field(
    			esc_html__( 'List Payment Icon:', 'wd_package' ), 
    			$this->get_field_name( 'list_icon_payment' ),
    			$this->get_field_id( 'list_icon_payment' ),
    			$list_icon_payment, 
    			esc_html__("Ex: fa-cc-diners-club, fa-cc-discover, fa-cc-jcb, fa-cc-mastercard, fa-cc-paypal, fa-cc-stripe, fa-cc-visa, fa-credit-card, fa-credit-card-alt, fa-google-wallet, fa-paypal", 'wd_package')
    		);
            $this->select_field(
                esc_html__( 'Font size:', 'wd_package' ), 
                $this->get_field_name( 'size' ), 
                $this->get_field_id( 'size' ), 
                $size_arr, 
                $size
            );
            $this->select_field(
                esc_html__( 'Text Align:', 'wd_package' ), 
                $this->get_field_name( 'text_align' ), 
                $this->get_field_id( 'text_align' ), 
                $text_align_arr, 
                $text_align
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
	function wd_widget_icon_payment_register() {
		register_widget( 'wd_widget_icon_payment' );
	}
	add_action( 'widgets_init', 'wd_widget_icon_payment_register' );
}