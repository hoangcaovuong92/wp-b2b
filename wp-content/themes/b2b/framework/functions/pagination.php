<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
 
if (!class_exists('WD_Pagination')) {
	class WD_Pagination {
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

			//echo apply_filters('wd_filter_display_pagination', 3, $query);
 			add_filter( 'wd_filter_display_pagination', array($this, 'display_pagination' ), 10, 2);
		}

		public function get_pagenum_link($pagenum = 1, $escape = true ) {
			global $wp_rewrite;
		
			$pagenum = (int) $pagenum;
		
			$request = remove_query_arg( 'paged' );
		
			$home_root = parse_url(home_url('/'));
			$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
			$home_root = preg_quote( trailingslashit( $home_root ), '|' );
		
			$request = preg_replace('|^'. $home_root . '|', '', $request);
			$request = preg_replace('|^/+|', '', $request);
		
			if ( !$wp_rewrite->using_permalinks() || is_admin() ) {
				$base = trailingslashit( home_url('/') );
		
				if ( $pagenum > 1 ) {
					//$result = add_query_arg( 'paged', $pagenum, $base . $request );
					$result = $base . $request;
				} else {
					$result = $base . $request;
				}
			} else {
				$qs_regex = '|\?.*?$|';
				preg_match( $qs_regex, $request, $qs_match );
		
				if ( !empty( $qs_match[0] ) ) {
					$query_string = $qs_match[0];
					$request = preg_replace( $qs_regex, '', $request );
				} else {
					$query_string = '';
				}
		
				$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request);
				$request = preg_replace( '|^index\.php|', '', $request);
				$request = ltrim($request, '/');
		
				$base = trailingslashit( home_url('/') );
		
				if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) )
					$base .= 'index.php/';
		
				if ( $pagenum > 1 ) {
					$request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
				}
		
				$result = $base . $request . $query_string;
			}
		
			$result = apply_filters('get_pagenum_link', $result);
		
			if ( $escape )
				return esc_url( $result );
			else
				return esc_url_raw( $result );
		}
		
		/*
			Generate pagination.
			Input : 
				- int $num_pages_per_phrase : the number of page per group.
			No output.
		*/
		public function display_pagination($num_pages_per_phrase = 3, $query = null){
			ob_start();
			if(function_exists ('wp_pagenavi')){
				wp_pagenavi() ;			
				return;
			}
			global $wp_query;
			
			$found_posts = $wp_query->found_posts;
			$paged = max( 1, absint( $wp_query->get( 'paged' ) ) );
			$max_num_pages = $wp_query->max_num_pages;
			if( $query != null ){
				$found_posts = $query->found_posts;
				$paged = max( 1, absint( $query->get( 'paged' ) ) );
				$max_num_pages = $query->max_num_pages;
			}

			if( !isset($_GET['page']) ){
				$_GET['page'] = 1;
			}
			if( $found_posts > 0 ): 
				$current_page_request = $this->get_pagenum_link($_GET['page']);
				$current_page_request = str_replace(array('page='.$_GET['page'],'page/'.$_GET['page'], '#038;'),'',$current_page_request);
				
				$term = get_query_var('term');
				$tax = get_query_var('taxonomy');
				$max_page = min(array($max_num_pages, $num_pages_per_phrase));
				if( $max_page <= 0){ 
					$max_page = 1; 
				}
				$phrase = ceil($paged/$max_page);
				$start_page = $max_page*($phrase-1) + 1;
				?>
				<ul class="sub-pagination">
					<?php
					if($paged > 1){
						$first_page_link 	= add_query_arg('paged', 1, $current_page_request);
						$previous_page_link = add_query_arg('paged', ($paged - 1), $current_page_request); ?>
						<li><a class="wd-pagination-item first" href="<?php echo esc_url($first_page_link); ?>"><?php esc_html_e('First', 'feellio'); ?></a></li>
						<li><a class="wd-pagination-item previous" href="<?php echo esc_url($previous_page_link); ?>"><?php esc_html_e('Previous Post', 'feellio'); ?></a></li>
					<?php }
					if($phrase > 1){
						$previous_phrase_link = add_query_arg('paged', ($max_page*($phrase-2) + 1), $current_page_request); ?>
						<li><a class="wd-pagination-item previous-phrase" href="<?php echo esc_url($previous_phrase_link);?>"><?php esc_html_e('...', 'feellio'); ?></a></li>
					<?php } ?>
					<?php
					if( $max_num_pages > 1 ) {
						for($i = 0; $start_page + $i <= min(array($max_num_pages, $start_page + $max_page - 1)); $i++){
							$page_num = $start_page + $i;
							$item_class = 'wd-pagination-item';
							$item_class .= ($i == 0) ? ' first-pager' : '';
							$item_class .= ($page_num == min(array($max_num_pages, $start_page+$max_page-1))) ? ' last-pager' : '';
							$item_class .= ($paged == $page_num) ? ' current' : '';
							if($paged == $page_num){ ?>
								<li><span class="<?php echo esc_attr($item_class); ?>"><?php echo ($page_num); ?></span></li>
							<?php }else{ ?>
								<?php $page_links = add_query_arg('paged', ($page_num), $current_page_request); ?>
								<li><a class="<?php echo esc_attr($item_class); ?>" href="<?php echo esc_url($page_links); ?>"><?php echo ($page_num); ?></a></li>
							<?php 
							}
						}
					}
					if($phrase < ceil($max_num_pages/$max_page)){
						$next_phrase_link = add_query_arg('paged', ($max_page*$phrase + 1), $current_page_request); ?>
						<li><a class="wd-pagination-item next-phrase" href="<?php echo esc_url($next_phrase_link); ?>"><?php esc_html_e('...', 'feellio'); ?></a></li>
					<?php } ?>
					<?php if($paged < $max_num_pages){
							$next_link 	= add_query_arg('paged', ($paged + 1), $current_page_request);
							$last_link = add_query_arg('paged', $max_num_pages, $current_page_request); ?>
							<li><a class="wd-pagination-item next" href="<?php echo esc_url($next_link); ?>"><?php esc_html_e('Next Post', 'feellio'); ?></a></li>
							<li><a class="wd-pagination-item last" href="<?php echo esc_url($last_link); ?>"><?php esc_html_e('Last', 'feellio'); ?></a></li>
					<?php }?>
				</ul>
				<?php
			endif;
			paginate_links(); /* Fix theme check */
			return ob_get_clean();
		}
	}
	WD_Pagination::get_instance();  // Start an instance of the plugin class 
}