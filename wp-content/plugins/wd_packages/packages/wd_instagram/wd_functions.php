<?php 
if( !function_exists('wd_vc_get_list_instagram_image_size') ){
	function wd_vc_get_list_instagram_image_size() { 
		return array(
			__( 'Thumbnail - 150x150', 'wd_package' ) 				=> 'thumbnail',
            __( 'Low_resolution - 306x306', 'wd_package' )		 	=> 'low_resolution',
            __( 'Standard_resolution - 612x612', 'wd_package' ) 	=> 'standard_resolution',
        );
	}
} 

if( !function_exists('wd_vc_get_list_instagram_sort_by') ){
	function wd_vc_get_list_instagram_sort_by() { 
		return array(
            __( 'none (default) - As they come from Instagram.', 'wd_package' ) 		=> 'none',
            __( 'most-recent - Newest to oldest.', 'wd_package' ) 						=> 'most-recent',
            __( 'least-recent - Oldest to newest.', 'wd_package' ) 						=> 'least-recent',
            __( 'most-liked - Highest # of likes to lowest.', 'wd_package' ) 			=> 'most-liked',
            __( 'least-liked - Lowest # likes to highest.', 'wd_package' ) 				=> 'least-liked',
            __( 'most-commented - Highest # of comments to lowest.', 'wd_package' ) 	=> 'most-commented',
            __( 'least-commented - Lowest # of comments to highest.', 'wd_package' ) 	=> 'least-commented',
            __( 'random - Random order', 'wd_package' ) 								=> 'random',
        );
	}
} 


if( !function_exists('wd_get_list_instagram_image_size') ){
	function wd_get_list_instagram_image_size() { 
		return array(
			'thumbnail' 			=> __( 'Thumbnail - 150x150', 'wd_package' ),
            'low_resolution' 		=> __( 'Low_resolution - 306x306', 'wd_package' ),
            'standard_resolution' 	=> __( 'Standard_resolution - 612x612', 'wd_package' ),
        );
	}
} 

if( !function_exists('wd_get_list_instagram_sort_by') ){
	function wd_get_list_instagram_sort_by() { 
		return array(
            'none' 				=> __( 'none (default) - As they come from Instagram.', 'wd_package' ),
            'most-recent' 		=> __( 'most-recent - Newest to oldest.', 'wd_package' ),
            'least-recent' 		=> __( 'least-recent - Oldest to newest.', 'wd_package' ),
            'most-liked' 		=> __( 'most-liked - Highest # of likes to lowest.', 'wd_package' ),
            'least-liked' 		=> __( 'least-liked - Lowest # likes to highest.', 'wd_package' ),
            'most-commented' 	=> __( 'most-commented - Highest # of comments to lowest.', 'wd_package' ),
            'least-commented' 	=> __( 'least-commented - Lowest # of comments to highest.', 'wd_package' ),
            'random' 			=> __( 'random - Random order', 'wd_package' ),
        );
	}
} 


if( !function_exists('wd_instagram_check_access_token') ){
	function wd_instagram_check_access_token($user, $access_token) { 
		$ins_data = wp_remote_get( 'https://api.instagram.com/v1/users/'.$user.'/?access_token='.$access_token );

		$status = array(
			'success' 	=> false,
			'username' 	=> '',
			'error' 	=> '',
		);
		if (isset($ins_data['body'])) {
			$ins_data = json_decode($ins_data['body']);
			if (is_object($ins_data)) {
				if ($ins_data->meta->code == 200) {
					$status['success'] 	= true;
					$status['username'] = $ins_data->data->username;
				}else{
					$status['error'] 	= $ins_data->meta->error_message;
				}
			}else{
				$status['error'] 	= __( 'An unknown error. Please check the data you entered again.', 'wd_package' );
			}
			
		}else{
			$status['error'] 	= __( 'An unknown error. Please check the data you entered again.', 'wd_package' );
		}

		//get id by username
		//$ins_data2 = wp_remote_get( 'https://api.instagram.com/v1/users/search?q=1'.$user.'&access_token='.$access_token );
		//var_dump(json_decode($ins_data2['body']));

		return $status;
	}
} 

// Get Data Instagram (old solution)
if(!function_exists ('wd_scrape_instagram')){
	function wd_scrape_instagram( $username ) {

		$username = strtolower( $username );
		$username = str_replace( '@', '', $username );
		if ( false === ( $instagram = get_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ) ) ) ) {

			//$aaa = json_decode( wp_remote_get( "https://www.instagram.com/sontungmtp/media?__a=1" )['body'] ); 
			//var_dump($aaa->items[1]);

			$remote = wp_remote_get( 'http://instagram.com/'.trim( $username ) );
			if ( is_wp_error( $remote ) )
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wd_package' ) );

			if ( 200 != wp_remote_retrieve_response_code( $remote ) )
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wd_package' ) );

			$shards = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], TRUE );

			if ( ! $insta_array )
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wd_package' ) );

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wd_package' ) );
			}

			if ( ! is_array( $images ) )
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wd_package' ) );

			$instagram = array();
			foreach ( $images as $image ) {

				$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
				$image['display_src'] = preg_replace( '/^https?\:/i', '', $image['display_src'] );

				// handle both types of CDN url
				if ( ( strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
					$image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
					$image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
				} else {
					$urlparts = wp_parse_url( $image['thumbnail_src'] );
					$pathparts = explode( '/', $urlparts['path'] );
					array_splice( $pathparts, 3, 0, array( 's160x160' ) );
					$image['thumbnail'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
					$pathparts[3] = 's320x320';
					$image['small'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
				}

				$image['large'] = $image['thumbnail_src'];

				if ( $image['is_video'] == true ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'wd_package' );
				if ( ! empty( $image['caption'] ) ) {
					$caption = $image['caption'];
				}

				$instagram[] = array(
					'description'   => $caption,
					'link'		  	=> trailingslashit( '//instagram.com/p/' . $image['code'] ),
					'time'		  	=> $image['date'],
					'comments'	  	=> $image['comments']['count'],
					'likes'		 	=> $image['likes']['count'],
					'thumbnail'	 	=> $image['thumbnail'],
					'small'			=> $image['small'],
					'large'			=> $image['large'],
					'original'		=> $image['display_src'],
					'type'		  	=> $type
				);
			}

			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = json_encode( serialize( $instagram ) );
				set_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS*2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {

			return unserialize( json_decode( $instagram ) );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wd_package' ) );

		}
	}
}