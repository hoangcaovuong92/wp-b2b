<?php
$post_id  			= apply_filters('wd_filter_global_post_id', '');
$html_header 		= apply_filters('wd_filter_header_choices', array('value_return' => 'name'));
$html_footer 		= apply_filters('wd_filter_footer_choices', array('value_return' => 'name'));
$_layout_config 	= apply_filters('wd_filter_post_layout_config', $post_id);
$selected_header 	= get_post_meta( $post_id, '_wd_custom_header', true );
$selected_footer 	= get_post_meta( $post_id, '_wd_custom_footer', true );
$default_breadcrumb_img = WD_THEME_IMAGES.'/banner_breadcrumb.jpg';
wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' ); ?>

<div class="wd-layout-config-wrapper">
	<?php 
	$metabox_manager = new WD_Metaboxes;
	$display_class 	= (get_current_screen()->id != 'page') ? 'hidden' : '';
	$metabox_manager->get_header($html_header, $selected_header);
	$metabox_manager->get_footer($html_footer, $selected_footer);
	$metabox_manager->get_layout($_layout_config["layout"]);
	$metabox_manager->get_sidebar('left', $_layout_config["left_sidebar"]);
	$metabox_manager->get_sidebar('right', $_layout_config["right_sidebar"]);
	$metabox_manager->get_breadcrumb_style($_layout_config["style_breadcrumb"]);
	$metabox_manager->get_breadcrumb_image($_layout_config["image_breadcrumb"], $default_breadcrumb_img);
	$metabox_manager->get_custom_class($_layout_config["custom_class"], $display_class);
	$metabox_manager->get_custom_id($_layout_config["custom_id"], $display_class); ?>
</div>