<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 
if (!class_exists('WD_Load_Font')) {
	class WD_Load_Font {
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
			
			add_action('wp_enqueue_scripts', array($this, 'load_google_fonts'));
		}

		/* Load font list from xml file */
		public function load_google_fonts(){
			global $wd_font_web, $wd_google_fonts;
			$list_font_used	= is_array($wd_font_web) ? $wd_font_web : array();

			if (WD_THEME_STYLE_MODE === 'xml') {
				$xml_manager 	= WD_XML_Manager::get_instance();
				$list_font_used	= $xml_manager->get_list_google_font_customize($list_font_used);
			}

			$google_font 	= array();
			if (count($list_font_used) > 0) {
				foreach ($list_font_used as $font_name) {
					foreach ($wd_google_fonts as $key => $value) {
						if ($value->font_family == $font_name) {
							$name  	= str_replace( " ", "+", trim($value->font_family) );
							$name   .= ':'.$value->font_styles;
							$google_font[] = $name;
						}
					}
				}
			}
			if (!empty($google_font)) {
				$this->load_google_fonts_with_css($google_font); // or $this->google_fonts_with_js($google_font);
			}
		}

		/* enqueue google font with js */
		public function google_fonts_with_js($font_array = array()) {
			ob_start(); 
			if( count($font_array) > 0 ){ 
				$font_string 	= implode(',', $font_array); ?>
				<script type="text/javascript">
					WebFontConfig = {
						google: { families: [ <?php echo $font_string; ?> ] }
					};
					(function () {
						var wf   = document.createElement( 'script' );
						wf.src   = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
						wf.type  = 'text/javascript';
						wf.async = 'true';
						var s    = document.getElementsByTagName( 'script' )[ 0 ];
						s.parentNode.insertBefore( wf, s );
					})();
				</script>
				<?php
				$output = str_replace( array( '<script type="text/javascript">', '</script>' ), '', ob_get_clean() );
				wp_add_inline_script( 'wd-custom-script-inline-js', $output );
			}
		}

		/* enqueue google font with css */
		public function load_google_fonts_with_css($font_array = array()) {
			if( count($font_array) > 0 ){
				$font_string 	= implode('|', $font_array);
				$protocol 		= is_ssl() ? 'https' : 'http';
				$url 			= "//fonts.googleapis.com/css?family={$font_string}";
				wp_enqueue_style( "wd-google-font", str_replace(' ', '%20', $url) );
			}
		}
	}
	WD_Load_Font::get_instance();  // Start an instance of the plugin class 
}