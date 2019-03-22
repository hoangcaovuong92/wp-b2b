<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Custom Script', 'feellio' ),
    'id'               => 'wd_custom_css_script',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-fire',
    'fields'           => array(
        /*array(
            'id'       => 'wd_custom_css',
            'type'     => 'ace_editor',
            'title'    => __( 'CSS Code', 'feellio' ),
            'subtitle' => __( 'Paste your CSS code here.', 'feellio' ),
            'mode'     => 'css',
            'theme'    => 'monokai',
            'desc'     => '',
            'default'  => ""
        ),*/
        array(
            'id'       => 'wd_custom_script',
            'type'     => 'ace_editor',
            'title'    => __( 'JS Code', 'feellio' ),
            'subtitle' => __( 'Paste your JS code here.', 'feellio' ),
            'mode'     => 'javascript',
            'theme'    => 'chrome',
            'desc'     => '',
            'default'  => ""
        ),
    ),
) );