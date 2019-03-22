<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Page_Template')) {
	class WD_Page_Template {
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

        private $post_status_name = 'homepage_template';
        private $post_status_title = 'WD Templates';
        private $template_metabox_key = '_wd_homepage_template';

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
            static::$called = true;
            
            // add_action( 'init', array($this, 'custom_status_creation'));
            // add_action('admin_footer-edit.php', array($this, 'custom_status_add_in_quick_edit'));
            // add_action('admin_footer-post.php', array($this, 'custom_status_add_in_post_page'));
            // add_action('admin_footer-post-new.php', array($this, 'custom_status_add_in_post_page'));
            add_filter('display_post_states', array($this, 'display_archive_state'));

            //add_action('pre_post_update', array($this, 'pre_post_update'), 10, 2);
			add_action('deleted_post', array($this, 'delete_post'));

            //Create home page template
			add_action('wp_ajax_nopriv_create_page_template', array($this, 'create_page_template_ajax'));
            add_action('wp_ajax_create_page_template', array($this, 'create_page_template_ajax'));

            add_action('admin_menu', array($this, 'admin_page_register'));
        }

        //**************************************************************//
		/*							PAGE TEMPLATE						*/
        //**************************************************************//
        public function admin_page_register(){
            add_submenu_page( 
                'wd-package-setting', 
                esc_html__('Demo Pages', 'wd_package'), 
                esc_html__('Demo Pages', 'wd_package'), 
                'manage_options', 
                'wd-page-setting', 
                array($this, 'admin_page_callback')
            );
        }
        
		public function admin_page_callback(){ 
			$templates = $this->homepage_template(); ?>
		    <div class="wrap wd-admin-page-wrap">
				<div class="tab-content card">
					<div id="setting" class="tab-pane fade in active">
                        <?php WD_Packages_Admin_Page::setting_page_tabs(); ?>
						<h3 class="wd-admin-page-heading"><span class="dashicons dashicons-admin-home"></span> <?php esc_html_e("Create or restore the default content of special pages:", 'wd_package'); ?></h3>
						<?php 
						if (is_array($templates) && count($templates)) { ?>
							<div class="wd-table-responsive">
								<table class="table wd-page-admin-page-form">
									<tr valign="top">
										<th><?php esc_html_e("Information", 'wd_package'); ?></th>
										<th><?php esc_html_e("Preview", 'wd_package'); ?></th>
										<th><?php esc_html_e("Is Homepage Template?", 'wd_package'); ?></th>
										<th><?php esc_html_e("Status", 'wd_package'); ?></th>
										<th><?php esc_html_e("Action", 'wd_package'); ?></th>
									</tr>
									<?php foreach ($templates as $name => $data) { 
										$post_id = $this->check_homepage_template_exit($name); 
										$header_template = !empty($data['meta_data']['_wd_custom_header']) ? $data['meta_data']['_wd_custom_header'] : esc_html__("Default Header", 'wd_package');
                                        $footer_template = !empty($data['meta_data']['_wd_custom_footer']) ? $data['meta_data']['_wd_custom_footer'] : esc_html__("Default Footer", 'wd_package');

                                        $editor_list = array(
											'visual_composer' => esc_html__("Visual Composer Editor", 'wd_package'),
											'gutenberg' => esc_html__("Gutenberg Editor", 'wd_package'),
                                        );
                                        
                                        $required_template = array(
                                            'sidebar' => array(),
                                            'banner' => $data['banner'],
                                        );
                                        if (!empty($data['meta_data']['_wd_custom_layout_config']['left_sidebar'])){
                                            $required_template['sidebar'][] = $data['meta_data']['_wd_custom_layout_config']['left_sidebar'];
                                        }
                                        if (!empty($data['meta_data']['_wd_custom_layout_config']['right_sidebar'])){
                                            $required_template['sidebar'][] = $data['meta_data']['_wd_custom_layout_config']['right_sidebar'];
                                        } ?>
										<tr valign="top">
											<td>
												<h3><?php echo esc_html($name); ?></h3>
												<p><?php printf(__('Description: <strong>%s</strong>', 'wd_package'), esc_html($data['desc'])) ?></p>
												<p><?php printf(__('Header: <strong>%s</strong>', 'wd_package'), $header_template) ?></p>
                                                <p><?php printf(__('Footer: <strong>%s</strong>', 'wd_package'), $footer_template) ?></p>
                                                
                                                <?php if (!empty($required_template['sidebar'])) { ?>
                                                    <p><?php printf(__('Required Sidebar: <strong>%s</strong>', 'wd_package'), implode(', ', $required_template['sidebar'])); ?></p>
                                                <?php } ?>

                                                <?php if (!empty($required_template['banner'])) { ?>
                                                    <p><?php printf(__('Required Banner: <strong>%s</strong>', 'wd_package'), implode(', ', $required_template['banner'])); ?></p>
                                                <?php } ?>
											</td>
											<td>
												<img src="<?php echo esc_url($data['thumbnail']); ?>" alt="<?php echo esc_html($data['desc']); ?>">
											</td>
											<td>
												<?php 
												if ($post_id && $post_id == get_option('page_on_front')) {
													echo '<p class="wd-form-notice">'.esc_html__("(Current homepage)", 'wd_package').'</p>';
												} else { ?>
													<p>
														<strong><?php echo ($data['is_homepage']) ? esc_html__("Yes", 'wd_package') : esc_html__("No", 'wd_package'); ?></strong>
													</p>
												<?php } ?>
											</td>
											<td>
												<?php 
												if ($post_id) {
													echo '<p>'.sprintf(__('This page already exists with the name: </br><span class="wd-form-notice">%1$s (#%2$s)</span>', 'wd_package'), get_the_title($post_id), $post_id).'</p>';
													echo '<p><a target="_blank" href="'.get_the_permalink($post_id).'">'.esc_html__("View", 'wd_package').'</a> / <a target="_blank" href="'.get_edit_post_link($post_id).'">'.esc_html__("Edit", 'wd_package').'</a></p>';
												}else{
													echo '<p class="wd-form-desc">'.esc_html__("Does not exist", 'wd_package').'</p>';
												} ?>
											</td>
											<td>
												<p>	
													<?php 
													$count = 1;
													foreach ($editor_list as $key => $value) {
														$checked = $count == 1 ? 'checked="checked"' : '';
														echo '<label><input type="radio" '.$checked.' name="set_editor_action_'.str_replace(' ', '-', $name).'" value="'.$key.'"> '.$value.'</label><br/>';
														$count ++;
													} ?>
												</p>

												<?php 
												if ($data['is_homepage']) {
													echo '<p><input type="checkbox" checked="checked" name="set_at_homepage" value="1"> '.esc_html__("Set at homepage?", 'wd_package').'</p>';
                                                } ?>
                                                <?php 
												if (!empty($required_template['sidebar'])) {
													echo '<p><input type="checkbox" checked="checked" name="create_sidebar" value="1"> '.esc_html__("Create Sidebar Widget?", 'wd_package').'</p>';
                                                } ?>
                                                <?php 
												if (!empty($required_template['banner'])) {
													echo '<p><input type="checkbox" checked="checked" name="create_banner" value="1"> '.esc_html__("Create Banner Template?", 'wd_package').'</p>';
                                                } ?>
												<br/>
                                                <p><a   data-template="<?php echo $name; ?>" 
                                                        data-sidebar-template="<?php echo implode(',', $required_template['sidebar']); ?>"
                                                        data-banner-template="<?php echo implode(',', $required_template['banner']); ?>"
                                                        data-template-exist="<?php echo $this->check_homepage_template_exit($name) ? 'true' : 'false'; ?>" 
                                                        href="" class="button button-primary wd-button-with-loading wd-create-page-template">
														<?php echo $this->check_homepage_template_exit($name) 
																? esc_html__("Restore Page", 'wd_package') 
																: esc_html__("Create Page", 'wd_package'); ?>
														<span class="wd-image-loading">
															<img src="<?php echo WDADMIN_IMAGE.'/loading.gif'; ?>" alt="<?php echo esc_html__("Loading Icon", 'wd_package'); ?>">
														</span>
													</a>
												</p>
											</td>
										</tr>
									<?php } ?>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php
		} //end content admin page


		public function homepage_template($template = 'all'){
            $demo_image_id = apply_filters('wd_filter_demo_image', true);
			$data = array(
				'Home Style 1' => array(
					'desc'      	=> esc_html__( 'Content of homepage 1', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/pages/home1.jpg',
                    'content'       => '[vc_row][vc_column][wd_blog_layout id_category="-1" number_blogs="6" columns="1" columns_tablet="2" columns_mobile="1" grid_list_button="0" pagination_loadmore="loadmore"][/vc_column][/vc_row]',
                    'content_gutenberg' => '<!-- wp:shortcode -->[vc_row][vc_column][wd_blog_layout id_category="-1" number_blogs="6" columns="1" columns_tablet="2" columns_mobile="1" grid_list_button="0" pagination_loadmore="loadmore"][/vc_column][/vc_row]<!-- /wp:shortcode -->',
                    'is_homepage'   => true,
                    'header'        => '', //empty or the filename of 1 file in framework\layout\headers\templates
                    'footer'        => '',
                    'banner'        => array('homepage-1-before-front-page'),
					'meta_data'   	=> array(
                        '_wd_custom_header' => '',
                        '_wd_custom_footer' => '',
                        '_wd_custom_layout_config' => array(
                            'layout' 				=> '0-0-1',
                            'left_sidebar' 			=> '',
                            'right_sidebar' 		=> 'right_sidebar',
                            'style_breadcrumb'		=> '',
                            'image_breadcrumb'		=> '',
                            'custom_class'			=> '',
                            'custom_id'				=> '',
                        ),
                    ),
                ),
                'Home Style 2' => array(
					'desc'      	=> esc_html__( 'Content of homepage 2', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/pages/home2.jpg',
                    'content'       => 'aaa',
                    'content_gutenberg' => '',
                    'is_homepage'   => true,
                    'banner'        => array(),
					'meta_data'   	=> array(
                        '_wd_custom_header' => '',
                        '_wd_custom_footer' => '',
                        '_wd_custom_layout_config' => array(
                            'layout' 				=> '',
                            'left_sidebar' 			=> '',
                            'right_sidebar' 		=> '',
                            'style_breadcrumb'		=> '',
                            'image_breadcrumb'		=> '',
                            'custom_class'			=> '',
                            'custom_id'				=> '',
                        ),
                    ),
                ),
                'About Us' => array(
					'desc'      	=> esc_html__( 'Content of about us page', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/pages/about.jpg',
                    'content'       => '[vc_row][vc_column][wd_title text_align="text-center" display_button="0" title="ABOUT US"][wd_profile image="'.WDADMIN_TEMPLATE_URI.'/images/pages/about-big.jpg'.'" display_logo="0" display_sign="1" sign_image="'.WDADMIN_TEMPLATE_URI.'/images/pages/sign.png'.'"][/vc_column][/vc_row]',
                    'content_gutenberg' => '<!-- wp:shortcode -->[vc_row][vc_column][wd_title text_align="text-center" display_button="0" title="ABOUT US"][wd_profile image="'.WDADMIN_TEMPLATE_URI.'/images/pages/about-big.jpg'.'" display_logo="0" display_sign="1" sign_image="'.WDADMIN_TEMPLATE_URI.'/images/pages/sign.png'.'"][/vc_column][/vc_row]<!-- /wp:shortcode -->',
                    'is_homepage'   => false,
                    'banner'        => array(),
					'meta_data'   	=> array(
                        '_wd_custom_header' => '',
                        '_wd_custom_footer' => '',
                        '_wd_custom_layout_config' => array(
                            'layout' 				=> '',
                            'left_sidebar' 			=> '',
                            'right_sidebar' 		=> '',
                            'style_breadcrumb'		=> '',
                            'image_breadcrumb'		=> '',
                            'custom_class'			=> '',
                            'custom_id'				=> '',
                        ),
                    ),
                ),
                'Contact' => array(
					'desc'      	=> esc_html__( 'Content of contact page', 'wd_package' ),
					'thumbnail'     => WDADMIN_TEMPLATE_URI.'/images/pages/contact.jpg',
                    'content'       => 'a',
                    'content_gutenberg' => '',
                    'is_homepage'   => false,
                    'banner'        => array(),
					'meta_data'   	=> array(
                        '_wd_custom_header' => '',
                        '_wd_custom_footer' => '',
                        '_wd_custom_layout_config' => array(
                            'layout' 				=> '',
                            'left_sidebar' 			=> '',
                            'right_sidebar' 		=> '',
                            'style_breadcrumb'		=> '',
                            'image_breadcrumb'		=> '',
                            'custom_class'			=> '',
                            'custom_id'				=> '',
                        ),
                    ),
                )
            );
            if ($template === 'all') {
                return $data;
            }else if($template !== 'all' && !empty($data[$template])){
                return $data[$template];
            }else{
                return false;
            }
        }

        //Check if homepage template page is created
        //Return true if already exist
        public function check_homepage_template_exit($template = ''){
            $args = array(
                'post_type' => 'page',
                'post_status' => 'publish'
            );
            $posts = new WP_Query( $args );
            $result = false;

            if( $posts->have_posts() ) {
                //$result = $posts->post_count;
                while( $posts->have_posts() ) {
                    $posts->the_post(); global $post;
                    $template_name = get_post_meta($post->ID, $this->template_metabox_key, true);
                    if ($template_name === $template) {
                        $result = $post->ID;
                        break;
                    }
                }
            }
            wp_reset_postdata();
           // var_dump($result);
            return $result;
        }

        //Renew content of homepage template or create new
        //$template : name of template (a part of $this->homepage_template())
        public function create_page_template($template = '', $editor = 'visual_composer', $set_homepage = false){
            $post_id = $this->check_homepage_template_exit($template);
            $template_data = $this->homepage_template($template);
            if (empty($template_data)) return;

            $content = $template_data['content'];
            $content = ($editor === 'gutenberg' && !empty($template_data['content_gutenberg'])) ? $template_data['content_gutenberg'] : $content;

            if ($post_id) {
                $post_id = wp_update_post(array(
                    'ID'            =>  $post_id,
                    'post_status'   => 'publish',
                    'post_content'  => $content,
                ));
            }else{
                $args = array(
                    'post_title'    => $template,
                    'post_status'   => 'publish',
                    'post_content'  => $content,
                    'post_type'     => 'page',
                );
                $post_id = wp_insert_post($args);
            }
            //Update post meta
            if ($post_id) {
                update_post_meta($post_id, $this->template_metabox_key, $template);

                if (!empty($template_data['meta_data'])) {
                    foreach ($template_data['meta_data'] as $key => $value) {
                        update_post_meta($post_id, $key, $value);
                    }
                }
               
                if ($template_data['is_homepage'] && $set_homepage === 'true') {
                    update_option('page_on_front', $post_id);
                    update_option( 'show_on_front', 'page' );
                }
            }
            return $content;
        }

        //**************************************************************//
		/*						        AJAX			                */
		//**************************************************************//
        public function create_page_template_ajax(){
            $template = $_REQUEST['template'];
            $editor = $_REQUEST['editor'];
            $set_homepage = $_REQUEST['set_homepage'];
            $create_sidebar = $_REQUEST['create_sidebar'];
            $create_banner = $_REQUEST['create_banner'];
            $result['template'] = $template;
            $result['editor'] = $editor;
            $result['set_homepage'] = $set_homepage;
            if (!empty($create_sidebar)) {
                $sidebar = WD_Sidebar_Installation::get_instance();
                $create_sidebar = explode(',', $create_sidebar);
                foreach ($create_sidebar as $sidebar_id) {
                    $sidebar->auto_add_sidebar_widgets($sidebar_id, 'replace');
                }
            }
            if (!empty($create_banner)) {
                $banner = WD_Template_Parts::get_instance();
                $create_banner = explode(',', $create_banner);
                foreach ($create_banner as $template) {
                    $banner->create_template_part($template, 'wd_banner');
                }
            }
			if ($template) {
                $result['html'] = $this->create_page_template($template, $editor, $set_homepage);
            }
			wp_send_json_success($result);
			die(); //stop "0" from being output
        }
        
        //**************************************************************//
		/*						NEW PAGE STATUS REGISTER			    */
		//**************************************************************//
        public function custom_status_creation(){
            register_post_status( $this->post_status_name, array(
                'label'                     => _x('WD Templates', 'page', 'wd_package'),
                'label_count'               => _n_noop('WD Templates <span class="count">(%s)</span>', 'WD Templates <span class="count">(%s)</span>', 'wd_package'),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true
            ));
        }
        
        public function display_archive_state( $states ) {
            global $post;
            $template_name = get_post_meta($post->ID, $this->template_metabox_key, true);
            if ($template_name) {
                $states[] = sprintf(esc_html__( '%1$s - %2$s', 'wd_package' ), $this->post_status_title, $template_name);
            }
           return $states;
        }

        public function custom_status_add_in_quick_edit() {
            echo "<script>
            jQuery(document).ready( function() {
                jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"homepage_template\">WD Templates</option>' );      
            }); 
            </script>";
        }
        
        public function custom_status_add_in_post_page() {
            echo "<script>
            jQuery(document).ready( function() {        
                jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"homepage_template\">WD Templates</option>' );
            });
            </script>";
        }

        //**************************************************************//
		/*							PRE POST UPDATE			  			*/
		//**************************************************************//
        public function pre_post_update($post_id){
			// Bail if we're doing an auto save
		    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            remove_action( 'pre_post_update', array( $this, 'pre_post_update' ) );

            $template_name = get_post_meta($post_id, $this->template_metabox_key, true);

            if( $template_name ){
                wp_update_post(array(
                    'ID'            =>  $post_id,
                    'post_status'   => $this->post_status_name,
                ));
            }
            
            add_action( 'pre_post_update', array( $this, 'pre_post_update' ) );
        }
        
        public function delete_post( $post_id ){
			delete_post_meta($post_id, $this->template_metabox_key);
		}
	}
	WD_Page_Template::get_instance();  // Start an instance of the plugin class 
}