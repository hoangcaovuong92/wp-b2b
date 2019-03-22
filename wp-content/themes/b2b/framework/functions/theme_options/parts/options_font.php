<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Custom Font', 'feellio' ),
    'id'               => 'wd_font_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-font',
) );

//Custom Font Settings
$manager = (WD_THEME_STYLE_MODE === 'sass') ? WD_SASS::get_instance() : WD_XML_Manager::get_instance();
$manager->options_font_fields();