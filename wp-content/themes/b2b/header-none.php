<!DOCTYPE html>
<html itemscope <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php 
		/**
		 * wd_hook_header_meta_data hook.
		 *
		 * @hooked display_favicon - 5
		 * @hooked facebook_comment_setting_meta - 10
		 * @hooked display_banner - 15
		*/
		do_action('wd_hook_header_meta_data'); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php 
		/**
		 * wd_hook_after_opening_body_tag hook.
		 *
		 * @hooked facebook_api - 5
		 * @hooked loading_page_effect - 10
		 * @hooked pushmenu_mobile - 15
		 */ 
		do_action('wd_hook_after_opening_body_tag'); ?>
		<div class="body-wrapper">
			<header id="header" class="header">
				<div class="wd-header-content-wrap wd-header-mobile wd-mobile-screen">
					<?php
					/**
					 * wd_hook_header_mobile hook.
					 *
					 * @hooked html_header_mobile_init - 5
					 */ 
					do_action('wd_hook_header_mobile'); ?>
				</div>
			</header> <!-- END HEADER  -->