<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 

if (!class_exists('WD_Default_Data')) {
	class WD_Default_Data {
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


		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;

			// $wd_default_data = apply_filters('wd_filter_get_icon', 'all'); //return class of icon
			add_filter( 'wd_filter_get_icon', array($this, 'set_icons' ), 10, 2);

			// $wd_default_data = apply_filters('wd_filter_defaut_data', 'all'); //return array default data
			add_filter( 'wd_filter_defaut_data', array($this, 'set_default_data' ), 10, 2);
		}

		//****************************************************************//
		/*								ICONS							  */
		//****************************************************************//
		public function set_icons($icon_name = 'all'){
			$all_parts = array(
				'cart' => 'fa fa-cart-arrow-down'
			);
			
			return ($icon_name !== 'all' && !empty($all_parts[$icon_name])) ? $all_parts[$icon_name] : $all_parts;
		}

		//****************************************************************//
		/*							THEME OPTIONS						  */
		//****************************************************************//
		public function set_default_data($part = 'all'){
			$all_parts = array(
				'general'       	=> $this->default_general(),
				'sidebar'       	=> $this->default_sidebar(),
				'layout'       		=> $this->default_layout(),
				'columns'       	=> $this->default_columns(),
				'header'   	 		=> $this->default_header(),
				'footer'    		=> $this->default_footer(),
				'nav_user'      	=> $this->default_navuser(),
				'menu'    			=> $this->default_menu(),
				'breadcrumb'    	=> $this->default_breadcrumb(),
				'woo'   			=> $this->default_woocommerce(),
				'blog'   			=> $this->default_blog(),
				'404_page'   		=> $this->default_404_page(),
				'search_page'   	=> $this->default_search_page(),
				'back_to_top'   	=> $this->default_back_to_top(),
				'effects'   		=> $this->default_effects(),
				'email_popup'   	=> $this->default_email_popup(),
				'fb_chatbox' 		=> $this->default_fb_chatbox(),
				'google_map' 		=> $this->default_google_map(),
				'social_share'  	=> $this->default_social_share(),
				'social_link'		=> $this->default_social_link(),
				'social_instagram'	=> $this->default_social_instagram(),
				'comment'       	=> $this->default_comment(),
			);
			return ($part !== 'all' && !empty($all_parts[$part])) ? $all_parts[$part] : $all_parts;
		}

		public function default_general(){
			return array(
				'default'       => array(
					'logo'      	=> WD_THEME_IMAGES.'/logo.png',
					'logo-footer'   => WD_THEME_IMAGES.'/logo_footer.png',
					'favicon'   	=> WD_THEME_IMAGES.'/logo.png',
					'bg_display'   	=> '0',
					'bg_body'   	=> array( 
						'background-image' 		=> WD_THEME_IMAGES.'/bg-body.png', 
						'background-repeat' 	=> 'no-repeat', 
						'background-position'	=> 'left top',
						'background-attachment'	=> 'fixed'
					),
					'user_id'    		=> '100013941973162', //facebook API
					'app_id'    		=> '325713691192544', //facebook API
				)
			);
		}

		public function default_sidebar(){
			return array(
		        'default'       => array(
		            'blog_default_left'     => 'left_sidebar',
		            'blog_default_right'    => 'right_sidebar',
		            'blog_archive_left'     => 'left_sidebar',
		            'blog_archive_right'    => 'right_sidebar',
		            'blog_single_left'      => 'left_sidebar',
		            'blog_single_right'     => 'right_sidebar',
		            'page_default_left'     => 'left_sidebar',
		            'page_default_right'    => 'right_sidebar',
		            'page_search_left'   	=> 'left_sidebar',
		            'page_search_right' 	=> 'right_sidebar',
		            'woo_template_left'     => 'left_sidebar_shop',
		            'woo_template_right'   	=> 'right_sidebar_shop',
		            'archive_product_left'  => 'left_sidebar_shop',
		            'archive_product_right' => 'right_sidebar_shop',
		            'single_product_left'   => 'left_sidebar_product',
		            'single_product_right'  => 'right_sidebar_product',
		        ),
		    );
		}

		public function default_layout(){
			return array(
		    	'choose'        => array(
                    '0-0-0' => array(
                        'alt' => '1 Column',
                        'img' => WD_THEME_IMAGES . '/layouts/wd_fullwidth.png'
                    ),
                    '1-0-0' => array(
                        'alt' => '2 Column Left',
                        'img' => WD_THEME_IMAGES . '/layouts/wd_left_sidebar.png'
                    ),
                    '0-0-1' => array(
                        'alt' => '2 Column Right',
                        'img' => WD_THEME_IMAGES . '/layouts/wd_right_sidebar.png'
                    ),
                    '1-0-1' => array(
                        'alt' => '3 Column Middle',
                        'img' => WD_THEME_IMAGES . '/layouts/wd_left_right.png'
                    ),
                ),
		        'default'       => array(
		            'blog_single'  		=> '0-0-0',
		            'blog_archive'  	=> '0-0-0',
		            'blog_default'  	=> '0-0-0',
		            'page_default'  	=> '0-0-0',
		            'page_search'  		=> '0-0-0',
		            'single_product'	=> '0-0-0',
		            'product_archive'	=> '0-0-0',
		        )
	    	);
		}
	
		public function default_columns(){
			return array(
		    	'choose'        	=>  array(
				        '1' => esc_html__( '1 Column', 'feellio' ),
				        '2' => esc_html__( '2 Columns', 'feellio' ),
				        '3' => esc_html__( '3 Columns', 'feellio' ),
				        '4' => esc_html__( '4 Columns', 'feellio' ),
				    ),
		        'default'       => array(
		            'product_archive'  	=> '3',
		            'page_search'		=> '3', //columns of post result
		            'blog_recent'		=> '3',
		        )
	    	);
		}

		public function default_header(){
			return array(
		        'choose'         => array(
		            'site_title'        => array(
                        '0'    => __( 'Show Logo', 'feellio' ),
                        '1'    => __( 'Show Site Title', 'feellio' ),
                    ),
		        ),
		        'default'        => array(
		            'site_title'        => '0',
		            'show_logo'         => '1',
		            'show_social'       => '1',
		            'show_navuser'      => '1',
		        ),
		    );
		}

		public function default_footer(){
			return array(
		        'choose'         => array(
		        ),
		        'default'        => array(
		            'copyright_text'    => sprintf(__( 'Copyright %s. All rights reserved.', 'feellio' ), esc_html( get_bloginfo('name')) ),
					'instagram' 		=> true,
					'social' 			=> true,
					'copyright' 		=> true,
				),
		    );
		}

		public function default_navuser(){
			return array(
				'choose'        => array(
					'sorter'		=> array(
						'mini_cart'     => __( 'Cart Icon', 'feellio' ),
						'mini_account'  => __( 'Account Icon', 'feellio' ),
						'wishlist'     	=> __( 'Wishlist Icon', 'feellio' ),
						'search'    	=> __( 'Search Icon', 'feellio' ),
					),
					'dropdown_position' => array(
						'left'    	=> __( 'Left', 'feellio' ),
						'right'    	=> __( 'Right', 'feellio' ),
					),
				),
				'default'       => array(
					'sorter_desktop' => array(
						'search'    	=> true,
						'mini_account'  => true,
						'mini_cart'     => true,
						'wishlist'     	=> true,
					),
					'sorter_mobile'	=> array(
						'search'    	=> true,
						'mini_account'  => false,
						'mini_cart'     => true,
						'wishlist'     	=> false,
					),
					'sorter_pushmenu'	=> array(
						'search'    	=> false,
						'mini_account'  => false,
						'mini_cart'     => false,
						'wishlist'     	=> true,
					),
					'show_icon' => 1,
					'show_text'	=> 0,
					'dropdown_position' => 'left'
				),
			);
		}

		public function default_menu(){
			return array(
		        'megamenu'         => array(
					'choose'         => array(
						'layout' => array(
							'menu-horizontal' => __( 'Menu Horizontal', 'feellio' ),
							// 'menu-vertical'   => __( 'Menu Vertical', 'feellio' ),
						),
						'vertical_submenu_position' => array(
							'left'    	=> __( 'Left', 'feellio' ),
							'right'    	=> __( 'Right', 'feellio' ),
						),
						'menu_container' => array(
							''    			=> __( 'Container Fluid (Without Padding)', 'feellio' ),
							'container'    	=> __( 'Container (Padding)', 'feellio' ),
						),
						'style' => array(
							'style-1'  => __( 'Style 1', 'feellio' ),
							'style-2'  => __( 'Style 2', 'feellio' ),
							'style-3'  => __( 'Style 3', 'feellio' ),
							'style-4'  => __( 'Style 4', 'feellio' ),
						),
						'type' => array(
							'theme-location' => __( 'Menu Theme Location', 'feellio' ),
							'specific-menu'  => __( 'Integrate Specific Menu', 'feellio' ),
						),
					),
					'default'        => array(
						'layout'  					=> 'menu-horizontal',
						'vertical_submenu_position' => 'left',
						'menu_container' 			=> '',
						'style'						=> 'style-1',
						'hover_style'				=> 'style-1',
						'type'						=> 'theme-location',
						'menu_theme_location'		=> '',
						'integrate_specific_menu'	=> '',
					),
				),
				'main_menu'		=> array(
					'choose'         => array(
						'menu_location'     => array(
							'primary' 	=> esc_html__('Primary Menu', 'feellio'),
							'secondary' => esc_html__('Secondary Menu', 'feellio'),
							'mobile' 	=> esc_html__('Mobile Menu', 'feellio'),
						),
					),
					'default'        => array(
						'menu_location_desktop'		=> 'primary',
						'menu_location_mobile'		=> 'mobile'
					),
				),
				'pushmenu'         => array(
					'choose'         => array(
						'panel_positon' => array(
							'left'    	=> __( 'Left', 'feellio' ),
							'right'    	=> __( 'Right', 'feellio' ),
						),
					),
					'default'        => array(
						'panel_positon'    	=> 'left',
						'form_user'			=> false,
					),
				),
			);
		}

		public function default_breadcrumb(){
			return array(
		        'choose'         => array(
		            'type'              => array(
		                'breadcrumb_default'=> __( 'Background Color', 'feellio' ),
		                'breadcrumb_banner' => __( 'Background Image', 'feellio' ),
		                'no_breadcrumb'     => __( 'No Breadcrumb', 'feellio' )
		            ),
		            'text_style'        => array(
		                'inline'            => __( 'Inline', 'feellio' ),
		                'block'             => __( 'Block', 'feellio' ),
		            ),
		            'text_align'        => array(
		                'text-center'       => __( 'Text Center', 'feellio' ),
		                'text-left'         => __( 'Text Left', 'feellio' ),
		                'text-right'        => __( 'Text Right', 'feellio' ),
		                'text-justify'      => __( 'Text Justified', 'feellio' ),
		            ),
		        ),
		        'default'        => array(
		            'type'              => 'breadcrumb_default',
		            'bg_color'          => '#F6F5F3',
		            'background'        => WD_THEME_IMAGES.'/banner_breadcrumb.jpg',
		            'height'            => '50',
		            'text_color'        => '#282828',
		            'text_style'        => 'inline',
		            'text_align'        => 'text-center',
		            //breadcrumb custom setting for special template
		            'blog_archive'		=> false,
					'product_archive'	=> false,
					'woo_special_page'	=> false,
					'search_page'		=> false,
		        ),
		    );
		}

		public function default_woocommerce(){
			return array(
		    	'config'   		=> array(
			        'choose'        => array(
			        	'button_position'	=> array(
	                        'after-content'    => __( 'After Content Detail', 'feellio' ),
	                        'before-content'   => __( 'Before Content Detail', 'feellio' ),
	                    ),
			        ),
			        'default'       => array(
			            'display_buttons'       => true,
			            'button_position' 		=> 'after-content',
			            'wishlist_default'   	=> false,
			            'compare_default'       => false,
			            'title'         		=> true,
			            'title_word'			=> '5',
			            'desc'         			=> false,
			            'desc_word'         	=> '40',
			            'rating'         		=> true,
			            'price'         		=> true,
			            'price_decimal'         => false,
			            'meta'         			=> true,
			        ),
		        ),
		        'visual'   	=> array(
			        'choose'        => array(
			        	'hover_style'		=> array(
		                    'wd-hover-style-1' => array(
		                        'alt' => 'Style Hover 1',
		                        'img' => WD_THEME_IMAGES . '/products/wd-hover-style-1.jpg'
		                    ),
		                ),
			        ),
			        'default'       => array(
			            'popup_cart'			=> true,
			            'hover_thumbnail'		=> true,
			            'hover_style'       	=> 'wd-hover-style-1',
			        ),
		        ),
		        'woo_template'   	=> array(
			        'choose'        => array(
			        ),
			        'default'       => array(
			        ),
		        ),
		        'archive'   	=> array(
			        'choose'        => array(
			        ),
			        'default'       => array(
			            'posts_per_page'        => '24',
			        ),
		        ),
		        'single'  	 	=> array(
			        'choose'        => array(
			        	'position_thumbnail'	=> array(
		                    'left'      => __( 'Left', 'feellio' ),
		                    'bottom'    => __( 'Bottom', 'feellio' ), 
		                ),
		                'summary_layout'		=> array(
		                    'single_product_summary_price'         	=> __( 'Price', 'feellio' ),
		                    'single_product_summary_review'        	=> __( 'Review', 'feellio' ),
		                    'single_product_summary_sku'           	=> __( 'Sku', 'feellio' ),
		                    'single_product_summary_availability'  	=> __( 'Availability', 'feellio' ),
		                    'single_product_summary_excerpt'		=> __( 'Excerpt', 'feellio' ),
		                    'single_product_summary_add_to_cart'   	=> __( 'Add To Cart', 'feellio' ),
		                    'single_product_summary_categories'     => __( 'Categories', 'feellio' ),
		                )
			        ),
			        'default'       => array(
			            'position_thumbnail'    => 'left',
			            'thumbnail_number'		=> '4',
			            'summary_layout'		=> array(
		                    'single_product_summary_price'         	=> true,
		                    'single_product_summary_review'        	=> true,
		                    'single_product_summary_sku'           	=> true,
		                    'single_product_summary_availability'  	=> true,
		                    'single_product_summary_excerpt'		=> true,
		                    'single_product_summary_add_to_cart'   	=> true,
		                    'single_product_summary_categories'     => false,
		                ),
		                'recent'				=> true,
		                'upsell'				=> false,
			        ),
		        ),
		        'cart_page'   	=> array(
			        'choose'        => array(
			        ),
			        'default'       => array(
			            'payment_method'		=> '',
			        ),
		        ),
		        'sale_flash'   	=> array(
			        'choose'        => array(
			        ),
			        'default'       => array(
			            'text'			=> 'Sale!',
			            'percent'		=> false,
			        ),
		        ),
		    );
		}

		public function default_blog(){
			return array(
		    	'config'   		=> array(
			        'choose'        => array(
			        ),
			        'default'       => array(
			            'title'         		=> true,
			            'thumbnail'         	=> true,
			            'show_by_post_format'   => true,
			            'placeholder'         	=> true,
			            'date'         			=> false,
			            'author'         		=> true,
			            'comment'         		=> true,
			            'tag'         			=> true,
			            'like'         			=> false,
			            'view'         			=> false,
			            'share'         		=> false,
			            'category'         		=> true,
			            'excerpt'         		=> false,
			            'excerpt_word'       	=> '-1',
			            'readmore'         		=> false,
			        ),
		        ),
		        'archive'   	=> array(
			        'choose'        => array(
			        	'style'			=> array(
		                    'list'      => esc_html__( 'List', 'feellio' ),
		                    'grid'      => esc_html__( 'Grid', 'feellio' ),
		                ),
			        ),
			        'default'       => array(
						'toggle_layout'	=> false,
						'style'         => 'grid',
						'columns'		=> '3',
			        ),
		        ),
		        'single'  	 	=> array(
			        'choose'        => array(
			        ),
			        'default'       => array(
			            'author'         	=> false,
			            'previous_next'		=> true,
			            'recent'			=> true,
			        ),
		        ),
		        'index'   		=> array(
			        'choose'        => array(
			        	'style'			=> array(
		                    'list'      => esc_html__( 'List', 'feellio' ),
		                    'grid'      => esc_html__( 'Grid', 'feellio' ),
		                ),
			        ),
			        'default'       => array(
						'toggle_layout'	=> false,
						'style'         => 'grid',
						'columns'		=> '1',
			        ),
		        ),
		    );
		}

		public function default_404_page(){
			return array(
		        'choose'        => array(
		            'bg_style'         => array(
	                    'bg_image'          => esc_html__( 'Background Image', 'feellio' ),
	                    'bg_color'          => esc_html__( 'Background Color', 'feellio' ),
	                ),
		        ),
		        'default'       => array(
		            'bg_style'     		=> 'bg_image',
		            'bg_color'      	=> '#fff',
		            'bg_image'      	=> WD_THEME_IMAGES.'/bg_404.jpg',
		            'header_footer'		=> false,
		            'search_form'		=> false,
		            'button'			=> true,
		            'button_text'		=> 'Back To Homepage',
		            'button_class'		=> '',
		        )
		    );
		}

		public function default_search_page(){
			return array(
		        'choose'        => array(
		            'bg_style'         	=> array(
	                    'bg_image'          => esc_html__( 'Background Image', 'feellio' ),
	                    'bg_color'          => esc_html__( 'Background Color', 'feellio' ),
	                ),
	                'type'         		=> array(
	                    'post'          	=> esc_html__( 'Blog', 'feellio' ),
	                    'product'          	=> esc_html__( 'Product', 'feellio' ),
	                    'page'      		=> esc_html__( 'Page', 'feellio' ),
	                ),
		        ),
		        'default'       => array(
		            'bg_style'     		=> 'bg_color',
		            'bg_color'      	=> '#fff',
		            'bg_image'      	=> WD_THEME_IMAGES.'/bg_404.jpg',
		            'type'      		=> 'post',
		            'search_only_title' => false,
		            'ajax'     			=> false,
		            'show_thumbnail'    => true,
		        )
		    );
		}

		public function default_back_to_top(){
			return array(
		        'choose'        => array(
		            'style'         => array(
		                'icon'             => __( 'Icon Only', 'feellio' ),
		                'icon-background'  => __( 'Icon & Background', 'feellio' ),
		            ),
		            'bg_shape'      => array(
		                'circle'       => __( 'Circle', 'feellio' ),
		                'square'       => __( 'Square', 'feellio' ),
		            ),
		        ),
		        'default'       => array(
		            'display'       => true,
		            'style'         => 'icon',
		            'bg_color'      => '#333333',
		            'border_color'  => array(
				        'color'     => '#BBBBBB',
				        'alpha'     => 0.3
				    ),
		            'bg_shape'      => 'circle',
		            'icon'          => 'el el-chevron-up',
		            'icon_color'    => '#dddddd',
		            'width' 		=> '40px',
		            'height' 		=> '40px',
		            'right' 		=> '20px',
		            'bottom' 		=> '50px',
		        )
		    );
		}

		public function default_effects(){
			return array(
				'choose'        => array(
		    		'loading_style'   => array(
		                'style-1'         => __( 'Style 1', 'feellio' ),
		                'style-2'         => __( 'Style 2', 'feellio' ),
		                'style-3'         => __( 'Style 3', 'feellio' ),
		            ),
		        ),
		        'default'       => array(
		            'loading'       		=> false,
		            'loading_style' 		=> 'style-1',
		            'sidebar_fixed' 		=> false,
		            'scroll_smooth' 		=> false,
		            'scroll_smooth_step' 	=> 100,
		            'scroll_smooth_speed' 	=> 800,
		        )
		    );
		}

		public function default_email_popup(){
			return array(
		    	'choose'        => array(
		    		'source'   => array(
		                'feedburner'         => __( 'Feedburner Form', 'feellio' ),
		                'custom'             => __( 'Custom Content', 'feellio' ),
		            ),
		        ),
		        'default'       => array(
					'display'       		=> false,
					'only_home'				=> true,
					'popup_mobile'			=> false,
					'delay_time'			=> '5',	//seconds
					'session_expire'		=> '30', //minutes
					'banner'				=> '',
					'source'				=> 'feedburner', //or custom
					'custom_content'		=> '',
					'feedburner_id'			=> 'WpComic-Manga',
					'width'					=> '800',
					'height'				=> '300',
		            'title'					=> 'Sign up for Our Newsletter',
					'desc'					=> 'A newsletter is a regularly distributed publication generally',
					'placeholder'			=> 'Enter your email address',
					'button_text'			=> 'Subscribe',
					
		        )
		    );
		}

		public function default_fb_chatbox(){
			return array(
		    	'choose'        => array(
		            'default_mode'   => array(
		                '1'             => __( 'Show', 'feellio' ),
		                '0'             => __( 'Hide', 'feellio' ),
		            ),
		        ),
		        'default'       => array(
		            'display'  	 	=> false,
		            'url' 			=> 'https://www.facebook.com/WeLoveMTPSonTung/',
		            'width' 		=> '250px',
		            'height' 		=> '325px',
		            'right' 		=> '100px',
		            'bottom' 		=> '0px',
		            'default_mode' 	=> '0', //0 = hide , 1 = show
		            'bg_color' 		=> '#3b5998',
		            'logo'      	=> WD_THEME_IMAGES.'/logo_footer.png',
		            'text_footer'   => __( 'Send a message to us...', 'feellio' ),
		            'link_caption'  => __( 'Visit us on facebook', 'feellio' ),
		            'link_url'  	=> '#',
		        )
		    );
		}

		public function default_google_map(){
			return array(
		    	'default'       => array(
					'api_key'     	=> 'AIzaSyAwJR7kylDCymhx59VKffi40Ez1qaU6aSo',
					'zoom'    		=> 17,
				),
		    );
		}

		public function default_social_share(){
			return array(
		        'default'       => array(
					'display'       => false,
					'title_display' => false,
		            'pubid'       	=> 'ra-593a226bbc5a2a6c',
		            'button_class'  => 'addthis_inline_share_toolbox',
		        )
		    );
		}

		public function default_social_link(){
			return array(
		        'default'       => array(
					'rss_id'		=> '#',
					'twitter_id'	=> '#',
					'facebook_id'	=> '#',
					'google_id'		=> '#',
					'pin_id'		=> '#',
					'youtube_id'	=> '#',
					'instagram_id'	=> '#',
		        )
		    );
		}

		public function default_social_instagram(){
			return array(
		        'default'       => array(
					'insta_user'				=> '484934710',
					'insta_client_id'			=> '3433732ada5842a5abce2eddeb702d41',
					'insta_access_token'		=> '484934710.3433732.c52c74a8827a4d8a9357d1912b04c8f2',
		        )
		    );
		}

		public function default_comment(){
			return array(
		    	'choose'        => array(
		            'sorter'        => array(
	                    'wordpress' 	=> __( 'Wordpress Comment', 'feellio' ),
	                    'facebook'  	=> __( 'Facebook Comment', 'feellio' ),
	                ),
	                'mode'         	=> array(
                        '1'    			=> __( 'Multi Domain', 'feellio' ),
                        '0'    			=> __( 'Single Domain', 'feellio' ),
                    ),
                    'layout'        => array(
                        '1'    			=> __( 'Tab', 'feellio' ),
                        '0'    			=> __( 'Normal', 'feellio' ),
                    ),
		        ),
		        'default'       => array(
		            'sorter'        => array(
	                    'wordpress' 	=> true,
	                    'facebook'  	=> false,
	                ),
	                'single_product' 	=> false,
	                'number_comment' 	=> 10,
	                'mode'		 		=> '1', //1 = multi domain, 0 = single domain
	                'layout'		 	=> '1', //1 = tab, 0 = normal
		        )
	    	);
		}
	}
	WD_Default_Data::get_instance();  // Start an instance of the plugin class 
}