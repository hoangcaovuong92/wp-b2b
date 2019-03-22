<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_XML_Manager')) {
	class WD_XML_Manager {
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

		protected $font_file 				= 'font_config';
		protected $color_file 				= 'color_default';
		protected $theme_option_obj_name 	= 'wd_theme_options';

		public function __construct($font_file = '', $color_file = ''){
			$this->constant();
			$this->font_file 	= ($font_file == '') ? $this->font_file : $font_file;
			$this->color_file 	= ($color_file == '') ? $this->color_file : $color_file;

			//$style = apply_filters('wd_filter_xml_style', true);
			add_filter( 'wd_filter_xml_style', array($this, 'xml_style'), 10, 2);
		}
		
		protected function constant(){
			if (!defined('WDXML_BASE')) define('WDXML_BASE', WD_THEME_STYLE_MANAGER . '/config_xml/files/');
		}

		/* Get current option mode */
		public function use_theme_option_mode(){
			$mode = defined('WD_THEME_OPTION_MODE') ? WD_THEME_OPTION_MODE : class_exists( 'ReduxFramework' );
			return WD_THEME_OPTION_MODE;
		}

		/* Get custom css font config from xml file */
		public function xml_style(){
			$custom_css = $this->get_custom_font_css();
			$custom_css .= $this->get_custom_color_css();
			return $custom_css;
		}
		
		/* Get current color file name - customize mode */
		public function get_color_file_name_selected(){
			$color_file = $this->color_file;
			return $color_file;
		}

		/* Usage: Check and add custom google fonts to the font list.
		-----------
		$xml_manager = WD_XML_Manager::get_instance();
		$xml_manager->get_list_google_font_customize($list_font_used);
		-----------
		File : framework\functions\wd_enqueue_font.php
		*/
		public function get_list_google_font_customize($list_font_used){
			$file_url 		= WDXML_BASE.$this->font_file.'.xml';
			if ( !$this->use_theme_option_mode() && file_exists($file_url)) {
				$objXML_font 		= simplexml_load_file($file_url);
				foreach ($objXML_font->children() as $child) {	 				
					foreach ($child->items->children() as $childofchild) { 		
						$name 	 			=  (string)$childofchild->name;		
						$slug 	 			=  (string)$childofchild->slug;
						$std 	 			=  (string)$childofchild->std;
						$frontend 			=  $childofchild->frontend; 		
						$font_name 			=  get_theme_mod($slug, $std);
						$list_font_used[$font_name]	= $font_name;
					}
				}	
			}
			return $list_font_used;
		}

		/* Usage: display list theme font options field
		-----------
		$manager = WD_XML_Manager::get_instance();
		$manager->options_font_fields($wp_customize);
		-----------
		File : theme_option\parts\options_font.php
		File : theme_customize\parts\wd_customize_font.php
		*/
		public function options_font_fields($wp_customize = ''){
			$file_url 		= WDXML_BASE.$this->font_file.'.xml';
			if (!file_exists($file_url)) return;

			$objXML_font    = simplexml_load_file($file_url);
			if (!$this->use_theme_option_mode()) {
				global $wd_google_fonts;
				if (!$wp_customize) return;
				foreach ($objXML_font->children() as $child) {
					$title 			= (string)$child->title;
					$section 		= (string)$child->section;
					$description 	= (string)$child->description;
					$wp_customize->add_section( $section , array(
							'title'       		=> $title,
							'description' 		=> $description,
							'panel'	 			=> 'wd_font_config',
						));	
					foreach ($child->items->children() as $childofchild) {
						$name 	 		=  (string)$childofchild->name;
						$slug 	 		=  (string)$childofchild->slug;
						$std 	 		=  (string)$childofchild->std;
						$description 	=  (string)$childofchild->description;
						$control =  (string)$slug."_control";
						$wp_customize->add_setting( $slug , array(
							'default'           =>  $std,
							'sanitize_callback' => 'wd_sanitize_text',
							'capability' 		=> 'edit_theme_options'
						));
						$wp_customize->add_control( new WD_Customize_Fonts_Select_Field($wp_customize, $control ,array(
							'label'   			=> $name,
							'section'  			=> $section,
							'description' 		=> $description,
							'settings' 			=> $slug,
							'choices' 			=> $wd_google_fonts,
						)));
					}
				}	
			}else{
				$i = 1;
				foreach ($objXML_font->children() as $child) {
				    $title          = (string)$child->title;
				    $section        = (string)$child->section;
				    $description    = (string)$child->description;

				    $font_field_array = array();
				    foreach ($child->items->children() as $childofchild) {
				        $name           = (string)$childofchild->name;
				        $slug           = (string)$childofchild->slug;
				        $std            = (string)$childofchild->std;
				        $description    = (string)$childofchild->description;
				        $elements       = (array)$childofchild->frontend;
				        $disable        = 0;
				        if (count($elements)) {
				            foreach ($elements as $key => $value) {
				                if ($key == 'selector' && !$value) $disable++;
				            }
				        } 
				        if ($disable == 0) {
				            $font_field_array[] = array(
				                'id'       => $slug,
				                'type'     => 'typography',
				                'title'    => $name,
				                'subtitle' => $description,
				                'google'   => true,
				                'font-weight'   => false,
				                'color'         => false,
				                'text-align'    => false,
				                'line-height'   => false,
				                'font-style'    => false,
				                'font-size'     => false,
				                'subsets'       => false,
				                'default'  => array(
				                    'color'       => '#dd9933',
				                    'font-size'   => '30px',
				                    'font-family' =>  $std,
				                    'font-weight' => 'Normal',
				                ),
				            );
				        }
				    }
				    if (count($font_field_array)) {
				        Redux::setSection( $this->theme_option_obj_name, array(
				            'title'            => $title,
				            'id'               => 'wd_font_setting_'.$i,
				            'subsection'       => true,
				            'customizer_width' => '450px',
				            'desc'             => $description,
				            'fields'           => $font_field_array
				        ) );
				    }
				    $i ++;
				}
			}
		}		

		/* Usage: display list theme color options field
		-----------
		$manager = WD_XML_Manager::get_instance();
		$manager->options_color_fields($wp_customize);
		-----------
		File: theme_option\parts\options_color.php
		File: theme_customize\parts\wd_customize_styling.php
		*/
		public function options_color_fields($wp_customize = ''){
			$color_file = $this->get_color_file_name_selected();
			$file_url 	=	WDXML_BASE.$color_file.'.xml';

			if (!file_exists($file_url)) return;

			$objXML_color       = simplexml_load_file($file_url);
			if (!$this->use_theme_option_mode()) {
				if (!$wp_customize) return;
				foreach ($objXML_color->children() as $child) {	
					$title 			= (string)$child->title;
					$section 		= (string)$child->section;
					$description 	= (string)$child->description;
					$wp_customize->add_section( $section , array(
							'title'       		=> $title,
							'description' 		=> $description,
							'panel'	 			=> 'wd_styling_config',
						));
					foreach ($child->items->children() as $childofchild) {
						$name 	=  (string)$childofchild->name;
						$slug 	=  (string)$childofchild->slug;
						$std 	=  (string)$childofchild->std;
						
						$wp_customize->add_setting( $slug , array(
							'default'           =>  $std,
							'sanitize_callback' => 'sanitize_hex_color',
							'transport'         => 'postMessage',
						));
						$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $slug , array(
							'label'      		=>  $name,
							'section'     		=>  $section,
						)));
					}
				} 
			}else{
				$i = 1;
				foreach ($objXML_color->children() as $child) {
				    $title          = (string)$child->title;
				    $section        = (string)$child->section;
				    $description    = (string)$child->description;

				    $color_field_array = array();
				    foreach ($child->items->children() as $childofchild) {
				        $name       = (string)$childofchild->name;
				        $slug       = (string)$childofchild->slug;
				        $std        = (string)$childofchild->std;
				        $elements   = (array)$childofchild->frontend;
				        $disable    = 0;
				        if (count($elements)) {
				            foreach ($elements as $value) {
				                if ($value->selector == '') $disable++;
				            }
				        }
				        if ($disable == 0) {
				            $color_field_array[] = array(
				                'id'            => $slug,
				                'type'          => 'color',
				                'transparent'   => false,
				                'title'         => $name,
				                'subtitle'      => __( '', 'feellio' ),
				                'default'       => $std,
				            );
				        }
					}
				    if (count($color_field_array)) {
				         Redux::setSection( $this->theme_option_obj_name, array(
				            'title'            => $title,
				            'id'               => 'wd_color_setting_'.$i,
				            'subsection'       => true,
				            'customizer_width' => '450px',
				            'desc'             => $description,
				            'fields'           => $color_field_array
				        ) );
				    }
				    $i ++;
				}
			}
		}

		/* Usage: get css font customize
		-----------
		$xml_manager = WD_XML_Manager::get_instance();
		$xml_manager->get_custom_font_css();
		-----------
		File: framework\functions\wd_enqueue_scripts.php
		*/
		public function get_custom_font_css(){
			$file_url 		= WDXML_BASE.$this->font_file.'.xml';
			if (!file_exists($file_url)) return;

			$custom_css 	= '';
			ob_start();
			$objXML_font    = simplexml_load_file($file_url);
			foreach ($objXML_font->children() as $child) {
				foreach ($child->items->children() as $childofchild) {
					$slug 	 		= (string)$childofchild->slug;
					$std 	 		= (string)$childofchild->std;
					$data_style 	= apply_filters('wd_filter_get_option_value', $slug, $std, 'font');
					foreach ($childofchild->frontend as $childoffrontend) {	
						$attr 					= 'font-family';
						$selector 				= (string)$childoffrontend->selector;
						$selector_important 	= (string)$childoffrontend->selector_important;
						echo ($selector).'{';
							echo esc_attr($attr).': '.esc_attr($data_style).';';
						echo '}'."\n";
						if($selector_important!=''){
							echo ($selector_important).'{';
								echo esc_attr($attr).': '.esc_attr($data_style).' !important ;';
							echo '}'."\n";
						}	
					}
				}
			}
			$custom_css = ob_get_clean();
			return $custom_css;
		}

		/* Usage: get css color customize
		-----------
		$xml_manager = WD_XML_Manager::get_instance();
		$xml_manager->get_custom_color_css();
		-----------
		File: framework\functions\wd_enqueue_scripts.php
		*/
		public function get_custom_color_css(){
			$custom_css = '';
			if (!isset($_GET['color'])) {
				$color_file 	= $this->get_color_file_name_selected();
				$file_url 		= WDXML_BASE.$color_file.'.xml';
				if (!file_exists($file_url)) return;
			
				$objXML_color   = simplexml_load_file($file_url);
				foreach ($objXML_color->children() as $child) {	 				
					foreach ($child->items->children() as $childofchild) { 	
						$important 		= (isset($childofchild->important) &&  (int)$childofchild->important == 1) ? ' !important' : ''; 
						$slug 			= (string)$childofchild->slug; 					
						$std 			= (string)$childofchild->std;
						$data_style 	= apply_filters('wd_filter_get_option_value', $slug, $std);
						foreach ($childofchild->frontend->children() as $childoffrontend) {	// frondend => f*
							$attribute 	= $childoffrontend->attribute;
							$selector 	= $childoffrontend->selector;
							$custom_css .= $selector.'{'.$attribute.': '.$data_style.$important.';}';
						}	
					}
				}
			}else{
				/* Home color get from request $_GET['color'] for demo */
				$color_file 	= "color_".$_GET['color'];
				$objXML_color 	= WDXML_BASE.$color_file.".xml";
				$objXML_color 	= @simplexml_load_file($objXML_color); 
				if($objXML_color) {
					foreach ($objXML_color->children() as $child) {      
						foreach ($child->items->children() as $childofchild) {   
							$std =  (string)$childofchild->std;      
							foreach ($childofchild->frontend->children() as $childofchilds) {   
								$attribute  =  (string)$childofchilds->attribute;     
								$selector  	=  (string)$childofchilds->selector; 
								$custom_css .= $selector."{".$attribute.":".$std."}";
							}
						}
					}
				}
			}
			return $custom_css;
		}
	}
	//WD_XML_Manager::get_instance();  // Start an instance of the plugin class 
}