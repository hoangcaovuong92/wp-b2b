<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'General', 'feellio' ),
    'id'               => 'wd_general_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-cogs',
    'fields'     => array(
        array(
            'id'       => 'wd_logo',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Logo Default', 'feellio' ),
            'compiler' => 'true',
            'desc'     => __( '', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => array( 'url' => $wd_default_data['general']['default']['logo'] ),
          
        ),
        array(
            'id'       => 'wd_favicon',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Favicon', 'feellio' ),
            'compiler' => 'true',
            'desc'     => __( '', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => array( 'url' => $wd_default_data['general']['default']['favicon'] ),
        ),
        array(
            'id'       => 'wd_bg_body_display',
            'type'     => 'switch',
            'title'    => __( 'Background Image', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['general']['default']['bg_display'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_bg_body',
            'type'     => 'background',
            'background-size'   => false,
            'title'    => __( 'Background Body', 'feellio' ),
            'compiler' => 'true',
            'desc'     => __( '', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'background-color'  => false,
            'preview_media'     => true,
            'preview'           => false,
            'default'  => $wd_default_data['general']['default']['bg_body'],
            'required' => array('wd_bg_body_display','=', '1' ),
        ),
    ) 
) );