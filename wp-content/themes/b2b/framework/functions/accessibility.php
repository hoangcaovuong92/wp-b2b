<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Accessibility')) {
	class WD_Accessibility {
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

			/* Backend Favicon */
			add_action('admin_head', array($this, 'display_favicon'), 5);

			/* Frontend Favicon */
			add_action('wd_hook_header_meta_data', array($this, 'display_favicon'), 5);

			/* Facebook setting meta */
			add_action('wd_hook_header_meta_data', array($this, 'facebook_comment_setting_meta'), 10);

			/* Facebook API script */
			add_action('wd_hook_after_opening_body_tag', array($this, 'facebook_api'), 5);

			/* Loading page effect */
			add_action('wd_hook_after_opening_body_tag', array($this, 'loading_page_effect'), 10);

			/* Social Share */
			add_action('wd_hook_social_sharing', array($this, 'social_sharing'), 5);

			/* Back To Top button */
			add_action('wd_hook_footer_init_action', array($this, 'back_to_top_button'), 10);

			/* Other Scripts */
			add_action('wp_enqueue_scripts', array($this, 'effect_js'), 999999);
			add_action('wp_enqueue_scripts', array($this, 'addthis_script'));

			//$style = apply_filters('wd_filter_back_to_top_style', true);
			add_filter( 'wd_filter_back_to_top_style', array($this, 'back_to_top_button_style'), 10, 2);
		}

		// Check email subscribe popup status 
		static function wd_email_popup_enable() {
			$type 	= session_id() ? 'session' : 'transient'; 
			$key	= 'wd_disabled_email_popup';
			if ($type == 'transient') {
				$disabled_email_popup 	= get_transient( $key );
				$result 				= ($disabled_email_popup === false) ? true : false;
			}else{
				if (!isset($_SESSION['wd_disabled_email_popup'])) return true;
				$current_value 	= $_SESSION['wd_disabled_email_popup'];
				$result 		= ($current_value < time()) ? true : false;
			}
			return $result;
		}

		/* Favicon */
		public function display_favicon(){ 
			/**
			 * package: favicon
			 * var: icon
			 */
			extract(apply_filters('wd_filter_get_data_package', 'favicon' ));
			if( strlen(trim($icon)) > 0 ) :?>
				<link rel="shortcut icon" href="<?php echo esc_url($icon);?>" />
			<?php endif;
		}

		/* Facebook Comment Meta */
		public function facebook_comment_setting_meta(){ 
			/**
			 * package: facebook-api
			 * var: user_id
			 * var: app_id
			 * var: comment_status
			 * var: chatbox_status
			 */
			extract(apply_filters('wd_filter_get_data_package', 'facebook-api' )); 
			$status 	= isset($comment_status['facebook']) ? $comment_status['facebook'] : false;
			$content 	= '';
			if ($status) {
				ob_start(); 
				?>
					<meta property="fb:admins" content="<?php echo esc_attr($user_id); ?>"/>
					<meta property="fb:app_id" content="<?php echo esc_attr($app_id); ?>" />
				<?php
				$content = ob_get_clean();
			}
			echo $content;
		}

		/* Facebook API */
		public function facebook_api(){ 
			/**
			 * package: facebook-api
			 * var: user_id
			 * var: app_id
			 * var: comment_status
			 * var: chatbox_status
			 */
			extract(apply_filters('wd_filter_get_data_package', 'facebook-api' )); 
			$comment_status 	= isset($comment_status['facebook']) ? $comment_status['facebook'] : false;
			$chatbox_status 	= isset($chatbox_status) ? $chatbox_status : false;
			$content = '';
			if ($comment_status || $chatbox_status) {
				ob_start(); ?>
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=<?php echo esc_attr($app_id); ?>";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					<?php
				$content = ob_get_clean();
			}
			echo $content;
		}

		/* Loading Page Effect */
		public function loading_page_effect(){
			$style = 'style-3';

			/**
			* package: loading-effect
			* var: status
			* var: style
			*/
			extract(apply_filters('wd_filter_get_data_package', 'loading-effect' )); 
			if (!$status) return; ?>
			<div id="wd-loader-wrapper">
				<?php if ($style === 'style-1') { ?>
					<div class="wd-loader wd-loader--style-1">
						<div class="wd-loader-content">
							<div class="circularG circularG--1"></div>
							<div class="circularG circularG--2"></div>
							<div class="circularG circularG--3"></div>
							<div class="circularG circularG--4"></div>
							<div class="circularG circularG--5"></div>
							<div class="circularG circularG--6"></div>
							<div class="circularG circularG--7"></div>
							<div class="circularG circularG--8"></div>
						</div>
					</div>
				<?php }elseif ($style === 'style-2'){ ?>
					<div class="wd-loader wd-loader--style-2">
						<div class="wd-loader-content">
							<span class="line line-1"></span>
							<span class="line line-2"></span>
							<span class="line line-3"></span>
							<span class="line line-4"></span>
							<span class="line line-5"></span>
							<span class="line line-6"></span>
							<span class="line line-7"></span>
							<span class="line line-8"></span>
							<span class="line line-9"></span>
							<div><?php esc_html_e( "Loading", 'feellio' ); ?></div>
						</div>
					</div>
				<?php }elseif ($style === 'style-3'){ ?>
					<div class="wd-loader wd-loader--style-3">
						<div class="wd-loader-content"></div>
					</div>
				<?php } ?>
			</div>
			<?php 
		}

		/* JS Effect */
		public function effect_js(){
			/**
			* package: js-effect
			* var: sidebar_scroll
			* var: scroll_smooth
			* var: scroll_smooth_step
			* var: scroll_smooth_speed
			*/
			extract(apply_filters('wd_filter_get_data_package', 'js-effect' ));
			if ($sidebar_scroll) {
				wp_enqueue_script('hc-sticky-js', WD_THEME_EXTEND_LIBS.'/hc-sticky/js/jquery.hc-sticky.min.js',false,false,true);
			}
			wp_enqueue_script('wd-effects-js'	, WD_THEME_JS.'/wd_effects.js',false,false,true);
			wp_localize_script( 'wd-effects-js', 'effects_status', array(
				'sidebar_scroll' => $sidebar_scroll,
			));

			//smooth_scroll
			$special_template = is_page_template( 'page-templates/template-home-header-left.php' );
			if(!wp_is_mobile() && is_windows() && is_chrome() && !$special_template && $scroll_smooth) {
				$settings = array(
					'scroll_smooth_step' 	=> $scroll_smooth_step,
					'scroll_smooth_speed' 	=> $scroll_smooth_speed,
				); 
				wp_enqueue_script( 'wd-smooth-scroll', WD_THEME_EXTEND_LIBS.'/smooth_scroll/jQuery.scrollSpeed.js',array('jquery'),false,true);
				wp_enqueue_script( 'wd-smooth-scroll-run', WD_THEME_EXTEND_LIBS.'/smooth_scroll/run.js',false,false,true);
				wp_localize_script('wd-smooth-scroll-run', 	'settings', $settings);
			}
		}

		/* Add Social Share */
		public function addthis_script(){
			/**
			* package: social_share
			* var: display_social
			* var: pubid
			*/
			extract(apply_filters('wd_filter_get_data_package', 'social_share' ));
			if ($display_social) {
				if( is_single() || is_page_template('page-templates/template-blog.php') || ( is_category()) || is_tag() ){ 
					wp_enqueue_script( 'addthis-script', '//s7.addthis.com/js/300/addthis_widget.js#pubid='.esc_html($pubid));
				} 
			}
		}

		/* Social Share HTML */
		public function social_sharing(){
			/**
			* package: social_share
			* var: display_social
			* var: title_display
			* var: pubid
			* var: button_class
			*/
			extract(apply_filters('wd_filter_get_data_package', 'social_share' ));
			if ($display_social) { ?>
				<div class="wd-social-share">
					<?php if ($title_display) { ?>
						<span><?php esc_html_e('Share ', 'feellio'); ?></span>
					<?php } ?>
					<div class="<?php echo $button_class; ?>"></div>
				</div>
			<?php
			}
		}

		/* Back To Top Button */
		public function back_to_top_button(){
			/**
			* package: backtotop
			* var: scroll_button
			* var: button_style
			* var: border_color
			* var: background_color
			* var: background_shape
			* var: class_icon
			* var: color_icon
			* var: width
			* var: height
			* var: right
			* var: bottom
			*/
			extract(apply_filters('wd_filter_get_data_package', 'back_to_top' ));
			if($scroll_button){ ?>
				<div id="wd-back-to-top" class="scroll-button">
					<a class="scroll-button" href="javascript:void(0)" title="<?php esc_html_e('To Top', 'feellio');?>">
						<i class="<?php echo esc_attr($class_icon); ?>"></i>
					</a>
				</div>
			<?php }
		}

		/* Get custom css back to top button */
		public function back_to_top_button_style(){
			/**
			 * package: backtotop
			 * var: scroll_button
			 * var: button_style
			 * var: border_color
			 * var: background_color
			 * var: background_shape
			 * var: class_icon
			 * var: color_icon
			 * var: width
			 * var: height
			 * var: right
			 * var: bottom
			 */
			extract(apply_filters('wd_filter_get_data_package', 'back_to_top' )); 
			$custom_css = '';
			//icon
			$custom_css .= '#wd-back-to-top a i{color:'.esc_attr($color_icon).';}';
		
			//wrap
			$custom_css .= '#wd-back-to-top{';
			//wrap color
			$custom_css .= 'color:'.esc_attr($color_icon).';';
			//wrap position
			$custom_css .= 'right:'.esc_attr($right).'; bottom:'.esc_attr($bottom).';';
			//wrap style
			if ($button_style === 'icon-background') {
				if ($background_color != 'transparent') {
					$custom_css .= 'background-color:'.esc_attr($background_color).';';
				}
				if ($background_shape === 'circle') {
					$custom_css .= "-webkit-border-radius: 100%;-moz-border-radius: 100%;-ms-border-radius: 100%;border-radius: 100%;";
				}
				//border
				if ((isset($border_color['rgba']) && $border_color['rgba'] != '') || $border_color['color'] != '') {
					$custom_css .= '-webkit-background-clip: padding-box; /* for Safari */ background-clip: padding-box; /* for IE9+, Firefox 4+, Opera, Chrome */';
					if (isset($border_color['rgba']) && $border_color['rgba'] != '') {
						$custom_css .= 'border: 6px solid '.$border_color['rgba'].';';
					}else{
						$custom_css .= 'border: 6px solid '.$border_color['color'].';';
					}
				}
			}
			$custom_css .= '}';
		
			//link
			$custom_css .= '#wd-back-to-top a{';
			//wrap style
			$custom_css .= 'width:'.esc_attr($width).'; height:'.esc_attr($height).'; line-height:'.esc_attr($height).';';
			if ($button_style == '0') {
				if ($background_color != 'transparent') {
					$custom_css .= 'background-color:'.esc_attr($background_color).';';
				}
				if ($background_shape) {
					$custom_css .= "-webkit-border-radius: 100%;-moz-border-radius: 100%;-ms-border-radius: 100%;border-radius: 100%;";
				}
			}
			$custom_css .= '}';
			return $custom_css;
		}
	}
	WD_Accessibility::get_instance();  // Start an instance of the plugin class 
}