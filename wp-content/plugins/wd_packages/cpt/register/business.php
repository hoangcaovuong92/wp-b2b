<?php
if (!class_exists('WD_Business_Post_Type')) {
	class WD_Business_Post_Type extends WD_Package_Metabox{
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

		protected $post_type 				= 'wd_business';
		protected $post_type_single_name 	= 'Business';
		protected $post_type_multiple_name 	= 'Business';
		protected $post_type_display_name 	= 'Business';
		protected $post_type_icon 			= 'dashicons-format-gallery';
		protected $show_in_nav_menus 		= true;
		protected $show_in_menu 			= true; //'wd-post-type-panel'
		protected $post_type_position		= 20;
		protected $taxonomies 				= array(
			'wd_business_group' => 'Business Group',
			'wd_location_group' => 'Location Group',
		);

		public function __construct(){
			$this->constant();
			
			/****************************/
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array( $this, 'register_taxonomy' ) );
			//add_filter('single_template', array( $this, 'single_template'));
			
			add_action('admin_enqueue_scripts', array($this,'init_admin_script'));
			
			//Metabox
			add_action('add_meta_boxes', array( $this,'create_metabox' ));	
			add_action('pre_post_update', array($this,'metabox_save_data') , 10, 2);
			add_action('deleted_post', array($this, 'delete_post'));

			//Template
			add_action('template_redirect', array($this,'template_redirect'));
			add_filter('attribute_escape', array($this,'rename_second_menu_name') , 10, 2);
			add_filter('enter_title_here', array($this, 'change_title_text')); //Change Placeholder Title Post
			add_theme_support('post-thumbnails', array($this->post_type));
			
			//$this->init_handle();

			add_action('admin_notices', array($this, 'alert_screen'));
		}
		
		protected function constant(){
			define('WD_BANNER_BASE'			,   plugin_dir_path( __FILE__ ) );
			define('WD_BANNER_BASE_URI'		,   plugins_url( '', __FILE__ ) );
		}

		public function alert_screen(){
			if (!$this->checkPluginVC() || !$this->check_current_screen()) return;
			$vc_role_page = admin_url('admin.php?page=vc-roles');
			$mess['class'] 		= 'updated settings-error notice is-dismissible wd-compile-sass-notice';
            $mess['message'] 	= sprintf(__( 'If the Backend Editor mode (Visual Composer) is not enabled, you need to set up the role at the following link: %1$s', 'wd_packages' ), '<a target="_blank" href="'.$vc_role_page.'">'.__('Set Visual Composer Role' ,'wd_packages').'</a>' );
            printf( '<div class="%1$s"><p><strong>%2$s</strong></p></div>', esc_attr( $mess['class'] ), $mess['message'] );
		}

		public function check_current_screen() {
			$screen = get_current_screen();
			return $this->post_type == $screen->post_type ? true : false;
		}

		public function visual_composer_css($post_id) {
			$custom_css = '';
			/** code copied from Vc_Base::addPageCustomCss() */
			$post_custom_css = get_post_meta( $post_id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) )
				$custom_css .= $post_custom_css;
			
			/** code copied from Vc_Base::addShortcodesCustomCss() */
			$shortcodes_custom_css = get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$custom_css .= $shortcodes_custom_css;
			}
			
			return $custom_css;
		}
		/******************************** REGISTER POST TYPE ***********************************/
		public function register_post_type(){
			if (!post_type_exists($this->post_type)) {
				register_post_type($this->post_type, array(
					'exclude_from_search' 	=> true, 
					'labels' 				=> array(
		                'name' 				=> $this->post_type_display_name,
		                'singular_name' 	=> $this->post_type_display_name,
		                'add_new' 			=> sprintf( __( 'Add %s', 'wd_packages' ), $this->post_type_single_name ),
		                'add_new_item' 		=> sprintf( __( 'Add New %s', 'wd_packages' ), $this->post_type_single_name ),
						'edit_item' 		=> sprintf( __( 'Edit %s', 'wd_packages' ), $this->post_type_single_name ),
						'new_item' 			=> sprintf( __( 'New %s', 'wd_packages' ), $this->post_type_single_name ),
						'all_items' 		=> sprintf( __( 'All %s', 'wd_packages' ), $this->post_type_multiple_name ),
						'view_item' 		=> sprintf( __( 'View %s', 'wd_packages' ), $this->post_type_single_name ),
						'search_items' 		=> sprintf( __( 'Search %a', 'wd_packages' ), $this->post_type_multiple_name ),
						'not_found' 		=> sprintf( __( 'No %s Found', 'wd_packages' ), $this->post_type_multiple_name ),
						'not_found_in_trash'=> sprintf( __( 'No %s Found In Trash', 'wd_packages' ), $this->post_type_multiple_name ),
		                'parent_item_colon' => '',
		                'menu_name' 		=> $this->post_type_display_name,
					),
					'singular_label' 		=> $this->post_type_display_name,
					'taxonomies' 			=> array_keys($this->taxonomies),
					'public' 				=> true,
					'supports' 			 	=>  array('editor', 'title'),
					'has_archive' 			=> false,
					'rewrite' 				=>  array('slug'  =>  $this->post_type, 'with_front' =>  true),
					'show_in_nav_menus' 	=> $this->show_in_nav_menus,
					'show_in_menu' 			=> $this->show_in_menu,
					'menu_icon'				=> $this->post_type_icon,
					'menu_position'			=> $this->post_type_position,
					'show_in_rest' 			=> false,
					'hierarchical' 			=> true,
				));	
				flush_rewrite_rules();
			}
		}

		public function register_taxonomy(){
			if (!empty($this->taxonomies)) {
				foreach ($this->taxonomies as $taxonomy => $taxonomy_single_name) {
					register_taxonomy( $taxonomy, $this->post_type, array(
						'hierarchical'     		=> true,
						'labels'            	=> array(
							'name' 				=> $taxonomy_single_name,
							'singular_name' 	=> $taxonomy_single_name,
							'new_item'          => sprintf( esc_html__('Add New %s', 'wd_packages' ), $taxonomy_single_name ),
							'edit_item'         => sprintf( esc_html__('Edit %s', 'wd_packages' ), $taxonomy_single_name ),
							'view_item'   		=> sprintf( esc_html__('View %s', 'wd_packages' ), $taxonomy_single_name ),
							'add_new_item'      => sprintf( esc_html__('Add New %s', 'wd_packages' ), $taxonomy_single_name ),
							'menu_name'         => $taxonomy_single_name,
						),
						'show_ui'           	=> true,
						'show_admin_column' 	=> true,
						'query_var'         	=> true,
						'rewrite'           	=> array( 'slug' => $taxonomy ),
						'public'				=> true,
					));	
				}
			}
		}

		/******************************** METABOX ***********************************/
		public function get_meta_data_default($field = ''){
			$default = array(
				$this->post_type		=> array(
					'phone'			=> '',
					'email'			=> '',
					'website'		=> '',
					'business_id'	=> '',
					'time_open'		=> '8:00 AM',
					'time_close'	=> '06:00 PM',
					'date'			=> array(
						'monday',
						'tuesday',
						'wednesday',
						'thursday',
						'friday',
					),
					'address'		=> array(
						"lat" => 10.762622, 
						"lng" => 106.660172, 
						"address" => "" 
					),
					'featured' => '',
					'certificate' => '',
				),
			);
			return ($field && isset($default[$field])) ? $default[$field] : $default;
		}

		public function metabox_form(){
			wp_nonce_field( $this->post_type.'_box', $this->post_type.'_box_nonce' );
			$random_id 	= 'wd-'.$this->post_type.'-metabox-'.mt_rand();
			$meta_key 	= $this->post_type;
			$meta_data 	= $this->get_meta_data($meta_key);
			$meta_data 	= empty($meta_data) ? $this->get_meta_data_default($meta_key) : $meta_data;
			$date_option = array(
				'monday' 	=> __( 'Monday', 'wd_package' ),
				'tuesday' 	=> __( 'Tuesday', 'wd_package' ),
				'wednesday' => __( 'Wednesday', 'wd_package' ),
				'thursday' 	=> __( 'Thursday', 'wd_package' ),
				'friday' 	=> __( 'Friday', 'wd_package' ),
				'saturday' 	=> __( 'Saturday', 'wd_package' ),
				'sunday' 	=> __( 'Sunday', 'wd_package' ),
			); ?>
			<table class="form-table wd-<?php echo $this->post_type; ?>-custom-meta-box wd-custom-meta-box-width">
				<tbody>
				<?php
					$this->get_heading_field(array(
						"title" => esc_html__( 'Contact', 'wd_packages' ),
						"desc" => '',
					));
					$this->get_text_field(array(
						"title" => esc_html__( 'Phone', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[phone]",
						"value" => $meta_data['phone'],
						"required" => true,
					));
					$this->get_email_field(array(
						"title" => esc_html__( 'Email', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[email]",
						"value" => $meta_data['email'],
						"required" => true,
					));
					$this->get_url_field(array(
						"title" => esc_html__( 'Website', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[website]",
						"value" => $meta_data['website'],
					));

					$this->get_heading_field(array(
						"title" => esc_html__( 'Business', 'wd_packages' ),
						"desc" => '',
					));
					$this->get_text_field(array(
						"title" => esc_html__( 'Business ID', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[business_id]",
						"value" => $meta_data['business_id'],
						"required" => true,
					));
					$this->get_timepicker_field(array(
						"title" => esc_html__( 'Time Open', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[time_open]",
						"value" => $meta_data['time_open'],
					));
					$this->get_timepicker_field(array(
						"title" => esc_html__( 'Time Close', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[time_close]",
						"value" => $meta_data['time_close'],
					));
					$this->get_checkbox_field(array(
						"title" => esc_html__( 'Date', 'wd_packages' ),
						"desc" => '',
						"field_name" => $this->post_type."[date]",
						"value" => $meta_data['date'],
						"options" => $date_option,
					));
					$this->get_map_field(array(
						"title" => esc_html__( 'Address', 'wd_packages' ),
						"desc" => '',
						"placeholder" => "",
						"field_name" => $this->post_type."[address]",
						"value" => $meta_data['address'],
					));
					$this->get_textarea_field(array(
						"title" => esc_html__( 'Featured', 'wd_packages' ),
						"desc" => esc_html__( 'Enter each field on 1 row', 'wd_packages' ),
						"placeholder" => "",
						"field_name" => $this->post_type."[featured]",
						"value" => $meta_data['featured'],
					));
					$this->get_textarea_field(array(
						"title" => esc_html__( 'Certificate', 'wd_packages' ),
						"desc" => esc_html__( 'Enter each field on 1 row', 'wd_packages' ),
						"placeholder" => "",
						"field_name" => $this->post_type."[certificate]",
						"value" => $meta_data['certificate'],
					)); ?>	
				</tbody>
			</table>
		<?php
		}

		public function metabox_save_data($post_id) {
			if ( ! isset( $_POST[$this->post_type.'_box_nonce'] ) )
				return $post_id;
			// verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times
			if (!wp_verify_nonce($_POST[$this->post_type.'_box_nonce'],$this->post_type.'_box'))
				return $post_id;
			if (!current_user_can('edit_post', $post_id))
				return $post_id;

			$data = array();
			if (isset($_POST[$this->post_type])) {
				$data[$this->post_type] = $_POST[$this->post_type];
			}
			update_post_meta($post_id, $this->post_type.'_meta_data', $data);
		}

		public function delete_post( $post_id ){
			$meta_key = $this->post_type.'_meta_data';
			delete_post_meta($post_id, $meta_key);
		}

		public function process_meta_data_repeatable_field_after_save($meta_key, $list_meta_name){
			$data 	= array();
			if (isset($_POST[$meta_key])) {
				foreach ($list_meta_name as $name) {
					if (count($_POST[$meta_key][$name]) > 0) {
						foreach ($_POST[$meta_key][$name] as $key => $value) {
							$data[$key][$name] = $value;
						}
					}
				}
				//Remove last item (repeatable field)
				unset($data[count($data)-1]);
			}
			return $data;
		}

		public function get_meta_data($field = ''){
			$default = $this->get_meta_data_default();
			$meta_data = get_post_meta( get_the_ID(), $this->post_type.'_meta_data', true );
			$meta_data = ($meta_data) ? wp_parse_args( $meta_data, $default ) : array();
			return ($field && isset($meta_data[$field])) ? $meta_data[$field] : $meta_data;
		}
		
		/******************************** TEMPLATE ***********************************/
		public function template_redirect(){
		}
		
		public function create_metabox() {
			if(post_type_exists($this->post_type)) {
				add_meta_box("wp_cp_".$this->post_type."_info", $this->post_type_single_name." Metadata", array($this,"metabox_form"), $this->post_type, "normal", "high");
			}
		}

		public function change_title_text( $title ){
		    $screen = get_current_screen();
		  
		    if  ( $this->post_type == $screen->post_type ) {
		        $title = sprintf( __( 'Enter the %s name here', 'wd_packages' ), $this->post_type_single_name );
		    }
		    return $title;
		}
		
		public function rename_second_menu_name($safe_text, $text) {
			if (sprintf(__('%s Items', 'wd_packages'), $this->post_type_single_name) !== $text) {
				return $safe_text;
			}
			// We are on the main menu item now. The filter is not needed anymore.
			remove_filter('attribute_escape', array($this,'rename_second_menu_name') );
			return $this->post_type_single_name;
		}

	    protected function init_handle(){
			//add_image_size('wd-'.$this->post_type.'-thumb',400,400,true);  
		}
		
		public function init_admin_script($hook) {
			$screen = get_current_screen();
			if ($hook = 'post.php' && $this->post_type == $screen->post_type) {
			}
		}
		
		public function single_template($single) {
			global $post; 
			if ($post->post_type === $this->post_type && file_exists(WD_PACKAGE_CPT . '/templates/single-content-only.php')) {
				return WD_PACKAGE_CPT . '/templates/single-content-only.php';
			}
			return $single;
		}
		/******************************** Check Visual Composer active ***********************************/
		protected function checkPluginVC(){
			$_active_vc = apply_filters('active_plugins',get_option('active_plugins'));
			if(in_array('js_composer/js_composer.php',$_active_vc)){
				return true;
			}else{
				return false;
			}
		}

	}
	WD_Business_Post_Type::get_instance();  // Start an instance of the plugin class 
}