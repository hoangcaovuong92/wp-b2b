<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

// Get global ID
if( !function_exists('wd_get_global_post_id') ){
	function wd_get_global_post_id(){
		global $post;
        $current_post = ($current_post) ? $current_post : $post;
        if ($current_post) {
            return $current_post->ID;
        }
	} 
}

// Check Woo
if( !function_exists('wd_is_woocommerce') ){
	function wd_is_woocommerce(){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		return ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) ? false : true;
	} 
}

if( !function_exists('wd_is_visual_composer') ){
	function wd_is_visual_composer(){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		return ( !in_array( "js_composer/js_composer.php", $_actived ) ) ? false : true;
	} 
} 

//Return child folder name array
//$part : all / folder / file
if( !function_exists('wd_scan_folder') ){
	function wd_scan_folder($part = 'all', $path = '') {
		$path    = dirname( __FILE__ ) . $path . '/';
		$files_and_folder = scandir( $path, 1 );

		$return_data = array();
		if (count($files_and_folder) > 0) {
			foreach ( $files_and_folder as $item ) {
				if ( $item === '.' or $item === '..') continue;
				if (is_dir( $path . $item )) {
					$return_data['folder'][] = $item;
				}
				if (is_file( $path . $item ) && file_exists( $path . $item )) {
					$return_data['file'][] = $item;
				}
			}
		}

		return ($part !== 'all' && !empty($return_data[$part])) ? $return_data[$part] : $return_data ;
	}
}

if(!function_exists ('is_windows')){
	function is_windows(){
		$u = $_SERVER['HTTP_USER_AGENT'];
		$window  = (bool)preg_match('/Windows/i', $u );
		return $window;
	}
}

if(!function_exists ('is_chrome')){
	function is_chrome(){
		$u = $_SERVER['HTTP_USER_AGENT'];
		$chrome  = (bool)preg_match('/Chrome/i', $u );
		return $chrome;
	}
}

// Tablet and mobile device detection
// Source : https://mobiforge.com/design-development/tablet-and-mobile-device-detection-php
if(!function_exists ('wd_is_mobile_or_tablet')){
	function wd_is_mobile_or_tablet() {
		$tablet_browser = 0;
		$mobile_browser = 0;
		
		if (wp_is_mobile()) {
			$mobile_browser++;
		}

		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		    $tablet_browser++;
		}
		 
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		    $mobile_browser++;
		}
		 
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		    $mobile_browser++;
		}
		 
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
		    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		    'newt','noki','palm','pana','pant','phil','play','port','prox',
		    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		    'wapr','webc','winw','winw','xda ','xda-');
		 
		if (in_array($mobile_ua,$mobile_agents)) {
		    $mobile_browser++;
		}
		 
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
		    $mobile_browser++;
		    //Check for tablets on opera mini alternative headers
		    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
		      	$tablet_browser++;
		    }
		}

		if ($tablet_browser > 0 || $mobile_browser > 0) {
		   return true;
		}else {
		   return false;
		}  
	}
}

/* Get current URL */
if(!function_exists ('wd_get_current_url')){
	function wd_get_current_url(){ 
		$current_url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$current_url = htmlspecialchars( $current_url, ENT_QUOTES, 'UTF-8' );
		$current_url = explode('?', $current_url);
		return $current_url[0];
	}
} 

if(!function_exists ('wd_wd_get_embbed_daily')){
	function wd_wd_get_embbed_daily($video_url, $width = 940,$height = 558){
		$url_embbed = "http://www.dailymotion.com/swf/".wd_wd_parse_daily_link($video_url);
		return '<object width="'.$width.'" height="'.$height.'">
					<param name="movie" value="'.$url_embbed.'"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowScriptAccess" value="always"></param>
					<embed type="application/x-shockwave-flash" src="'.$url_embbed.'" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always"></embed>
				</object>';
	}
}
if(!function_exists ('wd_wd_get_embbed_vimeo')){
	function wd_wd_get_embbed_vimeo($video_url, $width,$height){
		return '//player.vimeo.com/video/'.wd_wd_parse_vimeo_link($video_url);
	}
}
if(!function_exists ('wd_wd_parse_vimeo_link')){
	function wd_wd_parse_vimeo_link($video_url){
		if (preg_match('~^https://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $match)) {
			return $match[1];
		}
		else{
			return substr($video_url,10,strlen($video_url));
		}
	}
}
if(!function_exists ('wd_wd_parse_youtube_link')){
	function wd_wd_parse_youtube_link($youtube_link){
		preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_link, $match);
		if(count($match) >= 2)
			return $match[1];
	   else
		   return '';
	}
}
if(!function_exists ('wd_get_embbed_video')){
	function wd_get_embbed_video($video_url, $width = 940, $height = 558){
		if(strstr($video_url,'youtube.com') || strstr($video_url,'youtu.be')){
			return "http://www.youtube.com/embed/".wd_wd_parse_youtube_link($video_url)."";
						
		}
		elseif(strstr($video_url,'vimeo.com')){
			return wd_wd_get_embbed_vimeo($video_url, $width, $height);
		}
		elseif(strstr($video_url,'dailymotion.com')){
			return wd_wd_get_embbed_daily($video_url, $width, $height);
		}	
	}
}