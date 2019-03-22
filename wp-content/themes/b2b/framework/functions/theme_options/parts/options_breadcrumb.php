<?php 
if (class_exists('WD_Breadcrumb')) {
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Breadcrumb', 'feellio' ),
        'id'               => 'wd_breadcrumb',
        'desc'             => __( '', 'feellio' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-puzzle',
        'fields'     => array(
            /******************************** BREADCRUMB GENERAL *******************************/
            array(
                'id'       => 'wd_breadcrumb_general_section_start',
                'type'     => 'section',
                'title'    => __( 'General', 'feellio' ),
                'subtitle' => __( '', 'feellio' ),
                'indent'   => true,
            ),

            /****************************/
                array(
                    'id'       => 'wd_breadcrumb_type',
                    'type'     => 'radio',
                    'title'    => __( 'Select The Layout', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['type'],
                    'default'  => $wd_default_data['breadcrumb']['default']['type'],
                ),

                array(
                    'id'       => 'wd_breadcrumb_background_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Background Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['bg_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['bg_color'],
                    'required' => array('wd_breadcrumb_type', '=', 'breadcrumb_default'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_background',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Background', 'feellio' ),
                    'compiler' => 'true',
                    'desc'     => __( '', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => array('url'  => $wd_default_data['breadcrumb']['default']['background']),
                    'required' => array('wd_breadcrumb_type', '=', 'breadcrumb_banner'),
                ),
                array(
                    'id'             => 'wd_breadcrumb_height',
                    'type'           => 'dimensions',
                    'units'          => false,    // You can specify a unit value. Possible: px, em, %
                    'width'          => false,   
                    'units_extended' => 'true',  // Allow users to select any type of unit
                    'title'          => __( 'Height', 'feellio' ),
                    'subtitle'       => __( '', 'feellio' ),
                    'desc'           => __( 'Unit: pixels', 'feellio' ),
                    'default'        => array('height' => $wd_default_data['breadcrumb']['default']['height']),
                    'required' => array('wd_breadcrumb_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_text_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Title & Slug Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['text_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['text_color'],
                    'required' => array('wd_breadcrumb_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_text_style',
                    'type'     => 'radio',
                    'title'    => __( 'Title & Slug Style', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_style'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_style'],
                    'required' => array('wd_breadcrumb_type', '!=', 'no_breadcrumb'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_text_align',
                    'type'     => 'select',
                    'title'    => __( 'Text Align', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_align'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_align'],
                    'required' => array('wd_breadcrumb_text_style', '=', 'block'),
                ),
            /****************************/
            array(
                'id'     => 'wd_breadcrumb_general_section_end',
                'type'   => 'section',
                'indent' => false,
            ),
            /******************************** BREADCRUMB ARCHIVE BLOG *******************************/
            array(
                'id'       => 'wd_breadcrumb_archive_blog_section_start',
                'type'     => 'section',
                'title'    => __( 'Blog Archive', 'feellio' ),
                'subtitle' => __( 'Disable this if you want to use the settings in the General section', 'feellio' ),
                'indent'   => true,
            ),

            /****************************/
                array(
                    'id'       => 'wd_breadcrumb_archive_blog_customize',
                    'type'     => 'switch',
                    'title'    => __( 'Customize', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => false,
                    'on'       => 'Enable',
                    'off'      => 'Disable',
                ),
                array(
                    'id'       => 'wd_breadcrumb_archive_blog_type',
                    'type'     => 'radio',
                    'title'    => __( 'Select The Layout', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['type'],
                    'default'  => $wd_default_data['breadcrumb']['default']['type'],
                    'required' => array('wd_breadcrumb_archive_blog_customize', '=', '1'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_blog_background_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Background Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['bg_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['bg_color'],
                    'required' => array('wd_breadcrumb_archive_blog_type', '=', 'breadcrumb_default'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_blog_background',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Background', 'feellio' ),
                    'compiler' => 'true',
                    'desc'     => __( '', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => array('url'  => $wd_default_data['breadcrumb']['default']['background']),
                    'required' => array('wd_breadcrumb_archive_blog_type', '=', 'breadcrumb_banner'),
                ),
                array(
                    'id'             => 'wd_breadcrumb_archive_blog_height',
                    'type'           => 'dimensions',
                    'units'          => false,    // You can specify a unit value. Possible: px, em, %
                    'width'          => false,   
                    'units_extended' => 'true',  // Allow users to select any type of unit
                    'title'          => __( 'Height', 'feellio' ),
                    'subtitle'       => __( '', 'feellio' ),
                    'desc'           => __( 'Unit: pixels', 'feellio' ),
                    'default'        => array('height' => $wd_default_data['breadcrumb']['default']['height']),
                    'required'       => array('wd_breadcrumb_archive_blog_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_archive_blog_text_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Title & Slug Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['text_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['text_color'],
                    'required' => array('wd_breadcrumb_archive_blog_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_archive_blog_text_style',
                    'type'     => 'radio',
                    'title'    => __( 'Title & Slug Style', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_style'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_style'],
                    'required' => array('wd_breadcrumb_archive_blog_type', '!=', 'no_breadcrumb'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_blog_text_align',
                    'type'     => 'select',
                    'title'    => __( 'Text Align', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_align'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_align'],
                    'required' => array('wd_breadcrumb_archive_blog_text_style', '=', 'block'),
                ),
            /****************************/
            array(
                'id'     => 'wd_breadcrumb_archive_blog_section_end',
                'type'   => 'section',
                'indent' => false,
            ),

            /******************************** SEARCH PAGE *******************************/
            array(
                'id'       => 'wd_breadcrumb_search_page_section_start',
                'type'     => 'section',
                'title'    => __( 'Search Page', 'feellio' ),
                'subtitle' => __( 'Disable this if you want to use the settings in the General section', 'feellio' ),
                'indent'   => true,
            ),

            /****************************/
                array(
                    'id'       => 'wd_breadcrumb_search_page_customize',
                    'type'     => 'switch',
                    'title'    => __( 'Customize', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => false,
                    'on'       => 'Enable',
                    'off'      => 'Disable',
                ),
                array(
                    'id'       => 'wd_breadcrumb_search_page_type',
                    'type'     => 'radio',
                    'title'    => __( 'Select The Layout', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['type'],
                    'default'  => $wd_default_data['breadcrumb']['default']['type'],
                    'required' => array('wd_breadcrumb_search_page_customize', '=', '1'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_search_page_background_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Background Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['bg_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['bg_color'],
                    'required' => array('wd_breadcrumb_search_page_type', '=', 'breadcrumb_default'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_search_page_background',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Background', 'feellio' ),
                    'compiler' => 'true',
                    'desc'     => __( '', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => array('url'  => $wd_default_data['breadcrumb']['default']['background']),
                    'required' => array('wd_breadcrumb_search_page_type', '=', 'breadcrumb_banner'),
                ),
                array(
                    'id'             => 'wd_breadcrumb_search_page_height',
                    'type'           => 'dimensions',
                    'units'          => false,    // You can specify a unit value. Possible: px, em, %
                    'width'          => false,   
                    'units_extended' => 'true',  // Allow users to select any type of unit
                    'title'          => __( 'Height', 'feellio' ),
                    'subtitle'       => __( '', 'feellio' ),
                    'desc'           => __( 'Unit: pixels', 'feellio' ),
                    'default'        => array('height' => $wd_default_data['breadcrumb']['default']['height']),
                    'required' => array('wd_breadcrumb_search_page_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_search_page_text_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Title & Slug Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['text_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['text_color'],
                    'required' => array('wd_breadcrumb_search_page_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_search_page_text_style',
                    'type'     => 'radio',
                    'title'    => __( 'Title & Slug Style', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_style'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_style'],
                    'required' => array('wd_breadcrumb_search_page_type', '!=', 'no_breadcrumb'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_search_page_text_align',
                    'type'     => 'select',
                    'title'    => __( 'Text Align', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_align'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_align'],
                    'required' => array('wd_breadcrumb_search_page_text_style', '=', 'block'),
                ),
            /****************************/
            array(
                'id'     => 'wd_breadcrumb_search_page_section_end',
                'type'   => 'section',
                'indent' => false,
            ),

            /******************************** BREADCRUMB ARCHIVE PRODUCT *******************************/
            array(
                'id'       => 'wd_breadcrumb_archive_product_section_start',
                'type'     => 'section',
                'title'    => __( 'Product Archive', 'feellio' ),
                'subtitle' => __( 'Use for Product Taxonomy Archive/Product Category. Disable this if you want to use the settings in the General section', 'feellio' ),
                'indent'   => true,
            ),

            /****************************/
                array(
                    'id'       => 'wd_breadcrumb_archive_product_customize',
                    'type'     => 'switch',
                    'title'    => __( 'Customize', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => false,
                    'on'       => 'Enable',
                    'off'      => 'Disable',
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_product_type',
                    'type'     => 'radio',
                    'title'    => __( 'Select The Layout', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['type'],
                    'default'  => $wd_default_data['breadcrumb']['default']['type'],
                    'required' => array('wd_breadcrumb_archive_product_customize', '=', '1'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_product_background_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Background Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['bg_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['bg_color'],
                    'required' => array('wd_breadcrumb_archive_product_type', '=', 'breadcrumb_default'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_product_background',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Background', 'feellio' ),
                    'compiler' => 'true',
                    'desc'     => __( '', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => array('url'  => $wd_default_data['breadcrumb']['default']['background']),
                    'required' => array('wd_breadcrumb_archive_product_type', '=', 'breadcrumb_banner'),
                ),
                array(
                    'id'             => 'wd_breadcrumb_archive_product_height',
                    'type'           => 'dimensions',
                    'units'          => false,    // You can specify a unit value. Possible: px, em, %
                    'width'          => false,   
                    'units_extended' => 'true',  // Allow users to select any type of unit
                    'title'          => __( 'Height', 'feellio' ),
                    'subtitle'       => __( '', 'feellio' ),
                    'desc'           => __( 'Unit: pixels', 'feellio' ),
                    'default'        => array('height' => $wd_default_data['breadcrumb']['default']['height']),
                    'required'       => array('wd_breadcrumb_archive_product_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_archive_product_text_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Title & Slug Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['text_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['text_color'],
                    'required' => array('wd_breadcrumb_archive_product_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_archive_product_text_style',
                    'type'     => 'radio',
                    'title'    => __( 'Title & Slug Style', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_style'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_style'],
                    'required' => array('wd_breadcrumb_archive_product_type', '!=', 'no_breadcrumb'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_archive_product_text_align',
                    'type'     => 'select',
                    'title'    => __( 'Text Align', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_align'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_align'],
                    'required' => array('wd_breadcrumb_archive_product_text_style', '=', 'block'),
                ),
            /****************************/
            array(
                'id'     => 'wd_breadcrumb_archive_product_section_end',
                'type'   => 'section',
                'indent' => false,
            ),

            /******************************** WOOCOMMERCE SPECIAL PAGE PAGE *******************************/
            array(
                'id'        => 'wd_breadcrumb_woo_special_page_section_start',
                'type'     => 'section',
                'title'    => __( 'Woocommerce Special Page', 'feellio' ),
                'subtitle' => __( 'Use for Cart/Checkout page. Disable this if you want to use the settings in the General section', 'feellio' ),
                'indent'   => true,
            ),

            /****************************/
                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_customize',
                    'type'     => 'switch',
                    'title'    => __( 'Customize', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => false,
                    'on'       => 'Enable',
                    'off'      => 'Disable',
                ),
                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_type',
                    'type'     => 'radio',
                    'title'    => __( 'Select The Layout', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['type'],
                    'default'  => $wd_default_data['breadcrumb']['default']['type'],
                    'required' => array('wd_breadcrumb_woo_special_page_customize', '=', '1'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_background_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Background Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['bg_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['bg_color'],
                    'required' => array('wd_breadcrumb_woo_special_page_type', '=', 'breadcrumb_default'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_background',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( 'Background', 'feellio' ),
                    'compiler' => 'true',
                    'desc'     => __( '', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'default'  => array('url'  => $wd_default_data['breadcrumb']['default']['background']),
                    'required' => array('wd_breadcrumb_woo_special_page_type', '=', 'breadcrumb_banner'),
                ),
                array(
                    'id'             => 'wd_breadcrumb_woo_special_page_height',
                    'type'           => 'dimensions',
                    'units'          => false,    // You can specify a unit value. Possible: px, em, %
                    'width'          => false,   
                    'units_extended' => 'true',  // Allow users to select any type of unit
                    'title'          => __( 'Height', 'feellio' ),
                    'subtitle'       => __( '', 'feellio' ),
                    'desc'           => __( 'Unit: pixels', 'feellio' ),
                    'default'        => array('height' => $wd_default_data['breadcrumb']['default']['height']),
                    'required' => array('wd_breadcrumb_woo_special_page_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_text_color',
                    'type'     => 'color',
                    'transparent'=> false,
                    'title'    => __( 'Title & Slug Color', 'feellio' ),
                    'subtitle' => sprintf(__( '(Default: %s).', 'feellio' ), $wd_default_data['breadcrumb']['default']['text_color']),
                    'default'  => $wd_default_data['breadcrumb']['default']['text_color'],
                    'required' => array('wd_breadcrumb_woo_special_page_type', '!=', 'no_breadcrumb'),
                ),
                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_text_style',
                    'type'     => 'radio',
                    'title'    => __( 'Title & Slug Style', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_style'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_style'],
                    'required' => array('wd_breadcrumb_woo_special_page_type', '!=', 'no_breadcrumb'),
                ),

                array(
                    'id'       => 'wd_breadcrumb_woo_special_page_text_align',
                    'type'     => 'select',
                    'title'    => __( 'Text Align', 'feellio' ),
                    'subtitle' => __( '', 'feellio' ),
                    'desc'     => __( '', 'feellio' ),
                    'options'  => $wd_default_data['breadcrumb']['choose']['text_align'],
                    'default'  => $wd_default_data['breadcrumb']['default']['text_align'],
                    'required' => array('wd_breadcrumb_woo_special_page_text_style', '=', 'block'),
                ),
            /****************************/
            array(
                'id'     => 'wd_breadcrumb_woo_special_page_section_end',
                'type'   => 'section',
                'indent' => false,
            ),
        )
    ) );
}