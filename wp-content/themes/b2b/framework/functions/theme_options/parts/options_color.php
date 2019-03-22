<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Custom Color', 'feellio' ),
    'id'               => 'wd_color_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-magic',
) );

//Custom color Settings
$manager = (WD_THEME_STYLE_MODE === 'sass') ? WD_SASS::get_instance() : WD_XML_Manager::get_instance();
$manager->options_color_fields();