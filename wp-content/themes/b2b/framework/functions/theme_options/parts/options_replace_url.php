<?php 
Redux::setSection( $opt_name, array(
    'title'            => __( 'Replace URL', 'feellio' ),
    'id'               => 'wd_replace_url',
    'desc'             => __( 'Search for and replace the path of all the photo options in the Theme Option panel and database.<br/> If you do not get the wrong path after the domain name conversion, do not worry about this part.', 'feellio' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-link',
    'fields'     	   => array(
        array(
            'id'       => 'wd_replace_url_old',
            'type'     => 'text',
            'title'    => __( 'Old URL String', 'feellio' ),
            'placeholder' => __( 'Enter URL without \'/\' at the end', 'feellio' ),
            'desc'     => '',
            'default'  => '',
        ),
        array(
            'id'       => 'wd_replace_url_new',
            'type'     => 'text',
            'title'    => __( 'New URL String', 'feellio' ),
            'subtitle' => __( 'Leave blank if you want to use Site URL', 'feellio' ),
            'placeholder' => __( 'Enter URL without \'/\' at the end', 'feellio' ),
            'desc'     => sprintf(__( 'Site URL: <strong>%s</strong>', 'feellio' ), get_option( "siteurl", "" )),
            'default'  => '',
        ),
        array(
            'id'       => 'wd_replace_url_image_theme_option',
            'type'     => 'checkbox',
            'title'    => __( 'Theme Option Image', 'feellio' ),
            'subtitle' => __( 'This option will replace all image URL set in the Theme Option', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => '1'// 1 = on | 0 = off
        ),
        array(
            'id'       => 'wd_replace_url_site_database',
            'type'     => 'checkbox',
            'title'    => __( 'Database', 'feellio' ),
            'subtitle' => __( 'This option will replace site URL and something in the database', 'feellio' ),
            'desc'     => __( '', 'feellio' ),
            'default'  => '0'// 1 = on | 0 = off
        ),
    ) 
) );

?>