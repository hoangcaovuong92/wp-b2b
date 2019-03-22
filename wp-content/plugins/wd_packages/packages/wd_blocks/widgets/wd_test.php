<?php
if( !class_exists( 'wd_widget_test' ) ) {
	class wd_widget_test extends WD_Widgets_Fields{
	    public function __construct() {
            $this->widget_init();
        }
	}
	function wd_widget_test_register() {
		register_widget( 'wd_widget_test' );
	}
	add_action( 'widgets_init', 'wd_widget_test_register' );
}