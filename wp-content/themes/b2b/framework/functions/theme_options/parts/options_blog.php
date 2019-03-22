<?php 
 Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog', 'feellio' ),
    'id'               => 'wd_blog_setting',
    'desc'             => __( '', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-edit'
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog Config', 'feellio' ),
    'id'               => 'wd_layout_blog_config',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_blog_config_title_display',
            'type'     => 'switch',
            'title'    => __( 'Title', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['title'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_thumbnail_display',
            'type'     => 'switch',
            'title'    => __( 'Thumbnail', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['thumbnail'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
           'id'       => 'wd_layout_blog_config_thumbnail_section_start',
            'type'     => 'section',
            'title'    => __( 'Thumbnail Settings', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'indent'   => true,
            'required' => array('wd_layout_blog_config_thumbnail_display','=', '1' ),
        ),
        /****************************/
            array(
                'id'       => 'wd_layout_blog_config_show_by_post_format',
                'type'     => 'switch',
                'title'    => __( 'By Post Format', 'feellio' ),
                'subtitle' => __( 'Enable to display posts by post format (video, audio, quote, gallery ...)', 'feellio' ),
                'default'  => $wd_default_data['blog']['config']['default']['show_by_post_format'],
                'on'       => 'Show',
                'off'      => 'Hide',
                'required' => array('wd_layout_blog_config_thumbnail_display','=', '1' ),
            ),

            array(
                'id'       => 'wd_layout_blog_config_thumbnail_placeholder',
                'type'     => 'switch',
                'title'    => __( 'Placeholder Image', 'feellio' ),
                'subtitle' => __( 'Placeholder image display when post no thumbnail', 'feellio' ),
                'default'  => $wd_default_data['blog']['config']['default']['placeholder'],
                'on'       => 'Show',
                'off'      => 'Hide',
                'required' => array('wd_layout_blog_config_thumbnail_display','=', '1' ),
            ),
        /****************************/
        array(
            'id'     => 'wd_layout_blog_config_thumbnail_section_end',
            'type'   => 'section',
            'indent' => false,
            'required' => array('wd_layout_blog_single_recent_post','=', '1' ),
        ),

        array(
            'id'       => 'wd_layout_blog_config_author_display',
            'type'     => 'switch',
            'title'    => __( 'Author', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['author'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_number_comment_display',
            'type'     => 'switch',
            'title'    => __( 'Comment Number', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['comment'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_like_display',
            'type'     => 'switch',
            'title'    => __( 'Like Number', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['like'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_view_display',
            'type'     => 'switch',
            'title'    => __( 'View Number', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['view'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_share_display',
            'type'     => 'switch',
            'title'    => __( 'Share Button', 'feellio' ),
            'subtitle' => __( 'Customize at Theme Options => Socials => Social Share', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['share'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_category_display',
            'type'     => 'switch',
            'title'    => __( 'Category', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['category'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_tag_display',
            'type'     => 'switch',
            'title'    => __( 'Tags', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['tag'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_excerpt_display',
            'type'     => 'switch',
            'title'    => __( 'Excerpt', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['excerpt'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_config_number_excerpt_word',
            'type'     => 'text',
            'title'    => __( 'Number Excerpt Word', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['excerpt_word'],
        ),

        array(
            'id'       => 'wd_layout_blog_config_readmore_display',
            'type'     => 'switch',
            'title'    => __( 'Readmore Button', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['config']['default']['readmore'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog Single', 'feellio' ),
    'id'               => 'wd_layout_blog_single',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_blog_single_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['blog_single'],
        ),

        array(
            'id'       => 'wd_layout_blog_single_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['blog_single_left'],
            /*'required' => array('wd_layout_blog_single_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_blog_single_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['blog_single_right'],
            /*'required' => array('wd_layout_blog_single_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_blog_single_author_information',
            'type'     => 'switch',
            'title'    => __( 'Author Information', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['single']['default']['author'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
            'id'       => 'wd_layout_blog_single_previous_next_button',
            'type'     => 'switch',
            'title'    => __( 'Previous/Next Button', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['single']['default']['previous_next'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),

        array(
           'id'       => 'wd_layout_blog_single_recent_post_section_start',
            'type'     => 'section',
            'title'    => __( 'Related Blog Settings', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'indent'   => true,
        ),
        /****************************/ 
            array(
                'id'       => 'wd_layout_blog_single_recent_post',
                'type'     => 'switch',
                'title'    => __( 'Related Blog', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'default'  => $wd_default_data['blog']['single']['default']['recent'],
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'wd_layout_blog_single_recent_post_columns',
                'type'     => 'text',
                'title'    => __( 'Columns', 'feellio' ),
                'subtitle' => __( 'Number of columns displayed with slider', 'feellio' ),
                'desc'     => __( '', 'feellio' ),
                'default'  => $wd_default_data['columns']['default']['blog_recent'],
            ),
        /****************************/
        
        array(
            'id'     => 'wd_layout_blog_single_recent_post_section_end',
            'type'   => 'section',
            'indent' => false,
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog Archive', 'feellio' ),
    'id'               => 'wd_layout_blog_archive',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array(
        array(
            'id'       => 'wd_layout_blog_archive_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['blog_archive'],
        ),

        array(
            'id'       => 'wd_layout_blog_archive_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['blog_archive_left'],
            /*'required' => array('wd_layout_blog_archive_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_blog_archive_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['blog_archive_right'],
            /*'required' => array('wd_layout_blog_archive_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),
        array(
            'id'       => 'wd_layout_blog_archive_toggle_layout',
            'type'     => 'switch',
            'title'    => __( 'Toggle Layout Button', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['archive']['default']['toggle_layout'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        array(
            'id'       => 'wd_layout_blog_archive_style',
            'type'     => 'button_set',
            'title'    => __( 'Default Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['blog']['archive']['choose']['style'],
            'default'  => $wd_default_data['blog']['archive']['default']['style'],
            'required' => array('wd_layout_blog_archive_toggle_layout','=',false),
        ),
        array(
            'id'       => 'wd_layout_blog_archive_columns',
            'type'     => 'text',
            'title'    => __( 'Columns', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['archive']['default']['columns'],
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog Default (Index)', 'feellio' ),
    'id'               => 'wd_layout_blog_default',
    'subsection'       => true,
    'customizer_width' => '450px',
    'desc'             => __( '', 'feellio' ),
    'fields'           => array( 
        array(
            'id'       => 'wd_layout_blog_default_layout',
            'type'     => 'image_select',
            'title'    => __( 'Select The Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['layout']['choose'],
            'default'  => $wd_default_data['layout']['default']['blog_default'],
        ),

        array(
            'id'       => 'wd_layout_blog_default_left_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Left Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['blog_default_left'],
            /*'required' => array('wd_layout_blog_default_layout','=',array( '1-0-0', '1-0-1' ) ),*/
        ),

        array(
            'id'       => 'wd_layout_blog_default_right_sidebar',
            'type'     => 'select',
            'title'    => __( 'Select Right Sidebar', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'data'     => 'sidebars',
            'default'  => $wd_default_data['sidebar']['default']['blog_default_right'],
            /*'required' => array('wd_layout_blog_default_layout','=',array( '0-0-1', '1-0-1' ) ),*/
        ),
        array(
            'id'       => 'wd_layout_blog_default_toggle_layout',
            'type'     => 'switch',
            'title'    => __( 'Toggle Layout Button', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['index']['default']['toggle_layout'],
            'on'       => 'Show',
            'off'      => 'Hide',
        ),
        array(
            'id'       => 'wd_layout_blog_default_style',
            'type'     => 'button_set',
            'title'    => __( 'Default Layout', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'options'  => $wd_default_data['blog']['index']['choose']['style'],
            'default'  => $wd_default_data['blog']['index']['default']['style'],
            'required' => array('wd_layout_blog_default_toggle_layout','=',false),
        ),
        array(
            'id'       => 'wd_layout_blog_default_columns',
            'type'     => 'text',
            'title'    => __( 'Columns', 'feellio' ),
            'subtitle' => __( '', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => $wd_default_data['blog']['index']['default']['columns'],
        ),
    )
) );