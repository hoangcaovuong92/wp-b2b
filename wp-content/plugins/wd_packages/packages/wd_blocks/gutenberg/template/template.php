<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
if (!class_exists('WD_Gutenberg_Template')) {
	class WD_Gutenberg_Template {
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

        protected $block_name, $block_title, $block_desc, $block_icon, $block_category, $block_attributes, $show_toolbar, $show_preview, $show_current_value, $callback, $group_name;

		public function __construct(){
            // Ensure construct function is called only once
			if ( static::$called ) return;
            static::$called = true;
            
            if ( ! function_exists( 'register_block_type' ) ) {
                // Gutenberg is not active.
                return;
            }

            //$this->block_init();
        }

        public function block_init(){
            $this->set_group_name();
            $this->set_block_info();
            add_action('init', array($this, 'register_block_type'));
        } 

        /**
         * Get DATA from REST
         *
         * @param Array $data.
         * @return array
         */
        // $posts = $this->get_posts( array(
        //     'post_type' => 'post',
        //     'phone_number' => '123',
        // ) );
        // var_dump($posts);
        public function get_posts( $data ) {
            $request = new WP_REST_Request( 'GET', '/wd-rest/v1/get_posts' );
            $request->set_query_params( $data );
            $response = rest_do_request( $request );
            $server = rest_get_server();
            $data = $server->response_to_data( $response, false );
            $data = isset( $data['response'] ) ? $data['response'] : false;
            return isset( $data['data'] ) ? $data['data'] : false;
        }

        //Format an array based on Block Type format
        public function format_array($array = array()){
            $new_array = array();
            if (!empty($array)) {
                foreach ($array as $key => $value) {
                    $new_array[] = array(
                        'value' => $key, 
                        'label' => $value
                    );
                }
            }
            return $new_array;
        }

        public function set_group_name($group_name = ''){
            $this->group_name = !empty($group_name) ? $group_name : __('General Settings', 'wd_package');
        } 
        
        public function set_block_info(){ 
            $this->block_name = 'blog-grid-list';
            $this->block_title = __('WD - Blog Grid/List', 'wd_package');
            $this->block_desc = __('Display blog with Grid/List layout...', 'wd_package');
            $this->block_icon = 'welcome-write-blog'; //See the list icon: https://developer.wordpress.org/resource/dashicons
            $this->block_category = 'wd-block';
            $this->show_toolbar = false; //If set true, alignment and image icon will display on toolbar
            $this->show_preview = true; //If set true, switch preview button will display on toolbar
            $this->show_current_value = false; //If set true, the setting values will display on block screen
            $this->custom_style = false; //If set true, include editor.css and style.css
            $this->callback = '';
            /* ---------------------------------*/
            /* New attribute fields starts here */

            /* AVAILABLE FIELDS:
             * $this->get_image_block($id, $title = 'Image', $default = '', $required = array());
             * $this->get_text_block($id, $title = 'Text', $default = '', $required = array());
             * $this->get_textarea_block($id, $title = 'Textarea', $default = '', $required = array());
             * $this->get_range_block($id, $title = 'Range', $min = 1, $max = 100, $default = 1, $required = array())
             * $this->get_number_block($id, $title = 'Number', $default = '', $required = array());
             * $this->get_url_block($id, $title = 'Url', $default = '', $required = array());
             * $this->get_hidden_block($id, $title = 'Hidden', $default = '', $required = array());
             * $this->get_toggle_block($id, $title = 'Toggle', $default = true, $required = array());
             * $this->get_editable_block($id, $title = 'Enter text here...', $selector = 'h5', $default = '', $required = array());
             * $this->get_select_block($id, $options = array(), $title = 'Select an option', $default = '', $required = array());
             * $this->get_api_block($id, $type = 'posts', $title = 'Select an option', $default = '', $required = array());
             * $this->get_radio_block($id, $options = array(), $title = 'Select an option', $default = '', $required = array());
             * $this->get_color_block($id, $title = 'Choose a color', $default = '', $required = array());
             * $this->get_date_block($id, $title = 'Pick a date', $default = '', $required = array());
             */

            /* EXAM:
             * $age_options = array(
                    '25' => __('25', 'wd_package'),
                    '26' => __('26', 'wd_package'),
                    '27' => __('27', 'wd_package'),
                    '28' => __('28', 'wd_package'),
                );
             * $this->get_image_block('avatar', __('Avatar', 'wd_package'));
             * $this->get_text_block('name', __('Enter your name', 'wd_package'), 'Cao Vương');
             * $this->get_range_block('name', __('Choose a value', 'wd_package'), 1, 24, 1);
             * $this->get_number_block('number', __('Age', 'wd_package'), '27');
             * $this->get_url_block('website', __('Website', 'wd_package'), '');
             * $this->get_hidden_block('hidden', __('Hidden', 'wd_package'), '');
             * $this->get_select_block('age', $age_options, __('Select you ages', 'wd_package'), '27');
             * $this->get_color_block('fav_color', __('Your fav color', 'wd_package'));
             * $this->get_date_block('date', __('Your birthday', 'wd_package'));
             * $this->get_editable_block('about', __('Something about yourself...', 'wd_package'));
             */

            /* New attribute fields end here */
            /* ------------------------------*/
        }

        public function get_block_info(){ 
            $this->display_toolbar();
            $this->display_preview();
            
            $block_info = array(
                'name' => 'wd/'.$this->block_name,
                'title' => $this->block_title,
                'desc' => $this->block_desc,
                'icon' => $this->block_icon,
                'category' => $this->block_category,
                'attributes' => $this->block_attributes,
                'show_current_value' => $this->show_current_value,
            );
            return $block_info;
        }

        //**************************************************************//
		/*						  BLOCK FIELDS			                */
        //**************************************************************//
        //Required exam : array('sort', '==', 'DESC') - only display when "sort" field = 'DESC'
        public function get_image_block($id, $title = 'Image', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => '',
                'type' => 'number',
                'default' => '',
                'group' => $this->group_name,
            );
            $this->block_attributes[$id.'_url'] = array(
                'title' => sprintf(__('Select %s', 'wd_package'), $title),
                'desc'  => '',
                'control' => 'MediaUpload',
                'img_id' => $id,
                'show_on_toolbar' => $this->show_toolbar,
                'type' => 'url',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id.'_url']['required'] = $required;
            }
        }

        public function get_text_block($id, $title = 'Text', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'TextControl',
                'type' => 'text',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_textarea_block($id, $title = 'Textarea', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'TextareaControl',
                'type' => 'text',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_range_block($id, $title = 'Range', $min = 1, $max = 100, $default = 1, $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'type' => 'string',
                'control' => 'RangeControl',
                'min' => $min,
                'max' => $max,
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_number_block($id, $title = 'Number', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'TextControl',
                'type' => 'number',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_url_block($id, $title = 'Url', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'TextControl',
                'type' => 'url',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_hidden_block($id, $title = 'Hidden', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'TextControl',
                'type' => 'hidden',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_toggle_block($id, $title = 'Toggle', $default = true, $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'type' => 'boolean',
                'control' => 'ToggleControl',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_editable_block($id, $title = 'Enter text here...', $selector = 'h5', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'RichText',
                'type' => 'array',
                'source' => 'children',
                'selector' => $selector,
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_select_block($id, $options = array(), $title = 'Select an option', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'type' => 'string',
                'control' => 'SelectControl',
                'options' => $this->format_array($options),
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        //$type : posts / post_categories / products / product_categories
        public function get_api_block($id, $type = 'posts', $title = 'Select an option', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'control' => 'API',
                'type' => $type,
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_radio_block($id, $options = array(), $title = 'Select an option', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'type' => 'string',
                'control' => 'RadioControl',
                'options' => $this->format_array($options),
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_color_block($id, $title = 'Choose a color', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'type' => 'string',
                'control' => 'ColorPicker',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function get_date_block($id, $title = 'Pick a date', $default = '', $required = array()){
            if (!isset($id)) return;
            $this->block_attributes[$id] = array(
                'title' => $title,
                'desc'  => '',
                'type' => 'string',
                'control' => 'DatePicker',
                'default' => $default,
                'group' => $this->group_name,
            );
            if (!empty($required)) {
                $this->block_attributes[$id]['required'] = $required;
            }
        }

        public function display_preview(){
            if ($this->show_preview) {
                $this->block_attributes['preview'] = array(
                    'title' => __('Preview', 'wd_package'),
                    'type' => 'string',
                    'control' => 'ServerSideRender',
                    'default' => false
                );
            }
        }

        public function display_toolbar(){
            if ($this->show_toolbar) {
                $this->block_attributes['alignment'] = array(
                    'title' => __('Alignment Toolbar', 'wd_package'),
                    'type' => 'string',
                    'control' => 'AlignmentToolbar',
                    'default' => ''
                );
            }
        }

        //**************************************************************//
		/*						 BLOCK REGISTER			                */
        //**************************************************************//
		public function register_block_type(){ 
            //Scripts
            wp_register_script(
                'wd-'.$this->block_name.'-block-script', // Handle.
                WD_BLOCKS_BASE_URI.'/gutenberg/template/block.js', // Block.js: We register the block here.
                array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'wp-date', 'wp-api-fetch' ), // Dependencies, defined above.
                filemtime( WD_BLOCKS_BASE.'/gutenberg/template/block.js' ),
                true
            );
            //Pass block object info to block.js
            wp_localize_script('wd-'.$this->block_name.'-block-script', 'wd_block', $this->get_block_info());

            // Styles.
            if ($this->custom_style) {
                wp_register_style(
                    'wd-'.$this->block_name.'-block-editor-style', // Handle.
                    plugins_url( '', __FILE__ ).'/editor.css', // Block editor CSS.
                    array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
                    filemtime( plugin_dir_path( __FILE__ ).'editor.css' )
                );
                wp_register_style(
                    'wd-'.$this->block_name.'-block-frontend-style', // Handle.
                    plugins_url( '', __FILE__ ).'/style.css', // Block editor CSS.
                    array(), // Dependency to include the CSS after it.
                    filemtime( plugin_dir_path( __FILE__ ).'style.css' )
                );
            }
            
            // Here we actually register the block with WP, again using our namespacing.
            // We also specify the editor script to be used in the Gutenberg interface.
            $block_args = array(
                'editor_script' => 'wd-'.$this->block_name.'-block-script',
                'attributes'    => $this->block_attributes,
            );
            if ($this->custom_style) {
                $block_args['editor_style'] = 'wd-'.$this->block_name.'-block-editor-style';
                $block_args['style'] = 'wd-'.$this->block_name.'-block-frontend-style';
            }
            if ($this->callback) {
                $block_args['render_callback'] = array($this, 'block_callback');
            }
            register_block_type('wd/'.$this->block_name, $block_args);
        } 

        //**************************************************************//
		/*						    CALLBACK			                */
		//**************************************************************//
        function block_callback( $atts, $content ) {
            $content = function_exists($this->callback) ? call_user_func($this->callback, $atts) : $content;
            return $content;
        }
	}
	WD_Gutenberg_Template::get_instance();
}