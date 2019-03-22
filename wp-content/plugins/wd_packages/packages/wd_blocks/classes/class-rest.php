<?php
/**
 * Rest API functions
 *
 * @package wp package
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

if (!class_exists('WD_Rest_API')) {
	class WD_Rest_API extends WP_REST_Controller {
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

        protected $namespace = 'wd-rest/v';
        protected $version   = '1';

        public function __construct() {
            add_action('rest_api_init', array( $this, 'register_routes') );
        }

        /**
         * Register rest routes.
         */
        public function register_routes() {
            $namespace = $this->namespace . $this->version;
            register_rest_route(
                $namespace, '/get_posts/', array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_posts'),
                    'permission_callback' => array( $this, 'get_permission'),
                )
            );
            register_rest_route(
                $namespace, '/get_post_categories/', array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_post_categories'),
                    'permission_callback' => array( $this, 'get_permission'),
                )
            );
            register_rest_route(
                $namespace, '/get_products/', array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_products'),
                    'permission_callback' => array( $this, 'get_permission'),
                )
            );
            register_rest_route(
                $namespace, '/get_product_categories/', array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_product_categories'),
                    'permission_callback' => array( $this, 'get_permission'),
                )
            );
        }

        /**
         * Get permissions.
         *
         * @param WP_REST_Request $request  request object.
         *
         * @return bool
         */
        public function get_permission( WP_REST_Request $request ) {
            return true;
            // if ( current_user_can( 'edit_pages' ) ) {
            //     return true;
            // }
            // return new WP_Error('rad_unauthorized', __('You are not authorized to update this resource.', 'wd_package'), 
            //     array( 'status' => is_user_logged_in() ? 403 : 401 ) );
        }

        /**
         * Get posts
         *
         * @param WP_REST_Request $request  request object.
         *
         * @return mixed
         */
        public function get_posts( WP_REST_Request $request ) {
            //$post_type = $request->get_param('post_type');
            $args = array(  
				'post_type' 		=> 'post',
				'posts_per_page' 	=> -1,
				'order'				=> 'DESC',
				'orderby' 			=> 'date',
				// 'ignore_sticky_posts' => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'paged' 			=> get_query_var('paged')
            );
            
            $data_array = array();
            $data = new WP_Query($args);
            if( $data->have_posts() ){
                while( $data->have_posts() ){
                    $data->the_post();
                    global $post;
                    $data_array[] = array(
                        'value' => $post->ID, 
                        'label' => html_entity_decode( $post->post_title, ENT_QUOTES, 'UTF-8').' (#'.$post->ID.')'
                    );
                }
            }
            wp_reset_postdata();
            
            if (!empty($data_array)) {
                return $this->success( $data_array );
            } else {
                return $this->error('not_found_message', __('Not found!', 'wd_package') );
            }
        }

        /**
         * Get post categories
         *
         * @param WP_REST_Request $request  request object.
         *
         * @return mixed
         */
        public function get_post_categories( WP_REST_Request $request ) {
            //$post_type = $request->get_param('post_type');
            $list_categories = array();
            $list_categories[] = array(
                'value' => -1, 
                'label' => esc_html__('All Category', 'wd_package')
            );

            $args = array(
                'taxonomy' => 'category',
                'hide_empty' 	=> 0,
            );

            $categories = get_terms($args);
            if (!is_wp_error($categories) && is_array($categories) && count($categories) > 0) {
                foreach ($categories as $category ) {
                    $list_categories[] = array(
                        'value' => $category->term_id, 
                        'label' => html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8').' (' . $category->count . ' items)'
                    );
                }
            }

            wp_reset_postdata();

            if (!empty($list_categories)) {
                return $this->success($list_categories);
            } else {
                return $this->error('not_found_message', __('Not found!', 'wd_package') );
            }
        }

        /**
         * Get products
         *
         * @param WP_REST_Request $request  request object.
         *
         * @return mixed
         */
        public function get_products( WP_REST_Request $request ) {
            //$post_type = $request->get_param('post_type');
            $args = array(  
				'post_type' 		=> 'product',  
				'posts_per_page' 	=> -1,
				'order'				=> 'DESC',
				'orderby' 			=> 'date',
				// 'ignore_sticky_posts' => true,
				'update_post_term_cache' => false, 
				'update_post_meta_cache' => false, 
				'paged' 			=> get_query_var('paged')
            );
            
            $data_array = array();
            $data = new WP_Query($args);
            if( $data->have_posts() ){
                while( $data->have_posts() ){
                    $data->the_post();
                    global $post;
                    $data_array[] = array(
                        'value' => $post->ID, 
                        'label' => html_entity_decode( $post->post_title, ENT_QUOTES, 'UTF-8').' ('.$post->ID.')'
                    );
                }
            }
            wp_reset_postdata();
            
            if (!empty($data_array)) {
                return $this->success( $data_array );
            } else {
                return $this->error('not_found_message', __('Not found!', 'wd_package') );
            }
        }

        /**
         * Get product categories
         *
         * @param WP_REST_Request $request  request object.
         *
         * @return mixed
         */
        public function get_product_categories( WP_REST_Request $request ) {
            //$post_type = $request->get_param('post_type');
            $list_categories = array();
            $list_categories[] = array(
                'value' => -1, 
                'label' => esc_html__('All Category', 'wd_package')
            );

            $args = array(
                'taxonomy' => 'product_cat',
                'hide_empty' 	=> 0,
            );

            $condition = (!class_exists('WooCommerce')) ? false : true;
            if ($condition) {
                $categories = get_terms($args);
                if (!is_wp_error($categories) && is_array($categories) && count($categories) > 0) {
                    foreach ($categories as $category ) {
                        $list_categories[] = array(
                            'value' => $category->term_id, 
                            'label' => html_entity_decode( $category->name, ENT_QUOTES, 'UTF-8').' (' . $category->count . ' items)'
                        );
                    }
                }
            }

            wp_reset_postdata();

            if (!empty($list_categories)) {
                return $this->success($list_categories);
            } else {
                return $this->error('not_found_message', __('Not found!', 'wd_package') );
            }
        }

        /**
         * Success rest.
         *
         * @param mixed $response response data.
         * @return mixed
         */
        public function success( $response ) {
            return new WP_REST_Response(
                array(
                    'success' => true,
                    'response' => $response,
                ), 200
            );
        }

        /**
         * Error rest.
         *
         * @param mixed $code     error code.
         * @param mixed $response response data.
         * @return mixed
         */
        public function error( $code, $response ) {
            return new WP_REST_Response(
                array(
                    'error' => true,
                    'success' => false,
                    'error_code' => $code,
                    'response' => $response,
                ), 401
            );
        }
	}
	WD_Rest_API::get_instance();
}