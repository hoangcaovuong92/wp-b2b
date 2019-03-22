<?php
	$post_id  			= apply_filters('wd_filter_global_post_id', '');
	$_layout_config 	= apply_filters('wd_filter_post_layout_config', $post_id);
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
?>
<div class="wd-layout-config-wrapper">
	<?php 
	$metabox_manager = new WD_Metaboxes;
	$metabox_manager->get_custom_class($_layout_config["custom_class"]);
	$metabox_manager->get_custom_id($_layout_config["custom_id"]); ?>
</div>