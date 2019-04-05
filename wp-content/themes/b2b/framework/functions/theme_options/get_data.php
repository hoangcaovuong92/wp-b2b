<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 
if (!class_exists('WD_Get_Theme_Options')) {
	class WD_Get_Theme_Options {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		// Ensure construct function is called only once
		private static $called = false;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private $theme_option_obj_name = 'wd_theme_options';
		private $pre = 'wd_';

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;

			// echo apply_filters('wd_filter_get_data_package', $template); //Get package datas
			add_filter( 'wd_filter_get_data_package', array($this, 'get_data_package' ), 10, 2);

			//Get a value from Theme Option Setting
			//echo apply_filters('wd_filter_get_option_value', $keyname, $default_value, $type); 
			add_filter( 'wd_filter_get_option_value', array($this, 'get_option_value' ), 10, 2);

			//Action after save Theme Option
			add_action('redux/options/'.$this->theme_option_obj_name.'/saved', array($this, 'theme_option_after_save'));
		}

		public function get_data_package( $template = 'all' ) {
			/* DATA SETTING */ 
			$wd_default_data = apply_filters('wd_filter_defaut_data', 'all');
			$data = array(
				'background' => array(
					'bg_display'	  		=> $this->get_option_value('bg_body_display', $wd_default_data['general']['default']['bg_display']),  
					'bg_config'	  			=> $this->get_option_value('bg_body', $wd_default_data['general']['default']['bg_body']),  
				),
	
				'favicon' => array(
					'icon'	  				=> $this->get_option_value('favicon', $wd_default_data['general']['default']['favicon'], 'image'), 
				),
	
				'loading-effect' => array(
					'status'	  			=> $this->get_option_value('loading_effect_display', $wd_default_data['effects']['default']['loading']), 
					'style' 				=> $this->get_option_value('loading_effect_style', $wd_default_data['effects']['default']['loading_style']), 
				),
	
				'js-effect' => array(
					'sidebar_scroll'	  	=> $this->get_option_value('sidebar_fixed_effect_display', $wd_default_data['effects']['default']['sidebar_fixed']), 
					'scroll_smooth'	  		=> $this->get_option_value('scroll_smooth', $wd_default_data['effects']['default']['scroll_smooth']), 
					'scroll_smooth_step'	=> $this->get_option_value('scroll_smooth_step', $wd_default_data['effects']['default']['scroll_smooth_step']), 
					'scroll_smooth_speed'	=> $this->get_option_value('scroll_smooth_speed', $wd_default_data['effects']['default']['scroll_smooth_speed']), 
				),

				'google_map' => array(
					'api_key'	  			=> $this->get_option_value('google_map_api_key', $wd_default_data['google_map']['default']['api_key']),  
					'zoom'	  				=> $this->get_option_value('google_map_zoom', $wd_default_data['google_map']['default']['zoom']),  
				),
	
				'myaccount-url' => array(
					'login_url'	  			=> $this->get_option_value('page_login_url', ''),
					'register_url'	  		=> $this->get_option_value('page_register_url', ''), 
					'forgot_password_url'	=> $this->get_option_value('page_forgot_password_url', ''),
				),

				'social-link' => array(
					'rss_id'				=> $this->get_option_value('social_link_rss_id', $wd_default_data['social_link']['default']['rss_id']),
					'twitter_id'	  		=> $this->get_option_value('social_link_twitter_id', $wd_default_data['social_link']['default']['twitter_id']), 
					'facebook_id'			=> $this->get_option_value('social_link_facebook_id', $wd_default_data['social_link']['default']['facebook_id']),
					'google_id'				=> $this->get_option_value('social_link_google_id', $wd_default_data['social_link']['default']['google_id']),
					'pin_id'				=> $this->get_option_value('social_link_pin_id', $wd_default_data['social_link']['default']['pin_id']),
					'youtube_id'			=> $this->get_option_value('social_link_youtube_id', $wd_default_data['social_link']['default']['youtube_id']),
					'instagram_id'			=> $this->get_option_value('social_link_instagram_id', $wd_default_data['social_link']['default']['instagram_id']),
				),

				'instagram' => array(
					'insta_user'			=> $this->get_option_value('social_instagram_user', $wd_default_data['social_instagram']['default']['insta_user']),
					'insta_client_id'		=> $this->get_option_value('social_instagram_client_id', $wd_default_data['social_instagram']['default']['insta_client_id']),
					'insta_access_token'	=> $this->get_option_value('social_instagram_access_token', $wd_default_data['social_instagram']['default']['insta_access_token']),
				),

				'nav-user' => array(
					'layout_desktop'		=> $this->get_option_value('nav_user_desktop_sorter', $wd_default_data['nav_user']['default']['sorter_desktop']),
					'layout_mobile'	  		=> $this->get_option_value('nav_user_mobile_sorter', $wd_default_data['nav_user']['default']['sorter_mobile']), 
					'layout_pushmenu'	  	=> $this->get_option_value('nav_user_pushmenu_sorter', $wd_default_data['nav_user']['default']['sorter_pushmenu']), 
					'show_icon'				=> $this->get_option_value('nav_user_show_icon', $wd_default_data['nav_user']['default']['show_icon']),
					'show_text'				=> $this->get_option_value('nav_user_show_text', $wd_default_data['nav_user']['default']['show_text']),
					'dropdown_position'		=> $this->get_option_value('nav_user_dropdown_position', $wd_default_data['nav_user']['default']['dropdown_position']),
				),

				'megamenu' => array(
					'layout'				=> $this->get_option_value('megamenu_layout', $wd_default_data['menu']['megamenu']['default']['layout']),
					'vertical_submenu_position'	=> $this->get_option_value('megamenu_vertical_submenu_position', $wd_default_data['menu']['megamenu']['default']['vertical_submenu_position']),
					'menu_container'		=> $this->get_option_value('megamenu_menu_container', $wd_default_data['menu']['megamenu']['default']['menu_container']),
					'style'					=> $this->get_option_value('megamenu_style', $wd_default_data['menu']['megamenu']['default']['style']),
					'hover_style'			=> $this->get_option_value('megamenu_hover_style', $wd_default_data['menu']['megamenu']['default']['hover_style']),
					'type'					=> $this->get_option_value('megamenu_type', $wd_default_data['menu']['megamenu']['default']['type']),
					'menu_theme_location'	=> $this->get_option_value('megamenu_menu_theme_location', $wd_default_data['menu']['megamenu']['default']['menu_theme_location']),
					'integrate_specific_menu' => $this->get_option_value('megamenu_integrate_specific_menu', $wd_default_data['menu']['megamenu']['default']['integrate_specific_menu']),
				),
	
				'accessibility' => array(
					'chatbox_status'	  	=> $this->get_option_value('facebook_chatbox_display', $wd_default_data['fb_chatbox']['default']['display']),
					'popup_email'	  		=> $this->get_option_value('email_subscriber_popup_display', $wd_default_data['email_popup']['default']['display']), 
					'popup_only_home'	  	=> $this->get_option_value('email_subscriber_popup_only_home', $wd_default_data['email_popup']['default']['only_home']),
					'popup_mobile'	  		=> $this->get_option_value('email_subscriber_popup_mobile', $wd_default_data['email_popup']['default']['popup_mobile']),
					'popup_email_width'	  	=> $this->get_option_value('email_subscriber_popup_width', $wd_default_data['email_popup']['default']['width']), 
					'popup_email_height'	=> $this->get_option_value('email_subscriber_popup_height', $wd_default_data['email_popup']['default']['height']), 
					'popup_delay_time'	  	=> $this->get_option_value('email_subscriber_popup_delay_time', $wd_default_data['email_popup']['default']['delay_time']),
				),
	
				'facebook-api' => array(
					'user_id'	  			=> $this->get_option_value('comment_facebook_user_id', $wd_default_data['general']['default']['user_id']), 
					'app_id'	  			=> $this->get_option_value('comment_facebook_app_id', $wd_default_data['general']['default']['app_id']),
					'comment_status'	  	=> $this->get_option_value('comment_sorter', $wd_default_data['comment']['default']['sorter']),
					'chatbox_status'	  	=> $this->get_option_value('facebook_chatbox_display', $wd_default_data['fb_chatbox']['default']['display']),
				),
	
				'facebook-chatbox' => array(
					'chatbox_status'	  	=> $this->get_option_value('facebook_chatbox_display', $wd_default_data['fb_chatbox']['default']['display']),
					'url'	  				=> $this->get_option_value('facebook_chatbox_url', $wd_default_data['fb_chatbox']['default']['url']),
					'width'	  				=> $this->get_option_value('facebook_chatbox_width', $wd_default_data['fb_chatbox']['default']['width']),
					'height'	  			=> $this->get_option_value('facebook_chatbox_height', $wd_default_data['fb_chatbox']['default']['height']),
					'right'	  				=> $this->get_option_value('facebook_chatbox_right', $wd_default_data['fb_chatbox']['default']['right']),
					'bottom'	  			=> $this->get_option_value('facebook_chatbox_bottom', $wd_default_data['fb_chatbox']['default']['bottom']),
					'default_mode'	  		=> $this->get_option_value('facebook_chatbox_default_mode', $wd_default_data['fb_chatbox']['default']['default_mode']),
					'bg_color'	  			=> $this->get_option_value('facebook_chatbox_bg_color', $wd_default_data['fb_chatbox']['default']['bg_color']),
					'logo'	  				=> $this->get_option_value('facebook_chatbox_logo', $wd_default_data['fb_chatbox']['default']['logo']),
					'text_footer'	  		=> $this->get_option_value('facebook_chatbox_text_footer', $wd_default_data['fb_chatbox']['default']['text_footer']),
					'link_caption'	  		=> $this->get_option_value('facebook_chatbox_link_caption', $wd_default_data['fb_chatbox']['default']['link_caption']),
					'link_url'	  			=> $this->get_option_value('facebook_chatbox_link_url', $wd_default_data['fb_chatbox']['default']['link_url']),
				),
	
				'email-popup' => array(
					'display'	  			=> $this->get_option_value('email_subscriber_popup_display', $wd_default_data['email_popup']['default']['display']), 
					'popup_only_home'	  	=> $this->get_option_value('email_subscriber_popup_only_home', $wd_default_data['email_popup']['default']['only_home']),
					'popup_mobile'	  		=> $this->get_option_value('email_subscriber_popup_mobile', $wd_default_data['email_popup']['default']['popup_mobile']),
					'delay_time'	  		=> $this->get_option_value('email_subscriber_popup_delay_time', $wd_default_data['email_popup']['default']['delay_time']), 
					'session_expire'	  	=> $this->get_option_value('email_subscriber_popup_session_expire', $wd_default_data['email_popup']['default']['session_expire']), 
					'banner'	  			=> $this->get_option_value('email_subscriber_popup_banner', $wd_default_data['email_popup']['default']['banner']), 
					'source'	  			=> $this->get_option_value('email_subscriber_popup_source', $wd_default_data['email_popup']['default']['source']), 
					'custom_content'	  	=> $this->get_option_value('email_subscriber_popup_custom_content', $wd_default_data['email_popup']['default']['custom_content']), 
					'feedburner_id'	  		=> $this->get_option_value('email_subscriber_popup_feedburner_id', $wd_default_data['email_popup']['default']['feedburner_id']), 
					'width'	  				=> $this->get_option_value('email_subscriber_popup_width', $wd_default_data['email_popup']['default']['width']), 
					'height'	  			=> $this->get_option_value('email_subscriber_popup_height', $wd_default_data['email_popup']['default']['height']), 
					'title'	  				=> $this->get_option_value('email_subscriber_popup_title', $wd_default_data['email_popup']['default']['title']), 
					'desc'	  				=> $this->get_option_value('email_subscriber_popup_desc', $wd_default_data['email_popup']['default']['desc']), 
					'placeholder'	  		=> $this->get_option_value('email_subscriber_popup_placeholder', $wd_default_data['email_popup']['default']['placeholder']), 
					'button_text'	  		=> $this->get_option_value('email_subscriber_popup_button_text', $wd_default_data['email_popup']['default']['button_text']), 
				),
	
				'comment' => array(
					'layout_style'	  		=> $this->get_option_value('comment_layout_style', $wd_default_data['comment']['default']['layout']), 
					'comment_status'	  	=> $this->get_option_value('comment_sorter', $wd_default_data['comment']['default']['sorter']), 
					'comment_mode'	  		=> $this->get_option_value('comment_facebook_mode', $wd_default_data['comment']['default']['mode']), 
					'num_comment'	  		=> $this->get_option_value('comment_facebook_number_comment_display', $wd_default_data['comment']['default']['number_comment']),  
				),
	
				'comment-layout' => array(
					'display_tab'	  		=> $this->get_option_value('comment_layout_style', $wd_default_data['comment']['default']['layout']), 
				),
	
				'header-default' => array(
					'show_logo_title'	  	=> $this->get_option_value('header_show_site_title', $wd_default_data['header']['default']['site_title']),
					'logo_default'	  		=> $this->get_option_value('logo', $wd_default_data['general']['default']['logo'], 'image'),
					'logo_url'	  			=> $this->get_option_value('header_logo', $wd_default_data['general']['default']['logo'], 'image'),
					'show_logo'	  			=> $this->get_option_value('header_show_logo', $wd_default_data['header']['default']['show_logo']),
					'show_social'	  		=> $this->get_option_value('header_show_social', $wd_default_data['header']['default']['show_social']),
					'show_navuser'	  		=> $this->get_option_value('header_show_navuser', $wd_default_data['header']['default']['show_navuser']),
				),

				'main-menu' => array(
					'menu_location_desktop' => $this->get_option_value('menu_location_desktop', $wd_default_data['menu']['main_menu']['default']['menu_location_desktop']), 
					'menu_location_mobile' 	=> $this->get_option_value('menu_location_mobile', $wd_default_data['menu']['main_menu']['default']['menu_location_mobile']), 
				),

				'pushmenu' => array(
					'show_logo_title'	  	=> $this->get_option_value('header_show_site_title', $wd_default_data['header']['default']['site_title']),
					'logo_default'	  		=> $this->get_option_value('logo', $wd_default_data['general']['default']['logo'], 'image'),
					'logo_url'	  			=> $this->get_option_value('header_logo', $wd_default_data['general']['default']['logo'], 'image'),
					'pushmenu_panel_position' => $this->get_option_value('pushmenu_panel_position', $wd_default_data['menu']['pushmenu']['default']['panel_positon']),
				),
	
				'footer-default' => array(
					'logo_default'	  		=> $this->get_option_value('logo', $wd_default_data['general']['default']['logo'], 'image'),
					'logo_url'	  			=> $this->get_option_value('footer_logo', $wd_default_data['general']['default']['logo-footer'], 'image'),
					'instagram'	  			=> $this->get_option_value('footer_instagram', $wd_default_data['footer']['default']['instagram']),
					'social'	  			=> $this->get_option_value('footer_social', $wd_default_data['footer']['default']['social']),
					'copyright'	  			=> $this->get_option_value('footer_copyright', $wd_default_data['footer']['default']['copyright']),
				),

				'copyright' => array(
					'copyright' 			=> $this->get_option_value('footer_copyright_text', $wd_default_data['footer']['default']['copyright_text']),
				),
	
				'breadcrumb-default' => array(
					'layout_breadcrumbs'	=> $this->get_option_value('breadcrumb_type',  $wd_default_data['breadcrumb']['default']['type']),
					'image_breadcrumbs'		=> $this->get_option_value('breadcrumb_background', $wd_default_data['breadcrumb']['default']['background'], 'image'),
					'height'				=> $this->get_option_value('breadcrumb_height', $wd_default_data['breadcrumb']['default']['height'], 'height'),
					'color_breadcrumbs'		=> $this->get_option_value('breadcrumb_background_color', $wd_default_data['breadcrumb']['default']['bg_color']),
					'text_color'			=> $this->get_option_value('breadcrumb_text_color', $wd_default_data['breadcrumb']['default']['text_color']),
					'text_style'			=> $this->get_option_value('breadcrumb_text_style', $wd_default_data['breadcrumb']['default']['text_style']),
					'text_align'			=> $this->get_option_value('breadcrumb_text_align', $wd_default_data['breadcrumb']['default']['text_align']),
				),
					
				'breadcrumb-custom-setting' => array(
					'blog_archive'			=> $this->get_option_value('breadcrumb_archive_blog_customize', $wd_default_data['breadcrumb']['default']['blog_archive']),
					'product_archive'		=> $this->get_option_value('breadcrumb_archive_product_customize', $wd_default_data['breadcrumb']['default']['product_archive']),
					'woo_special_page'		=> $this->get_option_value('breadcrumb_woo_special_page_customize', $wd_default_data['breadcrumb']['default']['woo_special_page']),
					'search_page'			=> $this->get_option_value('breadcrumb_search_page_customize', $wd_default_data['breadcrumb']['default']['search_page']),
				),
	
				'breadcrumb-blog-archive' => array(
					'layout_breadcrumbs'	=> $this->get_option_value('breadcrumb_archive_blog_type',  $wd_default_data['breadcrumb']['default']['type']),
					'image_breadcrumbs'		=> $this->get_option_value('breadcrumb_archive_blog_background', $wd_default_data['breadcrumb']['default']['background'], 'image'),
					'height'				=> $this->get_option_value('breadcrumb_archive_blog_height', $wd_default_data['breadcrumb']['default']['height'], 'height'),
					'color_breadcrumbs'		=> $this->get_option_value('breadcrumb_archive_blog_background_color', $wd_default_data['breadcrumb']['default']['bg_color']),
					'text_color'			=> $this->get_option_value('breadcrumb_archive_blog_text_color', $wd_default_data['breadcrumb']['default']['text_color']),
					'text_style'			=> $this->get_option_value('breadcrumb_archive_blog_text_style', $wd_default_data['breadcrumb']['default']['text_style']),
					'text_align'			=> $this->get_option_value('breadcrumb_archive_blog_text_align', $wd_default_data['breadcrumb']['default']['text_align']),
				),
	
				'breadcrumb-product-archive' => array(
					'layout_breadcrumbs'	=> $this->get_option_value('breadcrumb_archive_product_type',  $wd_default_data['breadcrumb']['default']['type']),
					'image_breadcrumbs'		=> $this->get_option_value('breadcrumb_archive_product_background', $wd_default_data['breadcrumb']['default']['background'], 'image'),
					'height'				=> $this->get_option_value('breadcrumb_archive_product_height', $wd_default_data['breadcrumb']['default']['height'], 'height'),
					'color_breadcrumbs'		=> $this->get_option_value('breadcrumb_archive_product_background_color', $wd_default_data['breadcrumb']['default']['bg_color']),
					'text_color'			=> $this->get_option_value('breadcrumb_archive_product_text_color', $wd_default_data['breadcrumb']['default']['text_color']),
					'text_style'			=> $this->get_option_value('breadcrumb_archive_product_text_style', $wd_default_data['breadcrumb']['default']['text_style']),
					'text_align'			=> $this->get_option_value('breadcrumb_archive_product_text_align', $wd_default_data['breadcrumb']['default']['text_align']),
				),
	
				'breadcrumb-woo-special-page' => array(
					'layout_breadcrumbs'	=> $this->get_option_value('breadcrumb_woo_special_page_type',  $wd_default_data['breadcrumb']['default']['type']),
					'image_breadcrumbs'		=> $this->get_option_value('breadcrumb_woo_special_page_background', $wd_default_data['breadcrumb']['default']['background'], 'image'),
					'height'				=> $this->get_option_value('breadcrumb_woo_special_page_height', $wd_default_data['breadcrumb']['default']['height'], 'height'),
					'color_breadcrumbs'		=> $this->get_option_value('breadcrumb_woo_special_page_background_color', $wd_default_data['breadcrumb']['default']['bg_color']),
					'text_color'			=> $this->get_option_value('breadcrumb_woo_special_page_text_color', $wd_default_data['breadcrumb']['default']['text_color']),
					'text_style'			=> $this->get_option_value('breadcrumb_woo_special_page_text_style', $wd_default_data['breadcrumb']['default']['text_style']),
					'text_align'			=> $this->get_option_value('breadcrumb_woo_special_page_text_align', $wd_default_data['breadcrumb']['default']['text_align']),
				),
	
				'breadcrumb-search-page' => array(
					'layout_breadcrumbs'	=> $this->get_option_value('breadcrumb_search_page_type',  $wd_default_data['breadcrumb']['default']['type']),
					'image_breadcrumbs'		=> $this->get_option_value('breadcrumb_search_page_background', $wd_default_data['breadcrumb']['default']['background'], 'image'),
					'height'				=> $this->get_option_value('breadcrumb_search_page_height', $wd_default_data['breadcrumb']['default']['height'], 'height'),
					'color_breadcrumbs'		=> $this->get_option_value('breadcrumb_search_page_background_color', $wd_default_data['breadcrumb']['default']['bg_color']),
					'text_color'			=> $this->get_option_value('breadcrumb_search_page_text_color', $wd_default_data['breadcrumb']['default']['text_color']),
					'text_style'			=> $this->get_option_value('breadcrumb_search_page_text_style', $wd_default_data['breadcrumb']['default']['text_style']),
					'text_align'			=> $this->get_option_value('breadcrumb_search_page_text_align', $wd_default_data['breadcrumb']['default']['text_align']),
				),
	
				'default-page' => array(
					'layout' 				=> $this->get_option_value('layout_page_default_layout', $wd_default_data['layout']['default']['page_default']),
					'sidebar_left' 			=> $this->get_option_value('layout_page_default_left_sidebar', $wd_default_data['sidebar']['default']['page_default_left']),
					'sidebar_right' 		=> $this->get_option_value('layout_page_default_right_sidebar', $wd_default_data['sidebar']['default']['page_default_right']),
				),
	
				//archive.php
				'archive-blog' => array(
					'layout' 				=> $this->get_option_value('layout_blog_archive_layout', $wd_default_data['layout']['default']['blog_archive']),
					'sidebar_left' 			=> $this->get_option_value('layout_blog_archive_left_sidebar', $wd_default_data['sidebar']['default']['blog_archive_left']),
					'sidebar_right' 		=> $this->get_option_value('layout_blog_archive_right_sidebar', $wd_default_data['sidebar']['default']['blog_archive_right']),
					'toggle_layout' 		=> $this->get_option_value('layout_blog_archive_toggle_layout', $wd_default_data['blog']['archive']['default']['toggle_layout']),
					'layout_style' 			=> $this->get_option_value('layout_blog_archive_style', $wd_default_data['blog']['archive']['default']['style']),
					'columns' 				=> $this->get_option_value('layout_blog_archive_columns', $wd_default_data['blog']['archive']['default']['columns']),
				),
	
				//index.php
				'default-blog-page' => array(
					'layout' 				=> $this->get_option_value('layout_blog_default_layout', $wd_default_data['layout']['default']['blog_default']),
					'sidebar_left' 			=> $this->get_option_value('layout_blog_default_left_sidebar', $wd_default_data['sidebar']['default']['blog_default_left']),
					'sidebar_right' 		=> $this->get_option_value('layout_blog_default_right_sidebar', $wd_default_data['sidebar']['default']['blog_default_right']),
					'toggle_layout' 		=> $this->get_option_value('layout_blog_default_toggle_layout', $wd_default_data['blog']['index']['default']['toggle_layout']),
					'layout_style' 			=> $this->get_option_value('layout_blog_default_style', $wd_default_data['blog']['index']['default']['style']),
					'columns' 				=> $this->get_option_value('layout_blog_default_columns', $wd_default_data['blog']['index']['default']['columns']),
				),
	
				'blog-related' => array(
					'columns' 				=> $this->get_option_value('layout_blog_single_recent_post_columns', $wd_default_data['columns']['default']['blog_recent']),
				),
	
				'content-blog' => array(
					'show_title'  			=> $this->get_option_value('layout_blog_config_title_display', $wd_default_data['blog']['config']['default']['title']),
					'show_thumbnail'  		=> $this->get_option_value('layout_blog_config_thumbnail_display', $wd_default_data['blog']['config']['default']['thumbnail']),
					'show_by_post_format' 	=> $this->get_option_value('layout_blog_config_show_by_post_format', $wd_default_data['blog']['config']['default']['show_by_post_format']),
					'placeholder_image'  	=> $this->get_option_value('layout_blog_config_thumbnail_placeholder', $wd_default_data['blog']['config']['default']['placeholder']),
					'show_author'  			=> $this->get_option_value('layout_blog_config_author_display', $wd_default_data['blog']['config']['default']['author']),
					'show_number_comments'  => $this->get_option_value('layout_blog_config_number_comment_display', $wd_default_data['blog']['config']['default']['comment']),
					'show_tag'  			=> $this->get_option_value('layout_blog_config_tag_display', $wd_default_data['blog']['config']['default']['tag']),
					'show_like'  			=> $this->get_option_value('layout_blog_config_like_display', $wd_default_data['blog']['config']['default']['like']),
					'show_view'  			=> $this->get_option_value('layout_blog_config_view_display', $wd_default_data['blog']['config']['default']['view']),
					'show_share'  			=> $this->get_option_value('layout_blog_config_share_display', $wd_default_data['blog']['config']['default']['share']),
					'show_category'  		=> $this->get_option_value('layout_blog_config_category_display', $wd_default_data['blog']['config']['default']['category']),
					'show_excerpt'  		=> $this->get_option_value('layout_blog_config_excerpt_display', $wd_default_data['blog']['config']['default']['excerpt']),
					'number_excerpt'  		=> $this->get_option_value('layout_blog_config_number_excerpt_word', $wd_default_data['blog']['config']['default']['excerpt_word']),
					'show_readmore'  		=> $this->get_option_value('layout_blog_config_readmore_display', $wd_default_data['blog']['config']['default']['readmore']),
				),
	
				'single-blog-layout' => array(
					'layout' 				=> $this->get_option_value('layout_blog_single_layout', $wd_default_data['layout']['default']['blog_single']),
					'sidebar_left' 			=> $this->get_option_value('layout_blog_single_left_sidebar', $wd_default_data['sidebar']['default']['blog_single_left']),
					'sidebar_right' 		=> $this->get_option_value('layout_blog_single_right_sidebar', $wd_default_data['sidebar']['default']['blog_single_right']),
					'show_recent_blog' 		=> $this->get_option_value('layout_blog_single_recent_post', $wd_default_data['blog']['single']['default']['recent']),
				),

				'single-blog-content' => array(
					'show_author_information' => $this->get_option_value('layout_blog_single_author_information', $wd_default_data['blog']['single']['default']['author']),
					'show_previous_next_btn' => $this->get_option_value('layout_blog_single_previous_next_button', $wd_default_data['blog']['single']['default']['previous_next']),
					'show_title'  			=> $this->get_option_value('layout_blog_config_title_display', $wd_default_data['blog']['config']['default']['title']),
					'show_thumbnail'  		=> $this->get_option_value('layout_blog_config_thumbnail_display', $wd_default_data['blog']['config']['default']['thumbnail']),
					'show_author'  			=> $this->get_option_value('layout_blog_config_author_display', $wd_default_data['blog']['config']['default']['author']),
					'show_number_comments'  => $this->get_option_value('layout_blog_config_number_comment_display', $wd_default_data['blog']['config']['default']['comment']),
					'show_tag'  			=> $this->get_option_value('layout_blog_config_tag_display', $wd_default_data['blog']['config']['default']['tag']),
					'show_like'  			=> $this->get_option_value('layout_blog_config_like_display', $wd_default_data['blog']['config']['default']['like']),
					'show_view'  			=> $this->get_option_value('layout_blog_config_view_display', $wd_default_data['blog']['config']['default']['view']),
					'show_share'  			=> $this->get_option_value('layout_blog_config_share_display', $wd_default_data['blog']['config']['default']['share']),
					'show_category'  		=> $this->get_option_value('layout_blog_config_category_display', $wd_default_data['blog']['config']['default']['category']),
					'show_excerpt'  		=> $this->get_option_value('layout_blog_config_excerpt_display', $wd_default_data['blog']['config']['default']['excerpt']),
					'number_excerpt'  		=> $this->get_option_value('layout_blog_config_number_excerpt_word', $wd_default_data['blog']['config']['default']['excerpt_word']),
					'show_readmore'  		=> $this->get_option_value('layout_blog_config_readmore_display', $wd_default_data['blog']['config']['default']['readmore']),
				),
	
				'archive-product' => array(
					'layout'  				=> $this->get_option_value('layout_archive_product_layout', $wd_default_data['layout']['default']['product_archive']),
					'sidebar_left'  		=> $this->get_option_value('layout_archive_product_left_sidebar', $wd_default_data['sidebar']['default']['archive_product_left']),
					'sidebar_right'  		=> $this->get_option_value('layout_archive_product_right_sidebar', $wd_default_data['sidebar']['default']['archive_product_right']),
					'columns_product' 		=> $this->get_option_value('layout_archive_product_columns', $wd_default_data['columns']['default']['product_archive']),
				),
	
				'product-archive-posts-per-page' => array(
					'posts_per_page' 		=> $this->get_option_value('layout_archive_product_posts_per_page', $wd_default_data['woo']['archive']['default']['posts_per_page']),
				),
	
				'product-loop-title-word' => array(
					'title_word' 			=> $this->get_option_value('layout_product_config_number_title_word', $wd_default_data['woo']['config']['default']['title_word']),
				),
	
				'woocommerce-page' => array(
					'layout'  				=> $this->get_option_value('layout_woo_template_layout', $wd_default_data['layout']['default']['product_archive']),
					'sidebar_left'  		=> $this->get_option_value('layout_woo_template_left_sidebar', $wd_default_data['sidebar']['default']['woo_template_left']),
					'sidebar_right'  		=> $this->get_option_value('layout_woo_template_right_sidebar', $wd_default_data['sidebar']['default']['woo_template_right']),
				),
	
				'product-config' => array(
					'display_buttons'    	=> $this->get_option_value('layout_product_config_display_buttons', $wd_default_data['woo']['config']['default']['display_buttons']),
	
					'show_title'    		=> $this->get_option_value('layout_product_config_title_display', $wd_default_data['woo']['config']['default']['title']),
					'show_description'  	=> $this->get_option_value('layout_product_config_description_display', $wd_default_data['woo']['config']['default']['desc']),
					'show_rating'  			=> $this->get_option_value('layout_product_config_rating_display', $wd_default_data['woo']['config']['default']['rating']),
					'show_price'  			=> $this->get_option_value('layout_product_config_price_display', $wd_default_data['woo']['config']['default']['price']),
					'show_meta'  			=> $this->get_option_value('layout_product_config_meta_display', $wd_default_data['woo']['config']['default']['meta']),
				),
	
				'product-effect' => array(
					'popup_cart'    		=> $this->get_option_value('product_effect_popup_cart', $wd_default_data['woo']['visual']['default']['popup_cart']),
				),
	
				'product-sale-flash' => array(
					'text'    				=> $this->get_option_value('layout_product_sale_flash_text', $wd_default_data['woo']['sale_flash']['default']['text']),
					'show_percent'    		=> $this->get_option_value('layout_product_sale_flash_percent', $wd_default_data['woo']['sale_flash']['default']['percent']),
				),
	
				'woo_hook' => array(
					'display_buttons'    	=> $this->get_option_value('layout_product_config_display_buttons', $wd_default_data['woo']['config']['default']['display_buttons']),
	
					'wishlist_default'    	=> $this->get_option_value('layout_product_config_wishlist_default', $wd_default_data['woo']['config']['default']['wishlist_default']),
					'compare_default'    	=> $this->get_option_value('layout_product_config_compare_default', $wd_default_data['woo']['config']['default']['compare_default']),
					'show_recently_product' => $this->get_option_value('layout_single_product_recent_product', $wd_default_data['woo']['single']['default']['recent']),
					'show_upsell_product'   => $this->get_option_value('layout_single_product_upsell_product', $wd_default_data['woo']['single']['default']['upsell']),
	
					'show_title'    		=> $this->get_option_value('layout_product_config_title_display', $wd_default_data['woo']['config']['default']['title']),
					'show_description'  	=> $this->get_option_value('layout_product_config_description_display', $wd_default_data['woo']['config']['default']['desc']),
					'show_rating'  			=> $this->get_option_value('layout_product_config_rating_display', $wd_default_data['woo']['config']['default']['rating']),
					'show_price'  			=> $this->get_option_value('layout_product_config_price_display', $wd_default_data['woo']['config']['default']['price']),
					'show_price_decimal'  	=> $this->get_option_value('layout_product_config_price_decimal_display', $wd_default_data['woo']['config']['default']['price_decimal']),
					'show_meta'  			=> $this->get_option_value('layout_product_config_meta_display', $wd_default_data['woo']['config']['default']['meta']),
	
					'product_summary_layout' => $this->get_option_value('layout_single_product_summary_layout', $wd_default_data['woo']['single']['default']['summary_layout']),
					'hover_thumbnail' 		=> $this->get_option_value('product_effect_hover_thumbnail', $wd_default_data['woo']['visual']['default']['hover_thumbnail']),
				),
	
				'content-product' => array(
					'display_buttons'    	=> $this->get_option_value('layout_product_config_display_buttons', $wd_default_data['woo']['config']['default']['display_buttons']),
					'hover_thumbnail' 		=> $this->get_option_value('product_effect_hover_thumbnail', $wd_default_data['woo']['visual']['default']['hover_thumbnail']),
					'style_hover_product' 	=> $this->get_option_value('product_effect_hover_style', $wd_default_data['woo']['visual']['default']['hover_style']),
					'button_group_position' => $this->get_option_value('layout_product_config_button_group_position', $wd_default_data['woo']['config']['default']['button_position']),
				),
	
				'product-description' => array(
					'show_description'  	=> $this->get_option_value('layout_product_config_description_display', $wd_default_data['woo']['config']['default']['desc']),
					'number_word'  			=> $this->get_option_value('layout_product_config_number_desc_word', $wd_default_data['woo']['config']['default']['desc_word']),
				),
	
				'single-product' => array(
					'layout' 				=> $this->get_option_value('layout_single_product_layout', $wd_default_data['layout']['default']['single_product']),
				),
	
				'content-single-product' => array(
					'layout' 				=> $this->get_option_value('layout_single_product_layout', $wd_default_data['layout']['default']['single_product']),
					'sidebar_left' 			=> $this->get_option_value('layout_single_product_left_sidebar', $wd_default_data['sidebar']['default']['single_product_left']),
					'sidebar_right' 		=> $this->get_option_value('layout_single_product_right_sidebar', $wd_default_data['sidebar']['default']['single_product_right']),
				),
	
				'single-product-thumbnail' => array(
					'thumbnail_number' 		=> $this->get_option_value('layout_single_product_thumbnail_number', $wd_default_data['woo']['single']['default']['thumbnail_number']),
					'position_additional' 	=> $this->get_option_value('layout_single_product_position_thumbnail', $wd_default_data['woo']['single']['default']['position_thumbnail']),
				),
	
				'cart' => array(
					'payment_method' 		=> $this->get_option_value('layout_cart_page_payment_method', $wd_default_data['woo']['cart_page']['default']['payment_method']),
				),
	
				'404' => array(
					'select_style' 			=> $this->get_option_value('layout_page_404_background_style', $wd_default_data['404_page']['default']['bg_style']),
					'bg_404_url' 			=> $this->get_option_value('layout_page_404_background_image', $wd_default_data['404_page']['default']['bg_image'], 'image'),
					'bg_404_color'  		=> $this->get_option_value('layout_page_404_background_color', $wd_default_data['404_page']['default']['bg_color']),
					'show_search_form' 		=> $this->get_option_value('layout_page_404_show_search_form', $wd_default_data['404_page']['default']['search_form']),
					'show_back_to_home_btn' => $this->get_option_value('layout_page_404_show_back_to_home_button', $wd_default_data['404_page']['default']['button']),
					'back_to_home_btn_text' => $this->get_option_value('layout_page_404_show_back_to_home_button_text', $wd_default_data['404_page']['default']['button_text']),
					'back_to_home_btn_class' => $this->get_option_value('layout_page_404_show_back_to_home_button_class', $wd_default_data['404_page']['default']['button_class']),
					'show_header_footer' 	=> $this->get_option_value('layout_page_404_show_header_footer', $wd_default_data['404_page']['default']['header_footer']),
				),
	
				'search-style' => array(
					'select_style' 			=> $this->get_option_value('layout_page_search_background_style', $wd_default_data['search_page']['default']['bg_style']),
					'bg_search_url' 		=> $this->get_option_value('layout_page_search_background_image', $wd_default_data['search_page']['default']['bg_image'], 'image'),
					'bg_search_color'  		=> $this->get_option_value('layout_page_search_background_color', $wd_default_data['search_page']['default']['bg_color']),
				),
	
				'search-form' => array(
					'type' 					=> $this->get_option_value('layout_page_search_type', $wd_default_data['search_page']['default']['type']),
					'ajax' 					=> $this->get_option_value('layout_page_search_ajax', $wd_default_data['search_page']['default']['ajax']),
					'show_thumbnail' 		=> $this->get_option_value('layout_page_search_show_thumbnail', $wd_default_data['search_page']['default']['show_thumbnail']),
					'search_only_title' 	=> $this->get_option_value('layout_page_search_only_title', $wd_default_data['search_page']['default']['search_only_title']),
				),
	
				'search-layout' => array(
					'layout' 				=> $this->get_option_value('layout_page_search_layout', $wd_default_data['layout']['default']['page_search']),
					'sidebar_left' 			=> $this->get_option_value('layout_page_search_left_sidebar', $wd_default_data['sidebar']['default']['page_search_left']),
					'sidebar_right' 		=> $this->get_option_value('layout_page_search_right_sidebar', $wd_default_data['sidebar']['default']['page_search_right']),
					'type' 					=> $this->get_option_value('layout_page_search_type', $wd_default_data['search_page']['default']['type']),
					'columns' 				=> $this->get_option_value('layout_page_search_columns', $wd_default_data['columns']['default']['page_search']),
				),
	
				'back_to_top' => array(
					'scroll_button'    		=> $this->get_option_value('back_to_top_display', $wd_default_data['back_to_top']['default']['display']),
					'button_style'    		=> $this->get_option_value('back_to_top_style', $wd_default_data['back_to_top']['default']['style']),
					'border_color'    		=> $this->get_option_value('back_to_top_border_color', $wd_default_data['back_to_top']['default']['border_color']),
					'background_color'    	=> $this->get_option_value('back_to_top_background_color', $wd_default_data['back_to_top']['default']['bg_color']),
					'background_shape'    	=> $this->get_option_value('back_to_top_background_shape', $wd_default_data['back_to_top']['default']['bg_shape']),
					'class_icon'    		=> $this->get_option_value('back_to_top_select_icon', $wd_default_data['back_to_top']['default']['icon']),
					'color_icon'    		=> $this->get_option_value('back_to_top_icon_color', $wd_default_data['back_to_top']['default']['icon_color']),
					'width'	  				=> $this->get_option_value('back_to_top_width', $wd_default_data['back_to_top']['default']['width']),
					'height'	  			=> $this->get_option_value('back_to_top_height', $wd_default_data['back_to_top']['default']['height']),
					'right'	  				=> $this->get_option_value('back_to_top_right', $wd_default_data['back_to_top']['default']['right']),
					'bottom'	  			=> $this->get_option_value('back_to_top_bottom', $wd_default_data['back_to_top']['default']['bottom']),
				),
	
				'social_share' => array(
					'display_social'    	=> $this->get_option_value('share_button_display', $wd_default_data['social_share']['default']['display']),
					'title_display'    		=> $this->get_option_value('share_button_title_display', $wd_default_data['social_share']['default']['title_display']),
					'pubid'    				=> $this->get_option_value('share_button_custom_pubid', $wd_default_data['social_share']['default']['pubid']),
					'button_class'    		=> $this->get_option_value('share_button_button_class', $wd_default_data['social_share']['default']['button_class']),
				),
			);
			return ($template !== 'all' && !empty($data[$template])) ? $data[$template] : $data ;
		}

		public function get_all_theme_options(){
			global $wd_theme_options;
			$data = $wd_theme_options;
			return $data;
		}
		
		// $type: normal / image / font / height / width
		public function get_option_value($keyname, $default_value = '', $type = 'normal') {
			if (empty($keyname)) return;
			$data = '';
			if (WD_THEME_OPTION_MODE) {
				$theme_options = !empty($this->get_all_theme_options()) ? $this->get_all_theme_options() : get_option('wd-theme-options', true);
				$keyname = $this->pre.$keyname;
				if (isset($theme_options[$keyname])) {
					if ($type == 'image') {
						$data = $theme_options[$keyname]['url'];
					}elseif ($type == 'font') {
						$data = $theme_options[$keyname]['font-family'];
					}elseif ($type == 'height') {
						$data = $theme_options[$keyname]['height'];
					}elseif ($type == 'width') {
						$data = $theme_options[$keyname]['width'];
					}else{
						$data = $theme_options[$keyname];
					}
				}else{
					$data = $default_value;
				}
			} else {
				$data = $default_value;
			}
			
			return $data;
		}
		
		public function theme_option_after_save() {
			$url_old 			= Redux::getOption($this->theme_option_obj_name, $this->pre.'replace_url_old');
			$url_new 			= Redux::getOption($this->theme_option_obj_name, $this->pre.'replace_url_new');
			$image_theme_option = Redux::getOption($this->theme_option_obj_name, $this->pre.'replace_url_image_theme_option');
			$site_database 		= Redux::getOption($this->theme_option_obj_name, $this->pre.'replace_url_site_database');
		
			if ($url_old && $url_old !== $url_new) {
				$url_old_array = array($url_old);
				//if old url is blank, use current url of site
				$url_new = (!$url_new) ? get_option( "siteurl", "" ) : $url_new;
			
				//update theme option image url
				if ($image_theme_option) {
					$list_key_need_replace = array(
						'logo',
						'favicon',
						'breadcrumb_background',
						'breadcrumb_archive_blog_background',
						'breadcrumb_archive_product_background',
						'breadcrumb_woo_special_page_background',
						'breadcrumb_search_page_background',
						'facebook_chatbox_logo',
						'header_logo',
						'footer_logo',
						'layout_page_404_background_image',
						'layout_page_search_background_image',
					);
			
					foreach ($list_key_need_replace as $key) {
						$key = $this->pre.$key;
						$data = Redux::getOption($this->theme_option_obj_name, $key );
						if (isset($data) && $data['url'] != '') {
							$data['url'] = str_replace($url_old_array, $url_new, $data['url']);
							Redux::setOption($this->theme_option_obj_name, $key, $data );
						}
					}
				}
			
				//update database
				if ($site_database) {
					global $wpdb;
					$wp_prefix = $wpdb->base_prefix;
					$result1 = $wpdb->query("update `{$wp_prefix}options` set `option_value`='{$url_new}' where `option_name` in('siteurl','home');");
					$result0 = $wpdb->query("update `{$wp_prefix}links` set `link_url` = replace(`link_url`, '{$url_old}', '{$url_new}');");
					$result2 = $wpdb->query("update `{$wp_prefix}posts` set `guid` = replace(`guid`, '{$url_old}', '{$url_new}');");
					$result3 = $wpdb->query("update `{$wp_prefix}posts` set `post_content` = replace(`post_content`, '{$url_old}', '{$url_new}');");
					$result4 = $wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '{$url_old}', '{$url_new}');");
				}
			
				//reset form
				Redux::setOption($this->theme_option_obj_name, $this->pre.'replace_url_old', '');
				Redux::setOption($this->theme_option_obj_name, $this->pre.'replace_url_new', '');
			} //End update url

			//Update theme options to database
			update_option('wd-theme-options', $this->get_all_theme_options());
			update_option('wd-theme-options-packages', $this->get_data_package());
		}
	}
	WD_Get_Theme_Options::get_instance();  // Start an instance of the plugin class 
}