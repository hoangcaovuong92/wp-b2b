<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Search', 'feellio' ),
    'id'               => 'wd_search_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-search',
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Search Form', 'feellio' ),
    'id'               => 'wd_search_form',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_page_search_type',
            'type'     => 'radio',
            'title'    => __( 'Result Filter', 'feellio' ),
            'subtitle' => __( 'Choose the target for search result', 'feellio' ),
            'options'  => $wd_default_data['search_page']['choose']['type'],
            'default'  => $wd_default_data['search_page']['default']['type'],
        ),
        array(
            'id'       => 'wd_layout_page_search_only_title',
            'type'     => 'switch',
            'title'    => __( 'Search Title Only', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['search_page']['default']['search_only_title'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_layout_page_search_ajax',
            'type'     => 'switch',
            'title'    => __( 'Ajax', 'feellio' ),
            'subtitle' => __( 'Search with ajax request?', 'feellio' ),
            'default'  => $wd_default_data['search_page']['default']['ajax'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'wd_layout_page_search_show_thumbnail',
            'type'     => 'switch',
            'title'    => __( 'Show Thumbnail', 'feellio' ),
            'subtitle' => __( 'Get post thumbnail on result?', 'feellio' ),
            'default'  => $wd_default_data['search_page']['default']['show_thumbnail'],
            'on'       => 'Enable',
            'off'      => 'Disabled',
            'required' => array('wd_layout_page_search_ajax','=',array(true) ),
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Search Result Page', 'feellio' ),
    'id'               => 'wd_layout_page_search',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_page_search_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['page_search'],
        ),

        array(
            'id'       => 'wd_layout_page_search_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['page_search_left'],
            /*'required' => array('wd_layout_page_search_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_page_search_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['page_search_right'],
            /*'required' => array('wd_layout_page_search_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_page_search_background_style',
            'type'     => 'radio',
            'title'    => __( 'Background Style', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['search_page']['choose']['bg_style'],
            'default'  => $wd_default_data['search_page']['default']['bg_style'],
        ),

        array(
            'id'       => 'wd_layout_page_search_background_color',
            'type'     => 'color',
            'transparent'=> false,
            'title'    => __( 'Background Color', 'feellio' ),
            'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['search_page']['default']['bg_color']),
            'default'  => $wd_default_data['search_page']['default']['bg_color'],
            'required' => array('wd_layout_page_search_background_style', '=', 'bg_color'),
        ),

        array(
            'id'       => 'wd_layout_page_search_background_image',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Background Image', 'feellio' ),
            'compiler' => 'true',
            'desc'     => __( '', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => array( 'url' => $wd_default_data['search_page']['default']['bg_image'] ),
            'required' => array('wd_layout_page_search_background_style', '=', 'bg_image'),
        ),
        array(
            'id'       => 'wd_layout_page_search_columns',
            'type'     => 'button_set',
            'title'    => __( 'Columns', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'subtitle' => __( 'Set columns for blog search page.', 'feellio' ),
            'options'  => $wd_default_data['columns']['choose'],
            'default'  => $wd_default_data['columns']['default']['page_search'],
            'required' => array('wd_layout_page_search_type', '=', 'post'),
        ),
    )
) );