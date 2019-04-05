<?php
if ( ! function_exists( 'wd_pages_list_function' ) ) {
	function wd_pages_list_function( $atts ) {
		extract(shortcode_atts( array(
			'ids'				=> '-1',
			'style'				=> 'vertical',
			'copyright'			=> '0',
			'copyright_text'	=> sprintf(__( '© 2019 by %s. All rights reserved.', 'wd_package' ), esc_html( get_bloginfo('name'))),
			'fullwidth_mode' 	=> false,
			'class'      		=> '',
		), $atts ));

		if ($ids == '-1' || $ids == '') return;
		$list_pages_id 	= array_filter(explode(',', $ids));

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
		ob_start(); ?>
        <div class="wd-shortcode wd-shortcode-pages-list <?php echo esc_attr($style); ?> <?php echo esc_attr($class); ?>">
        	<ul>
				<?php foreach ( $list_pages_id as $page_id ) { ?>
					<?php if ($page_id && !is_wp_error(get_post($page_id)) && get_post_field( 'post_name', $page_id )): ?>
			       		<li class="<?php echo 'wd-page-slug-'.get_post_field( 'post_name', $page_id ); ?>"><a href="<?php echo get_page_link($page_id); ?>"><?php echo get_the_title( $page_id ); ?></a></li>
			       	<?php endif ?>
			    <?php } ?>
			    <?php if ($copyright){
					echo apply_filters('wd_filter_copyright', array('copyright' => $copyright_text, 'list_item' => true)); 
				} ?>
			</ul>
        </div>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}

if (!function_exists('wd_pages_list_vc_map')) {
	function wd_pages_list_vc_map() {
		return array(
			'name'        => __( "WD - Pages List", 'wd_package' ),
			'description' => __( "Display pages link with list style...", 'wd_package' ),
			'base'        => 'wd_pages_list',
			"category"    => esc_html__("WD - Content", 'wd_package'),
			'icon'        => 'icon-wpb-ui-accordion',
			'params'      => array(
				/*-----------------------------------------------------------------------------------
					Categories
				-------------------------------------------------------------------------------------*/
				array(
					'type' 			=> 'sorted_list',
					'heading' 		=> __( 'Categories', 'wd_package' ),
					'param_name' 	=> 'ids',
					'description' 	=> __( 'Select and sort pages.', 'wd_package' ),
					'value' 		=> '-1',
					'options' 		=> wd_vc_get_list_pages('sorted_list'),
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Style', 'wd_package' ),
					'param_name' 	=> 'style',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Vertical', 'wd_package' ) 		=> 'vertical',
						esc_html__( 'Horizontal', 'wd_package' ) 	=> 'horizontal',
					),
					'std'			=> 'vertical',
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Display Copyright?", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "copyright",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "textarea",
					"class" 		=> "",
					"heading" 		=> esc_html__("Copyright Text", 'wd_package'),
					"description" 	=> esc_html__("", 'wd_package'),
					"param_name" 	=> "copyright_text",
					"value" 		=> sprintf(__( 'Copyright %s. All rights reserved.', 'wd_package' ), esc_html( get_bloginfo('name')) ), 
					'dependency'  	=> array('element' => "copyright", 'value' => array('1')),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( "Extra class name", 'wd_package' ),
					'description' => __( "Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package' ),
					'admin_label' => true,
					'param_name'  => 'class',
					'value'       => '',
				),
			)
		);
	}
}