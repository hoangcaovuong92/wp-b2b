<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Timezone')) {
	class WD_Timezone extends \DateTimeZone{
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
			
			//$timezone = apply_filters('wd_filter_timestamp', $timezone);
			add_filter( 'wd_filter_timestamp', array($this, 'get_current_timestamp' ), 10, 2);
			//$timezone = apply_filters('wd_filter_time_string', $timestamp, $datetimeFormat);
			add_filter( 'wd_filter_time_string', array($this, 'timestamp_to_time_string' ), 10, 2);
		}

		public function getWpTimezone() {

			$timezone_string = get_option( 'timezone_string' );

			if ( ! empty( $timezone_string ) ) {
				return $timezone_string;
			}

			$offset  = get_option( 'gmt_offset' );
			$hours   = (int) $offset;
			$minutes = ( $offset - floor( $offset ) ) * 60;
			$offset  = sprintf( '%+03d:%02d', $hours, $minutes );
			return $offset;
		}

		public function get_current_timestamp($timezone = '') {
			$timezone = $timezone ? $timezone : $this->getWpTimezone();
			$date = new DateTime(null, new DateTimeZone($timezone));
			return ($date->getTimestamp() + $date->getOffset());
		}
		
		public function timestamp_to_time_string($timestamp = '', $datetimeFormat = 'Y-m-d H:i:s') {
			$timestamp = $timestamp ? $timestamp : $this->get_current_timestamp($timestamp);
			$date = new \DateTime();
			// If you must have use time zones
			// $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
			$date->setTimestamp($timestamp);
			$date->format($datetimeFormat);
			return $date->format($datetimeFormat);
		}
	}
	WD_Timezone::get_instance();  // Start an instance of the plugin class 
}