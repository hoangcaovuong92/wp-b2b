<?php 
if (!class_exists('WD_Facebook_Chatbox')) {
	class WD_Facebook_Chatbox {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			$this->constant();
			$this->include_lybrary();
			// Actions
			add_action('wp_enqueue_scripts', array( $this, 'enqueue_script'));
			add_action('wd_hook_footer_init_action', array($this, 'facebook_chatbox_content'), 15);
	    }

        /*-----------------------------------------------------------------------------------*/
		/* Class Functions */
		/*-----------------------------------------------------------------------------------*/
		protected function constant(){
			define('WDFC_BASE'		,   plugin_dir_path( __FILE__ )		);
			define('WDFC_BASE_URI'	,   plugins_url( '', __FILE__ )		);
			define('WDFC_JS'		, 	WDFC_BASE_URI . '/assets/js'	);
			define('WDFC_CSS'		, 	WDFC_BASE_URI . '/assets/css'	);
		}

		function include_lybrary(){
			if( file_exists(WDFC_BASE.'/wd_functions.php') ){
				require_once WDFC_BASE.'/wd_functions.php';
			}
		}

		function facebook_chatbox_content(){ 
			/**
		     * package: facebook-chatbox
			 * var: chatbox_status
			 * var: url
			 * var: width
			 * var: height
			 * var: right
			 * var: bottom
			 * var: default_mode
			 * var: bg_color
			 * var: logo
			 * var: text_footer
			 * var: link_caption
			 * var: link_url
			 */

			$facebook_chatbox_package = get_option('wd-theme-options-packages', array());
			if (!count($facebook_chatbox_package) || !is_array($facebook_chatbox_package)) return;
			extract($facebook_chatbox_package['facebook-chatbox']); 

			$content = '';
			if ($chatbox_status && !is_admin()) {
				ob_start(); ?>
					<div data-toggle="<?php echo $default_mode; ?>" class="wd-facebook-chatbox-wrap">
						<span class="wd-facebook-chatbox-close-btn"><?php esc_html_e( 'X', 'wd_packages' ); ?></span>
					    <div class="fb-page wd-facebook-chatbox-content" data-adapt-container-width="true" data-height="<?php echo esc_attr($height); ?>" data-hide-cover="false" data-href="<?php echo esc_url($url); ?>" data-show-facepile="true" data-show-posts="false" data-small-header="false" data-tabs="messages" data-width="<?php echo esc_attr($width); ?>"></div>
					    <p class="wd-facebook-chatbox-permarlink-wrap">
					    	<a class="wd-facebook-chatbox-permarlink" href="<?php echo esc_url($link_url); ?>" target="_blank">
					    		<?php echo esc_html($link_caption); ?></a>
					    		<?php if (isset($logo['url'])): ?>
					    			<a class="wd-facebook-chatbox-logo"><img src="<?php echo esc_url($logo['url']); ?>">
					    		<?php endif ?>
				    		</a>
			    		</p>
					</div>
					<div class="wd-facebook-chatbox-footer">
					    <div class="wd-facebook-chatbox-footer-content">
					    	<a><i class="fa fa-facebook-square" aria-hidden="true"></i>
					    		<span class="wd-facebook-chatbox-footer-text"><?php echo esc_html($text_footer); ?></span>
				    		</a>
					    	<i class="fa fa-sort-asc" aria-hidden="true"></i>
				    	</div class="wd-facebook-chatbox-footer-content">
					</div>
					<?php
				$content = ob_get_clean();
			}
			echo $content;
		}

		/** Get custom facebook chatbox css */
		public function facebook_chatbox_style() {
			/**
			 * package: facebook-chatbox
			 * var: chatbox_status
			 * var: url
			 * var: width
			 * var: height
			 * var: right
			 * var: bottom
			 * var: default_mode
			 * var: bg_color
			 * var: logo
			 * var: text_footer
			 * var: link_caption
			 * var: link_url
			 */
			$facebook_chatbox_package = get_option('wd-theme-options-packages', array());
			if (!count($facebook_chatbox_package) || !is_array($facebook_chatbox_package)) return;
			extract($facebook_chatbox_package['facebook-chatbox']); 

			$custom_css 	= '';
			if ($chatbox_status) {
				ob_start(); ?>
				<style>
					.wd-facebook-chatbox-wrap{position:fixed; z-index:99; right:<?php echo $right; ?>; bottom:<?php echo $bottom; ?>; width:<?php echo $width; ?>; height:<?php echo $height; ?>; overflow:unset!important}
					.wd-facebook-chatbox-close-btn{background:rgba(78,86,101,.8); font-size:12px; font-weight:700; color:#fff; display:inline-block; height:25px; line-height:25px; position:absolute; right:2px; text-align:center; top:-19px; width:25px; z-index:100}
					.wd-facebook-chatbox-close-btn:hover{cursor:pointer}
					.wd-facebook-chatbox-permarlink-wrap{text-align:left; height:20px; margin-bottom:0; margin-top:0; background:<?php echo $bg_color; ?>; width:100%; bottom:0; display:block; left:0; position:absolute; z-index:99; border-left:1px solid #fff}
					.wd-facebook-chatbox-permarlink-wrap a.wd-facebook-chatbox-permarlink{color:#fff; font-size:12px; line-height:23px; padding-left:5px; text-decoration:none}
					.wd-facebook-chatbox-permarlink-wrap a.wd-facebook-chatbox-permarlink:hover{text-decoration:underline}
					.wd-facebook-chatbox-logo{position:absolute; bottom:0; right:0; z-index:99; width:40px; height:20px; display:inline-block; background:echo; padding-right:0; padding-left:5px}
					.wd-facebook-chatbox-logo img{vertical-align:unset; height:14px; padding-right:3px}
					.wd-facebook-chatbox-footer{cursor:pointer; position:fixed; width:<?php echo $width; ?>; background:<?php echo $bg_color; ?>; z-index:99; right:<?php echo $right; ?>; bottom:<?php echo $bottom; ?>; border-style:solid solid none; border-width:2px 2px 0; border-color:#fff; border-radius:8px 8px 0 0!important; -moz-border-radius:8px 8px 0 0!important; -webkit-border-radius:8px 8px 0 0!important}
					.wd-facebook-chatbox-footer .wd-facebook-chatbox-footer-content{color:#fff; font-size:13px; margin:0; padding:0 13px; text-align:left}
					.wd-facebook-chatbox-footer .wd-facebook-chatbox-footer-content a{color:#fff; font-size:13px; padding:5px 0 7px; margin:0; display:inline-block; text-decoration:none}
					.wd-facebook-chatbox-footer .wd-facebook-chatbox-footer-text{margin-left:5px}
					.wd-facebook-chatbox-footer .wd-facebook-chatbox-footer-content a:hover{text-decoration:underline; cursor:pointer}
					.wd-facebook-chatbox-footer .wd-facebook-chatbox-footer-content>i{float:right; margin-top:13px}
					.wd-facebook-chatbox-content{position:relative; z-index:99; right:0; bottom:21px; border-left:1px solid #fff; border-top:1px solid #fff}
				</style>
				<?php
				$custom_css = str_replace( array( '<style>', '</style>' ), '', ob_get_clean() );
			}
			return $custom_css;
		}
		
		function enqueue_script() {
			if ( is_admin() )
				return;
			//Style
			$custom_css = $this->facebook_chatbox_style();
			wp_enqueue_style( 'wd-facebook-chatbox-styles', WDFC_CSS. '/wd_style.css' );
			wp_add_inline_style( 'wd-facebook-chatbox-styles', $custom_css );
			
			// Scripts
			wp_enqueue_script( 'wd-facebook-chatbox-js', WDFC_JS. '/wd_scripts.js', array( 'jquery' ), false, true );
		}
	}

	WD_Facebook_Chatbox::get_instance();
}
